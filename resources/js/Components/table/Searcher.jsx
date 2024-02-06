import * as React from "react";
import axios from "axios";

export const Searcher = (props) => {

    return (

        <div className={'searcher'}>
            <img src={'/assets/icons/search.png'} alt={''} className={'searcher-icon'}/>
            <input type={'text'} className={'searcher-input'} value={props.value} onChange={(e) => {
                props.callback(e.target.value)
            }}/>
        </div>

    )
}


