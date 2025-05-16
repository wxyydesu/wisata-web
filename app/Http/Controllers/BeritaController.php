<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $greeting = $this->getGreeting();
        $news = Berita::with('kategoriBerita')->orderBy('tgl_post', 'desc')->get();
        
        return view('be.berita.index', [
            'greeting' => $greeting,
            'news' => $news
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        $categories = KategoriBerita::all();
        return view('be.berita.create', [
            'greeting' => $greeting,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'berita' => 'required|string',
            'tgl_post' => 'required|date',
            'id_kategori_berita' => 'required|exists:kategori_beritas,id',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('berita_images', 'public');
            $validatedData['foto'] = $path;
        }

        Berita::create($validatedData);

        return redirect()->route('berita.index')
                         ->with('success', 'Berita created successfully.');
    }

    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $news = Berita::findOrFail($id);
        $categories = KategoriBerita::all();
        
        return view('be.berita.edit', [
            'greeting' => $greeting,
            'news' => $news,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'berita' => 'required|string',
            'tgl_post' => 'required|date',
            'id_kategori_berita' => 'required|exists:kategori_beritas,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old image if exists
            if ($berita->foto) {
                Storage::disk('public')->delete($berita->foto);
            }
            
            $path = $request->file('foto')->store('berita_images', 'public');
            $validatedData['foto'] = $path;
        }

        $berita->update($validatedData);

        return redirect()->route('berita.index')
                         ->with('success', 'Berita updated successfully.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Delete image if exists
        if ($berita->foto) {
            Storage::disk('public')->delete($berita->foto);
        }
        
        $berita->delete();

        return redirect()->route('berita.index')
                         ->with('success', 'Berita deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) return 'Good Morning';
        if ($hour < 18) return 'Good Afternoon';
        return 'Good Evening';
    }
}