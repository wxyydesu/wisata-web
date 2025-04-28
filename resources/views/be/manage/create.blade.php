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
            <h4 class="card-title">Basic form elements</h4>
            <p class="card-description"> Basic form elements </p>
            <form class="forms-sample" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" class="form-control" id="exampleInputName1" placeholder="Name" name="name" required>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email" name="email" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputPhone1">Phone Number</label>
                    <input type="text" name="no_hp" class="form-control" id="exampleInputPhone1" placeholder="Phone Number">
                    @if ($errors->has('no_hp'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('no_hp') }}</strong>
                        </span>
                    @endif
                </div>                
                <div class="form-group">
                    <label for="exampleInputPassword4">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword4" placeholder="Password" name="password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>                
                <div class="form-group">
                <label for="exampleSelectGender">Role</label>
                <select class="form-select" id="exampleSelectGender" name="level">
                    <option selected>Select Role</option>
                      <option value="admin">Admin</option>
                      <option value="bendahara">Bendahara</option>
                      <option value="owner">Owner</option>
                      <option value="pelanggan">Pelanggan</option>
                </select>
                </div>
                <div class="form-group">
                <label>Image Profile Upload</label>
                <input type="file" name="image" class="file-upload-default">
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                    <span class="input-group-append">
                    <button class="file-upload-browse btn btn-primary" type="button" name="image">Upload</button>
                    </span>
                </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputCity1">Address</label>
                    <input type="text" class="form-control" id="exampleInputCity1" placeholder="Location" name="alamat" required>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection