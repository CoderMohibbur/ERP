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
    InvoiceItemController,
    PaymentController,
    TermAndConditionController,
    ItemCategoryController,
    EmployeeHistoryController,
    EmployeeDocumentController,
    SkillController,
    EmployeeSkillController,
    ShiftController,
    EmployeeShiftController,
    EmployeeDependentController,
    EmployeeResignationController,
    EmployeeDisciplinaryActionController,
    TaxRuleController,
};
use App\Models\InvoiceItem;

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

    // ✅ Authenticated resource routes
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('attendance-settings', AttendanceSettingController::class);
    Route::get('attendance-settings/edit', [AttendanceSettingController::class, 'edit'])->name('attendance-settings.edit');
    Route::put('attendance-settings/{attendanceSetting}', [AttendanceSettingController::class, 'update'])->name('attendance-settings.update');
Route::resource('tax-rules', TaxRuleController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('employee-histories', EmployeeHistoryController::class);
    Route::resource('shifts', ShiftController::class);
Route::resource('employee-disciplinary-actions', EmployeeDisciplinaryActionController::class);
    Route::resource('client-contacts', ClientContactController::class)->except(['show']);
Route::resource('employee-resignations', EmployeeResignationController::class);
    Route::resource('employee-shifts', EmployeeShiftController::class);
    Route::resource('client-notes', ClientNoteController::class)->except(['show']);
    Route::resource('employee-dependents', EmployeeDependentController::class);

    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('project-files', ProjectFileController::class);
    Route::resource('project-notes', ProjectNoteController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('invoice-items', InvoiceItemController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('employee-documents', EmployeeDocumentController::class);

    Route::resource('item-categories', ItemCategoryController::class);

    Route::resource('terms', TermAndConditionController::class);

    Route::resource('employee-skills', EmployeeSkillController::class);


    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
});




Route::get('/invoice-items/row-template/{index}', function ($index) {
    $categories = \App\Models\ItemCategory::pluck('name', 'id'); // Optional, if category needed
    return view('invoice-items.form', compact('index', 'categories'));
});
