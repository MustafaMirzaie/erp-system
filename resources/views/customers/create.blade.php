@extends('layouts.app')
@section('title', 'افزودن مشتری | ERP System')
@section('page-title', 'افزودن مشتری جدید')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">فرم ثبت مشتری</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام مشتری <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="نام شرکت یا شخص">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">کد اقتصادی</label>
                            <input type="text" class="form-control" id="economic_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">شناسه ملی</label>
                            <input type="text" class="form-control" id="national_id">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">سقف اعتبار (ریال)</label>
                        <input type="number" class="form-control" id="credit_limit" value="0" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" id="status">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>

                    <hr>
                    <h5 class="mb-3">آدرس پیش‌فرض</h5>

                    <div class="mb-3">
                        <label class="form-label">عنوان آدرس</label>
                        <input type="text" class="form-control" id="address_title" placeholder="مثال: دفتر مرکزی">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">استان</label>
                            <input type="text" class="form-control" id="province">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">شهر</label>
                            <input type="text" class="form-control" id="city">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">آدرس کامل</label>
                        <textarea class="form-control" id="full_address" rows="2"></textarea>
                    </div>

                    <hr>
                    <h5 class="mb-3">گیرنده پیش‌فرض</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نام و نام خانوادگی</label>
                            <input type="text" class="form-control" id="contact_name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">تلفن</label>
                            <input type="text" class="form-control" id="contact_phone">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">موبایل</label>
                            <input type="text" class="form-control" id="contact_mobile">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره</span>
                            <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                        </button>
                        <a href="/customers" class="btn btn-secondary">انصراف</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('submit-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const name = document.getElementById('name').value.trim();
            if (!name) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">نام مشتری الزامی است.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const data = await apiCall('/api/v1/customers', {
                method: 'POST',
                body: JSON.stringify({
                    name,
                    economic_code:  document.getElementById('economic_code').value || null,
                    national_id:    document.getElementById('national_id').value || null,
                    credit_limit:   parseFloat(document.getElementById('credit_limit').value) || 0,
                    status:         document.getElementById('status').value,
                    address: {
                        title:        document.getElementById('address_title').value || null,
                        province:     document.getElementById('province').value || null,
                        city:         document.getElementById('city').value || null,
                        full_address: document.getElementById('full_address').value || null,
                        is_default:   true,
                    },
                    contact: {
                        full_name: document.getElementById('contact_name').value || null,
                        phone:     document.getElementById('contact_phone').value || null,
                        mobile:    document.getElementById('contact_mobile').value || null,
                    }
                })
            });

            if (data.id) {
                showToast('مشتری با موفقیت ثبت شد', 'success');
                setTimeout(() => window.location.href = '/customers', 1000);
            } else {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'خطا در ثبت'}</div>`;
                btn.disabled = false;
                btn.querySelector('.normal-text').style.display = 'inline';
                btn.querySelector('.loading-text').style.display = 'none';
            }
        });
    </script>
@endpush
