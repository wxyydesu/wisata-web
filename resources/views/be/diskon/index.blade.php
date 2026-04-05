@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="content-wrapper">
    {{-- <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title">Manajemen Diskon Paket Wisata</h4>
    </div> --}}

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kelola Diskon Semua Paket Wisata</h4>
                    <p class="card-description">
                        Atur diskon persentase dan periode berlaku untuk setiap paket
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('diskon.updateAll') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:35%">Paket Wisata</th>
                                        <th style="width:12%" class="text-center">Diskon (%)</th>
                                        <th style="width:18%">Tanggal Mulai</th>
                                        <th style="width:18%">Tanggal Berakhir</th>
                                        <th style="width:12%" class="text-center">Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paket as $p)
                                        @php
                                            $d = $diskon[$p->id] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $p->nama_paket }}</strong>
                                                <input type="hidden" name="paket_id[]" value="{{ $p->id }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="persen[{{ $p->id }}]" class="form-control form-control-sm text-center" 
                                                    min="0" max="100" step="0.01" value="{{ $d ? $d->persen : 0 }}" 
                                                    style="max-width:100px;margin:0 auto;">
                                            </td>
                                            <td>
                                                <input type="date" name="tanggal_mulai[{{ $p->id }}]" class="form-control form-control-sm" 
                                                    value="{{ $d && $d->tanggal_mulai ? date('Y-m-d', strtotime($d->tanggal_mulai)) : '' }}">
                                            </td>
                                            <td>
                                                <input type="date" name="tanggal_akhir[{{ $p->id }}]" class="form-control form-control-sm" 
                                                    value="{{ $d && $d->tanggal_akhir ? date('Y-m-d', strtotime($d->tanggal_akhir)) : '' }}">
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" name="aktif[]" value="{{ $p->id }}" 
                                                        id="aktif_{{ $p->id }}" {{ $d && $d->aktif ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted">Tidak ada paket wisata</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Simpan Semua Diskon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
