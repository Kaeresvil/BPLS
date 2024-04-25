<template>
    <div style="text-align: center; color: aliceblue; padding-top: 4%;" >

        <h5 style="font-weight: bold; font-size: 30px; ">CITIZENCARE: Government Business Permit and</h5>
        <h5 style="font-weight: bold; font-size: 30px; "> License Service of Currimao Town</h5>
        <!-- <p style="font-size: 20px; ">ENGAGE WITH US</p> -->

        <a-row :gutter="30" style="width: 50%; margin: 7% auto; ">
        <a-col  class="gutter-row"  :span="12">

            <a style="text-decoration: none; color: black;" @click="$router.push('/business-application')">
            <div class="gutter-box">
             
                <FormOutlined style="font-size: 100px;" />
            <div class="card-body">
                <h5 v-if="isAdmin == 'Applicant' && !loading" class="card-title">Business Application</h5>
                <h5 v-else class="card-title">Review Business Application</h5>

                </div>
          </div>
        </a>

        
        

        </a-col>
        <a-col class="gutter-row" :span="12">

            <a style="text-decoration: none; color: black;"  @click="$router.push('/appointment')">
            <div class="gutter-box">
            <FileDoneOutlined style="font-size: 100px;" />

            <div class="card-body">
            <h5 v-if="isAdmin == 'Applicant' && !loading" class="card-title">Appointment</h5>
            <h5 v-else class="card-title">Review Appointment</h5>

            </div>
            </div>
        </a>

        

        </a-col>
    </a-row>


    </div>
</template>

<script >
import { defineComponent, ref, onMounted, computed} from 'vue';
import axios from "../../axios"
import { useRoute,useRouter } from 'vue-router';

export default defineComponent({
components:{},
props: {
    isLog: {
        type: Boolean
    }
},

setup(props){

    const isLoggedIn = ref(false)
    const loading = ref(false)
    const isAdmin = ref(false)
    const isHeadTeacher = ref(false)
    const route = useRoute()
    const router = useRouter()
    const authUser = ref()
    var self = this;

    onMounted(() =>{

        console.log('mounted')
        loading.value = true
        axios.get('backend/auth_user')
                    .then(response => {
                        loading.value = false
                        isAdmin.value = response.data.role 
                    })
                    

    })

    
    const editRecord = () => {
            router.push({path: '/profile/' +  authUser.value.id,
            })
    }

    
    const logout = (e)=>{

                e.preventDefault()

                    axios.get('backend/logout')
                    .then(response => {
                        window.localStorage.removeItem("BPLS_TOKEN");
                        window.localStorage.removeItem("AUTH_ROLE");
                            window.location.href = "/"
 
                    })
                    .catch(function (error) {
                        console.error(error);
                    });

    }
    return {
        isLoggedIn,
        isAdmin,
        isHeadTeacher,
        authUser,
        loading,
        logout,
        editRecord
    }
}
})


</script>

<style scoped>
.gutter-box{
    background-color: rgb(234, 234, 234);
    border-radius: 10px 10px ;
    padding: 25px 0 25px 0 ;



}


</style>