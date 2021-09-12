import axios from "axios";
import store from "~/store";

window.axios = axios.create();

if (localStorage.token) {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);
}

window.axios.interceptors.response.use(
    function (response) {
        var token = response.headers.authorization

        if (token) {
            localStorage.token = token.substr(7);
            window.axios.defaults.headers.common['Authorization'] = 'Bearer'.concat(' ', localStorage.token);
        }

        return response.data;
    },
    function (err) {
        switch (err.response.status) {
            case 401:
                document.location.href = "/logout";
                localStorage.removeItem('token');
                break;
            case 422:
            case 429:
                let s = "";
                let errors = err.response.data.errors;
                if (errors) {
                    Object.keys(errors).forEach((error) => {
                        s += errors[error] + '\n';
                    });
                } else {
                    s = err.response.data.message;
                }
                store.commit('show_error', s);
                break;
            default:
                store.commit('show_error', err.response.data.message);
                break;
        }

        return Promise.reject(err.response);
    }
);
