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

Route::get('admin/translations/change', function () {
    $user = User::find(auth()->user()->id);

    $langArray = config('filament-translations.switcher');

    if ($user->lang === $langArray[0]) {
        $user->lang = $langArray[1];
        $user->save();
    } else {
        $user->lang = $langArray[0];
        $user->save();
    }

    session()->flash('notification', [
        'message' => __(trans('translation.notification') . $user->lang),
        'status' => "success",
    ]);

    return back();
})->middleware('web');
