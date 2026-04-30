! function(t) {
    "use strict";

    function e() {}
    e.prototype.init = function() {
        t("#sa-basic").on("click", function() {
            Swal.fire({
                title: "هر احمقی می‌تواند از کامپیوتر استفاده کند",
                confirmButtonColor: "#556ee6"
            })
        }), t("#sa-title").click(function() {
            Swal.fire({
                title: "اینترنت؟",
                text: "این چیز هنوز وجود دارد؟",
                icon: "question",
                confirmButtonColor: "#556ee6"
            })
        }), t("#sa-success").click(function() {
            Swal.fire({
                title: "کار خوبی انجام دادید!",
                text: "شما دکمه را کلیک کردید!",
                icon: "success",
                showCancelButton: !0,
                confirmButtonColor: "#556ee6",
                cancelButtonColor: "#f46a6a"
            })
        }), t("#sa-warning").click(function() {
            Swal.fire({
                title: "آیا مطمئن هستید؟",
                text: "شما قادر به بازگشت این کار نخواهید بود!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "بله، حذف کنید!"
            }).then(function(t) {
                t.value && Swal.fire("حذف شد!", "فایل شما حذف شده است.", "success")
            })
        }), t("#sa-params").click(function() {
            Swal.fire({
                title: "آیا مطمئن هستید؟",
                text: "شما قادر به بازگشت این کار نخواهید بود!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "بله، حذف کنید!",
                cancelButtonText: "خیر، لغو کنید!",
                customClass: {
                    confirmButton: "btn btn-success mt-2",
                    cancelButton: "btn btn-danger ms-2 mt-2"
                },
                buttonsStyling: !1
            }).then(function(t) {
                t.value ? Swal.fire({
                    title: "حذف شد!",
                    text: "فایل شما حذف شده است.",
                    icon: "success"
                }) : t.dismiss === Swal.DismissReason.cancel && Swal.fire({
                    title: "لغو شد",
                    text: "فایل خیالی شما ایمن است :)",
                    icon: "error"
                })
            })
        }), t("#sa-image").click(function() {
            Swal.fire({
                title: "خوبه!",
                text: "مودال با تصویر سفارشی.",
                imageUrl: "assets/images/logo-dark.png",
                imageHeight: 20,
                confirmButtonColor: "#556ee6",
                animation: !1
            })
        }), t("#sa-close").click(function() {
            var e;
            Swal.fire({
                title: "هشدار خودکار بسته شود!",
                html: "من در <strong></strong> ثانیه بسته خواهم شد.",
                timer: 2e3,
                confirmButtonColor: "#556ee6",
                onBeforeOpen: function() {
                    Swal.showLoading(), e = setInterval(function() {
                        Swal.getContent().querySelector("strong").textContent = Swal.getTimerLeft()
                    }, 100)
                },
                onClose: function() {
                    clearInterval(e)
                }
            }).then(function(e) {
                e.dismiss === Swal.DismissReason.timer && console.log("با تایمر بسته شدم")
            })
        }), t("#custom-html-alert").click(function() {
            Swal.fire({
                title: "<i>HTML</i> <u>مثال</u>",
                icon: "info",
                html: 'می‌توانید از <b>متن‌های پررنگ</b>، <a href="//محمد تقی پور.in/">لینک‌ها</a> و سایر تگ‌های HTML استفاده کنید',
                showCloseButton: !0,
                showCancelButton: !0,
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger ms-1",
                confirmButtonColor: "#47bd9a",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: '<i class="fas fa-thumbs-up me-1"></i> عالی!',
                cancelButtonText: '<i class="fas fa-thumbs-down"></i>'
            })
        }), t("#sa-position").click(function() {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "کار شما ذخیره شده است",
                showConfirmButton: !1,
                timer: 1500
            })
        }), t("#custom-padding-width-alert").click(function() {
            Swal.fire({
                title: "عرض، پر کردن و پس‌زمینه سفارشی.",
                width: 600,
                padding: 100,
                confirmButtonColor: "#556ee6",
                background: "#fff url(//subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/geometry.png)"
            })
        }), t("#ajax-alert").click(function() {
            Swal.fire({
                title: "برای ارسال ایمیل برای اجرای درخواست ajax",
                input: "email",
                showCancelButton: !0,
                confirmButtonText: "ارسال",
                showLoaderOnConfirm: !0,
                confirmButtonColor: "#556ee6",
                cancelButtonColor: "#f46a6a",
                preConfirm: function(n) {
                    return new Promise(function(t, e) {
                        setTimeout(function() {
                            "taken@example.com" === n ? e("این ایمیل قبلاً گرفته شده است.") : t()
                        }, 2e3)
                    })
                },
                allowOutsideClick: !1
            }).then(function(t) {
                Swal.fire({
                    icon: "success",
                    title: "درخواست ajax انجام شد!",
                    html: "ایمیل ارسال شده: " + t,
                    confirmButtonColor: "#556ee6"
                })
            })
        })
    }, t.SweetAlert = new e, t.SweetAlert.Constructor = e
}(window.jQuery),
function() {
    "use strict";
    window.jQuery.SweetAlert.init()
}();
