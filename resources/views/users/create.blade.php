@extends('layouts.app')
@section('title', 'افزودن پرسنل | ERP System')
@section('page-title', 'افزودن پرسنل جدید')

@section('content')
    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">فرم ثبت پرسنل</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نام کاربری <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رمز عبور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نقش <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id">
                            <option value="">-- انتخاب نقش --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" id="status">
                            <option value="active">فعال</option>
                            <option value="inactive">غیرفعال</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره</span>
                            <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                        </button>
                        <a href="/users" class="btn btn-secondary">انصراف</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        apiCall('/api/v1/roles').then(roles => {
            const sel = document.getElementById('role_id');
            roles.forEach(r => sel.innerHTML += `<option value="${r.id}">${r.name}</option>`);
        });

        document.getElementById('submit-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';
            const full_name = document.getElementById('full_name').value.trim();
            const username  = document.getElementById('username').value.trim();
            const password  = document.getElementById('password').value;
            const role_id   = document.getElementById('role_id').value;

            if (!full_name || !username || !password || !role_id) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً همه فیلدهای الزامی را پر کنید.</div>';
                return;
            }
            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const data = await apiCall('/api/v1/users', {
                method: 'POST',
                body: JSON.stringify({
                    full_name, username, password,
                    role_id: parseInt(role_id),
                    status: document.getElementById('status').value,
                })
            });

            if (data.id) {
                showToast('پرسنل با موفقیت ثبت شد', 'success');
                setTimeout(() => window.location.href = '/users', 1000);
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
