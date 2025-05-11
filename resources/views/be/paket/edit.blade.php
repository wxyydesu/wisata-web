@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Paket Wisata</h4>
                        
                        <form action="{{ route('paket_update', $paket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="nama_paket">Package Name</label>
                                <input type="text" class="form-control" id="nama_paket" name="nama_paket" 
                                       value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                                @error('nama_paket')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="deskripsi">Description</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="fasilitas">Facilities</label>
                                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas', $paket->fasilitas) }}</textarea>
                                @error('fasilitas')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="harga_per_pack">Price (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_pack" name="harga_per_pack" 
                                       value="{{ old('harga_per_pack', $paket->harga_per_pack) }}" required>
                                @error('harga_per_pack')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto1">Main Image</label>
                                        <input type="file" class="form-control" id="foto1" name="foto1">
                                        @error('foto1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto1)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto1) }}" width="100" class="img-thumbnail">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto2">Additional Image 1</label>
                                        <input type="file" class="form-control" id="foto2" name="foto2">
                                        @error('foto2')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto2)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto2) }}" width="100" class="img-thumbnail">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto3">Additional Image 2</label>
                                        <input type="file" class="form-control" id="foto3" name="foto3">
                                        @error('foto3')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto3)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto3) }}" width="100" class="img-thumbnail">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto4">Additional Image 3</label>
                                        <input type="file" class="form-control" id="foto4" name="foto4">
                                        @error('foto4')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if($paket->foto4)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $paket->foto4) }}" width="100" class="img-thumbnail">
                                                <p class="text-muted mt-1">Current image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="foto5">Additional Image 4</label>
                                <input type="file" class="form-control" id="foto5" name="foto5">
                                @error('foto5')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if($paket->foto5)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $paket->foto5) }}" width="100" class="img-thumbnail">
                                        <p class="text-muted mt-1">Current image</p>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('paket_manage') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection