import * as React from "react";
import axios from "axios";

export const UpdaterBtn = (props) => {

    return (

        <button className={'btn updater'} onClick={() => props.callback()}>
            <img alt={""} src={'/assets/icons/update.png'}/>
        </button>

    )
}


