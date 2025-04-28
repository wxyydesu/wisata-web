@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($user) ? 'Edit User' : 'Create User' }}</h4>
                        <p class="card-description">{{ isset($user) ? 'Update user information' : 'Fill the form to create a new user' }}</p>

                        <form class="forms-sample" method="POST" 
                        action="{{ isset($user) ? route('user.update', ['user_manage' => $user->id]) : route('user.store') }}"
                        enctype="multipart/form-data">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="nama">Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama" 
                                       name="nama" 
                                       placeholder="Name" 
                                       value="{{ old('nama', $user->pelanggan->nama_lengkap ?? $user->karyawan->nama_karyawan ?? '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Email" 
                                       value="{{ old('email', $user->email ?? '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="no_hp">Phone Number</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       placeholder="Phone Number" 
                                       value="{{ old('no_hp', $user->pelanggan->no_hp ?? $user->karyawan->no_hp ?? '') }}" 
                                       required>
                            </div>

                            @if(!isset($user))
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Password" 
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm Password" 
                                           required>
                                </div>
                            @endif

                            <div class="form-group" >
                                <label for="level">Role</label>
                                <select class="form-control" 
                                        id="level" 
                                        name="level" 
                                        required>
                                    <option value="" disabled {{ isset($user) ? '' : 'selected' }}>Select Role</option>
                                    <option value="admin" {{ old('level', $user->level ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="bendahara" {{ old('level', $user->level ?? '') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="owner" {{ old('level', $user->level ?? '') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="pelanggan" {{ old('level', $user->level ?? '') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="image">Image Profile Upload</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="image" 
                                       name="image">
                            </div>

                            <div class="form-group">
                                <label for="alamat">Address</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="alamat" 
                                       name="alamat" 
                                       placeholder="Address" 
                                       value="{{ old('alamat', $user->pelanggan->alamat ?? $user->karyawan->alamat ?? '') }}" 
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">
                                {{ isset($user) ? 'Update' : 'Submit' }}
                            </button>
                            <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
