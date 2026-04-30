@extends('layouts.app')

@section('title', 'سفارش‌ها | ERP System')
@section('page-title', 'لیست سفارش‌ها')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">سفارش‌ها</h4>
                        <a href="/orders/create" class="btn btn-primary waves-effect waves-light">
                            <i class="bx bx-plus me-1"></i> سفارش جدید
                        </a>
                    </div>

                    <!-- فیلتر وضعیت -->
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary filter-btn active" data-status="all">همه</button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-status="pending">در انتظار</button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-status="approved">تایید شده</button>
                            <button type="button" class="btn btn-outline-danger filter-btn" data-status="rejected">رد شده</button>
                            <button type="button" class="btn btn-outline-secondary filter-btn" data-status="draft">پیش‌نویس</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0">
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
                            <tbody id="orders-table">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- پیام خالی -->
                    <div id="empty-message" class="text-center py-4" style="display:none;">
                        <i class="bx bx-receipt font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">هیچ سفارشی یافت نشد</p>
                        <a href="/orders/create" class="btn btn-primary btn-sm">ثبت سفارش جدید</a>
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

        let allOrders = [];

        function renderOrders(orders) {
            const tbody = document.getElementById('orders-table');
            const empty = document.getElementById('empty-message');

            if (orders.length === 0) {
                tbody.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            tbody.innerHTML = orders.map(o => `
            <tr>
                <td><a href="/orders/${o.id}" class="text-body fw-bold">#${o.id}</a></td>
                <td>${o.customer?.name || '-'}</td>
                <td>${o.company?.name || '-'}</td>
                <td>${o.total_price ? Number(o.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>${statusMap[o.status] || o.status}</td>
                <td>${o.created_at ? new Date(o.created_at).toLocaleDateString('fa-IR') : '-'}</td>
                <td>
                    <a href="/orders/${o.id}" class="btn btn-sm btn-primary waves-effect">
                        <i class="bx bx-show"></i> مشاهده
                    </a>
                </td>
            </tr>
        `).join('');
        }

        // بارگذاری سفارش‌ها
        apiCall('/api/v1/orders').then(orders => {
            allOrders = orders;
            renderOrders(orders);
        });

        // فیلتر وضعیت
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const status = this.dataset.status;
                if (status === 'all') {
                    renderOrders(allOrders);
                } else {
                    renderOrders(allOrders.filter(o => o.status === status));
                }
            });
        });
    </script>
@endpush
