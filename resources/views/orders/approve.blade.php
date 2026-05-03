@extends('layouts.app')
@section('title', 'بررسی سفارش | ERP System')
@section('page-title', 'بررسی و تایید سفارش')

@section('content')
    <div class="row g-3">
        <div class="col-xl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">اطلاعات سفارش <span id="order-badge"></span></h5>
                        <span id="order-status-badge"></span>
                    </div>
                    <div id="order-info" class="row g-3">
                        <div class="col-12 text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول آیتم‌ها -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">آیتم‌های سفارش</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="table-light text-center" style="font-size:12px;">
                            <tr>
                                <th>ردیف</th>
                                <th>شرح کالا</th>
                                <th>قیمت واحد</th>
                                <th>نوع بسته‌بندی</th>
                                <th>تعداد</th>
                                <th>مقدار</th>
                                <th>واحد</th>
                                <th>مقدار کل</th>
                                <th>مبلغ (ریال)</th>
                            </tr>
                            </thead>
                            <tbody id="items-body">
                            <tr><td colspan="9" class="text-center py-3">
                                    <div class="spinner-border text-primary spinner-border-sm"></div>
                                </td></tr>
                            </tbody>
                            <tfoot class="table-light" style="font-size:13px;">
                            <tr><td colspan="8" class="text-end fw-bold">جمع جزء:</td>
                                <td class="text-center fw-bold" id="subtotal">-</td></tr>
                            <tr><td colspan="8" class="text-end">ارزش افزوده:</td>
                                <td class="text-center" id="tax-total">-</td></tr>
                            <tr><td colspan="8" class="text-end">پس کرایه:</td>
                                <td class="text-center" id="freight-display">-</td></tr>
                            <tr class="table-primary">
                                <td colspan="8" class="text-end fw-bold">جمع کل:</td>
                                <td class="text-center fw-bold" id="grand-total">-</td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- بخش تایید/رد -->
            <div class="card mb-3" id="approval-section" style="display:none;">
                <div class="card-body">
                    <h5 class="card-title mb-3">اقدام شما</h5>
                    <div id="approval-status-info" class="mb-3"></div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات (اختیاری)</label>
                        <textarea class="form-control" id="approval-description" rows="3"
                                  placeholder="توضیحات خود را وارد کنید..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success waves-effect" id="approve-btn">
                            <i class="bx bx-check me-1"></i> تایید سفارش
                        </button>
                        <button type="button" class="btn btn-danger waves-effect" id="reject-btn">
                            <i class="bx bx-x me-1"></i> رد سفارش
                        </button>
                        <a href="/orders" class="btn btn-secondary">بازگشت</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ستون کناری -->
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">توضیحات سفارش</h5>
                    <div id="order-notes" class="text-muted small">در حال بارگذاری...</div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">خلاصه مالی</h5>
                    <table class="table table-sm mb-0">
                        <tr><td>جمع جزء:</td><td class="text-end" id="summary-subtotal">-</td></tr>
                        <tr><td>ارزش افزوده:</td><td class="text-end" id="summary-tax">-</td></tr>
                        <tr><td>کرایه:</td><td class="text-end" id="summary-freight">-</td></tr>
                        <tr class="table-primary fw-bold">
                            <td>جمع کل:</td>
                            <td class="text-end" id="summary-total">-</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">وضعیت تایید</h5>
                    <div id="approvals-list">
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const orderId = {{ $id }};
        let currentApprovalId = null;

        const statusMap = {
            'draft':    '<span class="badge bg-secondary">پیش‌نویس</span>',
            'pending':  '<span class="badge bg-warning">در انتظار تایید</span>',
            'approved': '<span class="badge bg-success">تایید شده</span>',
            'rejected': '<span class="badge bg-danger">رد شده</span>',
            'revision': '<span class="badge bg-info">ویرایش</span>',
        };

        const approvalStatusMap = {
            'pending':  '<span class="badge bg-warning">در انتظار</span>',
            'approved': '<span class="badge bg-success">تایید شده</span>',
            'rejected': '<span class="badge bg-danger">رد شده</span>',
        };

        const fmt = n => n ? Number(n).toLocaleString('fa-IR') + ' ریال' : '0 ریال';

        // بارگذاری سفارش
        apiCall(`/api/v1/orders/${orderId}`).then(order => {
            document.getElementById('order-badge').textContent = '#' + order.id;
            document.getElementById('order-status-badge').innerHTML = statusMap[order.status] || order.status;

            // اطلاعات اصلی
            const fields = [
                ['نام مشتری', order.customer?.name],
                ['شرکت', order.company?.name],
                ['آدرس تحویل', order.address?.full_address],
                ['گیرنده', order.contact?.full_name],
                ['نوع حواله', order.is_official ? 'رسمی' : 'غیررسمی'],
                ['تاریخ صدور', order.issue_date || '-'],
                ['تاریخ ارسال', order.send_date || '-'],
                ['شرایط پرداخت', order.payment_terms || '-'],
            ];

            document.getElementById('order-info').innerHTML = fields.map(([label, val]) => `
        <div class="col-md-3">
            <label class="form-label text-muted small mb-1">${label}</label>
            <input type="text" class="form-control form-control-sm" value="${val || '-'}" disabled>
        </div>
    `).join('');

            // آیتم‌ها
            const tbody = document.getElementById('items-body');
            if (order.items?.length) {
                let subtotal = 0;
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

                const tax = order.is_official ? subtotal * 0.1 : 0;
                const freight = parseFloat(order.freight_amount) || 0;
                const grand = subtotal + tax + freight;

                document.getElementById('subtotal').textContent = Number(subtotal).toLocaleString('fa-IR');
                document.getElementById('tax-total').textContent = Number(tax).toLocaleString('fa-IR');
                document.getElementById('freight-display').textContent = Number(freight).toLocaleString('fa-IR');
                document.getElementById('grand-total').textContent = Number(grand).toLocaleString('fa-IR');
                document.getElementById('summary-subtotal').textContent = fmt(subtotal);
                document.getElementById('summary-tax').textContent = fmt(tax);
                document.getElementById('summary-freight').textContent = fmt(freight);
                document.getElementById('summary-total').textContent = fmt(grand);
            }

            // توضیحات
            document.getElementById('order-notes').innerHTML = order.notes
                ? `<p>${order.notes}</p>` : '<p class="text-muted">توضیحاتی ثبت نشده</p>';
        });

        // بارگذاری approvals
        apiCall(`/api/v1/workflow/orders/${orderId}/approvals`).then(approvals => {
            const list = document.getElementById('approvals-list');

            if (!approvals.length) {
                list.innerHTML = '<p class="text-muted text-center small">گردش کاری تعریف نشده</p>';
                return;
            }

            list.innerHTML = approvals.map(a => `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small">${a.step?.name || '-'}</span>
            ${approvalStatusMap[a.status] || a.status}
        </div>
    `).join('');

            // پیدا کردن اولین pending
            const pending = approvals.find(a => a.status === 'pending');
            if (pending) {
                currentApprovalId = pending.id;
                document.getElementById('approval-section').style.display = 'block';
                document.getElementById('approval-status-info').innerHTML =
                    `<div class="alert alert-warning">مرحله در انتظار تایید شما: <strong>${pending.step?.name || '-'}</strong></div>`;
            }
        });

        // تایید
        document.getElementById('approve-btn')?.addEventListener('click', async function() {
            if (!currentApprovalId) return;
            this.disabled = true;
            const data = await apiCall(`/api/v1/workflow/approvals/${currentApprovalId}/approve`, {
                method: 'POST',
                body: JSON.stringify({ description: document.getElementById('approval-description').value })
            });
            showToast('سفارش تایید شد', 'success');
            setTimeout(() => location.reload(), 1000);
        });

        // رد
        document.getElementById('reject-btn')?.addEventListener('click', async function() {
            if (!currentApprovalId) return;
            this.disabled = true;
            const data = await apiCall(`/api/v1/workflow/approvals/${currentApprovalId}/reject`, {
                method: 'POST',
                body: JSON.stringify({ description: document.getElementById('approval-description').value })
            });
            showToast('سفارش رد شد', 'warning');
            setTimeout(() => location.reload(), 1000);
        });
    </script>
@endpush
