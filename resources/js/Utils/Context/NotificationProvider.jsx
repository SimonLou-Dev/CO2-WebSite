import React, {useReducer, useState, createContext, useContext} from "react";
import {v4} from 'uuid';
import NotificationContext from "../../Components/NotificationContext";
import Notification from "../../Components/Notification";




const NotificationsProvider = (props) => {

    const [state, dispatch] = useReducer((state, action) => {
        switch (action.type){
            case 'ADD_NOTIFICATION':
                let mypayload = action.payload[0].payload;
                return [...state, {
                    id: mypayload.id,
                    type: mypayload.type,
                    message: mypayload.message
                }];
            case 'REMOVE_NOTIFICATION':
                return state.filter(el => el.id !== action.id);
            default :
                return state;
        }
    },[
        {}
    ]);


    return (

        <NotificationContext.Provider value={dispatch}>
            <div className={'notification-wrapper'}>
                {state.map(note => {
                    return <Notification dispatch={dispatch} key={note.id} {...note}/>
                })}
            </div>
            {props.children}
        </NotificationContext.Provider>
    )
};

export const useNotifications = () => {
    const dispatch = useContext(NotificationContext)

    return (...props)=>{
        dispatch({
            type: 'ADD_NOTIFICATION',
            payload: {
                ...props
            }
        })
    };
}

export default NotificationsProvider;

export const pushNotification = (dispatch, data) => {
    let payload = {};
    switch (data.type){
        case 1:
            payload= {
                id:v4(),
                type: 'success',
                message: data.text
            }
            break
        case 2:
            payload= {
                id:v4(),
                type: 'info',
                message: data.text
            }
            break;
        case 3:
            payload= {
                id:v4(),
                type: 'warning',
                message: data.text
            }
            break;
        case 4:
            payload= {
                id:v4(),
                type: 'danger',
                message: data.text
            }
            break;
        default: break;
    }
    dispatch({
        type: 'ADD_NOTIFICATION',
        payload: {
            id: payload.id,
            type: payload.type,
            message: payload.message
        }
    });
}

export const pushNotificationSimply = (dispatch, type, text) => {
    pushNotification(dispatch, {
        type: type,
        text: text
    })
}

