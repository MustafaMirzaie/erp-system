function getChartColorsArray(id) {
    if (document.getElementById(id) !== null) {
        var colorsAttribute = document.getElementById(id).getAttribute("data-colors");
        if (colorsAttribute) {
            return JSON.parse(colorsAttribute).map(function(color) {
                var trimmedColor = color.replace(" ", "");
                if (trimmedColor.indexOf(",") === -1) {
                    var computedColor = getComputedStyle(document.documentElement).getPropertyValue(trimmedColor);
                    return computedColor || trimmedColor;
                }
                var colorParts = color.split(",");
                if (colorParts.length !== 2) {
                    return trimmedColor;
                } else {
                    return "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(colorParts[0]) + "," + colorParts[1] + ")";
                }
            });
        }
        console.warn("ویژگی data-colors در المان مورد نظر یافت نشد:", id);
    }
}

var lineChartDatalabelColors = getChartColorsArray("line_chart_datalabel");
if (lineChartDatalabelColors) {
    var options = {
        chart: {
            height: 380,
            type: "line",
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: lineChartDatalabelColors,
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [3, 3],
            curve: "straight"
        },
        series: [{
            name: "بالا - ۲۰۱۸",
            data: [26, 24, 32, 36, 33, 31, 33]
        }, {
            name: "پایین - ۲۰۱۸",
            data: [14, 11, 16, 12, 17, 13, 12]
        }],
        title: {
            text: "میانگین دمای بالا و پایین",
            align: "left",
            style: {
                fontWeight: "500"
            }
        },
        grid: {
            row: {
                colors: ["transparent", "transparent"],
                opacity: 0.2
            },
            borderColor: "#f1f1f1"
        },
        markers: {
            style: "inverted",
            size: 6
        },
        xaxis: {
            categories: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "جولای"],
            title: {
                text: "ماه"
            }
        },
        yaxis: {
            title: {
                text: "دما"
            },
            min: 5,
            max: 40
        },
        legend: {
            position: "top",
            horizontalAlign: "right",
            floating: true,
            offsetY: -25,
            offsetX: -5
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#line_chart_datalabel"), options);
    chart.render();
}


var lineChartdashedColors = getChartColorsArray("line_chart_dashed");
lineChartdashedColors && (options = {
    chart: {
        height: 380,
        type: "line",
        zoom: {
            enabled: false
        },
        toolbar: {
            show: false
        }
    },
    colors: lineChartdashedColors,
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: [3, 4, 3],
        curve: "straight",
        dashArray: [0, 8, 5]
    },
    series: [{
        name: "مدت زمان جلسه",
        data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
    }, {
        name: "تعداد بازدید صفحات",
        data: [36, 42, 60, 42, 13, 18, 29, 37, 36, 51, 32, 35]
    }, {
        name: "کل بازدیدها",
        data: [89, 56, 74, 98, 72, 38, 64, 46, 84, 58, 46, 49]
    }],
    title: {
        text: "آمار صفحات",
        align: "left",
        style: {
            fontWeight: "500"
        }
    },
    markers: {
        size: 0,
        hover: {
            sizeOffset: 6
        }
    },
    xaxis: {
        categories: ["01 ژانویه", "02 ژانویه", "03 ژانویه", "04 ژانویه", "05 ژانویه", "06 ژانویه", "07 ژانویه", "08 ژانویه", "09 ژانویه", "10 ژانویه", "11 ژانویه", "12 ژانویه"]
    },
    tooltip: {
        y: [{
            title: {
                formatter: function(e) {
                    return e + " (دقیقه)"
                }
            }
        }, {
            title: {
                formatter: function(e) {
                    return e + " در هر جلسه"
                }
            }
        }, {
            title: {
                formatter: function(e) {
                    return e
                }
            }
        }]
    },
    grid: {
        borderColor: "#f1f1f1"
    }
}, (chart = new ApexCharts(document.querySelector("#line_chart_dashed"), options)).render());

