@extends('be.master')
@section('sidebar')
  @include('be.sidebar')
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Reservasi</h4>
                        <p class="card-description">Form Tambah Data Reservasi</p>

                        <form class="forms-sample" method="POST" action="{{ route('reservasi_store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Pelanggan --}}
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                    <option value="" disabled selected>Pilih Pelanggan</option>
                                    @foreach($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}" {{ old('id_pelanggan') == $pelanggan->id ? 'selected' : '' }}>
                                            {{ $pelanggan->name_lengkap }} ({{ $pelanggan->no_hp }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_pelanggan')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Paket Wisata --}}
                            <div class="form-group">
                                <label for="id_paket">Paket Wisata</label>
                                <select class="form-control" id="id_paket" name="id_paket" required>
                                    <option value="" disabled selected>Pilih Paket Wisata</option>
                                    @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}" data-harga="{{ $paket->harga_per_pack }}" {{ old('id_paket') == $paket->id ? 'selected' : '' }}>
                                            {{ $paket->name_paket }} (Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_paket')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Tanggal Reservasi --}}
                            <div class="form-group">
                                <label for="tgl_reservasi">Tanggal Reservasi</label>
                                <input type="datetime-local" class="form-control" id="tgl_reservasi" name="tgl_reservasi" 
                                       value="{{ old('tgl_reservasi') }}" required min="{{ date('Y-m-d\TH:i') }}">
                                @error('tgl_reservasi')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Jumlah Peserta --}}
                            <div class="form-group">
                                <label for="jumlah_peserta">Jumlah Peserta</label>
                                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="{{ old('jumlah_peserta', 1) }}" required>
                                @error('jumlah_peserta')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Harga (auto-filled) --}}
                            <div class="form-group">
                                <label for="harga">Harga Paket</label>
                                <input type="text" class="form-control" id="harga" readonly value="{{ old('harga') }}">
                                <input type="hidden" id="harga_value" name="harga" value="{{ old('harga') }}">
                            </div>

                            {{-- Diskon --}}
                            <div class="form-group">
                                <label for="diskon">Diskon (%)</label>
                                <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="{{ old('diskon', 0) }}" step="0.01">
                                @error('diskon')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Nilai Diskon (auto-calculated) --}}
                            <div class="form-group">
                                <label for="nilai_diskon_display">Nilai Diskon (Rp)</label>
                                <input type="text" class="form-control" id="nilai_diskon_display" readonly value="{{ old('nilai_diskon_display', 'Rp 0') }}">
                                <input type="hidden" id="nilai_diskon" name="nilai_diskon" value="{{ old('nilai_diskon', 0) }}">
                            </div>

                            {{-- Total Bayar (auto-calculated) --}}
                            <div class="form-group">
                                <label for="total_bayar_display">Total Bayar</label>
                                <input type="text" class="form-control" id="total_bayar_display" readonly value="{{ old('total_bayar') }}">
                                <input type="hidden" id="total_bayar" name="total_bayar" value="{{ old('total_bayar') }}">
                            </div>

                            {{-- Status --}}
                            <div class="form-group">
                                <label for="status_reservasi">Status Reservasi</label>
                                <select class="form-control" id="status_reservasi" name="status_reservasi" required>
                                    <option value="pesan" {{ old('status_reservasi') == 'pesan' ? 'selected' : '' }}>Pesan</option>
                                    <option value="dibayar" {{ old('status_reservasi') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="selesai" {{ old('status_reservasi') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status_reservasi')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- File Upload --}}
                            <div class="form-group">
                                <label for="file_bukti_tf">Upload Bukti Transfer</label>
                                <input type="file" class="form-control" id="file_bukti_tf" name="file_bukti_tf">
                                @error('file_bukti_tf')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <a href="{{ route('reservasi_manage') }}" class="btn btn-light">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate harga, diskon, and total
    const paketSelect = document.getElementById('id_paket');
    const jumlahPeserta = document.getElementById('jumlah_peserta');
    const diskonInput = document.getElementById('diskon');
    
    function calculateTotal() {
        const harga = parseFloat(paketSelect.options[paketSelect.selectedIndex]?.dataset.harga) || 0;
        const peserta = parseInt(jumlahPeserta.value) || 0;
        const diskon = parseFloat(diskonInput.value) || 0;
        
        const subtotal = harga * peserta;
        const nilaiDiskon = subtotal * (diskon / 100);
        const total = subtotal - nilaiDiskon;
        
        // Update display fields
        document.getElementById('harga').value = formatRupiah(harga);
        document.getElementById('harga_value').value = harga;
        document.getElementById('nilai_diskon_display').value = formatRupiah(nilaiDiskon);
        document.getElementById('nilai_diskon').value = nilaiDiskon;
        document.getElementById('total_bayar_display').value = formatRupiah(total);
        document.getElementById('total_bayar').value = total;
    }
    
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
    
    // Event listeners
    paketSelect.addEventListener('change', calculateTotal);
    jumlahPeserta.addEventListener('input', calculateTotal);
    diskonInput.addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
});
</script>
@endsection