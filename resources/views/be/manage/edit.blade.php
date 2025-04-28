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

                        <form class="forms-sample" method="POST" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama">Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama" 
                                       name="name" 
                                       placeholder="Name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="no_hp">Phone Number</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       placeholder="Phone Number" 
                                       value="{{ old('no_hp', $user->no_hp) }}" 
                                       required>
                            </div>

                            <div class="form-group" >
                                <label for="level">Role</label>
                                <select class="form-control" 
                                        id="level" 
                                        name="level" 
                                        required>
                                    <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="bendahara" {{ $user->level == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                    <option value="owner" {{ $user->level == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="pelanggan" {{ $user->level == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="foto">Image Profile Upload</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="foto" 
                                       name="foto">
                            </div>

                            <div class="form-group">
                                <label for="alamat">Address</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="alamat" 
                                       name="alamat" 
                                       placeholder="Address" 
                                       value="{{ old('alamat', $user->alamat) }}" 
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">
                                Update
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
