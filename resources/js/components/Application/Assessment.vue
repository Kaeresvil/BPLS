<template>
    <div>
      <table class="contents">
        <thead>

            <tr>
                <th style="font-size: medium;"> 
                    Local Taxes
                </th>
                <th style="font-size: medium;"> 
                   Amount Due
                </th>
                <th style="font-size: medium;"> 
                    Penalty/Surcharge
                </th>
                <th style="font-size: medium;"> 
                 Total
                </th>
               
            </tr>
        </thead>
        <tbody v-if="!isApplicant">
            <tr
            v-for="(activity, index) in assessments"
            :key="index" 
            >
                <td v-if=" activity.tax != 'TOTAL FEES FOR LGU' &&  activity.tax != 'FIRE SAFETY INSPECTION FEE(10%)' " style="width: 45%;">
                    {{ activity.tax }}
                </td>
                <td  v-else style="width: 45%;">
                   <b>{{ activity.tax }}</b> 
                </td>
                <td>
                    <a-input
                    v-if=" activity.tax != 'TOTAL FEES FOR LGU' &&  activity.tax != 'FIRE SAFETY INSPECTION FEE(10%)' "
                        type="number"
                        id="middle_name"
                        v-model:value=" activity.amount"
                        :min="0"
                        @change="inputAmount(activity, index)"  
                        />

                        <a-space v-else style="font-size: medium;">{{ activity.amount }}</a-space>
                </td>
                <td>
                    <a-input
                    v-if=" activity.tax != 'TOTAL FEES FOR LGU' &&  activity.tax != 'FIRE SAFETY INSPECTION FEE(10%)' "
                        type="number"
                        id="middle_name"
                        v-model:value=" activity.penalty"
                        @change="inputPenalty(activity, index)"  
                        />
                    <a-space v-else style="font-size: medium;">{{ activity.penalty }}</a-space>
                </td>
                <td style="width: 15%;">
                        <a-space  style="font-size: 1rem;">{{ activity.total }}</a-space>
                </td>

            </tr>

        </tbody>
        <tbody v-else>
            <tr
            v-for="(activity, index) in assessments"
            :key="index" 

            >
                <td v-if=" activity.tax != 'TOTAL FEES FOR LGU' &&  activity.tax != 'FIRE SAFETY INSPECTION FEE(10%)' " style="width: 45%;">
                    {{ activity.tax }}
                </td>
                <td  v-else style="width: 45%;">
                   <b>{{ activity.tax }}</b> 
                </td>
                <td  v-if="activity.tax == 'TOTAL FEES FOR LGU'" style="text-align:end;">

                        <a-space style="font-size: medium ;">
                            <b>{{ activity.amount == 0 ?'': formatAmount(activity.amount)}}</b>
                        </a-space>
                </td>
                <td v-else style="text-align:end;">

                        <a-space style="font-size: medium ;">{{ activity.amount == 0 ?'': formatAmount(activity.amount)}}</a-space>
                </td>


                <td  v-if="activity.tax == 'TOTAL FEES FOR LGU'" style="text-align:end;">

                    <a-space  style="font-size: medium;">
                       <b>{{ activity.penalty == 0 ? '':formatAmount(activity.penalty)}}</b> 
                    </a-space>
                </td>
                <td v-else style="text-align:end;">

                    <a-space  style="font-size: medium;">{{ activity.penalty == 0 ? '':formatAmount(activity.penalty)}}</a-space>
                </td>


                <td v-if="activity.tax == 'TOTAL FEES FOR LGU'" style="text-align:end; width: 15%">
                        <a-space  style="font-size: 1rem;">
                           <b>{{ activity.total == 0 ? '-':formatAmount(activity.total)}}</b> 
                        </a-space>
                </td>
                <td v-else style="text-align:end; width: 15%">
                        <a-space  style="font-size: 1rem;">{{ activity.total == 0 ? '-':formatAmount(activity.total)}}</a-space>
                </td>

            </tr>

        </tbody>
      </table> 

      <a-space v-if="!isApplicant" style=" margin-top: 25px;">
                  <a-button
                    type="primary"
                    @click="submitAssessment()"
                    class="buttoncreate"
                    :loading="loading"
                  >
                    Submit
                  </a-button>
                </a-space>



    </div>
</template>

