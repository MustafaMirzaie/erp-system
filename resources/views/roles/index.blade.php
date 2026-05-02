@extends('layouts.app')
@section('title', 'نقش‌ها | ERP System')
@section('page-title', 'مدیریت نقش‌ها')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">نقش‌ها</h4>
                        <a href="/roles/create" class="btn btn-primary waves-effect">
                            <i class="bx bx-plus me-1"></i> افزودن نقش
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>نام نقش</th>
                            </tr>
                            </thead>
                            <tbody id="roles-table">
                            <tr><td colspan="2" class="text-center py-4">
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
        apiCall('/api/v1/roles').then(roles => {
            const tbody = document.getElementById('roles-table');
            if (!roles.length) {
                tbody.innerHTML = '<tr><td colspan="2" class="text-center">موردی یافت نشد</td></tr>';
                return;
            }
            tbody.innerHTML = roles.map(r => `
        <tr>
            <td>${r.id}</td>
            <td><strong>${r.name}</strong></td>
        </tr>
    `).join('');
        });
    </script>
@endpush