var lineChartsplineAreaColors = getChartColorsArray("spline_area");
lineChartsplineAreaColors && (options = {
    chart: {
        height: 350,
        type: "area",
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: "smooth",
        width: 3
    },
    series: [{
        name: "سری اول",
        data: [34, 40, 28, 52, 42, 109, 100]
    }, {
        name: "سری دوم",
        data: [32, 60, 34, 46, 34, 52, 41]
    }],
    colors: lineChartsplineAreaColors,
    xaxis: {
        type: "datetime",
        categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"]
    },
    grid: {
        borderColor: "#f1f1f1"
    },
    tooltip: {
        x: {
            format: "dd/MM/yy HH:mm"
        }
    }
}, (chart = new ApexCharts(document.querySelector("#spline_area"), options)).render());



var columnChartColors = getChartColorsArray("column_chart");
columnChartColors && (options = {
    chart: {
        height: 350,
        type: "bar",
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "45%",
            endingShape: "rounded"
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ["transparent"]
    },
    series: [{
        name: "سود خالص",
        data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
    }, {
        name: "درآمد",
        data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
    }, {
        name: "جریان نقدی آزاد",
        data: [37, 42, 38, 26, 47, 50, 54, 55, 43]
    }],
    colors: columnChartColors,
    xaxis: {
        categories: ["فوریه", "مارس", "آوریل", "مه", "ژوئن", "جولای", "اوت", "سپتامبر", "اکتبر"]
    },
    yaxis: {
        title: {
            text: "دلار (هزار)",
            style: {
                fontWeight: "500"
            }
        }
    },
    grid: {
        borderColor: "#f1f1f1"
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function(e) {
                return "دلار " + e + " هزار"
            }
        }
    }
}, (chart = new ApexCharts(document.querySelector("#column_chart"), options)).render());

