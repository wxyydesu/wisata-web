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
        $validatedData = $request->validate([
            'kategori_wisata' => 'required|string|max:255|unique:kategori_wisatas',
            'deskripsi' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        KategoriWisata::create($validatedData);

        return redirect()->route('kategori-wisata.index')
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

        $validatedData = $request->validate([
            'kategori_wisata' => 'required|string|max:255|unique:kategori_wisatas,kategori_wisata,'.$id,
            'deskripsi' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $category->update($validatedData);

        return redirect()->route('kategori-wisata.index')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = KategoriWisata::findOrFail($id);
        
        $category->delete();

        return redirect()->route('kategori-wisata.index')
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