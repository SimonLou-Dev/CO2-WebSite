import * as React from "react";
import axios from "axios";
import {useEffect, useState} from "react";
import {AppLoadingView} from "./AppLoadingView.";
import HighchartsReact from "highcharts-react-official";
import Highcharts from "highcharts";
import highchartsSolideGauge from "highcharts/modules/solid-gauge";

highchartsSolideGauge(Highcharts);
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
    title: {
        text: "Température"
    },
    pane: {
        center: ['50%', '85%'],
        size: '140%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    exporting: {
        enabled: false
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        tickWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    },
    series: [{
        name: "Temperature",
        data: [20]
    }]
}


const MainPage = () => {
    const [tempGaugeOption, setTempGaugeOption] = useState(baseTempGaugeOption)

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
                        options={tempGaugeOption}/>

                    <h5>Température</h5>
                </div>
                <div className={"gauge"}>

                    <h5>Humidité</h5>
                </div>
                <div className={"gauge"}>

                    <h5>Taux de Co2</h5>
                </div>

            </section>
            <section className={"charts-section"}>
                <div className={"header"}>
                    <h5>Courbes</h5>
                    <div className={"tile_selector"}>

                    </div>
                </div>
            </section>
            <section className={"charts-section"}>
                <div className={"header"}>
                    <h5>HeatMap</h5>

                </div>

            </section>
        </div>
    )
}


