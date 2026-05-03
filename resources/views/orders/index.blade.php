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
                        <table id="orders-datatable" class="table table-bordered dt-responsive nowrap w-100">
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
                            <tbody id="orders-table"></tbody>
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
                        <a href="/orders/${o.id}" class="btn btn-sm btn-primary waves-effect me-1">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="/orders/${o.id}/approve" class="btn btn-sm btn-warning waves-effect me-1" title="بررسی">
                            <i class="bx bx-check-shield"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-secondary waves-effect" title="تکرار سفارش"
                            onclick="repeatOrder(${o.id})">
                            <i class="bx bx-copy"></i>
                        </button>
                        <a href="/orders/${o.id}/print" class="btn btn-sm btn-outline-dark waves-effect" title="پرینت" target="_blank">
                            <i class="bx bx-printer"></i>
                        </a>
                    </td>
                </tr>
            `).join('');
        }

        apiCall('/api/v1/orders').then(orders => {
            const tbody = document.getElementById('orders-table');
            tbody.innerHTML = orders.map(o => `...`).join('');

            // DataTable init
            if ($.fn.DataTable.isDataTable('#orders-datatable')) {
                $('#orders-datatable').DataTable().destroy();
            }
            $('#orders-datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fa.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'print',  text: '<i class="bx bx-printer me-1"></i> پرینت',  className: 'btn btn-sm btn-secondary' },
                    { extend: 'excel',  text: '<i class="bx bx-export me-1"></i> Excel',   className: 'btn btn-sm btn-success' },
                ],
                order: [[0, 'desc']],
                pageLength: 25,
                responsive: true,
            });
        });

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

        // تکرار سفارش
        function repeatOrder(id) {
            window.location.href = '/orders/create?repeat=' + id;
        }
    </script>
@endpush
