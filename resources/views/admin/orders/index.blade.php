@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="fas fa-shopping-cart text-primary"></i> Orders
    </h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="align-middle">
                        {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                    </td>
                    <td class="align-middle fw-semibold">
                        {{ $order->user->name ?? 'N/A' }}
                        <div class="text-muted small">
                            {{ $order->user->email ?? '' }}
                        </div>
                    </td>
                    <td class="align-middle text-success fw-semibold">
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="align-middle">
                        @if($order->status == 'pending')
                            <span class="badge bg-warning text-dark">⏳ Pending</span>
                        @elseif($order->status == 'processing')
                            <span class="badge bg-info text-white">🔄 Processing</span>
                        @elseif($order->status == 'completed')
                            <span class="badge bg-success">✅ Completed</span>
                        @else
                            <span class="badge bg-danger">❌ Cancelled</span>
                        @endif
                    </td>
                    <td class="align-middle text-muted small">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        មិនទាន់មាន Order ទេ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($orders->hasPages())
<nav class="mt-3">
    <ul class="pagination pagination-sm">
        @if($orders->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $orders->previousPageUrl() }}">
                    Previous
                </a>
            </li>
        @endif

        @for($i = 1; $i <= $orders->lastPage(); $i++)
            <li class="page-item {{ $orders->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if($orders->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</nav>
@endif
@endsection