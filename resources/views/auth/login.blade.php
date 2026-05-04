<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>ورود | ERP System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app-rtl.min.css') }}" rel="stylesheet">
    <style>
        body { direction: rtl; text-align: right; }
        #username, #password { direction: ltr; text-align: left; }
        .toggle-password {
            cursor: pointer;
            border: 1px solid #ced4da;
            border-left: none;
            border-right: 1px solid #ced4da;
            background: #f8f9fa;
            padding: 0 12px;
            display: flex;
            align-items: center;
            border-radius: 0 4px 4px 0;
        }
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
                            <div id="error-alert" class="alert alert-danger" style="display:none;">
                                <span id="error-text"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">نام کاربری</label>
                                <input type="text" class="form-control" id="username"
                                       placeholder="نام کاربری را وارد کنید">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">رمز عبور</label>
                                <div class="d-flex">
                                    <span class="toggle-password" id="toggle-pwd">
                                        <i class="mdi mdi-eye-outline" id="eye-icon"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password"
                                           placeholder="رمز عبور را وارد کنید"
                                           style="border-radius: 4px 0 0 4px; border-right: none;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">تأیید امنیتی</label>
                                <div class="d-flex align-items-stretch gap-2" style="direction:ltr;">
                                    <span class="badge bg-primary d-flex align-items-center px-3"
                                          id="captcha-question" style="font-size:15px; min-width:80px; justify-content:center; border-radius:4px;">
                                    </span>
                                    <span class="d-flex align-items-center fw-bold fs-5">=</span>
                                    <input type="text" class="form-control" id="captcha-answer"
                                           placeholder="جواب" style="max-width:90px; text-align:center;">
                                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center"
                                            id="refresh-captcha" title="تغییر سوال" style="min-width:42px;">
                                        <i class="mdi mdi-refresh"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="remember-check">
                                <label class="form-check-label" for="remember-check">مرا به خاطر بسپار</label>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary waves-effect waves-light" id="login-btn" type="button">
                                    <span id="btn-normal">ورود</span>
                                    <span id="btn-loading" style="display:none;">در حال ورود...</span>
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
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();

    // ========== CAPTCHA ==========
    let captchaAnswer = 0;
    let failedAttempts = parseInt(localStorage.getItem('login_fails') || '0');
    let lockUntil = parseInt(localStorage.getItem('login_lock') || '0');

    function generateCaptcha() {
        const a = Math.floor(Math.random() * 10) + 1;
        const b = Math.floor(Math.random() * 10) + 1;
        const ops = ['+', '-', '×'];
        const op = ops[Math.floor(Math.random() * ops.length)];
        let answer;
        if (op === '+') answer = a + b;
        else if (op === '-') answer = Math.abs(a - b);
        else answer = a * b;
        const q = op === '-' ? `${Math.max(a,b)} - ${Math.min(a,b)}` : `${a} ${op} ${b}`;
        document.getElementById('captcha-question').textContent = q;
        document.getElementById('captcha-answer').value = '';
        captchaAnswer = answer;
    }

    document.getElementById('refresh-captcha').addEventListener('click', generateCaptcha);
    generateCaptcha();

    // ========== LOCK CHECK ==========
    function checkLock() {
        const now = Date.now();
        if (lockUntil > now) {
            const remaining = Math.ceil((lockUntil - now) / 1000);
            showError(`حساب موقتاً قفل شده. ${remaining} ثانیه دیگر تلاش کنید.`);
            document.getElementById('login-btn').disabled = true;
            setTimeout(checkLock, 1000);
            return true;
        } else if (lockUntil && lockUntil <= now) {
            localStorage.removeItem('login_lock');
            localStorage.removeItem('login_fails');
            failedAttempts = 0;
            document.getElementById('login-btn').disabled = false;
            document.getElementById('error-alert').style.display = 'none';
        }
        return false;
    }
    checkLock();

    // ========== HELPERS ==========
    function showError(msg) {
        const el = document.getElementById('error-alert');
        document.getElementById('error-text').textContent = msg;
        el.style.display = 'block';
    }

    // ========== TOGGLE PASSWORD ==========
    document.getElementById('toggle-pwd').onclick = function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'mdi mdi-eye-off-outline';
        } else {
            pwd.type = 'password';
            icon.className = 'mdi mdi-eye-outline';
        }
    };

    // ========== LOGIN ==========
    function doLogin() {
        if (checkLock()) return;

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const userCaptcha = parseInt(document.getElementById('captcha-answer').value);
        const errorAlert = document.getElementById('error-alert');
        const loginBtn = document.getElementById('login-btn');

        errorAlert.style.display = 'none';

        if (!username || !password) {
            showError('لطفاً نام کاربری و رمز عبور را وارد کنید.');
            return;
        }

        if (isNaN(userCaptcha) || userCaptcha !== captchaAnswer) {
            showError('پاسخ تأیید امنیتی اشتباه است.');
            generateCaptcha();
            return;
        }

        loginBtn.disabled = true;
        document.getElementById('btn-normal').style.display = 'none';
        document.getElementById('btn-loading').style.display = 'inline';

        fetch('/api/v1/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ username, password })
        })
            .then(r => r.json())
            .then(data => {
                if (data.token) {
                    // موفق — پاک کردن تلاش‌های ناموفق
                    localStorage.removeItem('login_fails');
                    localStorage.removeItem('login_lock');
                    localStorage.setItem('erp_token', data.token);
                    localStorage.setItem('erp_user', JSON.stringify(data.user));
                    window.location.href = '/dashboard';
                } else {
                    // ناموفق
                    failedAttempts++;
                    localStorage.setItem('login_fails', failedAttempts);

                    if (failedAttempts >= 5) {
                        const lockTime = Date.now() + (15 * 60 * 1000); // 15 دقیقه
                        localStorage.setItem('login_lock', lockTime);
                        lockUntil = lockTime;
                        showError('پس از 5 تلاش ناموفق، حساب شما به مدت 15 دقیقه قفل شد.');
                        loginBtn.disabled = true;
                        checkLock();
                    } else {
                        const remaining = 5 - failedAttempts;
                        showError(`نام کاربری یا رمز عبور اشتباه است. ${remaining} تلاش باقیمانده.`);
                        loginBtn.disabled = false;
                        document.getElementById('btn-normal').style.display = 'inline';
                        document.getElementById('btn-loading').style.display = 'none';
                        generateCaptcha();
                    }
                }
            })
            .catch(() => {
                showError('خطا در ارتباط با سرور');
                loginBtn.disabled = false;
                document.getElementById('btn-normal').style.display = 'inline';
                document.getElementById('btn-loading').style.display = 'none';
            });
    }

    document.getElementById('login-btn').addEventListener('click', doLogin);
    document.getElementById('password').addEventListener('keypress', e => {
        if (e.key === 'Enter') doLogin();
    });
    document.getElementById('captcha-answer').addEventListener('keypress', e => {
        if (e.key === 'Enter') doLogin();
    });
</script>
</body>
</html>
