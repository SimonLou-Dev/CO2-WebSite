import * as React from "react";
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import ReactDOM from "react-dom";
import '../sass/App.scss';
import {StrictMode} from "react";

class App extends React.Component{
    constructor(props) {
        super(props);
    }

    render() {
        return(
            <StrictMode>
                <h1>Salute</h1>
            </StrictMode>
        )
    }
}

export default App;
if (document.getElementById('app')) {
    ReactDOM.render(
        <StrictMode>
            <App/>
        </StrictMode>

        , document.getElementById('app'));
}
