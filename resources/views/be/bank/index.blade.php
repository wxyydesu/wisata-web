@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Bank Management</h4>
            <a href="{{ route('bank.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle me-2"></i>Add Bank
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Bank Accounts</h4>
                        <p class="card-description">
                            Bank Table <code>Add | Edit | Remove</code>
                        </p>
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Bank Name</th>
                                        <th scope="col">Account Number</th>
                                        <th scope="col">Account Holder</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($banks as $i => $bank)
                                    <tr>
                                        <th scope="row">{{ $i+1 }}.</th>
                                        <td>{{ $bank->nama_bank }}</td>
                                        <td>{{ $bank->no_rekening }}</td>
                                        <td>{{ $bank->atas_nama }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-dark btn-sm" onClick="window.location.href='{{ route('bank.edit', $bank->id) }}'">
                                                    <i class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                                <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" id="deleteForm{{ $bank->id }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-fw" onclick="deleteConfirm({{ $bank->id }})">
                                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($banks->isEmpty())
                            <div class="alert alert-info text-center mt-3">
                                No bank accounts available
                            </div>
                        @endif

                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- content-wrapper -->

<script>
function deleteConfirm(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>

@endsection