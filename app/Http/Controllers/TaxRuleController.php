<?php

namespace App\Http\Controllers;

use App\Models\TaxRule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTaxRuleRequest;
use App\Http\Requests\UpdateTaxRuleRequest;

class TaxRuleController extends Controller
{
    /**
     * Display a listing of the tax rules.
     */
    public function index(): View
    {
        $taxRules = TaxRule::latest()->paginate(10);
        return view('tax-rules.index', compact('taxRules'));
    }

    /**
     * Show the form for creating a new tax rule.
     */
    public function create(): View
    {
        return view('tax-rules.create');
    }

    /**
     * Store a newly created tax rule.
     */
    public function store(StoreTaxRuleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        TaxRule::create($data);

        return redirect()->route('tax-rules.index')
                         ->with('success', 'Tax rule created successfully.');
    }

    /**
     * Show the form for editing the specified tax rule.
     */
    public function edit(TaxRule $taxRule): View
    {
        return view('tax-rules.edit', compact('taxRule'));
    }

    /**
     * Update the specified tax rule.
     */
    public function update(UpdateTaxRuleRequest $request, TaxRule $taxRule): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $taxRule->update($data);

        return redirect()->route('tax-rules.index')
                         ->with('success', 'Tax rule updated successfully.');
    }

    /**
     * Remove the specified tax rule.
     */
    public function destroy(TaxRule $taxRule): RedirectResponse
    {
        $taxRule->delete();

        return redirect()->route('tax-rules.index')
                         ->with('success', 'Tax rule deleted successfully.');
    }
}
