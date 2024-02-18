import * as React from "react";
import {useEffect, useRef, useState} from "react";
import {AppLoadingView} from "./AppLoadingView";
import Highcharts from "highcharts";
import highchartsMore from "highcharts/highcharts-more"
import highchartsSolidGauge from "highcharts/modules/solid-gauge"
import HighchartsReact from "highcharts-react-official";
import axios from "axios";
import {useLocation} from "react-router-dom";
import Select from 'react-select'
import highchartsSeriesLabel from "highcharts/modules/series-label";
import highchartsHeatmap from "highcharts/modules/heatmap";
import highchartsAccessibility from "highcharts/modules/accessibility";
import {pushNotification} from "../Utils/Context/NotificationProvider";
import Pusher from "pusher-js";
import Echo from "laravel-echo";

highchartsMore(Highcharts)
highchartsSolidGauge(Highcharts)
highchartsHeatmap(Highcharts)
highchartsAccessibility(Highcharts)

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
    accessibility: {
      enabled: false
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
        dataLabels:  {
            format: "{y} °C",
        }
    }]
}
const basePpmGaugeOption = {
    chart:{
        type: "solidgauge"
    },
    accessibility: {
        enabled: false
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
    }]
}
const baseHumidGaugeOption = {
    chart:{
        type: "solidgauge"
    },
    accessibility: {
        enabled: false
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
    xAxis: {
      type: 'datetime',
        labels:{
            format: '{value:%d/%m %H:%M}'
        }
    },
    yAxis: [{
        labels: {
            format: '{value} ppm'
        },
        title: {
            text: "Concentration en CO2"
        },
        plotBands: [
            {
                from: 0,
                to: 800,
                zIndex: 10,
                color: "rgba(37,211,130,0.2)",
                label: {
                    zIndex: 10,
                    text: "Bonne qualité d'air",
                    style: {
                        color: "#25D382"
                    }
                }
            },{
                from: 800,
                to: 1200,
                zIndex: 10,
                color: "rgba(255,153,41,0.2)",
                label: {
                    zIndex: 10,
                    text: "Qualité d'air moyenne",
                    style: {
                        color: "#FF9929"
                    }
                }
            },{
                from: 1200,
                to: 8000,
                zIndex: 10,
                color: "rgb(255,66,89,0.2)",
                label: {
                    zIndex: 10,
                    text: "Mauvaise qualité",
                    style: {
                        color: "#FF4259"
                    }
                }
            }
        ]
    },{
        labels: {
            format: '{value} °C'
        },
        title: {
            text: "Température"
        },
        opposite: true

    }],

    responsive: {
      rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: "horizontal",
                    align: "left",
                    verticalAlign: "bottom"
                },
                yAxis: [{
                    labels: {
                        align: "right",
                        x: 0,
                        y: -6
                    },
                    title: {
                        text: null
                    }
                },{
                    labels: {
                        align: "left",
                        x: 0,
                        y: -6
                    },
                    title: {
                        text: null
                    }
                }]
            }
      }]
    },
    series: [
        {
            type: "line",
            name: "température",
            yAxis: 1,
            zIndex:4,
        },{
        type: "area",
        name: "concentration de CO2",
        zIndex:2,
    },
    ]

}
const mapOptions = {
    chart: {
        type: 'heatmap',
    },
    title: {
        text: 'Heatmap'
    },
    colorAxis: {
        stops: [
            [0, '#25D382'],
            [800/1200, '#FF9929'],
            [1200/1200, '#FF4259']
        ],
        max: 1200,
        startOnTick: false,
        endOnTick: false,
    },
    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: "horizontal",
                    align: "left",
                    verticalAlign: "bottom"
                },
                yAxis: [{
                    labels: {
                        align: "left",
                        x: 0,
                        y: -6
                    },
                    title: {
                        text: null
                    }
                }]
            }
        }]
    },

    series: [{
        name: "Concentration de CO2",
        dataLabels: {
            enabled: true,
            color: '#000',
            borderColor: "none",
        }
    }]
}

