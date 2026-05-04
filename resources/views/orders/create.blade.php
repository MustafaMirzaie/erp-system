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

    @push('styles')
        <style>
            .select2-container--default .select2-selection--single {
                height: 38px !important;
                padding: 6px 12px !important;
                border: 1px solid #ced4da !important;
                border-radius: 4px !important;
                text-align: right !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 24px !important;
                padding-left: 8px !important;
                padding-right: 0 !important;
                text-align: right !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px !important;
                left: 8px !important;
                right: auto !important;
            }
            .select2-dropdown {
                text-align: right !important;
                direction: rtl !important;
            }
            .select2-search--dropdown .select2-search__field {
                text-align: right !important;
                direction: rtl !important;
            }
        </style>
    @endpush

    <div class="row g-3">

        <div id="page-loading">
            <div class="row g-3">
                <div class="col-xl-9">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="placeholder-glow">
                                <span class="placeholder col-3 mb-3"></span>
                                <div class="row g-2">
                                    <div class="col-md-1"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                    <div class="col-md-2"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                    <div class="col-md-2"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                    <div class="col-md-3"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                    <div class="col-md-2"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                    <div class="col-md-2"><span class="placeholder col-12" style="height:38px;display:block;"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body placeholder-glow">
                            <span class="placeholder col-2 mb-3"></span>
                            <span class="placeholder col-12" style="height:50px;display:block;"></span>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body placeholder-glow">
                            <span class="placeholder col-2 mb-3"></span>
                            <span class="placeholder col-12" style="height:120px;display:block;"></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body placeholder-glow">
                            <span class="placeholder col-4 mb-3"></span>
                            <span class="placeholder col-12 mb-2" style="height:38px;display:block;"></span>
                            <span class="placeholder col-12 mb-2" style="height:38px;display:block;"></span>
                            <span class="placeholder col-12 mb-2" style="height:38px;display:block;"></span>
                            <span class="placeholder col-12 mb-2" style="height:80px;display:block;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="page-content" style="display:none;">

        {{-- ستون اصلی --}}
        <div class="col-xl-9">

            {{-- بخش اطلاعات اصلی --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">اطلاعات سفارش</h5>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="row g-2">
                        {{-- شماره سفارش --}}
                        <div class="col-md-1">
                            <label class="form-label">شماره سفارش</label>
                            <input type="text" class="form-control" id="order_number" disabled placeholder="اختیاری">
                        </div>

                        {{-- تاریخ صدور --}}
                        <div class="col-md-2">
                            <label class="form-label">تاریخ صدور</label>
                            <input type="text" class="form-control bg-light text-center" id="issue_date_display" readonly>
                            <input type="hidden" id="issue_date">
                        </div>

                        {{-- شرکت --}}
                        <div class="col-md-2">
                            <label class="form-label">شرکت <span class="text-danger">*</span></label>
                            <select class="form-select" id="company-select">
                                <option value="">-- انتخاب --</option>
                            </select>
                        </div>

                        {{-- مشتری --}}
                        <div class="col-md-3">
                            <label class="form-label">نام مشتری <span class="text-danger">*</span></label>
                            <select class="form-control" id="customer-select" style="width:100%">
                                <option value="">جستجو و انتخاب مشتری...</option>
                            </select>
                        </div>

                        {{-- تاریخ ارسال --}}
                        <div class="col-md-2">
                            <label class="form-label">تاریخ ارسال <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center" id="send_date_display" placeholder="انتخاب تاریخ">
                            <input type="hidden" id="send_date">
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
                        <button type="button" class="btn btn-success w-lg waves-effect waves-light" id="add-sale-btn">
                            <i class="bx bx-plus me-1"></i> افزودن کارشناس
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
                        <button type="button" class="btn btn-success w-lg waves-effect waves-light" id="add-item-btn">
                            <i class="bx bx-plus me-1"></i> افزودن ردیف
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
                            <tr class="table-warning">
                                <td colspan="8" class="text-end">مبلغ بیمه‌نامه:</td>
                                <td class="text-center" id="insurance-display">0</td>
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

                    <div class="row g-2 mb-3">
                        <div class="row g-2 mb-3">
                            {{-- نوع کرایه --}}
                            <div class="col-6">
                                <label class="form-label" style="font-size:13px;">نوع کرایه <span class="text-danger">*</span></label>
                                <select class="form-select" id="freight-type-select">
                                    <option value="">-- انتخاب --</option>
                                </select>
                            </div>

                            {{-- مبلغ کرایه --}}
                            <div class="col-6">
                                <label class="form-label" style="font-size:13px;">مبلغ کرایه (ریال)</label>
                                <input type="text" class="form-control" id="freight_amount" value=""
                                       placeholder="0" inputmode="numeric">
                                <div id="freight-required-msg" class="text-danger small mt-1" style="display:none;">
                                    مبلغ کرایه اجباری است
                                </div>
                            </div>
                        </div>

                        {{-- اعتبار باقیمانده --}}
                        <div class="col-12 mt-2">
                            <label class="form-label" style="font-size:13px;">اعتبار باقیمانده مشتری</label>
                            <input type="text" class="form-control bg-light" id="remaining-credit"
                                   value="-" readonly style="font-size:13px;">
                        </div>
                    </div>

                    {{-- آدرس --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;">آدرس تحویل <span class="text-danger">*</span></label>
                        <select class="form-select" id="address-select" disabled>
                            <option value="">-- ابتدا مشتری انتخاب کنید --</option>
                        </select>
                    </div>

                    {{-- گیرنده --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;">گیرنده <span class="text-danger">*</span></label>
                        <select class="form-select" id="contact-select" disabled>
                            <option value="">-- ابتدا آدرس انتخاب کنید --</option>
                        </select>
                    </div>

                    {{-- نوع پرداخت --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;">نوع پرداخت <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_type" disabled>
                            <option value="cash">خرید نقدی</option>
                            <option value="check">خرید چک</option>
                            <option value="credit">خرید اعتباری</option>
                        </select>
                    </div>

                    {{-- هشدار اعتبار --}}
                    <div id="credit-warning" style="display:none;" class="mb-2"></div>

                    {{-- شرایط پرداخت --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;">شرایط پرداخت تکمیلی <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="payment_terms" rows="3" disabled
                                  placeholder="توضیحات شرایط پرداخت..." style="font-size:13px;"></textarea>
                    </div>

                    {{-- سایر ملاحظات --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;">سایر ملاحظات</label>
                        <textarea class="form-control" id="notes" rows="4"
                                  placeholder="آدرس حمل، شرایط تحویل، سایر ملاحظات..." style="font-size:13px;"></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title">خلاصه مالی</h6>
                    <table class="table table-sm mb-0">
                        <tr><td>جمع جزء:</td><td class="text-end" id="summary-subtotal">0 ریال</td></tr>
                        <tr><td>ارزش افزوده:</td><td class="text-end" id="summary-tax">0 ریال</td></tr>
                        <tr><td>کرایه:</td><td class="text-end" id="summary-freight">0 ریال</td></tr>
                        <tr class="table-warning">
                            <td>بیمه‌نامه:</td><td class="text-end" id="summary-insurance">0 ریال</td>
                        </tr>
                        <tr class="table-primary fw-bold">
                            <td>جمع کل:</td><td class="text-end" id="summary-total">0 ریال</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="button" class="btn btn-primary btn-lg waves-effect" id="submit-btn">
                    <span class="normal-text"><i class="bx bx-save me-1"></i> ثبت سفارش</span>
                    <span class="loading-text" style="display:none;">در حال ثبت...</span>
                </button>
            </div>
        </div>

    </div>

    </div>{{-- end page-content --}}
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

        // یک ردیف پیش‌فرض
        function addSaleRow(userId = '', share = '') {
            const container = document.getElementById('sales-container');
            if (container.querySelectorAll('.sale-row').length >= 3) {
                showToast('حداکثر 3 کارشناس فروش می‌توانید انتخاب کنید', 'warning');
                return;
            }
            const div = document.createElement('div');
            div.className = 'sale-row row g-2 mb-2 align-items-center';
            div.innerHTML = `
                <div class="col-md-3">
                    <select class="form-select user-select" style="width:100%;">
                        <option value="">-- انتخاب کارشناس --</option>
                        ${users.map(u => `<option value="${u.id}" ${u.id == userId ? 'selected' : ''}>${u.full_name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="number" class="form-control share-input" value="${share}" placeholder="0" min="1" max="100">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                <div class="col-2 remove-sale-col">
                    <button type="button" class="btn btn-danger btn-sm remove-sale" style="width:34px;height:38px;padding:0;">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            `;
            container.appendChild(div);
            div.querySelector('.share-input').addEventListener('input', updateSalesTotal);
            div.querySelector('.remove-sale').addEventListener('click', () => {
                div.remove();
                updateSalesTotal();
                updateRemoveBtns();
            });
            updateSalesTotal();
            updateRemoveBtns();
        }

        function updateRemoveBtns() {
            const rows = document.querySelectorAll('.sale-row');
            rows.forEach(row => {
                row.querySelector('.remove-sale').style.display = rows.length <= 1 ? 'none' : 'block';
            });
        }

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

            // ردیف پیش‌فرض کارشناس با 100%
            addSaleRow('', 100);

            document.getElementById('page-loading').style.display = 'none';
            document.getElementById('page-content').style.display = 'flex';
            document.getElementById('page-content').className = 'row g-3';
        });

        // مبلغ کرایه با separator
        document.getElementById('freight_amount').addEventListener('input', function() {
            // حذف همه کاراکترهای غیر عددی
            let raw = this.value.replace(/[^0-9]/g, '');
            window._freightRaw = parseInt(raw) || 0;
            // نمایش با جداکننده انگلیسی
            this.value = window._freightRaw ? window._freightRaw.toLocaleString('en-US') : '';
            updateTotals();
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
            console.log('address found:', addr);

            if (addr?.contacts?.length) {
                addr.contacts.forEach(c => {
                    if (c.is_active != 0) {
                        contSel.innerHTML += `<option value="${c.id}">${c.full_name}${c.mobile ? ' — ' + c.mobile : ''}</option>`;
                    }
                });
                contSel.disabled = false;
            } else {
                // fallback: مستقیم از API
                apiCall(`/api/v1/customers/addresses/${id}/contacts`).then(contacts => {
                    console.log('contacts from API:', contacts);
                    if (Array.isArray(contacts) && contacts.length) {
                        contacts.forEach(c => {
                            if (c.is_active != 0) {
                                contSel.innerHTML += `<option value="${c.id}">${c.full_name}${c.mobile ? ' — ' + c.mobile : ''}</option>`;
                            }
                        });
                        contSel.disabled = false;
                    }
                });
            }
        });

        // تاریخ صدور شمسی
        document.getElementById('issue_date_display').value = new Date().toLocaleDateString('fa-IR');
        document.getElementById('issue_date').value = new Date().toISOString().split('T')[0];

        // Select2 برای مشتری با lazy loading
        $('#customer-select').select2({
            placeholder: 'جستجو و انتخاب مشتری...',
            minimumInputLength: 0,
            ajax: {
                url: '/api/v1/customers',
                dataType: 'json',
                delay: 300,
                headers: { 'Authorization': 'Bearer ' + token },
                data: function(params) {
                    return { search: params.term, page: params.page || 1 };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.map(c => ({ id: c.id, text: c.name, data: c })),
                        pagination: { more: data.length === 10 }
                    };
                },
                cache: true
            }
        });

        $('#customer-select').on('select2:select', function(e) {
            const customerId = e.params.data.id;
            const customer = e.params.data.data;

            console.log('customer selected:', customerId, customer);

            if (!customerId) return;

            // بارگذاری آدرس‌ها
            const addrSel = document.getElementById('address-select');
            const contSel = document.getElementById('contact-select');
            addrSel.innerHTML = '<option value="">در حال بارگذاری...</option>';
            addrSel.disabled = true;
            contSel.innerHTML = '<option value="">-- ابتدا آدرس انتخاب کنید --</option>';
            contSel.disabled = true;

            apiCall(`/api/v1/customers/${customerId}/addresses`).then(addrs => {
                addresses = addrs;
                addrSel.innerHTML = '<option value="">-- انتخاب آدرس --</option>';
                if (Array.isArray(addrs) && addrs.length) {
                    addrs.forEach(a => {
                        if (a.is_active == 1 || a.is_active === true || a.is_active === null || a.is_active === undefined) {
                            addrSel.innerHTML += `<option value="${a.id}">${a.title || ''} — ${a.city || ''} — ${(a.full_address || '').substring(0,25)}...</option>`;
                        }
                    });
                    addrSel.disabled = false;
                } else {
                    addrSel.innerHTML = '<option value="">آدرسی یافت نشد</option>';
                }
            });

            // نوع پرداخت
            document.getElementById('payment_type').disabled = false;

            // اعتبار
            if (customer) {
                const credit = parseFloat(customer.credit_limit) || 0;
                const el = document.getElementById('remaining-credit');
                el.value = credit > 0 ? Number(credit).toLocaleString('en-US') + ' ریال' : 'اعتبار تعریف نشده';
            } else {
                apiCall(`/api/v1/customers/${customerId}`).then(c => {
                    const credit = parseFloat(c.credit_limit) || 0;
                    const el = document.getElementById('remaining-credit');
                    el.value = credit > 0 ? Number(credit).toLocaleString('en-US') + ' ریال' : 'اعتبار تعریف نشده';
                });
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

        function calcInsurance(subtotal) {
            // تبدیل به تومان برای مقایسه
            const toman = subtotal / 10;
            let rate = 0;
            if (toman < 100_000_000) rate = 1/5;
            else if (toman < 300_000_000) rate = 1/3;
            else if (toman < 700_000_000) rate = 1/2;
            else rate = 1/1;
            return Math.round(subtotal * rate);
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
            const freight    = window._freightRaw || 0;
            const insurance  = calcInsurance(subtotal);
            const grand      = subtotal + tax + freight;

            const fmt = n => n ? Number(n).toLocaleString('fa-IR') : '0';

            document.getElementById('subtotal').textContent        = fmt(subtotal);
            document.getElementById('tax-total').textContent       = fmt(tax);
            document.getElementById('freight-display').textContent = fmt(freight);
            document.getElementById('grand-total').textContent     = fmt(grand);

            document.getElementById('summary-subtotal').textContent  = fmt(subtotal) + ' ریال';
            document.getElementById('summary-tax').textContent       = fmt(tax) + ' ریال';
            document.getElementById('summary-freight').textContent   = fmt(freight) + ' ریال';
            document.getElementById('summary-total').textContent     = fmt(grand) + ' ریال';
            document.getElementById('summary-insurance').textContent = fmt(insurance) + ' ریال';

            document.getElementById('insurance-display').textContent = fmt(insurance);

            // ذخیره برای ارسال
            window._insuranceAmount = insurance;
        }

        // آپدیت ارزش افزوده با تغییر نوع حواله
        document.querySelectorAll('input[name="is_official"]').forEach(r => r.addEventListener('change', updateTotals));

        document.getElementById('add-item-btn').addEventListener('click', addItem);

        // کارشناسان فروش
        document.getElementById('add-sale-btn').addEventListener('click', () => addSaleRow());

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

            // تاریخ ارسال اجباری
            if (!document.getElementById('send_date').value) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">تاریخ ارسال اجباری است.</div>';
                return;
            }

            // نوع کرایه
            const freightSel = document.getElementById('freight-type-select');
            if (!freightSel.value) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">انتخاب نوع کرایه اجباری است.</div>';
                return;
            }

            // کارشناس فروش اجباری
            const salesRows = document.querySelectorAll('.sale-row');
            if (!salesRows.length || !salesRows[0].querySelector('.user-select').value) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">انتخاب حداقل یک کارشناس فروش اجباری است.</div>';
                return;
            }

            // شرایط پرداخت اجباری
            const paymentType = document.getElementById('payment_type').value;
            if (paymentType !== 'cash' && !document.getElementById('payment_terms').value.trim()) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">شرایط پرداخت تکمیلی اجباری است.</div>';
                return;
            }

            // مبلغ کرایه اجباری برای پس کرایه
            const freightType = document.getElementById('freight-type-select');
            const freightTypeName = freightType.options[freightType.selectedIndex]?.text || '';
            if (freightTypeName.includes('پس کرایه') && !window._freightRaw) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">مبلغ کرایه برای نوع پس کرایه اجباری است.</div>';
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
                    issue_date:      document.getElementById('issue_date').value || null,
                    send_date:       document.getElementById('send_date').value || null,
                    freight_type_id: document.getElementById('freight-type-select').value
                        ? parseInt(document.getElementById('freight-type-select').value) : null,
                    freight_amount:  window._freightRaw || 0,
                    payment_type:    document.getElementById('payment_type').value,
                    payment_terms:   document.getElementById('payment_terms').value || null,
                    notes:           document.getElementById('notes').value || null,
                    items,
                    sales:           sales.length ? sales : undefined,
                    insurance_amount: window._insuranceAmount || 0,
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

        // تکرار سفارش از URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const repeatId = urlParams.get('repeat');
        if (repeatId) {
            apiCall(`/api/v1/orders/${repeatId}`).then(order => {
                if (!order.id) return;
                // پر کردن فیلدها بعد از لود شدن همه داده‌ها
                setTimeout(() => {
                    // مشتری
                    const custSel = document.getElementById('customer-select');
                    custSel.value = order.customer_id;
                    custSel.dispatchEvent(new Event('change'));

                    // شرکت
                    document.getElementById('company-select').value = order.company_id;

                    // نوع حواله
                    document.querySelector(`input[name="is_official"][value="${order.is_official ? '1' : '0'}"]`).checked = true;

                    // کرایه
                    if (order.freight_type_id) document.getElementById('freight-type-select').value = order.freight_type_id;
                    if (order.freight_amount) document.getElementById('freight_amount').value = order.freight_amount;
                    if (order.payment_terms) document.getElementById('payment_terms').value = order.payment_terms;
                    if (order.notes) document.getElementById('notes').value = order.notes;

                    // آدرس و گیرنده بعد از لود آدرس‌ها
                    setTimeout(() => {
                        if (order.address_id) document.getElementById('address-select').value = order.address_id;
                        document.getElementById('address-select').dispatchEvent(new Event('change'));
                        setTimeout(() => {
                            if (order.contact_id) document.getElementById('contact-select').value = order.contact_id;
                        }, 500);
                    }, 1000);

                    // آیتم‌ها
                    if (order.items?.length) {
                        document.getElementById('items-body').innerHTML = '';
                        itemIndex = 0;
                        order.items.forEach(item => {
                            addItem();
                            const rows = document.querySelectorAll('#items-body .item-row');
                            const row = rows[rows.length - 1];
                            row.querySelector('.product-select').value = item.product_id;
                            row.querySelector('.price-input').value = item.base_price;
                            row.querySelector('.quantity-input').value = item.quantity;
                            if (item.amount) row.querySelector('.amount-input').value = item.amount;
                            if (item.packaging_type_id) row.querySelector('.packaging-select').value = item.packaging_type_id;
                            if (item.unit_id) row.querySelector('.unit-select').value = item.unit_id;
                            calcRow(row);
                        });
                    }

                    showToast('اطلاعات سفارش قبلی بارگذاری شد', 'info');
                }, 1500);
            });
        }

        function loadAddresses(customerId) {
            const addrSel = document.getElementById('address-select');
            addrSel.innerHTML = '<option value="">-- ابتدا مشتری انتخاب کنید --</option>';
            document.getElementById('contact-select').innerHTML = '<option value="">--</option>';
            if (!customerId) return;

            apiCall(`/api/v1/customers/${customerId}/addresses`).then(addresses => {
                addrSel.innerHTML = '<option value="">-- انتخاب آدرس --</option>';
                (addresses || []).forEach(a => {
                    if (a.is_active !== false) {
                        addrSel.innerHTML += `<option value="${a.id}">${a.title || ''} — ${a.city || ''} — ${a.full_address?.substring(0,30)}...</option>`;
                    }
                });
            });
        }

        function loadContacts(addressId) {
            const contSel = document.getElementById('contact-select');
            contSel.innerHTML = '<option value="">-- انتخاب گیرنده --</option>';
            if (!addressId) return;

            apiCall(`/api/v1/customers/${addressId}/addresses`).then(data => {
                // پیدا کردن contacts از address
            });

            // از آدرس‌ها contacts رو بگیر
            apiCall(`/api/v1/customers/addresses/${addressId}/contacts`).then(contacts => {
                (contacts || []).forEach(c => {
                    if (c.is_active !== false) {
                        contSel.innerHTML += `<option value="${c.id}">${c.full_name} ${c.mobile ? '— ' + c.mobile : ''}</option>`;
                    }
                });
            });
        }

        // Persian Datepicker
        $(document).ready(function() {
            $("#send_date_display").persianDatepicker({
                format: 'YYYY/MM/DD',
                onSelect: function(unix) {
                    const d = new Date(unix);
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    document.getElementById('send_date').value = year + '-' + month + '-' + day;
                }
            });
        });

        // شماره سفارش
        apiCall('/api/v1/orders/next-number').then(data => {
            if (data && data.number) {
                document.getElementById('order_number').placeholder = data.number;
            }
        });

        // سنجش اعتبار
        document.getElementById('payment_type').addEventListener('change', function() {
            const terms = document.getElementById('payment_terms');

            terms.disabled = false;

            if (this.value === 'cash') {
                terms.placeholder = 'اختیاری برای نقدی...';
            } else {
                terms.placeholder = 'توضیحات شرایط پرداخت...';
            }

            // گرفتن اطلاعات مشتری و سنجش اعتبار
            const customerId = $('#customer-select').val();
            if (!customerId) return;

            apiCall(`/api/v1/customers/${customerId}`).then(customer => {
                if (!customer.credit_limit || customer.credit_limit <= 0) return;

                // محاسبه جمع سفارش فعلی
                let subtotal = 0;
                document.querySelectorAll('#items-body .item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
                    const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
                    subtotal += qty * price;
                });

                if (subtotal > customer.credit_limit) {
                    document.getElementById('credit-warning').style.display = 'block';
                    document.getElementById('credit-warning').innerHTML = `
                <div class="alert alert-warning">
                    <i class="bx bx-error me-1"></i>
                    مبلغ سفارش (${Number(subtotal).toLocaleString('fa-IR')} ریال)
                    از سقف اعتبار مشتری (${Number(customer.credit_limit).toLocaleString('fa-IR')} ریال)
                    بیشتر است.
                </div>`;
                } else {
                    document.getElementById('credit-warning').style.display = 'none';
                }
            });
        });

        // سنجش اعتبار
        document.getElementById('payment_type').addEventListener('change', function() {
            if (this.value === 'cash') {
                document.getElementById('credit-warning').style.display = 'none';
                return;
            }
            const customerId = $('#customer-select').val();
            if (!customerId) return;
            apiCall(`/api/v1/customers/${customerId}`).then(customer => {
                if (!customer.credit_limit || customer.credit_limit <= 0) return;
                let subtotal = 0;
                document.querySelectorAll('#items-body .item-row').forEach(row => {
                    subtotal += (parseFloat(row.querySelector('.quantity-input').value) || 0) *
                        (parseFloat(row.querySelector('.price-input').value) || 0);
                });
                const warn = document.getElementById('credit-warning');
                if (subtotal > customer.credit_limit) {
                    warn.style.display = 'block';
                    warn.innerHTML = `<div class="alert alert-warning py-2 small">
                <i class="bx bx-error me-1"></i>
                مبلغ سفارش از سقف اعتبار مشتری (${Number(customer.credit_limit).toLocaleString('fa-IR')} ریال) بیشتر است
            </div>`;
                } else {
                    warn.style.display = 'none';
                }
            });
        });

        document.getElementById('freight-type-select').addEventListener('change', function() {
            const txt = this.options[this.selectedIndex]?.text || 'پس کرایه';
            document.querySelector('#items-table tfoot tr:nth-child(3) td:first-child').textContent = txt + ':';
            document.querySelector('#summary-freight').closest('tr').querySelector('td:first-child').textContent = txt + ':';
            updateTotals();
        });

    </script>
@endpush
