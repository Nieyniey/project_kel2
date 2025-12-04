<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Address;

class BuyerController extends Controller
{
    public function home()
    {
        // Example dummy products (replace with DB later)
        $products = [
            (object)[
                'id' => 1,
                'name' => 'Black Knit Sweater',
                'price' => 120000,
                'image' => '/images/sample1.png'
            ],
            (object)[
                'id' => 2,
                'name' => 'Brown Vintage Jacket',
                'price' => 250000,
                'image' => '/images/sample2.png'
            ],
            (object)[
                'id' => 3,
                'name' => 'White Canvas Bag',
                'price' => 90000,
                'image' => '/images/sample3.png'
            ],
        ];

        return view('buyer.buyerHome', compact('products'));
    }


    public function settings(Request $request, $tab = 'personal-info')
    {
        $user = Auth::user();
        $isSeller = $user->seller()->exists();

        $data = [
            'user' => $user,
            'isSeller' => $isSeller,
            'activeTab' => $tab,
        ];

        if ($tab === 'orders') {
            $orders = Order::where('user_id', $user->id)
                           ->orderBy('created_at', 'desc') 
                           ->with('items.product.seller') 
                           ->get();
                           
            $data['orders'] = $orders;
        }

        return view('buyer.buyerSettings', $data);
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // 'name' is used as 'Username' in the form
            'name' => 'required|string|max:255', 
            // Email must be unique, ignoring the current user's ID
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20', 
            // DOB field from the image (assuming date format is DD/MM/YYYY)
            'DOB' => 'nullable|date_format:d/m/Y', 
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female'])], // Assuming a 'gender' field exists or can be added
        ]);

        if (isset($validated['DOB'])) {
            $validated['DOB'] = Carbon::createFromFormat('d/m/Y', $validated['DOB'])->format('Y-m-d');
        }

        $user->update($validated);

        return redirect()->route('buyer.settings')->with('success', 'Personal Information successfully updated!');
    }


    public function favorites()
    {
        // Example dummy favorites
        $favorites = [
            [
                'id' => 10,
                'name' => 'Vintage Boots',
                'price' => 150000,
                'image' => '/images/boots.png'
            ]
        ];

        return view('buyer.buyerFavorites', compact('favorites'));
    }


    public function chat()
    {
        // Dummy chat list
        $chats = [
            [
                'id' => 1,
                'name' => 'Seller A',
                'last_message' => 'Product masih ada kak?',
                'time' => '14:20',
                'image' => '/images/profile1.png'
            ],
            [
                'id' => 2,
                'name' => 'Seller B',
                'last_message' => 'Boleh nego ya kak',
                'time' => 'Yesterday',
                'image' => '/images/profile2.png'
            ],
        ];

        return view('buyer.buyerChat', compact('chats'));
    }


    public function cart()
    {
        // Dummy cart
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Black Knit Sweater',
                'price' => 120000,
                'qty' => 1,
                'image' => '/images/sample1.png'
            ],
            [
                'id' => 2,
                'name' => 'Vintage Jacket',
                'price' => 250000,
                'qty' => 1,
                'image' => '/images/sample2.png'
            ],
        ];

        return view('buyer.buyerKeranjang', compact('cartItems'));
    }

    public function changeAddressPage()
    {
        $address = Address::where('user_id', auth()->id())
                    ->where('is_default', 1)
                    ->first();

        return view('buyer.keranjang.changeaddress', compact('address'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'address_text' => 'required',
            'city'         => 'required',
            'postal_code'  => 'required'
        ]);

        // Hapus default sebelumnya
        Address::where('user_id', auth()->id())->update(['is_default' => 0]);

        // Simpan alamat baru
        Address::create([
            'user_id'     => auth()->id(),
            'label'       => 'Default Address',
            'address'     => $request->address_text,
            'city'        => $request->city,
            'postal_code' => $request->postal_code,
            'is_default'  => 1
        ]);

        return redirect()->route('payment.page', session('last_order_id'))
                        ->with('success', 'Address updated');
    }
}
