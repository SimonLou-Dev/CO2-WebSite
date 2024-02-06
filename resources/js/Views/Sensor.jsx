import * as React from "react";
import axios from "axios";
import {PageNavigator} from "../Components/table/PageNavigator";
import {Searcher} from "../Components/table/Searcher";
import {UpdaterBtn} from "../Components/table/UpdaterBtn";
import {useEffect, useState} from "react";
import {pushNotification, useNotifications} from "../Utils/Context/NotificationProvider";

export const Sensor = () => {
    const [sensor, setSensors ] = useState([]);
    const [page, setPage] = useState(1);
    const [limit, setLimit] = useState(1);
    const dispatch = useNotifications()


    useEffect(() => {

        getSensor()

    }, []);

    const getSensor = async (_page = page) => {
        if(_page > 0 && _page <= limit) setPage(_page)
        else _page = page


        await axios.get("/sensors",{
            params: {
                page: _page
            }
        })
            .then(response => {
                setSensors(response.data.data)
                setLimit(response.data.last_page)
        }).catch(e => {
            if (e.response.status === 401){
                console.log("Unauthorized")
            }
        });
    }




    return (

        <div className="sensor">
            <div className={'table-header'}>
                <PageNavigator
                    prev={()=>{
                        getSensor(page-1)
                }}
                    next={()=>{
                        getSensor(page+1)
                    }}
                    prevDisabled={(page === 1)}
                    nextDisabled={(page === limit)}/>
                <UpdaterBtn callback={getSensor}/>

            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>salle</th>
                            <th>dernier message</th>
                            <th>dev eui</th>
                            <th>modifier</th>
                            <th>supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                    {sensor && sensor.map((item) =>
                        <tr key={item.id}>
                            <td>{item.id_hex}</td>
                            <td>{item.room_id}</td>
                            <td>{item.last_message}</td>
                            <td>{item.device_addr}</td>
                            <td>
                                <button className={"btn"}><img src={"/assets/icons/editer.svg"}/></button>
                            </td>
                            <td>
                                <button className={"btn"}><img src={"/assets/icons/supprimer.svg"} onClick={async ()=>{
                                    await axios.delete(`/sensors/${item.id}`).then(response => {
                                        pushNotification(dispatch, {
                                            type: 1,
                                            text: "Capteur supprimé",
                                        })
                                        getSensor();
                                    }).catch(()=>{
                                        pushNotification(dispatch, {
                                            type: 4,
                                            text: "Erreur lors de la suppression",
                                        })
                                    })
                                }}/></button>
                            </td>
                        </tr>
                    )}

                    </tbody>
                </table>
            </div>
        </div>

    )
}


