@extends('layouts.app')
@section('title', 'گزارش خرید مشتریان | ERP System')
@section('page-title', 'گزارش خرید مشتریان')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">مشتری</label>
                            <select class="form-select" id="customer-filter">
                                <option value="">همه مشتریان</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="search-btn">
                                <i class="bx bx-search me-1"></i> جستجو
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">گزارش سفارش‌ها</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>مشتری</th>
                                <th>شرکت</th>
                                <th>مبلغ کل</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody id="report-table">
                            <tr><td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary"></div>
                                </td></tr>
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
        };

        let allOrders = [];

        // بارگذاری مشتریان
        apiCall('/api/v1/customers').then(customers => {
            const sel = document.getElementById('customer-filter');
            customers.forEach(c => sel.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        });

        // بارگذاری سفارش‌ها
        function loadOrders(customerId = '') {
            apiCall('/api/v1/orders').then(orders => {
                allOrders = orders;
                renderOrders(customerId ? orders.filter(o => o.customer?.id == customerId) : orders);
            });
        }

        function renderOrders(orders) {
            const tbody = document.getElementById('report-table');
            if (!orders.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">سفارشی یافت نشد</td></tr>';
                return;
            }
            tbody.innerHTML = orders.map(o => `
        <tr>
            <td>#${o.id}</td>
            <td>${o.customer?.name || '-'}</td>
            <td>${o.company?.name || '-'}</td>
            <td>${o.total_price ? Number(o.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
            <td>${statusMap[o.status] || o.status}</td>
            <td>${o.created_at ? new Date(o.created_at).toLocaleDateString('fa-IR') : '-'}</td>
            <td><a href="/orders/${o.id}" class="btn btn-sm btn-primary">مشاهده</a></td>
        </tr>
    `).join('');
        }

        loadOrders();

        document.getElementById('search-btn').addEventListener('click', () => {
            loadOrders(document.getElementById('customer-filter').value);
        });
    </script>
@endpush
