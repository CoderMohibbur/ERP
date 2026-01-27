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

    // ✅ NEW (Phase-0 CRM + Finance basics)
    LeadController,
    DealController,
    ServiceController,
    ExpenseController,
    OwnerDashboardController,
    TimeLogController,
    ServiceRenewalController,
    ActivityController, 
    ProjectBoardController,
    TaskStatusController,
    ActivityPageController,
};


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

    /*
    |--------------------------------------------------------------------------
    | ✅ NEW: Phase-0 minimum routes (Minimum_ERP_Spec)
    |--------------------------------------------------------------------------
    */
    Route::resource('leads', LeadController::class);
    Route::resource('deals', DealController::class);

    Route::get('deals-pipeline', [DealController::class, 'pipeline'])->name('deals.pipeline');
    Route::post('deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.stage');

    Route::resource('services', ServiceController::class);
    Route::resource('expenses', ExpenseController::class);

    Route::get('owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});

Route::get('/invoice-items/row-template/{index}', function ($index) {
    $categories = \App\Models\ItemCategory::pluck('name', 'id'); // Optional, if category needed
    return view('invoice-items.form', compact('index', 'categories'));
});


Route::post('tasks/{task}/timer/start', [TimeLogController::class, 'start'])->name('timer.start');
Route::post('tasks/{task}/timer/stop',  [TimeLogController::class, 'stop'])->name('timer.stop');

Route::post('services/{service}/renewals/generate-invoice', [ServiceRenewalController::class, 'generateInvoice'])
    ->name('services.renewals.invoice');

    Route::resource('activities', ActivityController::class)->only(['store', 'update', 'destroy']);
Route::get('projects/{project}/board', ProjectBoardController::class)
    ->name('projects.board')
    ->middleware('permission:project.view|project.*');

Route::patch('tasks/{task}/status', TaskStatusController::class)
    ->name('tasks.status')
    ->middleware('permission:task.update|task.*');

        Route::get('/activities', [ActivityPageController::class, 'index'])->name('activities.index');
    Route::get('/activities/{activity}', [ActivityPageController::class, 'show'])->name('activities.show');
