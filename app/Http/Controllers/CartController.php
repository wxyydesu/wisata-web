<?php

namespace App\Http\Controllers;

use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    public function add(PaketWisata $paket)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$paket->id])) {
            $cart[$paket->id]['quantity']++;
        } else {
            $cart[$paket->id] = [
                "id" => $paket->id,
                "nama" => $paket->nama,
                "quantity" => 1,
                "harga" => $paket->harga,
                "gambar" => $paket->gambar
            ];
        }
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Paket added to cart successfully!');
    }

    public function remove(PaketWisata $paket)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$paket->id])) {
            unset($cart[$paket->id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Paket removed successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaketWisata $paket)
    {
        $cart = session()->get('cart');
        
        if(isset($cart[$paket->id])) {
            $cart[$paket->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
