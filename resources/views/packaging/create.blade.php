@extends('layouts.app')
@section('title', 'افزودن بسته‌بندی | ERP System')
@section('page-title', 'افزودن بسته‌بندی جدید')

@section('content')
    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">فرم ثبت بسته‌بندی</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام بسته‌بندی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="مثال: گالن، کیسه، تانک IBC">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" id="is_active">
                            <option value="1">فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره</span>
                            <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                        </button>
                        <a href="/packaging" class="btn btn-secondary">انصراف</a>
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
                alertBox.innerHTML = '<div class="alert alert-danger">نام بسته‌بندی الزامی است.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const data = await apiCall('/api/v1/lookup/packaging-types', {
                method: 'POST',
                body: JSON.stringify({
                    name,
                    is_active: document.getElementById('is_active').value === '1'
                })
            });

            if (data.id) {
                showToast('بسته‌بندی با موفقیت ثبت شد', 'success');
                setTimeout(() => window.location.href = '/packaging', 1000);
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
