// تابع برای دریافت آرایه رنگ‌ها از ویژگی data-colors
function getChartColorsArray(id) {
    // بررسی وجود عنصر با شناسه مشخص
    if (null !== document.getElementById(id)) {
        // دریافت مقدار ویژگی data-colors
        var colorsAttr = document.getElementById(id).getAttribute("data-colors");
        if (colorsAttr) {
            // تجزیه و تحلیل رشته JSON به آرایه رنگ‌ها
            var colors = JSON.parse(colorsAttr).map(function(color) {
                // حذف فاصله‌های اضافی
                var trimmedColor = color.replace(" ", "");
                // اگر رشته شامل کاما باشد، آن را به صورت RGBA برمی‌گرداند
                if (-1 === trimmedColor.indexOf(",")) {
                    var computedColor = getComputedStyle(document.documentElement).getPropertyValue(trimmedColor);
                    return computedColor || trimmedColor;
                }
                // در غیر این صورت، تقسیم و تجزیه رنگ‌ها بر اساس RGB
                var splitColor = color.split(",");
                if (2 !== splitColor.length) {
                    return trimmedColor;
                }
                return "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(splitColor[0]) + "," + splitColor[1] + ")";
            });
            return colors;
        }
        // اگر ویژگی data-colors وجود نداشته باشد
        console.warn("ویژگی data-colors در عنصر مورد نظر یافت نشد:", id);
    }
}

// دریافت رنگ‌ها برای نمودار خطی
var lineChartColors = getChartColorsArray("line-chart");
// اگر رنگ‌های دریافت شده وجود داشته باشند
if (lineChartColors) {
    // دریافت عنصر HTML برای نمودار خطی
    var dom = document.getElementById("line-chart");
    // ایجاد یک نمودار جدید از نوع ECharts
    var myChart = echarts.init(dom);
    // تنظیمات و مشخصات نمودار
    var option = {
        grid: {
            zlevel: 0,
            x: 50,
            x2: 50,
            y: 30,
            y2: 30,
            borderWidth: 0,
            backgroundColor: "rgba(0,0,0,0)",
            borderColor: "rgba(0,0,0,0)"
        },
        xAxis: {
            type: "category",
            data: ["دوشنبه", "سه‌شنبه", "چهارشنبه", "پنج‌شنبه", "جمعه", "شنبه", "یکشنبه"],
            axisLine: {
                lineStyle: {
                    color: "#8791af"
                }
            }
        },
        yAxis: {
            type: "value",
            axisLine: {
                lineStyle: {
                    color: "#8791af"
                }
            },
            splitLine: {
                lineStyle: {
                    color: "rgba(166, 176, 207, 0.1)"
                }
            }
        },
        series: [{
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: "line"
        }],
        color: lineChartColors // اعمال رنگ‌های به‌دست آمده به نمودار
    };
    // اعمال تنظیمات به نمودار
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
}
// دریافت رنگ‌های برای نمودار mix-line-bar
var mixLineChartColors = getChartColorsArray("mix-line-bar");
// اگر رنگ‌های دریافت شده وجود داشته باشند
if (mixLineChartColors) {
    // دریافت المان HTML برای نمودار mix-line-bar
    var domMixLineBar = document.getElementById("mix-line-bar");
    // ایجاد یک نمودار جدید از نوع ECharts
    var myChartMixLineBar = echarts.init(domMixLineBar);
    // تنظیمات و مشخصات نمودار mix-line-bar
    var optionMixLineBar = {
        grid: {
            zlevel: 0,
            x: 80,
            x2: 50,
            y: 30,
            y2: 30,
            borderWidth: 0,
            backgroundColor: "rgba(0,0,0,0)",
            borderColor: "rgba(0,0,0,0)"
        },
        tooltip: {
            trigger: "axis",
            axisPointer: {
                type: "cross",
                crossStyle: {
                    color: "#999"
                }
            }
        },
        toolbox: {
            orient: "center",
            left: 0,
            top: 20,
            feature: {
                dataView: {
                    readOnly: false,
                    title: "نمایش داده‌ها"
                },
                magicType: {
                    type: ["line", "bar"],
                    title: {
                        line: "برای نمودار خطی",
                        bar: "برای نمودار میله‌ای"
                    }
                },
                restore: {
                    title: "بازگردانی"
                },
                saveAsImage: {
                    title: "دانلود تصویر"
                }
            }
        },
        color: mixLineChartColors,
        legend: {
            data: ["تبخیر", "بارش", "میانگین دما"],
            textStyle: {
                color: "#8791af"
            }
        },
        xAxis: [{
            type: "category",
            data: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "جولای", "اوت"],
            axisPointer: {
                type: "shadow"
            },
            axisLine: {
                lineStyle: {
                    color: "#8791af"
                }
            }
        }],
        yAxis: [{
            type: "value",
            name: "حجم آب",
            min: 0,
            max: 250,
            interval: 50,
            axisLine: {
                lineStyle: {
                    color: "#8791af"
                }
            },
            splitLine: {
                lineStyle: {
                    color: "rgba(166, 176, 207, 0.1)"
                }
            },
            axisLabel: {
                formatter: "{value} میلی لیتر"
            }
        }, {
            type: "value",
            name: "دما",
            min: 0,
            max: 25,
            interval: 5,
            axisLine: {
                lineStyle: {
                    color: "#8791af"
                }
            },
            splitLine: {
                lineStyle: {
                    color: "rgba(166, 176, 207, 0.1)"
                }
            },
            axisLabel: {
                formatter: "{value} درجه سانتی‌گراد"
            }
        }],
        series: [{
            name: "تبخیر",
            type: "bar",
            data: [2, 4.9, 7, 23.2, 25.6, 76.7, 135.6, 162.2]
        }, {
            name: "بارش",
            type: "bar",
            data: [2.6, 5.9, 9, 26.4, 28.7, 70.7, 175.6, 182.2]
        }, {
            name: "میانگین دما",
            type: "line",
            yAxisIndex: 1,
            data: [2, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4]
        }]
    };
    // اعمال تنظیمات به نمودار mix-line-bar
    if (optionMixLineBar && typeof optionMixLineBar === "object") {
        myChartMixLineBar.setOption(optionMixLineBar, true);
    }
}

