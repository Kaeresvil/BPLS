<template>
    <div class="conrainer" style="margin:3% 2% 0 2%;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <h4 class="card-title my-auto" style="font-size: 15px; font-weight: bold; color: #312d2dd9;">Roles List</h4>

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
                    :data-source="roles.data"
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

                <template v-slot:role="{ record }">
                    <span>{{ record.role.name }}</span>
                </template>


                </a-table>





                
            <a-row style="margin: 15px 15px" v-if="true">
                <a-col class="search" span="24">
                <div>
                    <span
                    >{{ roles.from }} - {{ roles.to }} of
                    {{ roles.total }}</span
                    >
                </div>
                <div>
                    <a-pagination
                    size="small"
                    :total="roles.total"
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
    const roles = ref([])
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
            dataIndex: 'name',
        },
        {
            title: 'Description',
            dataIndex: 'description',
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
                   axios.get('/backend/roles', {params: {...payload}})
                    .then(response => {
                        roles.value = response.data.data;
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
            router.push({path: '/edit/roles/' + record.id,
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
        roles,
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