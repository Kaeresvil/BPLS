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
           Register Account
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

            <h6>Registrant Profile</h6>

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

        <h6 style="margin: 10px; margin-bottom: -13px;">Disclaimer</h6>
        <a-list  >
            <a-list-item>
                <a-list-item-meta
                description="In accordance to R.A. 10173 or Data Privacy Act, all collected information will be treated with utmost confidentiality and will not be subjected to public disclosure."
                >
                </a-list-item-meta>
            </a-list-item>
           
        </a-list>
      </a-col>
      <a-col class="gutter-row" :span="12">
        <div class="gutter-box">
            <h6>Account Details</h6>

            <a-form-item name="password">
                <a-input
                  type="password"
                  id="password"
                  placeholder="Password"
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
                  placeholder="Verify Password"
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

              <a-form-item class="formItem"  name="files">

                  Upload Valid ID's
                    <a-button class="buttoncreate" type="primary" size="small" @click="visible = true">
                      Add
                    </a-button>
                  </a-form-item>


              <a-table row-key="id" size="small" :columns="columns" :data-source="form.files" bordered style="overflow-x: auto;overflow-y: auto;" >
                <template v-slot:action="{ record }">

                  <a-space>
                      <a-button class="buttondelete" type="danger" size="small" @click="remove(record)" > Remove</a-button>
                  </a-space>
                </template>
            </a-table>


            <a-modal v-model:visible="visible" title="Upload Valid ID" :footer="null">
    
                <a-form
                  :labelCol="{ span: 4 }"
                  :wrapperCol="{ span: 18 }"
                  id="myForm"
                >
                  
                
                  <a-form-item label="File">
                    <a-input
                      type="file"
                      @change="handlefile($event)"
                      ref="fileInput"
                      :accept="acceptedFiles"
                    />
                  </a-form-item>

                  <a-form-item :wrapper-col="{ span: 18, offset: 4 }">
                    <a-space>
                      <a-button key="back" @click="visible = false">
                        Back
                      </a-button>
                      <a-button
                        type="primary"
                        @click="addFile()"
                        class="buttoncreate"
                        :loading="false"
                      >
                        Add
                      </a-button>
                    </a-space>
                  </a-form-item>
                </a-form>
              </a-modal>


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
                Register
              </a-button>

              <div style="text-align: center; margin-top: 10px; margin-bottom: -20px">
                Already have an account?
                <router-link to="/" style="color: #24792f"
                  >SingIn</router-link
                >
              </div>
            </a-form-item>
        </a-form>
    </div>
     
  </template>
  
  <script >
  import { defineComponent, reactive, ref } from "vue";
  import axios from "../axios"
  import { useRouter } from "vue-router";
  import Swal from "sweetalert2";
  import { UploadOutlined } from '@ant-design/icons-vue';
  import { Modal } from "ant-design-vue";

  function getBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}
  
  export default defineComponent({
    components: {
      UploadOutlined,
  },
    setup() {
      const router = useRouter();
      const loading = ref(false)
      const visible = ref(false)
      const acceptedFiles = ref(['.png','.jpeg','.jpg'])

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
      isapplicant: true,
      is_verified: 0,
      file:'',
      files: []
    })

    const columns = [
      {
          title: 'File',
          dataIndex: 'name'
      },
      {
        title: "Action",
        dataIndex: "action",
        slots: { customRender: "action" },
        align: 'center'
      }
    ];



      const handleFinish = async() => {

        const formData = new FormData();
        formData.append("first_name", form.first_name);
        formData.append("last_name", form.last_name);
        formData.append("middle_name", form.middle_name);
        formData.append("extension_name", form.extension_name);
        formData.append("email", form.email);
        formData.append("is_verified", form.is_verified);
        formData.append("password", form.password);
        formData.append("isapplicant", form.isapplicant);
        formData.append("confirmpassword", form.confirmpassword);

        for (var i = 0; i < form.files.length; i++) {
            let file = form.files[i];
            formData.append("users_files[" + i + "]", file);
          }

          if (form.files.length == 0) {
            Swal.fire({
            position: "top-end",
            icon: "error",
            text: "Error! Please upload valid ID.",
            showConfirmButton: false,
            timer: 3000,
            width: "330px"
          });
       
          }else{

          loading.value = true
            await axios.post("register/applicant",formData)
                .then(response => { 
                    form.first_name= '',
                    form.last_name= '',
                    form.middle_name= '',
                    form.extension_name= '',
                    form.email= '',
                    form.password= '',
                    form.confirmpassword= '',
                    loading.value = false,
                    router.push('/')

                        
                })
                .catch(function (error) {
                    loading.value = false
                });
          }


     
      };

      const remove = (row) => {
      form.files.map(function (value, key) {
        if (value.id == row.id) {
            form.files.splice(key, 1);
        }
      });
    };

    const handlefile = (e) => {
      console.log('file', e.target.files[0])

      form.file =  e.target.files[0]
      const input = ref(fileInput);
      input.value = ''; // Clear the selected file
     
       
    };

    const addFile = () => {
      if(form.file != ''){
      form.files.push(form.file)
      form.file = ''
      }else{
        visible.value = false
      }
      console.log(form)

    }




  
      return {
        form,
        loading, 
        columns,
        visible,
        acceptedFiles,
        handleFinish,
        handlefile,
        addFile,

        remove

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
  