<template>
             <nav v-if="loading || $route.path.includes('signup')" class="navbar" style="background-color: #f0f0f0; position: fixed; right: 0; left: 0; z-index: 1030; top: 0; height: 60px; ">

            <div  style="width: 100vw; display: flex;">
                <div style=" width: 201px;">
                    <h3 style="margin-top: 7px; margin-left: 8px;">CITIZENCARE</h3>
                </div>
            <div v-if="!$route.path.includes('signup')" style="width: 40vw; margin-left: 2px;">
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
          <!-- <div style=" width: 50%;"> -->
                    <MessageFilled @click="openMes = !openMes, openNotif = false" style="cursor: pointer;  font-size: 23px; margin-left: 32%; margin-top: 10px; color: #404040; "/>
                    <a-badge v-if="!$route.path.includes('signup')"
                        style=" position: relative; top: 1px; left: -7px;cursor: pointer; "
                        @click="openMes = !openMes"
                        :count="messagesTotalCount"
                        :number-style="{
                            backgroundColor: 'rgb(255 41 6)',
                            color: 'white',
                            boxShadow: 'rgb(167 18 18) 0px 0px 0px 1px inset',
                        }"
                        />
                    <div v-if="openMes" class="notificationCss">
                            <div
                                style="
                                text-align: left;
                                width: 100%;
                                padding: 10px;
                                padding-left: 18px;
                                line-height: 35px;
                                background: #2c7dc4;
                                color: white;
                                font-size: 17px;
                                border-radius: 20px 20px 0 0;
                                "
                            >
                                Messages

                                
                                 <div style="float: right; font-size: 14px">
                                <!-- <span
                                    style="cursor: pointer;font-size: 14px"
                                    @click="filterNotif('read')"
                                    :class="isRead === 'read' ? 'activeStatus' : ''"
                                    >User</span
                                > -->

                                <span
                                    style="font-size: 13px; margin-right: 15px;"
    
                                    >{{ authUser.role_id == 3? "BPLO's List":"User's List" }}</span
                                >
                                </div>

                            </div>

                            
                            <div
                                v-for="(user, index) in onlineUsers"
                                :key="index"
                                class="online-users"
                                :class="user.unseen_messages.length > 0 ? 'messUnread' : ''"
                                @click="handleUserClick(user)"
                            >
                                {{ user.full_name }}
                            </div>
                           
                            <!-- <div style=" height: 80px; "> 
                                <h6 style="margin: 10% 28%;">No Messages Available</h6>
                            </div> -->
                           
                            <div
                                class="notif"
                                style="
                                cursor: pointer;
                                text-align: center;
                                font-weight: bold;
                                font-size: 14px;
                                background: #d6d6d6;
                                border-radius: 0 0 20px 20px;
                                "
                                @click="openMes = !openMes"
                            >
                                <!-- See more -->
                            </div>
                            </div>

                            <audio style="display: none" id="chat-tone">
                                    <source src='/assets/google_meet_chat_ping.mp3' type="audio/mpeg" />
                                    Your browser does not support audio element
                            </audio>

                        <!-- CHAT PANEL -->
                        <div class="fixed bottom-0 right-4 z-99999 w-full chat-panel-containers">    
                            <div class=" chat-items">
                                <ChatPanel v-for="panel in chatPanels.panels" :key="panel.selectedUser.id"
               :user="panel.selectedUser" :emitted-message="panel.emittedMessage" :auth="authUser" @onCloseChat="hideChatPanel"/>
                            </div>

                        </div>
                           <!-- CHAT PANEL -->


                    <!-- Notification -->
                    <BellFilled v-if="!$route.path.includes('signup')"  @click="openNotif = !openNotif, openMes = false" style="cursor: pointer;  font-size: 23px; margin-left: 3%; margin-top: 10px; color: #404040; " />
                    <a-badge v-if="!$route.path.includes('signup')"
                        style=" position: relative; top: 1px; left: -7px;cursor: pointer; "
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
                            <div
                                v-for="(notif, index) in notifications"
                                :key="index"
                                class="notif"
                                :class="notif.is_read ? '' : 'notifUnread'"
                                @click="goToNotif(notif)"
                            >
                                {{ notif.description }}
                            </div>
                            <div
                                class="notif"
                                style="
                                cursor: pointer;
                                text-align: center;
                                font-weight: bold;
                                font-size: 14px;
                                border-radius: 0 0 20px 20px;
                                "
                                @click="$router.push('/notification'),openNotif = !openNotif"
                            >
                                See more
                            </div>
                            </div>
                        <!-- </div> -->
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
import axios2 from "axios";
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";
import ChatPanel from "../Chat/ChatPanel.vue";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default defineComponent({
    components: {ChatPanel},
  setup() {
    const router = useRouter();
    const route = useRoute();
    const authUser = ref()
    const loading = ref(false)
    const openNotif = ref(false)
    const openMes = ref(false)
    const visibleModal = ref(false)
    const date = ref()
    const time = ref()
    const day = ref()
    const notificationCount = ref()
    const messagesTotalCount = ref()
    const isRead = ref("All")
    const current = ref(['business_permit']);
    const form = reactive({
    id: '',
    total: '',
    role:''
    })
    const unseen = reactive({
    ids: []
    })
    const notifications = ref([])

    const onlineUsers = ref([]);
    const chatPanels = reactive({
        panels: []
    });

    const getOnlineUsers = () => {
        if(!route.path.includes('signup')){
           axios.get('/backend/online-users')
                    .then(response => {
                        onlineUsers.value = response.data.users
                        let count = 0;
                onlineUsers.value.forEach((data) => {
                    data.unseen_messages.length != 0 ? count++: '';
                });
                messagesTotalCount.value = count;
                      
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                }

        }

        const playChatTone = () => {
            (document.getElementById("chat-tone")).play();
        }

        const handleUserClick = (user) => {
            unseen.ids = [];
            user.unseen_messages.forEach( (messages) =>{
                unseen.ids.push( messages.id)

            })
            sendMessageUpdateRequest(unseen, user.id)
            showChatPanel(user);
        }
        const showChatPanel = (user, emittedMessage = null) => {

            const isPanelOpened = chatPanels.panels.find(panel => panel.selectedUser.id === user.id);


            if(!isPanelOpened) {
                const userPanel = {
                    selectedUser: user,
                    emittedMessage
                };

                chatPanels.panels.push(userPanel);
                openMes.value = false
                return true;
                
            }

            // if the panel already opened
            const index = chatPanels.panels.findIndex(panel => panel.selectedUser.id === user.id);

            chatPanels.panels[index] = {...chatPanels.panels[index], emittedMessage};
            openMes.value = false
            return false;
          
        }

        const hideChatPanel = (user) => {
            const filtered = [...chatPanels.panels].filter(panel => panel.selectedUser.id !== user.id);

            chatPanels.panels = [...filtered];

            // removeChatPanelFromStorage(user);
        }

        const sendMessageUpdateRequest = (unseenIds ,messageId) => {
            axios.put(`/backend/messages/${messageId}`, unseenIds)
                .then(response => {
                        getOnlineUsers();
                });
        }

    const index = (payload = {page: 1, admin: form.role}) => {
          
                   axios.get('/backend/notifications', {params: {...payload}})
                    .then(response => {
                        notifications.value = response.data.data.data;
                        const unread = response.data.data.data.filter(
                        (notif) => notif.is_read === 0
                        );
                        notificationCount.value = unread.length;
                       
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }
    getOnlineUsers();

    const pusherConfig = (auth_id) =>{

        Pusher.logToConsole = true;

        const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                encrypted: true // optional, depending on your Pusher configuration
            });

            // Subscribe to the private channel
            const channel = pusher.subscribe(`private-messages.${auth_id}`);

            // Bind to events on the channel
            channel.bind('message.sent', (e) => {
                const message = e.message;
                showChatPanel(message.sender, message);
                playChatTone();

                // Handle incoming messages
            });

            // Handle subscription success
            channel.bind('pusher:subscription_succeeded', () => {
                console.log('Subscription succeeded');
            });

            // Handle subscription error
            channel.bind('pusher:subscription_error', (status) => {
                console.error('Subscription error:', status);
            });




        // window.Pusher = Pusher;

        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: import.meta.env.VITE_PUSHER_APP_KEY,
        //     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        //     forceTLS: true
        // });

        // window.Echo.private('messages.'+auth_id)
        //             .listen('message.sent', e => {
        //                 const message = e.message;

        //                 console.log('message', message)

        //                 // showChatPanel(message.sender, message);

        //             });
    }
    onMounted(() =>{
        current.value[0] = route.path
    if(!route.path.includes('signup')){
        axios.get('backend/auth_user')
                    .then(response => {
                        loading.value = true
                        authUser.value = response.data
                       form.role = authUser.value.role
                       index();
                       pusherConfig(authUser.value.id);
                    })
        }
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

    const goToNotif = (notif) => {
        axios.put(`/backend/read/${notif.id}`)
        .then(response => { 
            window.location.href = '/edit/business-application/' + response.data.data.application_id
 

                 
        })
        .catch(function (error) {
            loading.value = false
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
        openMes,
        isRead,
        visibleModal,
        notifications,

        onlineUsers,
        chatPanels,
        messagesTotalCount,
        handleUserClick,
        hideChatPanel,

        logout,
        appointment,
        submitOccupancy,
        goToNotif


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
.online-users {
  text-align: left;
  cursor: pointer;
  padding: 15px;
  line-height: normal;
  border-bottom: 0.5px solid #d2d6de;
  /* overflow-y: scroll ; */
}
.online-users:hover {
  background: #b2cfe0;
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
.messUnread {
  background: #a0c4da !important;
  font-weight: bold;
}
.messUnread:hover {
  background: #a7bdca !important;
}
.activeStatus {
  border-bottom: 1px solid white;
}

.chat-panel-containers{
   position: fixed;
   right: 1rem;
   z-index: 99999;


}
.chat-items{
    position: relative;
    /* overflow-x: scroll; */
    display: flex;
    flex-direction: row-reverse;
    margin-left: 1.5rem; /* Adjust as needed */
    margin-bottom: 0.5rem;
}


</style>