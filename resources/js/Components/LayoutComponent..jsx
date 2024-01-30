import * as React from "react";
import {Link, Outlet} from "react-router-dom";
import {useEffect, useState} from "react";
import UserContext from "../Utils/Context/UserContext";
import {useLocalStorage} from "../Utils/StorageGroup";

export const LayoutComponent = () => {
    const [userAuthed, authUser] = useState(false)

    const [user, setUser] = useState(null)
    const [token, setToken, removeToken] = useLocalStorage("token", null)

    useEffect(() => {
        if (user == null && token !== null){
            //Faire une request pour vérifier si le token est toujours alive
        }

    }, []);


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


