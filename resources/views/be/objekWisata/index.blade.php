    @extends('be.master')

    @section('sidebar')
        @include('be.sidebar')
    @endsection

    @section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('wisata.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle me-2"></i>Tambah Objek Wisata
                </a>
            </div>

            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Manajemen Objek Wisata</h4>
                            <p class="card-description">
                                Daftar Objek Wisata <code>Tambah | Edit | Hapus</code>
                            </p>
                            
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Foto 1</th>
                                            <th scope="col">Foto 2</th>
                                            <th scope="col">Foto 3</th>
                                            <th scope="col">Foto 4</th>
                                            <th scope="col">Foto 5</th>
                                            <th scope="col">Nama Wisata</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Deskripsi</th>
                                            <th scope="col">Fasilitas</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($objekWisatas as $index => $wisata)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}.</th>
                                            @for($i = 1; $i <= 5; $i++)
                                                @php $foto = 'foto'.$i; @endphp
                                                <td class="py-1">
                                                    @if ($wisata->$foto)
                                                        <img src="{{ asset('storage/' . $wisata->$foto) }}" alt="Foto {{$i}}" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('images/default-wisata.png') }}" alt="Default {{$i}}" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                </td>
                                            @endfor
                                            <td>{{ Str::limit($wisata->nama_wisata, 20) }}</td>
                                            <td>{{ $wisata->kategoriWisata->kategori_wisata ?? '-' }}</td>
                                            <td>{{ Str::limit($wisata->deskripsi_wisata, 30) }}</td>
                                            <td>{{ Str::limit($wisata->fasilitas, 30) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('wisata.edit', $wisata->id) }}'">
                                                        <i class="fa fa-pencil-square-o"></i> Edit
                                                    </button>
                                                    <form action="{{ route('wisata.destroy', $wisata->id) }}" method="POST" id="deleteForm{{ $wisata->id }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-fw" onclick="confirmDelete({{ $wisata->id }})">
                                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('wisata.show', $wisata->id) }}'">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $objekWisatas->links() }}
                            </div>
                            @if($objekWisatas->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                Tidak ada data Objek wisata tersedia
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Preview">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    // Fungsi untuk menampilkan modal preview gambar
    function showImageModal(src) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImage').src = src;
        modal.show();
    }

    // Tambahkan event listener untuk semua gambar setelah halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Temukan semua gambar dengan class 'previewable'
        const images = document.querySelectorAll('img[src^="{{ asset("storage/") }}"], img[src^="{{ asset("images/") }}"]');
        
        // Tambahkan event click ke setiap gambar
        images.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                showImageModal(this.src);
            });
        });
    });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
    @endsection