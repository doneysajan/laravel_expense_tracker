<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'cid',
        'amount',
        'date',
        'payment',
        'maincategory',
        'merchantname',
        'notes',
        'receipt'
    ];
}