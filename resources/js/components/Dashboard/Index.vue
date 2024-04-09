<template>
             <nav v-if="loading || $route.path.includes('signup')" class="navbar" style="background-color: #f0f0f0; position: fixed; right: 0; left: 0; z-index: 1030; top: 0; height: 60px; ">

            <div  style="width: 100vw; display: flex;">
                <div style=" width: 12vw;">
                    <h3 style="margin-top: 7px; margin-left: 8px;">CITIZENCARE</h3>
                </div>
            <div v-if="!$route.path.includes('signup')" style="width: 40vw;">
                <a-menu v-model:selectedKeys="current" mode="horizontal" style=" background-color:#f0f0f0;">
                    <a-menu-item key="/home" @click="$router.push('/home')">
                    <template #icon>
                        <FormOutlined />
                    </template>
                     Business Permit
                    </a-menu-item>

                    <a-sub-menu key="/users">
                    <template  v-if="authUser.role == 'Admin'" #icon>
                        <SettingOutlined/>
                    </template>
                    <template v-if="authUser.role == 'Admin'" #title>System Settings</template>
                    <a-menu-item-group >
                        <a-menu-item key="/appoinment" @click="appointment()">Appointment</a-menu-item>
                        <a-menu-item key="/users" @click="$router.push('/users')">Users</a-menu-item>
                        <a-menu-item key="/roles" @click="$router.push('/roles')">Roles</a-menu-item>
                    </a-menu-item-group>
                    </a-sub-menu>

                    <a-sub-menu key="/profile">
                    <template #icon>
                        <UserOutlined />
                    </template>
                    <template v-if="loading" #title>Welcome ({{authUser.first_name}})</template>
                    <a-menu-item-group >
                        <a-menu-item key="/profile" @click="$router.push('/profile')">Profile</a-menu-item>
                        <a-menu-item key="setting:2" @click="logout">Logout</a-menu-item>
                    </a-menu-item-group>
                    </a-sub-menu>

                   
                </a-menu>
            </div>

            <a-modal v-model:visible="visibleModal" title="Set Appoinment Calendar Occupancy" :footer="null">
                    <a-form-item class="formItem" label="Count" name="first_name"> 
                        <a-input
                        type="number"
                        id="first_name"
                        v-model:value="form.total" 
                            
                        />
                    </a-form-item>


                        <a-space style="margin-left: 390px; ">
                        <a-button
                            type="primary"
                            @click="submitOccupancy()"
                            class="buttoncreate"
                            :loading="false"
                        >
                            Submit
                        </a-button>
                        </a-space>

             </a-modal>
            <div style="position: relative"></div>
                    <BellFilled  @click="openNotif = !openNotif" style="cursor: pointer; margin-left: 42%; font-size: 23px; margin-top: 10px; color: #404040; " />
                    <a-badge
                        style="position: relative; top: 1px; left: -7px; cursor: pointer;"
                        @click="openNotif = !openNotif"
                        :count="notificationCount"
                        :number-style="{
                            backgroundColor: 'rgb(255 41 6)',
                            color: 'white',
                            boxShadow: 'rgb(167 18 18) 0px 0px 0px 1px inset',
                        }"
                        />
                        <div v-if="openNotif" class="notificationCss">
                            <div
                                style="
                                text-align: left;
                                width: 100%;
                                padding: 10px;
                                line-height: 35px;
                                background: #7d1010;
                                color: white;
                                font-size: 17px;
                                border-radius: 20px 20px 0 0;
                                "
                            >
                                Notifications
                                <!-- <div style="float: right; font-size: 13px">
                                <span
                                    style="cursor: pointer;font-size: 13px"
                                    @click="filterNotif('read')"
                                    :class="isRead === 'read' ? 'activeStatus' : ''"
                                    >Read</span
                                >
                                /
                                <span
                                    style="cursor: pointer;font-size: 13px"
                                    @click="filterNotif('unread')"
                                    :class="isRead === 'unread' ? 'activeStatus' : ''"
                                    >Unread</span
                                >
                                </div> -->
                            </div>
                            <!-- <div
                                v-for="(notif, index) in allNotification"
                                :key="index"
                                class="notif"
                                :class="notif.is_read ? '' : 'notifUnread'"
                                @click="goToNotif(notif)"
                            >
                                Outage Log ID: <b>{{ notif.outage.outage_code }}</b>
                                {{ notif.description }}
                            </div> -->
                            <div
                                class="notif"
                                style="
                                cursor: pointer;
                                text-align: center;
                                font-weight: bold;
                                font-size: 14px;
                                border-radius: 0 0 20px 20px;
                                "
                                @click="$router.push('/notification')"
                            >
                                See more
                            </div>
                            </div>

            </div>
            


        </nav>
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
                    <div class="div-2">
                        <div style="text-align: right; color: aliceblue; margin-top: 5%;">
                            <h5 style="font-weight: bold; margin-bottom: -5px; font-size: 22px; margin-right: 9%;">{{time}}</h5>
                            <p style="margin-right: 4%;">{{date}}({{day}})</p>
                        </div>
                    </div>

                </div>
            <!-- <div class="linebottom"></div> -->

          
            <div>
                <router-view></router-view>
            </div>


    
