<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'price'];

    public function getPrice(): string
    {
        return '$ ' . round($this->price / 100, 2);
    }
}