var dumbbellColumnChartsColors = getChartColorsArray("dumbbell_column_charts");
dumbbellColumnChartsColors && (options = {
    series: [{
        data: [{
            x: "2008",
            y: [2800, 4500]
        }, {
            x: "2009",
            y: [3200, 4100]
        }, {
            x: "2010",
            y: [2950, 7800]
        }, {
            x: "2011",
            y: [3000, 4600]
        }, {
            x: "2012",
            y: [3500, 4100]
        }, {
            x: "2013",
            y: [4500, 6500]
        }, {
            x: "2014",
            y: [4100, 5600]
        }]
    }],
    chart: {
        height: 350,
        type: "rangeBar",
        zoom: {
            enabled: false
        }
    },
    color: dumbbellColumnChartsColors,
    plotOptions: {
        bar: {
            isDumbbell: true,
            columnWidth: 3,
            dumbbellColors: dumbbellColumnChartsColors
        }
    },
    legend: {
        show: true,
        showForSingleSeries: true,
        position: "top",
        horizontalAlign: "left",
        customLegendItems: ["محصول A", "محصول B"]
    },
    fill: {
        type: "gradient",
        gradient: {
            type: "vertical",
            gradientToColors: ["#00E396"],
            inverseColors: true,
            stops: [0, 100]
        }
    },
    grid: {
        xaxis: {
            lines: {
                show: true
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        }
    },
    xaxis: {
        tickPlacement: "on"
    }
}, (chart = new ApexCharts(document.querySelector("#dumbbell_column_charts"), options)).render());



var rangeAreaChartColors = getChartColorsArray("range_area_chart");
rangeAreaChartColors && (options = {
    series: [{
        name: "دمای نیویورک",
        data: [{
            x: "ژانویه",
            y: [-2, 4]
        }, {
            x: "فوریه",
            y: [-1, 6]
        }, {
            x: "مارس",
            y: [3, 10]
        }, {
            x: "آوریل",
            y: [8, 16]
        }, {
            x: "مه",
            y: [13, 22]
        }, {
            x: "ژوئن",
            y: [18, 26]
        }, {
            x: "جولای",
            y: [21, 29]
        }, {
            x: "اوت",
            y: [21, 28]
        }, {
            x: "سپتامبر",
            y: [17, 24]
        }, {
            x: "اکتبر",
            y: [11, 18]
        }, {
            x: "نوامبر",
            y: [6, 12]
        }, {
            x: "دسامبر",
            y: [1, 7]
        }]
    }],
    chart: {
        height: 350,
        type: "rangeArea"
    },
    colors: rangeAreaChartColors,
    stroke: {
        curve: "straight"
    },
    title: {
        text: "دمای نیویورک (طول سال)"
    },
    markers: {
        hover: {
            sizeOffset: 5
        }
    },
    dataLabels: {
        enabled: false
    },
    yaxis: {
        labels: {
            formatter: function(e) {
                return e + "°C"
            }
        }
    }
}, (chart = new ApexCharts(document.querySelector("#range_area_chart"), options)).render()), (dumbbellColumnChartsColors = getChartColorsArray("dumbbell_timeline_charts")) && (options = {
    series: [{
        data: [{
            x: "عملیات",
            y: [2800, 4500]
        }, {
            x: "موفقیت مشتری",
            y: [3200, 4100]
        }, {
            x: "مهندسی",
            y: [2950, 7800]
        }, {
            x: "بازاریابی",
            y: [3000, 4600]
        }, {
            x: "محصول",
            y: [3500, 4100]
        }, {
            x: "علوم داده",
            y: [4500, 6500]
        }, {
            x: "فروش",
            y: [4100, 5600]
        }]
    }],
    chart: {
        height: 390,
        type: "rangeBar",
        zoom: {
            enabled: false
        }
    },
    colors: dumbbellColumnChartsColors,
    plotOptions: {
        bar: {
            horizontal: true,
            isDumbbell: true,
            dumbbellColors: dumbbellColumnChartsColors
        }
    },
    title: {
        text: "تفاوت حقوقی جنسیتی"
    },
    legend: {
        show: true,
        showForSingleSeries: true,
        position: "top",
        horizontalAlign: "left",
        customLegendItems: ["زن", "مرد"]
    },
    fill: {
        type: "gradient",
        gradient: {
            gradientToColors: ["#36BDCB"],
            inverseColors: false,
            stops: [0, 100]
        }
    },
    grid: {
        xaxis: {
            lines: {
                show: true
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        }
    }
}, (chart = new ApexCharts(document.querySelector("#dumbbell_timeline_charts"), options)).render());

var funnelChartColors = getChartColorsArray("funnel_charts");
funnelChartColors && (options = {
    series: [{
        name: "سری فانل",
        data: [1380, 1100, 990, 880, 740, 548, 330, 200]
    }],
    chart: {
        type: "bar",
        height: 390
    },
    plotOptions: {
        bar: {
            borderRadius: 0,
            horizontal: true,
            barHeight: "80%",
            isFunnel: true
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function(e, t) {
            return t.w.globals.labels[t.dataPointIndex] + ":  " + e
        },
        dropShadow: {
            enabled: true
        }
    },
    colors: funnelChartColors,
    title: {
        text: "قیف گردش کاری"
    },
    xaxis: {
        categories: ["منبعی", "صفحه‌یابی", "ارزیابی شده", "مصاحبه HR", "فنی", "تایید", "پیشنهادی", "استخدام شده"]
    },
    legend: {
        show: false
    }
}, (chart = new ApexCharts(document.querySelector("#funnel_charts"), options)).render());



var columnChartDatalabelColors = getChartColorsArray("column_chart_datalabel");
columnChartDatalabelColors && (options = {
    chart: {
        height: 350,
        type: "bar",
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                position: "top"
            }
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function(e) {
            return e + "%"
        },
        offsetY: -22,
        style: {
            fontSize: "12px",
            colors: ["#304758"]
        }
    },
    series: [{
        name: "تورم",
        data: [2.5, 3.2, 5, 10.1, 4.2, 3.8, 3, 2.4, 4, 1.2, 3.5, 0.8]
    }],
    colors: columnChartDatalabelColors,
    grid: {
        borderColor: "#f1f1f1"
    },
    xaxis: {
        categories: ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "جولای", "اوت", "سپتامبر", "اکتبر", "نوامبر", "دسامبر"],
        position: "top",
        labels: {
            offsetY: -18
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        crosshairs: {
            fill: {
                type: "gradient",
                gradient: {
                    colorFrom: "#D8E3F0",
                    colorTo: "#BED1E6",
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5
                }
            }
        },
        tooltip: {
            enabled: true,
            offsetY: -35
        }
    },
    fill: {
        gradient: {
            shade: "light",
            type: "horizontal",
            shadeIntensity: 0.25,
            gradientToColors: undefined,
            inverseColors: true,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [50, 0, 100, 100]
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        labels: {
            show: false,
            formatter: function(e) {
                return e + "%"
            }
        }
    },
    title: {
        text: "تورم ماهانه در آرژانتین، 2002",
        floating: true,
        offsetY: 330,
        align: "center",
        style: {
            color: "#444",
            fontWeight: "500"
        }
    }
}, (chart = new ApexCharts(document.querySelector("#column_chart_datalabel"), options)).render());

var barChartColors = getChartColorsArray("bar_chart");
barChartColors && (options = {
    chart: {
        height: 350,
        type: "bar",
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: true
        }
    },
    dataLabels: {
        enabled: false
    },
    series: [{
        data: [380, 430, 450, 475, 550, 584, 780, 1100, 1220, 1365]
    }],
    colors: barChartColors,
    grid: {
        borderColor: "#f1f1f1"
    },
    xaxis: {
        categories: ["کره جنوبی", "کانادا", "انگلیس", "هلند", "ایتالیا", "فرانسه", "ژاپن", "ایالات متحده", "چین", "آلمان"]
    }
}, (chart = new ApexCharts(document.querySelector("#bar_chart"), options)).render());



var mixedChartColors = getChartColorsArray("mixed_chart");
mixedChartColors && (options = {
    chart: {
        height: 350,
        type: "line",
        stacked: false,
        toolbar: {
            show: false
        }
    },
    stroke: {
        width: [0, 2, 4],
        curve: "smooth"
    },
    plotOptions: {
        bar: {
            columnWidth: "50%"
        }
    },
    colors: mixedChartColors,
    series: [{
        name: "تیم A",
        type: "column",
        data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
    }, {
        name: "تیم B",
        type: "area",
        data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
    }, {
        name: "تیم C",
        type: "line",
        data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
    }],
    fill: {
        opacity: [0.85, 0.25, 1],
        gradient: {
            inverseColors: false,
            shade: "light",
            type: "vertical",
            opacityFrom: 0.85,
            opacityTo: 0.55,
            stops: [0, 100, 100, 100]
        }
    },
    labels: ["01/01/2003", "02/01/2003", "03/01/2003", "04/01/2003", "05/01/2003", "06/01/2003", "07/01/2003", "08/01/2003", "09/01/2003", "10/01/2003", "11/01/2003"],
    markers: {
        size: 0
    },
    xaxis: {
        type: "datetime"
    },
    yaxis: {
        title: {
            text: "امتیازها"
        }
    },
    tooltip: {
        shared: true,
        intersect: false,
        y: {
            formatter: function(e) {
                return e !== undefined ? e.toFixed(0) + " امتیاز" : e;
            }
        }
    },
    grid: {
        borderColor: "#f1f1f1"
    }
}, (chart = new ApexCharts(document.querySelector("#mixed_chart"), options)).render());


var radialChartColors = getChartColorsArray("radial_chart");
radialChartColors && (options = {
    chart: {
        height: 370,
        type: "radialBar"
    },
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                    fontSize: "22px"
                },
                value: {
                    fontSize: "16px"
                },
                total: {
                    show: true,
                    label: "کل",
                    formatter: function(e) {
                        return 249;
                    }
                }
            }
        }
    },
    series: [44, 55, 67, 83],
    labels: ["کامپیوتر", "تبلت", "لپتاپ", "موبایل"],
    colors: radialChartColors
}, (chart = new ApexCharts(document.querySelector("#radial_chart"), options)).render());


var pieChartColors = getChartColorsArray("pie_chart");
pieChartColors && (options = {
    chart: {
        height: 320,
        type: "pie"
    },
    series: [44, 55, 41, 17, 15],
    labels: ["سری 1", "سری 2", "سری 3", "سری 4", "سری 5"],
    colors: pieChartColors,
    legend: {
        show: true,
        position: "bottom",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: false,
        fontSize: "14px",
        offsetX: 0
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: false
            }
        }
    }]
}, (chart = new ApexCharts(document.querySelector("#pie_chart"), options)).render());


var donutChartColors = getChartColorsArray("donut_chart");
donutChartColors && (options = {
    chart: {
        height: 320,
        type: "donut"
    },
    series: [44, 55, 41, 17, 15],
    labels: ["سری 1", "سری 2", "سری 3", "سری 4", "سری 5"],
    colors: donutChartColors,
    legend: {
        show: true,
        position: "bottom",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: false,
        fontSize: "14px",
        offsetX: 0
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: false
            }
        }
    }]
}, (chart = new ApexCharts(document.querySelector("#donut_chart"), options)).render());
