<?php

namespace App\Http\Controllers;

use App\Models\ObyekWisata;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ObyekWisataController extends Controller
{
    public function index()
    {
        $objekWisatas = ObyekWisata::with('kategoriWisata')->get(); // Changed variable name to $objekWisatas
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.index', [
            'title' => 'Objek Wisata Management',
            'objekWisatas' => $objekWisatas, // Changed key to 'objekWisatas'
            'greeting' => $greeting
        ]);
    }

    public function create()
    {
        $kategoris = KategoriWisata::all();
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.create', [
            'title' => 'Create Objek Wisata',
            'kategoris' => $kategoris,
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi_wisata' => 'required|string',
            'id_kategori_wisata' => 'required|exists:kategori_wisata,id',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|string',
            'foto2' => 'nullable|string',
            'foto3' => 'nullable|string',
            'foto4' => 'nullable|string',
            'foto5' => 'nullable|string'
        ]);

        ObyekWisata::create($validated);

        return redirect()->route('objek-wisata.index')->with('success', 'Objek Wisata created successfully.');
    }

    public function show(ObyekWisata $obyekWisata)
    {
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.show', [
            'title' => 'Detail Objek Wisata',
            'obyekWisata' => $obyekWisata,
            'greeting' => $greeting
        ]);
    }

    public function edit(ObyekWisata $obyekWisata)
    {
        $kategoris = KategoriWisata::all();
        $greeting = $this->getGreeting();
        
        return view('be.objek_wisata.edit', [
            'title' => 'Edit Objek Wisata',
            'obyekWisata' => $obyekWisata,
            'kategoris' => $kategoris,
            'greeting' => $greeting
        ]);
    }

    public function update(Request $request, ObyekWisata $obyekWisata)
    {
        $validated = $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi_wisata' => 'required|string',
            'id_kategori_wisata' => 'required|exists:kategori_wisata,id',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|string',
            'foto2' => 'nullable|string',
            'foto3' => 'nullable|string',
            'foto4' => 'nullable|string',
            'foto5' => 'nullable|string'
        ]);

        $obyekWisata->update($validated);

        return redirect()->route('objek-wisata.index')->with('success', 'Objek Wisata updated successfully.');
    }

    public function destroy(ObyekWisata $obyekWisata)
    {
        $obyekWisata->delete();
        return redirect()->route('objek-wisata.index')->with('success', 'Objek Wisata deleted successfully.');
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