// دریافت رنگ‌های برای نمودار doughnut-chart
var doughnutChartColors = getChartColorsArray("doughnut-chart");
// اگر رنگ‌های دریافت شده وجود داشته باشند
if (doughnutChartColors) {
    // دریافت المان HTML برای نمودار doughnut-chart
    var domDoughnutChart = document.getElementById("doughnut-chart");
    // ایجاد یک نمودار جدید از نوع ECharts
    var myChartDoughnut = echarts.init(domDoughnutChart);
    // تنظیمات و مشخصات نمودار doughnut-chart
    var optionDoughnutChart = {
        tooltip: {
            trigger: "item",
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: "vertical",
            x: "left",
            data: ["لپ تاپ", "تبلت", "موبایل", "دیگر", "رایانه شخصی"],
            textStyle: {
                color: "#8791af"
            }
        },
        color: doughnutChartColors,
        series: [{
            name: "فروش کلی",
            type: "pie",
            radius: ["50%", "70%"],
            avoidLabelOverlap: false,
            label: {
                normal: {
                    show: false,
                    position: "center"
                },
                emphasis: {
                    show: true,
                    textStyle: {
                        fontSize: "30",
                        fontWeight: "bold"
                    }
                }
            },
            labelLine: {
                normal: {
                    show: false
                }
            },
            data: [{
                value: 335,
                name: "لپ تاپ"
            }, {
                value: 310,
                name: "تبلت"
            }, {
                value: 234,
                name: "موبایل"
            }, {
                value: 135,
                name: "دیگر"
            }, {
                value: 1548,
                name: "رایانه شخصی"
            }]
        }]
    };
    // اعمال تنظیمات به نمودار doughnut-chart
    if (optionDoughnutChart && typeof optionDoughnutChart === "object") {
        myChartDoughnut.setOption(optionDoughnutChart, true);
    }
}


var pieChartColors = getChartColorsArray("pie-chart");
pieChartColors && (dom = document.getElementById("pie-chart"), myChart = echarts.init(dom), app = {}, option = null, option = {
    tooltip: {
        trigger: "item",
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: "vertical",
        left: "left",
        data: ["لپتاپ", "تبلت", "موبایل", "سایر", "دسکتاپ"],
        textStyle: {
            color: "#8791af"
        }
    },
    color: pieChartColors,
    series: [{
        name: "فروش کل",
        type: "pie",
        radius: "55%",
        center: ["50%", "60%"],
        data: [{
            value: 335,
            name: "لپتاپ"
        }, {
            value: 310,
            name: "تبلت"
        }, {
            value: 234,
            name: "موبایل"
        }, {
            value: 135,
            name: "سایر"
        }, {
            value: 1548,
            name: "دسکتاپ"
        }],
        itemStyle: {
            emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: "rgba(0, 0, 0, 0.5)"
            }
        }
    }]
}, option && "object" == typeof option && myChart.setOption(option, !0));

