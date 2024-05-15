<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'money_type_id',
        'user_id',
        'payment_type_id',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function money_type()
    {
        return $this->belongsTo(MoneyType::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
