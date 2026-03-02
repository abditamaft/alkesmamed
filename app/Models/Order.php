<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- INI PENYELAMATNYA
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'invoice_number', 'subtotal', 'shipping_cost', 
        'grand_total', 'status', 'payment_method', 'notes'
    ];
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}