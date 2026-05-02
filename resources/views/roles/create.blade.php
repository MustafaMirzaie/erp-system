@extends('layouts.app')
@section('title', 'افزودن نقش | ERP System')
@section('page-title', 'افزودن نقش جدید')

@section('content')
    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">فرم ثبت نقش</h4>
                    <div id="alert-box" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">نام نقش <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="مثال: مدیر فروش">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary waves-effect" id="submit-btn">
                            <span class="normal-text"><i class="bx bx-save me-1"></i> ذخیره</span>
                            <span class="loading-text" style="display:none;">در حال ذخیره...</span>
                        </button>
                        <a href="/roles" class="btn btn-secondary">انصراف</a>
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
                alertBox.innerHTML = '<div class="alert alert-danger">نام نقش الزامی است.</div>';
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading-text').style.display = 'inline';

            const data = await apiCall('/api/v1/roles', {
                method: 'POST',
                body: JSON.stringify({ name })
            });

            if (data.id) {
                showToast('نقش با موفقیت ثبت شد', 'success');
                setTimeout(() => window.location.href = '/roles', 1000);
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
