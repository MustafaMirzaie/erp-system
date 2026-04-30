@extends('layouts.app')

@section('title', 'داشبورد | ERP System')
@section('page-title', 'داشبورد')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">سفارش‌های در انتظار</p>
                            <h4 class="mb-0" id="pending-count">...</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title">
                                <i class="bx bx-receipt font-size-24"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">مشتریان فعال</p>
                            <h4 class="mb-0" id="customer-count">...</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                            <span class="avatar-title">
                                <i class="bx bxs-user-detail font-size-24"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">محصولات فعال</p>
                            <h4 class="mb-0" id="product-count">...</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                            <span class="avatar-title">
                                <i class="bx bx-package font-size-24"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">آخرین سفارش‌ها</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>مشتری</th>
                                <th>شرکت</th>
                                <th>مبلغ کل</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody id="orders-table">
                            <tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>
                            </tbody>
                        </table>
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

        // بارگذاری سفارش‌ها
        apiCall('/api/v1/orders').then(orders => {
            document.getElementById('pending-count').textContent =
                orders.filter(o => o.status === 'pending').length;

            const tbody = document.getElementById('orders-table');
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">هیچ سفارشی یافت نشد</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(o => `
            <tr>
                <td><a href="/orders/${o.id}" class="text-body fw-bold">#${o.id}</a></td>
                <td>${o.customer?.name || '-'}</td>
                <td>${o.company?.name || '-'}</td>
                <td>${o.total_price ? Number(o.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>${statusMap[o.status] || o.status}</td>
                <td>${o.created_at ? new Date(o.created_at).toLocaleDateString('fa-IR') : '-'}</td>
            </tr>
        `).join('');
        });

        // بارگذاری مشتریان
        apiCall('/api/v1/customers').then(customers => {
            document.getElementById('customer-count').textContent = customers.length;
        });

        // بارگذاری محصولات
        apiCall('/api/v1/products').then(products => {
            document.getElementById('product-count').textContent = products.length;
        });
    </script>
@endpush
