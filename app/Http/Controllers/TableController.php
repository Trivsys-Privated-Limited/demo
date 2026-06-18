<?php
namespace App\Http\Controllers;

use App\Models\table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    public function index()
    {
        $tables = table::all();
        return view('backend.table', compact('tables'));
    }

    public function create()
    {
        return view('backend.add_table');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required',
        ]);

        table::create([
            'table_number' => $request->table_number,
            'qr_token'     => Str::uuid(),
            'user_id'      => auth()->id() ?: 1,
        ]);

        return redirect()->route('tables.index')->with('success', 'Table created successfully.');
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
