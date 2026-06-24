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
        'name'        => 'required|string|max:255',
        'description' => 'required|string',
        'price'       => 'required|numeric|min:0', // Price minus me na jaa sake
        'status'      => 'required|in:active,inactive', // Sirf active ya inactive accept kare
        'item_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp,avif', // Image format aur size restriction
        'category'    => 'required|string', // Kyunki drop-down lagaya hy, toh ab yeh required hona chahiye
        // 💡 FIX 1: Agar dropdown me custom_option select ho, toh text input required ho jaye
        'custom_category' => 'required_if:category,custom_option|nullable|string|max:100',
    ]);

        $imageName = null;

        if ($request->hasFile('item_image')) {
            $imageName = time() . '.' . $request->item_image->extension();
            $request->item_image->move(public_path('images'), $imageName);
        }
        
// 💡 FIX 2: Check karein ke category dropdown wali save karni hy ya custom text input wali
        $finalCategory = $request->category;
    if ($request->category === 'custom_option') {
        $finalCategory = trim($request->custom_category);
    }

        item::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => $request->status,
            'category'    => $finalCategory, // 💡 FIX 3: Yahan hamari final category chali gayi (e.g. Burgers)
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
