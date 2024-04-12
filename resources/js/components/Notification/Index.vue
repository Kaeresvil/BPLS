<template>
    <div class="conrainer" style="margin:3% 1% 0 1%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 20px; font-weight: bold; color: #312d2dd9;">Notifications</h4>

                    <!-- <a-button
                    class="buttoncreate"
                    @click="$router.push('/business-application/new')"
             
                    >
                    Create Appoinment
                
                    </a-button> -->
                      
    
                </div>
                <a-divider />

                <div class="card-container">
                <a-list
                    item-layout="horizontal"
                    :data-source="items.data"
                >
                    <template #renderItem="{ item }">
                    <a-list-item
                        class="notif"
                        style="padding: 10px;cursor: pointer; color: black;"
                        :style="item.is_read ? '' : 'background: #bbe6ff; font-weight: bold;  color: black'"
                        
                        @click="goToNotif(item)"
                    >
                        <a-list-item-meta :description="item.description" >
                        <template #title>
                      
                        </template>

                        </a-list-item-meta>
                    </a-list-item>
                    </template>
                </a-list>
                <br>

                <a-pagination
                    v-model:current="form.page"
                    :total="items.total"
                    :page-size="15"
                    show-less-items
                    @change="changeNotifPage(form.page)"
                />
                </div>

        
        
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'

export default defineComponent({
components:{},
setup(){
    const items = ref([])
    const loading = ref(true)
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
      role:''
    });



        const index = (payload = {page: 1, admin: form.role}) => {
            loading.value = false;
            axios.get('/backend/notifications', {params: {...payload}})
                    .then(response => {
                        items.value = response.data.data;
                        console.log('items', items.value)
                        // const unread = response.data.data.data.filter(
                        // (notif) => notif.is_read === 0
                        // );
                        // notificationCount.value = unread.length;
                       
                    })
                    .catch(function(error) {
                        console.log(error);
                    });


        }
        const changeNotifPage = (page) => {
            index({ page: page , admin: form.role});
            };

            const goToNotif = (notif) => {
        axios.put(`/backend/read/${notif.id}`)
        .then(response => { 
            router.push({path: '/edit/business-application/' + response.data.data.application_id,
            query: {archive: 'false'}
            })
            setTimeout(()=>{
            window.location.reload()
          },100)

                 
        })
        .catch(function (error) {
            loading.value = false
        });

    };

   
    onMounted(() =>{

        axios.get('backend/auth_user')
                    .then(response => {
                       form.role = response.data.role
                       index();
                    })
       
    })

    return {
        changeNotifPage,
        goToNotif,

        form,
        items,
        loading,

    }
}
})

   
</script>

<style scoped>
.ant-table-small .ant-table-thead > tr > th {
    background-color: #bb1111;
}
.search {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  align-content: space-between;
}


.notif:hover {
  background: #b2cfe0;
}
</style>