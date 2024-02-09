<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\DataAcces\ExpenseAccessor;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class ExpenseController extends Controller {
    protected $expenseAccessor;

    public function __construct(ExpenseAccessor $expenseAccessor)
    {
        $this->expenseAccessor = $expenseAccessor;
    }
//    public function index()
//    {
//        $user_id = auth()->user()->id;
//        $expenses = $this->expenseAccessor->getAllUserExpenses($user_id);
//        $categories = ExpenseCategory::all();
//        Log::info('Pagina de cheltuieli accesată.', ['user_id' => $user->id]);
//        $rewards = auth()->user()->rewards;
//
//        return view('pages.home', compact('expenses', 'categories', 'rewards'));
//    }
    public function index()
    {
        $user_id = auth()->user()->id;
        $expenses = $this->expenseAccessor->getAllUserExpenses($user_id);
        $categories = ExpenseCategory::all();
        // Presupunând că rewards este gestionat separat și nu este nevoie să fie modificat aici
        $rewards = auth()->user()->rewards;

        return view('pages.home', compact('expenses', 'categories', 'rewards'));
    }

//    public function index() {
//        $user = auth()->user();
//        $expenses = $user->expenses;
//        $rewards = $user->rewards;
//        $categories = ExpenseCategory::all();
//
//        Log::info('Pagina de cheltuieli accesată.', ['user_id' => $user->id]);
//
//        return view('pages.home', compact('expenses', 'categories', 'rewards'));
//    }

    public function create() {
        $categories = ExpenseCategory::all();

        Log::info('Pagina de creare cheltuială accesată.', ['user_id' => auth()->user()->id]);

        return view('pages.expense.create', compact('categories'));
    }

    public function store(Request $request) {
        // Validează datele din request
        $validatedData = $request->validate([
            'category_id' => 'required',
            'Amount' => 'required|numeric',
            'Date' => 'required',
            'Description' => 'required',
        ]);

        $validatedData = array_merge(['user_id' => auth()->user()->id], $validatedData);

        Expense::create($validatedData);

        Log::info('Cheltuială nouă adăugată.', ['user_id' => auth()->user()->id, 'amount' => $validatedData['Amount']]);

        return redirect()->route('pages.expense.create')->with('success', 'Cheltuiala a fost adaugata!');
    }

    public function edit(Expense $expense) {

        abort_if($expense->user_id !== auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $categories = ExpenseCategory::all();

        Log::info('Pagina de editare cheltuială accesată.', ['user_id' => auth()->user()->id, 'expense_id' => $expense->id]);

        return view('pages.expense.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense) {
        // Valideaza datele din request
        $request->validate([
            'category_id' => 'required',
            'Amount' => 'required|numeric',
            'Date' => 'required',
            'Description' => 'required',
        ]);

        $expense->update([
            'category_id' => $request->category_id,
            'Amount' => $request->Amount,
            'Date' => $request->Date,
            'Description' => $request->Description,
        ]);

        Log::info('Cheltuială actualizată.', ['user_id' => auth()->user()->id, 'expense_id' => $expense->id]);

        return redirect()->route('pages.expense.create')->with('success', 'Cheltuiala a fost modificata!');
    }

    public function destroy(Expense $expense) {
        abort_if($expense->user_id !== auth()->user()->id, Response::HTTP_UNAUTHORIZED);

        $expense->delete();

        Log::info('Cheltuială ștearsă.', ['user_id' => auth()->user()->id, 'expense_id' => $expense->id]);

        session()->flash('success', 'Cheltuiala a fost stearsa!');

        return redirect()->route('pages.index');
    }

}
