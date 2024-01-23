import * as React from "react";
import axios from "axios";
import {useEffect, useState} from "react";
import {AppLoadingView} from "./AppLoadingView.";
import Highcharts from "highcharts";
import highchartsMore from "highcharts/highcharts-more"
import highchartsSolidGauge from "highcharts/modules/solid-gauge"
import HighchartsReact from "highcharts-react-official";

highchartsMore(Highcharts)
highchartsSolidGauge(Highcharts)

export const IndexView = () => {
    const [dataLoaded, setDataLoaded] = useState(true);

    useEffect(() => {
        setDataLoaded(true)

    }, []);


    if (dataLoaded) return (<MainPage/>)
    else return (<AppLoadingView/>)
}


const baseTempGaugeOption = {
    chart:{
        type: "solidgauge"
    },
    title: null,
    pane: {
        center: ['50%', "90%"],
        size: '130%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: "#EDFFF9",
            shape: "arc",
            innerRadius: "60%",
            outerRadius: "100%"
        }
    },
    rangeSelector:{
        enabled: false
    },

    exporting: {
        enabled: false
    },


    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        min: -40,
        max: 60,

        stops: [
            [0.1, '#69CADE'], // green
            [0.8, '#25D382'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        tickWidth: 0,
        tickAmount: 0,
        minorTickInterval: null,
        labels: {
            y: 16
        }

    },
    plotOptions: {
        solidgauge: {
            dataLabels: {
                size: 50,
                y: -40,
                borderWidth: 0,
            }
        }
    },
    credits: {
        enabled: false
    },

    series: [{
        data: [-20],
        dataLabels:  {
            format: "{y} °C",
        }
    }]
}
const basePpmGaugeOption = {
    chart:{
        type: "solidgauge"
    },
    title: null,
    pane: {
        center: ['50%', "90%"],
        size: '130%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: "#EDFFF9",
            shape: "arc",
            innerRadius: "60%",
            outerRadius: "100%"
        }
    },
    rangeSelector:{
        enabled: false
    },
    credits:{
        enabled: false
    },

    exporting: {
        enabled: false
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        min: 200,
        max: 1400,
        stops: [
            [0.1, '#25D382'], // green
            [0.7, '#FF9929'], // yellow
            [0.9, '#FF4259'] // red
        ],
        lineWidth: 0,
        tickWidth: 0,
        tickAmount: 0,
        minorTickInterval: null,
        labels: {
            y: 16
        }

    },
    plotOptions: {
        solidgauge: {
            dataLabels: {
                size: 50,
                y: -40,
                borderWidth: 0,
            }
        }
    },

    series: [{
        data: [1200]
    }]
}
const baseHumidGaugeOption = {
    chart:{
        type: "solidgauge"
    },
    title: null,
    pane: {
        center: ['50%', "90%"],
        size: '130%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: "#EDFFF9",
            shape: "arc",
            innerRadius: "60%",
            outerRadius: "100%"
        }

    },
    rangeSelector:{
        enabled: false
    },

    credits: {
        enabled: false
    },

    exporting: {
        enabled: false
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        min: 0,
        max: 100,
        stops: [
            [0.1, '#FF4259'], // green
            [0.5, '#25D382'], // yellow
            [0.9, '#69CADE'] // red
        ],
        lineWidth: 0,
        tickWidth: 0,
        tickAmount: 0,
        minorTickInterval: null,
        labels: {
            y: 16
        },
        visible: false,
        pane: 0,

    },
    plotOptions: {
        solidgauge: {
            dataLabels: {
                size: 50,
                y: -40,
                borderWidth: 0,
            }
        }
    },

    series: [{
        data: [90],
        dataLabels:  {
            format: "{y} %",
        }
    }]
}
const baseCharts = {
    chart: {
        zoomType: "x",
    },
    credits: {
        enabled: false
    },
    title: {
        text: "Concentration en CO2"
    },
    yAxis: [{
        labels: {
            format: '{value} ppm'
        },
        title: {
            text: "Concentration en CO2"
        }
    },{
        labels: {
            format: '{value} °C'
        },
        title: {
            text: "Température"
        },
        opposite: true

    }],
    xAxis: {
        type: "datetime"
    },
    series: [
        {
            type: "line",
            name: "température",
            yAxis: 1,
            zIndex:4,
            data: [
                [
                    1262304000000,
                    0.7537
                ],
                [
                    1262563200000,
                    0.6951
                ],
                [
                    1262649600000,
                    0.6925
                ],
                [
                    1262736000000,
                    0.697
                ],
                [
                    1262822400000,
                    0.6992
                ],
                [
                    1262908800000,
                    0.7007
                ],
                [
                    1263168000000,
                    0.6884
                ],
                [
                    1263254400000,
                    0.6907
                ],
                [
                    1263340800000,
                    0.6868
                ],
                [
                    1263427200000,
                    0.6904
                ],
                [
                    1263513600000,
                    0.6958
                ],
                [
                    1263772800000,
                    0.696
                ],
                [
                    1263859200000,
                    0.7004
                ],
                [
                    1263945600000,
                    0.7077
                ],
                [
                    1264032000000,
                    0.7111
                ]
            ]
        },{
        type: "area",
        name: "concentration de CO2",
        zIndex:2,
        data: [
            [
                1262304000000,
                0.7537
            ],
            [
                1262563200000,
                0.6951
            ],
            [
                1262649600000,
                0.6925
            ],
            [
                1262736000000,
                0.697
            ],
            [
                1262822400000,
                0.6992
            ],
            [
                1262908800000,
                0.7007
            ],
            [
                1263168000000,
                0.6884
            ],
            [
                1263254400000,
                0.6907
            ],
            [
                1263340800000,
                0.6868
            ],
            [
                1263427200000,
                0.6904
            ],
            [
                1263513600000,
                0.6958
            ],
            [
                1263772800000,
                0.696
            ],
            [
                1263859200000,
                0.7004
            ],
            [
                1263945600000,
                0.7077
            ],
            [
                1264032000000,
                0.7111
            ]
        ]
    },
    ]

}

const MainPage = () => {

    return (
        <div className={"charts"}>
            <div className={"header"}>
                <h1>Mesures et statistiques</h1>
                <select defaultValue={0}>
                    <option value={0} disabled={true}>Choississez une salle</option>
                </select>
            </div>
            <section className={"charts-section flex-line"}>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseTempGaugeOption}/>
                    <h4>Température</h4>
                </div>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseHumidGaugeOption}/>
                    <h4>Humiditée</h4>
                </div>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={basePpmGaugeOption}/>
                    <h4>Concentration CO2</h4>
                </div>

            </section>
            <section className={"charts-section"}>
                <div className={"header"}>
                    <h3>Courbes</h3>
                    <div className={"time_selector"}>
                        <button className={"btn-time"}>1H</button>
                        <button className={"btn-time"}>1J</button>
                        <button className={"btn-time"}>1S</button>
                        <button className={"btn-time"}>1M</button>
                        <button className={"btn-time"}>1A</button>
                    </div>
                </div>
                <div className={"charts-container"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseCharts}/>
                </div>

            </section>
            <section className={"charts-section"}>
                <div className={"header"}>
                    <h3>HeatMap</h3>

                </div>
                <div className={"charts-container"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseCharts}/>
                </div>

            </section>
        </div>
    )
}


