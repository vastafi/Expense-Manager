<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'Expenses';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'category_id',
        'Amount',
        'Date',
        'Description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'category_id');
    }
}
