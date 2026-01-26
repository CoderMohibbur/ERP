<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $q = trim((string) $request->input('q', ''));
        $category = $request->input('category');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $expenses = Expense::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                        ->orWhere('vendor', 'like', "%{$q}%")
                        ->orWhere('reference', 'like', "%{$q}%");
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->when($dateFrom, fn ($query) => $query->whereDate('expense_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('expense_date', '<=', $dateTo))
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends($request->query());

        $categories = ['server', 'tools', 'salary', 'office', 'marketing', 'other'];

        return view('expenses.index', compact('expenses', 'categories', 'q', 'category', 'dateFrom', 'dateTo', 'perPage'));
    }

    public function create(): View
    {
        $categories = ['server', 'tools', 'salary', 'office', 'marketing', 'other'];

        return view('expenses.create', compact('categories'));
    }

    public function store(ExpenseStoreRequest $request): RedirectResponse
    {
        $expense = Expense::create($request->validated());

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense): View
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        $categories = ['server', 'tools', 'salary', 'office', 'marketing', 'other'];

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(ExpenseUpdateRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());

        return redirect()
            ->route('expenses.show', $expense)
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
