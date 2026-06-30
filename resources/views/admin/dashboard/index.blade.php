@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 fs-4">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Products</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_products'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-3 p-3 fs-4">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Categories</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_categories'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning text-white rounded-3 p-3 fs-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Orders</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_orders'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-3 p-3 fs-4">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Users</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_users'] }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection