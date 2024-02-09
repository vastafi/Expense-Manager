<?php

namespace App\DataAcces;


use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class ExpenseAccessor
{
    public function getAllUserExpenses(int $userId): Collection
    {
        return Expense::where('user_id', $userId)->get();
    }

    public function createExpense(array $data): Expense
    {
        return Expense::create($data);
    }

    public function updateExpense(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense;
    }

    public function deleteExpense(Expense $expense): bool
    {
        return $expense->delete();
    }
}

