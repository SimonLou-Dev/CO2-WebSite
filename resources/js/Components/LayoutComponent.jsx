import * as React from "react";
import {Link, Outlet} from "react-router-dom";
import {useEffect, useState} from "react";
import {v4} from "uuid"
import UserContext from "../Utils/Context/UserContext";
import {useLocalStorage} from "../Utils/StorageGroup";
import {useNotifications} from "../Utils/Context/NotificationProvider";

export const LayoutComponent = () => {
    const [userAuthed, authUser] = useState(false)
    const dispatch = useNotifications()

    const [user, setUser] = useState(null)
    const [token, setToken, removeToken] = useLocalStorage("token", null)

    useEffect(() => {

        if (user == null && token !== null){
            //Faire une request pour vérifier si le token est toujours alive
        }

        addNotification({
            type: 1,
            text: "Salut"
        })


    }, []);

    const addNotification = (data) => {
        let payload = {};
        switch (data.type){
            case 1:
                payload= {
                    id:v4(),
                    type: 'success',
                    message: data.text
                }
                break
            case 2:
                payload= {
                    id:v4(),
                    type: 'info',
                    message: data.text
                }
                break;
            case 3:
                payload= {
                    id:v4(),
                    type: 'warning',
                    message: data.text
                }
                break;
            case 4:
                payload= {
                    id:v4(),
                    type: 'danger',
                    message: data.text
                }
                break;
            default: break;
        }
        dispatch({
            type: 'ADD_NOTIFICATION',
            payload: {
                id: payload.id,
                type: payload.type,
                message: payload.message
            }
        });
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
                {userAuthed &&
                <Link to={"/sensors"} className={"menu-link"}>
                    <img src={"/assets/icons/capteur.svg"}/>
                </Link>
                }
                {userAuthed &&
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


