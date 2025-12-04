<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Seller; // Ensure this is imported

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
}