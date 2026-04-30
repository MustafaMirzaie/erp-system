<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>ورود | ERP System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body { direction: rtl; text-align: right; }
        .form-label { float: right; }
        .error-message { color: #f46a6a; font-size: 13px; margin-top: 5px; }
        .loading { display: none; }
    </style>
</head>
<body>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">خوش آمدید!</h5>
                                    <p>وارد شوید تا به سیستم ERP ادامه دهید.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="auth-logo">
                            <a href="#" class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo.svg') }}" alt="" class="rounded-circle" height="55">
                                        </span>
                                </div>
                            </a>
                        </div>
                        <div class="p-2">
                            <!-- Error Message -->
                            <div id="error-alert" class="alert alert-danger" style="display:none;">
                                <span id="error-text"></span>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">نام کاربری</label>
                                <input type="text" class="form-control" id="username" placeholder="نام کاربری را وارد کنید">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">رمز عبور</label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control" id="password" placeholder="رمز عبور را وارد کنید">
                                    <button class="btn btn-light" type="button" id="password-addon">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-check">
                                <label class="form-check-label" for="remember-check">مرا به خاطر بسپار</label>
                            </div>

                            <div class="mt-3 d-grid">
                                <button class="btn btn-primary waves-effect waves-light" id="login-btn" type="button">
                                    <span class="normal-text">ورود</span>
                                    <span class="loading">در حال ورود...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <p>© <span id="year"></span> سیستم ERP</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();

    // نمایش/مخفی رمز عبور
    document.getElementById('password-addon').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = this.querySelector('i');

        if (pwd.getAttribute('type') === 'password') {
            pwd.setAttribute('type', 'text');
            icon.classList.remove('mdi-eye-outline');
            icon.classList.add('mdi-eye-off-outline');
        } else {
            pwd.setAttribute('type', 'password');
            icon.classList.remove('mdi-eye-off-outline');
            icon.classList.add('mdi-eye-outline');
        }
    });

    // ورود
    document.getElementById('login-btn').addEventListener('click', function() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const errorAlert = document.getElementById('error-alert');
        const errorText = document.getElementById('error-text');
        const btn = this;

        // validation ساده
        if (!username || !password) {
            errorAlert.style.display = 'block';
            errorText.textContent = 'لطفاً نام کاربری و رمز عبور را وارد کنید.';
            return;
        }

        // loading state
        btn.disabled = true;
        btn.querySelector('.normal-text').style.display = 'none';
        btn.querySelector('.loading').style.display = 'inline';
        errorAlert.style.display = 'none';

        // API call
        fetch('/api/v1/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ username, password })
        })
            .then(res => res.json())
            .then(data => {
                if (data.token) {
                    // ذخیره token
                    localStorage.setItem('erp_token', data.token);
                    localStorage.setItem('erp_user', JSON.stringify(data.user));
                    // redirect به dashboard
                    window.location.href = '/dashboard';
                } else {
                    errorAlert.style.display = 'block';
                    errorText.textContent = data.message || 'خطا در ورود';
                    btn.disabled = false;
                    btn.querySelector('.normal-text').style.display = 'inline';
                    btn.querySelector('.loading').style.display = 'none';
                }
            })
            .catch(() => {
                errorAlert.style.display = 'block';
                errorText.textContent = 'خطا در ارتباط با سرور';
                btn.disabled = false;
                btn.querySelector('.normal-text').style.display = 'inline';
                btn.querySelector('.loading').style.display = 'none';
            });
    });

    // Enter key
    document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('login-btn').click();
        }
    });
</script>
</body>
</html>
