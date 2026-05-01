@extends('layouts.app')

@section('title', 'سفارش جدید | ERP System')
@section('page-title', 'ثبت سفارش جدید')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">فرم ثبت سفارش</h4>

                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">مشتری <span class="text-danger">*</span></label>
                        <select class="form-select" id="customer-select">
                            <option value="">-- انتخاب مشتری --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">شرکت <span class="text-danger">*</span></label>
                        <select class="form-select" id="company-select">
                            <option value="">-- انتخاب شرکت --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">آدرس تحویل <span class="text-danger">*</span></label>
                        <select class="form-select" id="address-select" disabled>
                            <option value="">-- ابتدا مشتری انتخاب کنید --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">گیرنده <span class="text-danger">*</span></label>
                        <select class="form-select" id="contact-select" disabled>
                            <option value="">-- ابتدا آدرس انتخاب کنید --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نوع سفارش</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is-official" checked>
                            <label class="form-check-label" for="is-official">سفارش رسمی</label>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">آیتم‌های سفارش</h5>
                    <div id="items-container">
                        <div class="item-row row g-2 mb-2" data-index="0">
                            <div class="col-md-5">
                                <select class="form-select product-select">
                                    <option value="">-- انتخاب محصول --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control quantity-input" placeholder="تعداد" min="1" value="1">
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control price-input" placeholder="قیمت (ریال)" min="0">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-item-btn">
                        <i class="bx bx-plus me-1"></i> افزودن آیتم
                    </button>

                    <hr>

                    <div class="text-end mb-3">
                        <h5>مجموع کل: <span id="total-display" class="text-primary">0 ریال</span></h5>
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> ثبت سفارش</span>
                            <span class="loading-text" style="display:none;">در حال ثبت...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let products = [];
        let addresses = [];

        // بارگذاری مشتریان
        apiCall('/api/v1/customers').then(customers => {
            const sel = document.getElementById('customer-select');
            customers.forEach(c => {
                sel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
        });

        // بارگذاری شرکت‌ها از API
        apiCall('/api/v1/companies').then(companies => {
            const sel = document.getElementById('company-select');
            companies.forEach(c => {
                sel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
        });

        // بارگذاری محصولات
        apiCall('/api/v1/products').then(prods => {
            products = prods;
            updateProductSelects();
        });

        function updateProductSelects() {
            document.querySelectorAll('.product-select').forEach(sel => {
                const currentVal = sel.value;
                sel.innerHTML = '<option value="">-- انتخاب محصول --</option>';
                products.forEach(p => {
                    sel.innerHTML += `<option value="${p.id}" data-price="${p.base_price}">${p.name}</option>`;
                });
                sel.value = currentVal;
            });
        }

        // انتخاب مشتری → بارگذاری آدرس‌ها
        document.getElementById('customer-select').addEventListener('change', function() {
            const customerId = this.value;
            const addressSel = document.getElementById('address-select');
            const contactSel = document.getElementById('contact-select');

            addressSel.innerHTML = '<option value="">در حال بارگذاری...</option>';
            addressSel.disabled = true;
            contactSel.innerHTML = '<option value="">-- ابتدا آدرس انتخاب کنید --</option>';
            contactSel.disabled = true;

            if (!customerId) {
                addressSel.innerHTML = '<option value="">-- ابتدا مشتری انتخاب کنید --</option>';
                return;
            }

            apiCall(`/api/v1/customers/${customerId}/addresses`).then(addrs => {
                addresses = addrs;
                addressSel.innerHTML = '<option value="">-- انتخاب آدرس --</option>';
                addrs.forEach(a => {
                    addressSel.innerHTML += `<option value="${a.id}">${a.title || a.full_address}</option>`;
                });
                addressSel.disabled = false;
            });
        });

        // انتخاب آدرس → بارگذاری گیرنده‌ها
        document.getElementById('address-select').addEventListener('change', function() {
            const addressId = parseInt(this.value);
            const contactSel = document.getElementById('contact-select');

            contactSel.innerHTML = '<option value="">-- انتخاب گیرنده --</option>';
            contactSel.disabled = true;

            if (!addressId) return;

            const address = addresses.find(a => a.id === addressId);
            if (address && address.contacts && address.contacts.length > 0) {
                address.contacts.forEach(c => {
                    contactSel.innerHTML += `<option value="${c.id}">${c.full_name}${c.mobile ? ' - ' + c.mobile : ''}</option>`;
                });
                contactSel.disabled = false;
            } else {
                contactSel.innerHTML = '<option value="">گیرنده‌ای یافت نشد</option>';
            }
        });

        // افزودن آیتم
        let itemIndex = 1;
        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const div = document.createElement('div');
            div.className = 'item-row row g-2 mb-2';
            div.dataset.index = itemIndex++;
            div.innerHTML = `
            <div class="col-md-5">
                <select class="form-select product-select">
                    <option value="">-- انتخاب محصول --</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.base_price}">${p.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control quantity-input" placeholder="تعداد" min="1" value="1">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control price-input" placeholder="قیمت (ریال)" min="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
            container.appendChild(div);
            bindItemEvents(div);
            updateTotal();
        });

        function bindItemEvents(row) {
            row.querySelector('.product-select').addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                const price = opt.dataset.price;
                if (price) row.querySelector('.price-input').value = price;
                updateTotal();
            });
            row.querySelector('.quantity-input').addEventListener('input', updateTotal);
            row.querySelector('.price-input').addEventListener('input', updateTotal);
            row.querySelector('.remove-item')?.addEventListener('click', function() {
                row.remove();
                updateTotal();
            });
        }

        bindItemEvents(document.querySelector('.item-row'));

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                total += qty * price;
            });
            document.getElementById('total-display').textContent =
                total.toLocaleString('fa-IR') + ' ریال';
        }

        // ثبت سفارش
        document.getElementById('submit-btn').addEventListener('click', function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const customerId = document.getElementById('customer-select').value;
            const companyId  = document.getElementById('company-select').value;
            const addressId  = document.getElementById('address-select').value;
            const contactId  = document.getElementById('contact-select').value;
            const isOfficial = document.getElementById('is-official').checked;

            if (!customerId || !companyId || !addressId || !contactId) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً تمام فیلدها را پر کنید.</div>';
                return;
            }

            const items = [];
            let hasError = false;
            document.querySelectorAll('.item-row').forEach(row => {
                const productId = row.querySelector('.product-select').value;
                const quantity  = parseInt(row.querySelector('.quantity-input').value);
                const price     = parseFloat(row.querySelector('.price-input').value);
                if (!productId || !quantity || !price) { hasError = true; return; }
                items.push({ product_id: parseInt(productId), quantity, price });
            });

            if (hasError || items.length === 0) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً آیتم‌های سفارش را کامل کنید.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            apiCall('/api/v1/orders', {
                method: 'POST',
                body: JSON.stringify({
                    customer_id: parseInt(customerId),
                    company_id:  parseInt(companyId),
                    address_id:  parseInt(addressId),
                    contact_id:  parseInt(contactId),
                    is_official: isOfficial,
                    items
                })
            }).then(data => {
                if (data.id) {
                    showToast('سفارش با موفقیت ثبت شد', 'success');
                    setTimeout(() => window.location.href = '/orders/' + data.id, 1000);
                } else {
                    alertBox.style.display = 'block';
                    alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'خطا در ثبت سفارش'}</div>`;
                    btn.disabled = false;
                    btn.querySelector('.normal-text').style.display = 'inline';
                    btn.querySelector('.loading-text').style.display = 'none';
                }
            });
        });
    </script>
@endpush
