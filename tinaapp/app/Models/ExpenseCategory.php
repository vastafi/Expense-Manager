<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'expense_categories';
    public $timestamps = false;

    protected $fillable = [
        'Name',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id', 'category_id');
    }
}
