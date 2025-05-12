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
        $objekWisatas = ObyekWisata::with('kategoriWisata')->paginate(10);
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.index', [
            'title' => 'Objek Wisata Management',
            'objekWisatas' => $objekWisatas,
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
            'id_kategori_wisata' => 'required|exists:kategori_wisatas,id',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan setiap file ke storage
        foreach (range(1, 5) as $i) {
            $field = 'foto' . $i;
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('objekwisata', 'public');
            }
        }

        ObyekWisata::create($validated);

        return redirect()->route('objek_wisata_manage')->with('success', 'Objek Wisata created successfully.');
    }


    public function show($id)
    {
        $greeting = $this->getGreeting();
        $obyekWisata = ObyekWisata::findOrFail($id);

        return view('be.objekWisata.show', [
            'title' => 'Detail Objek Wisata',
            'obyekWisata' => $obyekWisata,
            'greeting' => $greeting
        ]);
    }


   public function edit($id)
    {
        $obyekWisata = ObyekWisata::findOrFail($id);
        $kategoris = KategoriWisata::all(); // or whatever your category model is
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.edit', [
            'title' => 'Create Objek Wisata',
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
            'id_kategori_wisata' => 'required|exists:kategori_wisatas,id',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Isi manual data lama dulu (jika tidak ada file baru)
        foreach (range(1, 5) as $i) {
            $field = 'foto' . $i;

            if ($request->hasFile($field)) {
                // Jika ada foto baru, upload dan simpan
                $validated[$field] = $request->file($field)->store('objekwisata', 'public');
            } else {
                // Jika tidak ada foto baru, pakai data lama
                $validated[$field] = $obyekWisata->$field;
            }
        }

        $obyekWisata->update($validated);

        return redirect()->route('objek_wisata_manage')->with('success', 'Objek Wisata updated successfully.');
    }



    public function destroy($id)
    {
        $obyekWisata = ObyekWisata::findOrFail($id);
        $obyekWisata->delete();
        return redirect()->route('objek_wisata_manage')->with('success', 'Objek Wisata deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 15) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}