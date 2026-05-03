@extends('layouts.app')
@section('title', 'ثبت آدرس و گیرنده | ERP System')
@section('page-title', 'ثبت آدرس و گیرنده')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title mb-4">اطلاعات آدرس</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">عنوان آدرس <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" placeholder="مثال: دفتر مرکزی، انبار، کارخانه">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">استان <span class="text-danger">*</span></label>
                            <select class="form-select" id="province-select">
                                <option value="">-- انتخاب استان --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">شهرستان <span class="text-danger">*</span></label>
                            <select class="form-select" id="city-select" disabled>
                                <option value="">-- ابتدا استان انتخاب کنید --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">آدرس کامل <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="full_address" rows="3"
                                  placeholder="خیابان، کوچه، پلاک..."></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_default">
                        <label class="form-check-label" for="is_default">آدرس پیش‌فرض</label>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">گیرندگان</h4>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-contact-btn">
                            <i class="bx bx-plus me-1"></i> افزودن گیرنده
                        </button>
                    </div>

                    <div id="contacts-container">
                        <!-- گیرنده اول -->
                        <div class="contact-row border rounded p-3 mb-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control contact-name" placeholder="نام گیرنده">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شماره موبایل</label>
                                    <input type="text" class="form-control contact-mobile" placeholder="09xxxxxxxxx">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">تلفن</label>
                                    <input type="text" class="form-control contact-phone" placeholder="021xxxxxxxx">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-contact"
                                            style="display:none; width:36px; height:36px; padding:0;">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                    <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره</span>
                    <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                </button>
                <a href="/customers/{{ $customerId }}" class="btn btn-secondary">انصراف</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const customerId = {{ $customerId }};

        // داده‌های استان‌ها و شهرستان‌ها
        const iranProvinces = {
            'تهران': ['تهران', 'ری', 'شمیرانات', 'اسلامشهر', 'رباط کریم', 'ورامین', 'پاکدشت', 'دماوند', 'فیروزکوه', 'شهریار'],
            'اصفهان': ['اصفهان', 'کاشان', 'خمینی‌شهر', 'نجف‌آباد', 'شاهین‌شهر', 'فلاورجان', 'لنجان', 'مبارکه', 'گلپایگان', 'اردستان'],
            'فارس': ['شیراز', 'مرودشت', 'جهرم', 'کازرون', 'فسا', 'داراب', 'لارستان', 'ممسنی', 'نی‌ریز', 'آباده'],
            'خراسان رضوی': ['مشهد', 'نیشابور', 'سبزوار', 'تربت حیدریه', 'قوچان', 'کاشمر', 'تربت جام', 'درگز', 'خواف', 'گناباد'],
            'آذربایجان شرقی': ['تبریز', 'مراغه', 'مرند', 'میانه', 'اهر', 'بناب', 'سراب', 'هشترود', 'شبستر', 'ملکان'],
            'آذربایجان غربی': ['ارومیه', 'خوی', 'مهاباد', 'بوکان', 'میاندوآب', 'سلماس', 'پیرانشهر', 'سردشت', 'اشنویه', 'نقده'],
            'کرمان': ['کرمان', 'رفسنجان', 'جیرفت', 'سیرجان', 'بم', 'زرند', 'کهنوج', 'بردسیر', 'شهربابک', 'عنبرآباد'],
            'مازندران': ['ساری', 'آمل', 'بابل', 'قائمشهر', 'نوشهر', 'چالوس', 'بابلسر', 'تنکابن', 'رامسر', 'بهشهر'],
            'گیلان': ['رشت', 'انزلی', 'لاهیجان', 'لنگرود', 'صومعه‌سرا', 'فومن', 'تالش', 'آستانه', 'آستارا', 'رودبار'],
            'البرز': ['کرج', 'فردیس', 'نظرآباد', 'ساوجبلاغ', 'طالقان', 'اشتهارد'],
            'قم': ['قم'],
            'همدان': ['همدان', 'ملایر', 'نهاوند', 'اسدآباد', 'کبودرآهنگ', 'تویسرکان', 'بهار', 'رزن'],
            'کرمانشاه': ['کرمانشاه', 'اسلام‌آباد', 'سنقر', 'کنگاور', 'جوانرود', 'پاوه', 'هرسین', 'صحنه'],
            'خوزستان': ['اهواز', 'آبادان', 'خرمشهر', 'دزفول', 'مسجد سلیمان', 'بهبهان', 'شادگان', 'ماهشهر', 'شوش', 'اندیمشک'],
            'گلستان': ['گرگان', 'گنبدکاووس', 'علی‌آباد', 'کردکوی', 'آق‌قلا', 'مینودشت', 'رامیان', 'آزادشهر'],
            'سمنان': ['سمنان', 'شاهرود', 'دامغان', 'گرمسار', 'مهدیشهر', 'میامی'],
            'زنجان': ['زنجان', 'ابهر', 'خدابنده', 'قیدار', 'ایجرود', 'طارم'],
            'بوشهر': ['بوشهر', 'دشتستان', 'دشتی', 'گناوه', 'تنگستان', 'جم', 'دیلم', 'کنگان', 'دیر'],
            'قزوین': ['قزوین', 'البرز', 'بوئین‌زهرا', 'آبیک', 'تاکستان'],
            'اردبیل': ['اردبیل', 'مشکین‌شهر', 'پارس‌آباد', 'خلخال', 'گرمی', 'نیر', 'بیله‌سوار'],
            'لرستان': ['خرم‌آباد', 'بروجرد', 'کوهدشت', 'ازنا', 'الیگودرز', 'پلدختر', 'دورود'],
            'چهارمحال و بختیاری': ['شهرکرد', 'بروجن', 'لردگان', 'فارسان', 'کیار', 'اردل', 'کوهرنگ'],
            'کهگیلویه و بویراحمد': ['یاسوج', 'دهدشت', 'گچساران', 'دنا', 'بویراحمد'],
            'خراسان شمالی': ['بجنورد', 'شیروان', 'اسفراین', 'آشخانه', 'جاجرم', 'فاروج'],
            'خراسان جنوبی': ['بیرجند', 'قائن', 'طبس', 'فردوس', 'سربیشه', 'درمیان'],
            'مرکزی': ['اراک', 'ساوه', 'خمین', 'محلات', 'آشتیان', 'تفرش', 'شازند'],
            'ایلام': ['ایلام', 'مهران', 'دهلران', 'آبدانان', 'دره‌شهر', 'ایوان', 'چرداول'],
            'یزد': ['یزد', 'میبد', 'اردکان', 'بافق', 'ابرکوه', 'تفت', 'مهریز'],
            'سیستان و بلوچستان': ['زاهدان', 'زابل', 'ایرانشهر', 'خاش', 'سراوان', 'چابهار', 'نیکشهر'],
            'کردستان': ['سنندج', 'سقز', 'مریوان', 'بانه', 'قروه', 'دیواندره', 'کامیاران'],
            'هرمزگان': ['بندرعباس', 'میناب', 'جاسک', 'قشم', 'خمیر', 'رودان', 'حاجی‌آباد'],
            'گیلان': ['رشت', 'انزلی', 'لاهیجان', 'لنگرود', 'صومعه‌سرا', 'فومن', 'تالش'],
        };

        // بارگذاری استان‌ها از API
        apiCall('/api/v1/provinces').then(provinces => {
            const sel = document.getElementById('province-select');
            provinces.forEach(p => sel.innerHTML += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`);
        });

        let provinceData = [];
        apiCall('/api/v1/provinces').then(data => { provinceData = data; });

        document.getElementById('province-select').addEventListener('change', function() {
            const citySel = document.getElementById('city-select');
            citySel.innerHTML = '<option value="">-- انتخاب شهرستان --</option>';
            citySel.disabled = true;
            const province = provinceData.find(p => p.name === this.value);
            if (!province) return;
            apiCall(`/api/v1/provinces/${province.id}/cities`).then(cities => {
                cities.forEach(c => citySel.innerHTML += `<option value="${c.name}">${c.name}</option>`);
                citySel.disabled = false;
            });
        });

        // پر کردن استان‌ها
        const provinceSel = document.getElementById('province-select');
        Object.keys(iranProvinces).sort().forEach(p => {
            provinceSel.innerHTML += `<option value="${p}">${p}</option>`;
        });

        provinceSel.addEventListener('change', function() {
            const citySel = document.getElementById('city-select');
            citySel.innerHTML = '<option value="">-- انتخاب شهرستان --</option>';
            citySel.disabled = true;
            if (!this.value) return;
            const cities = iranProvinces[this.value] || [];
            cities.forEach(c => citySel.innerHTML += `<option value="${c}">${c}</option>`);
            citySel.disabled = false;
        });

        // افزودن گیرنده
        document.getElementById('add-contact-btn').addEventListener('click', function() {
            const container = document.getElementById('contacts-container');
            const div = document.createElement('div');
            div.className = 'contact-row border rounded p-3 mb-3';
            div.innerHTML = `
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                <input type="text" class="form-control contact-name" placeholder="نام گیرنده">
            </div>
            <div class="col-md-4">
                <label class="form-label">شماره موبایل</label>
                <input type="text" class="form-control contact-mobile" placeholder="09xxxxxxxxx">
            </div>
            <div class="col-md-3">
                <label class="form-label">تلفن</label>
                <input type="text" class="form-control contact-phone" placeholder="021xxxxxxxx">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-contact"
                    style="width:36px; height:36px; padding:0;">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        </div>
    `;
            container.appendChild(div);
            div.querySelector('.remove-contact').addEventListener('click', () => div.remove());
        });

        // ذخیره
        document.getElementById('submit-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const title    = document.getElementById('title').value.trim();
            const province = document.getElementById('province-select').value;
            const city     = document.getElementById('city-select').value;
            const address  = document.getElementById('full_address').value.trim();

            if (!title || !province || !city || !address) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً تمام فیلدهای الزامی را پر کنید.</div>';
                return;
            }

            const contacts = [];
            document.querySelectorAll('.contact-row').forEach(row => {
                const name = row.querySelector('.contact-name').value.trim();
                if (name) {
                    contacts.push({
                        full_name: name,
                        mobile: row.querySelector('.contact-mobile').value.trim() || null,
                        phone:  row.querySelector('.contact-phone').value.trim() || null,
                    });
                }
            });

            if (!contacts.length) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">حداقل یک گیرنده وارد کنید.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            // ذخیره آدرس
            const addrData = await apiCall(`/api/v1/customers/${customerId}/addresses`, {
                method: 'POST',
                body: JSON.stringify({
                    title,
                    province,
                    city,
                    full_address: address,
                    is_default: document.getElementById('is_default').checked,
                })
            });

            if (!addrData.id) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<div class="alert alert-danger">${addrData.message || 'خطا در ثبت آدرس'}</div>`;
                btn.disabled = false;
                btn.querySelector('.normal-text').style.display = 'inline';
                btn.querySelector('.loading-text').style.display = 'none';
                return;
            }

            // ذخیره گیرندگان
            for (const contact of contacts) {
                await apiCall(`/api/v1/customers/addresses/${addrData.id}/contacts`, {
                    method: 'POST',
                    body: JSON.stringify(contact)
                });
            }

            showToast('آدرس و گیرنده با موفقیت ثبت شد', 'success');
            setTimeout(() => window.location.href = `/customers/${customerId}`, 1000);
        });
    </script>
@endpush