const MainPage = (props) => {
    const location = useLocation()
    const myParam = new URLSearchParams(location.search).get("id");

    const chartGaugeTemp = useRef(null);
    const chartGaugeHum = useRef(null);
    const chartGaugePpm = useRef(null);
    const chartLine = useRef(null);
    const chartMap = useRef(null);
    const [period, setPeriod] = useState("h");

    const [rooms, setRooms] = useState([]);
    const [roomList, setRoomList] = useState([]);
    const [heatMapDates, setHeatMapDates] = useState([]);

    const [sensorId, setSensorId] = useState(0);





    useEffect(() => {
        let params= myParam
        if(params === null) params = 0;
        params = parseInt(params)


        fetchData(period, params)
        getRooms()
        getHeatmapData()
    }, []);



    const listenForUpdates = (_sensorId = sensorId) => {
        //updateGraphEvent

        if(_sensorId != sensorId){
            window.Echo.leaveChannel("Sensor." +  process.env.APP_ENV + "." + sensorId)

            window.Echo.channel("Sensor." +  process.env.APP_ENV + "." + _sensorId)
                .listen(".updateGraphEvent", (e) => {
                    if(period === "h" || period === "j") fetchData()
                    if( new Date().getMinutes() <= 10) getHeatmapData();
                })
        }




    }


    const fetchData = async (per = period, sensor = sensorId) => {
        const gaugeTemp = chartGaugeTemp.current.chart;
        const gaugeHum = chartGaugeHum.current.chart;
        const gaugePpm = chartGaugePpm.current.chart;
        const chartLineRef = chartLine.current.chart;

        if(gaugeTemp.series === undefined && gaugeHum.series === undefined && gaugePpm.series === undefined) return

        setSensorId(sensor)

        console.log(Date.UTC(2010,0,1))

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
                    data: [ parseInt(response.data.last_measure.humidity)],
                });

                gaugePpm.series[0].update({
                    data: [ parseInt(response.data.last_measure.ppm)]
                });

                chartLineRef.series[0].update({
                    data: response.data.data.temperature
                })


                chartLineRef.xAxis[0].setCategories(response.data.data.dates)



                chartLineRef.series[1].update({
                    data: response.data.data.ppm,
                    pointInterval: response.data.pointInterval,
                })

                setPeriod(per)
                setSensorId(response.data.sensor.id)
                listenForUpdates(response.data.sensor.id)

            }).catch((error) => {
                console.log(error)
            })


    }

    const getRooms = async () => {

        const rooms = [];


        await axios.get("/rooms", ).then((response) => {

            response.data.forEach((room) => {
                rooms.push({value: room.id, label: room.name + (room.get_sensor ? " (#" + room.get_sensor.id_hex + ")" : "")})
            })
            setRooms(rooms)
            setRoomList(response.data)
        })

    }

    const selectRoom = (id) => {
        roomList.forEach((room) => {
          if(room.id === id.value && room.get_sensor !== null){
              const _sensorId=  room.get_sensor.id
              fetchData(period, _sensorId);
              getHeatmapData(_sensorId)
          }
        })

    }

    const changePeriod = (_period) => {
        fetchData(_period)
    }

    const getHeatmapData = async (_sensorId = sensorId) => {
        const chartMapRef = chartMap.current.chart

        await axios.get("/sensors/" + _sensorId + "/heatmap", {})
            .then((response) => {
                let measures = response.data.data;
                let days = response.data.days;
                const data = [];
                measures.forEach((measure) => {
                  data.push([measure.x, measure.y, measure.ppm])
                })
                setHeatMapDates(days);


                chartMapRef.series[0].update({
                    data: data
                })

                chartMapRef.yAxis[0].setCategories(days)

                chartMapRef.tooltip.update({
                    format: '<b>{series.yAxis.categories.(point.y)}</b> à <b>{point.x}h</b> la concentration est de <b>{point.value}</b> ppm'
                });






            }).catch((error) => {
                console.log(error)
            })
    }


    return (
        <div className={"charts"}>
            <div className={"header"}>
                <h1>Mesures et statistiques</h1>
                <Select options={rooms}  onChange={selectRoom} placeholder={"Choisir une salle"} className={"roomSelector"} classNamePrefix="react-select"/>
            </div>
            <section className={"charts-section flex-line"}>
                <div className={"gauge"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseTempGaugeOption}
                        ref={chartGaugeTemp}/>

                    <h4>Température</h4>
                </div>
                <div className={"gauge phoneHidden"}>
                    <HighchartsReact
                        highcharts={Highcharts}
                        options={baseHumidGaugeOption}
                        ref={chartGaugeHum}/>
                    <h4>Humidité</h4>
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
                        ref={chartMap}
                        highcharts={Highcharts}
                        options={mapOptions}/>
                </div>

            </section>
        </div>
    )
}


