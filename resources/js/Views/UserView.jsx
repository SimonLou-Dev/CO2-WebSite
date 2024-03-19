import * as React from "react";
import axios from "axios";
import {useNavigate} from "react-router-dom";
import {SensorPopup} from "../Components/SensorPopup/SensorPopup";
import {PageNavigator} from "../Components/table/PageNavigator";
import {UpdaterBtn} from "../Components/table/UpdaterBtn";
import {pushNotification, pushNotificationSimply} from "../Utils/Context/NotificationProvider";
import {useContext, useState, useEffect} from "react";
import {Searcher} from "../Components/table/Searcher";
import UserContext from "../Utils/Context/UserContext";
import {setAuthToken} from "../Utils/AxiosFunction";
import {useNotifications} from "../Utils/Context/NotificationProvider";

export const UserView = () => {
    const navigate = useNavigate();
    const userC = useContext(UserContext);
    const dispatch = useNotifications();

    const [page, setPage] = useState(1);
    const [limit, setLimit] = useState(1);
    const [search, setSearch] = useState("");

    const [users, setUsers] = useState([]);
    const [roles, setRoles] = useState([])

    useEffect(() => {
        getUser()
    }, []);

    const getUser = async (_page = page, _search = search) => {
        setSearch(_search)
        setAuthToken(userC.token)
        if(_page > 0 && _page <= limit) setPage(_page)
        else _page = page

        await axios.get("/users", {
            params: {
                page: _page,
                search: _search
            }
        }).then(r => {
            setUsers(r.data.users.data)
            setLimit(r.data.users.last_page)
            setRoles(r.data.roles)
        })

    }


    return (

        <div className="user-viewer">
            <div className={'table-wrapper'}>
                <div className={'table-header'}>

                    <PageNavigator
                        prev={() => {
                            getUser(page - 1)
                        }}
                        next={() => {
                            getUser(page + 1)
                        }}
                        prevDisabled={(page === 1)}
                        nextDisabled={(page === limit)}/>
                    <Searcher callback={(v) => {getUser(page, v)}} value={search} />
                    <UpdaterBtn callback={getUser}/>


                </div>
                <div className={'table-container'}>
                    <table>
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>nom</th>
                            <th className={"phoneHidden"}>email</th>
                            <th className={"phoneHidden"}>dernière connexion</th>
                            <th>role</th>
                            <th>supprimer</th>
                        </tr>
                        </thead>
                        <tbody>
                        {users && users.map((item) =>
                            <tr key={item.id}>
                                <td>{item.id}</td>
                                <td>{item.name}</td>
                                <td className={"phoneHidden"}>{item.email}</td>
                                <td className={"phoneHidden"}>{item.updated_at}</td>
                                <td>
                                    <select defaultValue={0} value={item.role_id} onChange={async (e) => {
                                        await axios.patch("/users/" + item.id + "/role/" + e.target.value,).then(r => {
                                            pushNotificationSimply(dispatch, 1,"role changé")
                                            getUser();
                                        }).catch(e => {
                                            pushNotificationSimply(dispatch, 4,"erreur lors du changement de role")
                                        })
                                    }}>
                                        <option value={0} disabled={true}>veuillez chisir un role</option>
                                        {roles && roles.map((role) =>
                                            <option key={role.id} value={role.id}>{role.name}</option>
                                        )}
                                    </select>
                                </td>
                                <td>
                                    <button className={"btn"} disabled={!(userC.user && userC.user.perm != null && (userC.user.perm.user_delete || userC.user.perm["*"]))}><img src={"/assets/icons/supprimer.svg"}
                                                                   onClick={async () => {
                                                                       await axios.delete("/users/" + item.id).then(response => {
                                                                           pushNotificationSimply(dispatch, 1, "Utilisateur supprimé")
                                                                           getUser();
                                                                       }).catch(() => {
                                                                           pushNotificationSimply(dispatch, 4, "Erreur lors de la suppression")
                                                                       })
                                                                   }}/></button>
                                </td>
                            </tr>
                        )}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    )
}


