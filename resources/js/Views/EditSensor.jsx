import * as React from "react";
import axios from "axios";
import {useNavigate, useParams} from "react-router-dom";
import {useContext, useEffect, useState} from "react";
import userContext from "../Utils/Context/UserContext";
import UserContext from "../Utils/Context/UserContext";
import {setAuthToken} from "../Utils/AxiosFunction";
import {pushNotification, useNotifications} from "../Utils/Context/NotificationProvider";

export const EditSensor = () => {
    const {id} = useParams();
    const userC = useContext(UserContext)
    const dispatch = useNotifications()
    const navigate = useNavigate()

    const [sensor, setSensor] = React.useState(null);
    const [rooms , setRooms] = React.useState([])

    const [roomSelected, setRoomSelected] = useState();

    useEffect(() => {
        getSensor()
    }, []);

    const getSensor = async () => {
        setAuthToken(userC.token)
        await axios.get("/sensors/" + id).then(response => {
            setSensor(response.data.sensor)
            setRooms(response.data.rooms)
            setRoomSelected(response.data.sensor.room_id)
        })
    }

    const updateSensor = async () => {
        setAuthToken(userC.token)
        await axios.put("/sensors/" + id, {
            room_id: roomSelected,
            device_addr: sensor.device_addr
        }).then(response => {
            pushNotification(dispatch, {
                type: 1,
                text: "Salle modifiée",
            })
            navigate("/sensors")


        })
    }

    return (

        <div className="oneSensor">
            <button className={"btn back-btn"} onClick={() => {
                window.history.back()
            }}>R</button>
            <section className={"sensor-info"}>
                <div className={"sensor-info-form"}>
                    <div className={"card form-card"}>
                        <div className={"form-group"}>
                            <label>Affectation</label>
                            <select defaultValue={0}  disabled={(userC.user && userC.user.perm != null && (userC.user.perm['*'] || userC.user.perm.sensor_update))} value={roomSelected} onClick={(e) => {
                                setRoomSelected(e.target.value)
                            }}>
                                <option value={0} disabled={true}>chargement ...</option>
                                {rooms && rooms.map((room, index) =>
                                    <option key={index} value={room.id}>{room.name + (sensor.room_id === room.id ? " (actuel) " : "")}</option>
                                )}
                            </select>
                        </div>
                        <div className={"form-button"}>
                            <button className={"btn"} disabled={(userC.user && userC.user.perm != null && (userC.user.perm['*'] || userC.user.perm.sensor_update))} onClick={() => updateSensor()}>Enregistrer</button>
                        </div>
                    </div>
                    <div className={"card form-card"}>
                        <div className={"form-group"}>
                            <label>Adresse (devEui)</label>
                            <input type={"text"} disabled={true}
                                   value={(sensor !== null ? sensor.device_addr : "chargement ...")}/>
                        </div>
                        <div className={"form-group"}>
                            <label>Code (.ino)</label>
                            <input type={"text"} disabled={true}/>
                        </div>
                    </div>
                    <button className={"del-btn"}>Supprimer</button>
                </div>
                <div className={"qrCode"}>
                    <div className={"card"}>
                        <img src={(process.env.APP_URL + "/api" + "/sensors/" + id + "/qrcode")}
                             alt={"QR code"}/>
                        <h1>{(sensor !== null ? sensor.id_hex : "Ox....")}</h1>
                    </div>
                </div>
            </section>
        </div>

    )
}


