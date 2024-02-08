<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseCategoriesController extends Controller
{
    public function index()
    {
    $post = ExpenseCategory::all(); // Retrieve all posts from the database
    return response()->json($post, Response::HTTP_OK); // Return the data as JSON
}

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id'=> 'required',
            'Name' => 'required',
        ]);

        $category = ExpenseCategory::create([
            'id' => $validatedData['id'],
            'Name' => $validatedData['Name'],
        ]);

        return response()->json($category, Response::HTTP_CREATED);
    }
    function getid($CategoryID)
    {
        $category = ExpenseCategory::find($CategoryID);
        if (!$category) {
            return response()->json(['message' => 'Expense Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->get();
        return response()->json($category, Response::HTTP_CREATED);
    }

    public function destroy($CategoryID)
    {
        $category = ExpenseCategory::find($CategoryID);
        if (!$category) {
            return response()->json(['message' => 'Expense Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->delete();
        return response()->json(['message' => 'Expense Category deleted'], Response::HTTP_OK);
    }
}
