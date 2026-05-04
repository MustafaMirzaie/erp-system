@extends('layouts.app')
@section('title', 'جزئیات درخواست | ERP System')
@section('page-title', 'جزئیات درخواست')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/inbox">صندوق دریافتی</a></li>
    <li class="breadcrumb-item active">جزئیات درخواست</li>
@endsection

@push('styles')
    <style>
        .step-wrap { display:flex; align-items:center; overflow-x:auto; padding:20px 0; }
        .step-item { display:flex; flex-direction:column; align-items:center; flex:1; min-width:90px; }
        .step-circle {
            width:38px; height:38px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:14px; font-weight:bold; color:#fff;
            background:#dee2e6; position:relative; z-index:1;
        }
        .step-circle.done    { background:#34c38f; }
        .step-circle.current { background:#556ee6; box-shadow:0 0 0 4px rgba(85,110,230,.2); }
        .step-circle.rejected{ background:#f46a6a; }
        .step-line { flex:1; height:2px; background:#dee2e6; margin:0 -5px; margin-top:-19px; }
        .step-line.done { background:#34c38f; }
        .step-label { font-size:11px; text-align:center; margin-top:8px; color:#74788d; max-width:90px; line-height:1.3; }
        .step-label.current { color:#556ee6; font-weight:bold; }
        .step-label.done    { color:#34c38f; }
        .readonly-field { background:#f8f9fa !important; cursor:default; }
    </style>
@endpush

@section('content')
    <div id="page-loading" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">در حال بارگذاری...</p>
    </div>

    <div id="page-content" style="display:none;">
        <div class="row g-3">

            {{-- ستون اصلی --}}
            <div class="col-xl-9">

                {{-- Stepper --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2 mb-3">مسیر گردش کار</h5>
                        <div class="step-wrap" id="workflow-stepper">
                            <div class="text-center w-100">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- اطلاعات سفارش --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="card-title mb-0">اطلاعات سفارش <span id="order-badge" class="text-muted fs-6"></span></h5>
                            <span id="order-status-badge"></span>
                        </div>
                        <div class="row g-2" id="order-fields">
                            <div class="col-12 text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- کارشناسان فروش --}}
                <div class="card mb-3" id="sales-card" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2 mb-3">کارشناسان فروش</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light text-center">
                                <tr><th>کارشناس</th><th>درصد مشارکت</th></tr>
                                </thead>
                                <tbody id="sales-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- آیتم‌های سفارش --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2 mb-3">آیتم‌های سفارش</h5>
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
                                    <td class="text-center fw-bold" id="subtotal">-</td></tr>
                                <tr><td colspan="8" class="text-end">ارزش افزوده (10%):</td>
                                    <td class="text-center" id="tax-total">-</td></tr>
                                <tr><td colspan="8" class="text-end" id="freight-label">پس کرایه:</td>
                                    <td class="text-center" id="freight-display">-</td></tr>
                                <tr class="table-warning">
                                    <td colspan="8" class="text-end">مبلغ بیمه‌نامه:</td>
                                    <td class="text-center" id="insurance-display">-</td></tr>
                                <tr class="table-primary">
                                    <td colspan="8" class="text-end fw-bold">جمع کل:</td>
                                    <td class="text-center fw-bold" id="grand-total">-</td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- دکمه‌های اقدام --}}
                <div class="card mb-3" id="action-section" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2 mb-3">اقدام شما</h5>
                        <div id="action-info" class="mb-3"></div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات (اختیاری)</label>
                            <textarea class="form-control" id="action-description" rows="3"
                                      placeholder="توضیحات خود را وارد کنید..."></textarea>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-success waves-effect" id="btn-approve">
                                <i class="bx bx-check me-1"></i> تایید
                            </button>
                            <button type="button" class="btn btn-danger waves-effect" id="btn-reject">
                                <i class="bx bx-x me-1"></i> رد
                            </button>
                            <a href="/inbox" class="btn btn-secondary">بازگشت</a>
                        </div>
                    </div>
                </div>

                <div id="no-action-section" class="alert alert-secondary d-flex align-items-center" style="display:none!important;">
                    <i class="bx bx-info-circle me-2 fs-5"></i>
                    <span id="no-action-text"></span>
                    <a href="/inbox" class="btn btn-sm btn-outline-secondary ms-auto">بازگشت به صندوق</a>
                </div>
            </div>

            {{-- ستون کناری --}}
            <div class="col-xl-3">

                {{-- توضیحات و ملاحظات --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title border-bottom pb-2 mb-3">توضیحات و ملاحظات</h6>

                        <div class="mb-2">
                            <label class="form-label small text-muted">نوع کرایه</label>
                            <input type="text" class="form-control form-control-sm readonly-field"
                                   id="r-freight-type" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">مبلغ کرایه (ریال)</label>
                            <input type="text" class="form-control form-control-sm readonly-field"
                                   id="r-freight-amount" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">آدرس تحویل</label>
                            <input type="text" class="form-control form-control-sm readonly-field"
                                   id="r-address" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">گیرنده</label>
                            <input type="text" class="form-control form-control-sm readonly-field"
                                   id="r-contact" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">نوع پرداخت</label>
                            <input type="text" class="form-control form-control-sm readonly-field"
                                   id="r-payment-type" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">شرایط پرداخت</label>
                            <textarea class="form-control form-control-sm readonly-field"
                                      id="r-payment-terms" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted">سایر ملاحظات</label>
                            <textarea class="form-control form-control-sm readonly-field"
                                      id="r-notes" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>

                {{-- تاریخچه اقدامات --}}
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
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const orderId = {{ $id }};
        let currentApprovalId = null;

        const statusColors = { pending:'warning', approved:'success', rejected:'danger' };
        const statusLabels = { pending:'در انتظار تایید', approved:'تایید شده', rejected:'رد شده' };
        const paymentTypeMap = { cash:'خرید نقدی', check:'خرید چک', credit:'خرید اعتباری' };
        const fmt = n => n ? Number(n).toLocaleString('fa-IR') + ' ریال' : '0 ریال';

        Promise.all([
            apiCall(`/api/v1/orders/${orderId}`),
            apiCall(`/api/v1/workflow/orders/${orderId}/approvals`),
            apiCall('/api/v1/inbox/roles'),
        ]).then(([order, approvals, myRoles]) => {

            // --- هدر ---
            document.getElementById('order-badge').textContent = order.order_number ? '— ' + order.order_number : '— #' + order.id;
            document.getElementById('order-status-badge').innerHTML =
                `<span class="badge bg-${statusColors[order.status] || 'secondary'} fs-6">${statusLabels[order.status] || order.status}</span>`;

            // --- اطلاعات اصلی ---
            const fields = [
                ['شماره سفارش', order.order_number || '-'],
                ['تاریخ صدور', order.issue_date ? new Date(order.issue_date).toLocaleDateString('fa-IR') : '-'],
                ['شرکت', order.company?.name],
                ['نام مشتری', order.customer?.name],
                ['تاریخ ارسال', order.send_date ? new Date(order.send_date).toLocaleDateString('fa-IR') : '-'],
                ['نوع حواله', order.is_official ? 'رسمی' : 'غیررسمی'],
            ];
            document.getElementById('order-fields').innerHTML = fields.map(([label, val]) => `
        <div class="col-md-2">
            <label class="form-label small text-muted mb-1">${label}</label>
            <input type="text" class="form-control readonly-field" value="${val || '-'}" readonly>
        </div>
    `).join('');

            // --- کارشناسان فروش ---
            if (order.sales?.length) {
                document.getElementById('sales-card').style.display = 'block';
                document.getElementById('sales-table').innerHTML = order.sales.map(s => `
            <tr>
                <td>${s.user?.full_name || '-'}</td>
                <td class="text-center">${s.share_percent}%</td>
            </tr>
        `).join('');
            }

            const orderFields = [
                ['نام مشتری', order.customer?.name, 'col-md-4'],
                ['تاریخ صدور', order.issue_date ? new Date(order.issue_date).toLocaleDateString('fa-IR') : '-', 'col-md-2'],
                ['تاریخ ارسال', order.send_date ? new Date(order.send_date).toLocaleDateString('fa-IR') : '-', 'col-md-2'],
                ['شرکت', order.company?.name, 'col-md-2'],
                ['نوع حواله', order.is_official ? 'رسمی' : 'غیررسمی', 'col-md-2'],
            ];
            document.getElementById('order-fields').innerHTML = orderFields.map(([label, val, col]) => `
                <div class="${col}">
                    <label class="form-label small text-muted mb-1">${label}</label>
                    <input type="text" class="form-control readonly-field" value="${val || '-'}" readonly>
                </div>
            `).join('');

            // --- آیتم‌ها ---
            let subtotal = 0;
            const tbody = document.getElementById('items-table');
            if (order.items?.length) {
                tbody.innerHTML = order.items.map((item, i) => {
                    const line = item.quantity * item.base_price;
                    subtotal += line;
                    const totalAmt = item.amount ? (item.quantity * item.amount) : 0;
                    return `<tr>
                        <td class="text-center">${i+1}</td>
                        <td>${item.product?.name || '-'}</td>
                        <td class="text-end">${Number(item.base_price).toLocaleString('fa-IR')}</td>
                        <td class="text-center">${item.packaging_type?.name || item.packagingType?.name || '-'}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-center">${item.amount || '-'}</td>
                        <td class="text-center">${item.unit?.name || '-'}</td>
                        <td class="text-center">${totalAmt ? Number(totalAmt).toLocaleString('fa-IR') : '-'}</td>
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
            const freightName = order.freight_type?.name || 'پس کرایه';

            document.getElementById('subtotal').textContent = Number(subtotal).toLocaleString('fa-IR');
            document.getElementById('tax-total').textContent = Number(tax).toLocaleString('fa-IR');
            document.getElementById('freight-label').textContent = freightName + ':';
            document.getElementById('freight-display').textContent = Number(freight).toLocaleString('fa-IR');
            document.getElementById('insurance-display').textContent = Number(insurance).toLocaleString('fa-IR');
            document.getElementById('grand-total').textContent = Number(grand).toLocaleString('fa-IR');

            // --- ستون کناری ---
            document.getElementById('r-freight-type').value = order.freight_type?.name || order.freightType?.name || '-';
            document.getElementById('r-freight-amount').value = freight ? Number(freight).toLocaleString('fa-IR') + ' ریال' : '-';
            document.getElementById('r-address').value = order.address?.full_address || '-';
            document.getElementById('r-contact').value = order.contact?.full_name
                ? order.contact.full_name + (order.contact.mobile ? ' — ' + order.contact.mobile : '')
                : '-';
            document.getElementById('r-payment-type').value = paymentTypeMap[order.payment_type] || '-';
            document.getElementById('r-payment-terms').value = order.payment_terms || '-';
            document.getElementById('r-notes').value = order.notes || '-';

            // --- Stepper ---
            buildStepper(approvals, order);

            // --- تاریخچه ---
            buildHistory(approvals);

            // --- دسترسی اقدام ---
            const isAdmin = myRoles.is_admin;
            const pendingApproval = approvals.find(a => a.status === 'pending');
            const canAct = pendingApproval && (isAdmin ||
                (myRoles.has_inbox && myRoles.steps?.some(s => s.id === pendingApproval.step_id)));

            if (canAct && order.status === 'pending') {
                currentApprovalId = pendingApproval.id;
                document.getElementById('action-section').style.display = 'block';
                document.getElementById('action-info').innerHTML = `
            <div class="alert alert-warning mb-0">
                <i class="bx bx-bell me-2"></i>
                نوبت شماست! مرحله: <strong>${pendingApproval.step?.name || '-'}</strong>
            </div>`;
            } else {
                const noAction = document.getElementById('no-action-section');
                noAction.style.removeProperty('display');
                const currentStep = approvals.find(a => a.status === 'pending');
                document.getElementById('no-action-text').textContent = currentStep
                    ? `این درخواست در مرحله «${currentStep.step?.name}» قرار دارد.`
                    : `وضعیت: ${statusLabels[order.status] || order.status}`;
            }

            // نمایش صفحه
            document.getElementById('page-loading').style.display = 'none';
            document.getElementById('page-content').style.display = 'block';
        });

        function buildStepper(approvals, order) {
            const stepper = document.getElementById('workflow-stepper');
            if (!approvals.length) {
                stepper.innerHTML = '<p class="text-muted text-center w-100">مراحل گردش کار تعریف نشده</p>';
                return;
            }

            const firstDone = { name: 'ثبت سفارش', status: 'done', is_first: true };
            const lastItem  = { name: 'تکمیل', status: approvals.every(a => a.status === 'approved') ? 'done' : 'waiting', is_last: true };
            const steps = [firstDone, ...approvals, lastItem];

            let html = '';
            steps.forEach((step, i) => {
                let status;
                if (step.is_first) status = 'done';
                else if (step.is_last) status = approvals.every(a => a.status === 'approved') ? 'done' : 'waiting';
                else if (step.status === 'approved') status = 'done';
                else if (step.status === 'rejected') status = 'rejected';
                else {
                    const firstPending = approvals.findIndex(a => a.status === 'pending');
                    const myIdx = approvals.findIndex(a => a.id === step.id);
                    status = myIdx === firstPending ? 'current' : 'waiting';
                }

                const icon = status === 'done' ? '<i class="bx bx-check"></i>'
                    : status === 'rejected' ? '<i class="bx bx-x"></i>'
                        : status === 'current' ? '<i class="bx bx-time-five"></i>'
                            : (i);

                if (i > 0) html += `<div class="step-line ${status === 'done' || steps[i-1]?.status === 'approved' ? 'done' : ''}"></div>`;

                html += `<div class="step-item">
            <div class="step-circle ${status}">${icon}</div>
            <div class="step-label ${status}">${step.step?.name || step.name}</div>
        </div>`;
            });

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
        async function doAction(type) {
            if (!currentApprovalId) return;
            const desc = document.getElementById('action-description').value;
            const btnApprove = document.getElementById('btn-approve');
            const btnReject = document.getElementById('btn-reject');
            btnApprove.disabled = true;
            btnReject.disabled = true;

            const endpoint = type === 'approved'
                ? `/api/v1/workflow/approvals/${currentApprovalId}/approve`
                : `/api/v1/workflow/approvals/${currentApprovalId}/reject`;

            await apiCall(endpoint, { method: 'POST', body: JSON.stringify({ description: desc }) });
            showToast(type === 'approved' ? 'سفارش تایید شد' : 'سفارش رد شد',
                type === 'approved' ? 'success' : 'warning');
            setTimeout(() => window.location.href = '/inbox', 1200);
        }

        document.getElementById('btn-approve')?.addEventListener('click', () => doAction('approved'));
        document.getElementById('btn-reject')?.addEventListener('click', () => doAction('rejected'));
    </script>
@endpush
