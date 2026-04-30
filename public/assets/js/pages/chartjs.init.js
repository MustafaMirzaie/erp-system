function getChartColorsArray(r) {
    if (null !== document.getElementById(r)) {
        var o = document.getElementById(r).getAttribute("data-colors");
        if (o) return (o = JSON.parse(o)).map(function(r) {
            var o = r.replace(" ", "");
            if (-1 === o.indexOf(",")) {
                var e = getComputedStyle(document.documentElement).getPropertyValue(o);
                return e || o
            }
            var t = r.split(",");
            return 2 != t.length ? o : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")"
        })
    }
}
Chart.defaults.borderColor = "rgba(133, 141, 152, 0.1)", Chart.defaults.color = "#858d98",
    function(p) {
        "use strict";

        function r() {}
        r.prototype.respChart = function(r, o, e, t) {
            var a = r.get(0).getContext("2d"),
                n = p(r).parent();

            function l() {
                r.attr("width", p(n).width());
                switch (o) {
                    case "خطی":
                        new Chart(a, {
                            type: "line",
                            data: e,
                            options: t
                        });
                        break;
                    case "دونات":
                        new Chart(a, {
                            type: "doughnut",
                            data: e,
                            options: t
                        });
                        break;
                    case "دایره‌ای":
                        new Chart(a, {
                            type: "pie",
                            data: e,
                            options: t
                        });
                        break;
                    case "ستونی":
                        new Chart(a, {
                            type: "bar",
                            data: e,
                            options: t
                        });
                        break;
                    case "رادار":
                        new Chart(a, {
                            type: "radar",
                            data: e,
                            options: t
                        });
                        break;
                    case "قطبی":
                        new Chart(a, {
                            data: e,
                            type: "polarArea",
                            options: t
                        })
                }
            }
            p(window).resize(l), l()
        }, r.prototype.init = function() {
            var r, o = getChartColorsArray("lineChart");
            o && (r = {
                labels: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه", "اوت", "سپتامبر", "اکتبر"],
                datasets: [{
                    label: "تحلیل فروش",
                    fill: !0,
                    lineTension: .5,
                    backgroundColor: o[0],
                    borderColor: o[1],
                    borderCapStyle: "butt",
                    borderDash: [],
                    borderDashOffset: 0,
                    borderJoinStyle: "miter",
                    pointBorderColor: o[1],
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: o[1],
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [65, 59, 80, 81, 56, 55, 40, 55, 30, 80]
                }, {
                    label: "درآمد ماهانه",
                    fill: !0,
                    lineTension: .5,
                    backgroundColor: o[2],
                    borderColor: o[3],
                    borderCapStyle: "butt",
                    borderDash: [],
                    borderDashOffset: 0,
                    borderJoinStyle: "miter",
                    pointBorderColor: o[3],
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: o[3],
                    pointHoverBorderColor: "#eef0f2",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [80, 23, 56, 65, 23, 35, 85, 25, 92, 36]
                }]
            }, this.respChart(p("#lineChart"), "خطی", r));
            var e, t = getChartColorsArray("doughnut");
            t && (e = {
                labels: ["رومیزی", "تبلت"],
                datasets: [{
                    data: [300, 210],
                    backgroundColor: t,
                    hoverBackgroundColor: t,
                    hoverBorderColor: "#fff"
                }]
            }, this.respChart(p("#doughnut"), "دونات", e));
            var a, n = getChartColorsArray("pie");
            n && (a = {
                labels: ["رومیزی", "تبلت"],
                datasets: [{
                    data: [300, 180],
                    backgroundColor: n,
                    hoverBackgroundColor: n,
                    hoverBorderColor: "#fff"
                }]
            }, this.respChart(p("#pie"), "دایره‌ای", a));
            var l, i = getChartColorsArray("bar");
            i && (l = {
                labels: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه"],
                datasets: [{
                    label: "تحلیل فروش",
                    backgroundColor: i[0],
                    borderColor: i[0],
                    borderWidth: 1,
                    hoverBackgroundColor: i[1],
                    hoverBorderColor: i[1],
                    data: [65, 59, 81, 45, 56, 80, 50, 20]
                }]
            }, this.respChart(p("#bar"), "ستونی", l));
            var d, s = getChartColorsArray("radar");
            s && (d = {
                labels: ["خوردن", "نوشیدن", "خوابیدن", "طراحی", "کدنویسی", "دوچرخه‌سواری", "دویدن"],
                datasets: [{
                    label: "رومیزی",
                    backgroundColor: s[0],
                    borderColor: s[1],
                    pointBackgroundColor: s[1],
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: s[1],
                    data: [65, 59, 90, 81, 56, 55, 40]
                }, {
                    label: "تبلت",
                    backgroundColor: s[2],
                    borderColor: s[3],
                    pointBackgroundColor: s[3],
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: s[3],
                    data: [28, 48, 40, 19, 96, 27, 100]
                }]
            }, this.respChart(p("#radar"), "رادار", d));
            var C, u = getChartColorsArray("polarArea");
            u && (C = {
                datasets: [{
                    data: [11, 16, 7, 18],
                    backgroundColor: u,
                    label: "داده‌های من",
                    hoverBorderColor: "#fff"
                }],
                labels: ["سری 1", "سری 2", "سری 3", "سری 4"]
            }, this.respChart(p("#polarArea"), "قطبی", C))
        }, p.ChartJs = new r, p.ChartJs.Constructor = r
    }(window.jQuery),
    function() {
        "use strict";
        window.jQuery.ChartJs.init()
    }();
