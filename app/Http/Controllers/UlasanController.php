<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Store a newly created ulasan in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'penginapan_id' => 'nullable|exists:penginapans,id',
                'paket_wisata_id' => 'nullable|exists:paket_wisatas,id',
                'user_id' => 'required|exists:users,id',
                'rating' => 'required|integer|min:1|max:5',
                'komentar' => 'required|string|max:1000',
                'reservasi_id' => 'nullable|exists:reservasis,id',
                'penginapan_reservasi_id' => 'nullable|exists:penginapan_reservasis,id',
            ]);

            // Ensure user is authenticated
            if ($validated['user_id'] != Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            Ulasan::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ulasan berhasil ditambahkan'
                ]);
            }

            return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan ulasan: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan ulasan');
        }
    }

    /**
     * Delete a ulasan from storage.
     */
    public function destroy(Ulasan $ulasan)
    {
        try {
            // Authorization check
            if ($ulasan->user_id != Auth::id() && !Auth::user()->isAdmin) {
                abort(403, 'Unauthorized action.');
            }

            $ulasan->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ulasan berhasil dihapus'
                ]);
            }

            return redirect()->back()->with('success', 'Ulasan berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus ulasan'
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menghapus ulasan');
        }
    }

    /**
     * Update a ulasan in storage.
     */
    public function update(Request $request, Ulasan $ulasan)
    {
        try {
            // Authorization check
            if ($ulasan->user_id != Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'komentar' => 'required|string|max:1000',
            ]);

            $ulasan->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ulasan berhasil diperbarui'
                ]);
            }

            return redirect()->back()->with('success', 'Ulasan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui ulasan'
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui ulasan');
        }
    }
}
