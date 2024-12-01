<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = [

        'supplier_name',
        'supplier_address',
        'phone',
        'comment',
        'producer',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
