import * as React from "react";
import '../sass/App.scss';
import {StrictMode, useEffect} from "react";
import Pusher from "pusher-js";
import axios from "axios";
import {createRoot} from "react-dom/client";
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import {IndexView} from "./Views/IndexView.";
import {ErrorView} from "./Views/ErrorView.";
import {LayoutComponent} from "./Components/LayoutComponent.";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

let router = createBrowserRouter([
    {

        path: "/",
        element: <LayoutComponent/>,
        errorElement: <ErrorView/>,
        children: [
            {
                index: true,
                element: <IndexView/>
            }
        ]
    }
])


if (document.getElementById('app')) {
    const container  = document.getElementById("app");
    const root = createRoot(container );
    root.render(<StrictMode><RouterProvider router={router}/></StrictMode>)
}
