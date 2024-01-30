import React  from "react";

export default React.createContext({
    token: null,
    setToken: () => {},
    removeToken: () => {},
    user: null,
    setUser: () => {},
})
