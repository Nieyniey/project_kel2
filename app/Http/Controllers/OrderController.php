<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Important: Added DB facade

class OrderController extends Controller
{
    /**
     * Handles placing an order from selected cart items.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function place(Request $request)
    {
        // 1. Validation
        $request->validate([
            // Assuming the selected_items JSON string is passed
            'selected_items' => 'required|string'
        ]);

        $selectedItemsData = json_decode($request->selected_items, true);

        if (!$selectedItemsData || count($selectedItemsData) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $totalPrice = 0;
        $order = null;

        // 2. Wrap logic in a Database Transaction for safety and atomicity
        try {
            DB::beginTransaction();

            // 3. Create initial order record
            $order = Order::create([
                'user_id' => Auth::id(),
                // NOTE: 'address_id' => 1 is hardcoded. Use user's selected address ID here.
                'address_id' => 1, 
                'total_price' => 0, // Will be updated later
                'status' => 'pending'
            ]);

            // Prepare for Order Item creation
            $orderItemsToCreate = [];
            $cartItemsToDelete = [];
            
            // Collect Product IDs to lock them for stock safety
            $productIds = collect($selectedItemsData)->pluck('id')->toArray();
            
            // Lock the necessary CartItems and related Products (Optimistic Locking)
            $cartItems = CartItem::whereIn('id', $productIds)
                                 ->with('product')
                                 ->lockForUpdate() // Locks rows in the database
                                 ->get();
            
            // Map CartItem ID to the full CartItem object
            $cartItemMap = $cartItems->keyBy('id');

            // 4. Process each selected item
            foreach ($selectedItemsData as $s) {
                // Ensure the selected item ID and qty are present and valid
                if (!isset($s['id']) || !isset($s['qty'])) {
                    continue; 
                }

                $cartItemId = $s['id'];
                $qty = (int)$s['qty'];

                $item = $cartItemMap->get($cartItemId);

                if (!$item) continue;

                $product = $item->product;

                // 5. Check Stock and handle failure (before modification)
                if ($qty > $product->stock) {
                    // Rollback all changes if stock check fails
                    DB::rollBack();
                    return back()->with('error', 'Stock tidak cukup untuk: ' . $product->name);
                }

                // 6. Prepare Order Item and Update Totals
                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;

                $orderItemsToCreate[] = [
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'qty' => $qty,
                    'price_per_item' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 7. Reduce Stock (Product update prepared outside the loop if using mass update)
                // For simplicity and safety with lockForUpdate, we update it in the loop
                $product->stock -= $qty;
                $product->save();
                
                // 8. Mark Cart Item for deletion
                $cartItemsToDelete[] = $cartItemId;
            }

            // 9. Mass insert Order Items
            OrderItem::insert($orderItemsToCreate);

            // 10. Finalize Order Total and save
            $shippingCost = 10000;
            $order->total_price = $totalPrice + $shippingCost;
            $order->save();

            // 11. Delete processed Cart Items
            CartItem::destroy($cartItemsToDelete);

            // 12. Commit Transaction
            DB::commit();

        } catch (\Exception $e) {
            // Log the error and rollback the transaction if anything failed
            DB::rollBack();
            // Optional: Log error $e->getMessage()

            // Delete the partially created order if it exists (optional safety net)
            if ($order) {
                $order->delete();
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }

        // 13. Redirect
        return redirect()->route('payment.page', $order->order_id);
    }
    
    // ... (rest of the controller methods remain unchanged) ...
    public function trackList()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('buyer.track.track-list', compact('orders'));
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }
        
        // Revert stock for cancelled items
        DB::transaction(function () use ($order) {
            $order->status = 'cancelled';
            $order->save();

            // Increment product stock for each item in the cancelled order
            foreach ($order->items as $item) {
                // Using increment method to prevent race condition on stock
                Product::where('product_id', $item->product_id)->increment('stock', $item->qty);
            }
        });

        return back()->with('success', 'Order #' . $order->order_id . ' cancelled and stock reverted.');
    }

    public function completeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'paid') {
            return back()->with('error', 'Order belum dibayar.');
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Order #' . $order->order_id . ' completed.');
    }

    public function deleteOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Not allowed.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'hanya pesanan yang selesai atau dibatalkan yang dapat dihapus.');
        }

        $order->items()->delete();
        $order->delete();

        return back()->with('berhasil', 'Pesanan berhasil dihapus.');
    }
}