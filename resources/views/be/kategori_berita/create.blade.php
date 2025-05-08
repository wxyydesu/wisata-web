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
                        <h4 class="card-title">{{ isset($category) ? 'Edit' : 'Create' }} News Category</h4>
                        
                        <form action="{{ isset($category) ? route('kategori_berita_update', $category->id) : route('kategori_berita_store') }}" method="POST">
                            @csrf
                            @if(isset($category))
                                @method('PUT')
                            @endif
                            
                            <div class="form-group">
                                <label for="kategori_berita">Category Name</label>
                                <input type="text" class="form-control" id="kategori_berita" name="kategori_berita" 
                                       value="{{ old('kategori_berita', $category->kategori_berita ?? '') }}" required>
                                @error('kategori_berita')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ route('kategori_berita_manage') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection