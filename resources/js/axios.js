import axios from "axios";
import Swal from "sweetalert2";



const api = 'http://127.0.0.1:8000/api/';
const axiosRequest = axios.create({
    baseURL: api,
    timeout: 20000,
    headers: {
        Authorization: `Bearer ${window.localStorage.getItem("BPLS_TOKEN")}`,
        // Content-Type: `multipart/form-data`
    }
});

// RESPONSE
// Add a response interceptor
axiosRequest.interceptors.response.use(
    function(response) {
        // Do something with response data
    if(response.config.method != 'get' && response.status != 220){
        Swal.fire({
            position: "top-end",
            icon: "success",
            text: response.data.message,
            showConfirmButton: false,
            timer: 3000,
            width: "330px"
          });
        }

        return response;
    },
    function(error) {
        // Do something with response error
        if (error.response.status == 401) {
            window.localStorage.removeItem("BPLS_TOKEN");
            window.localStorage.removeItem("AUTH_ROLE");
            window.location.href = "/"
           
        }

        if (error.response.status == 422) {
            const item = error.response.data.errors;
            let errors = "";
            for (const key in item) {
                errors += `${item[key]}`;
                break
            }

            Swal.fire({
                icon: "error",
                titleText: 'Failed to process data!',
                text: errors,
                showConfirmButton: true,
                confirmButtonColor: "#24792f",
                });
        }
     

        return Promise.reject(error);
    }
);

export default axiosRequest;
// function else if() {
//     throw new Error("Function not implemented.");
// }
