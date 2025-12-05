<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'DOB',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the seller record associated with the user.
     * user_id di tabel 'sellers' adalah foreign key yang mengarah ke tabel 'users'.
     */
    public function seller(): HasOne
    {
        // 1. Relasi: HasOne (Satu User memiliki maksimal Satu Seller)
        // 2. Model: Seller::class (Model tujuan)
        // 3. Foreign Key: 'user_id' (Kolom FK di tabel 'sellers')
        // 4. Local Key: 'id' (Primary key di tabel 'users')
        return $this->hasOne(Seller::class, 'user_id', 'id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function inCart(int $productId): bool
    {
        // Check if the user's cart header exists, and if so, check its items.
        return $this->carts
                    ?->items() // Accesses the items() method on the Cart model
                    ->where('product_id', $productId)
                    ->exists() ?? false; // Safely check if the cart exists
    }

    public function inWishlist(int $productId): bool
    {
        // Check if the user's wishlist header exists, and if so, check its items.
        return $this->wishlist
                    ?->items() // Accesses the items() method on the Wishlist model
                    ->where('product_id', $productId)
                    ->exists() ?? false; // Safely check if the wishlist exists
    }
}