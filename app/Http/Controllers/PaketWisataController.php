<?php

namespace App\Http\Controllers;

use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaketWisataController extends Controller
{
    public function index()
    {
        $greeting = $this->getGreeting();
        $pakets = PaketWisata::orderBy('created_at', 'desc')->get();
        
        return view('be.paket.index', [
            'greeting' => $greeting,
            'pakets' => $pakets
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.paket.create', [
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'harga_per_pack' => 'required|integer',
            'foto1' => 'required|image|mimes:jpeg,png,jpg,gif',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Handle file uploads
        $imageFields = ['foto1', 'foto2', 'foto3', 'foto4', 'foto5'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('paket-wisata-images', 'public');
                $validatedData[$field] = $path;
            }
        }

        PaketWisata::create($validatedData);

        return redirect()->route('paket.index')
                         ->with('success', 'Paket wisata created successfully.');
    }

    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $paket = PaketWisata::findOrFail($id);
        
        return view('be.paket.edit', [
            'greeting' => $greeting,
            'paket' => $paket
        ]);
    }

    public function update(Request $request, $id)
    {
        $paket = PaketWisata::findOrFail($id);

        $validatedData = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'harga_per_pack' => 'required|integer',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Handle file uploads
        $imageFields = ['foto1', 'foto2', 'foto3', 'foto4', 'foto5'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($paket->$field) {
                    Storage::disk('public')->delete($paket->$field);
                }
                
                $path = $request->file($field)->store('paket-wisata-images', 'public');
                $validatedData[$field] = $path;
            } else {
                // Keep the existing image if no new one was uploaded
                $validatedData[$field] = $paket->$field;
            }
        }

        $paket->update($validatedData);

        return redirect()->route('paket.index')
                         ->with('success', 'Paket wisata updated successfully.');
    }

    public function destroy($id)
    {
        $paket = PaketWisata::findOrFail($id);
        
        // Delete all images if they exist
        $imageFields = ['foto1', 'foto2', 'foto3', 'foto4', 'foto5'];
        foreach ($imageFields as $field) {
            if ($paket->$field) {
                Storage::disk('public')->delete($paket->$field);
            }
        }
        
        $paket->delete();

        return redirect()->route('paket.index')
                         ->with('success', 'Paket wisata deleted successfully.');
    }

    public function deleteImage(Request $request, $id, $field = null)
    {
        $paket = PaketWisata::findOrFail($id);
        $imageFields = ['foto1', 'foto2', 'foto3', 'foto4', 'foto5'];

        // Ambil field dari request jika tidak ada di parameter
        $field = $field ?? $request->input('field');

        if (!in_array($field, $imageFields)) {
            return back()->with('error', 'Invalid image field.');
        }

        if ($paket->$field) {
            \Storage::disk('public')->delete($paket->$field);
            $paket->$field = null;
            $paket->save();
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = now()->hour;
        
        if ($hour < 12) return 'Good Morning';
        if ($hour < 18) return 'Good Afternoon';
        return 'Good Evening';
    }
}