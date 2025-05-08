@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $greeting }}, here's your News Management</h4>
            <a href="{{ route('berita_create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add News
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">News Management</h4>
                        <p class="card-description">
                            News Table <code>Add | Edit | Remove</code>
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Post Date</th>
                                        <th>Content</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($news as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                                 alt="News Image" 
                                                 class="rounded"
                                                 width="60" 
                                                 height="40"
                                                 style="object-fit: cover;">
                                            @else
                                            <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($item->judul, 50) }}</td>
                                        <td>
                                            @if($item->kategoriBerita)
                                                {{ $item->kategoriBerita->kategori_berita }}
                                            @else
                                                <span class="text-danger">Category Deleted</span>
                                            @endif
                                        </td>   
                                        <td>
                                            @if($item->tgl_post)
                                                {{ \Carbon\Carbon::parse($item->tgl_post)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">No date</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->berita)
                                                {{ $item->berita }}
                                            @else
                                                <span class="text-muted">No content</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('berita_edit', $item->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                <form action="{{ route('berita_destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($news->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                No news available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection