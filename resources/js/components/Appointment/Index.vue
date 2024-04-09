<template>
    <div class="conrainer" style="margin:3% 1% 0 1%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 20px; font-weight: bold; color: #312d2dd9;">Appointment List</h4>

                    <!-- <a-button
                    class="buttoncreate"
                    @click="$router.push('/business-application/new')"
             
                    >
                    Create Appoinment
                
                    </a-button> -->
                      
    
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

                </template>

                <template v-slot:taxpayer="{ record }">
                    <span>{{ record.first_name +' '+ record.last_name  }}</span>
                </template>
                <template v-slot:is_claim="{ record }">
                    <span>{{ record.is_claimed == 1 ?'Yes':'No'  }}</span>
                </template>
                <template v-slot:ref_no="{ record }">
                    <span>{{ record.application[0].ref_no }}</span>
                </template>
                <template v-slot:applicant="{ record }">
                    <span>{{ record.applicant.full_name}}</span>
                </template>
                <template v-slot:claimed_date="{ record }">
                    <span>{{ record.date_claimed  == null ? '--': record.date_claimed}}</span>
                </template>
                <template v-slot:claimed_by="{ record }">
                    <span>{{ record.application[0].claimed_by == null ? '--': record.application[0].claimed_by}}</span>
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
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'

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
            title: 'Application Reference No.',
            align: 'center',
            slots: { customRender: 'ref_no' },
        },
        {
            title: 'Appointment Date',
            dataIndex: 'date',
            // slots: { customRender: 'type_business' },
            align: 'center'
        },
        {
            title: 'Claim',
            dataIndex: 'first_name',
            slots: { customRender: 'is_claim' },
            align: 'center'
        },
        {
            title: 'Applicant',
            dataIndex: 'type_of_application',
            slots: { customRender: 'applicant' },
            align: 'center'
        },
        {
            title: 'Claimed By',
            dataIndex: 'status',
            slots: { customRender: 'claimed_by' },
            align: 'center'
        },
        {
            title: 'Claimed Date',
            dataIndex: 'claimed_date',
            slots: { customRender: 'claimed_date' },
            align: 'center'
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
                   axios.get('/backend/appointment', {params: {...payload}})
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
            router.push({path: '/edit/appointment/calendar/' + record.id,
            query: {archive: 'false'}
            })
    }

    onMounted(index)

    return {
        onChange,
        onShowLimit,
        debounce: createDebounce(),
        editRecord,

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