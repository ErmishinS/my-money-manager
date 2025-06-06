<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\BelongsToUserScope;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'payment_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new BelongsToUserScope);
    }
}
