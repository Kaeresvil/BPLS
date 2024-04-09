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
    <a-page-header
    title=" Staff Account Registration"
    @back="$router.push('/users')"
  />

    <!-- <h4 style="color:rgb(49, 49, 49); font-weight: bold;
      padding-bottom: 10px; padding-top: 10px;  text-align: center;">
       Register Account
      </h4> -->


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
  </a-col>
  <a-col class="gutter-row" :span="12">
    <div class="gutter-box">
        <h6>Account Details</h6>


        <a-form-item label="Email Address *" name="email">
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

          <a-form-item v-if=" $route.path.includes('edit')" style="margin-bottom: -10px;" class="formItem" label="Verify *" name="verfication">

          <a-radio-group name="radioGroup" v-model:value="form.is_verified">
            <a-radio value=1 >Yes </a-radio>
            <a-radio value=0 >No</a-radio>
        </a-radio-group>


        </a-form-item>

        <a-form-item label="Uploaded ID's" name="email">

        <a-image-preview-group v-for="item in form.files" :key="item">
          <a-image style="margin-left: 2px;" :width="200"  :height="200" :src="'/storage/' + item.file_name" />
        </a-image-preview-group>
      </a-form-item>

    </div>

    <!-- <h6 style="margin: 10px; margin-bottom: -13px;">Disclaimer</h6>
    <a-list  >
        <a-list-item>
            <a-list-item-meta
            description="In accordance to R.A. 10173 or Data Privacy Act, all collected information will be treated with utmost confidentiality and will not be subjected to public disclosure."
            >
            </a-list-item-meta>
        </a-list-item>
       
    </a-list> -->

  </a-col>

</a-row>
</div>



        <a-form-item v-if=" $route.path.includes('new')">
          <a-button
            class="login-form-button"
            :loading="loading"
            @click="handleFinish()"
           
          >
            Register
          </a-button>

        </a-form-item>
        <a-form-item v-else>
          <a-button
            class="login-form-button"
            :loading="loading"
            @click="handleFinish()"
           
          >
            Update
          </a-button>

        </a-form-item>
    </a-form>
</div>
 
</template>

<script >
import { defineComponent, reactive, ref, onMounted } from "vue";
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";

export default defineComponent({
setup() {
  const router = useRouter();
  const route = useRoute();
  const loading = ref(false)
  const visible = ref(false);
//   const api = 'http://127.0.0.1:8000/api/';

  const form = reactive({
  id: '',
  first_name: '',
  last_name: '',
  middle_name: '',
  extension_name: '',
  email: '',
  password: 'bpls-staff',
  confirmpassword: 'bpls-staff',
  isapplicant: false,
  is_verified: 1,
  files:''
})

const setVisible = value => {
  visible.value = value;
};

const columns = [

      {
        title: "Verification ID's",
        dataIndex: "action",
        slots: { customRender: "action" },
        align: 'center'
      }
    ];

  const verification = async(e) => {
    // form.is_verified = e ? 1:0
    console.log('veri', form)
  }
  const handleFinish = async() => {

   loading.value = true

   if(route.path.includes("edit")){
    axios.post(`/backend/user/update/${form.id}`,form)
        .then(response => { 
            loading.value = false,
            router.push('/users')

                 
        })
        .catch(function (error) {
            loading.value = false
        });

   }else{
    await axios.post(`register/applicant`,form)
        .then(response => { 
            form.first_name= '',
            form.last_name= '',
            form.middle_name= '',
            form.extension_name= '',
            form.email= '',
            form.password= '',
            form.confirmpassword= '',
            loading.value = false,
            router.push('/users')

                 
        })
        .catch(function (error) {
            loading.value = false
        });
    }


 
  };

  onMounted(() =>{

        if(route.path.includes("edit")){
        let id = route.params.id
        var list = []
            axios.get(`/backend/user/${id}`)
                .then(res => {
                form.id = id
                form.first_name = res.data.first_name
                form.last_name = res.data.last_name
                    form.middle_name = res.data.middle_name
                    form.extension_name = res.data.extension_name
                    form.email = res.data.email
                    form.password = ''
                    form.confirmpassword = ''
                    form.files = res.data.verification_ids
                    form.is_verified = res.data.is_verified == 1 ? '1':'0'
                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

    

    });



  return {
    form,
    loading, 
    columns,
    handleFinish,
    verification,
    setVisible


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
width: 75%;
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
