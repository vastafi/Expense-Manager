<?php

namespace App\Http\Controllers;

use App\Models\MonthlyBudget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonthlyBudgetControler extends Controller
{
    public function index()
    {
        $post = MonthlyBudget::all(); // Retrieve all posts from the database
        return response()->json($post, Response::HTTP_OK); // Return the data as JSON
    }

    //post
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id'=> 'required',
            'user_id'=> 'required',
            'category_id' => 'required',
            'Amount'=> 'required',
        ]);

        // Create a new Post instance with the validated data
        $post = new MonthlyBudget([
            'id'=> $validatedData['id'],
            'user_id'=> $validatedData['user_id'],
            'category_id' => $validatedData['category_id'],
            'Amount'=> $validatedData['Amount'],
        ]);

        $post->save(); // Save the new post to the database

        return response()->json($post, Response::HTTP_CREATED); // Return the new post as JSON
    }
    function getid($id)
    {
        $post = MonthlyBudget::find($id);
        if (!$post) {
            return response()->json(['message' => 'Monthly Buget not found'], Response::HTTP_NOT_FOUND);
        }

        $post->get();
        return response()->json($post, Response::HTTP_CREATED);
    }
    //delete
    public function destroy($id)
    {
        $post = MonthlyBudget::find($id);

        if (!$post) {
            return response()->json(['message' => 'Monthly budget not found'], Response::HTTP_NOT_FOUND);
        }

        $post->delete();
        return response()->json(['message' => 'Monthly budget deleted'], Response::HTTP_OK);
    }

}
