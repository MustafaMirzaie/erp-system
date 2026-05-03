@extends('layouts.app')
@section('title', 'مدیریت آدرس‌ها | ERP System')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/customers">مشتریان</a></li>
    <li class="breadcrumb-item active">مدیریت آدرس</li>
@endsection
@section('page-title', 'مدیریت آدرس و گیرندگان')

@section('content')
    <div class="row">
        <!-- اطلاعات مشتری -->
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body" id="customer-info">
                    <div class="text-center py-2">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- آدرس‌ها -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">آدرس‌ها و گیرندگان</h4>
                        <a href="/customers/{{ $customerId }}/addresses/create"
                           class="btn btn-primary btn-sm waves-effect">
                            <i class="bx bx-plus me-1"></i> آدرس جدید
                        </a>
                    </div>

                    <div id="addresses-container">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش آدرس -->
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش آدرس</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-address-id">
                    <div class="mb-3">
                        <label class="form-label">عنوان</label>
                        <input type="text" class="form-control" id="edit-title">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">استان</label>
                            <select class="form-select" id="edit-province">
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">شهرستان</label>
                            <select class="form-select" id="edit-city" disabled>
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">آدرس کامل</label>
                        <textarea class="form-control" id="edit-full-address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" id="save-address-btn">ذخیره</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش گیرنده -->
    <div class="modal fade" id="editContactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش گیرنده</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-contact-id">
                    <div class="mb-3">
                        <label class="form-label">نام و نام خانوادگی</label>
                        <input type="text" class="form-control" id="edit-contact-name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">موبایل</label>
                        <input type="text" class="form-control" id="edit-contact-mobile">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تلفن</label>
                        <input type="text" class="form-control" id="edit-contact-phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" id="save-contact-btn">ذخیره</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const customerId = {{ $customerId }};
        let provinces = [];

        // بارگذاری اطلاعات مشتری
        apiCall(`/api/v1/customers/${customerId}`).then(customer => {
            document.getElementById('customer-info').innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <div class="avatar-sm">
                    <span class="avatar-title rounded-circle bg-primary font-size-16">
                        ${customer.name?.charAt(0) || '?'}
                    </span>
                </div>
            </div>
            <div class="col">
                <h5 class="mb-1">${customer.name}</h5>
                <div class="text-muted small d-flex gap-3">
                    ${customer.economic_code ? `<span>کد اقتصادی: ${customer.economic_code}</span>` : ''}
                    ${customer.national_id ? `<span>شناسه ملی: ${customer.national_id}</span>` : ''}
                    ${customer.credit_limit ? `<span>سقف اعتبار: ${Number(customer.credit_limit).toLocaleString('fa-IR')} ریال</span>` : ''}
                    <span class="${customer.status === 'active' ? 'text-success' : 'text-danger'}">
                        ${customer.status === 'active' ? 'فعال' : 'غیرفعال'}
                    </span>
                </div>
            </div>
        </div>
    `;
        });

        // بارگذاری استان‌ها برای modal
        apiCall('/api/v1/provinces').then(data => {
            provinces = data;
            const sel = document.getElementById('edit-province');
            data.forEach(p => sel.innerHTML += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`);
        });

        document.getElementById('edit-province').addEventListener('change', function() {
            const citySel = document.getElementById('edit-city');
            citySel.innerHTML = '<option value="">-- انتخاب --</option>';
            citySel.disabled = true;
            const province = provinces.find(p => p.name === this.value);
            if (!province) return;
            apiCall(`/api/v1/provinces/${province.id}/cities`).then(cities => {
                cities.forEach(c => citySel.innerHTML += `<option value="${c.name}">${c.name}</option>`);
                citySel.disabled = false;
            });
        });

        // بارگذاری آدرس‌ها
        async function loadAddresses() {
            const addresses = await apiCall(`/api/v1/customers/${customerId}/addresses`);
            const container = document.getElementById('addresses-container');

            apiCall(`/api/v1/customers/${customerId}/addresses`).then(addresses => {
                const container = document.getElementById('addresses-container');

                if (!addresses.length) {
                    container.innerHTML = `
                <div class="text-center py-4">
                    <i class="bx bx-map font-size-48 text-muted"></i>
                    <p class="text-muted mt-2">هیچ آدرسی ثبت نشده</p>
                    <a href="/customers/${customerId}/addresses/create" class="btn btn-primary btn-sm">
                        افزودن اولین آدرس
                    </a>
                </div>`;
                    return;
                }

                container.innerHTML = addresses.map(addr => `
            <div class="border rounded mb-4 ${addr.is_active === false ? 'opacity-50' : ''}">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light rounded-top">
                    <div>
                        <h6 class="mb-0">
                            ${addr.title || 'آدرس'}
                            ${addr.is_default ? '<span class="badge bg-primary ms-2">پیش‌فرض</span>' : ''}
                            ${addr.is_active === false ? '<span class="badge bg-danger ms-2">غیرفعال</span>' : ''}
                        </h6>
                        <small class="text-muted">${addr.province || ''} — ${addr.city || ''}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="editAddress(${addr.id}, '${addr.title}', '${addr.province}', '${addr.city}', \`${addr.full_address}\`)">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm ${addr.is_active === false ? 'btn-outline-success' : 'btn-outline-danger'}"
                            onclick="toggleAddress(${addr.id})">
                            <i class="bx ${addr.is_active === false ? 'bx-toggle-left' : 'bx-toggle-right'}"></i>
                            ${addr.is_active === false ? 'فعال‌سازی' : 'غیرفعال'}
                        </button>
                    </div>
                </div>

                <div class="p-3">
                    <p class="text-muted small mb-3">
                        <i class="bx bx-map-pin me-1"></i>
                        ${addr.full_address || '-'}
                    </p>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead class="table-light">
                                <tr>
                                    <th>نام گیرنده</th>
                                    <th>موبایل</th>
                                    <th>تلفن</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${(addr.contacts || []).map(c => `
                                    <tr class="${c.is_active === false ? 'text-muted' : ''}">
                                        <td>${c.full_name}</td>
                                        <td>${c.mobile || '-'}</td>
                                        <td>${c.phone || '-'}</td>
                                        <td>${c.is_active === false
                    ? '<span class="badge bg-danger">غیرفعال</span>'
                    : '<span class="badge bg-success">فعال</span>'}</td>
                                        <td>
                                            <button class="btn btn-xs btn-outline-primary me-1"
                                                onclick="editContact(${c.id}, '${c.full_name}', '${c.mobile || ''}', '${c.phone || ''}')">
                                                <i class="bx bx-edit" style="font-size:12px;"></i>
                                            </button>
                                            <button class="btn btn-xs ${c.is_active === false ? 'btn-outline-success' : 'btn-outline-danger'}"
                                                onclick="toggleContact(${c.id})">
                                                <i class="bx ${c.is_active === false ? 'bx-check' : 'bx-x'}" style="font-size:12px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `).join('') || '<tr><td colspan="5" class="text-center text-muted">گیرنده‌ای ثبت نشده</td></tr>'}
                            </tbody>
                        </table>
                    </div>

                    <button class="btn btn-sm btn-outline-primary"
                        onclick="addContactToAddress(${addr.id})">
                        <i class="bx bx-plus me-1"></i> افزودن گیرنده
                    </button>
                </div>
            </div>
        `).join('');
            });
        }

        loadAddresses();

        // ویرایش آدرس
        function editAddress(id, title, province, city, fullAddress) {
            document.getElementById('edit-address-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-full-address').value = fullAddress;

            const provinceSel = document.getElementById('edit-province');
            provinceSel.value = province;

            // بارگذاری شهرستان‌ها
            const targetProvince = provinces.find(p => p.name === province);
            if (targetProvince) {
                apiCall(`/api/v1/provinces/${targetProvince.id}/cities`).then(cities => {
                    const citySel = document.getElementById('edit-city');
                    citySel.innerHTML = '<option value="">-- انتخاب --</option>';
                    if (Array.isArray(cities)) {
                        cities.forEach(c => citySel.innerHTML += `<option value="${c.name}">${c.name}</option>`);
                        citySel.value = city;
                        citySel.disabled = false;
                    }
                });
            }

            new bootstrap.Modal(document.getElementById('editAddressModal')).show();
        }

        document.getElementById('save-address-btn').addEventListener('click', async function() {
            const id = document.getElementById('edit-address-id').value;
            await apiCall(`/api/v1/customers/addresses/${id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    title:        document.getElementById('edit-title').value,
                    province:     document.getElementById('edit-province').value,
                    city:         document.getElementById('edit-city').value,
                    full_address: document.getElementById('edit-full-address').value,
                })
            });
            bootstrap.Modal.getInstance(document.getElementById('editAddressModal')).hide();
            showToast('آدرس ویرایش شد', 'success');
            loadAddresses();
        });

        // غیرفعال/فعال آدرس
        async function toggleAddress(id) {
            await apiCall(`/api/v1/customers/addresses/${id}/toggle`, { method: 'PATCH' });
            showToast('وضعیت آدرس تغییر کرد', 'info');
            await loadAddresses(); // await اضافه شد
        }

        async function toggleContact(id) {
            await apiCall(`/api/v1/customers/contacts/${id}/toggle`, { method: 'PATCH' });
            showToast('وضعیت گیرنده تغییر کرد', 'info');
            await loadAddresses(); // await اضافه شد
        }

        // ویرایش گیرنده
        function editContact(id, name, mobile, phone) {
            document.getElementById('edit-contact-id').value = id;
            document.getElementById('edit-contact-name').value = name;
            document.getElementById('edit-contact-mobile').value = mobile;
            document.getElementById('edit-contact-phone').value = phone;
            new bootstrap.Modal(document.getElementById('editContactModal')).show();
        }

        document.getElementById('save-contact-btn').addEventListener('click', async function() {
            const id = document.getElementById('edit-contact-id').value;
            await apiCall(`/api/v1/customers/contacts/${id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    full_name: document.getElementById('edit-contact-name').value,
                    mobile:    document.getElementById('edit-contact-mobile').value,
                    phone:     document.getElementById('edit-contact-phone').value,
                })
            });
            bootstrap.Modal.getInstance(document.getElementById('editContactModal')).hide();
            showToast('گیرنده ویرایش شد', 'success');
            loadAddresses();
        });

        // غیرفعال/فعال گیرنده
        async function toggleContact(id) {
            await apiCall(`/api/v1/customers/contacts/${id}/toggle`, { method: 'PATCH' });
            showToast('وضعیت گیرنده تغییر کرد', 'info');
            loadAddresses();
        }

        // افزودن گیرنده به آدرس موجود
        async function addContactToAddress(addressId) {
            const name = prompt('نام و نام خانوادگی گیرنده:');
            if (!name) return;
            const mobile = prompt('شماره موبایل (اختیاری):') || null;
            await apiCall(`/api/v1/customers/addresses/${addressId}/contacts`, {
                method: 'POST',
                body: JSON.stringify({ full_name: name, mobile })
            });
            showToast('گیرنده افزوده شد', 'success');
            loadAddresses();
        }
    </script>
@endpush
