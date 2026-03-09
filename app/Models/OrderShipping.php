<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use HasFactory;

    // REVISI: Tambahkan courier_name & tracking_number agar tidak diblokir satpam Laravel
    protected $fillable = [
        'order_id', 'city_id', 'recipient_name', 'phone', 'full_address',
        'courier_name', 'tracking_number' 
    ];

    // TAMBAHAN: Relasi ke City (karena di view kita panggil $order->shipping->city)
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Relasi balik ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}