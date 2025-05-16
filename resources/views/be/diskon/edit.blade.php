@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 grid-margin">
                <h4 class="font-weight-bold mb-4">Edit Diskon Paket Wisata</h4>
                <form method="POST" action="{{ route('diskon.update', $diskon->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="paket_id[]" value="{{ $paket->id }}">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" value="{{ $paket->nama_paket }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diskon (%)</label>
                        <input type="number" name="persen[{{ $paket->id }}]" class="form-control" min="0" max="100" step="0.01"
                            value="{{ $diskon ? $diskon->persen : 0 }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai[{{ $paket->id }}]" class="form-control"
                            value="{{ $diskon && $diskon->tanggal_mulai ? $diskon->tanggal_mulai->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir[{{ $paket->id }}]" class="form-control"
                            value="{{ $diskon && $diskon->tanggal_akhir ? $diskon->tanggal_akhir->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="aktif[]" value="{{ $paket->id }}" class="form-check-input"
                            {{ $diskon && $diskon->aktif ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Diskon</button>
                    <a href="{{ route('diskon.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
