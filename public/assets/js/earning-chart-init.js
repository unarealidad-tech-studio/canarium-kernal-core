/**
 * Created by mosaddek on 3/2/15.
 */

var data7_1 = [
    [1000000, 53],
    [2000000, 125],
    [3000000, 128],
    [4000000, 283],
    [5000000, 320],
    [6000000, 420],
    [7000000, 236]
];
var data7_2 = [
    [1000000, 43],
    [2000000, 130],
    [3000000, 100],
    [4000000, 223],
    [5000000, 98],
    [6000000, 125],
    [7000000, 50]
];
$(function() {
    $.plot($("#earning-chart #earning-container"), [{
        data: data7_1,
        label: "Page View",
        lines: {
            fill: true
        }
    }, {
        data: data7_2,
        label: "Online User",

        points: {
            show: true
        },
        lines: {
            show: true
        },
        yaxis: 2
    }
    ],
        {
            series: {
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 1
                },
                points: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    fillColor: "#ffffff",
                    symbol: "circle",
                    radius: 5
                },
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1,
                borderColor: "#f3f3f3"
            },
            colors: ["#53d192", "#72B7CD"],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            },
            xaxis: {
                mode: "time"
            },
            yaxes: [{
                /* First y axis */
            }, {
                /* Second y axis */
                position: "right" /* left or right */
            }]
        }
    );
});