</template>
<script >
import { defineComponent, onMounted, reactive, ref } from "vue";
import moment from "moment";
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";

export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const authUser = ref()
    const loading = ref(false)
    const openNotif = ref(false)
    const visibleModal = ref(false)
    const date = ref()
    const time = ref()
    const day = ref()
    const notificationCount = ref(5)
    const isRead = ref("All")
    const current = ref(['business_permit']);
    const form = reactive({
    id: '',
    total: ''
    })

    onMounted(() =>{
        current.value[0] = route.path

        axios.get('backend/auth_user')
                    .then(response => {
                        loading.value = true
                        authUser.value = response.data
                    })

        setInterval(() => {
            date.value = moment().format('LL')
            time.value = moment().format('LTS')
            day.value = moment().format('dddd'); 
    }, 1000);
       
    })

    const appointment = () => {
        visibleModal.value = true
        axios.get(`/backend/occupancy`)
                    .then(res => {
                        form.total = res.data.total

                    })
                    .catch(function(error) {
                        console.log(error);
                    });

    };
    const submitOccupancy = () => {
        axios.post(`/backend/occupancy`,form)
        .then(res => { 
            visibleModal.value = false
        })
        .catch(function (error) {
            loading.value = false
        });

    };

        
    const logout = (e)=>{

e.preventDefault()

    axios.get('backend/logout')
    .then(response => {
        console.log('logout',response)
        window.localStorage.removeItem("BPLS_TOKEN");
        window.localStorage.removeItem("AUTH_ROLE");
            window.location.href = "/"

    })
    .catch(function (error) {
        console.error(error);
    });

}


    return {
        date,
        form,
        time,
        day,
        authUser,
        loading,
        current,
        notificationCount,
        openNotif,
        isRead,
        visibleModal,

        logout,
        appointment,
        submitOccupancy


    };
  },
})
</script>

<style scoped>


.linebottom{
    width: 100%;
    height: 5px;
    background-color: #af1818;
   }
.headers{
    margin-top: 60px;
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


.notificationCss {
  position: absolute;
  background: white;
  border-radius: 20px;
  width: 400px;
  border: 1px solid #d2d6de;
  top:62px;
  right: 50px;
  z-index: 9999;
}
.notif {
  text-align: left;
  cursor: pointer;
  padding: 10px;
  line-height: normal;
  border-bottom: 1px solid #d2d6de;
}
.notif:hover {
  background: #b2cfe0;
}
.notifUnread {
  background: #bbe6ff !important;
}
.notifUnread:hover {
  background: #b2cfe0 !important;
}
.activeStatus {
  border-bottom: 1px solid white;
}

</style>