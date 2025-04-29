<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::with('kategoriBerita')->get();
        $greeting = $this->getGreeting();
        
        return view('be.berita.index', [
            'title' => 'Berita Management',
            'beritas' => $beritas,
            'greeting' => $greeting
        ]);
    }

    public function create()
    {
        $kategoris = KategoriBerita::all();
        $greeting = $this->getGreeting();
        
        return view('be.berita.create', [
            'title' => 'Create Berita',
            'kategoris' => $kategoris,
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ludul' => 'required|string|max:255',
            'berita' => 'required|string',
            'post' => 'required|date',
            'id_kategori_bertia' => 'required|exists:kategori_bertia,id',
            'foto' => 'nullable|string'
        ]);

        Berita::create($validated);

        return redirect()->route('berita.index')->with('success', 'Berita created successfully.');
    }

    public function show(Berita $berita)
    {
        $greeting = $this->getGreeting();
        
        return view('be.berita.show', [
            'title' => 'Detail Berita',
            'berita' => $berita,
            'greeting' => $greeting
        ]);
    }

    public function edit(Berita $berita)
    {
        $kategoris = KategoriBerita::all();
        $greeting = $this->getGreeting();
        
        return view('be.berita.edit', [
            'title' => 'Edit Berita',
            'berita' => $berita,
            'kategoris' => $kategoris,
            'greeting' => $greeting
        ]);
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'ludul' => 'required|string|max:255',
            'berita' => 'required|string',
            'post' => 'required|date',
            'id_kategori_bertia' => 'required|exists:kategori_bertia,id',
            'foto' => 'nullable|string'
        ]);

        $berita->update($validated);

        return redirect()->route('berita.index')->with('success', 'Berita updated successfully.');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('berita.index')->with('success', 'Berita deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) {
            return 'Selamat Pagi';
        } elseif ($hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
}