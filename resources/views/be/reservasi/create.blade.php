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

                        <form class="forms-sample" method="POST" action="{{ route('reservasi_store') }}">
                            @csrf

                            {{-- Pelanggan --}}
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                    <option value="" disabled selected>Pilih Pelanggan</option>
                                    @foreach($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}" {{ old('id_pelanggan') == $pelanggan->id ? 'selected' : '' }}>
                                            {{ $pelanggan->nama_lengkap }} ({{ $pelanggan->no_hp }})
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
                                            {{ $paket->nama_paket }} (Rp {{ number_format($paket->harga_per_pack, 0, ',', '.') }})
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
                                <label for="reservasi_wisata">Tanggal Reservasi</label>
                                <input type="datetime-local" class="form-control" id="reservasi_wisata" name="reservasi_wisata" value="{{ old('reservasi_wisata') }}" required>
                                @error('reservasi_wisata')
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
                                <input type="text" class="form-control" id="harga" name="harga" readonly value="{{ old('harga') }}">
                            </div>

                            {{-- Diskon --}}
                            <div class="form-group">
                                <label for="diskon">Diskon (%)</label>
                                <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="{{ old('diskon', 0) }}">
                                @error('diskon')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Nilai Diskon (auto-calculated) --}}
                            <div class="form-group">
                                <label for="nilai_diskon">Nilai Diskon (Rp)</label>
                                <input type="text" class="form-control" id="nilai_diskon" name="nilai_diskon" readonly value="{{ old('nilai_diskon', 0) }}">
                            </div>

                            {{-- Total Bayar (auto-calculated) --}}
                            <div class="form-group">
                                <label for="total_bayar">Total Bayar</label>
                                <input type="text" class="form-control" id="total_bayar" name="total_bayar" readonly value="{{ old('total_bayar') }}">
                            </div>

                            {{-- Status --}}
                            <div class="form-group">
                                <label for="status_reservasi_wisata">Status Reservasi</label>
                                <select class="form-control" id="status_reservasi_wisata" name="status_reservasi_wisata" required>
                                    <option value="pesan" {{ old('status_reservasi_wisata') == 'pesan' ? 'selected' : '' }}>Pesan</option>
                                    <option value="dibayar" {{ old('status_reservasi_wisata') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="selesai" {{ old('status_reservasi_wisata') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status_reservasi_wisata')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- File Upload --}}
                            <div class="form-group">
                                <label for="file_buku_if">Upload Bukti Transfer</label>
                                <input type="file" class="form-control" id="file_buku_if" name="file_buku_if">
                                @error('file_buku_if')
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

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
        const harga = paketSelect.options[paketSelect.selectedIndex]?.dataset.harga || 0;
        const peserta = jumlahPeserta.value || 0;
        const diskon = diskonInput.value || 0;
        
        const subtotal = harga * peserta;
        const nilaiDiskon = subtotal * (diskon / 100);
        const total = subtotal - nilaiDiskon;
        
        document.getElementById('harga').value = formatRupiah(harga);
        document.getElementById('nilai_diskon').value = formatRupiah(nilaiDiskon);
        document.getElementById('total_bayar').value = formatRupiah(total);
    }
    
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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