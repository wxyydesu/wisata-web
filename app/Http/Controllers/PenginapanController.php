<?php

namespace App\Http\Controllers;

use App\Models\Penginapan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenginapanController extends Controller
{
    public function index()
    {
        $penginapans = Penginapan::all();
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
            'foto1' => 'nullable|string',
            'foto2' => 'nullable|string',
            'foto3' => 'nullable|string',
            'foto4' => 'nullable|string',
            'foto5' => 'nullable|string'
        ]);

        Penginapan::create($validated);

        return redirect()->route('penginapan.index')->with('success', 'Penginapan created successfully.');
    }

    public function show(Penginapan $penginapan)
    {
        $greeting = $this->getGreeting();
        
        return view('be.penginapan.show', [
            'title' => 'Detail Penginapan',
            'penginapan' => $penginapan,
            'greeting' => $greeting
        ]);
    }

    public function edit(Penginapan $penginapan)
    {
        $greeting = $this->getGreeting();
        
        return view('be.penginapan.edit', [
            'title' => 'Edit Penginapan',
            'penginapan' => $penginapan,
            'greeting' => $greeting
        ]);
    }

    public function update(Request $request, Penginapan $penginapan)
    {
        $validated = $request->validate([
            'nama_penginapan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'fasilitas' => 'required|string',
            'foto1' => 'nullable|string',
            'foto2' => 'nullable|string',
            'foto3' => 'nullable|string',
            'foto4' => 'nullable|string',
            'foto5' => 'nullable|string'
        ]);

        $penginapan->update($validated);

        return redirect()->route('penginapan.index')->with('success', 'Penginapan updated successfully.');
    }

    public function destroy(Penginapan $penginapan)
    {
        $penginapan->delete();
        return redirect()->route('penginapan.index')->with('success', 'Penginapan deleted successfully.');
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