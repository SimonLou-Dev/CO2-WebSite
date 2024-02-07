import * as React from "react";
import axios from "axios";
import {useParams} from "react-router-dom";
import {useContext, useEffect} from "react";
import userContext from "../Utils/Context/UserContext";
import UserContext from "../Utils/Context/UserContext";
import {setAuthToken} from "../Utils/AxiosFunction";

export const EditSensor = () => {
    const {id} = useParams();
    const userC = useContext(UserContext)

    const [sensor, setSensor] = React.useState({});

    useEffect(() => {
        getSensor()
    }, []);

    const getSensor = async () => {
        setAuthToken(userC.token)
        await axios.get("/sensors/" + id).then(response => {
            setSensor(response.data)
        })
    }

    return (

        <div className="oneSensor">
            <button className={"btn back-btn"}>R</button>
            <section className={"sensor-info"}>
                <div className={"sensor-info-form"}>
                    <div className={"card form-card"}>
                        <div className={"form-group"}>
                            <label>Affectation</label>
                            <select defaultValue={0}>
                                <option value={0} disabled={true}>chargement ...</option>
                            </select>
                        </div>
                        <div className={"form-button"}>
                            <button className={"btn"}>Enregistrer</button>
                        </div>
                    </div>
                    <div className={"card form-card" }>
                        <div className={"form-group"}>
                            <label>Adresse (devEui)</label>
                            <input type={"text"} disabled={true} value={"ffffff"}/>
                        </div>
                        <div className={"form-button"}>
                            <button className={"btn"}>Enregistrer</button>
                        </div>
                    </div>
                    <button className={"del-btn"}>Supprimer</button>
                </div>
                <div className={"qrCode"}>
                    <div className={"card"}>
                        <img src={"https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=HelloWorld"}
                             alt={"QR code"}/>
                        <h1>0xff80</h1>
                    </div>
                </div>
            </section>
        </div>

    )
}


