@extends('be.master')

@section('sidebar')
    @include('be.sidebar')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Bank Account</h4>
                    <p class="card-description">
                        Update the bank account details below
                    </p>
                    
                    <form class="forms-sample" action="{{ route('bank.update', $bank->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="nama_bank">Bank Name</label>
                            <input type="text" class="form-control" id="nama_bank" name="nama_bank" 
                                value="{{ old('nama_bank', $bank->nama_bank) }}" placeholder="e.g. BCA, Mandiri, BRI" required>
                            @error('nama_bank')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="no_rekening">Account Number</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" 
                                value="{{ old('no_rekening', $bank->no_rekening) }}" placeholder="e.g. 1234567890" required>
                            @error('no_rekening')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="atas_nama">Account Holder Name</label>
                            <input type="text" class="form-control" id="atas_nama" name="atas_nama" 
                                value="{{ old('atas_nama', $bank->atas_nama) }}" placeholder="Name as shown in bank account" required>
                            @error('atas_nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                        <a href="{{ route('bank.index') }}" class="btn btn-light">
                            <i class="fa fa-arrow-left me-1"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection