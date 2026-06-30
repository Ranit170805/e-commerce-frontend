@extends('layouts.admin')

@section('page-title', 'Users')

@push('scripts')
<script>
    // Refresh notifications when viewing users list
    if (false) window.addEventListener('load', function() {
        setTimeout(function() {
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
        }, 300);
    });
</script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="fas fa-users text-primary"></i> Users
    </h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Orders</th>
                    <th>Joined</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                   <td class="align-middle">
                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                </td>
                    <td class="align-middle fw-semibold">
                        <i class="fas fa-user-circle text-secondary"></i>
                        {{ $user->name }}
                    </td>
                    <td class="align-middle text-muted">{{ $user->email }}</td>
                    <td class="align-middle">
                        <span class="badge bg-primary">
                            {{ $user->orders->count() }} orders
                        </span>
                    </td>
                    <td class="align-middle text-muted small">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        មិនទាន់មាន User ទេ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- <div class="mt-3">
    {{ $users->links() }}
</div> --}}

{{-- Bootstrap Pagination --}}
@if($users->hasPages())
<nav class="mt-3">
    <ul class="pagination pagination-sm">
users
        {{-- Previous --}}
        @if($users->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $users->previousPageUrl() }}">
                    Previous
                </a>
            </li>
        @endif

        {{-- Pages --}}
        @for($i = 1; $i <= $products->lastPage(); $i++)
            <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Next --}}
        @if($users->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
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
