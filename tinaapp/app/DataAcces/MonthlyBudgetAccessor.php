<?php

namespace App\DataAcces;
use App\Models\MonthlyBudget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class MonthlyBudgetAccessor
{
    public function getUserMonthlyBudgets(int $userId): Collection
    {
        $today = Carbon::now();
        $startOfMonth = $today->startOfMonth()->format('Y-m-d');
        $endOfMonth = $today->endOfMonth()->format('Y-m-d');

        return MonthlyBudget::where('user_id', $userId)
            ->with(['category' => function ($query) use ($startOfMonth, $endOfMonth, $userId) {
                $query->withSum(['expenses' => function ($query) use ($startOfMonth, $endOfMonth, $userId) {
                    $query->where('user_id', $userId)->whereBetween('date', [$startOfMonth, $endOfMonth]);
                }], 'amount');
            }])
            ->get();
    }

    public function storeBudget(array $data): MonthlyBudget
    {
        return MonthlyBudget::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id']
            ],
            ['Amount' => $data['Amount']]
        );
    }

    public function updateBudget(MonthlyBudget $monthlyBudget, array $data): MonthlyBudget
    {
        $monthlyBudget->update($data);
        return $monthlyBudget;
    }

    public function deleteBudget(MonthlyBudget $monthlyBudget): bool
    {
        return $monthlyBudget->delete();
    }
}

