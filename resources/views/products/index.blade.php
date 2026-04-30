@extends('layouts.app')

@section('title', 'محصولات | ERP System')
@section('page-title', 'لیست محصولات')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">محصولات</h4>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="search-input"
                                   placeholder="جستجو در نام محصول..." style="width:250px;">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary filter-btn active" data-status="all">همه</button>
                                <button class="btn btn-outline-success filter-btn" data-status="active">فعال</button>
                                <button class="btn btn-outline-danger filter-btn" data-status="inactive">غیرفعال</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>شناسه</th>
                                <th>نام محصول</th>
                                <th>قیمت پایه</th>
                                <th>وضعیت</th>
                            </tr>
                            </thead>
                            <tbody id="products-table">
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="empty-message" class="text-center py-4" style="display:none;">
                        <i class="bx bx-package font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">هیچ محصولی یافت نشد</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let allProducts = [];
        let currentFilter = 'all';
        let searchTimeout;

        function renderProducts(products) {
            const tbody = document.getElementById('products-table');
            const empty = document.getElementById('empty-message');

            // اعمال فیلتر وضعیت
            if (currentFilter !== 'all') {
                products = products.filter(p => p.status === currentFilter);
            }

            if (products.length === 0) {
                tbody.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            tbody.innerHTML = products.map(p => `
            <tr>
                <td>${p.id}</td>
                <td><strong>${p.name}</strong></td>
                <td>${p.base_price ? Number(p.base_price).toLocaleString('fa-IR') + ' ریال' : '-'}</td>
                <td>
                    ${p.status === 'active'
                ? '<span class="badge bg-success">فعال</span>'
                : '<span class="badge bg-danger">غیرفعال</span>'}
                </td>
            </tr>
        `).join('');
        }

        // بارگذاری محصولات
        apiCall('/api/v1/products').then(products => {
            allProducts = products;
            renderProducts(products);
        });

        // جستجو
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();

            if (keyword.length === 0) {
                renderProducts(allProducts);
                return;
            }

            searchTimeout = setTimeout(() => {
                apiCall(`/api/v1/products?search=${encodeURIComponent(keyword)}`)
                    .then(products => {
                        allProducts = products;
                        renderProducts(products);
                    });
            }, 400);
        });

        // فیلتر وضعیت
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.status;
                renderProducts(allProducts);
            });
        });
    </script>
@endpush
