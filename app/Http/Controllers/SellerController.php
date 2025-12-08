<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Seller;
use App\Models\User;
use App\Models\Product;

class SellerController extends Controller
{
    public function showSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->seller) {
            return redirect()->route('home')->with('error', 'Anda belom registrasi sebagai seller.');
        }
        
        $activeTab = $request->query('tab', 'store-info'); 

        $data = [
            'seller' => $user->seller,
            'user' => $user, 
            'activeTab' => $activeTab, 
        ];

        return view('seller.sellerSettings', $data);
    }

    public function updateStoreInfo(Request $request)
    {
        if (!Auth::check() || !Auth::user()->seller) {
            return redirect()->route('home')->with('error', 'Authentication required.');
        }
        
        $seller = Auth::user()->seller;

        $validated = $request->validate([
            'store_name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('sellers', 'store_name')->ignore($seller->id)
            ],
            'description' => 'nullable|string|max:500', 
            'instagram' => 'nullable|string|max:100',
                        
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $seller->update($validated);

        return redirect()->route('seller.settings', ['tab' => 'store-info'])
            ->with('success', 'Informasi toko terubah dan tersimpan!');
    }

    public function showCreateStore()
    {
        if (Auth::user()->seller) {
            return redirect()->route('seller.settings')->with('info', 'Anda sudah punya toko!');
        }
        
        $user = Auth::user(); 
        return view('seller.createStore', compact('user'));
    }

    public function registerStore(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:sellers,store_name',
            'description' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        if ($user->seller()->exists()) {
            return redirect()->route('seller.settings')->with('error', 'Anda sudah punya toko.');
        }

        $user->seller()->create([
            'store_name' => $validated['store_name'],
            'description' => $validated['description'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'status' => 'active',
        ]);
        
        return redirect()->route('seller.settings')->with('success', 'Tokomu berhasil dibuat!');
    }

    public function index($id)
    {
        $seller = Seller::findOrFail($id);
        
        $products = Product::where('seller_id', $seller->seller_id)
                        ->latest() 
                        ->get();

        return view('seller.sellerProfile', compact('seller', 'products'));
    }
}