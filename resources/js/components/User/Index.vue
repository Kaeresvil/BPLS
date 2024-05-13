<template>
    <div class="conrainer" style="margin:3% 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Users List</h4>
                    <a-button
                    class="buttoncreate"
                    @click="$router.push('/users/new')"
             
                    >
                    New User
                
                    </a-button>
                </div>
                <a-divider />

                <a-row style="margin-bottom: 15px">
                <a-col class="search">
                <a-input-search
                    placeholder="search"
                    style="width: 300px"
                    v-model:value="form.search"
                    @input="
                    debounce(() => {
                        form.search = $event.target.value;
                    })
                    "
                />
                </a-col>
                </a-row>


                <a-table

                    size="small"
                    row-key="id"
                    :columns="columns"
                    :data-source="users.data"
                    :pagination="false"
                    :loading="loading"
                    style="overflow-x: auto"
                >

                <template v-slot:action="{ record }">
                    <a-button
                    class="buttoncreate"
                    @click="editRecord(record)"
                    size="small"
                    >
                   View
                    </a-button>

                 
                <a-button
                    class="buttondelete"
                    size="small"
                    @click="deleteRecord(record)"
                    :disabled="record.role.name == 'Admin' ? true:false"
                    >
                   Delete
                    </a-button>

                    
                </template>

                <template v-slot:role="{ record }">
                    <span>{{ record.role.name }}</span>
                </template>

                <template v-slot:is_verified="{ record }">
                    <a-tag :color="record.is_verified === 1 ? '#24792f' : '#dd4b39'">{{
                    record.is_verified === 1 ? "Yes" : "No"
                }}</a-tag>
                </template>


                </a-table>





                
            <a-row style="margin: 15px 15px" v-if="true">
                <a-col class="search" span="24">
                <div>
                    <span
                    >{{ users.from }} - {{ users.to }} of
                    {{ users.total }}</span
                    >
                </div>
                <div>
                    <a-pagination
                    size="small"
                    :total="users.total"
                    show-size-changer
                    @change="onChange"
                    @showSizeChange="onShowLimit"
                    :current="form.page"
                    :default-page-size="15"
                    :page-size-options="['15', '30', '45', '60']"
                    />
                </div>
                </a-col>
            </a-row>

        
        
            </div>
        </div>
    </div>
</template>

<script>
import { createVNode, defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';

export default defineComponent({
components:{},
setup(){
    const users = ref([])
    const loading = ref(true)
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
    });

    const columns = [
        {
            title: 'Name',
            dataIndex: 'full_name',
        },
        {
            title: 'Email Address',
            dataIndex: 'email',
        },
        {
            title: 'Verify',
            slots: { customRender: 'is_verified' },
            align: 'center'
        },
        {
            title: 'Role',
            dataIndex: 'role.role_name',
            slots: { customRender: 'role' }
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];

        const index = (payload = {page: 1}) => {
            loading.value = true;
                   axios.get('/backend/users', {params: {...payload}})
                    .then(response => {
                        users.value = response.data.data;
                        console.log('users',users.value)
                        loading.value = false;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        }

        const onChange = (payload) => {
        const { page } = toRefs(form);
        page.value = payload;
        filter(form);
        };

    const onShowLimit = (current, pageSize) => {
      const { limit } = toRefs(form);
      const { page } = toRefs(form);
      page.value = 1;
      limit.value = pageSize;
      filter(form);
    };

    function createDebounce() {
      let timeout = null;
      return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const { page } = toRefs(form);
          page.value = 1;
          filter(form);
        }, delayMs || 500);
      };
    }
        const filter = (payload) => {
      index(payload)
    }
        const editRecord = (record) => {
            router.push({path: '/edit/users/' + record.id,
            query: {archive: 'false'}
            })
    }
    const deleteRecord = (record) => {

        Modal.confirm({
        title: () => 'Are you sure? You want to delete this item',
        icon: () => createVNode(ExclamationCircleOutlined),
        okText: () => "Yes",
        cancelText: () => "No",
        onOk() {
          return new Promise((resolve, reject) => {

             axios.delete(`/backend/user/delete/${record.id}`)
            .then(response => { 
                setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
                index()     
            })
            .catch(function (error) {
                setTimeout(Math.random() > 0.5 ? resolve : reject, 100);
                loading.value = false
            });
            
          }).catch(() => console.log('Oops errors!'));
        },
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        onCancel() {},
        okButtonProps: {
            style: {
            backgroundColor: '#24792f', // Change the color of the OK button here
            borderColor: '#24792f', // Change the border color of the OK button here
            color: 'white', // Change the text color of the OK button here
            },
        },
      });
        // axios.delete(`/backend/user/delete/${record.id}`)
        // .then(response => { 
        //     index()     
        // })
        // .catch(function (error) {
        //     loading.value = false
        // });
    }
    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,
        deleteRecord,

        form,
        users,
        loading,
        columns
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

.ant-btn-primary {
  background-color: #0e1318; /* Change the background color of the primary button */
  border-color: #101418; /* Change the border color of the primary button */
}

/* Change the text color of the primary button */
.ant-btn-primary,
.ant-btn-primary:focus,
.ant-btn-primary:hover {
  color: #1f1c1c;
}




</style>