@extends('be.master')
@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="font-weight-bold mb-0">Edit Reservasi</h4>
                    </div>
                    <div>
                        <a href="{{ route('reservasi.index') }}" class="btn btn-primary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('reservasi.update', $reservasi->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group">
                                        <label for="id_pelanggan">Pelanggan</label>
                                        <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                            <option value="" disabled>Pilih Pelanggan</option>
                                            @foreach($pelanggan as $p)
                                                <option value="{{ $p->id }}" {{ $reservasi->id_pelanggan == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_lengkap }} ({{ $p->no_hp }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_pelanggan')
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_paket">Paket Wisata</label>
                                        <select class="form-control" id="id_paket" name="id_paket" required>
                                            <option value="" disabled>Pilih Paket Wisata</option>
                                            @foreach($paket as $p)
                                                <option value="{{ $p->id }}" 
                                                        data-harga="{{ $p->harga_per_pack }}"
                                                        data-diskon="{{ $diskonAktif[$p->id] ?? 0 }}"
                                                        {{ $reservasi->id_paket == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_paket }} (Rp {{ number_format($p->harga_per_pack, 0, ',', '.') }})
                                                    @if(($diskonAktif[$p->id] ?? 0) > 0)
                                                        - Diskon {{ $diskonAktif[$p->id] }}%
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_paket')
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_mulai">Tanggal Mulai</label>
                                        <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" 
                                            id="tgl_mulai" name="tgl_mulai" 
                                            value="{{ old('tgl_mulai', $reservasi->tgl_mulai) }}" 
                                            min="{{ date('Y-m-d') }}" required>
                                        @error('tgl_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_akhir">Tanggal Akhir</label>
                                        <input type="date" class="form-control @error('tgl_akhir') is-invalid @enderror" 
                                            id="tgl_akhir" name="tgl_akhir" 
                                            value="{{ old('tgl_akhir', $reservasi->tgl_akhir) }}" 
                                            min="{{ date('Y-m-d') }}" required>
                                        @error('tgl_akhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lama_reservasi">Lama Reservasi (Hari)</label>
                                        <input type="number" class="form-control" id="lama_reservasi" name="lama_reservasi" readonly value="{{ old('lama_reservasi', $reservasi->lama_reservasi) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="jumlah_peserta">Jumlah Peserta</label>
                                    <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="{{ old('jumlah_peserta', $reservasi->jumlah_peserta) }}" required>
                                    @error('jumlah_peserta')
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="harga">Harga Paket</label>
                                        <input type="text" class="form-control" id="harga" readonly value="{{ old('harga', number_format($reservasi->harga, 0, ',', '.')) }}">
                                        <input type="hidden" id="harga_value" name="harga" value="{{ old('harga', $reservasi->harga) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diskon">Diskon (%)</label>
                                        <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="{{ old('diskon', $reservasi->diskon) }}" step="0.01" readonly>
                                        @error('diskon')
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nilai_diskon_display">Nilai Diskon (Rp)</label>
                                        <input type="text" class="form-control" id="nilai_diskon_display" readonly value="{{ old('nilai_diskon_display', number_format($reservasi->nilai_diskon, 0, ',', '.')) }}">
                                        <input type="hidden" id="nilai_diskon" name="nilai_diskon" value="{{ old('nilai_diskon', $reservasi->nilai_diskon) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="total_bayar_display">Total Bayar</label>
                                        <input type="text" class="form-control" id="total_bayar_display" readonly value="{{ old('total_bayar_display', number_format($reservasi->total_bayar, 0, ',', '.')) }}">
                                        <input type="hidden" id="total_bayar" name="total_bayar" value="{{ old('total_bayar', $reservasi->total_bayar) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="file_bukti_tf">Upload Bukti Transfer</label>
                                    <input type="file" class="form-control" id="file_bukti_tf" name="file_bukti_tf">
                                    @if($reservasi->file_bukti_tf)
                                        <div class="mt-2">
                                            <p>File saat ini: 
                                                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#buktiTFModal">
                                                    Lihat Bukti Transfer
                                                </button>
                                            </p>
                                        </div>
                                    @endif
                                    @error('file_bukti_tf')
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status_reservasi">Status Reservasi</label>
                                <select class="form-control" id="status_reservasi" name="status_reservasi" required>
                                    <option value="pesan" {{ $reservasi->status_reservasi == 'pesan' ? 'selected' : '' }}>Pesan</option>
                                    <option value="dibayar" {{ $reservasi->status_reservasi == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="selesai" {{ $reservasi->status_reservasi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ $reservasi->status_reservasi == 'ditolak' ? 'selected' : '' }}>ditolak</option>
                                </select>
                                @error('status_reservasi')
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

                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @if($reservasi->file_bukti_tf)
    <div class="alert alert-info">
        <p>Debug Info:</p>
        <p>Path in DB: {{ $reservasi->file_bukti_tf }}</p>
        <p>Storage Path: storage/app/public/{{ $reservasi->file_bukti_tf }}</p>
        <p>Public Path: public/storage/{{ $reservasi->file_bukti_tf }}</p>
        <p>File Exists: {{ Storage::exists('public/' . $reservasi->file_bukti_tf) ? 'Yes' : 'No' }}</p>
        <p>Storage URL: {{ Storage::url($reservasi->file_bukti_tf) }}</p>
    </div>
@endif --}}

@if($reservasi->file_bukti_tf)
<div class="modal fade" id="buktiTFModal" tabindex="-1" aria-labelledby="buktiTFModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buktiTFModalLabel">Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @php
                    $fileExtension = pathinfo($reservasi->file_bukti_tf, PATHINFO_EXTENSION);
                    $fileUrl = asset('storage/' . $reservasi->file_bukti_tf);
                @endphp
                
                @if(strtolower($fileExtension) === 'pdf')
                    <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="600px">
                @else
                    <img src="{{ $fileUrl }}" alt="Bukti Transfer" class="img-fluid">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Tutup
                </button>
                <a href="{{ $fileUrl }}" download class="btn btn-primary">
                    <i class="mdi mdi-download"></i> Unduh
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate harga, diskon, and total
    const paketSelect = document.getElementById('id_paket');
    const jumlahPeserta = document.getElementById('jumlah_peserta');
    const diskonInput = document.getElementById('diskon');
    
    function calculateTotal() {
        const selectedOption = paketSelect.options[paketSelect.selectedIndex];
        const harga = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
        const peserta = parseInt(jumlahPeserta.value) || 1;
        // Ambil diskon otomatis dari data attribute (readonly di edit juga, agar konsisten)
        const diskon = selectedOption ? parseFloat(selectedOption.getAttribute('data-diskon')) || 0 : 0;
        diskonInput.value = diskon;

        const subtotal = harga * peserta;
        const nilaiDiskon = subtotal * (diskon / 100);
        const total = subtotal - nilaiDiskon;

        // Update display fields
        document.getElementById('harga').value = formatRupiah(harga);
        document.getElementById('harga_value').value = harga;
        document.getElementById('nilai_diskon_display').value = formatRupiah(nilaiDiskon);
        document.getElementById('nilai_diskon').value = nilaiDiskon.toFixed(2);
        document.getElementById('total_bayar_display').value = formatRupiah(total);
        document.getElementById('total_bayar').value = total.toFixed(2);
    }
    
    function formatRupiah(angka) {
        if (isNaN(angka)) return 'Rp 0';
        return 'Rp ' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Event listeners
    paketSelect.addEventListener('change', calculateTotal);
    jumlahPeserta.addEventListener('input', calculateTotal);
    // diskonInput.addEventListener('input', calculateTotal); // readonly, diisi otomatis

    // Set initial values
    jumlahPeserta.value = jumlahPeserta.value || 1;
    // diskonInput.value = diskonInput.value || 0; // Akan diisi otomatis oleh calculateTotal

    // Trigger initial calculation
    if (paketSelect.selectedIndex > 0) {
        calculateTotal();
    }

    // Calculate duration
    const tglMulai = document.getElementById('tgl_mulai');
    const tglAkhir = document.getElementById('tgl_akhir');
    const lamaReservasi = document.getElementById('lama_reservasi');

    function calculateDuration() {
        if (tglMulai.value && tglAkhir.value) {
            const startDate = new Date(tglMulai.value);
            const endDate = new Date(tglAkhir.value);
            
            if (startDate > endDate) {
                alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
                tglAkhir.value = '';
                lamaReservasi.value = '';
                return;
            }
            
            // Perhitungan yang lebih akurat
            const diffTime = endDate - startDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
            lamaReservasi.value = diffDays;
        } else {
            lamaReservasi.value = '';
        }
    }

    tglMulai.addEventListener('change', function() {
        if (tglMulai.value) {
            tglAkhir.min = tglMulai.value;
            // Reset nilai akhir jika tidak valid
            if (tglAkhir.value && new Date(tglAkhir.value) < new Date(tglMulai.value)) {
                tglAkhir.value = '';
                lamaReservasi.value = '';
            }
        }
        calculateDuration();
    });

    tglAkhir.addEventListener('change', calculateDuration);
    
    // Initialize date min values
    if (tglMulai.value) {
        tglAkhir.min = tglMulai.value;
    }
    
    // Hitung durasi awal jika data sudah ada
    if (tglMulai.value && tglAkhir.value) {
        calculateDuration();
    }
});
</script>
@endsection