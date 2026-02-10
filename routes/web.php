<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
// livewire
use \App\Http\Livewire\Dashboard;
use \App\Http\Livewire\ShowChild;
use \App\Http\Livewire\ChildHistory;

use \App\Http\Livewire\JetAddChild;
use \App\Http\Controllers\ChartsController;

// use GuzzleHttp\Psr7\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/data', Dashboard::class)->name('data');
    Route::get('/show/{slug}', ShowChild::class)->name('hehe/{slug}');
    // Route::get('/show/{slug}', ShowChild::class);
    Route::get('/jet-add', JetAddChild::class)->name('jet-add');
    Route::get('/show/{slug}/{lingkar}/{panjang}/{berat}', [ChildHistory::class, 'store']);
// http://127.0.0.1:8000/show/2/6000/6000/6000



});

Route::post('post-request', function(Request $request){
    $input = $request->all();
    return $input;
});

Route::get('hehe',function(Request $request){
    $uri = $request->path();
    $urlWithQueryString = $request->fullUrl();
    return $urlWithQueryString;
});


Route::get('/user/{id}/{lingkar}/{berat}/{panjang}', function ($id, $lingkar, $berat, $panjang) {
    //retur
    return $id." nama saya".$lingkar;
})->whereNumber('id')->whereAlpha('name');


Route::get('/chart', [ChartsController::class, 'index'])->name('charts');