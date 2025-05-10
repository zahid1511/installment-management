<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RoleAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GuarantorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RecoveryOfficerController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\InstallmentController;
use App\Http\Controllers\Admin\DashboardController;






Route::group(['prefix' => 'admin', 'middleware' => ['role:Admin']], function () {
    
    Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.dashboard');

    //Dashboard
    Route::get('report', [DashboardController::class, 'report'])->name('admin.report');

    //customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{customer}/statement', [CustomerController::class, 'statement'])->name('customers.statement');
    
    //guarantors
    Route::resource('guarantors', GuarantorController::class);
    Route::post('guarantors/check', [GuarantorController::class, 'checkGuarantor'])->name('guarantors.check');

    //products
    Route::resource('products', ProductController::class);

    
    // recovery-officers
    Route::resource('recovery-officers', RecoveryOfficerController::class);
    
    //purchases
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/process-payment', [PurchaseController::class, 'processPayment'])->name('purchases.process-payment');

    //installments
    Route::resource('installments', InstallmentController::class);

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/get-list', [UserController::class, 'getUsers'])->name('users.list');
        Route::post('/update', [UserController::class, 'update'])->name('user.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    });
    
    Route::prefix('roles')->group(function () {
        Route::get('/', [UserController::class, 'getRolesIndex'])->name('admin.roles');
        Route::post('/store', [UserController::class, 'addRole'])->name('role.store');
        Route::post('/update', [UserController::class, 'updateRole'])->name('role.update');
        Route::get('/delete/{role}', [UserController::class, 'deleteRole'])->name('role.delete');
    });

    Route::prefix('role-assignment')->group(function () {
        Route::get('/', [RoleAssignmentController::class, 'index'])->name('role-assignment');
        Route::get('/user-role', [RoleAssignmentController::class, 'getUserRoles'])->name('user-role');
        Route::post('/assign-role', [RoleAssignmentController::class, 'assignOrUpdateRole'])->name('assign-role');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
    });

    Route::get('/roles-list', [UserController::class, 'getRolesList'])->name('admin.roles-list');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'store'])->name('store.settings');

});

Route::group(['middleware' => ['role:Customer']], function () {
    Route::get('/customer', [HomeController::class, 'index'])->name('customer.dashboard');
});

Route::group(['middleware' => ['role:Admin|Customer']], function () {

});


Route::get('admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
