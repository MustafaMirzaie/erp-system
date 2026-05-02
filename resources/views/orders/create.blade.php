@extends('layouts.app')
@section('title', 'سفارش جدید | ERP System')
@section('page-title', 'ثبت سفارش جدید')

@section('content')
    <style>
        .item-row td { padding: 4px 6px; vertical-align: middle; }
        .item-row input, .item-row select { font-size: 13px; padding: 4px 6px; height: 32px; }
        .section-card { border-right: 3px solid #556ee6; }
        .remove-item-btn { width: 28px; height: 28px; padding: 0; line-height: 1; font-size: 12px; }
    </style>

    <div class="row g-3">

        {{-- ستون اصلی --}}
        <div class="col-xl-9">

            {{-- بخش اطلاعات اصلی --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">اطلاعات سفارش</h5>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="row g-3">
                        {{-- شماره سفارش --}}
                        <div class="col-md-1">
                            <label class="form-label">شماره سفارش</label>
                            <input type="text" class="form-control form-control-sm" id="order_number" disabled
                                   placeholder="اختیاری">
                        </div>

                        {{-- تاریخ صدور --}}
                        <div class="col-md-2">
                            <label class="form-label">تاریخ صدور</label>
                            <input type="text" class="form-control form-control-sm" id="issue_date"
                                   value="{{ now()->format('Y/m/d') }}" disabled
                                   style="background:#f8f9fa;">
                        </div>

                        {{-- شرکت --}}
                        <div class="col-md-2">
                            <label class="form-label">شرکت <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="company-select">
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>

                        {{-- مشتری --}}
                        <div class="col-md-3">
                            <label class="form-label">نام مشتری <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="customer-select">
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>

                        {{-- تاریخ ارسال --}}
                        <div class="col-md-2">
                            <label class="form-label">تاریخ ارسال</label>
                            <input type="date" class="form-control form-control-sm" id="send_date">
                        </div>

                        {{-- نوع حواله --}}
                        <div class="col-md-2">
                            <label class="form-label">نوع حواله</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_official" id="official-yes" value="1" checked>
                                    <label class="form-check-label" for="official-yes">رسمی</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_official" id="official-no" value="0">
                                    <label class="form-check-label" for="official-no">غیررسمی</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- کارشناسان فروش --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h5 class="card-title mb-3">کارشناسان فروش</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-sale-btn">
                            <i class="bx bx-plus"></i> افزودن کارشناس
                        </button>
                    </div>
                    <div id="sales-container" class="border-bottom pb-2"></div>
                    <div id="sales-total" class="text-end small pt-2" style="display:none;"></div>
                </div>
            </div>

            {{-- جدول آیتم‌های سفارش --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">آیتم‌های سفارش</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-item-btn">
                            <i class="bx bx-plus"></i> افزودن ردیف
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0" id="items-table">
                            <thead class="table-light text-center" style="font-size:12px;">
                            <tr>
                                <th style="width:30px;">ردیف</th>
                                <th>شرح کالا</th>
                                <th style="width:120px;">قیمت واحد</th>
                                <th style="width:120px;">نوع بسته‌بندی</th>
                                <th style="width:120px;">تعداد</th>
                                <th style="width:120px;">مقدار</th>
                                <th style="width:120px;">واحد</th>
                                <th style="width:120px;">مقدار کل</th>
                                <th style="width:120px;">مبلغ (ریال)</th>
                                <th style="width:30px;"></th>
                            </tr>
                            </thead>
                            <tbody id="items-body">
                            </tbody>

                            <tfoot class="table-light" style="font-size:13px;">
                            <tr>
                                <td colspan="8" class="text-end fw-bold">جمع جزء:</td>
                                <td class="text-center fw-bold" id="subtotal">0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">ارزش افزوده (10%):</td>
                                <td class="text-center" id="tax-total">0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-end">پس کرایه:</td>
                                <td class="text-center" id="freight-display">0</td>
                                <td></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="8" class="text-end fw-bold">جمع کل:</td>
                                <td class="text-center fw-bold" id="grand-total">0</td>
                                <td></td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ستون کناری --}}
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">توضیحات و ملاحظات</h5>
                    <div class="row g-3">
                        {{-- نوع کرایه --}}
                        <div class="col-md-6">
                            <label class="form-label">نوع کرایه</label>
                            <select class="form-select form-select-sm" id="freight-type-select">
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>

                        {{-- مبلغ کرایه --}}
                        <div class="col-md-6">
                            <label class="form-label">مبلغ کرایه (ریال)</label>
                            <input type="number" class="form-control form-control-sm" id="freight_amount" value="0" min="0">
                        </div>
                    </div>

                    {{-- آدرس --}}
                    <div class="mb-3">
                        <label class="form-label">آدرس تحویل <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="address-select" disabled>
                            <option value="">-- ابتدا مشتری انتخاب کنید --</option>
                        </select>
                    </div>

                    {{-- گیرنده --}}
                    <div class="mb-3">
                        <label class="form-label">گیرنده <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="contact-select" disabled>
                            <option value="">-- ابتدا آدرس انتخاب کنید --</option>
                        </select>
                    </div>

                    {{-- شرایط پرداخت --}}
                    <div class="mb-3">
                        <label class="form-label small">شرایط پرداخت تکمیلی</label>
                        <textarea class="form-control form-control-sm" id="payment_terms" rows="3"
                                  placeholder="توضیحات شرایط پرداخت..."></textarea>
                    </div>

                    {{-- سایر ملاحظات --}}
                    <div class="mb-3">
                        <label class="form-label small">سایر ملاحظات</label>
                        <textarea class="form-control form-control-sm" id="notes" rows="5"
                                  placeholder="آدرس حمل، شرایط تحویل، سایر ملاحظات..."></textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">خلاصه مالی</h6>
                    <table class="table table-sm mb-0">
                        <tr><td>جمع جزء:</td><td class="text-end" id="summary-subtotal">0 ریال</td></tr>
                        <tr><td>ارزش افزوده:</td><td class="text-end" id="summary-tax">0 ریال</td></tr>
                        <tr><td>کرایه:</td><td class="text-end" id="summary-freight">0 ریال</td></tr>
                        <tr class="table-primary fw-bold">
                            <td>جمع کل:</td>
                            <td class="text-end" id="summary-total">0 ریال</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- دکمه ثبت --}}
            <div class="d-grid mb-3">
                <button type="button" class="btn btn-primary btn-lg waves-effect" id="submit-btn">
                    <span class="normal-text"><i class="bx bx-save me-1"></i> ثبت سفارش</span>
                    <span class="loading-text" style="display:none;">در حال ثبت...</span>
                </button>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let products = [];
        let addresses = [];
        let users = [];
        let packagingTypes = [];
        let units = [];
        let itemIndex = 0;
        let saleIndex = 0;

        // بارگذاری اولیه
        Promise.all([
            apiCall('/api/v1/customers'),
            apiCall('/api/v1/companies'),
            apiCall('/api/v1/products'),
            apiCall('/api/v1/users'),
            apiCall('/api/v1/lookup/packaging-types'),
            apiCall('/api/v1/lookup/units'),
            apiCall('/api/v1/lookup/freight-types'),
        ]).then(([customers, companies, prods, usrs, pkgs, unts, freights]) => {
            products = prods;
            users = usrs;
            packagingTypes = pkgs;
            units = unts;

            // مشتریان
            const custSel = document.getElementById('customer-select');
            customers.forEach(c => custSel.innerHTML += `<option value="${c.id}">${c.name}</option>`);

            // شرکت‌ها
            const compSel = document.getElementById('company-select');
            companies.forEach(c => compSel.innerHTML += `<option value="${c.id}">${c.name}</option>`);

            // نوع کرایه
            const freightSel = document.getElementById('freight-type-select');
            freights.forEach(f => freightSel.innerHTML += `<option value="${f.id}">${f.name}</option>`);

            // اضافه کردن اولین ردیف
            addItem();
        });

        // انتخاب مشتری → آدرس‌ها
        document.getElementById('customer-select').addEventListener('change', function() {
            const id = this.value;
            const addrSel = document.getElementById('address-select');
            const contSel = document.getElementById('contact-select');
            addrSel.innerHTML = '<option value="">در حال بارگذاری...</option>';
            addrSel.disabled = true;
            contSel.innerHTML = '<option value="">-- ابتدا آدرس انتخاب کنید --</option>';
            contSel.disabled = true;

            if (!id) { addrSel.innerHTML = '<option value="">-- ابتدا مشتری انتخاب کنید --</option>'; return; }

            apiCall(`/api/v1/customers/${id}/addresses`).then(addrs => {
                addresses = addrs;
                addrSel.innerHTML = '<option value="">-- انتخاب آدرس --</option>';
                addrs.forEach(a => addrSel.innerHTML += `<option value="${a.id}">${a.title || a.full_address}</option>`);
                addrSel.disabled = false;
            });
        });

        // انتخاب آدرس → گیرنده‌ها
        document.getElementById('address-select').addEventListener('change', function() {
            const id = parseInt(this.value);
            const contSel = document.getElementById('contact-select');
            contSel.innerHTML = '<option value="">-- انتخاب گیرنده --</option>';
            contSel.disabled = true;
            if (!id) return;
            const addr = addresses.find(a => a.id === id);
            if (addr?.contacts?.length) {
                addr.contacts.forEach(c => contSel.innerHTML += `<option value="${c.id}">${c.full_name}${c.mobile ? ' - ' + c.mobile : ''}</option>`);
                contSel.disabled = false;
            }
        });

        // آپدیت کرایه در جمع‌ها
        document.getElementById('freight_amount').addEventListener('input', updateTotals);

        // ساخت ردیف آیتم
        function addItem() {
            const idx = itemIndex++;
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.dataset.idx = idx;
            row.innerHTML = `
        <td class="text-center text-muted small">${idx + 1}</td>
        <td>
            <select class="form-select form-select-sm product-select">
                <option value="">-- انتخاب --</option>
                ${products.map(p => `<option value="${p.id}" data-price="${p.base_price}">${p.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" class="form-control form-control-sm price-input text-end" min="0" placeholder="0"></td>
        <td>
            <select class="form-select form-select-sm packaging-select">
                <option value="">-</option>
                ${packagingTypes.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" class="form-control form-control-sm quantity-input text-center" min="1" value="1"></td>
        <td><input type="number" class="form-control form-control-sm amount-input text-center" min="0" placeholder="0"></td>
        <td>
            <select class="form-select form-select-sm unit-select">
                <option value="">-</option>
                ${units.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="text" class="form-control form-control-sm total-amount text-center" readonly style="background:#f8f9fa;"></td>
        <td><input type="text" class="form-control form-control-sm line-total text-end fw-bold" readonly style="background:#f8f9fa;"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn" title="حذف">
                <i class="bx bx-x"></i>
            </button>
        </td>
    `;
            document.getElementById('items-body').appendChild(row);
            bindRowEvents(row);
            updateTotals();
        }

        function bindRowEvents(row) {
            row.querySelector('.product-select').addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                const price = opt.dataset.price || 0;
                row.querySelector('.price-input').value = price;
                calcRow(row);
            });
            row.querySelector('.price-input').addEventListener('input', () => calcRow(row));
            row.querySelector('.quantity-input').addEventListener('input', () => calcRow(row));
            row.querySelector('.amount-input').addEventListener('input', () => calcRow(row));
            row.querySelector('.remove-item-btn').addEventListener('click', () => {
                row.remove();
                renumberRows();
                updateTotals();
            });
        }

        function calcRow(row) {
            const qty   = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const amt   = parseFloat(row.querySelector('.amount-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const totalAmt = qty * amt;
            const lineTotal = qty * price;
            row.querySelector('.total-amount').value = totalAmt ? totalAmt.toLocaleString('fa-IR') : '';
            row.querySelector('.line-total').value   = lineTotal ? lineTotal.toLocaleString('fa-IR') : '';
            updateTotals();
        }

        function renumberRows() {
            document.querySelectorAll('#items-body .item-row').forEach((row, i) => {
                row.querySelector('td:first-child').textContent = i + 1;
            });
        }

        function updateTotals() {
            let subtotal = 0;
            document.querySelectorAll('#items-body .item-row').forEach(row => {
                const qty   = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += qty * price;
            });

            const isOfficial = document.querySelector('input[name="is_official"]:checked')?.value === '1';
            const tax        = isOfficial ? subtotal * 0.1 : 0;
            const freight    = parseFloat(document.getElementById('freight_amount').value) || 0;
            const grand      = subtotal + tax + freight;

            const fmt = n => n ? Number(n).toLocaleString('fa-IR') : '0';

            document.getElementById('subtotal').textContent       = fmt(subtotal);
            document.getElementById('tax-total').textContent      = fmt(tax);
            document.getElementById('freight-display').textContent= fmt(freight);
            document.getElementById('grand-total').textContent    = fmt(grand);
            document.getElementById('summary-subtotal').textContent = fmt(subtotal) + ' ریال';
            document.getElementById('summary-tax').textContent    = fmt(tax) + ' ریال';
            document.getElementById('summary-freight').textContent= fmt(freight) + ' ریال';
            document.getElementById('summary-total').textContent  = fmt(grand) + ' ریال';
        }

        // آپدیت ارزش افزوده با تغییر نوع حواله
        document.querySelectorAll('input[name="is_official"]').forEach(r => r.addEventListener('change', updateTotals));

        document.getElementById('add-item-btn').addEventListener('click', addItem);

        // کارشناسان فروش
        document.getElementById('add-sale-btn').addEventListener('click', function() {
            const container = document.getElementById('sales-container');
            if (container.querySelectorAll('.sale-row').length >= 3) {
                showToast('حداکثر 3 کارشناس فروش می‌توانید انتخاب کنید', 'warning');
                return;
            }

            const div = document.createElement('div');
            div.className = 'sale-row row g-2 mb-2 align-items-center';
            div.innerHTML = `
        <div class="col-3">
            <select class="form-select form-select-sm user-select">
                <option value="">-- انتخاب کارشناس --</option>
                ${users.map(u => `<option value="${u.id}">${u.full_name}</option>`).join('')}
            </select>
        </div>
        <div class="col-1">
            <div class="input-group input-group-sm">
                <input type="number" class="form-control share-input" placeholder="0" min="1" max="100">
                <span class="input-group-text">%</span>
            </div>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm remove-sale" style="width:27px;height:27px;padding:0;">
                <i class="bx bx-x"></i>
            </button>
        </div>
    `;
            container.appendChild(div);
            div.querySelector('.share-input').addEventListener('input', updateSalesTotal);
            div.querySelector('.remove-sale').addEventListener('click', () => { div.remove(); updateSalesTotal(); });
            document.getElementById('sales-total').style.display = 'block';
            updateSalesTotal();
        });

        function updateSalesTotal() {
            let total = 0;
            document.querySelectorAll('.share-input').forEach(i => total += parseFloat(i.value) || 0);
            const el = document.getElementById('sales-total');
            const rows = document.querySelectorAll('.sale-row').length;
            if (!rows) { el.style.display = 'none'; return; }
            el.style.display = 'block';
            if (total > 100) {
                el.innerHTML = `<span class="text-danger fw-bold"><i class="bx bx-error me-1"></i>جمع: ${total}% (بیشتر از 100%)</span>`;
            } else if (total === 100) {
                el.innerHTML = `<span class="text-success fw-bold"><i class="bx bx-check me-1"></i>جمع: ${total}%</span>`;
            } else {
                el.innerHTML = `<span class="text-muted">جمع درصد مشارکت: ${total}%</span>`;
            }
        }

        // ثبت سفارش
        document.getElementById('submit-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const customerId = document.getElementById('customer-select').value;
            const companyId  = document.getElementById('company-select').value;
            const addressId  = document.getElementById('address-select').value;
            const contactId  = document.getElementById('contact-select').value;

            if (!customerId || !companyId || !addressId || !contactId) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً مشتری، شرکت، آدرس و گیرنده را انتخاب کنید.</div>';
                return;
            }

            const items = [];
            let hasError = false;
            document.querySelectorAll('#items-body .item-row').forEach(row => {
                const productId = row.querySelector('.product-select').value;
                const quantity  = parseFloat(row.querySelector('.quantity-input').value);
                const price     = parseFloat(row.querySelector('.price-input').value);
                const amount    = parseFloat(row.querySelector('.amount-input').value) || 0;
                const pkgId     = row.querySelector('.packaging-select').value;
                const unitId    = row.querySelector('.unit-select').value;
                if (!productId || !quantity || !price) { hasError = true; return; }
                items.push({
                    product_id:        parseInt(productId),
                    quantity,
                    price,
                    amount,
                    packaging_type_id: pkgId ? parseInt(pkgId) : null,
                    unit_id:           unitId ? parseInt(unitId) : null,
                });
            });

            if (hasError || items.length === 0) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً حداقل یک آیتم کامل وارد کنید.</div>';
                return;
            }

            // بررسی درصد
            let salesTotal = 0;
            document.querySelectorAll('.share-input').forEach(i => salesTotal += parseFloat(i.value) || 0);
            if (salesTotal > 100) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">جمع درصد مشارکت کارشناسان نمی‌تواند بیشتر از 100 باشد.</div>';
                return;
            }

            const sales = [];
            document.querySelectorAll('.sale-row').forEach(row => {
                const userId = row.querySelector('.user-select').value;
                const share  = parseFloat(row.querySelector('.share-input').value);
                if (userId && share) sales.push({ user_id: parseInt(userId), share_percent: share });
            });

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const isOfficial = document.querySelector('input[name="is_official"]:checked')?.value === '1';

            const data = await apiCall('/api/v1/orders', {
                method: 'POST',
                body: JSON.stringify({
                    customer_id:     parseInt(customerId),
                    company_id:      parseInt(companyId),
                    address_id:      parseInt(addressId),
                    contact_id:      parseInt(contactId),
                    is_official:     isOfficial,
                    order_number:    document.getElementById('order_number').value || null,
                    send_date:       document.getElementById('send_date').value || null,
                    freight_type_id: document.getElementById('freight-type-select').value
                        ? parseInt(document.getElementById('freight-type-select').value) : null,
                    freight_amount:  parseFloat(document.getElementById('freight_amount').value) || 0,
                    payment_terms:   document.getElementById('payment_terms').value || null,
                    notes:           document.getElementById('notes').value || null,
                    items,
                    sales: sales.length ? sales : undefined,
                })
            });

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
    </script>
@endpush
