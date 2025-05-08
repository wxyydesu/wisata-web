<?php

namespace App\Http\Controllers;

use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KategoriWisataController extends Controller
{
    public function index()
    {
        $greeting = $this->getGreeting();
        $categories = KategoriWisata::orderBy('created_at', 'desc')->get();
        
        return view('be.kategori_wisata.index', [
            'greeting' => $greeting,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.kategori_wisata.create', [
            'greeting' => $greeting,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_wisata' => 'required|string|max:255|unique:kategori_wisatas',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/kategori_wisata');
            $data['foto'] = str_replace('public/', '', $path);
        }

        KategoriWisata::create($data);

        return redirect()->route('kategori_wisata_manage')
                         ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = KategoriWisata::findOrFail($id);
        $greeting = $this->getGreeting();
        return view('be.kategori_wisata.edit', [
            'greeting' => $greeting,
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = KategoriWisata::findOrFail($id);

        $request->validate([
            'kategori_wisata' => 'required|string|max:255|unique:kategori_wisatas,kategori_wisata,'.$id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $data = $request->except('icon');

        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($category->icon) {
                Storage::delete('public/' . $category->icon);
            }
            
            $path = $request->file('icon')->store('public/kategori_wisata');
            $data['icon'] = str_replace('public/', '', $path);
        }

        $category->update($data);

        return redirect()->route('kategori_wisata_manage')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = KategoriWisata::findOrFail($id);

        // Delete icon if exists
        if ($category->icon) {
            Storage::delete('public/' . $category->icon);
        }

        $category->delete();

        return redirect()->route('kategori_wisata_manage')
                         ->with('success', 'Category deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}