<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DepartmentController,
    DesignationController,
    EmployeeController,
    AttendanceController,
    AttendanceSettingController,
    ClientController,
    ClientContactController,
    ClientNoteController,
    ProjectController,
    TaskController,
    ProjectFileController,
    ProjectNoteController,
    InvoiceController,
    PaymentController,
};


Route::resource('departments', DepartmentController::class);
Route::resource('designations', DesignationController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('attendance-settings', AttendanceSettingController::class);
Route::resource('clients', ClientController::class);
Route::resource('client-contacts', ClientContactController::class);
Route::resource('client-notes', ClientNoteController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);
Route::resource('project-files', ProjectFileController::class);
Route::resource('project-notes', ProjectNoteController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('payments', PaymentController::class);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/page', function () {
    return view('page');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});




