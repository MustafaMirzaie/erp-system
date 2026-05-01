@extends('layouts.app')

@section('title', 'داشبورد | ERP System')
@section('page-title', 'داشبورد')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

    <!-- کارت‌های آمار -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">کل سفارش‌ها</p>
                            <h4 class="mb-0" id="stat-total-orders">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title"><i class="bx bx-receipt font-size-24"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">سفارش‌های تایید شده</p>
                            <h4 class="mb-0" id="stat-approved-orders">
                                <div class="spinner-border spinner-border-sm text-success"></div>
                            </h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <span class="avatar-title"><i class="bx bx-check-circle font-size-24"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">مجموع فروش تایید شده</p>
                            <h4 class="mb-0" id="stat-revenue">
                                <div class="spinner-border spinner-border-sm text-warning"></div>
                            </h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title"><i class="bx bx-dollar font-size-24"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">مشتریان فعال</p>
                            <h4 class="mb-0" id="stat-customers">
                                <div class="spinner-border spinner-border-sm text-info"></div>
                            </h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                <span class="avatar-title"><i class="bx bxs-user-detail font-size-24"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- نمودار وضعیت سفارش‌ها -->
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">وضعیت سفارش‌ها</h4>
                    <div style="position:relative; height:280px;">
                        <canvas id="orders-chart"></canvas>
                    </div>
                    <div id="chart-loading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- top مشتریان -->
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">برترین مشتریان</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>مشتری</th>
                                <th>تعداد سفارش</th>
                                <th>مجموع فروش</th>
                            </tr>
                            </thead>
                            <tbody id="top-customers-table">
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <div class="spinner-border text-primary"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- آخرین سفارش‌ها -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">آخرین سفارش‌ها</h4>
                        <a href="/orders" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>مشتری</th>
                                <th>مبلغ کل</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody id="orders-table">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="spinner-border text-primary"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- برترین کارشناسان -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">برترین کارشناسان فروش</h4>
                    <div id="top-sales-list">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const statusMap = {
            'draft':    '<span class="badge bg-secondary">پیش‌نویس</span>',
            'pending':  '<span class="badge bg-warning">در انتظار</span>',
            'approved': '<span class="badge bg-success">تایید شده</span>',
            'rejected': '<span class="badge bg-danger">رد شده</span>',
            'revision': '<span class="badge bg-info">ویرایش</span>',
        };

        // بارگذاری summary
        apiCall('/api/v1/reports/summary').then(data => {
            document.getElementById('stat-total-orders').textContent   = data.orders.total;
            document.getElementById('stat-approved-orders').textContent = data.orders.approved;
            document.getElementById('stat-customers').textContent      = data.customers;
            document.getElementById('stat-revenue').textContent =
                Number(data.revenue || 0).toLocaleString('fa-IR') + ' ریال';
        });

        // نمودار وضعیت سفارش‌ها
        apiCall('/api/v1/reports/orders-by-status').then(data => {
            document.getElementById('chart-loading').style.display = 'none';

            const ctx = document.getElementById('orders-chart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['پیش‌نویس', 'در انتظار', 'تایید شده', 'رد شده', 'ویرایش'],
                    datasets: [{
                        data: [
                            data.draft,
                            data.pending,
                            data.approved,
                            data.rejected,
                            data.revision
                        ],
                        backgroundColor: ['#74788d', '#f1b44c', '#34c38f', '#f46a6a', '#50a5f1'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });

        // top مشتریان
        apiCall('/api/v1/reports/top-customers').then(customers => {
            const tbody = document.getElementById('top-customers-table');
            if (customers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">داده‌ای یافت نشد</td></tr>';
                return;
            }
            tbody.innerHTML = customers.map((c, i) => `
            <tr>
                <td>
                    <span class="badge bg-primary me-2">${i + 1}</span>
                    ${c.name}
                </td>
                <td>${c.order_count} سفارش</td>
                <td>${Number(c.total_revenue || 0).toLocaleString('fa-IR')} ریال</td>
            </tr>
        `).join('');
        });

        // آخرین سفارش‌ها
        apiCall('/api/v1/orders').then(orders => {
            const tbody = document.getElementById('orders-table');
            const recent = orders.slice(0, 5);

            if (recent.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">سفارشی یافت نشد</td></tr>';
                return;
            }

            tbody.innerHTML = recent.map(o => `
            <tr>
                <td><a href="/orders/${o.id}" class="text-body fw-bold">#${o.id}</a></td>
                <td>${o.customer?.name || '-'}</td>
                <td>${o.total_price ? Number(o.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>${statusMap[o.status] || o.status}</td>
                <td>${o.created_at ? new Date(o.created_at).toLocaleDateString('fa-IR') : '-'}</td>
            </tr>
        `).join('');
        });

        // برترین کارشناسان
        apiCall('/api/v1/reports/top-sales-users').then(users => {
            const list = document.getElementById('top-sales-list');
            if (users.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">داده‌ای یافت نشد</p>';
                return;
            }

            list.innerHTML = users.map((u, i) => `
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-sm me-3">
                    <span class="avatar-title rounded-circle bg-${['primary','success','warning','info','secondary'][i]} font-size-16">
                        ${u.full_name?.charAt(0) || '?'}
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">${u.full_name}</h6>
                    <p class="text-muted small mb-0">${u.order_count} سفارش | میانگین ${Math.round(u.avg_share)}% مشارکت</p>
                </div>
                <span class="badge bg-primary">#${i + 1}</span>
            </div>
        `).join('');
        });
    </script>
@endpush
