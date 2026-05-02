@extends('layouts.app')
@section('title', 'پرسنل | ERP System')
@section('page-title', 'مدیریت پرسنل')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">لیست پرسنل</h4>
                        <a href="/users/create" class="btn btn-primary waves-effect">
                            <i class="bx bx-plus me-1"></i> افزودن پرسنل
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>نام</th>
                                <th>نام کاربری</th>
                                <th>نقش</th>
                                <th>وضعیت</th>
                            </tr>
                            </thead>
                            <tbody id="users-table">
                            <tr><td colspan="5" class="text-center py-4">
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
        apiCall('/api/v1/users').then(users => {
            const tbody = document.getElementById('users-table');
            if (!users.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">موردی یافت نشد</td></tr>';
                return;
            }
            tbody.innerHTML = users.map(u => `
        <tr>
            <td>${u.id}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    ${u.avatar
                ? `<img src="${u.avatar}" class="rounded-circle" width="32" height="32" style="object-fit:cover;">`
                : `<div class="avatar-xs"><span class="avatar-title rounded-circle bg-primary">${u.full_name?.charAt(0)}</span></div>`}
                    <strong>${u.full_name}</strong>
                </div>
            </td>
            <td>${u.username}</td>
            <td>${u.role?.name || '-'}</td>
            <td>${u.status === 'active'
                ? '<span class="badge bg-success">فعال</span>'
                : '<span class="badge bg-danger">غیرفعال</span>'}</td>
        </tr>
    `).join('');
        });
    </script>
@endpush
