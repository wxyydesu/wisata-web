@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <h4 class="font-weight-bold mb-4">Manajemen Diskon Paket Wisata</h4>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('diskon.updateAll') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Paket Wisata</th>
                                    <th>Diskon (%)</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paket as $p)
                                    @php
                                        $d = $diskon[$p->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $p->nama_paket }}
                                            <input type="hidden" name="paket_id[]" value="{{ $p->id }}">
                                        </td>
                                        <td>
                                            <input type="number" name="persen[{{ $p->id }}]" class="form-control" min="0" max="100" step="0.01"
                                                value="{{ $d ? $d->persen : 0 }}">
                                        </td>
                                        <td>
                                            <input type="date" name="tanggal_mulai[{{ $p->id }}]" class="form-control"
                                                value="{{ $d && $d->tanggal_mulai ? date('Y-m-d', strtotime($d->tanggal_mulai)) : '' }}">
                                        </td>
                                        <td>
                                            <input type="date" name="tanggal_akhir[{{ $p->id }}]" class="form-control"
                                                value="{{ $d && $d->tanggal_akhir ? date('Y-m-d', strtotime($d->tanggal_akhir)) : '' }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="aktif[]" value="{{ $p->id }}" {{ $d && $d->aktif ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Semua Diskon</button>
                </form>
            </div>
        </div>
    </div>
@endsection