var scatterChartColors = getChartColorsArray("scatter-chart");
scatterChartColors && (dom = document.getElementById("scatter-chart"), myChart = echarts.init(dom), app = {}, option = null, option = {
    grid: {
        zlevel: 0,
        x: 50,
        x2: 50,
        y: 30,
        y2: 30,
        borderWidth: 0,
        backgroundColor: "rgba(0,0,0,0)",
        borderColor: "rgba(0,0,0,0)"
    },
    xAxis: {
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                color: "rgba(166, 176, 207, 0.1)"
            }
        }
    },
    yAxis: {
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                color: "rgba(166, 176, 207, 0.1)"
            }
        }
    },
    series: [{
        symbolSize: 10,
        data: [
            [10, 8.04],
            [8, 6.95],
            [13, 7.58],
            [9, 8.81],
            [11, 8.33],
            [14, 9.96],
            [6, 7.24],
            [4, 4.26],
            [12, 10.84],
            [7, 4.82],
            [5, 5.68]
        ],
        type: "scatter"
    }],
    color: scatterChartColors
}, option && "object" == typeof option && myChart.setOption(option, !0));

var data, bubbleChartColors = getChartColorsArray("bubble-chart");
bubbleChartColors && (dom = document.getElementById("bubble-chart"), myChart = echarts.init(dom), app = {}, option = null, option = {
    grid: {
        zlevel: 0,
        x: 50,
        x2: 50,
        y: 30,
        y2: 30,
        borderWidth: 0,
        backgroundColor: "rgba(0,0,0,0)",
        borderColor: "rgba(0,0,0,0)"
    },
    legend: {
        right: 10,
        data: ["2018", "2019"]
    },
    xAxis: {
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                type: "dashed",
                color: "rgba(166, 176, 207, 0.1)"
            }
        }
    },
    yAxis: {
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                type: "dashed",
                color: "rgba(166, 176, 207, 0.1)"
            }
        },
        scale: !0
    },
    series: [{
        name: "2018",
        data: (data = [
            [
                [28604, 77, 17096869, "استرالیا", 1990],
                [31163, 77.4, 27662440, "کانادا", 1990],
                [1516, 68, 1154605773, "چین", 1990],
                [13670, 74.7, 10582082, "کوبا", 1990],
                [28599, 75, 4986705, "فنلاند", 1990],
                [29476, 77.1, 56943299, "فرانسه", 1990],
                [31476, 75.4, 78958237, "آلمان", 1990],
                [28666, 78.1, 254830, "ایسلند", 1990],
                [1777, 57.7, 870601776, "هند", 1990],
                [29550, 79.1, 122249285, "ژاپن", 1990],
                [2076, 67.9, 20194354, "کره شمالی", 1990],
                [12087, 72, 42972254, "کره جنوبی", 1990],
                [24021, 75.4, 3397534, "نیوزیلند", 1990],
                [43296, 76.8, 4240375, "نروژ", 1990],
                [10088, 70.8, 38195258, "لهستان", 1990],
                [19349, 69.6, 147568552, "روسیه", 1990],
                [10670, 67.3, 53994605, "ترکیه", 1990],
                [26424, 75.7, 57110117, "بریتانیا", 1990],
                [37062, 75.4, 252847810, "ایالات متحده", 1990]
            ],
            [
                [44056, 81.8, 23968973, "استرالیا", 2015],
                [43294, 81.7, 35939927, "کانادا", 2015],
                [13334, 76.9, 1376048943, "چین", 2015],
                [21291, 78.5, 11389562, "کوبا", 2015],
                [38923, 80.8, 5503457, "فنلاند", 2015],
                [37599, 81.9, 64395345, "فرانسه", 2015],
                [44053, 81.1, 80688545, "آلمان", 2015],
                [42182, 82.8, 329425, "ایسلند", 2015],
                [5903, 66.8, 1311050527, "هند", 2015],
                [36162, 83.5, 126573481, "ژاپن", 2015],
                [1390, 71.4, 25155317, "کره شمالی", 2015],
                [34644, 80.7, 50293439, "کره جنوبی", 2015],
                [34186, 80.6, 4528526, "نیوزیلند", 2015],
                [64304, 81.6, 5210967, "نروژ", 2015],
                [24787, 77.3, 38611794, "لهستان", 2015],
                [23038, 73.13, 143456918, "روسیه", 2015],
                [19360, 76.5, 78665830, "ترکیه", 2015],
                [38225, 81.4, 64715810, "بریتانیا", 2015],
                [53354, 79.1, 321773631, "ایالات متحده", 2015]
            ]
        ])[0],
        type: "scatter",
        symbolSize: function(t) {
            return Math.sqrt(t[2]) / 500
        },
        label: {
            emphasis: {
                show: !0,
                formatter: function(t) {
                    return t.data[3]
                },
                position: "top"
            }
        },
        itemStyle: {
            normal: {
                shadowBlur: 10,
                shadowColor: "rgba(" + bubbleChartColors[0] + ", 0.5)",
                shadowOffsetY: 5,
                color: new echarts.graphic.RadialGradient(.4, .3, 1, [{
                    offset: 0,
                    color: "rgba(" + bubbleChartColors[0] + ", 0.1)"
                }, {
                    offset: 1,
                    color: "rgb(" + bubbleChartColors[0] + ")"
                }])
            }
        }
    }, {
        name: "2019",
        data: data[1],
        type: "scatter",
        symbolSize: function(t) {
            return Math.sqrt(t[2]) / 500
        },
        label: {
            emphasis: {
                show: !0,
                formatter: function(t) {
                    return t.data[3]
                },
                position: "top"
            }
        },
        itemStyle: {
            normal: {
                shadowBlur: 10,
                shadowColor: "rgba(" + bubbleChartColors[1] + ", 0.5)",
                shadowOffsetY: 5,
                color: new echarts.graphic.RadialGradient(.4, .3, 1, [{
                    offset: 0,
                    color: "rgba(" + bubbleChartColors[1] + ", 0.1)"
                }, {
                    offset: 1,
                    color: "rgb(" + bubbleChartColors[1] + ")"
                }])
            }
        }
    }]
}, option && "object" == typeof option && myChart.setOption(option, !0));

