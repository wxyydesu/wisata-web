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
                        <h4 class="font-weight-bold mb-0">Tambah Reservasi Penginapan Baru</h4>
                    </div>
                    <div>
                        <a href="{{ route('penginapan_reservasi.index') }}" class="btn btn-primary">
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
                        <form class="forms-sample" method="POST" action="{{ route('penginapan_reservasi.store') }}" enctype="multipart/form-data">
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
                                        <label for="id_penginapan">Penginapan</label>
                                        <select class="form-control" id="id_penginapan" name="id_penginapan" required onchange="updatePrice()">
                                            <option value="" disabled selected>Pilih Penginapan</option>
                                            @foreach($penginapan as $p)
                                                <option value="{{ $p->id }}" 
                                                        data-harga="{{ $p->harga_per_malam }}"
                                                        {{ old('id_penginapan') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_penginapan }} (Rp {{ number_format($p->harga_per_malam, 0, ',', '.') }}/malam) 
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_penginapan')
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
                                        <label for="tgl_check_in">Tanggal Check In</label>
                                        <input type="date" class="form-control @error('tgl_check_in') is-invalid @enderror" 
                                            id="tgl_check_in" name="tgl_check_in" 
                                            value="{{ old('tgl_check_in') }}" 
                                            min="{{ date('Y-m-d') }}" required onchange="calculateLama()">
                                        @error('tgl_check_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_check_out">Tanggal Check Out</label>
                                        <input type="date" class="form-control @error('tgl_check_out') is-invalid @enderror" 
                                            id="tgl_check_out" name="tgl_check_out" 
                                            value="{{ old('tgl_check_out') }}" 
                                            min="{{ date('Y-m-d') }}" required onchange="calculateLama()">
                                        @error('tgl_check_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jumlah_kamar">Jumlah Kamar</label>
                                        <input type="number" class="form-control @error('jumlah_kamar') is-invalid @enderror" 
                                            id="jumlah_kamar" name="jumlah_kamar" 
                                            value="{{ old('jumlah_kamar', 1) }}" 
                                            min="1" required onchange="calculateTotal()">
                                        @error('jumlah_kamar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diskon">Diskon (%)</label>
                                        <input type="number" class="form-control @error('diskon') is-invalid @enderror" 
                                            id="diskon" name="diskon" 
                                            value="{{ old('diskon', 0) }}" 
                                            min="0" max="100" step="0.01" onchange="calculateTotal()">
                                        @error('diskon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status_reservasi">Status Reservasi</label>
                                        <select class="form-control" id="status_reservasi" name="status_reservasi" required>
                                            <option value="menunggu konfirmasi" {{ old('status_reservasi') == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                            <option value="booking" {{ old('status_reservasi') == 'booking' ? 'selected' : '' }}>Booking</option>
                                            <option value="batal" {{ old('status_reservasi') == 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        @error('status_reservasi')
                                            <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file_bukti_tf">Bukti Transfer</label>
                                        <input type="file" class="form-control @error('file_bukti_tf') is-invalid @enderror" 
                                            id="file_bukti_tf" name="file_bukti_tf" accept="image/*,application/pdf">
                                        <small class="text-muted">JPG, PNG, PDF (Max 2MB)</small>
                                        @error('file_bukti_tf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info" role="alert">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Harga/Malam:</strong>
                                                <div id="hargaPerMalam">Rp 0</div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Lama Malam:</strong>
                                                <div id="lamaMalam">0</div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Subtotal:</strong>
                                                <div id="subtotal">Rp 0</div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Total Bayar:</strong>
                                                <div id="totalBayar" style="font-size: 1.2em; font-weight: bold; color: green;">Rp 0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function updatePrice() {
    const select = document.getElementById('id_penginapan');
    const harga = select.options[select.selectedIndex].getAttribute('data-harga');
    document.getElementById('hargaPerMalam').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
    calculateTotal();
}

function calculateLama() {
    const checkIn = new Date(document.getElementById('tgl_check_in').value);
    const checkOut = new Date(document.getElementById('tgl_check_out').value);
    
    if (checkIn && checkOut) {
        const lama = Math.floor((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        document.getElementById('lamaMalam').textContent = lama > 0 ? lama : 0;
        calculateTotal();
    }
}

function calculateTotal() {
    const select = document.getElementById('id_penginapan');
    const harga = parseFloat(select.options[select.selectedIndex].getAttribute('data-harga'));
    const checkIn = new Date(document.getElementById('tgl_check_in').value);
    const checkOut = new Date(document.getElementById('tgl_check_out').value);
    const lama = Math.floor((checkOut - checkIn) / (1000 * 60 * 60 * 24));
    const jumlahKamar = parseInt(document.getElementById('jumlah_kamar').value) || 1;
    const diskon = parseFloat(document.getElementById('diskon').value) || 0;

    const subtotal = harga * lama * jumlahKamar;
    const nilaiDiskon = subtotal * diskon / 100;
    const total = subtotal - nilaiDiskon;

    document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    document.getElementById('totalBayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        return;
    }

    @if(session('swal'))
        Swal.fire({
            position: 'top-end',
            icon: '{{ session('swal.icon') }}',
            title: {!! json_encode(session('swal.title')) !!},
            text: {!! json_encode(session('swal.text')) !!},
            showConfirmButton: false,
            timer: {{ session('swal.timer') ?? 1500 }},
            toast: true
        });
    @endif
    
    @if($errors->any())
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Validasi Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            showConfirmButton: false,
            timer: 4000,
            toast: true
        });
    @endif

    // Initialize on page load
    updatePrice();
    calculateLama();
    calculateTotal();
});
</script>
@endsection
