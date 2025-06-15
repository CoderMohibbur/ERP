<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSkill;
use App\Models\Employee;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeSkillController extends Controller
{
    public function index(): View
    {
        $skills = EmployeeSkill::with(['employee', 'skill', 'assigner'])
            ->latest()
            ->paginate(20);

        return view('employee-skills.index', compact('skills'));
    }

    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        $skills    = Skill::pluck('name', 'id');

        return view('employee-skills.create', compact('employees', 'skills'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'skill_id'           => 'required|exists:skills,id',
            'proficiency_level'  => 'nullable|integer|min:1|max:10',
            'notes'              => 'nullable|string',
        ]);

        // Prevent duplicate manually (optional, DB already enforces)
        $exists = EmployeeSkill::where('employee_id', $validated['employee_id'])
            ->where('skill_id', $validated['skill_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['This employee already has this skill assigned.'])->withInput();
        }

        $validated['assigned_by'] = auth()->id();

        EmployeeSkill::create($validated);

        return redirect()->route('employee-skills.index')
            ->with('success', 'Skill assigned to employee.');
    }

    public function edit(EmployeeSkill $employeeSkill): View
    {
        $employees = Employee::pluck('name', 'id');
        $skills    = Skill::pluck('name', 'id');

        return view('employee-skills.edit', compact('employeeSkill', 'employees', 'skills'));
    }

    public function update(Request $request, EmployeeSkill $employeeSkill): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'skill_id'           => 'required|exists:skills,id',
            'proficiency_level'  => 'nullable|integer|min:1|max:10',
            'notes'              => 'nullable|string',
        ]);

        // Prevent duplicate assignment on update
        $duplicate = EmployeeSkill::where('employee_id', $validated['employee_id'])
            ->where('skill_id', $validated['skill_id'])
            ->where('id', '!=', $employeeSkill->id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['Duplicate skill mapping found.'])->withInput();
        }

        $validated['assigned_by'] = auth()->id();

        $employeeSkill->update($validated);

        return redirect()->route('employee-skills.index')
            ->with('success', 'Employee skill updated successfully.');
    }

    public function destroy(EmployeeSkill $employeeSkill): RedirectResponse
    {
        $employeeSkill->delete();

        return redirect()->route('employee-skills.index')
            ->with('success', 'Employee skill removed.');
    }
}
