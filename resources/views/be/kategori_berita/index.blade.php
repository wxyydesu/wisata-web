@extends('be.master')

@section('sidebar')
  @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">News Categories Management</h4>
            <a href="{{ route('kategori-berita.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add Category
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">News Categories</h4>
                        <p class="card-description">
                            Category Table <code>Add | Edit | Remove</code>
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category Name</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->kategori_berita }}</td>
                                        <td>{{ $category->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('kategori-berita.edit', $category->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                <form action="{{ route('kategori-berita.destroy', $category->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-fw" onclick="return confirm('Are you sure?')">
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

                        @if($categories->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                No categories available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection