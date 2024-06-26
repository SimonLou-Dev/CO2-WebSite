import * as React from "react";
import '../sass/App.scss';
import {StrictMode, useEffect} from "react";
import Pusher from "pusher-js";
import axios from "axios";
import {createRoot} from "react-dom/client";
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import {IndexView} from "./Views/IndexView";
import {ErrorView} from "./Views/ErrorView";
import {LayoutComponent} from "./Components/LayoutComponent";
import {Settings} from "./Views/Settings";
import NotificationProvider from "./Utils/Context/NotificationProvider";
import {Sensor} from "./Views/Sensor";
import {EditSensor} from "./Views/EditSensor";
import {UserView} from "./Views/UserView";

axios.defaults.baseURL = process.env.APP_URL + "/api";
axios.defaults.headers.post['Access-Control-Allow-Origin'] = '*';

let router = createBrowserRouter([
    {
        path: "/",
        element: <LayoutComponent/>,
        errorElement: <ErrorView/>,
        children: [
            {
                index: true,
                element: <IndexView/>
            },
            {
                path: "/settings",
                element: <Settings/>
            }, {
                path: "/sensors",
                element: <Sensor/>
            }, {
                path: "/sensors/:id",
                element: <EditSensor/>
            }, {
                path: "/users",
                element: <UserView/>
            }
        ]
    }
])


if (document.getElementById('app')) {
    const container  = document.getElementById("app");
    const root = createRoot(container );
    root.render(<NotificationProvider><RouterProvider router={router}/></NotificationProvider>)
}
