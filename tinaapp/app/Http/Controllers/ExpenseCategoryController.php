<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        Log::info('Vizualizare listă categorii cheltuieli.');
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        Log::info('Accesat pagina de creare categorie cheltuieli.');
        return view('pages.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        ExpenseCategory::create($request->all());
        Log::info('Categorie cheltuieli creată.', ['Name' => $request->Name]);


        return redirect()->route('pages.category.store')->with('success', 'Categoria a fost creata cu succes.');
    }

    public function edit(ExpenseCategory $category)
    {
        Log::info('Accesat pagina de editare categorie cheltuieli.', ['category_id' => $category->id]);
        return view('pages.category.edit', compact('category'));
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        $category->update($request->all());
        Log::info('Categoria cheltuieli actualizată.', ['category_id' => $category->id]);

        return redirect()->route('pages.category.edit', ['category' => $category])->with('success', 'Categoria a fost actualizată.');
    }
    public function destroy(ExpenseCategory $category)
    {
        // Verifică dacă există înregistrări în tabela 'expenses' care fac referire la categoria $category
        if ($category->expenses->count() > 0) {
            Log::warning('Încercare eșuată de a șterge categoria de cheltuieli cu înregistrări asociate.', ['category_id' => $category->id]);
            session()->flash('error', 'Nu poți șterge această categorie deoarece are înregistrări asociate în tabela "expenses".');
            return redirect()->back();
        }

        $category->delete();
        Log::info('Categoria cheltuieli ștearsă.', ['category_id' => $category->id]);

        session()->flash('success', 'Categoria a fost ștearsă cu succes.');
        return redirect()->route('pages.category.create');
    }
}
