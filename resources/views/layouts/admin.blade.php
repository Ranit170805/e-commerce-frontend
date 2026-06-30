<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }

        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar .brand {
            padding: 20px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #34495e;
            color: #ffffff;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: #ffffff;
            padding: 15px 30px;
            margin-left: 250px;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 60px;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column">
        <div class="brand">
            <h5 class="text-white mb-0">
                <i class="fas fa-store text-warning"></i> E-Commerce
            </h5>
            <small class="text-secondary">Admin Panel</small>
        </div>

        <nav class="nav flex-column mt-2">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Categories
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>
        </nav>

        <div class="mt-auto p-3">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Top Bar --}}
    <div class="topbar d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-muted fw-semibold">
            @yield('page-title', 'Dashboard')
        </h6>

        <div class="d-flex align-items-center gap-3">

            {{-- Notification Bell --}}
            <div class="dropdown">
                <button
                    id="notifBellBtn"
                    class="btn btn-light btn-sm rounded-circle d-flex align-items-center
                           justify-content-center position-relative"
                    style="width:38px;height:38px;border:1px solid #dee2e6;
                           font-size:16px;padding:0;"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    🔔
                    <span id="notifCount"
                          style="display:none;position:absolute;top:-5px;right:-5px;
                                 background:#dc3545;color:white;border-radius:50%;
                                 width:18px;height:18px;font-size:10px;font-weight:700;
                                 align-items:center;justify-content:center;line-height:1;">
                        0
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0"
                     style="width:320px;max-height:400px;overflow-y:auto;margin-top:8px;">

                    <div class="d-flex justify-content-between align-items-center p-3"
                         style="border-bottom:1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="font-size:0.9rem;">
                            🔔 Notifications
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm rounded-pill"
                                    style="font-size:0.75rem;padding:3px 10px;
                                           border:1px solid #dee2e6;color:#6c757d;"
                                    onclick="refreshAdminNotifications({ list: true })">
                                Refresh
                            </button>
                            <button class="btn btn-sm rounded-pill"
                                    style="font-size:0.75rem;padding:3px 10px;
                                           border:1px solid #dee2e6;color:#6c757d;"
                                    onclick="markAllRead()">
                                Mark all read
                            </button>
                        </div>
                    </div>

                    <div id="notifList">
                        <div class="text-center text-muted py-4">
                            <div style="font-size:1.5rem;opacity:0.3;">🔕</div>
                            <small class="mt-1 d-block">No notifications</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Admin Avatar + Name --}}
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center
                            justify-content-center text-white fw-bold"
                     style="width:34px;height:34px;font-size:0.85rem;flex-shrink:0;
                            background:linear-gradient(135deg,#667eea,#764ba2);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="text-muted small fw-semibold">
                    {{ Auth::user()->name }}
                </span>
            </div>

        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4"
                 id="autoAlert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4"
                 id="autoAlert">
                ❌ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <script>
        // Auto dismiss alert after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alertEl = document.getElementById('autoAlert')
            if (alertEl) {
                setTimeout(function () {
                    const bsAlert = new bootstrap.Alert(alertEl)
                    bsAlert.close()
                }, 3000)
            }
        })

        // Fetch notification count
        async function fetchNotifications() {
            try {
                const res = await fetch('/admin/notifications/count')
                const data = await res.json()
                const badge = document.getElementById('notifCount')
                if (data.count > 0) {
                    badge.textContent = data.count
                    badge.style.display = 'flex'
                } else {
                    badge.style.display = 'none'
                }
            } catch (e) {}
        }

        // Load notification list
        async function loadNotifications() {
            try {
                const res = await fetch('/admin/notifications')
                const notifications = await res.json()
                const list = document.getElementById('notifList')

                if (!notifications || notifications.length === 0) {
                    list.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <div style="font-size:1.5rem;opacity:0.3;">🔕</div>
                            <small class="mt-1 d-block">No notifications yet</small>
                        </div>`
                    return
                }

                list.innerHTML = notifications.map(n => `
                    <div class="p-3 notification-item"
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
                                <a href="${n.user_id ? '/admin/users/' + n.user_id + '/orders' : '/admin/orders'}"
                                   class="btn btn-sm mt-2 rounded-pill text-white"
                                   style="font-size:0.72rem;padding:3px 12px;
                                          background:linear-gradient(135deg,#667eea,#764ba2);
                                          border:none; text-decoration: none; display: inline-block;">
                                    👁️ View Orders →
                                </a>
                            </div>
                        </div>
                    </div>
                `).join('')

            } catch (e) {
                console.error(e)
            }
        }

        // Mark all as read
        async function markAllRead() {
            try {
                const res = await fetch('/admin/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                const data = await res.json()
                if (data.success) {
                    document.getElementById('notifCount').style.display = 'none'
                    document.querySelectorAll('.notification-item').forEach(el => {
                        el.style.background = 'white'
                    })
                }
            } catch (e) {}
        }

        // When bell dropdown starts opening → load + mark read
        document.getElementById('notifBellBtn').addEventListener(
            'show.bs.dropdown', function () {
                loadNotifications()
            }
        )

        // Initial count only. Further updates happen when the admin opens the
        // dropdown or clicks Refresh.
        fetchNotifications()
    </script>

    <script>
        const adminNotificationCsrfToken = '{{ csrf_token() }}'
        const adminNotificationBaseUrl = '{{ url('/admin/notifications') }}'

        function escapeNotificationHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[char]
            })
        }

        function getNotificationOrderUrl(notification) {
            return notification.id ? `/admin/orders/${notification.id}` : '/admin/orders'
        }

        function renderNotificationList(notifications) {
            const list = document.getElementById('notifList')
            if (!list) return

            if (!notifications || notifications.length === 0) {
                list.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <div style="font-size:1.5rem;opacity:0.3;">No</div>
                        <small class="mt-1 d-block">No notifications yet</small>
                    </div>`
                return
            }

            list.innerHTML = notifications.map(function (n) {
                const amount = Number.parseFloat(n.amount || 0).toFixed(2)
                const createdAt = n.created_at ? new Date(n.created_at).toLocaleString() : ''

                return `
                    <div class="p-3 notification-item"
                         style="border-bottom:1px solid #f8f9fa;
                                background:${!n.read ? '#f8f9ff' : 'white'};">
                        <div class="d-flex gap-2 align-items-start">
                            <div style="font-size:1.2rem;flex-shrink:0;">Order</div>
                            <div style="flex:1;">
                                <div class="fw-semibold" style="font-size:0.85rem;">
                                    ${escapeNotificationHtml(n.message)}
                                </div>
                                <div class="text-muted mt-1" style="font-size:0.75rem;">
                                    $${amount} &bull;
                                    ${escapeNotificationHtml(n.user)} &bull;
                                    ${escapeNotificationHtml(createdAt)}
                                </div>
                                <a href="${getNotificationOrderUrl(n)}"
                                   data-order-id="${escapeNotificationHtml(n.id || '')}"
                                   class="btn btn-sm mt-2 rounded-pill text-white js-view-order"
                                   style="font-size:0.72rem;padding:3px 12px;
                                          background:linear-gradient(135deg,#667eea,#764ba2);
                                          border:none; text-decoration:none; display:inline-block;">
                                    View Orders →
                                </a>
                            </div>
                        </div>
                    </div>
                `
            }).join('')
        }

        async function fetchNotifications() {
            try {
                const res = await fetch('/admin/notifications/count')
                const data = await res.json()
                const badge = document.getElementById('notifCount')
                if (!badge) return

                if (data.count > 0) {
                    badge.textContent = data.count
                    badge.style.display = 'flex'
                } else {
                    badge.style.display = 'none'
                }
            } catch (e) {}
        }

        async function loadNotifications() {
            try {
                const res = await fetch('/admin/notifications')
                const notifications = await res.json()
                renderNotificationList(notifications)
            } catch (e) {
                console.error(e)
            }
        }

        async function refreshAdminNotifications(options = {}) {
            await fetchNotifications()
            if (options.list || document.getElementById('notifBellBtn')?.classList.contains('show')) {
                await loadNotifications()
            }
        }

        async function markAllRead() {
            try {
                const res = await fetch('/admin/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': adminNotificationCsrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                const data = await res.json()
                if (data.success) {
                    await refreshAdminNotifications({ list: true })
                }
            } catch (e) {}
        }

        async function markOrderNotificationRead(orderId) {
            if (!orderId) return

            try {
                await fetch(`${adminNotificationBaseUrl}/${orderId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': adminNotificationCsrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
            } catch (e) {}
        }

        document.addEventListener('click', async function (event) {
            const link = event.target.closest('.js-view-order')
            if (!link) return

            event.preventDefault()
            await markOrderNotificationRead(link.dataset.orderId)
            link.closest('.notification-item')?.remove()
            window.location.href = link.href
        })
    </script>

</body>
</html>
