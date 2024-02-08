<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;
use App\Models\MonthlyBudget;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();
        $categories = ExpenseCategory::all();
        return view('pages.home', compact('expenses', 'categories'));
    }

    public function create()
    {
        $users = User::all();
        $categories = ExpenseCategory::all();
        return view('pages.expense.create', compact('users'));
    }

        public function store(Request $request)
    {
        // Validează datele din request
        $validatedData = $request->validate([
            'user_id' => 'required',
            'category_id' => 'required', // Asigurați-vă că category_id este inclus aici
            'Amount' => 'required',
            'Date' => 'required',
            'Description' => 'required',
        ]);

        // Salvăm noutatea în baza de date
        $expense = Expense::create($validatedData);

        // Redirectează către pagina cu lista de cheltuieli
        return redirect()->route('pages.expense.create');
    }

    public function edit(Expense $expense)
    {
        $users = User::all();
        $categories = ExpenseCategory::all();
        // Afiseaza formularul pentru editarea unei cheltuieli existente
        return view('pages.expense.edit', compact('expense', 'users', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Valideaza datele din request
        $request->validate([
            'user_id' => 'required',
            'category_id' => 'required',
            'Amount' => 'required',
            'Date' => 'required',
            'Description' => 'required',
        ]);

        // Actualizeaza cheltuiala existenta in baza de date
        $expense->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'Amount' => $request->Amount,
            'Date' => $request->Date,
            'Description' => $request->Description,
        ]);

        // Redirecteaza catre pagina cu lista de cheltuieli
        return redirect()->route('pages.expense.create');
    }

    public function destroy(Expense $expense)
    {
        // Șterge cheltuiala din baza de date
        $expense->delete();

        // Preia cheltuielile și categoriile pentru a le afișa din nou pe aceeași pagină
        $expenses = Expense::all();
        $categories = ExpenseCategory::all();

        // Returnează aceeași pagină cu datele actualizate și mesajul de succes
        return view('pages.home', compact('expenses', 'categories'))->with('success', 'Cheltuiala a fost ștearsă cu succes!');
    }
}
