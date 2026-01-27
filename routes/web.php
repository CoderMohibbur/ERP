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

    // Phase-0 CRM + Finance
    LeadController,
    DealController,
    ServiceController,
    ExpenseController,
    OwnerDashboardController,
    TimeLogController,
    ServiceRenewalController,

    // Activities
    ActivityController,
    ActivityPageController,

    ProjectBoardController,
    TaskStatusController,
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/page', function () {
    return view('page');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Core HR / Admin
    |--------------------------------------------------------------------------
    */
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class);

    // Attendance settings (single config)
    Route::resource('attendance-settings', AttendanceSettingController::class)->except(['create', 'store', 'show']);
    Route::get('attendance-settings/edit', [AttendanceSettingController::class, 'edit'])
        ->name('attendance-settings.edit');

    Route::resource('tax-rules', TaxRuleController::class);

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class);
    Route::resource('client-contacts', ClientContactController::class)->except(['show']);
    Route::resource('client-notes', ClientNoteController::class)->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Employees (HR Sub modules)
    |--------------------------------------------------------------------------
    */
    Route::resource('employee-histories', EmployeeHistoryController::class);
    Route::resource('employee-documents', EmployeeDocumentController::class);
    Route::resource('employee-skills', EmployeeSkillController::class);
    Route::resource('employee-shifts', EmployeeShiftController::class);
    Route::resource('employee-dependents', EmployeeDependentController::class);
    Route::resource('employee-resignations', EmployeeResignationController::class);
    Route::resource('employee-disciplinary-actions', EmployeeDisciplinaryActionController::class);
    Route::resource('shifts', ShiftController::class);
    Route::resource('skills', SkillController::class);

    /*
    |--------------------------------------------------------------------------
    | Projects & Tasks
    |--------------------------------------------------------------------------
    */
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('project-files', ProjectFileController::class);
    Route::resource('project-notes', ProjectNoteController::class);

    Route::get('projects/{project}/board', ProjectBoardController::class)
        ->name('projects.board')
        ->middleware('permission:project.view|project.*');

    Route::patch('tasks/{task}/status', TaskStatusController::class)
        ->name('tasks.status')
        ->middleware('permission:task.update|task.*');

    /*
    |--------------------------------------------------------------------------
    | Finance
    |--------------------------------------------------------------------------
    */
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    Route::resource('invoice-items', InvoiceItemController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('expenses', ExpenseController::class);

    // ✅ Missing before: Item Categories
    Route::resource('item-categories', ItemCategoryController::class);

    // ✅ Missing before: Terms & Conditions
    Route::resource('terms', TermAndConditionController::class);

    /*
    |--------------------------------------------------------------------------
    | CRM
    |--------------------------------------------------------------------------
    */
    Route::resource('leads', LeadController::class);
    Route::resource('deals', DealController::class);
    Route::get('deals-pipeline', [DealController::class, 'pipeline'])->name('deals.pipeline');
    Route::post('deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.stage');

    /*
    |--------------------------------------------------------------------------
    | Renewals (Services + Service Renewals)
    |--------------------------------------------------------------------------
    */
    Route::resource('services', ServiceController::class);

    // ✅ Missing before: Renewals list/show/update UI routes
    // Note: only these actions are added to avoid guessing controller methods.
    Route::resource('renewals', ServiceRenewalController::class)->only(['index', 'show', 'update']);

    // Existing action: generate invoice from a service renewal action
    Route::post(
        'services/{service}/renewals/generate-invoice',
        [ServiceRenewalController::class, 'generateInvoice']
    )->name('services.renewals.invoice');

    /*
    |--------------------------------------------------------------------------
    | Owner Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('owner-dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner-dashboard');

    /*
    |--------------------------------------------------------------------------
    | Activities (FINAL, CLEAN, NO CONFLICT)
    |--------------------------------------------------------------------------
    */
    // Read (UI)
    Route::get('activities', [ActivityPageController::class, 'index'])
        ->name('activities.index');

    Route::get('activities/{activity}', [ActivityPageController::class, 'show'])
        ->name('activities.show');

    // Write (Actions)
    Route::post('activities', [ActivityController::class, 'store'])
        ->name('activities.store');

    Route::put('activities/{activity}', [ActivityController::class, 'update'])
        ->name('activities.update');

    Route::delete('activities/{activity}', [ActivityController::class, 'destroy'])
        ->name('activities.destroy');

    /*
    |--------------------------------------------------------------------------
    | Time Logs
    |--------------------------------------------------------------------------
    */
    Route::post('tasks/{task}/timer/start', [TimeLogController::class, 'start'])
        ->name('timer.start');

    Route::post('tasks/{task}/timer/stop', [TimeLogController::class, 'stop'])
        ->name('timer.stop');

    /*
    |--------------------------------------------------------------------------
    | Helper Route (Invoice Item Row Template) - SECURED
    |--------------------------------------------------------------------------
    */
    Route::get('invoice-items/row-template/{index}', function ($index) {
        $categories = \App\Models\ItemCategory::pluck('name', 'id');
        return view('invoice-items.form', compact('index', 'categories'));
    })->name('invoice-items.row-template');
});
