<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Validation\Rule; 

class SellerProductController extends Controller
{
    public function index()
    {
        $sellerId = Auth::user()->seller->seller_id;
        
        $products = Product::where('seller_id', $sellerId)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('seller.sellerProducts', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.sellerAddProducts', compact('categories')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => ['required', 'integer', Rule::exists('categories', 'category_id')], 
        ]);

        $seller = Auth::user()->seller; 

        $imagePath = null;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        Product::create(array_merge($validated, [
            'seller_id' => $seller->seller_id,
            'image_path' => $imagePath,
        ]));

        return redirect()->route('seller.products')->with('success', 'Produk berhasil ditambahkan dan siap dijual!');
    }

    public function edit(Product $product)
    {
        $this->authorizeProductOwnership($product); 

        $categories = Category::all();

        return view('seller.sellerEditProducts', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProductOwnership($product);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = $validated;

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $updateData['image_path'] = $imagePath;
        }

        $product->update($updateData);

        return redirect()->route('seller.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProductOwnership($product);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('seller.products')->with('success', 'Produk berhasil dihapus dari daftar jual.');
    }

    protected function authorizeProductOwnership(Product $product)
    {
        $currentSellerId = Auth::user()->seller->seller_id ?? null;
        if ($product->seller_id != $currentSellerId) {
            abort(403, 'Akses Tidak Diizinkan. Produk ini bukan milik Anda.');
        }
    }
}