<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [OrderController::class, 'index'])->name('home');
    Route::post('order/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware(['permission:cancel_order']);
    Route::get('order/activity/{order_id}', [OrderController::class, 'activity'])->name('order.activity')->middleware(['role:management']);
    Route::resource('order', OrderController::class);
});

Route::group(['middleware' => ['auth', 'permission:product_mgt']], function () {
    Route::post('product/delete', [ProductsController::class, 'delete'])->name('product.delete');
    Route::resource('product', ProductsController::class);
});

Route::get('logout', function (){
    Auth::logout();
    return redirect('/');
});

Route::get('password', [UserController::class, 'password']);
Route::post('password', [UserController::class, 'updatePassword']);

Route::controller(UserController::class)->middleware(['auth', 'role:management'])
    ->prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('create', 'create')->name('create');
        Route::post('get', 'get')->name('get');
        Route::post('edit', 'edit')->name('edit');
        Route::get('assigned/permissions', 'assigned_permissions')->name('assigned.permissions');

        //roles
        Route::get('roles', 'roles')->name('roles');
        Route::post('roles', 'create_roles')->name('roles.create');
        Route::get('roles/{role_id}', 'view_roles')->name('roles.view');
        Route::post('roles/permissions', 'create_roles_permissions')->name('roles.permissions.create');
        Route::post('roles/permissions/sync', 'sync_roles_permissions')->name('roles.permissions.sync');
        Route::get('permissions', 'permissions')->name('permissions');
        Route::get('user/{user_id}/role/{role_id}', 'user_permissions')->name('user.permissions');

        Route::get('audit', 'audit')->name('audit');

    });
