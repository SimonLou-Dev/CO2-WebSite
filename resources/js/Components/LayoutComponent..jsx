import * as React from "react";
import {Link, Outlet} from "react-router-dom";
import {useEffect, useState} from "react";







export const LayoutComponent = () => {
    const [userAuthed, authUser] = useState(false)

    useEffect(() => {



    }, []);


    return (

        <div className="layout">
            <div className={"page-content"}>
                <Outlet/>
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


