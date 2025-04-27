<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            $now = Carbon::now();

            $greeting = '';

            if ($now->hour >= 5 && $now->hour < 12) {
                $greeting = 'Good Morning';
            } elseif ($now->hour >= 12 && $now->hour < 18) {
                $greeting = 'Good Evening';
            } else {
                $greeting = 'Good Night';
            }

            return view('be.bendahara.index', [
                'title' => 'Bendahara',
                'greeting' => $greeting,
            ]);
        }
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}