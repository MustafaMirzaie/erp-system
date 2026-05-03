@extends('layouts.app')
@section('title', 'صندوق دریافتی | ERP System')
@section('page-title', 'صندوق دریافتی')

@section('content')
    <div class="row">
        <div class="col-12">

            <!-- اطلاعات نقش کاربر -->
            <div class="alert alert-info d-flex align-items-center" id="role-info" style="display:none!important;">
                <i class="bx bx-user-circle font-size-20 me-2"></i>
                <span id="role-info-text">در حال بارگذاری...</span>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- تب‌ها -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-tab="my-tasks">
                                <i class="bx bx-task me-1"></i>
                                نیازمند اقدام من
                                <span class="badge bg-danger ms-1" id="tab-badge-my-tasks"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="in-progress">
                                <i class="bx bx-time me-1"></i>
                                در حال بررسی
                                <span class="badge bg-warning ms-1" id="tab-badge-in-progress"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="completed">
                                <i class="bx bx-check-circle me-1"></i>
                                تاریخچه
                                <span class="badge bg-success ms-1" id="tab-badge-completed"></span>
                            </a>
                        </li>
                    </ul>

                    <!-- جدول -->
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شماره درخواست</th>
                                <th>مشتری</th>
                                <th>ثبت‌کننده</th>
                                <th>مبلغ کل</th>
                                <th>مرحله جاری</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody id="inbox-table">
                            <tr><td colspan="8" class="text-center py-4">
                                    <div class="spinner-border text-primary"></div>
                                </td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="empty-message" class="text-center py-5" style="display:none;">
                        <i class="bx bx-inbox font-size-48 text-muted"></i>
                        <p class="text-muted mt-2" id="empty-text">موردی یافت نشد</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const statusMap = {
            'pending':  '<span class="badge bg-warning">در انتظار</span>',
            'approved': '<span class="badge bg-success">تایید شده</span>',
            'rejected': '<span class="badge bg-danger">رد شده</span>',
        };

        let currentTab = 'my-tasks';
        const emptyMessages = {
            'my-tasks':    'هیچ درخواستی نیازمند اقدام شما نیست',
            'in-progress': 'هیچ درخواستی در حال بررسی نیست',
            'completed':   'تاریخچه‌ای موجود نیست',
        };

        // بارگذاری اطلاعات نقش
        apiCall('/api/v1/inbox/roles').then(data => {
            const info = document.getElementById('role-info');
            const text = document.getElementById('role-info-text');
            if (data.has_inbox) {
                text.innerHTML = `شما با نقش <strong>${data.role}</strong> در مرحله گردش کار قرار دارید.`;
                info.style.display = 'flex';
            } else {
                text.innerHTML = `نقش شما (<strong>${data.role}</strong>) در مراحل گردش کار تعریف نشده است.`;
                info.style.removeProperty('display');
                info.classList.remove('alert-info');
                info.classList.add('alert-warning');
            }
        });

        function loadTab(tab) {
            const tbody = document.getElementById('inbox-table');
            const empty = document.getElementById('empty-message');
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4">
        <div class="spinner-border text-primary spinner-border-sm"></div>
    </td></tr>`;
            empty.style.display = 'none';

            const endpoints = {
                'my-tasks':    '/api/v1/inbox/my-tasks',
                'in-progress': '/api/v1/inbox/in-progress',
                'completed':   '/api/v1/inbox/completed',
            };

            apiCall(endpoints[tab]).then(items => {
                // آپدیت badge
                const badge = document.getElementById(`tab-badge-${tab}`);
                if (badge) badge.textContent = items.length || '';

                if (!items.length) {
                    tbody.innerHTML = '';
                    document.getElementById('empty-text').textContent = emptyMessages[tab];
                    empty.style.display = 'block';
                    return;
                }

                tbody.innerHTML = items.map(item => `
            <tr>
                <td>
                    <a href="/inbox/order/${item.order_id}" class="text-body fw-bold">
                        ${item.order_number || '#' + item.order_id}
                    </a>
                </td>
                <td>${item.customer || '-'}</td>
                <td>${item.created_by || '-'}</td>
                <td>${item.total_price ? Number(item.total_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>
                    <span class="badge bg-info">${item.step_name || '-'}</span>
                </td>
                <td>${statusMap[item.status] || item.status}</td>
                <td>${item.created_at ? new Date(item.created_at).toLocaleDateString('fa-IR') : '-'}</td>
                <td>
                    <a href="/inbox/order/${item.order_id}" class="btn btn-sm btn-primary waves-effect">
                        <i class="bx bx-show me-1"></i> مشاهده
                    </a>
                </td>
            </tr>
        `).join('');
            });
        }

        // تب کلیک
        document.querySelectorAll('.nav-link[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link[data-tab]').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                currentTab = this.dataset.tab;
                loadTab(currentTab);
            });
        });

        // بارگذاری همه badge ها
        ['my-tasks', 'in-progress', 'completed'].forEach(tab => {
            const endpoints = {
                'my-tasks':    '/api/v1/inbox/my-tasks',
                'in-progress': '/api/v1/inbox/in-progress',
                'completed':   '/api/v1/inbox/completed',
            };
            apiCall(endpoints[tab]).then(items => {
                const badge = document.getElementById(`tab-badge-${tab}`);
                if (badge && items.length) badge.textContent = items.length;
            });
        });

        loadTab('my-tasks');
    </script>
@endpush
