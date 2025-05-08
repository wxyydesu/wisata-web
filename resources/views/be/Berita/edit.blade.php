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
                        <h4 class="card-title">{{ isset($news) ? 'Edit' : 'Create' }} News</h4>
                        
                        <form action="{{ isset($news) ? route('berita_update', $news->id) : route('berita_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($news))
                                @method('PUT')
                            @endif
                            
                            <div class="form-group">
                                <label for="judul">Title</label>
                                <input type="text" class="form-control" id="judul" name="judul" 
                                       value="{{ old('judul', $news->judul ?? '') }}" required>
                                @error('judul')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="id_kategori_berita">Category</label>
                                <select class="form-control" id="id_kategori_berita" name="id_kategori_berita" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('id_kategori_berita', $news->id_kategori_berita ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->kategori_berita }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori_berita')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="tgl_post">Post Date</label>
                                <input type="datetime-local" class="form-control" id="tgl_post" name="tgl_post" 
                                       value="{{ old('tgl_post', isset($news->tgl_post) ? $news->tgl_post->format('Y-m-d\TH:i') : '') }}" required>
                                @error('tgl_post')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="foto">Image</label>
                                <input type="file" class="form-control" id="foto" name="foto" {{ !isset($news) ? 'required' : '' }}>
                                @error('foto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if(isset($news) && $news->foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $news->foto) }}" width="100" class="img-thumbnail">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="berita">Content</label>
                                <textarea class="form-control" id="berita" name="berita" rows="10" required>{{ old('berita', $news->berita ?? '') }}</textarea>
                                @error('berita')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ route('berita_manage') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection