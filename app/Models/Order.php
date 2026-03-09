<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'invoice_number', 'subtotal', 'shipping_cost', 
        'grand_total', 'status', 'payment_method', 'notes'
    ];

    // REVISI: Ubah nama dari orderItems() menjadi items() agar nyambung dengan Controller
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // 👇 TAMBAHKAN INI KEMBALI UNTUK MENYELAMATKAN CHECKOUT CUSTOMER 👇
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // TAMBAHAN: Relasi ke tabel OrderShipping
    public function shipping()
    {
        return $this->hasOne(OrderShipping::class);
    }

    // TAMBAHAN: Relasi ke pembeli (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}