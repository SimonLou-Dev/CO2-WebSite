import * as React from "react";
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import ReactDOM from "react-dom";
import '../sass/App.scss';
import {StrictMode, useEffect} from "react";
import Pusher from "pusher-js";
import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;




const App = () => {


        return(
            <StrictMode>
                <h1>Salute</h1>
            </StrictMode>
        )

}

export default App;
if (document.getElementById('app')) {
    ReactDOM.render(
        <StrictMode>
            <App/>
        </StrictMode>

        , document.getElementById('app'));
}
