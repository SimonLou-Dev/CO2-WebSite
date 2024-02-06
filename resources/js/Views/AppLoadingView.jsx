import * as React from "react";
import axios from "axios";
import {useState} from "react";

export const AppLoadingView = () => {




    return (

        <div className="loading">
            <div className={"center flex-column"} id={"loader_container"}>
                <img src={"/assets/icons/logo/Logo.svg"} alt={"CO2"} id={"loadImg_Main"}/>
                <div className={"little_loader"}>
                    <img src={"/assets/icons/loader/Loading_fleches.png"} alt={"CO2"} className={"rotate"}/>
                    <img src={"/assets/icons/loader/Loading_Co2.png"} alt={"CO2"} id={"loader_co2"}/>
                </div>
            </div>
        </div>

    )
}


