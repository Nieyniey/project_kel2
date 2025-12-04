<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Seller;

class SellerController extends Controller
{
    // public function __construct()
    // {
    //     $this->Middleware('is_seller'); 
    // }

    /**
     * Show the Seller Settings page. 
     * Now accepts an optional tab parameter and checks if the user is a seller.
     */
    public function showSettings(Request $request, $tab = 'store-info')
    {
        // 1. Ensure user is authenticated and is a seller
        $user = Auth::user();
        if (!$user || !$user->seller) {
            // Redirect or show an error if they shouldn't be here
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }
        
        // 2. Prepare data to pass to the view
        $data = [
            'seller' => $user->seller,
            'user' => $user, // Pass the user model for name/photo display
            'activeTab' => $tab, // Pass the active tab for highlighting the navigation link
        ];

        return view('seller.settings', $data);
    }

    /**
     * Handle the update of Store Information (just store_name).
     */
    public function updateStoreInfo(Request $request)
    {
        $seller = Auth::user()->seller;

        // 1. Validation (only for store_name)
        $validated = $request->validate([
            // Enforce store name uniqueness, ignoring the current seller's ID
            'store_name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('sellers')->ignore($seller->seller_id, 'seller_id')
            ],
        ]);

        // 2. Update the Seller model
        $seller->update($validated);

        // NOTE: I am assuming 'seller.settings' is the correct route name for the settings page.
        // You had 'seller.sellerSettings' which is likely a typo.
        return redirect()->route('seller.settings', ['tab' => 'store-info'])->with('success', 'Store Name successfully updated!');
    }

    public function showCreateStore()
    {
        // Only allow non-sellers to access this page
        if (Auth::user()->seller) {
            return redirect()->route('seller.settings')->with('info', 'You already have a store!');
        }
        
        // Pass necessary user data for the navigation panel
        $user = Auth::user(); 
        return view('seller.createStore', compact('user'));
    }

    /**
     * Handle the store registration and creation of the Seller model.
     */
    public function registerStore(Request $request)
    {
        $user = Auth::user();

        // 1. Validation (only for store_name)
        $validated = $request->validate([
            'store_name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('sellers', 'store_name') // Ensure the new store name is unique
            ],
        ]);

        // 2. Create the Seller model
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            // Add other mandatory fields here if your Seller model requires them 
        ]);
        
        // 3. Optional: Add a check if creation failed, then redirect
        if (!$seller) {
            return back()->with('error', 'Failed to create your store. Please try again.');
        }

        // 4. Redirect to the new seller's dashboard or settings page
        return redirect()->route('seller.settings')->with('success', 'Congratulations! Your store has been published.');
    }
}