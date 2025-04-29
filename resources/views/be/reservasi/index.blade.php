@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('reservasi_create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add Reservasi
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reservasi Management</h4>
                        <p class="card-description">
                            Reservasi Table <code>Add | Edit | Remove</code>
                        </p>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        {{-- <th scope="col">Photo</th> --}}
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reservasis as $nmr => $data)
                                    <tr>
                                        <th scope="row">{{ $nmr + 1 }}.</th>

                                        {{-- <td class="py-1">
                                            @if (!empty($data['foto']))
                                                <img src="{{ asset('storage/' . $data['foto']) }}" alt="image" style="width: 50px; height: 50px; border-radius: 50%;">
                                            @else
                                                <img src="{{ asset('images/default.png') }}" alt="image" style="width: 50px; height: 50px; border-radius: 50%;">
                                            @endif
                                        </td> --}}
                                        
                                        <td class="py-1">
                                            @if (strlen($data['name']) > 10)
                                                
                                                {{ substr($data['name'], 0, 10) . '...' }}
                                            @else
                                                {{ $data['name'] }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ strlen($data['no_hp']) > 5 ? substr($data['no_hp'], 0, 5) . '...' : $data['no_hp'] }}
                                        </td>

                                        <td>
                                            {{ ucfirst($data['level']) }}
                                            @if ($data['level'] === 'admin' || $data['level'] === 'bendahara' || $data['level'] === 'owner')
                                                @php
                                                    $jabatan = \App\Models\Karyawan::where('reservasi', $data->id)->first()?->jabatan;
                                                @endphp
                                                @if ($jabatan)
                                                    <br><small class="text-muted">(Jabatan: {{ ucfirst($jabatan) }})</small>
                                                @endif
                                            @endif
                                        </td>
                                        

                                        <td>
                                            {{ !empty($data['alamat']) ? (strlen($data['alamat']) > 5 ? substr($data['alamat'], 0, 5) . '...' : $data['alamat']) : 'Not Available' }}
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reservasi.edit', $data->id) }}" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </a>
                                                <form action="{{ route('reservasi_destroy', $data->id) }}" method="POST" id="deleteForm{{ $data->id }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $data->id }})">
                                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->
</div> <!-- main-panel -->
@endsection
