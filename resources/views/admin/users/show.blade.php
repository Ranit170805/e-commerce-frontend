@extends('layouts.admin')

@section('page-title', 'User Detail')

@push('scripts')
<script>
    // Notifications are refreshed on page load via the layout's JS.
</script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="fas fa-user text-primary"></i> User Detail
    </h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

    <div class="row g-4">

    {{-- Verify User Button --}}
    @if(!$user->email_verified_at)
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Verify User
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- User Info --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-user"></i> User Info
            </div>
            <div class="card-body text-center py-4">
                <div class="bg-secondary rounded-circle d-inline-flex align-items-center
                            justify-content-center text-white mb-3"
                     style="width:80px;height:80px;font-size:2rem;">
                    <i class="fas fa-user"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <p class="text-muted small mb-0">
                    Joined: {{ $user->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- User Stats --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-shopping-cart"></i> Order History
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td class="text-success fw-semibold">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info text-dark">Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                មិនទាន់មាន Order ទេ
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection