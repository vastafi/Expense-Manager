<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyBudget;
use App\Models\User;
use App\Models\ExpenseCategory;
use App\Models\Expense;

class MonthlyBudgetController extends Controller
{
    // Metoda pentru afișarea tuturor limitelor bugetare lunare
    public function index()
    {
        $expenses = Expense::all();
        $monthlyBudgets = MonthlyBudget::all();
        $users = User::all();
        $categories = ExpenseCategory::all();
        return view('pages.monthly.index', compact('monthlyBudgets','users', 'categories','expenses'));
    }

    // Metoda pentru afișarea formularului de creare a unei noi limite bugetare lunare
    public function create()
    {
        $users = User::all();
        $categories = ExpenseCategory::all();
        return view('pages.monthly.create', compact('users', 'categories'));
    }

    // Metoda pentru salvarea noii limite bugetare lunare în baza de date
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'category_id' => 'required',
            'Amount' => 'required',
        ]);

        MonthlyBudget::create($request->all());

        return redirect()->route('pages.monthly.create')
            ->with('success', 'Monthly budget created successfully.');
    }

    // Metoda pentru afișarea unei limite bugetare lunare specifice pentru editare
    public function edit(MonthlyBudget $monthlyBudget)
    {
        return view('monthly_budgets.edit', compact('monthlyBudget'));
    }

    // Metoda pentru actualizarea limitei bugetare lunare în baza de date
    public function update(Request $request, MonthlyBudget $monthlyBudget)
    {
        $request->validate([
            'monthly_limit' => 'required',
        ]);

        $monthlyBudget->update($request->all());

        return redirect()->route('monthly_budgets.index')
            ->with('success', 'Monthly budget updated successfully');
    }

    // Metoda pentru ștergerea unei limite bugetare lunare
    public function destroy(MonthlyBudget $monthlyBudget)
    {
        $monthlyBudget->delete();

        return redirect()->route('monthly_budgets.index')
            ->with('success', 'Monthly budget deleted successfully');
    }
}
