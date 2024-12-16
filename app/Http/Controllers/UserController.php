<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data dari API JSONPlaceholder
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        // Decode JSON response
        $users = $response->json();

        // Tampilkan di view
        return view('index', ['users' => $users]);
    }
}
