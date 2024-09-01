<?php

// app/Http/Controllers/GuardController.php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Guard;

class GuardController extends Controller
{
    public function index()
    {
        $guards = Guard::all();
        return view('guards.index', compact('guards'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('guards.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'telegram_link' => 'nullable|url',
            'department_id' => 'required|exists:departments,id',
        ]);

        Guard::create($request->all());

        return redirect()->route('guards.index')->with('success', 'Дежурный успешно добавлен.');
    }

    public function edit($id)
    {
        $guard = Guard::findOrFail($id);
        $departments = Department::all();
        return view('guards.edit', compact('guard', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'telegram_link' => 'nullable|url',
            'department_id' => 'required|exists:departments,id',
        ]);

        $guard = Guard::findOrFail($id);
        $guard->update($request->all());

        return redirect()->route('guards.index')->with('success', 'Дежурный успешно обновлен.');
    }

    public function destroy($id)
    {
        $guard = Guard::findOrFail($id);
        $guard->delete();

        return redirect()->route('guards.index')->with('success', 'Дежурный удален успешно!');
    }

}
