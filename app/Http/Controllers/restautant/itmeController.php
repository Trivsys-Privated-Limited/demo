<?php
namespace App\Http\Controllers\restautant;

use App\Http\Controllers\Controller;
use App\Models\item;
use Illuminate\Http\Request;

class itmeController extends Controller
{
    public function index()
    {
        $items = item::all();
        return view('backend.restaurant.manage_item',compact('items'));
    }

    public function create()
    {
        return view('backend.restaurant.add_item');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'price'       => 'required|numeric',
            'status'      => 'required',
            'item_image'  => 'nullable',
            'category'    => 'nullable',
        ]);

        $imageName = null;

        if ($request->hasFile('item_image')) {
            $imageName = time() . '.' . $request->item_image->extension();
            $request->item_image->move(public_path('images'), $imageName);
        }

        item::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => $request->status,
            'category'    => $request->category,
            'image'       => $imageName,
            'user_id'     => auth()->id() ?: 1,
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = item::findOrFail($id);
        return view('backend.restaurant.edit_item', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'price'       => 'required|numeric',
            'status'      => 'required',
            'item_image'  => 'nullable',
            'category'    => 'nullable',
        ]);

        $item = item::findOrFail($id);

        if ($request->hasFile('item_image')) {
            $imageName = time() . '.' . $request->item_image->extension();
            $request->item_image->move(public_path('images'), $imageName);
            $item->image = $imageName;
        }

        $item->name        = $request->name;
        $item->description = $request->description;
        $item->price       = $request->price;
        $item->status      = $request->status;
        $item->category    = $request->category;
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
