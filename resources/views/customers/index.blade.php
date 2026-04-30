@extends('layouts.app')

@section('title', 'مشتریان | ERP System')
@section('page-title', 'لیست مشتریان')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">مشتریان</h4>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="search-input"
                                   placeholder="جستجو در نام مشتری..." style="width:250px;">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>نام</th>
                                <th>کد اقتصادی</th>
                                <th>شناسه ملی</th>
                                <th>اعتبار</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody id="customers-table">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="empty-message" class="text-center py-4" style="display:none;">
                        <i class="bx bxs-user-detail font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">هیچ مشتری یافت نشد</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal جزئیات مشتری -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جزئیات مشتری</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let allCustomers = [];
        let searchTimeout;

        function renderCustomers(customers) {
            const tbody = document.getElementById('customers-table');
            const empty = document.getElementById('empty-message');

            if (customers.length === 0) {
                tbody.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            tbody.innerHTML = customers.map(c => `
            <tr>
                <td>${c.id}</td>
                <td><strong>${c.name}</strong></td>
                <td>${c.economic_code || '-'}</td>
                <td>${c.national_id || '-'}</td>
                <td>${c.credit_limit ? Number(c.credit_limit).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>
                    ${c.status === 'active'
                ? '<span class="badge bg-success">فعال</span>'
                : '<span class="badge bg-danger">غیرفعال</span>'}
                </td>
                <td>
                    <button class="btn btn-sm btn-primary waves-effect"
                        onclick="showCustomer(${c.id})">
                        <i class="bx bx-show"></i> مشاهده
                    </button>
                </td>
            </tr>
        `).join('');
        }

        // بارگذاری مشتریان
        apiCall('/api/v1/customers').then(customers => {
            allCustomers = customers;
            renderCustomers(customers);
        });

        // جستجو
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();

            if (keyword.length === 0) {
                renderCustomers(allCustomers);
                return;
            }

            searchTimeout = setTimeout(() => {
                apiCall(`/api/v1/customers?search=${encodeURIComponent(keyword)}`)
                    .then(renderCustomers);
            }, 400);
        });

        // نمایش جزئیات مشتری
        function showCustomer(id) {
            const modal = new bootstrap.Modal(document.getElementById('customerModal'));
            document.getElementById('modal-body').innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status"></div>
            </div>`;
            modal.show();

            apiCall(`/api/v1/customers/${id}/addresses`).then(addresses => {
                if (addresses.length === 0) {
                    document.getElementById('modal-body').innerHTML =
                        '<p class="text-muted text-center">آدرسی ثبت نشده</p>';
                    return;
                }

                document.getElementById('modal-body').innerHTML = addresses.map(a => `
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title">${a.title || 'آدرس'} ${a.is_default ? '<span class="badge bg-primary">پیش‌فرض</span>' : ''}</h6>
                        <p class="text-muted mb-2">
                            <i class="bx bx-map me-1"></i>
                            ${a.province || ''} - ${a.city || ''} - ${a.full_address || ''}
                        </p>
                        ${a.contacts && a.contacts.length > 0 ? `
                            <h6 class="mt-3">گیرندگان:</h6>
                            <ul class="list-unstyled mb-0">
                                ${a.contacts.map(c => `
                                    <li class="mb-1">
                                        <i class="bx bx-user me-1"></i>
                                        ${c.full_name}
                                        ${c.mobile ? `- <i class="bx bx-phone me-1"></i>${c.mobile}` : ''}
                                    </li>
                                `).join('')}
                            </ul>
                        ` : ''}
                    </div>
                </div>
            `).join('');
            });
        }
    </script>
@endpush
