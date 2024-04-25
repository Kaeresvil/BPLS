<template>
    <div class="main">
        <div class="headers">
            <div class="div-1">
                <div class="content" style="height: 100px; display: flex;">
                     <img style=" max-width: 100%; height: auto;  margin-left: 15px" src="/img/curimao-logo.png" />
                     <div class="content-1" style="width: 580px;  padding-top: 20px; padding-left: 15px;">
                        <p style="font-weight:bold; font-size: 18px;">BUSINESS PERMIT AND LICENSE SYSTEM</p>
                        <div style="border:2px solid #af1818; margin-top: -17px;"></div>
                        <p style="font-weight:bold; font-size: 18px;">MUNICIPALITY OF CURRIMAO ILOCOS, NORTE</p>
                     </div>
                     
                </div>

            </div>
            <div class="div-2"></div>

        </div>
        <div class="linebottom"></div>

        
      <div style="padding-bottom: 100px">
        
        <div
          class="section"
          style="
            padding: 25px;
            border-radius: 1rem;
            min-width: 400px;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
          align-items: center;
          "
        >
          <h4 style="color:rgb(22, 22, 22); font-size: 20px; font-weight: bold; padding-left: 60px;
          padding-bottom: 20px;">
            Forgot your password?
          </h4>
          <div>
            <span
              style="color: red; display: flex; margin-bottom: 10px"
              v-for="(error, index) in errors"
              :key="index"
            >
              {{ error[0] }}
            </span>
          </div>
          <div>
            <p style="color: black; font-size: small ">
                Please enter the email address you'd like your password reset information sent to
            </p>
          </div>
          <a-form
            id="components-form-demo-normal-login"
            :model="userState"
            :labelCol="{ span: 30 }"
            :wrapperCol="{ span: 25 }"
            @finish="handleFinish"
          >
            <a-form-item>
              <a-input
                type="email"
                placeholder="Email"
                autocomplete="off"
                v-model:value="userState.email"
                required
              >
                <template #prefix>
                  <UserOutlined style="color: rgba(0, 0, 0, 0.25)" />
                </template>
              </a-input>
            </a-form-item>
  
            <a-form-item>
              <a-button
                class="login-form-button"
                html-type="submit"
                :loading="loading"
              >
               Request password reset
              </a-button>

              <div style="text-align: center; margin-top: 10px">
                <router-link to="/" style="color: #24792f"
                  >Back to Sign In</router-link
                >
              </div>
            </a-form-item>
          </a-form>
        </div>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent, reactive, UnwrapRef, ref } from "vue";
  import { ValidateErrorEntity } from "ant-design-vue/es/form/interface";
  import Swal from "sweetalert2";
  import axios from "axios";
  import Axios from "../axios";
  import { useRouter } from "vue-router";
  import { Modal } from "ant-design-vue";
  
  interface FormState {
    email: string;
    password: string;
  }
  
  export default defineComponent({
    setup() {
      const router = useRouter();
      const api = 'http://127.0.0.1:8000/api/';
      const errors = ref([]);
      const loading = ref(false);
  
      const userState: UnwrapRef<FormState> = reactive({
        email: ""
      });
  
      const handleFinish = async (values: FormState) => {

        // window.localStorage.setItem("SIGNLE_SIGNON", "");
        loading.value = true
        await axios
          .post(`${api}forgot`, userState)
          .then((res) => {
            Swal.fire({
            position: "top-end",
            icon: "success",
            text: "New password has been sent to your email.",
            showConfirmButton: false,
            timer: 3000,
            width: "330px"
          });
            loading.value = false
            router.push("/");
          })
          .catch((err) => {
            errors.value = err.response.data.errors;
            loading.value = false
          });
      };
  
  
      const handleFinishFailed = (errors: ValidateErrorEntity<FormState>) => {
        console.log(errors);
      };
  
      return {
        userState,
        errors,
        loading,
        handleFinish,
        handleFinishFailed,
      };
    },
  });
  </script>
  <style lang="css" scoped>
  html,
  body {
    margin: 0px;
    height: 100%;
  }
  
  .login-logo {
    font-size: 25px;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 300;
  }
  
  .login-logo a {
    color: #444;
  }
  
  .main {
    width: 100%;
    height: 500px;
    /* margin-bottom: 30px; */
    /* display: flex; */
    justify-content: center;
    align-items: center;

  }
  
  .section {
    color: #fff;
    background: #2d2d2d;
    padding: 12px;
    margin-right: 4px;
    width: 400px;
    background: red;
    padding: 25px;
    background-color: #fff;
    height: 300px;

    margin: 12% auto;


    /* position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); */


  }
  
  .login-form-button {
    width: 100%;
    background-color: #24792f;
    color: white;
    font-weight: bold;
    font-size: 15px;
  }

  .linebottom{
    width: 100%;
    height: 5px;
    background-color: #af1818;
   }
.headers{
    width: 100%;

    height: 100px;
    display: flex;
  }
  .div-1{
  width: calc( 75% - 40px) ;
  height: 100%;
  background: #e1e1e1;
}

.div-2{
  width: calc( 50% + 40px );
  height: 100%;
  background: #af1818;
  border-left: 50px solid #e1e1e1;
  border-bottom: 100px solid transparent;
  box-sizing: border-box;
}
  </style>
  