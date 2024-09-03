<?php

// app/Http/Controllers/DutyController.php

namespace App\Http\Controllers;

use App\Imports\DutyImport;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Duty;
use Maatwebsite\Excel\Facades\Excel;

class DutyController extends Controller
{
    public function index()
    {
        $guards = Duty::all();
        return view('duties.index', compact('guards'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('duties.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'telegram_link' => 'nullable|url',
            'department_id' => 'required|exists:departments,id',
        ]);

        Duty::create($request->all());

        return redirect()->route('duties.index')->with('success', 'Дежурный успешно добавлен.');
    }

    public function edit($id)
    {
        $guard = Duty::findOrFail($id);
        $departments = Department::all();
        return view('duties.edit', compact('guard', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'telegram_link' => 'nullable|url',
            'department_id' => 'required|exists:departments,id',
        ]);

        $guard = Duty::findOrFail($id);
        $guard->update($request->all());

        return redirect()->route('duties.index')->with('success', 'Дежурный успешно обновлен.');
    }

    public function destroy($id)
    {
        $guard = Duty::findOrFail($id);
        $guard->delete();

        return redirect()->route('duties.index')->with('success', 'Дежурный удален успешно!');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new DutyImport, $request->file('excelFile'));

        return redirect()->route('duties.index')->with('success', 'Дежурные успешно загружены.');
    }

}
