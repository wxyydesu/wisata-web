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
                        <h4 class="card-title">Edit Objek Wisata</h4>
                        <p class="card-description">
                            Perbarui data objek wisata
                        </p>

                        <form class="forms-sample" action="{{ route('wisata.update', $obyekWisata->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama_wisata">Nama Objek Wisata</label>
                                <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata', $obyekWisata->nama_wisata) }}">
                                @error('nama_wisata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_kategori_wisata">Kategori</label>
                                <select class="form-control @error('id_kategori_wisata') is-invalid @enderror" id="id_kategori_wisata" name="id_kategori_wisata">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('id_kategori_wisata', $obyekWisata->id_kategori_wisata) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kategori_wisata }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori_wisata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="deskripsi_wisata">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi_wisata') is-invalid @enderror" id="deskripsi_wisata" name="deskripsi_wisata" rows="8" style="min-height: 150px;">{{ old('deskripsi_wisata', $obyekWisata->deskripsi_wisata) }}</textarea>
                                @error('deskripsi_wisata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fasilitas">Fasilitas</label>
                                <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="6" style="min-height: 120px;">{{ old('fasilitas', $obyekWisata->fasilitas) }}</textarea>
                                @error('fasilitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @for($i = 1; $i <= 5; $i++)
                                @php 
                                    $foto = 'foto'.$i;
                                    $deleteField = 'delete_foto'.$i;
                                @endphp
                                <div class="form-group">
                                    <label>Foto {{ $i }}</label>
                                    <input type="file" name="{{ $foto }}" class="form-control @error($foto) is-invalid @enderror">
                                    @error($foto)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($obyekWisata->$foto)
                                        <div class="mt-2 photo-container" data-foto="{{ $foto }}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $obyekWisata->$foto) }}" alt="Foto {{ $i }}" style="max-width: 200px; margin-right: 15px;">
                                                <button type="button" class="btn btn-danger btn-sm delete-photo" data-field="{{ $deleteField }}">
                                                    <i class="mdi mdi-delete"></i> Hapus Foto
                                                </button>
                                            </div>
                                            <input type="hidden" name="{{ $deleteField }}" id="{{ $deleteField }}" value="0">
                                        </div>
                                    @endif
                                </div>
                            @endfor

                            <button type="submit" class="btn btn-primary mr-2">Perbarui</button>
                            <a href="{{ route('wisata.index') }}" class="btn btn-light">Batal</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete photo buttons
        document.querySelectorAll('.delete-photo').forEach(button => {
            button.addEventListener('click', function() {
                const fieldName = this.getAttribute('data-field');
                const fieldInput = document.getElementById(fieldName);
                const photoContainer = this.closest('.d-flex');
                
                // Set the delete field value to 1
                fieldInput.value = '1';
                
                // Hide the photo and button
                photoContainer.style.display = 'none';
                
                // Optional: Show a message that photo will be deleted
                const message = document.createElement('div');
                message.className = 'alert alert-warning mt-2';
                message.textContent = 'Foto akan dihapus saat perubahan disimpan.';
                photoContainer.parentNode.insertBefore(message, photoContainer.nextSibling);
            });
        });
    });
</script>
@endsection