var candleStickChartColors = getChartColorsArray("candlestick-chart");
candleStickChartColors && (dom = document.getElementById("candlestick-chart"), myChart = echarts.init(dom), app = {}, option = null, option = {
    grid: {
        zlevel: 0,
        x: 50,
        x2: 50,
        y: 30,
        y2: 30,
        borderWidth: 0,
        backgroundColor: "rgba(0,0,0,0)",
        borderColor: "rgba(0,0,0,0)"
    },
    xAxis: {
        data: ["2017-10-24", "2017-10-25", "2017-10-26", "2017-10-27"],
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                color: "rgba(166, 176, 207, 0.1)"
            }
        }
    },
    yAxis: {
        axisLine: {
            lineStyle: {
                color: "#8791af"
            }
        },
        splitLine: {
            lineStyle: {
                color: "rgba(166, 176, 207, 0.1)"
            }
        }
    },
    series: [{
        type: "k",
        data: [
            [20, 30, 10, 35],
            [40, 35, 30, 55],
            [33, 38, 33, 40],
            [40, 40, 32, 42]
        ],
        itemStyle: {
            normal: {
                color: candleStickChartColors[0],
                color0: candleStickChartColors[1],
                borderColor: candleStickChartColors[0],
                borderColor0: candleStickChartColors[1]
            }
        }
    }]
}, option && "object" == typeof option && myChart.setOption(option, !0));

var dom, myChart, app, gaugeChartColors = getChartColorsArray("gauge-chart");
gaugeChartColors && (dom = document.getElementById("gauge-chart"), myChart = echarts.init(dom), app = {}, option = null, option = {
    series: [{
        type: "gauge",
        axisLine: {
            lineStyle: {
                width: 18,
                color: [
                    [.2, gaugeChartColors[0]],
                    [.8, gaugeChartColors[1]],
                    [1, gaugeChartColors[2]]
                ]
            }
        },
        pointer: {
            itemStyle: {
                color: "inherit"
            }
        },
        axisTick: {
            distance: -18,
            length: 8,
            lineStyle: {
                color: "#fff",
                width: 1
            }
        },
        splitLine: {
            distance: -18,
            length: 30,
            lineStyle: {
                color: "#fff",
                width: 1
            }
        },
        axisLabel: {
            distance: 28,
            fontSize: 13,
            color: "inherit"
        },
        detail: {
            valueAnimation: !0,
            formatter: "{value} km/h",
            color: "inherit",
            fontSize: 16
        },
        data: [{
            value: 70
        }]
    }]
}, setInterval(function() {
    option.series[0].data[0].value = +(100 * Math.random()).toFixed(2), myChart.setOption(option, !0)
}, 2e3), option && "object" == typeof option && myChart.setOption(option, !0));
