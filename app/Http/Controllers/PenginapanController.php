<?php

namespace App\Http\Controllers;

use App\Models\Penginapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PenginapanController extends Controller
{
    public function index()
    {
        $penginapans = Penginapan::query()->paginate(10);
        $greeting = $this->getGreeting();
        
        return view('be.penginapan.index', [
            'title' => 'Penginapan Management',
            'penginapans' => $penginapans,
            'greeting' => $greeting
        ]);
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        
        return view('be.penginapan.create', [
            'title' => 'Create Penginapan',
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penginapan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        // Map form fields to database columns
        $data = [
            'nama_penginapan' => $validated['nama_penginapan'], // Map to correct column
            'deskripsi' => $validated['deskripsi'],
            'fasilitas' => $validated['fasilitas']
        ];

        // Handle file uploads
        for ($i = 1; $i <= 5; $i++) {
            $field = 'foto'.$i;
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('penginapan_images', 'public');
                $data[$field] = $path;
            }
        }

        Penginapan::create($data);

        return redirect()->route('penginapan.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Penginapan berhasil ditambahkan!',
            'timer' => 1500
        ]);
    }

    public function show($id)
    {  
        $greeting = $this->getGreeting();
        $penginapan = Penginapan::findOrFail($id);
        
        return view('be.penginapan.show', [
            'title' => 'Detail Penginapan',
            'penginapan' => $penginapan,
            'greeting' => $greeting
        ]);
    }

    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $penginapan = Penginapan::findOrFail($id);
        
        return view('be.penginapan.edit', [
            'title' => 'Edit Penginapan',
            'penginapan' => $penginapan,
            'greeting' => $greeting
        ]);
    }

    public function update(Request $request, $id)
    {
        $penginapan = Penginapan::findOrFail($id);
        $validated = $request->validate([
            'nama_penginapan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto5' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'delete_foto1' => 'nullable|boolean',
            'delete_foto2' => 'nullable|boolean',
            'delete_foto3' => 'nullable|boolean',
            'delete_foto4' => 'nullable|boolean',
            'delete_foto5' => 'nullable|boolean',
        ]);

        $updateData = [
            'nama_penginapan' => $validated['nama_penginapan'],
            'deskripsi' => $validated['deskripsi'],
            'fasilitas' => $validated['fasilitas']
        ];

        // Handle photo updates
        for ($i = 1; $i <= 5; $i++) {
            $field = 'foto'.$i;
            $deleteField = 'delete_foto'.$i; // <-- perbaiki dari hapus_foto ke delete_foto

            // If delete checkbox is checked
            if ($request->has($deleteField) && $request->input($deleteField)) {
                // Delete old file if exists
                if ($penginapan->$field && Storage::disk('public')->exists($penginapan->$field)) {
                    Storage::disk('public')->delete($penginapan->$field);
                }
                $updateData[$field] = null;
            }
            // If new file is uploaded
            elseif ($request->hasFile($field)) {
                // Delete old file if exists
                if ($penginapan->$field && Storage::disk('public')->exists($penginapan->$field)) {
                    Storage::disk('public')->delete($penginapan->$field);
                }
                // Store new file
                $path = $request->file($field)->store('penginapan_images', 'public');
                $updateData[$field] = $path;
            }
            // Keep existing photo if no changes
            else {
                $updateData[$field] = $penginapan->$field;
            }
        }

        $penginapan->update($updateData);

        return redirect()->route('penginapan.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Data penginapan berhasil diperbarui!',
            'timer' => 1500
        ]);
    }

    public function destroy($id)
    {
        \Log::info('Delete request received for penginapan ID: '.$id);
        
        try {
            $penginapan = Penginapan::findOrFail($id);
            \Log::info('Found penginapan: '.$penginapan->nama_penginapan);
            
            // Hapus file foto
            for ($i = 1; $i <= 5; $i++) {
                $field = 'foto'.$i;
                if ($penginapan->$field) {
                    \Log::info('Deleting photo: '.$penginapan->$field);
                    Storage::disk('public')->delete($penginapan->$field);
                }
            }
            
            $penginapan->delete();
            \Log::info('Penginapan deleted successfully');
            
            return redirect()->route('penginapan.index')->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Penginapan berhasil dihapus!',
                'timer' => 1500
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menghapus penginapan',
                'timer' => 3000
            ]);
        }
    }

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}