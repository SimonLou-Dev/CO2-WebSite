import * as React from "react";
import {Link, Outlet} from "react-router-dom";
import {useEffect, useState} from "react";
import {v4} from "uuid"
import UserContext from "../Utils/Context/UserContext";
import {useLocalStorage} from "../Utils/StorageGroup";
import {pushNotification, useNotifications} from "../Utils/Context/NotificationProvider";
import axios from "axios";
import {setAuthToken} from "../Utils/AxiosFunction";

export const LayoutComponent = () => {
    const [userAuthed, authUser] = useState(false)
    const dispatch = useNotifications()

    const [user, setUser] = useState(null)
    const [token, setToken, removeToken] = useLocalStorage("token", null)

    useEffect( () => {

        fetchToken()

    }, [token]);

    const fetchToken = async () => {
        console.log(token)

        if (user == null && token !== null){
            setAuthToken(token)

            await axios.get("/user").then(response => {
                setUser(response.data)
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
                <Link to={"/"} className={"menu-link menu-selected"}>
                    <img src={"/assets/icons/graphique.svg"}/>
                </Link>
                {user != null &&
                <Link to={"/sensors"} className={"menu-link"}>
                    <img src={"/assets/icons/capteur.svg"}/>
                </Link>
                }
                {user != null &&
                <Link to={"/users"} className={"menu-link"}>
                    <img src={"/assets/icons/user.svg"}/>
                </Link>
                }
                <Link to={"/settings"} className={"menu-link"} id={"setting_icons"}>
                    <img src={"/assets/icons/setting.svg"} />
                </Link>


            </div>
        </div>

    )
}


