@extends('layouts.app')
@section('title', 'پروفایل | ERP System')
@section('page-title', 'مدیریت پروفایل')

@section('content')
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="avatar-preview" src="" alt="پروفایل"
                             class="rounded-circle"
                             style="width:120px;height:120px;object-fit:cover;border:3px solid #556ee6;"
                             onerror="this.src='/assets/images/users/avatar-1.jpg'">
                    </div>
                    <h5 id="profile-name">...</h5>
                    <p class="text-muted" id="profile-role">...</p>

                    <div class="mt-3">
                        <label class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-camera me-1"></i> تغییر تصویر
                            <input type="file" id="avatar-input" accept="image/*" style="display:none;">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">اطلاعات حساب کاربری</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام و نام خانوادگی</label>
                        <input type="text" class="form-control" id="full_name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نام کاربری</label>
                        <input type="text" class="form-control" id="username" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نقش</label>
                        <input type="text" class="form-control" id="role" disabled>
                    </div>

                    <hr>
                    <h5 class="mb-3">تغییر رمز عبور</h5>

                    <div class="mb-3">
                        <label class="form-label">رمز عبور جدید</label>
                        <input type="password" class="form-control" id="password"
                               placeholder="خالی بگذارید اگر نمی‌خواهید تغییر دهید">
                    </div>

                    <button type="button" class="btn btn-primary waves-effect" id="save-btn">
                        <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره تغییرات</span>
                        <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // بارگذاری اطلاعات پروفایل
        apiCall('/api/v1/profile').then(user => {
            document.getElementById('full_name').value   = user.full_name || '';
            document.getElementById('username').value    = user.username || '';
            document.getElementById('role').value        = user.role || '';
            document.getElementById('profile-name').textContent = user.full_name || '';
            document.getElementById('profile-role').textContent = user.role || '';

            const preview = document.getElementById('avatar-preview');
            if (user.avatar) {
                preview.src = user.avatar;
                // آپدیت header هم
                document.querySelector('.header-profile-user').src = user.avatar;
            } else {
                preview.src = '/assets/images/users/avatar-1.jpg';
            }
        });

        // تغییر تصویر
        document.getElementById('avatar-input').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showToast('حجم تصویر نباید بیشتر از 2 مگابایت باشد', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const base64 = e.target.result;
                document.getElementById('avatar-preview').src = base64;

                // ذخیره آنی
                apiCall('/api/v1/profile', {
                    method: 'PUT',
                    body: JSON.stringify({ avatar: base64 })
                }).then(data => {
                    if (data.avatar) {
                        document.querySelector('.header-profile-user').src = base64;
                        localStorage.setItem('erp_user',
                            JSON.stringify({...JSON.parse(localStorage.getItem('erp_user') || '{}'), avatar: base64}));
                        showToast('تصویر پروفایل ذخیره شد', 'success');
                    }
                });
            };
            reader.readAsDataURL(file);
        });

        // ذخیره اطلاعات
        document.getElementById('save-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const payload = {
                full_name: document.getElementById('full_name').value.trim(),
            };

            const pwd = document.getElementById('password').value;
            if (pwd) payload.password = pwd;

            const data = await apiCall('/api/v1/profile', {
                method: 'PUT',
                body: JSON.stringify(payload)
            });

            if (data.full_name) {
                showToast('پروفایل با موفقیت ذخیره شد', 'success');
                document.getElementById('profile-name').textContent = data.full_name;
                document.getElementById('header-username').textContent = data.full_name;
                localStorage.setItem('erp_user',
                    JSON.stringify({...JSON.parse(localStorage.getItem('erp_user') || '{}'), full_name: data.full_name}));
                document.getElementById('password').value = '';
            } else {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'خطا در ذخیره'}</div>`;
            }

            btn.disabled = false;
            btn.querySelector('.normal-text').style.display = 'inline';
            btn.querySelector('.loading-text').style.display = 'none';
        });
    </script>
@endpush
