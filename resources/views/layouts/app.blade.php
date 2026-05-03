<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'ERP System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/persian-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body data-sidebar="dark">

<!-- Loader -->
<div id="preloader" dir="rtl">
    <div id="status">
        <div class="spinner-chase">
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
        </div>
    </div>
</div>

<div id="layout-wrapper">

    <!-- Header -->
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="/dashboard" class="logo logo-dark">
                        <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="لوگو" height="22">
                        </span>
                        <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="لوگو" height="35">
                        </span>
                    </a>
                    <a href="/dashboard" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-light.svg') }}" alt="لوگو" height="22">
                        </span>
                        <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-light.png') }}" alt="لوگو" height="35">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex">
                <!-- نام کاربر -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="avatar">
                        <span class="d-none d-xl-inline-block ms-1" id="header-username">کاربر</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item text-danger" href="#" id="logout-btn">
                            <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                            خروج
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">

                    <li class="menu-title">داشبورد</li>
                    <li>
                        <a href="/dashboard" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>

                    <li class="menu-title">صندوق دریافتی</li>
                    <li>
                        <a href="/inbox" class="waves-effect">
                            <i class="bx bx-inbox"></i>
                            <span>صندوق دریافتی</span>
                            <span class="badge rounded-pill bg-danger float-end" id="inbox-badge" style="display:none;"></span>
                        </a>
                    </li>

                    <li class="menu-title">گردش کار</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-task"></i>
                            <span>گردش کار</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/workflow">مدیریت گردش کار</a></li>
                            <li><a href="/workflow/create">ایجاد گردش کار</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">فروش</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span>سفارش‌ها</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/orders">همه سفارش‌ها</a></li>
                            <li><a href="/orders/create">سفارش جدید</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">مشتریان</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-user-detail"></i>
                            <span>مشتریان</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/customers">لیست مشتریان</a></li>
                            <li><a href="/customers/create">افزودن مشتری</a></li>
                            <li><a href="/customers/reports">گزارش خرید</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">محصولات</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-package"></i>
                            <span>محصولات</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/products">همه محصولات</a></li>
                            <li><a href="/products/create">افزودن محصول</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">بسته‌بندی</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-box"></i>
                            <span>بسته‌بندی</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/packaging">همه بسته‌بندی‌ها</a></li>
                            <li><a href="/packaging/create">افزودن بسته‌بندی</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">کاربران</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-user"></i>
                            <span>پرسنل</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/users">مدیریت پرسنل</a></li>
                            <li><a href="/users/create">افزودن پرسنل</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-shield"></i>
                            <span>نقش‌ها</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/roles">مدیریت نقش‌ها</a></li>
                            <li><a href="/roles/create">افزودن نقش</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">حساب کاربری</li>
                    <li>
                        <a href="/profile" class="waves-effect">
                            <i class="bx bx-user-circle"></i>
                            <span>پروفایل</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">@yield('page-title', 'داشبورد')</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/dashboard">خانه</a></li>
                                    @hasSection('breadcrumb')
                                        @yield('breadcrumb')
                                    @else
                                        <li class="breadcrumb-item active">@yield('page-title', 'داشبورد')</li>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                @yield('content')

            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        {{ date('Y') }} © سیستم ERP
                    </div>
                </div>
            </div>
        </footer>
    </div>

</div>

<!-- Right bar overlay -->
<div class="rightbar-overlay"></div>

<div class="toast-container" style="position:fixed;top:20px;left:20px;z-index:9999;">
    <div id="toast-box"></div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/persian-date.min.js') }}"></script>
<script src="{{ asset('assets/js/persian-datepicker.min.js') }}"></script>

<script>
    const token = localStorage.getItem('erp_token');
    const user = JSON.parse(localStorage.getItem('erp_user') || '{}');

    if (!token) { window.location.href = '/login'; }

    if (user.full_name) {
        document.getElementById('header-username').textContent = user.full_name;
    }

    window.apiCall = function(url, options = {}) {
        return fetch(url, {
            ...options,
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(options.headers || {})
            }
        }).then(res => res.json());
    };

    window.showToast = function(message, type = 'success') {
        const colors = { 'success': 'bg-success', 'error': 'bg-danger', 'warning': 'bg-warning', 'info': 'bg-info' };
        const id = 'toast-' + Date.now();
        document.getElementById('toast-box').insertAdjacentHTML('beforeend', `
            <div id="${id}" class="toast align-items-center text-white ${colors[type]} border-0 mb-2 show"
                role="alert" style="min-width:280px;">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        onclick="document.getElementById('${id}').remove()"></button>
                </div>
            </div>`);
        setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 4000);
    };

    document.getElementById('logout-btn').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('/api/v1/logout', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).finally(() => {
            localStorage.removeItem('erp_token');
            localStorage.removeItem('erp_user');
            window.location.href = '/login';
        });
    });

    document.querySelectorAll('#side-menu a').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('mm-active');
            link.closest('li')?.classList.add('mm-active');
        }
    });

    // بارگذاری آواتار از دیتابیس
    apiCall('/api/v1/profile').then(profile => {
        if (profile.avatar) {
            document.querySelector('.header-profile-user').src = profile.avatar;
        }
    }).catch(() => {});

    // badge صندوق دریافتی
    apiCall('/api/v1/inbox/my-tasks').then(tasks => {
        if (tasks.length > 0) {
            const badge = document.getElementById('inbox-badge');
            if (badge) { badge.textContent = tasks.length; badge.style.display = 'inline'; }
        }
    }).catch(() => {});

    // تبدیل به شمسی
    window.toJalali = function(dateStr) {
        if (!dateStr) return '-';
        try {
            return new Date(dateStr).toLocaleDateString('fa-IR', {
                year: 'numeric', month: '2-digit', day: '2-digit'
            });
        } catch(e) { return dateStr; }
    };
</script>

@stack('scripts')

</body>
</html>
