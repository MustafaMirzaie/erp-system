$.sessionTimeout({
    keepAliveUrl: "pages-starter.html",
    logoutButton: "خروج",
    logoutUrl: "auth-login.html",
    redirUrl: "auth-lock-screen.html",
    warnAfter: 3000,
    redirAfter: 30000,
    countdownMessage: "انتقال به صفحه بلاک در {timer} ثانیه."
});

$("#session-timeout-dialog [data-dismiss=modal]").attr("data-bs-dismiss", "modal");
