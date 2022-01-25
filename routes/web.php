<?php

use App\Models\User;
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

Route::get('admin/change', function () {
    $user = User::find(auth()->user()->id);

    if ($user->lang === 'ar') {
        $user->lang = 'en';
        $user->save();
    } else if ($user->lang === 'en') {
        $user->lang = 'ar';
        $user->save();
    }

    session()->flash('notification', [
        'message' => __("Language Updated To " . $user->lang),
        'status' => "success",
    ]);

    return back();
})->middleware('web');
