<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBudget extends Model
{
    protected $fillable = ['id', 'user_id','category_id ','Amount', 'created_at', 'updated_at'];
}
