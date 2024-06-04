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
    const [token, setToken] = useState(null)


    useEffect(() => {

        const jsonValue = window.localStorage.getItem("token")

        fetchToken(jsonValue)
        window.Pusher = Pusher

        window.Echo = new Echo({
            broadcaster: process.env.VITE_WS_BROADCASTER,
            key: process.env.VITE_WS_APP_KEY,
            cluster: "eu",
            forceTLS: true})

        return () => {
            window.Echo.leaveAllChannels();
        }

    }, [])




    const connectToSocket = (_token = token, _user = user) => {
        if(!_token || !_user){
            console.log("RTN")
            return;
        }
        console.log(_token, _user)

        if(!_user) return


        setAuthToken(_token)

        window.Echo = new Echo({
            broadcaster: process.env.VITE_WS_BROADCASTER,
            key: process.env.VITE_WS_APP_KEY,
            cluster: "eu",
            forceTLS: true,

            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        }, {
                            headers: {
                                "Authorization":  `Bearer ${_token}`,
                            }
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
        /*if(user === _user || user == null) return;
        window.Echo.leaveChannel("User." +  process.env.APP_ENV + "." + user.id)

         */


    }

    const fetchToken = async (_token = token, _user = user) => {
        if(_token !== null) setToken(_token)
        if(_user !== null) setUser(_user)

        if(token !== _token){
            window.localStorage.setItem("token", _token)
            setAuthToken(_token)

            await axios.get("/user").then(response => {
                let Ruser = response.data.user;
                setUser(Ruser)
                if (Ruser !== null && _token !== null){
                    console.log("connecting to socket")
                    connectToSocket(_token, Ruser)
                }

            }).catch(e => {
                console.log("ICI", e)
                window.localStorage.removeItem("token")
                pushNotification(dispatch, {
                    type: 4,
                    text: "Votre session a expiré",
                })

            })

        }

    }

    const clearToken = () => {
        console.log("clearing token")
        setToken(null)
        setUser(null)
        window.localStorage.removeItem("token")
        setAuthToken(null)
    }

    return (

        <div className="layout">
            <div className={"page-content"}>
                <UserContext.Provider value={{user : user, setUser: (v) => fetchToken(token, v), token: token, setToken: (v) => fetchToken(v), removeToken: () => clearToken()}}>
                    <Outlet/>

                </UserContext.Provider>
            </div>

            <div className={"navBar"}>
                <Link to={"/"} className={"menu-link " + (location.pathname === "/" ? "menu-selected" : "")}>
                    <img src={"/assets/icons/graphique.svg"}/>
                </Link>
                {user != null && user.perm != null && (user.perm.sensor_viewAll || user.perm["*"]) &&
                <Link to={"/sensors"} className={"menu-link " + (location.pathname === "/sensors" ? "menu-selected" : "")}>
                    <img src={"/assets/icons/capteur.svg"}/>
                </Link>
                }
                {user != null && user.perm != null && (user.perm.user_viewAll || user.perm["*"]) &&
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


