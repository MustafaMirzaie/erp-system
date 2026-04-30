function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var t = document.getElementById(e).getAttribute("data-colors");
        if (t) return (t = JSON.parse(t)).map(function(e) {
            var t = e.replace(" ", "");
            if (-1 === t.indexOf(",")) {
                var a = getComputedStyle(document.documentElement).getPropertyValue(t);
                return a || t
            }
            var r = e.split(",");
            return 2 != r.length ? t : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(r[0]) + "," + r[1] + ")"
        })
    }
}
var areaSparkline1Colors = getChartColorsArray("area-sparkline-chart-1");
areaSparkline1Colors && (options = {
    series: [{
        name: "BTC",
        data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14]
    }],
    chart: {
        type: "area",
        height: 40,
        sparkline: {
            enabled: !0
        }
    },
    stroke: {
        curve: "smooth",
        width: 2
    },
    colors: areaSparkline1Colors,
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            inverseColors: !1,
            opacityFrom: .45,
            opacityTo: .05,
            stops: [25, 100, 100, 100]
        }
    },
    tooltip: {
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        marker: {
            show: !1
        }
    }
}, (chart = new ApexCharts(document.querySelector("#area-sparkline-chart-1"), options)).render());
var areaSparkline2Colors = getChartColorsArray("area-sparkline-chart-2");
areaSparkline2Colors && (options = {
    series: [{
        name: "ETH",
        data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
    }],
    chart: {
        type: "area",
        height: 40,
        sparkline: {
            enabled: !0
        }
    },
    stroke: {
        curve: "smooth",
        width: 2
    },
    colors: areaSparkline2Colors,
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            inverseColors: !1,
            opacityFrom: .45,
            opacityTo: .05,
            stops: [25, 100, 100, 100]
        }
    },
    tooltip: {
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        marker: {
            show: !1
        }
    }
}, (chart = new ApexCharts(document.querySelector("#area-sparkline-chart-2"), options)).render());
var areaSparkline3Colors = getChartColorsArray("area-sparkline-chart-3");
areaSparkline3Colors && (options = {
    series: [{
        name: "LTC",
        data: [35, 53, 93, 47, 54, 24, 47, 75, 65, 19, 14]
    }],
    chart: {
        type: "area",
        height: 40,
        sparkline: {
            enabled: !0
        }
    },
    stroke: {
        curve: "smooth",
        width: 2
    },
    colors: areaSparkline3Colors,
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            inverseColors: !1,
            opacityFrom: .45,
            opacityTo: .05,
            stops: [25, 100, 100, 100]
        }
    },
    tooltip: {
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        marker: {
            show: !1
        }
    }
}, (chart = new ApexCharts(document.querySelector("#area-sparkline-chart-3"), options)).render());
var walletOptions, walletBalanceColors = getChartColorsArray("wallet-balance-chart");
walletBalanceColors && (walletOptions = {
    series: [76, 67, 61],
    chart: {
        height: 302,
        type: "radialBar"
    },
    plotOptions: {
        radialBar: {
            offsetY: 0,
            startAngle: 0,
            endAngle: 270,
            hollow: {
                margin: 5,
                size: "35%",
                background: "transparent",
                image: void 0
            },
            track: {
                show: !0,
                startAngle: void 0,
                endAngle: void 0,
                background: "#f2f2f2",
                strokeWidth: "97%",
                opacity: 1,
                margin: 12,
                dropShadow: {
                    enabled: !1,
                    top: 0,
                    left: 0,
                    blur: 3,
                    opacity: .5
                }
            },
            dataLabels: {
                name: {
                    show: !0,
                    fontSize: "16px",
                    fontWeight: 600,
                    offsetY: -10
                },
                value: {
                    show: !0,
                    fontSize: "14px",
                    offsetY: 4,
                    formatter: function(e) {
                        return e + "%"
                    }
                },
                total: {
                    show: !0,
                    label: "جمع",
                    color: "#373d3f",
                    fontSize: "16px",
                    fontFamily: void 0,
                    fontWeight: 600,
                    formatter: function(e) {
                        return e.globals.seriesTotals.reduce(function(e, t) {
                            return e + t
                        }, 0) + "%"
                    }
                }
            }
        }
    },
    stroke: {
        lineCap: "round"
    },
    colors: walletBalanceColors,
    labels: ["Ethereum", "Bitcoin", "Litecoin"],
    legend: {
        show: !1
    }
}, (chart = new ApexCharts(document.querySelector("#wallet-balance-chart"), walletOptions)).render());
// تابع برای تبدیل تاریخ میلادی به تاریخ شمسی
function convertToJalaaliDate(date) {
    return moment(date).format("YYYY/MM/DD");
}

