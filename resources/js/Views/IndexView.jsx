import * as React from "react";
import {useEffect, useRef, useState} from "react";
import {AppLoadingView} from "./AppLoadingView";
import Highcharts from "highcharts";
import highchartsMore from "highcharts/highcharts-more"
import highchartsSolidGauge from "highcharts/modules/solid-gauge"
import HighchartsReact from "highcharts-react-official";
import axios from "axios";
import {useLocation} from "react-router-dom";

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

const MainPage = (props) => {
    const location = useLocation()
    const myParam = new URLSearchParams(location.search).get("sensor_id");

    const chartGaugeTemp = useRef(null);
    const chartGaugeHum = useRef(null);
    const chartGaugePpm = useRef(null);
    const chartLine = useRef(null);
    const [period, setPeriod] = useState("h");

    const [rooms, setRooms] = useState([]);
    const [roomText, setRoomText] = useState([]);

    const [sensorId, setSensorId] = useState(0);





    useEffect(() => {
        let params= myParam
        if(params === null) params = 0;
        params = parseInt(params)


        fetchData(period, params)
        getRooms()
    }, []);


    const fetchData = async (per = period, sensor = sensorId) => {
        const gaugeTemp = chartGaugeTemp.current.chart;
        const gaugeHum = chartGaugeHum.current.chart;
        const gaugePpm = chartGaugePpm.current.chart;
        const chartLineRef = chartLine.current.chart;

        if(gaugeTemp.series === undefined && gaugeHum.series === undefined && gaugePpm.series === undefined) return

        setSensorId(sensor)

        await axios.get("/sensors/" + sensor + "/measures", {
                params: {
                    period: "1" + per
                }
            })
            .then((response) => {

                gaugeTemp.series[0].update({
                    data: [ parseInt(response.data.last_measure.temperature)]
                });

                gaugeHum.series[0].update({
                    data: [ parseInt(response.data.last_measure.humidity)]
                });

                gaugePpm.series[0].update({
                    data: [ parseInt(response.data.last_measure.ppm)]
                });

                chartLineRef.series[0].update({
                    data: response.data.data.temperature
                })

                chartLineRef.xAxis[0].setCategories(response.data.data.dates)

                chartLineRef.series[1].update({
                    data: response.data.data.ppm
                })

                setRoomText(response.data.room.name + " (capteur" + response.data.sensor.id_hex + ")");

                setPeriod(per)

            }).catch((error) => {
                console.log(error)
            })

    }

    const getRooms = async (text = roomText) => {
        setRoomText(text)

        if(text.length <= 2 ) return;

        await axios.get("/rooms", {
            params: {
                search: text
            }
        }).then((response) => {
            setRooms(response.data)
            if(response.data.length === 1){
                if(response.data[0].get_sensor === null) return
                fetchData(period, response.data[0].get_sensor.id)
            }
        })

    }

    const changePeriod = (_period) => {

        fetchData(_period)
    }


    return (
        <div className={"charts"}>
            <div className={"header"}>
                <h1>Mesures et statistiques</h1>
                <input type={"text"} list={"autocomplete"} value={roomText} onChange={(e)=>getRooms(e.target.value)}/>
                {rooms &&
                    <datalist id={"autocomplete"}>
                        {rooms.map((room) => (
                            <option key={room.id} value={room.name}/>
                        ))}
                    </datalist>
                }
            </div>
            <section className={"charts-section flex-line"}>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseTempGaugeOption}
                        ref={chartGaugeTemp}/>

                    <h4>Température</h4>
                </div>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseHumidGaugeOption}
                        ref={chartGaugeHum}/>
                    <h4>Humiditée</h4>
                </div>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={basePpmGaugeOption}
                        ref={chartGaugePpm}/>
                    <h4>Concentration CO2</h4>
                </div>

            </section>
            <section className={"charts-section"}>
                <div className={"header"}>
                    <h3>Courbes</h3>
                    <div className={"time_selector"}>
                        <button onClick={() => changePeriod("h")} className={"btn-time " + (period === "h" ? "selected" : "")}>1H</button>
                        <button onClick={() => changePeriod("j")} className={"btn-time " + (period === "j" ? "selected" : "")}>1J</button>
                        <button onClick={() => changePeriod("s")} className={"btn-time " + (period === "s" ? "selected" : "")}>1S</button>
                        <button onClick={() => changePeriod("m")} className={"btn-time " + (period === "m" ? "selected" : "")}>1M</button>
                        <button onClick={() => changePeriod("a")} className={"btn-time " + (period === "a" ? "selected" : "")}>1A</button>
                    </div>
                </div>
                <div className={"charts-container"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseCharts}
                        ref={chartLine}
                    />
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


