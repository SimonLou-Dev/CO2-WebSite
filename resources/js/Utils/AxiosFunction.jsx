import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

export  function setAuthToken(token){
    axios.defaults.headers.common['Authorization'] = '';
    delete axios.defaults.headers.common['Authorization'];

    if (token) {
        axios.defaults.headers.common['Authorization'] = `${token}`;
    }
}





