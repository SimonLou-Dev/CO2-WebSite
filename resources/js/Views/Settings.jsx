import * as React from "react";
import axios from "axios";
import {useContext, useEffect, useState} from "react";
import userContext from "../Utils/Context/UserContext";
import {useNavigate} from "react-router-dom";
import {pushNotification, pushNotificationSimply, useNotifications} from "../Utils/Context/NotificationProvider";
import UserContext from "../Utils/Context/UserContext";
import {setAuthToken} from "../Utils/AxiosFunction";

export const Settings = () => {
    const user = useContext(userContext)
    const navigate = useNavigate()
    const dispatch = useNotifications()


    const [loginEmail, setLoginEmail] = useState()
    const [loginPassword, setLoginPassword] = useState()
    const [loginError, setLoginError] = useState({});

    const [registerEmail, setRegisterEmail] = useState();
    const [registerPassword, setRegisterPassword] = useState()
    const [registerCPassword, setRegisterCPassword] = useState()
    const [registerName, setRegisterName] = useState();
    const [registerError, setRegisterError] = useState({});

    const [chirpstackAppID, setChirpstackAppID] = useState()
    const [chirpstackProfileID, setChirpstackProfileID] = useState()
    const [chirpstackAPIKey, setChirpstackAPIKey] = useState()
    const [newRoom, setNewRoom] = useState()
    const [rooms, setRooms] = useState([])

    useEffect(() => {
        if(user && user.perm != null && (user.perm.update_chirpstack_key|| user.perm["*"])) getKeys()
        if(user && user.perm != null && (user.perm.room_modify || user.perm["*"])) getRoom()



    }, [])

    const getKeys = async ( ) => {
        setAuthToken(user.token)
        await axios.get("/chirpstack/keys").then(response => {
            setChirpstackAppID(response.data.app_id)
            setChirpstackProfileID(response.data.profile_id)
            setChirpstackAPIKey(response.data.api_key)})

    }

    const getRoom = async () => {
        setAuthToken(user.token)
        await axios.get("/rooms").then(response => {
            setRooms(response.data)
        })

    }

    const postKeys = async () => {
        await axios.put("/chirpstack/keys", {
            app_id: chirpstackAppID,
            profile_id: chirpstackProfileID,
            api_key: chirpstackAPIKey
        }).then(response => {
            pushNotification(dispatch, {
                type: 1,
                text: "Clefs modifées",
            })
            getKeys()
        }).catch(e => {
            if(e.response.status === 422){
                setRegisterError(e.response.data.errors)
            }
            pushNotification(dispatch, {
                type: 4,
                text: "Erreur lors de l'ajout de la clef chirpstack",
            })
        })

    }

    const pushRoom = async () => {
        await axios.post("/rooms", {
            name: newRoom,
        }).then(response => {
            pushNotification(dispatch, {
                type: 1,
                text: "Salle ajoutée",
            })
            getRoom()
            setNewRoom("")
        }).catch(e => {
            if(e.response.status === 422){
                setRegisterError(e.response.data.errors)
            }
            pushNotification(dispatch, {
                type: 4,
                text: "Erreur lors de l'ajout d'une salle",
            })
        })

    }

    const register = async (e) => {
        await axios.post("/register", {
            email: registerEmail,
            name: registerName,
            password: registerPassword,
            password_confirmation: registerCPassword,
            device_name: navigator.userAgent
        }).then(response => {

            user.setToken(response.data.token)
            user.setUser(response.data.user)
            pushNotification(dispatch, {
                type: 1,
                text: "Inscription confirmée",
            })
            navigate("/")


        }).catch(e => {
            let response = e.response
            pushNotification(dispatch, {
                type: 4,
                text: "Erreur lors de l'inscription",
            })
            if(response.status === 422){
                setRegisterError(response.data.errors)
            }
        })


    }

    const login = async (e) => {
        await axios.post("/login", {
            email: loginEmail,
            password: loginPassword,
            device_name: navigator.userAgent
        }).then(response => {

            user.setToken(response.data.token)
            user.setUser(response.data.user)
            pushNotification(dispatch, {
                type: 1,
                text: "Connexion confirmée",
            })
            navigate("/")



        }).catch(e => {
            let response = e.response
            pushNotification(dispatch, {
                type: 4,
                text: "Erreur lors de l'inscription",
            })
            if(response.status === 422){
                setLoginError(response.data.errors)
            }
        })
    }

    const logout = async () => {
        setAuthToken(user.token)
        await axios.patch("/logout").then(response => {
            user.removeToken()
            navigate("/")
            pushNotificationSimply(dispatch, 1, "Déconnexion confirmée")
        })
    }

    return (

        <div className="settings">

            {user.user && <button className={"deconnect-btn"} onClick={logout}>Déconnexion</button>}


            {!user.user && <div className={"card"}>
                <h2>Se connecter</h2>
                <div className={"card-content"}>
                    <div className={"form-group"}>
                        <label>Email</label>
                        <input type={"text"} value={loginEmail} onChange={(e) => setLoginEmail(e.target.value)}/>
                        {loginError.email &&
                            <div className={'errors-list'}>
                                <ul>
                                    {loginError.email.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>Mot de passe</label>
                        <input type={"password"} value={loginPassword}
                               onChange={(e) => setLoginPassword(e.target.value)}/>
                        {loginError.password &&
                            <div className={'errors-list'}>
                                <ul>
                                    {loginError.password.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-button"}>
                        <button className={"btn"} onClick={login}>valider</button>
                    </div>
                </div>
            </div>}

            {!user.user && <div className={"card"}>
                <h2>S'inscrire</h2>
                <div className={"card-content"}>
                    <div className={"form-group"}>
                        <label>Name</label>
                        <input type={"text"} onChange={(e) => {
                            setRegisterName(e.target.value)
                        }} value={registerName}/>
                        {registerError.name &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.name.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>Email</label>
                        <input type={"text"} onChange={(e) => {
                            setRegisterEmail(e.target.value)
                        }} value={registerEmail}/>
                        {registerError.email &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.email.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>Mot de passe</label>
                        <input type={"password"} onChange={(e) => {
                            setRegisterPassword(e.target.value)
                        }} value={registerPassword}/>
                        {registerError.password &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.password.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>Confirmer le mot de passe</label>
                        <input type={"password"} onChange={(e) => {
                            setRegisterCPassword(e.target.value)
                        }} value={registerCPassword}/>
                        {registerError.password_confirmation &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.password_confirmation.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-button"}>
                        <button className={"btn"} onClick={register}>valider</button>
                    </div>
                </div>
            </div>}

            {user.user && user.user.perm != null && (user.user.perm.room_modify || user.user.perm["*"]) && <div className={"card"}>
                <h2>Liste des salles</h2>
                <div className={"card-content"}>
                    <div className={"card-table"}>
                        <table>
                            <tbody>
                            {
                                rooms && rooms.map((room) =>
                                    <tr key={room.id}>
                                        <td className={"table-text"}> {room.name}</td>
                                        <td className={"table-btn"}>
                                            <button className={"btn"}><img src={"/assets/icons/supprimer.svg"} onClick={()=>{
                                                setAuthToken(user.token)
                                                axios.delete("/rooms/"+room.id).then(response => {
                                                    getRoom()
                                                }).catch(e => {
                                                    pushNotification(dispatch, {
                                                        type: 4,
                                                        text: "Erreur lors de la suppression",
                                                    })
                                                })
                                            }}/></button>
                                        </td>
                                    </tr>
                                )
                            }
                            </tbody>
                        </table>
                    </div>
                    <div className={"form-group"}>
                        <input type={"log"} onChange={(e) => {
                            setNewRoom(e.target.value)
                        }} value={newRoom}/>
                        {registerError.room_name &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.room_name.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-button"}>
                        <button className={"btn"} onClick={pushRoom}>ajouter</button>
                    </div>
                </div>
            </div>}

            {user.user && user.user.perm != null && (user.user.perm.update_chirpstack_key|| user.user.perm["*"]) && <div className={"card"}>
                <h2>Clef Chirpstack</h2>
                <div className={"card-content"}>
                    <div className={"form-group"}>
                        <label>Application ID</label>
                        <input type={"text"} onChange={(e) => {
                            setChirpstackAppID(e.target.value)
                        }} value={chirpstackAppID}/>
                        {registerError.app_id &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.app_id.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>ID du profile</label>
                        <input type={"text"} onChange={(e) => {
                            setChirpstackProfileID(e.target.value)
                        }} value={chirpstackProfileID}/>
                        {registerError.profile_id &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.profile_id.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-group"}>
                        <label>Clef d'API</label>
                        <textarea rows={4} onChange={(e) => {
                            setChirpstackAPIKey(e.target.value)
                        }} value={chirpstackAPIKey}/>
                        {registerError.api_key &&
                            <div className={'errors-list'}>
                                <ul>
                                    {registerError.api_key.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={"form-button"}>
                        <button className={"btn"} onClick={postKeys}>valider</button>
                    </div>
                </div>
            </div>}

        </div>

    )
}


