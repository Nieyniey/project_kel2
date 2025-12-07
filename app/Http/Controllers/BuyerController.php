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


    public function settings(Request $request)
    {
        $user = Auth::user();
        $isSeller = $user->seller()->exists();

        // ⬅ INI FIX-nya!!
        $activeTab = $request->query('tab', 'personal-info');

        $data = [
            'user' => $user,
            'isSeller' => $isSeller,
            'activeTab' => $activeTab,
        ];

        if ($activeTab === 'orders') {
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
            'name' => 'required|string|max:255', 
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20', 
            'DOB' => 'nullable|date_format:Y-m-d', 
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $profilePhotoPath = $request->file('profile_photo')->store('users', 'public');
            
            $validated['profile_photo'] = $profilePhotoPath;
        }

        $user->update($validated);

        return redirect()->route('buyer.settings')->with('success', 'Personal Information successfully updated!');
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

        return view('buyer.keranjang.buyerKeranjang', compact('cartItems'));
    }

    public function changeAddressPage($order_id)
    {
        return view('buyer.keranjang.changeaddress', compact('order_id'));
    }

    // -------------------------------
    // SAVE NEW ADDRESS
    // -------------------------------
    public function saveAddress(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'address'  => 'required|string',
        ]);

        // update or create address
        Address::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'label'       => 'Default',
                'address_text'=> $request->address,
                'city'        => '-',
                'postal_code' => '-',
                'is_default'  => 1,
            ]
        );

        // redirect ke payment page
        return redirect()
            ->route('payment.page', $request->order_id)
            ->with('success', 'Address updated!');
    }


}
