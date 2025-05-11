@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, here's your Users Management</h4>
            <a href="{{ route('user_create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add User
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Users Management</h4>
                        <p class="card-description">
                            User Table <code>Add | Edit | Remove</code>
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $nmr => $data)
                                    <tr>
                                        <th scope="row">{{ $nmr + 1 }}.</th>
                                
                                        {{-- Foto --}}
                                        <td>
                                            @php
                                                $photo = null;
                                                if ($data->level == 'pelanggan' && $data->pelanggan && $data->pelanggan->foto) {
                                                    $photo = asset('storage/' . $data->pelanggan->foto);
                                                } elseif ($data->karyawan && $data->karyawan->foto) {
                                                    $photo = asset('storage/' . $data->karyawan->foto);
                                                } else {
                                                    $photo = asset('images/default-user.jpg');
                                                }
                                            @endphp
                                            <img src="{{ $photo }}" 
                                                 alt="User Photo" 
                                                 class="rounded-circle border"
                                                 width="40" 
                                                 height="40"
                                                 style="cursor:pointer; object-fit: cover;"
                                                 onclick="showImgPreview('{{ $photo }}')">
                                        </td>
                                
                                        {{-- Nama --}}
                                        <td class="py-1">
                                            {{ $data['name'] }}
                                        </td>

                                        {{-- Email --}}
                                        <td class="py-1">
                                            {{ $data['email'] }}
                                        </td>
                                
                                        {{-- Nomor HP --}}
                                        <td>
                                            {{ $data['no_hp'] ?? '-' }}
                                        </td>
                                
                                        {{-- Role --}}
                                        <td>
                                            {{ ucfirst($data['level']) }}
                                            @if ($data['level'] === 'admin' || $data['level'] === 'bendahara' || $data['level'] === 'owner')
                                                @php
                                                    $jabatan = \App\Models\Karyawan::where('id_user', $data->id)->first()?->jabatan;
                                                @endphp
                                                @if ($jabatan)
                                                    <br><small class="text-muted">({{ ucfirst($jabatan) }})</small>
                                                @endif
                                            @endif
                                        </td>
                                
                                        {{-- Alamat --}}
                                        <td>
                                            @if($data->level == 'pelanggan' && $data->pelanggan)
                                                {{ Str::limit($data->pelanggan->alamat ?? '-', 20) }}
                                            @elseif($data->karyawan)
                                                {{ Str::limit($data->karyawan->alamat ?? '-', 20) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="py-1">
                                            @if($data['aktif'])
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                
                                        {{-- Aksi --}}
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('user_edit', $data->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                <form action="{{ route('user_destroy', $data->id) }}" method="POST" id="deleteForm{{ $data->id }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $data->id }})">
                                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-success btn-sm" onClick="window.location.href='{{ route('user_show', $data->id) }}'">
                                                    <i class="fa fa-eye"></i> Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($users->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                No users available
                            </div>
                        @endif

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->
</div> <!-- main-panel -->

<!-- Image Preview Modal -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imgPreview" src="" alt="Preview" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showImgPreview(src) {
    const previewImg = document.getElementById('imgPreview');
    previewImg.src = src;
    
    // Initialize and show modal
    const modal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
    modal.show();
}

function deleteConfirm(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>

@endsection