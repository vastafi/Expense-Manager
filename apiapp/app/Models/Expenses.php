<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = ['ID','UserID', 'CategoryID', 'Amount' ,'Date', 'Description','created_at','updated_at'];
}
