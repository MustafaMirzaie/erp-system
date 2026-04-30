! function(a) {
    "use strict";

    function n() {}
    n.prototype.init = function() {
        a("#world-map-markers").vectorMap({
            map: "world_mill_en",
            normalizeFunction: "polynomial",
            hoverOpacity: .7,
            hoverColor: !1,
            regionStyle: {
                initial: {
                    fill: "#d4dadd"
                }
            },
            markerStyle: {
                initial: {
                    r: 9,
                    fill: "#556ee6",
                    "fill-opacity": .9,
                    stroke: "#fff",
                    "stroke-width": 7,
                    "stroke-opacity": .4
                },
                hover: {
                    stroke: "#fff",
                    "fill-opacity": 1,
                    "stroke-width": 1.5
                }
            },
            backgroundColor: "transparent",
            markers: [{
                latLng: [41.9, 12.45],
                name: "واتیکان"
            }, {
                latLng: [43.73, 7.41],
                name: "موناکو"
            }, {
                latLng: [-.52, 166.93],
                name: "نائورو"
            }, {
                latLng: [-8.51, 179.21],
                name: "تووالو"
            }, {
                latLng: [43.93, 12.46],
                name: "سان مارینو"
            }, {
                latLng: [47.14, 9.52],
                name: "لیختن‌اشتاین"
            }, {
                latLng: [7.11, 171.06],
                name: "جزایر مارشال"
            }, {
                latLng: [17.3, -62.73],
                name: "سنت کیتس و نویس"
            }, {
                latLng: [3.2, 73.22],
                name: "مالدیو"
            }, {
                latLng: [35.88, 14.5],
                name: "مالت"
            }, {
                latLng: [12.05, -61.75],
                name: "گرنادا"
            }, {
                latLng: [13.16, -61.23],
                name: "سنت وینسنت و گرنادین‌ها"
            }, {
                latLng: [13.16, -59.55],
                name: "باربادوس"
            }, {
                latLng: [17.11, -61.85],
                name: "آنتیگوا و باربودا"
            }, {
                latLng: [-4.61, 55.45],
                name: "سیشل"
            }, {
                latLng: [7.35, 134.46],
                name: "پالائو"
            }, {
                latLng: [42.5, 1.51],
                name: "آندورا"
            }, {
                latLng: [14.01, -60.98],
                name: "سنت لوسیا"
            }, {
                latLng: [6.91, 158.18],
                name: "ایالات فدرال میکرونزی"
            }, {
                latLng: [1.3, 103.8],
                name: "سنگاپور"
            }, {
                latLng: [.33, 6.73],
                name: "سائوتومه و پرینسیپ"
            }]
        }), a("#usa-vectormap").vectorMap({
            map: "us_merc_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        }), a("#india-vectormap").vectorMap({
            map: "in_mill_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        }), a("#australia-vectormap").vectorMap({
            map: "au_mill_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        }), a("#chicago-vectormap").vectorMap({
            map: "us-il-chicago_mill_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        }), a("#uk-vectormap").vectorMap({
            map: "uk_mill_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        }), a("#canada-vectormap").vectorMap({
            map: "ca_lcc_en",
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: "#556ee6"
                }
            }
        })
    }, a.VectorMap = new n, a.VectorMap.Constructor = n
}(window.jQuery),
function() {
    "use strict";
    window.jQuery.VectorMap.init()
}();
