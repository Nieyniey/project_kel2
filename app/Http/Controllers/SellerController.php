<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Seller;

class SellerController extends Controller
{
    // ... (rest of your class properties) ...

    /**
     * Show the Seller Settings page. 
     * Reads the active tab from the request query parameter.
     */
    public function showSettings(Request $request)
    {
        // 1. Ensure user is authenticated and is a seller
        $user = Auth::user();
        if (!$user || !$user->seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }
        
        // --- CHANGE 1: Get tab from query parameter (e.g., ?tab=orders) ---
        $activeTab = $request->query('tab', 'store-info'); 
        
        // 2. Prepare data to pass to the view
        $data = [
            'seller' => $user->seller,
            'user' => $user, // Pass the user model for name/photo display
            'activeTab' => $activeTab, // Pass the active tab for highlighting the navigation link
        ];

        return view('seller.sellerSettings', $data);
    }

    /**
     * Handle the update of Store Information (store_name, description, instagram).
     */
    public function updateStoreInfo(Request $request)
    {
        // Ensure the user is authenticated and has a seller record
        if (!Auth::check() || !Auth::user()->seller) {
            return redirect()->route('home')->with('error', 'Authentication required.');
        }
        
        $seller = Auth::user()->seller;

        $validated = $request->validate([
            'store_name' => [
                'required', 
                'string', 
                'max:100', 
                // Ignore the current seller's ID for uniqueness check
                Rule::unique('sellers', 'store_name')->ignore($seller->id)
            ],
            'description' => 'nullable|string|max:500', 
            'instagram' => 'nullable|string|max:100',
            
            // --- NEW: Validation for Status ---
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        // Update the Seller model with all validated data
        $seller->update($validated);

        return redirect()->route('seller.settings', ['tab' => 'store-info'])
            ->with('success', 'Store Information successfully updated!');
    }

    public function showCreateStore()
    {
        // ... (No change required here) ...
        if (Auth::user()->seller) {
            return redirect()->route('seller.settings')->with('info', 'You already have a store!');
        }
        
        $user = Auth::user(); 
        return view('seller.createStore', compact('user'));
    }

    public function registerStore(Request $request)
    {
        // ... (No change required here as this was updated in previous steps) ...
        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:sellers,store_name',
            'description' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        if ($user->seller()->exists()) {
            return redirect()->route('seller.settings')->with('error', 'You already have a store.');
        }

        $user->seller()->create([
            'store_name' => $validated['store_name'],
            'description' => $validated['description'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'status' => 'active',
        ]);
        
        return redirect()->route('seller.settings')->with('success', 'Your store has been successfully created!');
    }
}