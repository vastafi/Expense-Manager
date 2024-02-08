<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response; // Import the Response class
use Illuminate\Http\Request;
use App\Models\Users;
class UserController extends Controller{
    //get data
    public function index()
    {
        $post = Users::all(); // Retrieve all posts from the database
        return response()->json($post, Response::HTTP_OK); // Return the data as JSON
    }

    //post
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name'=> 'required',
            'email'=> 'required',
            'email_verified_at' => 'required',
            'password'=> 'required',
            'remember_token'=> 'required',
        ]);

        // Create a new Post instance with the validated data
        $post = new Users([

            'id'=> $validatedData['id'],
            'name'=> $validatedData['name'],
            'email'=> $validatedData['email'],
            'email_verified_at' => $validatedData['email_verified_at'],
            'password'=> $validatedData['password'],
            'remember_token'=> $validatedData['remember_token'],

        ]);

        $post->save(); // Save the new post to the database

        return response()->json($post, Response::HTTP_CREATED); // Return the new post as JSON
    }
    function getid($id)
    {
        $post = Users::find($id);
        if (!$post) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $post->get();
        return response()->json($post, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'=> 'required',
            'email'=> 'required',
            'email_verified_at' => 'required',
            'password'=> 'required',
            'remember_token'=> 'required',
        ]);


        $post = Users::find($id);

        if (!$post) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $post->update($validatedData);

        return response()->json($post, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $post = Users::find($id);

        if (!$post) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $post->delete();
        return response()->json(['message' => 'User deleted'], Response::HTTP_OK);
    }
}
