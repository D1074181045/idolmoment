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
        switch (error.response.status) {
                case 401:
                    document.location.href = "/logout";
                    localStorage.removeItem('token');
                    break;
        }

        return Promise.reject(error.response);
    }
);
