import * as React from "react";
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import ReactDOM from "react-dom";
import '../sass/App.scss';
import {StrictMode, useEffect} from "react";
import Echo from "laravel-echo";
import Pusher from "pusher-js";





const App = () => {

    useEffect(() => {


        window.Pusher = Pusher

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'test',
            wsHost: window.location.hostname ,
            cluster: 'mt1',
            wsPort: 6001,
            wssPort: 6001,
            forceTLS: false,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('/api/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        })
                            .then(response => {
                                console.log(response.data);
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                };
            },
        });


        window.Echo.channel('app/test')
            .listen('*', (e) => {
                console.log(e);
            });








        //window.UserChannel = pusher.subscribe('messages');


    }, []);


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
