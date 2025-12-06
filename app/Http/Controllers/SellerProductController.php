<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Validation\Rule; //untuk validation

class SellerProductController extends Controller
{
    // public function __construct()
    // {
    //     // Gerbang Pertama: Memastikan hanya user yang sudah login DAN memiliki relasi 'seller' yang boleh masuk.
    //     $this->middleware('is_seller'); 
    // }

    /**
     * Tampilkan daftar semua produk yang dijual oleh seller yang sedang login.
     */
    public function index()
    {
        
        // Sekarang kita bisa langsung ambil ID seller karena kita yakin objek 'seller' itu ada
        $sellerId = Auth::user()->seller->seller_id;
        
        // Ambil semua produk milik seller ini
        $products = Product::where('seller_id', $sellerId)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // Anda mengganti view name di sini
        return view('seller.sellerProducts', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.sellerAddProducts', compact('categories')); 
    }

    public function store(Request $request)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // ADDED validation for category_id, ensuring it exists in the categories table
            'category_id' => ['required', 'integer', Rule::exists('categories', 'category_id')], 
        ]);

        $seller = Auth::user()->seller; 

        $imagePath = null;
        
        // 2. Proses Upload Gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        // 3. Buat produk baru
        // category_id is now correctly merged from $validated data
        Product::create(array_merge($validated, [
            'seller_id' => $seller->seller_id,
            'image_path' => $imagePath,
            // 'category_id' is already included in $validated
        ]));

        return redirect()->route('seller.products')->with('success', 'Produk berhasil ditambahkan dan siap dijual!');
    }

    /**
     * Tampilkan form untuk mengedit produk spesifik.
     */
    public function edit(Product $product)
    {
        // 1. Authorize ownership check
        $this->authorizeProductOwnership($product); 

        // 2. Fetch all categories needed for the dropdown
        $categories = Category::all(); // <--- ADD THIS LINE

        // 3. Pass both the product and the categories to the view
        return view('seller.sellerEditProducts', compact('product', 'categories'));
    }

    /**
     * Perbarui produk spesifik di database.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProductOwnership($product);

        // 1. Validasi data
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

        // 3. Perbarui data produk
        $product->update($updateData);

        return redirect()->route('seller.products')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk spesifik dari database (termasuk file gambarnya).
     */
    public function destroy(Product $product)
    {
        // Cek kepemilikan
        $this->authorizeProductOwnership($product);

        // Hapus file gambar dari storage
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        // Hapus produk dari database
        $product->delete();

        return redirect()->route('seller.products')->with('success', 'Produk berhasil dihapus dari daftar jual.');
    }

    /**
     * Fungsi helper untuk memastikan produk milik seller yang login
     */
    protected function authorizeProductOwnership(Product $product)
    {
        $currentSellerId = Auth::user()->seller->seller_id ?? null;
        
        // Jika seller_id produk tidak sama dengan seller_id user yang login
        if ($product->seller_id != $currentSellerId) {
            // Menghentikan eksekusi dan menampilkan 403 Forbidden
            abort(403, 'Akses Tidak Diizinkan. Produk ini bukan milik Anda.');
        }
    }
}