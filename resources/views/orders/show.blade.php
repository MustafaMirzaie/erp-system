@extends('layouts.app')

@section('title', 'جزئیات سفارش | ERP System')
@section('page-title', 'جزئیات سفارش')

@section('content')
    <div class="row">
        <!-- اطلاعات اصلی سفارش -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">سفارش <span id="order-id"></span></h4>
                        <span id="order-status"></span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">مشتری</p>
                            <h6 id="customer-name">-</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">شرکت</p>
                            <h6 id="company-name">-</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">آدرس</p>
                            <h6 id="address-info">-</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">گیرنده</p>
                            <h6 id="contact-info">-</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">رسمی / غیررسمی</p>
                            <h6 id="is-official">-</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">تاریخ ثبت</p>
                            <h6 id="created-at">-</h6>
                        </div>
                    </div>

                    <!-- آیتم‌های سفارش -->
                    <h5 class="mt-4 mb-3">آیتم‌های سفارش</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>قیمت پایه</th>
                                <th>قیمت نهایی</th>
                            </tr>
                            </thead>
                            <tbody id="items-table">
                            <tr><td colspan="4" class="text-center">در حال بارگذاری...</td></tr>
                            </tbody>
                            <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">مجموع کل:</td>
                                <td class="fw-bold" id="total-price">-</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- وضعیت گردش کار -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">وضعیت تایید</h4>
                    <div id="approvals-list">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="/orders" class="btn btn-secondary w-100">
                        <i class="bx bx-arrow-back me-1"></i> بازگشت به لیست
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const orderId = {{ $id }};

        const statusMap = {
            'draft':    '<span class="badge bg-secondary fs-6">پیش‌نویس</span>',
            'pending':  '<span class="badge bg-warning fs-6">در انتظار تایید</span>',
            'approved': '<span class="badge bg-success fs-6">تایید شده</span>',
            'rejected': '<span class="badge bg-danger fs-6">رد شده</span>',
            'revision': '<span class="badge bg-info fs-6">نیاز به ویرایش</span>',
        };

        const approvalStatusMap = {
            'pending':  '<span class="badge bg-warning">در انتظار</span>',
            'approved': '<span class="badge bg-success">تایید</span>',
            'rejected': '<span class="badge bg-danger">رد</span>',
            'revision': '<span class="badge bg-info">ویرایش</span>',
        };

        // بارگذاری سفارش
        apiCall(`/api/v1/orders/${orderId}`).then(order => {
            document.getElementById('order-id').textContent = '#' + order.id;
            document.getElementById('order-status').innerHTML = statusMap[order.status] || order.status;
            document.getElementById('customer-name').textContent = order.customer?.name || '-';
            document.getElementById('company-name').textContent = order.company?.name || '-';
            document.getElementById('address-info').textContent = order.address?.full_address || '-';
            document.getElementById('contact-info').textContent = order.contact?.full_name || '-';
            document.getElementById('is-official').textContent = order.is_official ? 'رسمی' : 'غیررسمی';
            document.getElementById('created-at').textContent = order.created_at
                ? new Date(order.created_at).toLocaleDateString('fa-IR') : '-';
            document.getElementById('total-price').textContent = order.total_price
                ? Number(order.total_price).toLocaleString('fa-IR') + ' ریال' : '-';

            // آیتم‌ها
            const tbody = document.getElementById('items-table');
            if (order.items && order.items.length > 0) {
                tbody.innerHTML = order.items.map(item => `
                <tr>
                    <td>${item.product?.name || 'محصول #' + item.product_id}</td>
                    <td>${item.quantity}</td>
                    <td>${Number(item.base_price).toLocaleString('fa-IR')} ریال</td>
                    <td>${Number(item.final_price).toLocaleString('fa-IR')} ریال</td>
                </tr>
            `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">آیتمی یافت نشد</td></tr>';
            }
        });

        // بارگذاری approvals
        apiCall(`/api/v1/workflow/orders/${orderId}/approvals`).then(approvals => {
            const list = document.getElementById('approvals-list');
            if (approvals.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">گردش کاری تعریف نشده</p>';
                return;
            }

            list.innerHTML = `
            <ul class="verti-timeline list-unstyled">
                ${approvals.map(a => `
                    <li class="event-list">
                        <div class="event-timeline-dot">
                            <i class="bx bx-right-arrow-circle font-size-18 ${a.status === 'approved' ? 'text-success' : a.status === 'rejected' ? 'text-danger' : ''}"></i>
                        </div>
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${a.step?.name || 'مرحله'}</h6>
                                <div>${approvalStatusMap[a.status] || a.status}</div>
                                ${a.description ? `<p class="text-muted small mt-1">${a.description}</p>` : ''}
                                ${a.action_at ? `<p class="text-muted small">${new Date(a.action_at).toLocaleDateString('fa-IR')}</p>` : ''}
                            </div>
                        </div>
                    </li>
                `).join('')}
            </ul>
        `;
        });
    </script>
@endpush
