<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if a FK already exists on a given table+column (MySQL).
     * This prevents duplicate FK creation when migrations re-run after partial failure.
     */
    private function hasForeignOnColumn(string $table, string $column): bool
    {
        try {
            if (Schema::getConnection()->getDriverName() !== 'mysql') {
                return false; // best-effort; for other DBs you can extend later
            }

            $dbName = DB::getDatabaseName();

            return DB::table('information_schema.key_column_usage')
                ->where('table_schema', $dbName)
                ->where('table_name', $table)
                ->where('column_name', $column)
                ->whereNotNull('referenced_table_name')
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Drop FK safely by constraint name (ignore if missing).
     */
    private function dropForeignIfExists(string $table, string $constraint): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($constraint) {
                $blueprint->dropForeign($constraint);
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function up(): void
    {
        // Users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id') && Schema::hasTable('roles') && !$this->hasForeignOnColumn('users', 'role_id')) {
                $table->foreign('role_id', 'fk_users_role_id')->references('id')->on('roles')->nullOnDelete();
            }
            if (Schema::hasColumn('users', 'created_by') && !$this->hasForeignOnColumn('users', 'created_by')) {
                $table->foreign('created_by', 'fk_users_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('users', 'updated_by') && !$this->hasForeignOnColumn('users', 'updated_by')) {
                $table->foreign('updated_by', 'fk_users_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Employees
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'department_id') && Schema::hasTable('departments') && !$this->hasForeignOnColumn('employees', 'department_id')) {
                $table->foreign('department_id', 'fk_employees_department_id')->references('id')->on('departments')->cascadeOnDelete();
            }
            if (Schema::hasColumn('employees', 'designation_id') && Schema::hasTable('designations') && !$this->hasForeignOnColumn('employees', 'designation_id')) {
                $table->foreign('designation_id', 'fk_employees_designation_id')->references('id')->on('designations')->cascadeOnDelete();
            }
            if (Schema::hasColumn('employees', 'created_by') && !$this->hasForeignOnColumn('employees', 'created_by')) {
                $table->foreign('created_by', 'fk_employees_created_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Attendances
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'employee_id') && Schema::hasTable('employees') && !$this->hasForeignOnColumn('attendances', 'employee_id')) {
                $table->foreign('employee_id', 'fk_attendances_employee_id')->references('id')->on('employees')->cascadeOnDelete();
            }
            if (Schema::hasColumn('attendances', 'verified_by') && !$this->hasForeignOnColumn('attendances', 'verified_by')) {
                $table->foreign('verified_by', 'fk_attendances_verified_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Clients
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'created_by') && !$this->hasForeignOnColumn('clients', 'created_by')) {
                $table->foreign('created_by', 'fk_clients_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('clients', 'updated_by') && !$this->hasForeignOnColumn('clients', 'updated_by')) {
                $table->foreign('updated_by', 'fk_clients_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        Schema::table('client_contacts', function (Blueprint $table) {
            if (Schema::hasColumn('client_contacts', 'client_id') && Schema::hasTable('clients') && !$this->hasForeignOnColumn('client_contacts', 'client_id')) {
                $table->foreign('client_id', 'fk_client_contacts_client_id')->references('id')->on('clients')->cascadeOnDelete();
            }
        });

        Schema::table('client_notes', function (Blueprint $table) {
            if (Schema::hasColumn('client_notes', 'client_id') && Schema::hasTable('clients') && !$this->hasForeignOnColumn('client_notes', 'client_id')) {
                $table->foreign('client_id', 'fk_client_notes_client_id')->references('id')->on('clients')->cascadeOnDelete();
            }
            if (Schema::hasColumn('client_notes', 'created_by') && !$this->hasForeignOnColumn('client_notes', 'created_by')) {
                $table->foreign('created_by', 'fk_client_notes_created_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Projects
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'client_id') && Schema::hasTable('clients') && !$this->hasForeignOnColumn('projects', 'client_id')) {
                $table->foreign('client_id', 'fk_projects_client_id')->references('id')->on('clients')->cascadeOnDelete();
            }
            if (Schema::hasColumn('projects', 'created_by') && !$this->hasForeignOnColumn('projects', 'created_by')) {
                $table->foreign('created_by', 'fk_projects_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('projects', 'updated_by') && !$this->hasForeignOnColumn('projects', 'updated_by')) {
                $table->foreign('updated_by', 'fk_projects_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Tasks  ✅ (এখানেই আপনার duplicate হচ্ছিল)
        Schema::table('tasks', function (Blueprint $table) {
            // NOTE: custom constraint names + "hasForeignOnColumn" guard => no duplicate ever

            if (Schema::hasColumn('tasks', 'project_id') && Schema::hasTable('projects') && !$this->hasForeignOnColumn('tasks', 'project_id')) {
                $table->foreign('project_id', 'fk_tasks_project_id')->references('id')->on('projects')->cascadeOnDelete();
            }
            if (Schema::hasColumn('tasks', 'assigned_to') && !$this->hasForeignOnColumn('tasks', 'assigned_to')) {
                $table->foreign('assigned_to', 'fk_tasks_assigned_to')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('tasks', 'parent_task_id') && !$this->hasForeignOnColumn('tasks', 'parent_task_id')) {
                $table->foreign('parent_task_id', 'fk_tasks_parent_task_id')->references('id')->on('tasks')->nullOnDelete();
            }
            if (Schema::hasColumn('tasks', 'dependency_task_id') && !$this->hasForeignOnColumn('tasks', 'dependency_task_id')) {
                $table->foreign('dependency_task_id', 'fk_tasks_dependency_task_id')->references('id')->on('tasks')->nullOnDelete();
            }
            if (Schema::hasColumn('tasks', 'created_by') && !$this->hasForeignOnColumn('tasks', 'created_by')) {
                $table->foreign('created_by', 'fk_tasks_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('tasks', 'updated_by') && !$this->hasForeignOnColumn('tasks', 'updated_by')) {
                $table->foreign('updated_by', 'fk_tasks_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Pivot tables
        Schema::table('project_employee', function (Blueprint $table) {
            if (Schema::hasColumn('project_employee', 'project_id') && Schema::hasTable('projects') && !$this->hasForeignOnColumn('project_employee', 'project_id')) {
                $table->foreign('project_id', 'fk_project_employee_project_id')->references('id')->on('projects')->cascadeOnDelete();
            }
            if (Schema::hasColumn('project_employee', 'employee_id') && Schema::hasTable('employees') && !$this->hasForeignOnColumn('project_employee', 'employee_id')) {
                $table->foreign('employee_id', 'fk_project_employee_employee_id')->references('id')->on('employees')->cascadeOnDelete();
            }
        });

        Schema::table('task_employee', function (Blueprint $table) {
            if (Schema::hasColumn('task_employee', 'task_id') && Schema::hasTable('tasks') && !$this->hasForeignOnColumn('task_employee', 'task_id')) {
                $table->foreign('task_id', 'fk_task_employee_task_id')->references('id')->on('tasks')->cascadeOnDelete();
            }
            if (Schema::hasColumn('task_employee', 'employee_id') && Schema::hasTable('employees') && !$this->hasForeignOnColumn('task_employee', 'employee_id')) {
                $table->foreign('employee_id', 'fk_task_employee_employee_id')->references('id')->on('employees')->cascadeOnDelete();
            }
        });

        // Project files/notes
        Schema::table('project_files', function (Blueprint $table) {
            if (Schema::hasColumn('project_files', 'project_id') && Schema::hasTable('projects') && !$this->hasForeignOnColumn('project_files', 'project_id')) {
                $table->foreign('project_id', 'fk_project_files_project_id')->references('id')->on('projects')->cascadeOnDelete();
            }
        });

        Schema::table('project_notes', function (Blueprint $table) {
            if (Schema::hasColumn('project_notes', 'project_id') && Schema::hasTable('projects') && !$this->hasForeignOnColumn('project_notes', 'project_id')) {
                $table->foreign('project_id', 'fk_project_notes_project_id')->references('id')->on('projects')->cascadeOnDelete();
            }
            if (Schema::hasColumn('project_notes', 'created_by') && !$this->hasForeignOnColumn('project_notes', 'created_by')) {
                $table->foreign('created_by', 'fk_project_notes_created_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Invoices
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'client_id') && Schema::hasTable('clients') && !$this->hasForeignOnColumn('invoices', 'client_id')) {
                $table->foreign('client_id', 'fk_invoices_client_id')->references('id')->on('clients')->cascadeOnDelete();
            }
            if (Schema::hasColumn('invoices', 'project_id') && Schema::hasTable('projects') && !$this->hasForeignOnColumn('invoices', 'project_id')) {
                $table->foreign('project_id', 'fk_invoices_project_id')->references('id')->on('projects')->nullOnDelete();
            }
            if (Schema::hasColumn('invoices', 'terms_id') && Schema::hasTable('terms_and_conditions') && !$this->hasForeignOnColumn('invoices', 'terms_id')) {
                $table->foreign('terms_id', 'fk_invoices_terms_id')->references('id')->on('terms_and_conditions')->nullOnDelete();
            }
            if (Schema::hasColumn('invoices', 'issued_by') && !$this->hasForeignOnColumn('invoices', 'issued_by')) {
                $table->foreign('issued_by', 'fk_invoices_issued_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('invoices', 'approved_by') && !$this->hasForeignOnColumn('invoices', 'approved_by')) {
                $table->foreign('approved_by', 'fk_invoices_approved_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('invoices', 'created_by') && !$this->hasForeignOnColumn('invoices', 'created_by')) {
                $table->foreign('created_by', 'fk_invoices_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('invoices', 'updated_by') && !$this->hasForeignOnColumn('invoices', 'updated_by')) {
                $table->foreign('updated_by', 'fk_invoices_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Invoice items
        Schema::table('invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_items', 'invoice_id') && Schema::hasTable('invoices') && !$this->hasForeignOnColumn('invoice_items', 'invoice_id')) {
                $table->foreign('invoice_id', 'fk_invoice_items_invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
            }
            if (Schema::hasColumn('invoice_items', 'item_category_id') && Schema::hasTable('item_categories') && !$this->hasForeignOnColumn('invoice_items', 'item_category_id')) {
                $table->foreign('item_category_id', 'fk_invoice_items_item_category_id')->references('id')->on('item_categories')->nullOnDelete();
            }
            if (Schema::hasColumn('invoice_items', 'created_by') && !$this->hasForeignOnColumn('invoice_items', 'created_by')) {
                $table->foreign('created_by', 'fk_invoice_items_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('invoice_items', 'updated_by') && !$this->hasForeignOnColumn('invoice_items', 'updated_by')) {
                $table->foreign('updated_by', 'fk_invoice_items_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Payments
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'invoice_id') && Schema::hasTable('invoices') && !$this->hasForeignOnColumn('payments', 'invoice_id')) {
                $table->foreign('invoice_id', 'fk_payments_invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
            }
            if (Schema::hasColumn('payments', 'payment_method_id') && Schema::hasTable('payment_methods') && !$this->hasForeignOnColumn('payments', 'payment_method_id')) {
                $table->foreign('payment_method_id', 'fk_payments_payment_method_id')->references('id')->on('payment_methods')->restrictOnDelete();
            }
            if (Schema::hasColumn('payments', 'received_by') && !$this->hasForeignOnColumn('payments', 'received_by')) {
                $table->foreign('received_by', 'fk_payments_received_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('payments', 'created_by') && !$this->hasForeignOnColumn('payments', 'created_by')) {
                $table->foreign('created_by', 'fk_payments_created_by')->references('id')->on('users')->nullOnDelete();
            }
            if (Schema::hasColumn('payments', 'updated_by') && !$this->hasForeignOnColumn('payments', 'updated_by')) {
                $table->foreign('updated_by', 'fk_payments_updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // ✅ Safe rollback (won’t throw if missing). Also uses our custom FK names only.

        // Payments
        $this->dropForeignIfExists('payments', 'fk_payments_invoice_id');
        $this->dropForeignIfExists('payments', 'fk_payments_payment_method_id');
        $this->dropForeignIfExists('payments', 'fk_payments_received_by');
        $this->dropForeignIfExists('payments', 'fk_payments_created_by');
        $this->dropForeignIfExists('payments', 'fk_payments_updated_by');

        // Invoice items
        $this->dropForeignIfExists('invoice_items', 'fk_invoice_items_invoice_id');
        $this->dropForeignIfExists('invoice_items', 'fk_invoice_items_item_category_id');
        $this->dropForeignIfExists('invoice_items', 'fk_invoice_items_created_by');
        $this->dropForeignIfExists('invoice_items', 'fk_invoice_items_updated_by');

        // Invoices
        $this->dropForeignIfExists('invoices', 'fk_invoices_client_id');
        $this->dropForeignIfExists('invoices', 'fk_invoices_project_id');
        $this->dropForeignIfExists('invoices', 'fk_invoices_terms_id');
        $this->dropForeignIfExists('invoices', 'fk_invoices_issued_by');
        $this->dropForeignIfExists('invoices', 'fk_invoices_approved_by');
        $this->dropForeignIfExists('invoices', 'fk_invoices_created_by');
        $this->dropForeignIfExists('invoices', 'fk_invoices_updated_by');

        // Project notes/files
        $this->dropForeignIfExists('project_notes', 'fk_project_notes_project_id');
        $this->dropForeignIfExists('project_notes', 'fk_project_notes_created_by');
        $this->dropForeignIfExists('project_files', 'fk_project_files_project_id');

        // Pivot tables
        $this->dropForeignIfExists('task_employee', 'fk_task_employee_task_id');
        $this->dropForeignIfExists('task_employee', 'fk_task_employee_employee_id');

        $this->dropForeignIfExists('project_employee', 'fk_project_employee_project_id');
        $this->dropForeignIfExists('project_employee', 'fk_project_employee_employee_id');

        // Tasks
        $this->dropForeignIfExists('tasks', 'fk_tasks_project_id');
        $this->dropForeignIfExists('tasks', 'fk_tasks_assigned_to');
        $this->dropForeignIfExists('tasks', 'fk_tasks_parent_task_id');
        $this->dropForeignIfExists('tasks', 'fk_tasks_dependency_task_id');
        $this->dropForeignIfExists('tasks', 'fk_tasks_created_by');
        $this->dropForeignIfExists('tasks', 'fk_tasks_updated_by');

        // Projects
        $this->dropForeignIfExists('projects', 'fk_projects_client_id');
        $this->dropForeignIfExists('projects', 'fk_projects_created_by');
        $this->dropForeignIfExists('projects', 'fk_projects_updated_by');

        // Client notes/contacts/clients
        $this->dropForeignIfExists('client_notes', 'fk_client_notes_client_id');
        $this->dropForeignIfExists('client_notes', 'fk_client_notes_created_by');

        $this->dropForeignIfExists('client_contacts', 'fk_client_contacts_client_id');

        $this->dropForeignIfExists('clients', 'fk_clients_created_by');
        $this->dropForeignIfExists('clients', 'fk_clients_updated_by');

        // Attendances
        $this->dropForeignIfExists('attendances', 'fk_attendances_employee_id');
        $this->dropForeignIfExists('attendances', 'fk_attendances_verified_by');

        // Employees
        $this->dropForeignIfExists('employees', 'fk_employees_department_id');
        $this->dropForeignIfExists('employees', 'fk_employees_designation_id');
        $this->dropForeignIfExists('employees', 'fk_employees_created_by');

        // Users
        $this->dropForeignIfExists('users', 'fk_users_role_id');
        $this->dropForeignIfExists('users', 'fk_users_created_by');
        $this->dropForeignIfExists('users', 'fk_users_updated_by');
    }
};
