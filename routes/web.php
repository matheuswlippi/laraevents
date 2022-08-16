<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController
};
use App\Http\Controllers\Participant\Dashboard\DashboardController as ParticipanteDashboardController;
use App\Http\Controllers\Organization\{
    Dashboard\DashboardController as OrganizationDashboardController,
    Event\EventController,
    Event\EventSubscriptionController,
    Event\EventPresenceController
};


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

Route::group(['as' => 'auth.'], function(){
    Route::group(['middleware'=> 'guest'], function(){
        Route::get('register', [RegisterController::class, 'create'])->name('register.create');
        Route::post('register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('login',[LoginController::class, 'create'])->name('login.create');
        Route::post('login',[LoginController::class, 'store'])->name('login.store');
    });


    Route::post('logout',[LoginController::class, 'destroy'])
        ->name('login.destroy')
        ->middleware('auth');

});

Route::group(['middleware' => 'auth'], function(){
    Route::get('participant/dashboard', [ParticipanteDashboardController::class, 'index'])
        ->name('participant.dashboard.index')
        ->middleware('role:participant');

    Route::group(['prefix' => 'organization', 'as' => 'organization.' , 'middleware' => 'role:organization'], function(){
        //dashboard
        Route::get('dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard.index');

        //eventos
        // Route::get('events', [EventController::class, 'index'])->name('events.index');
        // Route::get('events/create', [EventController::class, 'create'])->name('events.create');
        // Route::post('events', [EventController::class, 'store'])->name('events.store');
        // Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
        // Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        // Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
        // Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::post('events/{event}/subscriptions', [EventSubscriptionController::class, 'store'])
            ->name('events.subscriptions.store');
        Route::delete('events/{event}/subscriptions/{user}', [EventSubscriptionController::class, 'destroy'])
            ->name('events.subscriptions.destroy');
        Route::post('events/{event}/presences/{user}', EventPresenceController::class)
            ->name('events.presences');
        Route::resource('events', EventController::class);

    });

});


