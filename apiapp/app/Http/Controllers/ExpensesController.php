<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Expenses;

class ExpensesController extends Controller
{
    public function index()
    {
        $post = Expenses::all(); // Retrieve all posts from the database
        return response()->json($post, Response::HTTP_OK); // Return the data as JSON
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id'  => 'required',
            'user_id '=> 'required',
            'category_id' => 'required',
            'Amount'=> 'required',
            'Date'=> 'required',
            'Description'=> 'required',

        ]);

        // Create a new Post instance with the validated data
        $post = new Expenses([

            'ID'=> $validatedData['id'],
            'UserID'=> $validatedData['user_id'],
            'CategoryID'=> $validatedData['category_id' ],
            'Amount'=> $validatedData['Amount'],
            'Date'=> $validatedData['Date'],
            'Description'=> $validatedData['Description'],


        ]);

        $post->save(); // Save the new post to the database

        return response()->json($post, Response::HTTP_CREATED); // Return the new post as JSON
    }
    function getid($id)
    {
        $post = Expenses::find($id);
        if (!$post) {
            return response()->json(['message' => 'Expenses not found'], Response::HTTP_NOT_FOUND);
        }

        $post->get();
        return response()->json($post, Response::HTTP_CREATED);
    }
    public function destroy($ExpenseID)
    {
        $expense = Expenses::find($ExpenseID);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], Response::HTTP_NOT_FOUND);
        }

        $expense->delete();
        return response()->json(['message' => 'Expense deleted'], Response::HTTP_OK);
    }
}
