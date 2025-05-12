    @extends('be.master')

    @section('sidebar')
        @include('be.sidebar')
    @endsection

    @section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('objek_wisata_create') }}" class="btn btn-primary">
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
                                            <th scope="col">Gambar</th>
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
                                            <td class="py-1">
                                                @if ($wisata->foto1)
                                                    <img src="{{ asset('storage/' . $wisata->foto1) }}" alt="Gambar Wisata" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('images/default-wisata.png') }}" alt="Gambar Default" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($wisata->nama_wisata, 20) }}</td>
                                            <td>{{ $wisata->kategoriWisata->nama_kategori ?? '-' }}</td>
                                            <td>{{ Str::limit($wisata->deskripsi_wisata, 30) }}</td>
                                            <td>{{ Str::limit($wisata->fasilitas, 30) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('objek_wisata_edit', $wisata->id) }}'">
                                                        <i class="fa fa-pencil-square-o"></i> Edit
                                                    </button>
                                                    <form action="{{ route('objek_wisata_destroy', $wisata->id) }}" method="POST" id="deleteForm{{ $wisata->id }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $wisata->id }})">
                                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('objek_wisata_show', $wisata->id) }}'">
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

    <script>
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