import * as React from "react";
import axios from "axios";
import {useEffect, useState} from "react";
import {AppLoadingView} from "./AppLoadingView.";

export const IndexView = () => {
    const [dataLoaded, setDataLoaded] = useState(false);

    useEffect(() => {
        setDataLoaded(true)

    }, []);


    if (dataLoaded) return (<MainPage/>)
    else return (<AppLoadingView/>)
}

const MainPage = () => {
    return (
        <h1>Michel</h1>
    )
}


