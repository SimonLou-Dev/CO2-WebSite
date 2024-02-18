import * as React from "react";
import axios from "axios";
import {useContext, useState} from "react";
import userContext from "../Utils/Context/UserContext";
import {useNavigate} from "react-router-dom";
import {pushNotification, useNotifications} from "../Utils/Context/NotificationProvider";

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

    return (

        <div className="settings">
            <div className={"card"}>
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
                        <input type={"password"} value={loginPassword} onChange={(e) => setLoginPassword(e.target.value)}/>
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
            </div>
            <div className={"card"}>
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
            </div>
        </div>

    )
}


