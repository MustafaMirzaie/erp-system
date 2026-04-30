!function(w) {
    "use strict";

    function e() {}
    e.prototype.init = function() {
        var l = w("#event-modal"),
            t = w("#modal-title"),
            a = w("#form-event"),
            i = null,
            s = null,
            o = document.getElementsByClassName("needs-validation"),
            i = null,
            s = null,
            n = document.getElementById("locale-selector"),
            e = moment().format('jYYYY/jM/jD'),
            r = moment().jDate(),
            d = moment().jMonth(),
            c = moment().jYear();
        new FullCalendar.Draggable(document.getElementById("external-events"), {
            itemSelector: ".external-event",
            eventData: function(e) {
                return {
                    title: e.innerText,
                    className: w(e).data("class")
                }
            }
        });

        var v = [{
                title: "رویداد تمام روز",
                start: moment().startOf('day').add(9, 'hours').format(),
                end: moment().startOf('day').add(10, 'hours').format(),
            }, {
                title: "رویداد طولانی",
                start: moment().startOf('day').add(12, 'hours').format(),
                end: moment().startOf('day').add(14, 'hours').format(),
                className: "bg-warning"
            }, {
                id: 999,
                title: "رویداد تکراری",
                start: moment().startOf('day').add(13, 'hours').add(2, 'days').format(),
                end: moment().startOf('day').add(14, 'hours').add(2, 'days').format(),
                allDay: !1,
                className: "bg-info"
            }, {
                id: 999,
                title: "رویداد تکراری",
                start: moment().startOf('day').add(10, 'hours').add(3, 'days').format(),
                end: moment().startOf('day').add(11, 'hours').add(3, 'days').format(),
                allDay: !1,
                className: "bg-primary"
            }, {
                title: "جلسه",
                start: moment([c, d, r, 10, 30]).toDate(),
                allDay: !1,
                className: "bg-success"
            }, {
                title: "ناهار",
                start: moment([c, d, r, 12, 0]).toDate(),
                end: moment([c, d, r, 14, 0]).toDate(),
                allDay: !1,
                className: "bg-danger"
            }, {
                title: "جشن تولد",
                start: moment([c, d, r + 1, 19, 0]).toDate(),
                end: moment([c, d, r + 1, 22, 30]).toDate(),
                allDay: !1,
                className: "bg-success"
            }, {
                title: "کلیک برای گوگل",
                start: moment([c, d, 28]).toDate(),
                end: moment([c, d, 29]).toDate(),
                url: "http://google.com/",
                className: "bg-dark"
            }],
            u = document.getElementById("calendar");

        function m(e) {
            l.modal("show"), a.removeClass("was-validated"), a[0].reset(), w("#event-title").val(), w("#event-category").val(), t.text("افزودن رویداد"), s = e
        }
        var g = new FullCalendar.Calendar(u, {
            editable: !0,
            droppable: !0,
            selectable: !0,
            initialView: "dayGridMonth",
            themeSystem: "bootstrap",
            weekNumbers: !0,
            locale: "fa", 
            direction: 'rtl',
            headerToolbar: {
                left: "prev,next today",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                center: "title"
            },
            buttonText: {
                today: 'امروز',
                month: 'ماه',
                week: 'هفته',
                day: 'روز',
                list: 'لیست'
            },
            dayMaxEventRows: !0,
            views: {
                timeGrid: {
                    dayMaxEventRows: 5
                }
            },
            eventClick: function(e) {
                l.modal("show"), a[0].reset(), i = e.event, w("#event-title").val(i.title), w("#event-category").val(i.classNames[0]), s = null, t.text("ویرایش رویداد"), s = null
            },
            dateClick: function(e) {
                m(e)
            },
            events: v
        });
        g.render(), g.getAvailableLocaleCodes().forEach(function(e) {
            var t = document.createElement("option");
            t.value = e, t.selected = "fa" == e, t.innerText = e, n.appendChild(t)
        }), n.addEventListener("change", function() {
            this.value && g.setOption("locale", this.value)
        }), w(a).on("submit", function(e) {
            e.preventDefault();
            var t, a = w("#event-title").val(),
                n = w("#event-category").val();
            !1 === o[0].checkValidity() ? (e.preventDefault(), e.stopPropagation(), o[0].classList.add("was-validated")) : (i ? (i.setProp("title", a), i.setProp("classNames", [n])) : (t = {
                title: a,
                start: s.date,
                allDay: s.allDay,
                className: n
            }, g.addEvent(t)), l.modal("hide"))
        }), w("#btn-delete-event").on("click", function(e) {
            i && (i.remove(), i = null, l.modal("hide"))
        }), w("#btn-new-event").on("click", function(e) {
            m({
                date: moment().toDate(),
                allDay: !0
            })
        })
    }, w.CalendarPage = new e, w.CalendarPage.Constructor = e
}(window.jQuery),
function() {
    "use strict";
    window.jQuery.CalendarPage.init()
}();
