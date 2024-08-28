<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all users from the database
        $users = DB::select('SELECT * FROM users');

        // Return the list of users as a JSON response
        return Response::json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically, this would return a view for creating a user
        // Since we're dealing with an API, you might not need this method
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if email already exists
        $emailExists = DB::select('SELECT email FROM users WHERE email = ?', [$request->email]);
    
        if (count($emailExists) > 0) {
            return Response::json(['status' => false, 'message' => 'Email already exists']); // 409 Conflict
        }
    
        // Insert data into the database
        $insert = DB::insert(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)', 
            [
                $request->name,
                $request->email,
                bcrypt($request->password), // Encrypt password
            ]
        );
    
        // Check if the insertion was successful
        if ($insert) {
            return Response::json(['status' => true, 'message' => 'User created successfully'], 200);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed to create user'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the user by ID
        $user = DB::select('SELECT * FROM users WHERE user_id = ?', [$id]);

        // Check if user was found
        if ($user) {
            return Response::json($user, 200);
        } else {
            return Response::json(['status' => false, 'message' => 'User not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Typically, this would return a view for editing a user
        // Since we're dealing with an API, you might not need this method
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update user data in the database
        $update = DB::update(
            'UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?', 
            [
                $request->name,
                $request->email,
                bcrypt($request->password), // Encrypt password
                $id
            ]
        );

        // Check if the update was successful
        if ($update) {
            return Response::json(['status' => true, 'message' => 'User updated successfully'], 200);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed to update user'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete the user from the database
        $delete = DB::delete('DELETE FROM users WHERE user_id = ?', [$id]);

        // Check if the deletion was successful
        if ($delete) {
            return Response::json(['status' => true, 'message' => 'User deleted successfully'], 200);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed to delete user'], 500);
        }
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {

        // Retrieve the user by email
        $user = DB::select('SELECT * FROM users WHERE email = ?', [$request->email]);

        // Check if user exists and the password is correct
        if ($user && Hash::check($request->password, $user[0]->password)) {
            // Generate a token (for example, a JWT or personal access token)
            // Here we are just returning a success message
            return Response::json(['status' => true, 'message' => 'Login successful', 'user' => $user[0]], 200);
        } else {
            return Response::json(['status' => false, 'message' => 'Invalid credentials']);
        }
    }
}
