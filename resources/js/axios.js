import axios from "axios";

window.axios = axios.create();
window.axios.interceptors.response.use(
    function (response) {
        var token = response.headers.authorization

        if (token) {
            localStorage.token = token.substr(7);
            window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);
        }

        return response.data;
    },
    function (error) {
        return Promise.reject(error.response);
    }
);
