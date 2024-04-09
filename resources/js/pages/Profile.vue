<template>

        
    <div
      class="section"
      style="
        padding: 10px;
        border-radius: 1rem;
        min-width: 400px;
        box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
      align-items: center;
    
      "
    >

    <h4 style="color:rgb(49, 49, 49); font-weight: bold;
      padding-bottom: 10px; padding-top: 10px;  text-align: center;">
      Profile Account
      </h4>


<a-form
        
        layout="vertical"
        :labelCol="{ span: 30 }"
        :wrapperCol="{ span: 25 }"
        
 >      

    <div class="gutter-example">
<a-row :gutter="8">
  <a-col class="gutter-row" :span="12">
    <div class="gutter-box" >

        <h6>Profile Information</h6>

        <a-form-item label="First Name *" name="first_name">
            <a-input
              type="text"
              id="first_name"
              name="first_name"
              required 
              v-model:value="form.first_name"
            />
          </a-form-item>
        <a-form-item class="formItem" label="Middle Name" name="middle_name">
            <a-input
              type="text"
              id="middle_name"
              v-model:value="form.middle_name" 
                
            />
          </a-form-item>
        <a-form-item class="formItem" label="Last Name *" name="last_name">
            <a-input
              type="text"
              id="last_name"
              required 
                v-model:value="form.last_name" 

            />
          </a-form-item>
        <a-form-item style="margin-bottom: -20px;" class="formItem" label="Extension Name" name="extension_name">
            <a-input
              type="text"
              id="extension_name"
              placeholder="e.g Jr. III" 
              v-model:value="form.extension_name"  
            />
          </a-form-item>

    </div>
  </a-col>
  <a-col class="gutter-row" :span="12">
    <div class="gutter-box">
        <h6>Account Details</h6>

        <a-form-item name="old_password">
            <a-input
              type="password"
              id="old_password"
              placeholder="Old Password"
              required 
                v-model:value="form.old_password"  
            >
            <template #prefix>
              <LockOutlined style="color: rgba(0, 0, 0, 0.25)" />
            </template>
            </a-input>
          </a-form-item>
        <a-form-item name="password">
            <a-input
              type="password"
              id="password"
              placeholder="New Password"
              required 
                v-model:value="form.password"  
            >
            <template #prefix>
              <LockOutlined style="color: rgba(0, 0, 0, 0.25)" />
            </template>
            </a-input>
          </a-form-item>
     
        <a-form-item class="formItem"  name="confirmpassword">
            <a-input
             style="margin-top: 8px;"
              type="password"
              id="confirmpassword"
              placeholder="Verify New Password"
              required
                v-model:value="form.confirmpassword"   
            >
            <template #prefix>
              <LockOutlined style="color: rgba(0, 0, 0, 0.25)" />
            </template>
            </a-input>
          </a-form-item>
        <a-form-item style="margin-bottom: -20px;" class="formItem" label="Email Address *" name="email">
            <a-input
              type="email"
              id="email"
              placeholder="Email Address"
              required
                v-model:value="form.email" 
            >
            <template #prefix>
              <UserOutlined style="color: rgba(0, 0, 0, 0.25)" />
            </template>
            </a-input>
          </a-form-item>
    </div>

  </a-col>

</a-row>
</div>



        <a-form-item>
          <a-button
            class="login-form-button"
            :loading="loading"
            @click="handleFinish()"
           
          >
            Update Profile
          </a-button>
        </a-form-item>
    </a-form>
</div>
 
</template>

<script >
import { defineComponent, onMounted,reactive, ref } from "vue";
import axios from "../axios"
import { useRouter } from "vue-router";
import Swal from "sweetalert2";

export default defineComponent({
setup() {
  const router = useRouter();
  const loading = ref(false)
//   const api = 'http://127.0.0.1:8000/api/';

  const form = reactive({
  id: '',
  first_name: '',
  last_name: '',
  middle_name: '',
  extension_name: '',
  email: '',
  password: '',
  confirmpassword: '',
  old_password: ''
})


    onMounted(() =>{

    console.log('mounted')

    axios.get('backend/auth_user')
                .then(response => {
                   form.id = response.data.id
                   form.first_name = response.data.first_name
                   form.middle_name = response.data.middle_name
                   form.last_name = response.data.last_name
                   form.extension_name = response.data.extension_name
                   form.email = response.data.email
                })
                

    })

  const handleFinish = async() => {

   loading.value = true
   axios.post(`/backend/profile/${form.id}`,form)
        .then(res => { 
            console.log('profile',res);
            form.first_name= res.data.data.first_name,
            form.last_name= res.data.data.last_name,
            form.middle_name= res.data.data.middle_name,
            form.extension_name= res.data.data.extension_name,
            form.email= res.data.data.email,
            form.password = '',
            form.confirmpassword = '',
            form.old_password = '',
            loading.value = false

        })
        .catch(function (error) {
            loading.value = false
        });


 
  };



  return {
    form,
    loading, 
    handleFinish

  };
},
})
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
border: 1px solid black;
color: #fff;
background: #2d2d2d;
width: 65%;
background: red;
background-color: #fff;
height: auto;
margin: 7% auto;


}

.login-form-button {
width: 20%;
margin-top: 20px;
margin-left: 40%;
background-color: #24792f;
color: white;
font-weight: bold;
text-align: center;
/* font-size: 15px; */
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

.gutter-example :deep(.ant-row > div) {
background: transparent;
border: 0;
}
.gutter-box {
color: #2d2d2d;
padding: 10px 10px;
border: 1px solid #af1818;
border-top-width: 5px;
border-radius: 10px;
}

.formItem{
margin-top: -18px;
}
</style>