// تنظیمات نمودار
var options = {
    series: [{
        name: "BTC",
        data: [
            [moment("1390/12/10", "jYYYY/jMM/jDD").valueOf(), 30.95],
            [moment("1390/12/11", "jYYYY/jMM/jDD").valueOf(), 31.34],
            [moment("1390/12/12", "jYYYY/jMM/jDD").valueOf(), 31.18],
            [moment("1390/12/13", "jYYYY/jMM/jDD").valueOf(), 31.05],
            [moment("1390/12/17", "jYYYY/jMM/jDD").valueOf(), 31],
            [moment("1390/12/18", "jYYYY/jMM/jDD").valueOf(), 30.95],
            [moment("1390/12/19", "jYYYY/jMM/jDD").valueOf(), 31.24],
            [moment("1390/12/20", "jYYYY/jMM/jDD").valueOf(), 31.29],
            [moment("1390/12/21", "jYYYY/jMM/jDD").valueOf(), 31.85],
            [moment("1390/12/25", "jYYYY/jMM/jDD").valueOf(), 31.86],
            [moment("1390/12/26", "jYYYY/jMM/jDD").valueOf(), 32.28],
            [moment("1390/12/27", "jYYYY/jMM/jDD").valueOf(), 32.1],
            [moment("1390/12/28", "jYYYY/jMM/jDD").valueOf(), 32.65],
            [moment("1390/12/29", "jYYYY/jMM/jDD").valueOf(), 32.21],
            [moment("1391/01/03", "jYYYY/jMM/jDD").valueOf(), 32.35],
            [moment("1391/01/04", "jYYYY/jMM/jDD").valueOf(), 32.44],
            [moment("1391/01/05", "jYYYY/jMM/jDD").valueOf(), 32.46],
            [moment("1391/01/06", "jYYYY/jMM/jDD").valueOf(), 32.86],
            [moment("1391/01/07", "jYYYY/jMM/jDD").valueOf(), 32.75],
            [moment("1391/01/11", "jYYYY/jMM/jDD").valueOf(), 32.54],
            [moment("1391/01/12", "jYYYY/jMM/jDD").valueOf(), 32.33],
            [moment("1391/01/13", "jYYYY/jMM/jDD").valueOf(), 32.97],
            [moment("1391/01/14", "jYYYY/jMM/jDD").valueOf(), 33.41],
            [moment("1391/01/18", "jYYYY/jMM/jDD").valueOf(), 33.27],
            [moment("1391/01/19", "jYYYY/jMM/jDD").valueOf(), 33.27],
            [moment("1391/01/20", "jYYYY/jMM/jDD").valueOf(), 32.89],
            [moment("1391/01/21", "jYYYY/jMM/jDD").valueOf(), 33.1],
            [moment("1391/01/22", "jYYYY/jMM/jDD").valueOf(), 33.73],
            [moment("1391/01/26", "jYYYY/jMM/jDD").valueOf(), 33.22],
            [moment("1391/01/27", "jYYYY/jMM/jDD").valueOf(), 31.99],
            [moment("1391/01/28", "jYYYY/jMM/jDD").valueOf(), 32.41],
            [moment("1391/01/29", "jYYYY/jMM/jDD").valueOf(), 33.05],
            [moment("1391/02/01", "jYYYY/jMM/jDD").valueOf(), 33.64],
            [moment("1391/02/02", "jYYYY/jMM/jDD").valueOf(), 33.56],
            [moment("1391/02/03", "jYYYY/jMM/jDD").valueOf(), 34.22],
            [moment("1391/02/04", "jYYYY/jMM/jDD").valueOf(), 33.77],
            [moment("1391/02/05", "jYYYY/jMM/jDD").valueOf(), 34.17],
            [moment("1391/02/06", "jYYYY/jMM/jDD").valueOf(), 33.82],
            [moment("1391/02/10", "jYYYY/jMM/jDD").valueOf(), 34.51],
            [moment("1391/02/11", "jYYYY/jMM/jDD").valueOf(), 33.16],
            [moment("1391/02/12", "jYYYY/jMM/jDD").valueOf(), 33.56],
            [moment("1391/02/13", "jYYYY/jMM/jDD").valueOf(), 33.71],
            [moment("1391/02/14", "jYYYY/jMM/jDD").valueOf(), 33.81],
            [moment("1391/02/15", "jYYYY/jMM/jDD").valueOf(), 34.4],
            [moment("1391/02/16", "jYYYY/jMM/jDD").valueOf(), 34.63],
            [moment("1391/02/17", "jYYYY/jMM/jDD").valueOf(), 34.46],
            [moment("1391/02/18", "jYYYY/jMM/jDD").valueOf(), 34.48],
            [moment("1391/02/19", "jYYYY/jMM/jDD").valueOf(), 34.31],
            [moment("1391/02/23", "jYYYY/jMM/jDD").valueOf(), 34.7],
            [moment("1391/02/24", "jYYYY/jMM/jDD").valueOf(), 34.31],
            [moment("1391/02/25", "jYYYY/jMM/jDD").valueOf(), 33.46],
            [moment("1391/02/26", "jYYYY/jMM/jDD").valueOf(), 33.59],
            [moment("1391/03/01", "jYYYY/jMM/jDD").valueOf(), 33.22],
            [moment("1391/03/02", "jYYYY/jMM/jDD").valueOf(), 32.61],
            [moment("1391/03/03", "jYYYY/jMM/jDD").valueOf(), 33.01],
            [moment("1391/03/04", "jYYYY/jMM/jDD").valueOf(), 33.55],
            [moment("1391/03/05", "jYYYY/jMM/jDD").valueOf(), 33.18],
            [moment("1391/03/09", "jYYYY/jMM/jDD").valueOf(), 32.84],
            [moment("1391/03/10", "jYYYY/jMM/jDD").valueOf(), 33.84],
            [moment("1391/03/11", "jYYYY/jMM/jDD").valueOf(), 33.39],
            [moment("1391/03/12", "jYYYY/jMM/jDD").valueOf(), 32.91],
            [moment("1391/03/13", "jYYYY/jMM/jDD").valueOf(), 33.06],
            [moment("1391/03/17", "jYYYY/jMM/jDD").valueOf(), 32.62],
            [moment("1391/03/18", "jYYYY/jMM/jDD").valueOf(), 32.4],
            [moment("1391/03/19", "jYYYY/jMM/jDD").valueOf(), 33.13],
            [moment("1391/03/20", "jYYYY/jMM/jDD").valueOf(), 33.26],
            [moment("1391/03/21", "jYYYY/jMM/jDD").valueOf(), 33.58],
            [moment("1391/03/25", "jYYYY/jMM/jDD").valueOf(), 33.55],
            [moment("1391/03/26", "jYYYY/jMM/jDD").valueOf(), 33.77],
            [moment("1391/03/27", "jYYYY/jMM/jDD").valueOf(), 33.76],
            [moment("1391/03/28", "jYYYY/jMM/jDD").valueOf(), 33.32],
            [moment("1391/03/29", "jYYYY/jMM/jDD").valueOf(), 32.46],
            [moment("1391/04/03", "jYYYY/jMM/jDD").valueOf(), 32.23],
            [moment("1391/04/04", "jYYYY/jMM/jDD").valueOf(), 32.64],
            [moment("1391/04/05", "jYYYY/jMM/jDD").valueOf(), 33.14],
            [moment("1391/04/06", "jYYYY/jMM/jDD").valueOf(), 32.94],
            [moment("1391/04/10", "jYYYY/jMM/jDD").valueOf(), 32.47],
            [moment("1391/04/11", "jYYYY/jMM/jDD").valueOf(), 31.98],
            [moment("1391/04/12", "jYYYY/jMM/jDD").valueOf(), 31.94],
            [moment("1391/04/13", "jYYYY/jMM/jDD").valueOf(), 32.37],
            [moment("1391/04/14", "jYYYY/jMM/jDD").valueOf(), 32.32],
            [moment("1391/04/17", "jYYYY/jMM/jDD").valueOf(), 32.17],
            [moment("1391/04/18", "jYYYY/jMM/jDD").valueOf(), 32.37],
            [moment("1391/04/19", "jYYYY/jMM/jDD").valueOf(), 32.42],
            [moment("1391/04/20", "jYYYY/jMM/jDD").valueOf(), 33.05],
            [moment("1391/04/24", "jYYYY/jMM/jDD").valueOf(), 33.29],
            [moment("1391/04/25", "jYYYY/jMM/jDD").valueOf(), 33.32],
            [moment("1391/04/26", "jYYYY/jMM/jDD").valueOf(), 33.1],
            [moment("1391/04/27", "jYYYY/jMM/jDD").valueOf(), 32.91],
            [moment("1391/05/01", "jYYYY/jMM/jDD").valueOf(), 32.64],
            [moment("1391/05/02", "jYYYY/jMM/jDD").valueOf(), 32.32],
            [moment("1391/05/03", "jYYYY/jMM/jDD").valueOf(), 32.24],
            [moment("1391/05/04", "jYYYY/jMM/jDD").valueOf(), 32.85],
            [moment("1391/05/05", "jYYYY/jMM/jDD").valueOf(), 32.64],
            [moment("1391/05/08", "jYYYY/jMM/jDD").valueOf(), 32.31],
            [moment("1391/05/09", "jYYYY/jMM/jDD").valueOf(), 32.11],
            [moment("1391/05/10", "jYYYY/jMM/jDD").valueOf(), 31.71],
            [moment("1391/05/11", "jYYYY/jMM/jDD").valueOf(), 31.85],
            [moment("1391/05/15", "jYYYY/jMM/jDD").valueOf(), 31.1],
            [moment("1391/05/16", "jYYYY/jMM/jDD").valueOf(), 31.7],
            [moment("1391/05/17", "jYYYY/jMM/jDD").valueOf(), 31.31],
            [moment("1391/05/18", "jYYYY/jMM/jDD").valueOf(), 31.33],
            [moment("1391/05/19", "jYYYY/jMM/jDD").valueOf(), 31.37],
            [moment("1391/05/22", "jYYYY/jMM/jDD").valueOf(), 30.93],
            [moment("1391/05/23", "jYYYY/jMM/jDD").valueOf(), 30.96],
            [moment("1391/05/24", "jYYYY/jMM/jDD").valueOf(), 30.83],
            [moment("1391/05/25", "jYYYY/jMM/jDD").valueOf(), 30.81],
            [moment("1391/05/29", "jYYYY/jMM/jDD").valueOf(), 31.48],
            [moment("1391/05/30", "jYYYY/jMM/jDD").valueOf(), 31.11],
            [moment("1391/06/01", "jYYYY/jMM/jDD").valueOf(), 30.71],
            [moment("1391/06/02", "jYYYY/jMM/jDD").valueOf(), 30.01],
            [moment("1391/06/05", "jYYYY/jMM/jDD").valueOf(), 29.35],
            [moment("1391/06/06", "jYYYY/jMM/jDD").valueOf(), 29.63],
            [moment("1391/06/07", "jYYYY/jMM/jDD").valueOf(), 29.6],
            [moment("1391/06/08", "jYYYY/jMM/jDD").valueOf(), 29.59],
            [moment("1391/06/12", "jYYYY/jMM/jDD").valueOf(), 29.61],
            [moment("1391/06/13", "jYYYY/jMM/jDD").valueOf(), 29.66],
            [moment("1391/06/14", "jYYYY/jMM/jDD").valueOf(), 29.67],
            [moment("1391/06/15", "jYYYY/jMM/jDD").valueOf(), 29.75],
            [moment("1391/06/16", "jYYYY/jMM/jDD").valueOf(), 29.79],
            [moment("1391/06/19", "jYYYY/jMM/jDD").valueOf(), 30.35],
            [moment("1391/06/20", "jYYYY/jMM/jDD").valueOf(), 30.38],
            [moment("1391/06/21", "jYYYY/jMM/jDD").valueOf(), 30.39],
            [moment("1391/06/22", "jYYYY/jMM/jDD").valueOf(), 30.48],
            [moment("1391/06/23", "jYYYY/jMM/jDD").valueOf(), 30.08],
            [moment("1391/06/26", "jYYYY/jMM/jDD").valueOf(), 29.84],
            [moment("1391/06/27", "jYYYY/jMM/jDD").valueOf(), 30.16],
            [moment("1391/06/28", "jYYYY/jMM/jDD").valueOf(), 29.62],
            [moment("1391/06/29", "jYYYY/jMM/jDD").valueOf(), 29.81],
            [moment("1391/07/03", "jYYYY/jMM/jDD").valueOf(), 29.7],
            [moment("1391/07/04", "jYYYY/jMM/jDD").valueOf(), 29.55],
            [moment("1391/07/05", "jYYYY/jMM/jDD").valueOf(), 29.47],
            [moment("1391/07/06", "jYYYY/jMM/jDD").valueOf(), 29.6],
            [moment("1391/07/10", "jYYYY/jMM/jDD").valueOf(), 29.7],
            [moment("1391/07/11", "jYYYY/jMM/jDD").valueOf(), 29.81],
            [moment("1391/07/12", "jYYYY/jMM/jDD").valueOf(), 29.47],
            [moment("1391/07/13", "jYYYY/jMM/jDD").valueOf(), 29.46],
            [moment("1391/07/17", "jYYYY/jMM/jDD").valueOf(), 28.76],
            [moment("1391/07/18", "jYYYY/jMM/jDD").valueOf(), 28.82],
            [moment("1391/07/19", "jYYYY/jMM/jDD").valueOf(), 28.88],
            [moment("1391/07/20", "jYYYY/jMM/jDD").valueOf(), 28.82],
            [moment("1391/07/23", "jYYYY/jMM/jDD").valueOf(), 28.41],
            [moment("1391/07/24", "jYYYY/jMM/jDD").valueOf(), 28.31],
            [moment("1391/07/25", "jYYYY/jMM/jDD").valueOf(), 28.41],
            [moment("1391/07/26", "jYYYY/jMM/jDD").valueOf(), 28.26],
            [moment("1391/07/27", "jYYYY/jMM/jDD").valueOf(), 28.54],
            [moment("1391/07/31", "jYYYY/jMM/jDD").valueOf(), 28.54],
            [moment("1391/08/01", "jYYYY/jMM/jDD").valueOf(), 28.98],
            [moment("1391/08/02", "jYYYY/jMM/jDD").valueOf(), 28.86],
            [moment("1391/08/03", "jYYYY/jMM/jDD").valueOf(), 28.85],
            [moment("1391/08/07", "jYYYY/jMM/jDD").valueOf(), 29.3],
            [moment("1391/08/08", "jYYYY/jMM/jDD").valueOf(), 29.28],
            [moment("1391/08/09", "jYYYY/jMM/jDD").valueOf(), 29.38],
            [moment("1391/08/10", "jYYYY/jMM/jDD").valueOf(), 29.3],
            [moment("1391/08/14", "jYYYY/jMM/jDD").valueOf(), 29.28],
            [moment("1391/08/15", "jYYYY/jMM/jDD").valueOf(), 29.16],
            [moment("1391/08/16", "jYYYY/jMM/jDD").valueOf(), 29.2],
            [moment("1391/08/17", "jYYYY/jMM/jDD").valueOf(), 29.26],
            [moment("1391/08/20", "jYYYY/jMM/jDD").valueOf(), 29.13],
            [moment("1391/08/21", "jYYYY/jMM/jDD").valueOf(), 29.11],
            [moment("1391/08/22", "jYYYY/jMM/jDD").valueOf(), 29.03],
            [moment("1391/08/23", "jYYYY/jMM/jDD").valueOf(), 29.05],
            [moment("1391/08/24", "jYYYY/jMM/jDD").valueOf(), 29.23],
            [moment("1391/08/27", "jYYYY/jMM/jDD").valueOf(), 29.24],
            [moment("1391/08/28", "jYYYY/jMM/jDD").valueOf(), 29.24],
            [moment("1391/08/29", "jYYYY/jMM/jDD").valueOf(), 29.35],
            [moment("1391/08/30", "jYYYY/jMM/jDD").valueOf(), 29.16],
            [moment("1391/09/01", "jYYYY/jMM/jDD").valueOf(), 29.05],
            [moment("1391/09/02", "jYYYY/jMM/jDD").valueOf(), 29.01],
            [moment("1391/09/03", "jYYYY/jMM/jDD").valueOf(), 28.84],
            [moment("1391/09/04", "jYYYY/jMM/jDD").valueOf(), 28.92],
            [moment("1391/09/05", "jYYYY/jMM/jDD").valueOf(), 28.83],
            [moment("1391/09/08", "jYYYY/jMM/jDD").valueOf(), 28.61],
            [moment("1391/09/09", "jYYYY/jMM/jDD").valueOf(), 28.46],
            [moment("1391/09/10", "jYYYY/jMM/jDD").valueOf(), 28.41],
            [moment("1391/09/11", "jYYYY/jMM/jDD").valueOf(), 28.36],
            [moment("1391/09/12", "jYYYY/jMM/jDD").valueOf(), 28.42],
            [moment("1391/09/15", "jYYYY/jMM/jDD").valueOf(), 28.63],
            [moment("1391/09/16", "jYYYY/jMM/jDD").valueOf(), 28.73],
            [moment("1391/09/17", "jYYYY/jMM/jDD").valueOf(), 28.64],
            [moment("1391/09/18", "jYYYY/jMM/jDD").valueOf(), 28.84],
            [moment("1391/09/19", "jYYYY/jMM/jDD").valueOf(), 28.91],
            [moment("1391/09/22", "jYYYY/jMM/jDD").valueOf(), 28.84],
            [moment("1391/09/23", "jYYYY/jMM/jDD").valueOf(), 28.86],
            [moment("1391/09/24", "jYYYY/jMM/jDD").valueOf(), 28.85],
            [moment("1391/09/25", "jYYYY/jMM/jDD").valueOf(), 28.87],
            [moment("1391/09/26", "jYYYY/jMM/jDD").valueOf(), 28.85],
            [moment("1391/09/29", "jYYYY/jMM/jDD").valueOf(), 28.92],
            [moment("1391/09/30", "jYYYY/jMM/jDD").valueOf(), 28.92],
            [moment("1391/10/01", "jYYYY/jMM/jDD").valueOf(), 29.01],
            [moment("1391/10/02", "jYYYY/jMM/jDD").valueOf(), 29.02],
            [moment("1391/10/06", "jYYYY/jMM/jDD").valueOf(), 29.12],
            [moment("1391/10/07", "jYYYY/jMM/jDD").valueOf(), 29.01],
            [moment("1391/10/08", "jYYYY/jMM/jDD").valueOf(), 28.86],
            [moment("1391/10/09", "jYYYY/jMM/jDD").valueOf(), 28.81],
            [moment("1391/10/10", "jYYYY/jMM/jDD").valueOf(), 28.75],
            [moment("1391/10/13", "jYYYY/jMM/jDD").valueOf(), 28.9],
            [moment("1391/10/14", "jYYYY/jMM/jDD").valueOf(), 28.85],
            [moment("1391/10/15", "jYYYY/jMM/jDD").valueOf(), 28.96],
            [moment("1391/10/16", "jYYYY/jMM/jDD").valueOf(), 29.01],
            [moment("1391/10/17", "jYYYY/jMM/jDD").valueOf(), 28.94],
            [moment("1391/10/20", "jYYYY/jMM/jDD").valueOf(), 29.04],
            [moment("1391/10/21", "jYYYY/jMM/jDD").valueOf(), 29.09],
            [moment("1391/10/22", "jYYYY/jMM/jDD").valueOf(), 29.05],
            [moment("1391/10/23", "jYYYY/jMM/jDD").valueOf(), 29.06],
            [moment("1391/10/24", "jYYYY/jMM/jDD").valueOf(), 29.0],
            [moment("1391/10/27", "jYYYY/jMM/jDD").valueOf(), 28.91],
            [moment("1391/10/28", "jYYYY/jMM/jDD").valueOf(), 28.83],
            [moment("1391/10/29", "jYYYY/jMM/jDD").valueOf(), 28.9],
            [moment("1391/10/30", "jYYYY/jMM/jDD").valueOf(), 28.81],
            [moment("1391/11/01", "jYYYY/jMM/jDD").valueOf(), 28.88],
            [moment("1391/11/02", "jYYYY/jMM/jDD").valueOf(), 28.81],
            [moment("1391/11/03", "jYYYY/jMM/jDD").valueOf(), 28.84],
            [moment("1391/11/04", "jYYYY/jMM/jDD").valueOf(), 28.83],
            [moment("1391/11/07", "jYYYY/jMM/jDD").valueOf(), 28.75],
            [moment("1391/11/08", "jYYYY/jMM/jDD").valueOf(), 28.64],
            [moment("1391/11/09", "jYYYY/jMM/jDD").valueOf(), 28.61],
            [moment("1391/11/10", "jYYYY/jMM/jDD").valueOf(), 28.65],
            [moment("1391/11/11", "jYYYY/jMM/jDD").valueOf(), 28.56],
            [moment("1391/11/14", "jYYYY/jMM/jDD").valueOf(), 28.47],
            [moment("1391/11/15", "jYYYY/jMM/jDD").valueOf(), 28.46],
            [moment("1391/11/16", "jYYYY/jMM/jDD").valueOf(), 28.43],
            [moment("1391/11/17", "jYYYY/jMM/jDD").valueOf(), 28.4],
            [moment("1391/11/18", "jYYYY/jMM/jDD").valueOf(), 28.43],
            [moment("1391/11/21", "jYYYY/jMM/jDD").valueOf(), 28.38],
            [moment("1391/11/22", "jYYYY/jMM/jDD").valueOf(), 28.34],
            [moment("1391/11/23", "jYYYY/jMM/jDD").valueOf(), 28.32],
            [moment("1391/11/24", "jYYYY/jMM/jDD").valueOf(), 28.28],
            [moment("1391/11/25", "jYYYY/jMM/jDD").valueOf(), 28.24],
        ]
    }],
    chart: {
        type: "area",
        height: 240,
        toolbar: false
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: "smooth",
        width: 2
    },
    markers: {
        size: 0,
        style: "hollow"
    },
    xaxis: {
        type: "datetime",
        tickAmount: 6,
        labels: {
            formatter: function(value) {
                return convertToJalaaliDate(value);
            }
        }
    },
    tooltip: {
        x: {
            format: "dd MMM yyyy"
        }
    },
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.6,
            opacityTo: 0.05,
            stops: [42, 100, 100, 100]
        }
    }
};

// ساخت نمودار
var chart = new ApexCharts(document.querySelector("#overview-chart-timeline"), options);
chart.render();

// کنترل محدوده تاریخ‌ها با دکمه‌های کلیدی
document.querySelector("#one_month").addEventListener("click", function(e) {
    chart.updateOptions({
        xaxis: {
            min: moment("1391/11/28", "YYYY/MM/DD").valueOf(),
            max: moment("1391/12/27", "YYYY/MM/DD").valueOf()
        }
    });
});

document.querySelector("#six_months").addEventListener("click", function(e) {
    chart.updateOptions({
        xaxis: {
            min: moment("1391/06/27", "YYYY/MM/DD").valueOf(),
            max: moment("1391/12/27", "YYYY/MM/DD").valueOf()
        }
    });
});

document.querySelector("#one_year").addEventListener("click", function(e) {
    chart.updateOptions({
        xaxis: {
            min: moment("1391/12/27", "YYYY/MM/DD").subtract(1, 'jyear').valueOf(),
            max: moment("1391/12/27", "YYYY/MM/DD").valueOf()
        }
    });
});

document.querySelector("#all").addEventListener("click", function(e) {
    chart.updateOptions({
        xaxis: {
            min: undefined,
            max: undefined
        }
    });
});
