@extends('be.master')
@section('content')
<div class="content">
    <div class="pt-4 mt-4">
      <h2>Welcome Back, {{ auth()->user()->name }}!</h2>
      <div class="row mt-4">
        <div class="col-md-3">
          <div class="card text-bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Users</h5>
              <p class="card-text fs-4">1,240</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title">Sales</h5>
              <p class="card-text fs-4">$35,000</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-warning mb-3">
            <div class="card-body">
              <h5 class="card-title">Visitors</h5>
              <p class="card-text fs-4">4,520</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-danger mb-3">
            <div class="card-body">
              <h5 class="card-title">Orders</h5>
              <p class="card-text fs-4">980</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity or Chart Section -->
      <div class="card mt-4">
        <div class="card-header">
          Recent Activity
        </div>
        <div class="card-body">
          <p class="card-text">This is where you can place recent activity logs, tables, or charts.</p>
        </div>
      </div>
    </div>
  </div>
@endsection