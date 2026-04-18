<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::query()
            ->with('division', 'jobTitle')
            ->when($request->q, fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%")
                    ->orWhere('nip', 'like', "%{$request->q}%");
            }))
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->when($request->division, fn ($q) => $q->where('division_id', $request->division))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $divisions = Division::orderBy('name')->get();

        return view('admin.employees.index', compact('employees', 'divisions'));
    }

    public function create()
    {
        return view('admin.employees.create', $this->formRefs());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password'] ?? 'password');
        $data['raw_password'] = $request->password ?? 'password';

        User::create($data);

        return redirect()->route('admin.employees')
            ->with('flash.banner', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', [
            'employee' => $employee,
            ...$this->formRefs(),
        ]);
    }

    public function update(Request $request, User $employee)
    {
        $data = $this->validated($request, $employee);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $data['raw_password'] = $request->password;
        } else {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()->route('admin.employees')
            ->with('flash.banner', 'Data karyawan diperbarui.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees')
            ->with('flash.banner', 'Data karyawan dihapus.');
    }

    private function formRefs(): array
    {
        return [
            'divisions' => Division::orderBy('name')->get(),
            'jobTitles' => JobTitle::orderBy('name')->get(),
            'educations' => Education::orderBy('name')->get(),
            'roles' => User::$roleLabels,
        ];
    }

    private function validated(Request $request, ?User $existing = null): array
    {
        return $request->validate([
            'nip' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($existing)],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female'],
            'role' => ['required', Rule::in(User::$roles)],
            'status' => ['required', 'in:active,inactive'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'job_title_id' => ['nullable', 'exists:job_titles,id'],
            'education_id' => ['nullable', 'exists:educations,id'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => [$existing ? 'nullable' : 'required', 'string', 'min:6'],
        ]);
    }
}
