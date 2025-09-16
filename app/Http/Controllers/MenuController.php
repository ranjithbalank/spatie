<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $menus = Menu::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('name')
            ->paginate(5);

        return view('menus.index', compact('menus', 'search'));
    }



    public function create()
    {
        if (!auth()->user()->can('create menus')) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'You do not have permission');
        }
        $parents = Menu::whereNull('parent_id')->get();
        return view('menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'icon' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }
    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id) // prevent assigning itself as parent
            ->get();

        return view('menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'icon' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $menu->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
