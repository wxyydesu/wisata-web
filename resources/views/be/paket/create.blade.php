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
                        <h4 class="card-title">Create Paket Wisata</h4>
                        
                        <form action="{{ route('paket_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group">
                                <label for="nama_paket">Package Name</label>
                                <input type="text" class="form-control" id="nama_paket" name="nama_paket" 
                                       value="{{ old('nama_paket') }}" required>
                                @error('nama_paket')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="deskripsi">Description</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="fasilitas">Facilities</label>
                                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas') }}</textarea>
                                @error('fasilitas')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="harga_per_pack">Price (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_pack" name="harga_per_pack" 
                                       value="{{ old('harga_per_pack') }}" required>
                                @error('harga_per_pack')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto1">Main Image (Required)</label>
                                <input type="file" class="form-control" id="foto1" name="foto1" required>
                                @error('foto1')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto2">Additional Image 1 (Optional)</label>
                                <input type="file" class="form-control" id="foto2" name="foto2">
                                @error('foto2')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto3">Additional Image 2 (Optional)</label>
                                <input type="file" class="form-control" id="foto3" name="foto3">
                                @error('foto3')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto4">Additional Image 3 (Optional)</label>
                                <input type="file" class="form-control" id="foto4" name="foto4">
                                @error('foto4')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto5">Additional Image 4 (Optional)</label>
                                <input type="file" class="form-control" id="foto5" name="foto5">
                                @error('foto5')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ route('paket_manage') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection