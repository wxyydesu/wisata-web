<?php

namespace App\Http\Controllers;

use App\Models\ObyekWisata;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ObyekWisataController extends Controller
{
    public function index()
    {
        $objekWisatas = ObyekWisata::with('kategoriWisata')->paginate(10);
        $greeting = $this->getGreeting();
        
        return view('be.objekWisata.index', [
            'title' => 'Objek Wisata indexment',
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

        return redirect()->route('wisata.index')->with('success', 'Objek Wisata created successfully.');
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

    public function update(Request $request, $id)
    {
        $obyekWisata = ObyekWisata::findOrFail($id);
        
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
            'delete_foto1' => 'nullable|boolean',
            'delete_foto2' => 'nullable|boolean',
            'delete_foto3' => 'nullable|boolean',
            'delete_foto4' => 'nullable|boolean',
            'delete_foto5' => 'nullable|boolean',
        ]);

            // Handle photo deletions first
            for ($i = 1; $i <= 5; $i++) {
            $deleteField = 'delete_foto'.$i;
            $fotoField = 'foto'.$i;
            
            if ($request->has($deleteField) && $request->$deleteField == '1') {
                // Delete the file from storage
                if ($obyekWisata->$fotoField && Storage::disk('public')->exists($obyekWisata->$fotoField)) {
                    Storage::disk('public')->delete($obyekWisata->$fotoField);
                }
                // Clear the database field
                $obyekWisata->$fotoField = null;
            }
        }

        // Handle file uploads
        for ($i = 1; $i <= 5; $i++) {
            $fotoField = 'foto'.$i;
            if ($request->hasFile($fotoField)) {
                // Delete old file if exists
                if ($obyekWisata->$fotoField && Storage::disk('public')->exists($obyekWisata->$fotoField)) {
                    Storage::disk('public')->delete($obyekWisata->$fotoField);
                }
                // Store new file
                $obyekWisata->$fotoField = $request->file($fotoField)->store('objekwisata', 'public');
            }
        }

        // Update other fields
        $obyekWisata->nama_wisata = $validated['nama_wisata'];
        $obyekWisata->id_kategori_wisata = $validated['id_kategori_wisata'];
        $obyekWisata->deskripsi_wisata = $validated['deskripsi_wisata'];
        $obyekWisata->fasilitas = $validated['fasilitas'];
        
        $obyekWisata->save();

        return redirect()->route('wisata.index')->with('success', 'Objek Wisata updated successfully.');
    }




    public function destroy($id)
    {
        $obyekWisata = ObyekWisata::findOrFail($id);
        $obyekWisata->delete();
        return redirect()->route('wisata.index')->with('success', 'Objek Wisata deleted successfully.');
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