<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\Expense;
class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('pages.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        ExpenseCategory::create($request->all());

        return redirect()->route('pages.category.store')->with('success', 'Categoria a fost creata cu succes.');
    }

    public function edit(ExpenseCategory $category)
    {
        return view('pages.category.edit', compact('category'));
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('pages.category.edit', ['category' => $category])->with('success', 'Categoria a fost actualizată.');
    }
    public function destroy(ExpenseCategory $category)
    {
        $category->delete();

        return redirect()->route('pages.category.destroy', ['category' => $category])->with('success', 'Categoria a fost actualizată.');
    }
}
