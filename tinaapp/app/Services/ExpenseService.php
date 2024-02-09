<?php

namespace App\Services;

use App\DataAcces\ExpenseAccessor;
class ExpenseService{

protected $expenseAccessor;

    public function __construct(ExpenseAccessor $expenseAccessor)
    {
        $this->expenseAccessor = $expenseAccessor;
    }

    public function addExpense(array $data)
    {
        return $this->expenseAccessor->createExpense($data);
    }


}
