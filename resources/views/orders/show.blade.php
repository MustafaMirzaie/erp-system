@extends('layouts.app')
@section('title', 'جزئیات سفارش | ERP System')
@section('page-title', 'جزئیات سفارش')

@section('content')
    <div class="row g-3">
        <div class="col-xl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">سفارش <span id="order-id"></span></h5>
                        <div class="d-flex gap-2">
                            <span id="order-status"></span>
                            <a id="print-btn" href="#" class="btn btn-sm btn-outline-dark" target="_blank">
                                <i class="bx bx-printer me-1"></i> پرینت
                            </a>
                        </div>
                    </div>

                    <div class="row g-3" id="order-fields">
                        <div class="col-12 text-center py-3">
                            <div class="spinner-border text-primary spinner-border-sm"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- کارشناسان فروش -->
            <div class="card mb-3" id="sales-card" style="display:none;">
                <div class="card-body">
                    <h5 class="card-title mb-3">کارشناسان فروش</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                            <tr><th>کارشناس</th><th>درصد مشارکت</th></tr>
                            </thead>
                            <tbody id="sales-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- آیتم‌ها -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">آیتم‌های سفارش</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="table-light text-center" style="font-size:12px;">
                            <tr>
                                <th>ردیف</th><th>شرح کالا</th><th>قیمت واحد</th>
                                <th>نوع بسته‌بندی</th><th>تعداد</th><th>مقدار</th>
                                <th>واحد</th><th>مقدار کل</th><th>مبلغ (ریال)</th>
                            </tr>
                            </thead>
                            <tbody id="items-table">
                            <tr><td colspan="9" class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                </td></tr>
                            </tbody>
                            <tfoot class="table-light" style="font-size:13px;">
                            <tr><td colspan="8" class="text-end fw-bold">جمع جزء:</td>
                                <td class="text-end fw-bold" id="subtotal">-</td></tr>
                            <tr><td colspan="8" class="text-end">ارزش افزوده (10%):</td>
                                <td class="text-end" id="tax-total">-</td></tr>
                            <tr><td colspan="8" class="text-end">پس کرایه:</td>
                                <td class="text-end" id="freight-display">-</td></tr>
                            <tr class="table-primary">
                                <td colspan="8" class="text-end fw-bold">جمع کل:</td>
                                <td class="text-end fw-bold" id="grand-total">-</td></tr>
                            <tr class="table-warning">
                                <td colspan="8" class="text-end">رقم بیمه‌نامه:</td>
                                <td class="text-end" id="insurance-display">-</td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">خلاصه مالی</h6>
                    <table class="table table-sm mb-0">
                        <tr><td class="small">جمع جزء:</td><td class="text-end small" id="s-subtotal">-</td></tr>
                        <tr><td class="small">ارزش افزوده:</td><td class="text-end small" id="s-tax">-</td></tr>
                        <tr><td class="small">کرایه:</td><td class="text-end small" id="s-freight">-</td></tr>
                        <tr class="table-primary fw-bold">
                            <td class="small">جمع کل:</td><td class="text-end small" id="s-total">-</td></tr>
                        <tr class="table-warning">
                            <td class="small">بیمه‌نامه:</td><td class="text-end small" id="s-insurance">-</td></tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">وضعیت تایید</h6>
                    <div id="approvals-list">
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">توضیحات</h6>
                    <div id="order-notes" class="text-muted small">-</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="/orders" class="btn btn-secondary w-100 mb-2">
                        <i class="bx bx-arrow-back me-1"></i> بازگشت
                    </a>
                    <a id="repeat-btn" href="#" class="btn btn-outline-primary w-100">
                        <i class="bx bx-copy me-1"></i> تکرار سفارش
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
        };
        const approvalStatusMap = {
            'pending':  '<span class="badge bg-warning">در انتظار</span>',
            'approved': '<span class="badge bg-success">تایید</span>',
            'rejected': '<span class="badge bg-danger">رد</span>',
        };
        const fmt = n => n ? Number(n).toLocaleString('fa-IR') + ' ریال' : '0 ریال';

        document.getElementById('print-btn').href = `/orders/${orderId}/print`;
        document.getElementById('repeat-btn').href = `/orders/create?repeat=${orderId}`;

        apiCall(`/api/v1/orders/${orderId}`).then(order => {
            document.getElementById('order-id').textContent = '#' + order.id;
            document.getElementById('order-status').innerHTML = statusMap[order.status] || order.status;
            document.getElementById('order-notes').textContent = order.notes || 'توضیحاتی ثبت نشده';

            const fields = [
                ['مشتری', order.customer?.name],
                ['شرکت', order.company?.name],
                ['نوع حواله', order.is_official ? 'رسمی' : 'غیررسمی'],
                ['تاریخ صدور', order.issue_date ? new Date(order.issue_date).toLocaleDateString('fa-IR') : '-'],
                ['تاریخ ارسال', order.send_date ? new Date(order.send_date).toLocaleDateString('fa-IR') : '-'],
                ['آدرس تحویل', order.address?.full_address],
                ['گیرنده', order.contact?.full_name],
                ['موبایل گیرنده', order.contact?.mobile],
                ['نوع کرایه', order.freight_type?.name],
                ['مبلغ کرایه', order.freight_amount ? Number(order.freight_amount).toLocaleString('fa-IR') + ' ریال' : '-'],
                ['شرایط پرداخت', order.payment_terms],
                ['شماره سفارش', order.order_number],
            ];

            document.getElementById('order-fields').innerHTML = fields.map(([label, val]) => `
        <div class="col-md-3">
            <label class="form-label text-muted small mb-1">${label}</label>
            <input type="text" class="form-control form-control-sm bg-light"
                value="${val || '-'}" disabled>
        </div>
    `).join('');

            // کارشناسان
            if (order.sales?.length) {
                document.getElementById('sales-card').style.display = 'block';
                document.getElementById('sales-table').innerHTML = order.sales.map(s => `
            <tr>
                <td>${s.user?.full_name || '-'}</td>
                <td>${s.share_percent}%</td>
            </tr>
        `).join('');
            }

            // آیتم‌ها
            let subtotal = 0;
            const tbody = document.getElementById('items-table');
            if (order.items?.length) {
                tbody.innerHTML = order.items.map((item, i) => {
                    const line = item.quantity * item.base_price;
                    subtotal += line;
                    return `<tr>
                <td class="text-center">${i+1}</td>
                <td>${item.product?.name || '-'}</td>
                <td class="text-end">${Number(item.base_price).toLocaleString('fa-IR')}</td>
                <td class="text-center">${item.packaging_type?.name || '-'}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-center">${item.amount || '-'}</td>
                <td class="text-center">${item.unit?.name || '-'}</td>
                <td class="text-center">${item.amount ? (item.quantity * item.amount).toLocaleString('fa-IR') : '-'}</td>
                <td class="text-end fw-bold">${Number(line).toLocaleString('fa-IR')}</td>
            </tr>`;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">آیتمی ثبت نشده</td></tr>';
            }

            const tax = order.is_official ? subtotal * 0.1 : 0;
            const freight = parseFloat(order.freight_amount) || 0;
            const insurance = parseFloat(order.insurance_amount) || 0;
            const grand = subtotal + tax + freight;

            document.getElementById('subtotal').textContent = fmt(subtotal);
            document.getElementById('tax-total').textContent = fmt(tax);
            document.getElementById('freight-display').textContent = fmt(freight);
            document.getElementById('grand-total').textContent = fmt(grand);
            document.getElementById('insurance-display').textContent = fmt(insurance);
            document.getElementById('s-subtotal').textContent = fmt(subtotal);
            document.getElementById('s-tax').textContent = fmt(tax);
            document.getElementById('s-freight').textContent = fmt(freight);
            document.getElementById('s-total').textContent = fmt(grand);
            document.getElementById('s-insurance').textContent = fmt(insurance);
        });

        // approvals
        apiCall(`/api/v1/workflow/orders/${orderId}/approvals`).then(approvals => {
            const list = document.getElementById('approvals-list');
            if (!approvals.length) {
                list.innerHTML = '<p class="text-muted small text-center">گردش کاری تعریف نشده</p>';
                return;
            }
            list.innerHTML = approvals.map(a => `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small">${a.step?.name || '-'}</span>
            ${approvalStatusMap[a.status] || a.status}
        </div>
        ${a.description ? `<div class="text-muted small mb-2 ms-2">${a.description}</div>` : ''}
    `).join('');
        });
    </script>
@endpush
