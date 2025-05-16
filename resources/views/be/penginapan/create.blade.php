@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Penginapan Baru</h4>
                        <p class="card-description">
                            Formulir penambahan data penginapan
                        </p>

                        <form class="forms-sample" method="POST" action="{{ route('penginapan.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Nama Penginapan</label>
                                    <input type="text" name="nama_penginapan" class="form-control" placeholder="Masukkan nama penginapan" required>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi penginapan" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Fasilitas</label>
                                    <textarea name="fasilitas" class="form-control" rows="3" placeholder="Masukkan fasilitas yang tersedia" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Upload Foto</label>
                                <div class="row">
                                    @for($i = 1; $i <= 5; $i++)
                                    <div class="col-md-4 mb-3">
                                        <input type="file" class="form-control" name="foto{{ $i }}" accept="image/*">
                                        <small class="text-muted">Foto {{ $i }} (Opsional)</small>
                                    </div>
                                    @endfor
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
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Check if SweetAlert is loaded
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 is not loaded!');
            return;
        }

        // Debug: Check session data
        console.log('Session swal data:', @json(session('swal')));
        console.log('Validation errors:', @json($errors->all()));

        // Show SweetAlert notification if exists
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

        // Show validation errors if any
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
    });
</script>
@endsection