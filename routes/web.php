<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/users', [UserController::class, 'index']);
Route::get('/', [UserController::class, 'index'])->name('users');
Route::get('/api/users', function () {
    $response = Http::get('https://jsonplaceholder.typicode.com/users');
    return response()->json($response->json());
});
