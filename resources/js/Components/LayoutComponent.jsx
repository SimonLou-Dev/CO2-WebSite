import * as React from "react";
import {Link, Outlet, useLocation} from "react-router-dom";
import {useEffect, useState} from "react";
import {v4} from "uuid"
import UserContext from "../Utils/Context/UserContext";
import {useLocalStorage} from "../Utils/StorageGroup";
import {pushNotification, useNotifications} from "../Utils/Context/NotificationProvider";
import axios from "axios";
import {setAuthToken} from "../Utils/AxiosFunction";
import Echo from "laravel-echo";
import Pusher from "pusher-js";


export const LayoutComponent = () => {
    const [userAuthed, authUser] = useState(false)
    const dispatch = useNotifications()
    const location = useLocation()

    const [user, setUser] = useState(null)
    const [token, setToken, removeToken] = useLocalStorage("token", null)

    useEffect( () => {

        fetchToken()

    }, [token]);

    useEffect(() => {

        fetchToken()
        connectToSocket()
    })




    const connectToSocket = (_token = token, _user = user) => {

        window.Pusher = Pusher
        axios.defaults.headers.common['Authorization'] = `Bearer ${_token}`;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.PUSHER_APP_KEY,
            wsHost: process.env.PUSHER_HOST ,
            cluster: 'mt1',
            wsPort: 6001,
            wssPort: 6001,
            forceTLS: false,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        })
                            .then(response => {
                                console.log("Auth to the socket");
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                };
            },
        });

        if(!_user) return

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.PUSHER_APP_KEY,
            wsHost: process.env.PUSHER_HOST ,
            cluster: 'mt1',
            wsPort: 6001,
            wssPort: 6001,
            forceTLS: false,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        })
                            .then(response => {
                                console.log("Auth to the socket");
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                };
            },
        });

        window.Echo.private("User." +  process.env.APP_ENV + "." + _user.id)
            .listen(".notify", (e) => {
                console.log(e)
                pushNotification(dispatch, {
                    type: e.type,
                    text: e.message,
                })
            })

        window.Echo.leaveChannel("User." +  process.env.APP_ENV + "." + user.id)


    }

    const fetchToken = async () => {


        if (user == null && token !== null){
            setAuthToken(token)



            await axios.get("/user").then(response => {
                let user = response.data.user;
                setUser(user)
                connectToSocket(token, user)
            }).catch(e => {
                removeToken()
                pushNotification(dispatch, {
                    type: 4,
                    text: "Votre session a expiré",
                })

            })
        }
    }

    const addNotification = (data) => {
        pushNotification(dispatch, {
            type: data.type,
            text: data.text,
        })

    }


    return (

        <div className="layout">
            <div className={"page-content"}>
                <UserContext.Provider value={{user : user, setUser: (v) => setUser(v), token: token, setToken: (v) => setToken(v), removeToken: (v) => removeToken}}>
                    <Outlet/>

                </UserContext.Provider>
            </div>

            <div className={"navBar"}>
                <Link to={"/"} className={"menu-link " + (location.pathname === "/" ? "menu-selected" : "")}>
                    <img src={"/assets/icons/graphique.svg"}/>
                </Link>
                {user != null &&
                <Link to={"/sensors"} className={"menu-link " + (location.pathname === "/sensors" ? "menu-selected" : "")}>
                    <img src={"/assets/icons/capteur.svg"}/>
                </Link>
                }
                {user != null &&
                <Link to={"/users"} className={"menu-link " + (location.pathname === "/users" ? "menu-selected" : "")}>
                    <img src={"/assets/icons/user.svg"}/>
                </Link>
                }
                <Link to={"/settings"} className={"menu-link " + (location.pathname === "/settings" ? "menu-selected" : "")} id={"setting_icons"}>
                    <img src={"/assets/icons/setting.svg"} />
                </Link>


            </div>
        </div>

    )
}


