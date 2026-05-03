@extends('layouts.app')
@section('title', 'جزئیات درخواست | ERP System')
@section('page-title', 'جزئیات درخواست')

@section('content')
    @push('styles')
        <style>
            .stepper { display: flex; align-items: center; padding: 20px 0; overflow-x: auto; }
            .step-item { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 80px; }
            .step-circle {
                width: 36px; height: 36px; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-size: 14px; font-weight: bold; color: #fff;
                background: #dee2e6; position: relative; z-index: 1;
            }
            .step-circle.done    { background: #34c38f; }
            .step-circle.current { background: #556ee6; box-shadow: 0 0 0 4px rgba(85,110,230,.2); }
            .step-circle.rejected{ background: #f46a6a; }
            .step-line { flex: 1; height: 2px; background: #dee2e6; margin: 0 -5px; }
            .step-line.done { background: #34c38f; }
            .step-label { font-size: 11px; text-align: center; margin-top: 6px; color: #74788d; max-width: 80px; }
            .step-label.current { color: #556ee6; font-weight: bold; }
            .step-label.done { color: #34c38f; }
        </style>
    @endpush

    <div class="row g-3">
        <div class="col-xl-9">

            <!-- Stepper -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">مسیر گردش کار</h5>
                    <div class="stepper" id="workflow-stepper">
                        <div class="text-center w-100 py-2">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اطلاعات سفارش -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            اطلاعات درخواست
                            <span id="order-number" class="text-muted fs-6"></span>
                        </h5>
                        <span id="order-status"></span>
                    </div>
                    <div id="order-fields" class="row g-3">
                        <div class="col-12 text-center py-3">
                            <div class="spinner-border text-primary spinner-border-sm"></div>
                        </div>
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
                                <th>نوع</th><th>تعداد</th><th>مقدار</th>
                                <th>واحد</th><th>مقدار کل</th><th>مبلغ (ریال)</th>
                            </tr>
                            </thead>
                            <tbody id="items-body">
                            <tr><td colspan="9" class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
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

            <!-- دکمه‌های اقدام -->
            <div class="card mb-3" id="action-section" style="display:none;">
                <div class="card-body">
                    <h5 class="card-title mb-3">اقدام شما</h5>
                    <div id="action-info" class="mb-3"></div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات</label>
                        <textarea class="form-control" id="action-description" rows="3"
                                  placeholder="توضیحات اختیاری..."></textarea>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-success waves-effect" id="btn-approve">
                            <i class="bx bx-check me-1"></i> تایید
                        </button>
                        <button type="button" class="btn btn-danger waves-effect" id="btn-reject">
                            <i class="bx bx-x me-1"></i> رد
                        </button>
                        <button type="button" class="btn btn-warning waves-effect" id="btn-revision">
                            <i class="bx bx-edit me-1"></i> درخواست اصلاح
                        </button>
                        <a href="/inbox" class="btn btn-secondary">بازگشت</a>
                    </div>
                </div>
            </div>

            <div id="no-action-section" class="alert alert-secondary" style="display:none;">
                <i class="bx bx-info-circle me-2"></i>
                <span id="no-action-text"></span>
                <a href="/inbox" class="btn btn-sm btn-outline-secondary ms-3">بازگشت به صندوق</a>
            </div>
        </div>

        <!-- ستون کناری -->
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">خلاصه مالی</h6>
                    <table class="table table-sm mb-0">
                        <tr><td class="small">جمع جزء:</td>
                            <td class="text-end small" id="s-subtotal">-</td></tr>
                        <tr><td class="small">ارزش افزوده:</td>
                            <td class="text-end small" id="s-tax">-</td></tr>
                        <tr><td class="small">کرایه:</td>
                            <td class="text-end small" id="s-freight">-</td></tr>
                        <tr class="table-primary fw-bold">
                            <td class="small">جمع کل:</td>
                            <td class="text-end small" id="s-total">-</td></tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">تاریخچه اقدامات</h6>
                    <div id="approval-history">
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title border-bottom pb-2">توضیحات سفارش</h6>
                    <div id="order-notes" class="text-muted small">-</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const orderId = {{ $id }};
        const currentUser = JSON.parse(localStorage.getItem('erp_user') || '{}');
        let currentApprovalId = null;

        const statusColors = {
            'pending':  'warning',
            'approved': 'success',
            'rejected': 'danger',
            'revision': 'info',
        };
        const statusLabels = {
            'pending':  'در انتظار تایید',
            'approved': 'تایید شده',
            'rejected': 'رد شده',
            'revision': 'نیاز به اصلاح',
        };
        const fmt = n => n ? Number(n).toLocaleString('fa-IR') + ' ریال' : '0 ریال';

        // بارگذاری همه workflow steps برای stepper
        Promise.all([
            apiCall(`/api/v1/orders/${orderId}`),
            apiCall(`/api/v1/workflow/orders/${orderId}/approvals`),
            apiCall('/api/v1/inbox/roles'),
        ]).then(([order, approvals, myRoles]) => {

            // --- اطلاعات اصلی ---
            document.getElementById('order-number').textContent = order.order_number ? ' — ' + order.order_number : ' — #' + order.id;
            document.getElementById('order-status').innerHTML =
                `<span class="badge bg-${statusColors[order.status] || 'secondary'} fs-6">${statusLabels[order.status] || order.status}</span>`;

            const fields = [
                ['نام مشتری', order.customer?.name],
                ['شرکت', order.company?.name],
                ['آدرس تحویل', order.address?.full_address],
                ['گیرنده', order.contact?.full_name],
                ['نوع حواله', order.is_official ? 'رسمی' : 'غیررسمی'],
                ['تاریخ صدور', order.issue_date || '-'],
                ['تاریخ ارسال', order.send_date || '-'],
                ['شرایط پرداخت', order.payment_terms || '-'],
                ['نوع کرایه', order.freight_type?.name || '-'],
                ['مبلغ کرایه', order.freight_amount ? Number(order.freight_amount).toLocaleString('fa-IR') + ' ریال' : '-'],
            ];

            document.getElementById('order-fields').innerHTML = fields.map(([label, val]) => `
        <div class="col-md-3">
            <label class="form-label text-muted small mb-1">${label}</label>
            <input type="text" class="form-control form-control-sm bg-light" value="${val || '-'}" disabled>
        </div>
    `).join('');

            document.getElementById('order-notes').textContent = order.notes || 'توضیحاتی ثبت نشده';

            // --- آیتم‌ها ---
            const tbody = document.getElementById('items-body');
            let subtotal = 0;
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
            const grand = subtotal + tax + freight;

            ['subtotal', 'tax-total', 'freight-display', 'grand-total'].forEach((id, i) => {
                document.getElementById(id).textContent = Number([subtotal, tax, freight, grand][i]).toLocaleString('fa-IR');
            });
            document.getElementById('s-subtotal').textContent = fmt(subtotal);
            document.getElementById('s-tax').textContent = fmt(tax);
            document.getElementById('s-freight').textContent = fmt(freight);
            document.getElementById('s-total').textContent = fmt(grand);

            // --- Stepper ---
            buildStepper(approvals, order);

            // --- تاریخچه ---
            buildHistory(approvals);

            // --- بررسی دسترسی اقدام ---
            const pendingApproval = approvals.find(a => a.status === 'pending');
            const isAdmin = myRoles.is_admin;
            const canAct = pendingApproval && (isAdmin || (myRoles.has_inbox &&
                myRoles.steps.some(s => s.id === pendingApproval.step_id)));

            if (canAct) {
                currentApprovalId = pendingApproval.id;
                document.getElementById('action-section').style.display = 'block';
                document.getElementById('action-info').innerHTML =
                    `<div class="alert alert-warning mb-0">
                <i class="bx bx-bell me-2"></i>
                نوبت شماست! مرحله: <strong>${pendingApproval.step?.name}</strong>
            </div>`;
            } else if (order.status === 'pending') {
                const currentStep = approvals.find(a => a.status === 'pending');
                document.getElementById('no-action-section').style.display = 'block';
                document.getElementById('no-action-text').textContent =
                    currentStep
                        ? `این درخواست در مرحله «${currentStep.step?.name}» قرار دارد. اقدام با شما نیست.`
                        : 'این درخواست در انتظار بررسی است.';
            } else {
                document.getElementById('no-action-section').style.display = 'block';
                document.getElementById('no-action-text').textContent =
                    `وضعیت این درخواست: ${statusLabels[order.status] || order.status}`;
            }
        });

        function buildStepper(approvals, order) {
            const stepper = document.getElementById('workflow-stepper');
            if (!approvals.length) {
                stepper.innerHTML = '<p class="text-muted text-center w-100">مراحل گردش کار تعریف نشده</p>';
                return;
            }

            // اضافه کردن مرحله اول (ثبت سفارش)
            const steps = [{ name: 'ثبت سفارش', status: 'done', is_first: true }, ...approvals];
            let html = '';

            steps.forEach((step, i) => {
                const status = step.is_first ? 'done' :
                    step.status === 'approved' ? 'done' :
                        step.status === 'rejected' ? 'rejected' :
                            approvals.findIndex(a => a.status === 'pending') === (i - 1) ? 'current' : 'waiting';

                const icon = status === 'done' ? '<i class="bx bx-check"></i>' :
                    status === 'rejected' ? '<i class="bx bx-x"></i>' :
                        status === 'current' ? '<i class="bx bx-time"></i>' :
                            (i);

                if (i > 0) html += `<div class="step-line ${status === 'done' ? 'done' : ''}"></div>`;

                html += `
            <div class="step-item">
                <div class="step-circle ${status}">${icon}</div>
                <div class="step-label ${status}">${step.step?.name || step.name}</div>
            </div>`;
            });

            // اضافه کردن مرحله آخر (تکمیل)
            const allDone = approvals.every(a => a.status === 'approved');
            html += `<div class="step-line ${allDone ? 'done' : ''}"></div>`;
            html += `<div class="step-item">
        <div class="step-circle ${allDone ? 'done' : ''}">${allDone ? '<i class="bx bx-check"></i>' : '✓'}</div>
        <div class="step-label ${allDone ? 'done' : ''}">تکمیل</div>
    </div>`;

            stepper.innerHTML = html;
        }

        function buildHistory(approvals) {
            const container = document.getElementById('approval-history');
            const acted = approvals.filter(a => a.status !== 'pending');

            if (!acted.length) {
                container.innerHTML = '<p class="text-muted small text-center">هنوز اقدامی انجام نشده</p>';
                return;
            }

            container.innerHTML = acted.map(a => `
        <div class="border-bottom pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small fw-bold">${a.step?.name || '-'}</span>
                <span class="badge bg-${statusColors[a.status]}">${statusLabels[a.status] || a.status}</span>
            </div>
            ${a.action_at ? `<div class="text-muted" style="font-size:11px;">${new Date(a.action_at).toLocaleDateString('fa-IR')}</div>` : ''}
            ${a.description ? `<div class="small text-muted mt-1">${a.description}</div>` : ''}
        </div>
    `).join('');
        }

        // دکمه‌های اقدام
        async function doAction(status) {
            if (!currentApprovalId) return;
            const description = document.getElementById('action-description').value;
            const btn = document.getElementById(status === 'approved' ? 'btn-approve' : status === 'rejected' ? 'btn-reject' : 'btn-revision');
            btn.disabled = true;

            const endpoint = status === 'approved'
                ? `/api/v1/workflow/approvals/${currentApprovalId}/approve`
                : `/api/v1/workflow/approvals/${currentApprovalId}/reject`;

            await apiCall(endpoint, { method: 'POST', body: JSON.stringify({ description }) });
            showToast(status === 'approved' ? 'سفارش تایید شد' : 'سفارش رد شد', status === 'approved' ? 'success' : 'warning');
            setTimeout(() => window.location.href = '/inbox', 1200);
        }

        document.getElementById('btn-approve')?.addEventListener('click', () => doAction('approved'));
        document.getElementById('btn-reject')?.addEventListener('click', () => doAction('rejected'));
        document.getElementById('btn-revision')?.addEventListener('click', () => doAction('rejected'));
    </script>
@endpush
