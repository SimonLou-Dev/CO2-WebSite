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
        },{
        type: "area",
        name: "concentration de CO2",
        zIndex:2,
    },
    ]

}

const MainPage = (props) => {
    const location = useLocation()
    const myParam = new URLSearchParams(location.search).get("id");

    const chartGaugeTemp = useRef(null);
    const chartGaugeHum = useRef(null);
    const chartGaugePpm = useRef(null);
    const chartLine = useRef(null);
    const [period, setPeriod] = useState("h");

    const [rooms, setRooms] = useState([]);
    const [roomList, setRoomList] = useState([]);

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

                setPeriod(per)
                setSensorId(sensor)

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
          if(room.id === id.value && room.get_sensor !== null) fetchData(period, room.get_sensor.id)
        })

    }

    const changePeriod = (_period) => {
        fetchData(_period)
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


