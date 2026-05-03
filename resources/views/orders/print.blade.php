<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>برگ خروج کالا</title>
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } body { margin: 0; } }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; font-size: 13px; background: #fff; }
        .page { max-width: 820px; margin: 15px auto; padding: 15px; border: 1px solid #ccc; }
        .header-box { border: 2px solid #000; padding: 8px 12px; margin-bottom: 10px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; }
        .items-table th { background: #f0f0f0; font-weight: bold; font-size:12px; }
        .summary-table { min-width: 210px; border-collapse: collapse; }
        .summary-table td { padding: 3px 8px; border: 1px solid #aaa; font-size:12px; }
        .summary-table .total { font-weight: bold; background: #f0f0f0; }
        .footer-section { display: flex; gap: 20px; margin-top: 10px; }
        .notes-box { flex: 1; font-size: 12px; line-height: 2; }
        .signature-row { display: flex; justify-content: space-between; margin-top: 30px; }
        .signature-box { text-align: center; min-width: 140px; }
        .info-row { display: flex; gap: 20px; margin-bottom: 8px; font-size: 12px; }
        .info-item { flex: 1; }
        .info-label { font-weight: bold; display: inline; }
    </style>
</head>
<body>
<div class="page" id="print-content">
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
    </div>
    <div id="content" style="display:none;">

        <!-- Header -->
        <div class="header-box d-flex justify-content-between align-items-start">
            <div style="min-width:180px;">
                <div style="font-size:11px; color:#666; margin-bottom:2px;">شماره: <strong id="order-num">-</strong></div>
                <div style="font-size:11px; color:#666; margin-bottom:2px;">تاریخ: <strong id="order-date">-</strong></div>
                <div style="font-size:11px; color:#666; margin-bottom:2px;">مهندس فروش: <strong id="sale-user">-</strong></div>
            </div>
            <div class="text-center">
                <div id="doc-title" style="font-size:20px; font-weight:bold;">حواله انبار</div>
                <div id="official-label" class="mt-1"></div>
            </div>
            <div class="text-end" style="min-width:160px;">
                <div style="font-size:20px; font-weight:bold;">آریا بتن ارگ</div>
                <div style="font-size:10px; color:#666;">www.aryabetonarg.com</div>
            </div>
        </div>

        <!-- اطلاعات مشتری -->
        <div class="info-row">
            <div class="info-item">
                <span class="info-label">نام مشتری: </span>
                <span id="customer-name">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">موبایل مشتری: </span>
                <span id="customer-mobile">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">نوع کرایه: </span>
                <span id="freight-type">-</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-item">
                <span class="info-label">گیرنده: </span>
                <span id="contact-name">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">موبایل گیرنده: </span>
                <span id="contact-mobile">-</span>
            </div>
            <div class="info-item"></div>
        </div>

        <!-- جدول آیتم‌ها -->
        <table class="items-table">
            <thead>
            <tr>
                <th rowspan="2" width="30">ردیف</th>
                <th rowspan="2">شرح کالا</th>
                <th rowspan="2" width="90">قیمت واحد</th>
                <th colspan="4">بسته بندی</th>
                <th rowspan="2" width="60">واحد</th>
                <th rowspan="2" width="70">مقدار کل</th>
                <th rowspan="2" width="100">مبلغ (ریال)</th>
            </tr>
            <tr>
                <th width="55">نوع</th>
                <th width="45">تعداد</th>
                <th width="45">مقدار</th>
                <th width="55">واحد</th>
            </tr>
            </thead>
            <tbody id="items-body"></tbody>
        </table>

        <!-- جمع‌ها و توضیحات -->
        <div class="footer-section">
            <table class="summary-table">
                <tr><td>جمع جزء</td><td id="s-subtotal" class="text-start">-</td></tr>
                <tr><td>ارزش افزوده</td><td id="s-tax" class="text-start">-</td></tr>
                <tr><td>پس کرایه</td><td id="s-freight" class="text-start">-</td></tr>
                <tr class="total"><td>جمع کل</td><td id="s-total" class="text-start">-</td></tr>
                <tr><td colspan="2" style="font-size:11px; padding-top:6px; border-top:2px solid #000;">
                        رقم بیمه نامه: <strong id="s-insurance">-</strong>
                    </td></tr>
                <tr><td colspan="2" style="font-size:11px;">
                        نوع حواله: <span id="s-doc-type">-</span>
                    </td></tr>
            </table>

            <div class="notes-box">
                <div>آدرس حمل: <span id="footer-address">-</span></div>
                <div>شرایط پرداخت: <span id="payment-terms">-</span></div>
                <div>زمان تحویل: <span id="send-date">-</span></div>
                <div>سایر ملاحظات: <span id="footer-notes">-</span></div>
            </div>
        </div>

        <div class="signature-row">
            <div class="signature-box">
                <div>مدیر فروش</div>
                <div style="margin-top:35px; border-top:1px solid #000; padding-top:4px; font-size:12px;">امضا</div>
            </div>
            <div class="signature-box">
                <div>مدیریت</div>
                <div style="margin-top:35px; border-top:1px solid #000; padding-top:4px; font-size:12px;">امضا</div>
            </div>
        </div>
    </div>
</div>

<div class="no-print text-center mt-3 mb-4">
    <button onclick="window.print()" class="btn btn-primary me-2">
        <i class="bx bx-printer me-1"></i> پرینت
    </button>
    <a href="/orders/{{ $id }}" class="btn btn-secondary">بازگشت</a>
</div>

<script>
    const token = localStorage.getItem('erp_token');
    const toJalali = d => d ? new Date(d).toLocaleDateString('fa-IR') : '-';
    const fmt = n => n ? Number(n).toLocaleString('fa-IR') : '0';

    fetch(`/api/v1/orders/{{ $id }}`, {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    }).then(r => r.json()).then(order => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('content').style.display = 'block';

        document.getElementById('order-num').textContent      = order.order_number || order.id;
        document.getElementById('order-date').textContent     = toJalali(order.issue_date || order.created_at);
        document.getElementById('customer-name').textContent  = order.customer?.name || '-';
        document.getElementById('customer-mobile').textContent= order.contact?.mobile || '-';
        document.getElementById('contact-name').textContent   = order.contact?.full_name || '-';
        document.getElementById('contact-mobile').textContent = order.contact?.mobile || '-';
        document.getElementById('freight-type').textContent   = order.freight_type?.name || '-';
        document.getElementById('footer-address').textContent = order.address?.full_address || '-';
        document.getElementById('payment-terms').textContent  = order.payment_terms || '-';
        document.getElementById('send-date').textContent      = toJalali(order.send_date);
        document.getElementById('footer-notes').textContent   = order.notes || '-';
        document.getElementById('s-doc-type').textContent     = order.is_official ? 'رسمی' : 'غیررسمی';

        if (order.sales?.length) {
            document.getElementById('sale-user').textContent = order.sales[0].user?.full_name || '-';
        }

        document.getElementById('doc-title').textContent = order.is_official ? 'حواله انبار' : 'برگ خروج کالا';
        document.getElementById('official-label').innerHTML = order.is_official
            ? '<span style="background:#34c38f;color:#fff;padding:2px 8px;border-radius:3px;font-size:11px;">رسمی</span>'
            : '<span style="background:#6c757d;color:#fff;padding:2px 8px;border-radius:3px;font-size:11px;">غیررسمی</span>';

        // آیتم‌ها — بدون ردیف خالی
        let subtotal = 0;
        const tbody = document.getElementById('items-body');
        if (order.items?.length) {
            tbody.innerHTML = order.items.map((item, i) => {
                const line = item.quantity * item.base_price;
                subtotal += line;
                const totalAmt = item.amount ? item.quantity * item.amount : '';
                return `<tr>
                <td>${i+1}</td>
                <td style="text-align:right;">${item.product?.name || '-'}</td>
                <td>${fmt(item.base_price)}</td>
                <td>${item.packaging_type?.name || '-'}</td>
                <td>${item.quantity}</td>
                <td>${item.amount || ''}</td>
                <td>${item.unit?.name || '-'}</td>
                <td>${item.unit?.name || '-'}</td>
                <td>${totalAmt ? fmt(totalAmt) : ''}</td>
                <td style="text-align:left;">${fmt(line)}</td>
            </tr>`;
            }).join('');
        }

        const tax = order.is_official ? subtotal * 0.1 : 0;
        const freight = parseFloat(order.freight_amount) || 0;
        const insurance = parseFloat(order.insurance_amount) || 0;
        const grand = subtotal + tax + freight;

        document.getElementById('s-subtotal').textContent  = fmt(subtotal);
        document.getElementById('s-tax').textContent       = fmt(tax);
        document.getElementById('s-freight').textContent   = fmt(freight);
        document.getElementById('s-total').textContent     = fmt(grand);
        document.getElementById('s-insurance').textContent = fmt(insurance) + ' ریال';
    });
</script>
</body>
</html>
