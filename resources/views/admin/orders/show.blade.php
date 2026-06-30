@extends('layouts.admin')

@section('page-title', 'Order Detail')

@push('scripts')
<script>
    // Refresh notifications if order status was just updated
    @if(false)
        // Wait for page to fully load then refresh notifications
        window.addEventListener('load', function() {
            // Small delay to ensure everything is ready
            setTimeout(function() {
                // Trigger notification refresh by fetching count
                fetch('/admin/notifications/count')
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('notifCount');
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                
                // Also refresh the notification list if dropdown is open
                const dropdown = document.getElementById('notifBellBtn');
                if (dropdown && dropdown.classList.contains('show')) {
                    fetch('/admin/notifications')
                        .then(res => res.json())
                        .then(notifications => {
                            const list = document.getElementById('notifList');
                            if (!notifications || notifications.length === 0) {
                                list.innerHTML = `
                                    <div class="text-center text-muted py-4">
                                        <div style="font-size:1.5rem;opacity:0.3;">🔕</div>
                                        <small class="mt-1 d-block">No notifications yet</small>
                                    </div>`;
                            } else {
                                list.innerHTML = notifications.map(n => `
                                    <div class="p-3"
                                         style="border-bottom:1px solid #f8f9fa;
                                                background:${!n.read ? '#f8f9ff' : 'white'};">
                                        <div class="d-flex gap-2 align-items-start">
                                            <div style="font-size:1.2rem;flex-shrink:0;">🛍️</div>
                                            <div style="flex:1;">
                                                <div class="fw-semibold" style="font-size:0.85rem;">
                                                    ${n.message}
                                                </div>
                                                <div class="text-muted mt-1" style="font-size:0.75rem;">
                                                    💰 $${parseFloat(n.amount).toFixed(2)} •
                                                    👤 ${n.user} •
                                                    🕐 ${new Date(n.created_at).toLocaleString()}
                                                </div>
                                                <button
                                                    onclick="markReadAndGo(event)"
                                                    class="btn btn-sm mt-2 rounded-pill text-white"
                                                    style="font-size:0.72rem;padding:3px 12px;
                                                           background:linear-gradient(135deg,#667eea,#764ba2);
                                                           border:none;">
                                                    👁️ View Orders →
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `).join('');
                            }
                        });
                }
            }, 500);
        });
    @endif
</script>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-receipt text-primary"></i> Order #{{ $order->id }}
        </h4>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    {{-- Status Update --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">📋 Update Order Status</h6>
            <div class="d-flex gap-2 flex-wrap">

                <form action="{{ route('admin.orders.status', [$order, 'pending']) }}" method="POST" class="d-inline">
                    @csrf
                    <button
                        class="btn btn-warning btn-sm rounded-pill px-3
                    {{ $order->status == 'pending' ? 'opacity-50' : '' }}"
                        {{ $order->status == 'pending' ? 'disabled' : '' }}>
                        ⏳ Pending
                    </button>
                </form>

                <form action="{{ route('admin.orders.status', [$order, 'processing']) }}" method="POST" class="d-inline">
                    @csrf
                    <button
                        class="btn btn-info btn-sm rounded-pill px-3 text-white
                    {{ $order->status == 'processing' ? 'opacity-50' : '' }}"
                        {{ $order->status == 'processing' ? 'disabled' : '' }}>
                        🔄 Processing
                    </button>
                </form>

                <form action="{{ route('admin.orders.status', [$order, 'completed']) }}" method="POST" class="d-inline">
                    @csrf
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3
                    {{ $order->status == 'completed' ? 'opacity-50' : '' }}"
                        {{ $order->status == 'completed' ? 'disabled' : '' }}>
                        ✅ Completed
                    </button>
                </form>

                <form action="{{ route('admin.orders.status', [$order, 'cancelled']) }}" method="POST" class="d-inline">
                    @csrf
                    <button
                        class="btn btn-danger btn-sm rounded-pill px-3
                    {{ $order->status == 'cancelled' ? 'opacity-50' : '' }}"
                        {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                        ❌ Cancelled
                    </button>
                </form>

            </div>

            {{-- Current Status --}}
            <div class="mt-3">
                <small class="text-muted">Current Status:</small>
                @if ($order->status == 'pending')
                    <span class="badge bg-warning text-dark ms-2">⏳ Pending</span>
                @elseif($order->status == 'processing')
                    <span class="badge bg-info text-white ms-2">🔄 Processing</span>
                @elseif($order->status == 'completed')
                    <span class="badge bg-success ms-2">✅ Completed</span>
                @else
                    <span class="badge bg-danger ms-2">❌ Cancelled</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Customer Info --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    👤 Customer Info
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-0"><strong>Date:</strong>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Order Info --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    📦 Order Info
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p class="mb-1"><strong>Items:</strong>
                        {{ $order->orderItems->count() }} items
                    </p>
                    <p class="mb-0">
                        <strong>Total:</strong>
                        <span class="text-success fw-bold fs-5">
                            ${{ number_format($order->total_amount, 2) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-dark text-white">
            🛍️ Order Items
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                @if ($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="50"
                                        height="50" class="rounded object-fit-cover">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center
                                        justify-content-center text-white"
                                        style="width:50px;height:50px">
                                        📦
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle fw-semibold">
                                {{ $item->product->name ?? 'Product Deleted' }}
                            </td>
                            <td class="align-middle">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-primary">{{ $item->quantity }}</span>
                            </td>
                            <td class="align-middle text-success fw-semibold">
                                ${{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total:</td>
                        <td class="text-success fw-bold fs-5">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
