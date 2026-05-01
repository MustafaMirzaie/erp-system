@extends('layouts.app')

@section('title', 'گردش کار | ERP System')
@section('page-title', 'مدیریت گردش کار')

@section('content')
    <div class="row">
        <!-- مراحل workflow -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">مراحل تایید</h4>
                    <div id="steps-list">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- سفارش‌های در انتظار تایید -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">سفارش‌های در انتظار تایید</h4>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>مشتری</th>
                                <th>مبلغ</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody id="pending-orders">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تایید/رد -->
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">تایید سفارش</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="approvals-detail" class="mb-3"></div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات (اختیاری)</label>
                        <textarea class="form-control" id="approval-description" rows="3"
                                  placeholder="توضیحات خود را وارد کنید..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-success" id="approve-btn">
                        <i class="bx bx-check me-1"></i> تایید
                    </button>
                    <button type="button" class="btn btn-danger" id="reject-btn">
                        <i class="bx bx-x me-1"></i> رد
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentApprovalId = null;

        // بارگذاری مراحل
        apiCall('/api/v1/workflow/steps').then(steps => {
            const list = document.getElementById('steps-list');
            if (steps.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">مرحله‌ای تعریف نشده</p>';
                return;
            }
            list.innerHTML = `
            <ul class="verti-timeline list-unstyled">
                ${steps.map((s, i) => `
                    <li class="event-list">
                        <div class="event-timeline-dot">
                            <i class="bx bx-right-arrow-circle font-size-18 text-primary"></i>
                        </div>
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">مرحله ${s.step_order}: ${s.name}</h6>
                                <p class="text-muted small mb-0">نقش: ${s.role?.name || '-'}</p>
                            </div>
                        </div>
                    </li>
                `).join('')}
            </ul>
        `;
        });

        // بارگذاری سفارش‌های pending
        apiCall('/api/v1/orders').then(orders => {
            const pending = orders.filter(o => o.status === 'pending');
            const tbody = document.getElementById('pending-orders');

            if (pending.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3">سفارشی در انتظار تایید نیست</td></tr>';
                return;
            }

            tbody.innerHTML = pending.map(o => `
            <tr>
                <td><a href="/orders/${o.id}" class="text-body fw-bold">#${o.id}</a></td>
                <td>${o.customer?.name || '-'}</td>
                <td>${o.total_price ? Number(o.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>${o.created_at ? new Date(o.created_at).toLocaleDateString('fa-IR') : '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning waves-effect"
                        onclick="openApproval(${o.id})">
                        <i class="bx bx-check-shield me-1"></i> بررسی
                    </button>
                </td>
            </tr>
        `).join('');
        });

        function openApproval(orderId) {
            const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
            document.getElementById('approval-description').value = '';
            document.getElementById('approvals-detail').innerHTML = `
            <div class="text-center py-2">
                <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
            </div>`;
            modal.show();

            apiCall(`/api/v1/workflow/orders/${orderId}/approvals`).then(approvals => {
                const pending = approvals.filter(a => a.status === 'pending');
                if (pending.length === 0) {
                    document.getElementById('approvals-detail').innerHTML =
                        '<div class="alert alert-info">مرحله‌ای در انتظار تایید نیست</div>';
                    document.getElementById('approve-btn').disabled = true;
                    document.getElementById('reject-btn').disabled = true;
                    return;
                }

                currentApprovalId = pending[0].id;
                document.getElementById('modal-title').textContent =
                    'بررسی: ' + (pending[0].step?.name || 'مرحله');
                document.getElementById('approvals-detail').innerHTML =
                    `<div class="alert alert-warning">مرحله در انتظار: <strong>${pending[0].step?.name || '-'}</strong></div>`;
            });
        }

        // تایید
        document.getElementById('approve-btn').addEventListener('click', function() {
            if (!currentApprovalId) return;
            const description = document.getElementById('approval-description').value;
            this.disabled = true;

            apiCall(`/api/v1/workflow/approvals/${currentApprovalId}/approve`, {
                method: 'POST',
                body: JSON.stringify({ description })
            }).then(data => {
                bootstrap.Modal.getInstance(document.getElementById('approvalModal')).hide();
                showToast('سفارش با موفقیت تایید شد', 'success');
                setTimeout(() => location.reload(), 1500);
            }).catch(() => {
                showToast('خطا در تایید سفارش', 'error');
                this.disabled = false;
            });
        });

        // رد
        document.getElementById('reject-btn').addEventListener('click', function() {
            if (!currentApprovalId) return;
            const description = document.getElementById('approval-description').value;
            this.disabled = true;

            apiCall(`/api/v1/workflow/approvals/${currentApprovalId}/reject`, {
                method: 'POST',
                body: JSON.stringify({ description })
            }).then(data => {
                bootstrap.Modal.getInstance(document.getElementById('approvalModal')).hide();
                showToast('سفارش رد شد', 'warning');
                setTimeout(() => location.reload(), 1500);
            }).catch(() => {
                showToast('خطا در رد سفارش', 'error');
                this.disabled = false;
            });
        });
    </script>
@endpush