<script>
import { createVNode,defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';

export default defineComponent({
components:{},
props: {
    data: {
      type: Object,
    },
    isApplicant: {
      type: Boolean,
    },
  },
setup(props){
    const form = ref(props.data);
    const application = ref([])
    const loading = ref(false)
    const router = useRouter()
    const route = useRoute()

    const assessments =
      ref([
        {
          id: "",
          application_id: route.params.id,
          key: 1,
          tax: "Gross Sales Tax",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 2,
          tax: "Tax on delivery vans/trucks",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 3,
          tax: "Tax on storage for combustible/flammable of explosive substance",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 4,
          tax: "Tax on signboard/billboards",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 5,
          tax: "Occupation Tax",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 6,
          tax: "REGULATORY FEES AND CHRAGES",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 7,
          tax: "Mayors Permit Fee/Mayor's Clearance",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 8,
          tax: "Garbage Chrages(Dumpsite Fee/Coll.Fee)",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 9,
          tax: "Delivery Trucks/Vans Permit Fee",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 10,
          tax: "Sanitary Inspection Fee",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 11,
          tax: "MENRO Clearance",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 12,
          tax: "Barangay Clearance",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 13,
          tax: "Signboard/Billboard Renewal Fee",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 14,
          tax: "Signboard/Billboard Permit Fee",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 15,
          tax: "Storage and Sale of Combustibale/Flammable or Explosive Substance",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 16,
          tax: "Governor's Permit",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 17,
          tax: "Other's: Business Plate",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 18,
          tax: "TOTAL FEES FOR LGU",
          amount: 0,
          penalty: 0,
          total: 0
        },
        {
          id: "",
          application_id: route.params.id,
          key: 19,
          tax: "FIRE SAFETY INSPECTION FEE(10%)",
          amount: 0,
          penalty: 0,
          total: 0
        },

      ]);

      const formatAmount = (amount) => {
        return  parseFloat(amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
        }
        const index = () => {
            let id = route.params.id
            axios.get(`/backend/assessment/${id}`)
                    .then(res => {
                        if(res.data.length != 0){
                        assessments.value = []
                        assessments.value = res.data
                    }
                    })
                    .catch(function(error) {
                        console.log(error)
                    });
        }
        const submitAssessment = () => {

          Modal.confirm({
        title: () => 'Are you sure? You want to submit the assessment',
        icon: () => createVNode(ExclamationCircleOutlined),
        okText: () => "Yes",
        cancelText: () => "No",
        onOk() {
          return new Promise((resolve, reject) => {

            loading.value = true
            axios.post(`/backend/assessment`,assessments.value)
        .then(res => { 
          setTimeout(Math.random() > 0.5 ? resolve : reject, 2500);
            loading.value = false
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
        }
        const inputAmount = (assessment, index) => {
        assessment.amount = assessment.amount == '' ? 0:assessment.amount
        assessments.value[index].total = parseFloat(assessment.amount) +  parseFloat(assessment.penalty)
        assessments.value[17].amount = 0;
        assessments.value[17].amount = assessments.value.reduce((acc, item) => acc + parseFloat(item.amount), 0);
        assessments.value[17].total = 0;
        assessments.value[17].total = assessments.value.reduce((acc, item) => acc + parseFloat(item.total), 0);

        }
        const inputPenalty= (assessment, index) => {
        assessment.penalty = assessment.penalty == '' ? 0:assessment.penalty
        assessments.value[index].total = parseFloat(assessment.amount) +  parseFloat(assessment.penalty)
        assessments.value[17].penalty = 0;
        assessments.value[17].penalty = assessments.value.reduce((acc, item) => acc + parseFloat(item.penalty), 0);
        assessments.value[17].total = 0;
        assessments.value[17].total = assessments.value.reduce((acc, item) => acc + parseFloat(item.total), 0);

        }


    onMounted(index)

    return {
        assessments,
        loading,
        inputAmount,
        inputPenalty,
        submitAssessment,
        formatAmount
    }
}
})

   
</script>

<style lang="css" scoped>
.title {
  font-weight: bold;
  font-size: 14px;
}
.contents {
  /* border: 2px solid #000; */
  font-size: 10pt;
  width: 100%;
}
.contents thead tr th,
.contents tbody tr td {
  border: 2px solid #000;
  /* text-align: center; */

}
.signature {
  width: 90px;
}

.ant-checkbox css-dev-only-do-not-override-1hsjdkk ant-checkbox-checked ant-checkbox-disabled {
  background-color: #f5f5f5; /* Change the background color */
  border-color: #d9d9d9; /* Change the border color */
}

</style>