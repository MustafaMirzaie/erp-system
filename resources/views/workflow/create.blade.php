@extends('layouts.app')
@section('title', 'ایجاد گردش کار | ERP System')
@section('page-title', 'ایجاد مرحله گردش کار')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <!-- مراحل فعلی -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">ترتیب مراحل تایید فعلی</h4>
                    <div id="steps-list">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- فرم افزودن مرحله -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">افزودن مرحله جدید</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام مرحله <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name"
                               placeholder="مثال: تایید مدیر فروش">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ترتیب <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="step_order" min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نقش تایید کننده <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id">
                            <option value="">-- انتخاب نقش --</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> افزودن مرحله</span>
                            <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                        </button>
                        <a href="/workflow" class="btn btn-secondary">بازگشت</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ترتیب پیشنهادی</h5>
                    <ol class="text-muted small">
                        <li>مدیر فروش</li>
                        <li>مدیر مالی</li>
                        <li>مدیر عامل</li>
                        <li>سرپرست کارخانه</li>
                        <li>سرپرست تولید</li>
                        <li>مدیر کنترل کیفی</li>
                        <li>انتظامات (صدور بارنامه)</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // بارگذاری مراحل فعلی
        function loadSteps() {
            apiCall('/api/v1/workflow/steps').then(steps => {
                const list = document.getElementById('steps-list');
                if (!steps.length) {
                    list.innerHTML = '<p class="text-muted text-center">مرحله‌ای تعریف نشده</p>';
                    return;
                }
                list.innerHTML = `
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>ترتیب</th><th>نام مرحله</th><th>نقش</th></tr>
                    </thead>
                    <tbody>
                        ${steps.map(s => `
                            <tr>
                                <td><span class="badge bg-primary">${s.step_order}</span></td>
                                <td>${s.name}</td>
                                <td>${s.role?.name || '-'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
            });
        }

        // بارگذاری نقش‌ها
        apiCall('/api/v1/roles').then(roles => {
            const sel = document.getElementById('role_id');
            roles.forEach(r => {
                sel.innerHTML += `<option value="${r.id}">${r.name}</option>`;
            });
        });

        loadSteps();

        document.getElementById('submit-btn').addEventListener('click', async function() {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'none';

            const name       = document.getElementById('name').value.trim();
            const step_order = parseInt(document.getElementById('step_order').value);
            const role_id    = document.getElementById('role_id').value;

            if (!name || !step_order || !role_id) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = '<div class="alert alert-danger">لطفاً همه فیلدها را پر کنید.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const data = await apiCall('/api/v1/workflow/steps', {
                method: 'POST',
                body: JSON.stringify({ name, step_order, role_id: parseInt(role_id) })
            });

            if (data.id) {
                showToast('مرحله با موفقیت افزوده شد', 'success');
                loadSteps();
                document.getElementById('name').value = '';
            } else {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'خطا در ثبت'}</div>`;
            }

            btn.disabled = false;
            btn.querySelector('.normal-text').style.display = 'inline';
            btn.querySelector('.loading-text').style.display = 'none';
        });
    </script>
@endpush
