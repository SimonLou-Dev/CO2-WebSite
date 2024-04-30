import * as React from "react";
import axios from "axios";
import {SwitchBtn} from "../../Utils/SwitchBtn";
import {setAuthToken} from "../../Utils/AxiosFunction";
import userContext from "../../Utils/Context/UserContext";
import {useEffect} from "react";

export const PermPopup = (props) => {

    const [perms, setPerms] = React.useState([])
    const userC = React.useContext(userContext)


    const fetchPerms = ( ) => {
        setAuthToken(userC.token)
        axios.get("/permissions").then(r => {
            setPerms(r.data.perms)
        })


    }

    const updatePerm = (name, value) => {
        console.log("click")
        setAuthToken(userC.token)
        axios.patch("/permissions/"+name).then(r => {
            fetchPerms()
        })

    }

    useEffect(() => {
        fetchPerms()
    }, props.display);

    if(!props.display) return null
    return (

        <div className="popup">
            <div className={"card"}>
                <div className={"card-header"}>
                    <h3>Permission(s) des modérateurs</h3>
                    <button onClick={() => props.setDisplay(false)} className={"btn"}>fermer</button>
                </div>
                <div className={"card-body"}>
                    <div className={"perm-list"}>
                        <div className={"perm-item"}>
                            <label>Voir la liste des capteurs</label>
                            <SwitchBtn number={4} checked={(perms && perms.sensor_viewAll)}
                                       disabled={(perms && (perms.sensor_delete || perms.sensor_update || perms.sensor_create))}
                                       callback={() => {
                                           updatePerm("sensor_viewAll", !perms.sensor_viewAll)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>supprimer un capteur</label>
                            <SwitchBtn number={1} checked={(perms && perms.sensor_delete)}
                                       disabled={(perms && !perms.sensor_viewAll)}
                                       callback={() => {
                                           updatePerm("sensor_delete", !perms.sensor_delete)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>modifier un capteur</label>
                            <SwitchBtn number={2} checked={(perms &&  perms.sensor_update)}
                                       disabled={perms && !perms["sensor_viewAll"] }
                                       callback={() => {
                                           updatePerm("sensor_update", !perms.sensor_update)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>ajouter un capteur</label>
                            <SwitchBtn number={3} checked={(perms &&  perms.sensor_create)}
                                       disabled={perms && !perms["sensor_viewAll"] }
                                       callback={() => {
                                           updatePerm("sensor_create", !perms.sensor_create)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>mettre à jour les clef chirpstarck</label>
                            <SwitchBtn number={5} checked={perms && perms.update_chirpstack_key} callback={() => {
                                updatePerm("update_chirpstack_key", !perms.update_chirpstack_key)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>voir la liste des utilisateurs</label>
                            <SwitchBtn number={6} checked={(perms &&  perms.user_viewAll)}
                                       disabled={perms && perms["user_delete"] }
                                       callback={() => {
                                           updatePerm("user_viewAll", !perms.user_viewAll)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>supprimer un utilisateur</label>
                            <SwitchBtn number={7} checked={(perms &&  perms.user_delete)}
                                       disabled={perms && !perms["user_viewAll"] }
                                       callback={() => {
                                           updatePerm("user_delete", !perms.user_delete)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>modifier la liste des salles</label>
                            <SwitchBtn number={8} checked={(perms &&  perms.room_modify)} callback={() => {
                                updatePerm("room_modify", !perms.room_modify)
                            }}/>
                        </div>
                        <div className={"perm-item"}>
                            <label>Modifier les seuils de concentration</label>
                            <SwitchBtn number={9} checked={(perms &&  perms.update_threshold)} callback={() => {
                                updatePerm("update_threshold", !perms.update_threshold)
                            }}/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    )
}


