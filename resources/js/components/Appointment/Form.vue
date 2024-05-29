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
    title="Appointment Calendar"
    @back="$router.push('/appointment')"
  />
        
        <div v-if="!loading" style="margin: -20px 0 0 20px;">
        <a-alert type="info" show-icon style="width: 25%; margin-top: 6px; " :message="`You selected date: ${selectedValue && selectedValue.format('YYYY-MM-DD')}`" />
            <a-button
                    v-if="!$route.path.includes('edit')"
                    style="margin: 10px 0 0 5px; display:inline; border-width: 2px"
                    @click="submitAppointment()"
                    >
                   Submit Appointment 
                
            </a-button>
            <a-button
                v-else
                    style="margin: 10px 0 0 5px; display:inline; border-width: 2px"
                    @click="updateAppointment()"
                    >
                   Update Appointment 
                
            </a-button>
        </div>

        <a-skeleton active  v-if="loading"/>
            <div
            v-else
                style="
                width: 97%;
                border: 1px solid #d9d9d9;
               border-radius: 20px 20px;
               margin:1.5% ;
               padding:10px 10px;
                "
            >
            
          
                <a-calendar :disabled-date="loading ? () => false : disabledDate" :value="date" :mode="'month'" @select="onSelect" @panelChange="onPanelChange"/>
               
            </div>
  </div>

    
</template>

<script >
import { createVNode, defineComponent, reactive, ref, onMounted, watch } from "vue";
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";
import moment from 'moment';
import dayjs from 'dayjs';
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';

export default defineComponent({
setup() {
  const router = useRouter();
  const route = useRoute();
  const loading = ref(false)
  const visible = ref(false);
  const notAvailableDay = ref()
  const isDateAvailable = ref()
  const selectedValue = ref(moment());
//   const api = 'http://127.0.0.1:8000/api/';

  const form = reactive({
  id: '',
  application_id: '',
  date: ''
})

    const date = ref(dayjs());
    const onSelect = value => {
      date.value = value;
      selectedValue.value = value;
      form.date =  moment(value.$d).format("YYYY-MM-DD")
    };
    const onPanelChange = value => {
        date.value = value;
        form.date =  moment(value.$d).format("YYYY-MM-DD")
        loading.value = true;
        axios.get('/backend/schedule/available', {params: {date: form.date}})
                                    .then(response => {
                                        notAvailableDay.value = response.data
                                        loading.value = false;
                                    })
                                    .catch(function(error) {
                                        console.log(error);
                            });
    };

    const disabledDate = value => {

        const dayOfWeek = value.$W; 
        let notAvail = false
        if (notAvailableDay.value) {
        notAvailableDay.value.forEach(item => {
           if( moment(value.$d).format("YYYY-MM-DD") === item){
            notAvail = true
           }
            return notAvail ;

        });
        }

        // Check if the date is in the past
        const isPastDate = value.isBefore(dayjs(), 'day');
        // const isToday = value.isSame(dayjs(), 'day');



        return dayOfWeek === 0 || dayOfWeek === 6 || notAvail || isPastDate 


    };
    const submitAppointment = () => {
      Modal.confirm({
        title: () => 'Are you sure? You want to set the appointment',
        icon: () => createVNode(ExclamationCircleOutlined),
        okText: () => "Yes",
        cancelText: () => "No",
        onOk() {
          return new Promise((resolve, reject) => {

            form.application_id = parseInt(route.params.id)
       loading.value = true,
        axios.post(`backend/appointment`,form)
        .then(response => { 
            loading.value = false,
            setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
            router.push('/appointment')

                 
        })
        .catch(function (error) {
          setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
            loading.value = false
        });

          }).catch(() => console.log('Oops errors!'));
        },
        onCancel() {},
        okButtonProps: {
            style: {
            backgroundColor: '#24792f', 
            borderColor: '#24792f', 
            color: 'white', 
            },
        },
      });
      

    };
    const updateAppointment = () => {
      Modal.confirm({
        title: () => 'Are you sure? You want to update the appointment',
        icon: () => createVNode(ExclamationCircleOutlined),
        okText: () => "Yes",
        cancelText: () => "No",
        onOk() {
          return new Promise((resolve, reject) => {

            loading.value = true,
        axios.put(`/backend/appointment/${route.params.id}`,form)
        .then(res => { 
            loading.value = false
            setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
          router.push('/appointment')
        })
        .catch(function (error) {
          setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
            loading.value = false
        });

          }).catch(() => console.log('Oops errors!'));
        },
        onCancel() {},
        okButtonProps: {
            style: {
            backgroundColor: '#24792f', 
            borderColor: '#24792f', 
            color: 'white', 
            },
        },
      });
    };

    watch(notAvailableDay, () => {
     console.log('watchs');
    });


  onMounted(() =>{

    loading.value = true;
    if(route.path.includes("edit")){
            let id = route.params.id
            selectedValue.value = ''

                   axios.get(`/backend/appointment/${id}`)
                    .then(res => {
                        date.value = dayjs(res.data.date)
                        selectedValue.value = date.value
                        form.date =  moment(res.data.date).format("YYYY-MM-DD")
                        axios.get('/backend/schedule/available', {params: {date: res.data.date}})
                                    .then(response => {
                                        notAvailableDay.value = response.data
                                        loading.value = false;
                                    })
                                    .catch(function(error) {
                                        console.log(error);
                            });

                    })
                    .catch(function(error) {
                        console.log(error);
                    });

    }else if(!route.path.includes("edit")){

      setTimeout(() => {
        axios.get('/backend/schedule/available', {params: {date: moment(date.value).format("YYYY-MM-DD")}})
                    .then(response => {
                      notAvailableDay.value = response.data
                        loading.value = false;
                    })
                    .catch(function(error) {
                        console.log(error);
            });
      }, 1000);

       


    }

    

    });



  return {
    form,
    loading, 
    
    date,
    onSelect,
    onPanelChange,
    submitAppointment,
    updateAppointment,
    disabledDate,
    selectedValue,
    notAvailableDay

  };
},
})
</script>

<style scoped>
  .section {
    border: 1px solid black;
    color: #fff;
    background: #2d2d2d;
    width: 80%;
    background: red;
    background-color: #fff;
    margin: 1% auto;

  }

  .disabled-cell {
  background-color: #910808;
}
</style>