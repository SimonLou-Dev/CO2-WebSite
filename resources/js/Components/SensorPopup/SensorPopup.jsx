import * as React from "react";
import axios from "axios";
import {useEffect, useState} from "react";
import {pushNotification, useNotifications} from "../../Utils/Context/NotificationProvider";

export const SensorPopup = (props) => {
    const [step, setStep] = useState(0)

    const [roomList, setRoomList] = useState([]);
    const dispatch = useNotifications()

    const [sensor, setSensor] = useState({})

    const [currentRoom, setCurrentRoom] = useState(0)
    const [devEui, setDevEui] = useState("")

    const [error, setError] = useState({})


    useEffect(() => {

        getRooms()

    }, []);

    const getRooms = async () => {

        await axios.get("/rooms", ).then((response) => {
            setRoomList(response.data)
        })

    }

    const addSensor = async () => {
        await axios.post("/sensors", {
            room_id: currentRoom,
            device_addr: devEui
        }).then((response) => {
            setSensor(response.data)
            setStep(1)
            setCurrentRoom(0)
            setError({})
            setDevEui("")
            getRooms()
        }).catch((e) => {
            if(e.response.status === 422){
                setError(e.response.data.errors)
            }else if (e.response.status === 500){
                pushNotification(dispatch, {
                    type: 4,
                    text: "Erreur, veuillez vérifier les clefs API",
                })
            }
        })
    }

    if (!props.open) return null

    if (step === 0) return (

        <div className="sensor-popup">
            <div className={"popup-card"}>
                <h2>Ajouter un capteur</h2>
                <div className={"form-group"}>
                    <label>N° de la salle</label>
                    <select defaultValue={0} value={currentRoom} onChange={(e) => {
                        setCurrentRoom(e.target.value)
                    }}>
                        <option value={0} disabled={true}>Choisir une salle</option>
                        {roomList && roomList.map((room) =>
                            <option key={room.id} value={room.id}>{room.name}</option>
                        )}
                    </select>
                    {error.room_id &&
                        <div className={'errors-list'}>
                            <ul>
                                {error.room_id.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={"form-group"}>
                    <label>Adresse (DevEui)</label>
                    <input type={"text"} value={devEui} onChange={(e) => {
                        setDevEui(e.target.value)
                    }}/>
                    {error.device_addr &&
                        <div className={'errors-list'}>
                            <ul>
                                {error.device_addr.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={"form-button"}>
                    <button className={"btn"} onClick={() => props.setOpen(false)}>Fermer</button>
                    <button className={"btn"} onClick={() => addSensor()}>Suivant</button>

                </div>
            </div>
        </div>

    )

    if (step === 1) return (

        <div className="sensor-popup">
            <div className={"popup-card"}>
                <h2>Information du capteur</h2>
                <div className={"form-group"}>
                    <label>Capteur n°</label>
                    <input type={"text"} disabled={true}/>
                </div>
                <div className={"form-group"}>
                    <label>QrCode</label>
                    <input type={"text"} disabled={true}/>
                </div>
                <div className={"form-group"}>
                    <label>Code du capteur (.ino)</label>
                    <input type={"text"} disabled={true}/>
                </div>
                <div className={"form-button"}>
                    <button className={"btn"} onClick={() => {
                        props.setOpen(false)
                        setSensor({})
                        setStep(0)
                    }}>Fermer</button>
                </div>
            </div>
        </div>

    )
}


