<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        $greeting = $this->getGreeting();
        return view('be.bank.index', compact('banks', 'greeting'));
    }

    public function create()
    {
        $greeting = $this->getGreeting();
        return view('be.bank.create', compact('greeting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'nullable|string|max:100',
        ]);
        Bank::create($request->all());
        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $greeting = $this->getGreeting();
        $bank = Bank::findOrFail($id);
        return view('be.bank.edit', compact('bank', 'greeting'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'nullable|string|max:100',
        ]);
        $bank->update($request->all());
        return redirect()->route('bank.index')->with('success', 'Bank berhasil diupdate!');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus!');
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