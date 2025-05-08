<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBerita;

class KategoriBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $greeting = $this->getGreeting();
        $categories = KategoriBerita::orderBy('created_at', 'desc')->get();
        
        return view('be.kategori_berita.index', [
            'greeting' => $greeting,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.kategori_berita.create', ['greeting' => $greeting]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_berita' => 'required|string|max:255|unique:kategori_beritas',
        ]);

        KategoriBerita::create($validatedData);

        return redirect()->route('kategori_berita_manage')
                         ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $category = KategoriBerita::findOrFail($id);
        return view('be.kategori_berita.edit', [
            'greeting' => $greeting,
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = KategoriBerita::findOrFail($id);

        $validatedData = $request->validate([
            'kategori_berita' => 'required|string|max:255|unique:kategori_beritas,kategori_berita,'.$id,
        ]);

        $category->update($validatedData);

        return redirect()->route('kategori_berita_manage')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = KategoriBerita::findOrFail($id);
        $category->delete();

        return redirect()->route('kategori_berita_manage')
                         ->with('success', 'Category deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) return 'Good Morning';
        if ($hour < 18) return 'Good Afternoon';
        return 'Good Evening';
    }
}
