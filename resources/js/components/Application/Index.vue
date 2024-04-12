<template>
    <div class="conrainer" style="margin:3% 1% 0 1%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 20px; font-weight: bold; color: #312d2dd9;">Business Permit List</h4>

                    <a-button
                    class="buttoncreate"
                    @click="$router.push('/business-application/new')"
             
                    >
                    New Application
                
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
                    :data-source="application.data"
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
                    >
                   Delete
                    </a-button>


                </template>

                <template v-slot:taxpayer="{ record }">
                    <span>{{ record.first_name +' '+ record.last_name  }}</span>
                </template>


                </a-table>





                
            <a-row style="margin: 15px 15px" v-if="true">
                <a-col class="search" span="24">
                <div>
                    <span
                    >{{ application.from }} - {{ application.to }} of
                    {{ application.total }}</span
                    >
                </div>
                <div>
                    <a-pagination
                    size="small"
                    :total="application.total"
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
    const application = ref([])
    const loading = ref(true)
    const router = useRouter()
    const form = reactive({
      page: 1,
      limit: 15,
      search: "",
    });

    const columns = [
        {
            title: 'Reference No.',
            dataIndex: 'ref_no',
        },
        {
            title: 'Type of Business',
            dataIndex: 'type_of_business',
            slots: { customRender: 'type_business' },
            align: 'center'
        },
        {
            title: 'Name of Taxpayer/Registrant',
            dataIndex: 'first_name',
            slots: { customRender: 'taxpayer' }
        },
        {
            title: 'Type of Application',
            dataIndex: 'type_of_application',
            slots: { customRender: 'type_application' },
            align: 'center'
        },
        {
            title: 'Status of Application',
            dataIndex: 'status',
            align: 'center'
        },
        {
            title: 'Mode of Payment',
            dataIndex: 'mode_of_payment',
            slots: { customRender: 'mode_payment' },
            align: 'center'
        },
        {
            title: 'Application Date',
            dataIndex: 'date_of_application',
        },
        {
            title: 'Action',
            dataIndex: 'action',
            slots: { customRender: 'action' },
            align: 'center'
        },
        ];

        const index = (payload = {page: 1}) => {
            loading.value = false;
                   axios.get('/backend/application', {params: {...payload}})
                    .then(response => {
                        application.value = response.data.data;
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
            router.push({path: '/edit/business-application/' + record.id,
            query: {archive: 'false'}
            })
    }
        const approval = (record) => {
            router.push({path: '/edit/business-application/' + record.id,
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

                axios.delete(`/backend/application/${record.id}`)
                .then(response => { 
                    setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
                    index()     
                })
                .catch(function (error) {
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
        approval,
        deleteRecord,

        form,
        application,
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



</style>