@extends('layouts.app')
@section('title', 'بسته‌بندی | ERP System')
@section('page-title', 'مدیریت بسته‌بندی')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">انواع بسته‌بندی</h4>
                        <a href="/packaging/create" class="btn btn-primary waves-effect">
                            <i class="bx bx-plus me-1"></i> افزودن بسته‌بندی
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>نام</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody id="packaging-table">
                            <tr><td colspan="4" class="text-center py-4">
                                    <div class="spinner-border text-primary"></div>
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        apiCall('/api/v1/lookup/packaging-types').then(items => {
            const tbody = document.getElementById('packaging-table');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">موردی یافت نشد</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(p => `
        <tr>
            <td>${p.id}</td>
            <td><strong>${p.name}</strong></td>
            <td>${p.is_active
                ? '<span class="badge bg-success">فعال</span>'
                : '<span class="badge bg-danger">غیرفعال</span>'}</td>
            <td>
                <button class="btn btn-sm btn-danger waves-effect"
                    onclick="deleteItem(${p.id})">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
        });

        function deleteItem(id) {
            if (!confirm('آیا مطمئن هستید؟')) return;
            apiCall(`/api/v1/lookup/packaging-types/${id}`, { method: 'DELETE' })
                .then(() => { showToast('حذف شد', 'success'); location.reload(); });
        }
    </script>
@endpush
