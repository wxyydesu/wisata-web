@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, User Details</h4>
            <a href="{{ route('user_manage') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if($user->level === 'pelanggan' && $user->pelanggan && $user->pelanggan->foto)
                                    <img src="{{ asset('storage/' . $user->pelanggan->foto) }}" 
                                         class="img-fluid rounded-circle mb-3" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                @elseif($user->karyawan && $user->karyawan->foto)
                                    <img src="{{ asset('storage/' . $user->karyawan->foto) }}" 
                                         class="img-fluid rounded-circle mb-3" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-user.jpg') }}" 
                                         class="img-fluid rounded-circle mb-3" 
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                @endif
                                
                                <h4>{{ $user->name }}</h4>
                                <p class="text-muted">
                                    {{ ucfirst($user->level) }}
                                    @if($user->level !== 'pelanggan' && $user->karyawan)
                                        <br>({{ ucfirst($user->karyawan->jabatan) }})
                                    @endif
                                </p>
                                
                                <span class="badge badge-{{ $user->aktif ? 'success' : 'danger' }}">
                                    {{ $user->aktif ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="col-md-8">
                                <h4 class="mb-4">User Information</h4>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Email</h6>
                                        <p>{{ $user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Phone Number</h6>
                                        <p>{{ $user->no_hp ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Address</h6>
                                    <p>{{ $user->alamat ?? '-' }}</p>
                                </div>
                                
                                @if($user->level === 'pelanggan' && $user->pelanggan)
                                <div class="mb-3">
                                    <h6>Complete Name</h6>
                                    <p>{{ $user->pelanggan->nama_lengkap ?? '-' }}</p>
                                </div>
                                @endif
                                
                                <div class="mb-3">
                                    <h6>Account Created</h6>
                                    <p>{{ $user->created_at->format('d F Y H:i') }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Last Updated</h6>
                                    <p>{{ $user->updated_at->format('d F Y H:i') }}</p>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('user_edit', $user->id) }}" class="btn btn-primary me-2">
                                        <i class="fa fa-edit me-1"></i> Edit User
                                    </a>
                                    <form action="{{ route('user_destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fa fa-trash me-1"></i> Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection