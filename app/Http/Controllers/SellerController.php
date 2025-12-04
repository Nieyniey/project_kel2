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
     * Show the Seller Settings page with Store Information tab active.
     */
    public function showSettings()
    {
        // Fetch the current seller model instance
        $seller = Auth::user()->seller; 

        return view('seller.settings', compact('seller'));
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

        return redirect()->route('seller.sellerSettings')->with('success', 'Store Name successfully updated!');
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
        // We use $user->id for user_id and the validated store_name.
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            // Add other mandatory fields here if your Seller model requires them 
            // (e.g., initial status, if it's not nullable)
        ]);
        
        // 3. Optional: Add a check if creation failed, then redirect
        if (!$seller) {
            return back()->with('error', 'Failed to create your store. Please try again.');
        }

        // 4. Redirect to the new seller's dashboard or settings page
        return redirect()->route('seller.settings')->with('success', 'Congratulations! Your store has been published.');
    }
}