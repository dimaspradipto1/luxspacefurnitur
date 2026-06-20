<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'users_id',
        'name',
        'email',
        'phone',
        'address',
        'courier',
        'payment',
        'payment_url',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transactions_id');
    }
}
