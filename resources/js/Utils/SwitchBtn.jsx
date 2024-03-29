import * as React from "react";
import axios from "axios";

export const SwitchBtn = (props) => {

    return (
        <div className={"onoffswitch"}>
            <input type={"checkbox"} className={"onoffswitch-checkbox"} id={"myonoffswitch_" + props.number} tabIndex={"0"} checked={props.checked} onChange={props.callback} disabled={(props.disabled !== undefined ? props.disabled : false)}/>
            <label className={"onoffswitch-label"} htmlFor={"myonoffswitch_" + props.number}>
                <span className={"onoffswitch-inner"}></span>
                <span className={"onoffswitch-switch"}></span>
            </label>
        </div>
    )
}


