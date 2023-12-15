import * as React from "react";
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import ReactDOM from "react-dom";
import '../sass/App.scss';
import {StrictMode, useEffect} from "react";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;




const App = () => {

    useEffect(async () => {
        var  token = "";


        await axios.post("/api/login", {
            email: "a@b.c",
            password:  "Testtest",
            device_name: "johns-computer"
        }).then(r => {
            token = r.data.token
        })

        const headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        }


        //window.Pusher = Pusher

        let pusher = new Pusher("appkey", {
            wsHost : window.location.hostname,
            wsPort: 6001,
            wssPort: 6001,
            cluster: "eu",
            forceTLS: false,
            encrypted: true,
            authEndpoint : '/api/broadcasting/auth',
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('/api/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        }, {headers: headers, withCredentials: true})
                            .then(response => {
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                }
            }
        });

        window.messageChannel = pusher.subscribe("private-moi")
        window.messageChannel.bind('message-send', (e) => {
            console.log(e)
        })

        axios.patch("/api/messages/1", {
            "message": "Hello World",
            "title": "Hello World"
        }, {headers: headers}).then(r => {
            console.log(r)
        }).catch(e => {
            console.log(e)
        })


        return () => {
            window.messageChannel.unsubscribe();
        }


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
