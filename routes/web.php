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
    | ✅ Safe Alias Routes (UI/Sidebar compatibility) - NO BREAKING
    |--------------------------------------------------------------------------
    | এগুলো না থাকলে route('time-logs.index') / route('task-statuses.index') ইত্যাদি
    | থাকলে "Route not defined" হতে পারে।
    */
    Route::get('task-statuses', function () {
        return redirect()->route('tasks.index');
    })->name('task-statuses.index');

    Route::get('time-logs', function () {
        // TimeLogController এ index নেই (শুধু start/stop), তাই safe fallback
        return redirect()->route('tasks.index');
    })->name('time-logs.index');

    // Project board alias (same controller + same param)
    Route::get('project-board/{project}', ProjectBoardController::class)
        ->name('project-board.index')
        ->middleware('permission:project.view|project.*');

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

    /**
     * ✅ FIX: Client Contacts are CLIENT-SCOPED
     * Controller signature requires Client $client in every action.
     * So we define nested routes but keep the existing route names:
     * client-contacts.* (so existing blades keep working).
     */
    Route::get('clients/{client}/contacts', [ClientContactController::class, 'index'])
        ->name('client-contacts.index');
    Route::get('clients/{client}/contacts/create', [ClientContactController::class, 'create'])
        ->name('client-contacts.create');
    Route::post('clients/{client}/contacts', [ClientContactController::class, 'store'])
        ->name('client-contacts.store');
    Route::get('clients/{client}/contacts/{clientContact}/edit', [ClientContactController::class, 'edit'])
        ->name('client-contacts.edit');
    Route::put('clients/{client}/contacts/{clientContact}', [ClientContactController::class, 'update'])
        ->name('client-contacts.update');
    Route::delete('clients/{client}/contacts/{clientContact}', [ClientContactController::class, 'destroy'])
        ->name('client-contacts.destroy');

    // Client Notes are global CRUD (no client param required)
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

    Route::resource('item-categories', ItemCategoryController::class);
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
    Route::resource('renewals', ServiceRenewalController::class)->only(['index', 'show', 'update']);

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
    | Activities
    |--------------------------------------------------------------------------
    */
    Route::get('activities', [ActivityPageController::class, 'index'])
        ->name('activities.index');

    Route::get('activities/{activity}', [ActivityPageController::class, 'show'])
        ->name('activities.show');

    Route::post('activities', [ActivityController::class, 'store'])
        ->name('activities.store');

    Route::put('activities/{activity}', [ActivityController::class, 'update'])
        ->name('activities.update');

    Route::delete('activities/{activity}', [ActivityController::class, 'destroy'])
        ->name('activities.destroy');

    /*
    |--------------------------------------------------------------------------
    | Time Logs (Timer only: start/stop)
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
