@extends('layout.index')
{{-- @extends('layout.nav')
@extends('layout.sidebar') --}}

@section('home')
    {{-- Tailwind CDN — remove if already in layout.index --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        /* ── Reset wrapper to dark ───────────────────────── */
        body,
        .content-wrapper,
        #app,
        main {
            background: #0a0a0f !important;
        }

        /* ── Custom font ─────────────────────────────────── */
        .kitchen-ui {
            font-family: 'Space Grotesk', sans-serif;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* ── Noise overlay ───────────────────────────────── */
        .kitchen-ui::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.4;
        }

        /* ── Gradient background ─────────────────────────── */
        .kitchen-bg {
            background: #0a0a0f;
            background-image:
                radial-gradient(ellipse 60% 40% at 10% 0%, rgba(124, 58, 237, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 90% 100%, rgba(236, 72, 153, 0.08) 0%, transparent 50%);
            min-height: 100vh;
        }

        /* ── Glass cards ─────────────────────────────────── */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.13);
        }

        /* ── Status pill ─────────────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            padding: 3px 10px;
            border-radius: 999px;
        }

        .pill-pending {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, .3);
        }

        .pill-preparing {
            background: rgba(59, 130, 246, .15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, .3);
        }

        .pill-served {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, .3);
        }

        .pill-cancelled {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, .3);
        }

        /* ── Order card accent ───────────────────────────── */
        .accent-pending {
            box-shadow: inset 3px 0 0 #f59e0b;
        }

        .accent-preparing {
            box-shadow: inset 3px 0 0 #3b82f6;
        }

        .accent-served {
            box-shadow: inset 3px 0 0 #22c55e;
        }

        .accent-cancelled {
            box-shadow: inset 3px 0 0 #ef4444;
            opacity: .55;
        }

        /* ── Stat cards ──────────────────────────────────── */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(ellipse at 50% 0%, rgba(255, 255, 255, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Filter buttons ──────────────────────────────── */
        .filter-btn {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            border-radius: 6px;
            padding: 6px 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.4);
            background: transparent;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .filter-btn:hover {
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.06);
        }

        .filter-btn.active {
            color: white;
            background: #7c3aed;
            border-color: #7c3aed;
        }

        /* ── Custom select ───────────────────────────────── */
        .kstatus-select {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            padding: 6px 28px 6px 10px;
            cursor: pointer;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
            transition: border-color .15s;
        }

        .kstatus-select:hover {
            border-color: rgba(255, 255, 255, 0.25);
        }

        .kstatus-select:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 2px rgba(124, 58, 237, .25);
        }

        .kstatus-select option {
            background: #1a1a2e;
            color: white;
        }

        /* ── Buttons ─────────────────────────────────────── */
        .btn {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .03em;
            border-radius: 8px;
            padding: 6px 14px;
            cursor: pointer;
            border: none;
            transition: all .15s ease;
        }

        .btn-purple {
            background: #7c3aed;
            color: white;
        }

        .btn-purple:hover {
            background: #6d28d9;
        }

        .btn-pink {
            background: #be185d;
            color: white;
        }

        .btn-pink:hover {
            background: #9d174d;
        }

        .btn-blue {
            background: #1d4ed8;
            color: white;
        }

        .btn-blue:hover {
            background: #1e40af;
        }

        .btn-green {
            background: #15803d;
            color: white;
        }

        .btn-green:hover {
            background: #166534;
        }

        .btn-red {
            background: #b91c1c;
            color: white;
        }

        .btn-red:hover {
            background: #991b1b;
        }

        /* ── Divider ─────────────────────────────────────── */
        .kdivider {
            border: none;
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 0;
        }

        /* ── Scrollbar ───────────────────────────────────── */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 999px;
        }

        /* ── Animations ──────────────────────────────────── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(110%);
                opacity: 0
            }

            to {
                transform: translateX(0);
                opacity: 1
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(.94)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        .anim-fade-up {
            animation: fadeUp .3s ease both;
        }

        .anim-scale-in {
            animation: scaleIn .2s ease both;
        }

        .anim-slide-in {
            animation: slideIn .3s cubic-bezier(.22, 1, .36, 1) both;
        }

        .anim-pulse {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        /* Staggered card entry */
        .order-card:nth-child(1) {
            animation-delay: .03s
        }

        .order-card:nth-child(2) {
            animation-delay: .06s
        }

        .order-card:nth-child(3) {
            animation-delay: .09s
        }

        .order-card:nth-child(4) {
            animation-delay: .12s
        }

        .order-card:nth-child(5) {
            animation-delay: .15s
        }

        .order-card:nth-child(6) {
            animation-delay: .18s
        }

        /* ── LIVE badge pulse ────────────────────────────── */
        .live-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        /* ── Toast ───────────────────────────────────────── */
        .toast {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid;
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            animation: slideIn .3s cubic-bezier(.22, 1, .36, 1);
        }

        /* ── Modal backdrop ──────────────────────────────── */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{--                     MAIN UI                        --}}
    {{-- ═══════════════════════════════════════════════════ --}}

    <div class="kitchen-ui kitchen-bg relative z-10" style="padding:32px 28px; min-height:100vh;">

        {{-- ── TOP BAR ──────────────────────────────────── --}}
        <div
            style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:4px;">
                    <div
                        style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#ec4899);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">
                        🍽️</div>
                    <h1 style="font-size:26px;font-weight:700;color:white;letter-spacing:-.5px;margin:0;">Kitchen Orders
                    </h1>
                </div>
                <p style="color:rgba(255,255,255,0.35);font-size:13px;margin:0 0 0 52px;">Live order management dashboard
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                <div
                    style="display:flex;align-items:center;gap:7px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;font-size:11px;font-weight:700;letter-spacing:.08em;padding:6px 12px;border-radius:999px;">
                    <span class="live-dot"
                        style="width:7px;height:7px;background:#4ade80;border-radius:50%;display:inline-block;"></span>
                    LIVE
                </div>
                <div id="lastUpdated"
                    style="color:rgba(255,255,255,0.3);font-size:12px;font-family:'JetBrains Mono',monospace;">—</div>
            </div>
        </div>

        {{-- ── STATS ROW ────────────────────────────────── --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
            <!-- Pending -->
            <div class="glass-card stat-card"
                style="border-radius:14px;padding:18px;text-align:center;border-left:3px solid #f59e0b;">
                <div id="statPending"
                    style="font-size:28px;font-weight:700;color:#fbbf24;font-family:'JetBrains Mono',monospace;">—</div>
                <div
                    style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);letter-spacing:.08em;text-transform:uppercase;margin-top:4px;">
                    Pending</div>
            </div>
            <!-- Preparing -->
            <div class="glass-card stat-card"
                style="border-radius:14px;padding:18px;text-align:center;border-left:3px solid #3b82f6;">
                <div id="statPreparing"
                    style="font-size:28px;font-weight:700;color:#60a5fa;font-family:'JetBrains Mono',monospace;">—</div>
                <div
                    style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);letter-spacing:.08em;text-transform:uppercase;margin-top:4px;">
                    Preparing</div>
            </div>
            <!-- Served -->
            <div class="glass-card stat-card"
                style="border-radius:14px;padding:18px;text-align:center;border-left:3px solid #22c55e;">
                <div id="statServed"
                    style="font-size:28px;font-weight:700;color:#4ade80;font-family:'JetBrains Mono',monospace;">—</div>
                <div
                    style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);letter-spacing:.08em;text-transform:uppercase;margin-top:4px;">
                    Served</div>
            </div>
            <!-- Cancelled -->
            <div class="glass-card stat-card"
                style="border-radius:14px;padding:18px;text-align:center;border-left:3px solid #ef4444;">
                <div id="statCancelled"
                    style="font-size:28px;font-weight:700;color:#f87171;font-family:'JetBrains Mono',monospace;">—</div>
                <div
                    style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);letter-spacing:.08em;text-transform:uppercase;margin-top:4px;">
                    Cancelled</div>
            </div>
        </div>

        {{-- ── FILTER BAR ───────────────────────────────── --}}
        <div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
            <button onclick="setFilter('all')" id="filter-all" class="filter-btn active">All</button>
            <button onclick="setFilter('pending')" id="filter-pending" class="filter-btn"> Pending</button>
            <button onclick="setFilter('preparing')" id="filter-preparing" class="filter-btn"> Preparing</button>
            <button onclick="setFilter('served')" id="filter-served" class="filter-btn">Served</button>
            <button onclick="setFilter('cancelled')" id="filter-cancelled" class="filter-btn"> Cancelled</button>
        </div>

        {{-- ── ORDERS GRID ──────────────────────────────── --}}
        <div id="ordersContainer" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
        </div>

        {{-- ── EMPTY STATE ──────────────────────────────── --}}
        <div id="emptyState"
            style="display:none;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;text-align:center; background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);border-radius:16px;">
            <div style="font-size:56px;margin-bottom:16px;opacity:.5;">🍴</div>
            <p style="color:rgba(255,255,255,.4);font-size:16px;font-weight:600;margin:0;">No orders found</p>
            <p style="color:rgba(255,255,255,.2);font-size:13px;margin:6px 0 0;">New orders appear here automatically</p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{--                     MODAL                          --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div id="orderModal" class="modal-backdrop" onclick="if(event.target===this)closeModal()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center;padding:16px;">
        <div class="anim-scale-in"
            style="background:#111118;border:1px solid rgba(255,255,255,.1);border-radius:20px;width:100%;max-width:420px;padding:28px;position:relative;box-shadow:0 32px 80px rgba(0,0,0,.7);">
            <button onclick="closeModal()"
                style="position:absolute;top:16px;right:16px;width:30px;height:30px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:50%;color:rgba(255,255,255,.6);font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;"
                onmouseover="this.style.background='rgba(255,255,255,.13)'"
                onmouseout="this.style.background='rgba(255,255,255,.07)'">&times;</button>
            <div id="modalContent"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{--                  TOAST CONTAINER                   --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div id="toastContainer"
        style="position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;pointer-events:none;">
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{--                    JAVASCRIPT                      --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <script>
        let lastOrderNumber = Math.max(...@json($orders->keys()), 0);
        let currentFilter = 'all';
        let allOrdersData = {};

        const statusMeta = {
            pending: {
                label: 'Pending',
                pill: 'pill-pending',
                accent: 'accent-pending'
            },
            preparing: {
                label: 'Preparing',
                pill: 'pill-preparing',
                accent: 'accent-preparing'
            },
            served: {
                label: 'Served',
                pill: 'pill-served',
                accent: 'accent-served'
            },
            cancelled: {
                label: 'Cancelled',
                pill: 'pill-cancelled',
                accent: 'accent-cancelled'
            },
        };

        // ── Fetch ─────────────────────────────────────────
        function fetchOrders() {
            fetch("{{ route('orders.kichan') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allOrdersData = data;
                    updateStats(data);
                    renderOrders(data);
                    const keys = Object.keys(data).map(Number);
                    if (keys.length) {
                        const max = Math.max(...keys);
                        if (max > lastOrderNumber) {
                            lastOrderNumber = max;
                            showToast('🔔 New Order Received!', '#15803d', '#4ade80', 'rgba(34,197,94,.3)');
                            playBeep();
                        }
                    }
                    const now = new Date();
                    document.getElementById('lastUpdated').textContent =
                        now.toLocaleTimeString('en-GB', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                })
                .catch(() => {
                    document.getElementById('lastUpdated').textContent = '⚠ offline';
                    document.getElementById('lastUpdated').style.color = '#f87171';
                });
        }

        // ── Render Cards ──────────────────────────────────

        function renderOrders(data) {
            const container = document.getElementById('ordersContainer');
            const emptyState = document.getElementById('emptyState');
            let html = '',
                count = 0;

            // 💡 FIX: Object ki keys (order numbers) nikali aur unhein numeric descending sort kiya
            const sortedOrderNumbers = Object.keys(data).sort((a, b) => Number(b) - Number(a));

            // 💡 FIX: Ab purane loop ki jagah sorted keys par loop chalayenge
            sortedOrderNumbers.forEach(orderNum => {
                const group = data[orderNum];
                const status = group[0].status;
                if (currentFilter !== 'all' && status !== currentFilter) return; // forEach me return continue ka kaam krta hy

                count++;
                const table = group[0].table.table_number;
                const guestName = group[0].guest ? group[0].guest.name : null;
                const guestPhone = group[0].guest ? group[0].guest.phone : null;
                const createdAt = new Date(group[0].created_at);
                const timeStr = createdAt.toLocaleTimeString('en-GB', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const dateStr = createdAt.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short'
                });
                const meta = statusMeta[status] || statusMeta.pending;
                let itemsHtml = '',
                    total = 0;

                group.forEach(item => {
                    itemsHtml += `
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);">
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="width:22px;height:22px;background:rgba(255,255,255,.07);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:rgba(255,255,255,.6);font-family:'JetBrains Mono',monospace;">${item.quantity}</span>
            <span style="color:rgba(255,255,255,.8);font-size:13px;">${item.item.name}</span>
          </div>
          <span style="color:rgba(255,255,255,.4);font-size:12px;font-family:'JetBrains Mono',monospace;">Rs.${item.total}</span>
        </div>`;
                    total += item.total;
                });

                html += `
      <div class="glass-card order-card anim-fade-up ${meta.accent}"
           style="border-radius:16px;overflow:hidden;transition:transform .2s,box-shadow .2s;cursor:default;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.4)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">

        {{-- Card Header --}}
        <div style="padding:16px 18px 12px;display:flex;justify-content:space-between;align-items:flex-start;">
          <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;">
              <span style="color:white;font-weight:700;font-size:15px;">#${orderNum}</span>
              <span class="pill ${meta.pill}">
                ${status === 'pending' ? '⏳' : status === 'preparing' ? '🔥' : status === 'served' ? '✅' : '✕'}
                ${meta.label}
              </span>
            </div>
            <div style="color:rgba(255,255,255,.3);font-size:11px;font-family:'JetBrains Mono',monospace;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <span>🪑 T${table}</span>
              ${guestName ? `<span style="color:rgba(255,255,255,.15);">│</span>
              <span>👤 ${guestName}</span>
              <span style="color:rgba(255,255,255,.15);">│</span>
              <span>📞 ${guestPhone}</span>` : ''}
              <span style="color:rgba(255,255,255,.15);">│</span>
              <span>${dateStr}</span>
              <span style="color:rgba(255,255,255,.15);">│</span>
              <span>${timeStr}</span>
            </div>
          </div>
          <select onchange="updateStatus(${orderNum}, this.value)" class="kstatus-select">
            <option value="pending"   ${status==='pending'   ?'selected':''}>⏳ Pending</option>
            <option value="preparing" ${status==='preparing' ?'selected':''}>🔥 Preparing</option>
            <option value="served"    ${status==='served'    ?'selected':''}>✅ Served</option>
            <option value="cancelled" ${status==='cancelled' ?'selected':''}>✕ Cancelled</option>
          </select>
        </div>

        <hr class="kdivider">

        {{-- Items --}}
        <div style="padding:4px 18px 10px;">
          ${itemsHtml}
          <div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;margin-top:2px;">
            <span style="color:rgba(255,255,255,.25);font-size:11px;letter-spacing:.06em;text-transform:uppercase;font-weight:600;">${group.length} item${group.length!==1?'s':''}</span>
            <span style="color:white;font-weight:700;font-size:15px;font-family:'JetBrains Mono',monospace;">Rs. ${total}</span>
          </div>
        </div>

        <hr class="kdivider">

        {{-- Actions --}}
        <div style="padding:12px 18px;display:flex;gap:8px;align-items:center;">
          <button onclick="openModal(${orderNum})" class="btn btn-purple" style="flex:1;">🔍 Details</button>
          <button onclick="printInvoice(${orderNum})" class="btn btn-pink" style="flex:1;">🖨 Invoice</button>
        </div>

      </div>`;
            }); // 💡 FIX: Loop ka end yahan kiya

            container.innerHTML = html;

            if (count === 0) {
                container.style.display = 'none';
                emptyState.style.display = 'flex';
            } else {
                container.style.display = 'grid';
                emptyState.style.display = 'none';
            }
        }

        // ── Stats ──────────────────────────────────────────
        function updateStats(data) {
            const c = {
                pending: 0,
                preparing: 0,
                served: 0,
                cancelled: 0
            };
            for (let o in data) {
                const s = data[o][0].status;
                if (c[s] !== undefined) c[s]++;
            }
            document.getElementById('statPending').textContent = c.pending;
            document.getElementById('statPreparing').textContent = c.preparing;
            document.getElementById('statServed').textContent = c.served;
            document.getElementById('statCancelled').textContent = c.cancelled;
        }

        // ── Filter ─────────────────────────────────────────
        function setFilter(filter) {
            currentFilter = filter;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('filter-' + filter).classList.add('active');
            renderOrders(allOrdersData);
        }

        // ── Update Status ──────────────────────────────────
        function updateStatus(orderNumber, status) {
            fetch(`/kitchen/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_number: orderNumber,
                        status
                    })
                })
                .then(r => r.json())
                .then(() => {
                    showToast('✅ Status updated', '#1d4ed8', '#93c5fd', 'rgba(59,130,246,.3)');
                    fetchOrders();
                })
                .catch(() => showToast('❌ Update failed', '#b91c1c', '#fca5a5', 'rgba(239,68,68,.3)'));
        }

        // ── Modal ──────────────────────────────────────────
        function openModal(orderNumber) {
            fetch(`/orders/${orderNumber}`)
                .then(r => r.json())
                .then(order => {
                    if (!order.length) return alert('Order not found');

                    const status = order[0].status;
                    const meta = statusMeta[status] || statusMeta.pending;

                    let total = 0;
                    const rows = order.map(item => {
                        total += item.total;
                        return `
                  <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.06);">
                    <div style="display:flex;align-items:center;gap:10px;">
                      <span style="background:rgba(255,255,255,.08);border-radius:6px;padding:2px 7px;font-size:11px;font-weight:700;color:rgba(255,255,255,.6);font-family:'JetBrains Mono',monospace;">
                        ${item.quantity}×
                      </span>
                      <span style="color:rgba(255,255,255,.85);font-size:13px;">${item.item.name}</span>
                    </div>
                    <span style="color:rgba(255,255,255,.4);font-size:12px;font-family:'JetBrains Mono',monospace;">Rs.${item.total}</span>
                  </div>`;
                    }).join('');

                    document.getElementById('modalContent').innerHTML = `
              <div style="margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                  <h2 style="font-size:20px;font-weight:700;color:white;margin:0;">Order #${orderNumber}</h2>
                  <span class="pill ${meta.pill}">${meta.label}</span>
                </div>
                <div style="color:rgba(255,255,255,.3);font-size:12px;font-family:'JetBrains Mono',monospace;display:flex;gap:10px;flex-wrap:wrap;">
                  <span>🪑 Table ${order[0].table.table_number}</span>
                  ${guestName ? `<span>👤 ${guestName}</span><span>📞 ${guestPhone}</span>` : ''}
                  <span>⏱ ${new Date(order[0].created_at).toLocaleString('en-GB')}</span>
                </div>
              </div>

              <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:4px 14px;margin-bottom:20px;">
                ${rows}
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0 6px;">
                  <span style="color:rgba(255,255,255,.4);font-size:12px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;">Total</span>
                  <span style="color:white;font-weight:700;font-size:18px;font-family:'JetBrains Mono',monospace;">Rs. ${total}</span>
                </div>
              </div>

              <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
                <button onclick="updateStatus(${orderNumber},'preparing');closeModal();" class="btn btn-blue" style="padding:10px;">🔥 Confirm</button>
                <button onclick="updateStatus(${orderNumber},'served');closeModal();" class="btn btn-green" style="padding:10px;">✅ Served</button>
                <button onclick="updateStatus(${orderNumber},'cancelled');closeModal();" class="btn btn-red" style="padding:10px;">✕ Decline</button>
              </div>
            `;

                    document.getElementById('orderModal').style.display = 'flex';
                });
        }

        function closeModal() {
            document.getElementById('orderModal').style.display = 'none';
        }


        // ── Toast ──────────────────────────────────────────
        function showToast(message, bg, color, borderColor) {
            const t = document.createElement('div');
            t.className = 'toast';
            t.style.cssText = `background:${bg};color:${color};border-color:${borderColor};`;
            t.textContent = message;
            document.getElementById('toastContainer').appendChild(t);
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateX(110%)';
                t.style.transition = 'all .3s';
            }, 2700);
            setTimeout(() => t.remove(), 3100);
        }

        // ── Beep ───────────────────────────────────────────
        function playBeep() {
            try {
                const ctx = new(window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.45);
            } catch (e) {}
        }

        function printInvoice(n) {
            window.open(`/orders/${n}/invoice`, '_blank');
        }

        // ── Init ───────────────────────────────────────────
        setInterval(fetchOrders, 5000);
        fetchOrders();
    </script>
@endsection
