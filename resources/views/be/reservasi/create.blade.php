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
                        <h4 class="font-weight-bold mb-0">Tambah Reservasi Baru</h4>
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
                        <form class="forms-sample" method="POST" action="{{ route('reservasi.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group">
                                        <label for="id_pelanggan">Pelanggan</label>
                                        <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                            <option value="" disabled selected>Pilih Pelanggan</option>
                                            @foreach($pelanggan as $p)
                                                <option value="{{ $p->id }}" {{ old('id_pelanggan') == $p->id ? 'selected' : '' }}>
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
                                            <option value="" disabled selected>Pilih Paket Wisata</option>
                                            @foreach($paket as $p)
                                                <option value="{{ $p->id }}" 
                                                        data-harga="{{ $p->harga_per_pack }}"
                                                        data-diskon="{{ $diskonAktif[$p->id] ?? 0 }}"
                                                        {{ old('id_paket') == $p->id ? 'selected' : '' }}>
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
                                            value="{{ old('tgl_mulai') }}" 
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
                                            value="{{ old('tgl_akhir') }}" 
                                            min="{{ date('Y-m-d') }}" required>
                                        @error('tgl_akhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lama_reservasi">Lama Reservasi (Hari)</label>
                                        <input type="number" class="form-control" id="lama_reservasi" name="lama_reservasi" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="jumlah_peserta">Jumlah Peserta</label>
                                    <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="{{ old('jumlah_peserta', 1) }}" required>
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
                                        <input type="text" class="form-control" id="harga" readonly>
                                        <input type="hidden" id="harga_value" name="harga" value="{{ old('harga') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diskon">Diskon (%)</label>
                                        <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="{{ old('diskon', 0) }}" step="0.01" readonly>
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
                                        <input type="text" class="form-control" id="nilai_diskon_display" readonly>
                                        <input type="hidden" id="nilai_diskon" name="nilai_diskon" value="{{ old('nilai_diskon', 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="total_bayar_display">Total Bayar</label>
                                        <input type="text" class="form-control" id="total_bayar_display" readonly>
                                        <input type="hidden" id="total_bayar" name="total_bayar" value="{{ old('total_bayar') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="file_bukti_tf">Upload Bukti Transfer</label>
                                    <input type="file" class="form-control" id="file_bukti_tf" name="file_bukti_tf">
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
                                    <option value="pesan" {{ old('status_reservasi') == 'pesan' ? 'selected' : '' }}>Pesan</option>
                                    <option value="dibayar" {{ old('status_reservasi') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="ditolak" {{ old('status_reservasi') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="selesai" {{ old('status_reservasi') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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

                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </form>
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
        const selectedOption = paketSelect.options[paketSelect.selectedIndex];
        const harga = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
        const peserta = parseInt(jumlahPeserta.value) || 1;
        // Ambil diskon otomatis dari data attribute
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
    // diskonInput.addEventListener('input', calculateTotal); // Tidak perlu, diskon readonly dan diisi otomatis

    // Set initial values
    jumlahPeserta.value = jumlahPeserta.value || 1;
    // diskonInput.value = diskonInput.value || 0; // Akan diisi otomatis oleh calculateTotal

    // Trigger initial calculation
    // Jika ada paket yang sudah dipilih (bukan default), trigger calculateTotal
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
            
            const diffTime = endDate - startDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            lamaReservasi.value = diffDays;
        } else {
            lamaReservasi.value = '';
        }
    }

    tglMulai.addEventListener('change', function() {
        if (tglMulai.value) {
            tglAkhir.min = tglMulai.value;
        }
        calculateDuration();
    });

    tglAkhir.addEventListener('change', calculateDuration);
});
</script>
@endsection