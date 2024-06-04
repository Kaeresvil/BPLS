<template>
    <div>
      <table class="contents">
        <thead>

            <tr>
                <th style="font-size: medium; text-align: center;"> 
                
                <div class="flex-container">
                <div class="flex-item-left">
                    <img style=" width: 70px; margin: auto; margin-top: 13px;  " src="/img/ph-logo.png" />
                </div>
                <div class="flex-item-right"> 
                <span style="font-size: 20px;">OFFICIAL RECEIPT</span> <br>
                <span style="font-size: 12px;">Republic of the  Philippines</span><br>
                  <span style="font-size: 13px;">OFFICE OF THE TREASURER</span><br>
                  <span  style="font-size: 12px;">Province of Ilocos Norte</span><br>
                  <span  style="font-size: 11px; padding-bottom: 5px;">Municipality of Curimmao</span><br></div>
                <div class="flex-item-left">
                    <img style=" width: 75px; margin: 13px 0 13px 15px; " src="/img/curimao-logo.png" />
                </div>
                </div>
                 
                </th>
            </tr>
        </thead>
      </table> 

      <table class="contents">
        <tbody>

            <tr>
                <td style="width: 50%; border-top: none !important; text-align: center; padding: 0 20px 0 20px;">Accountable Form No. 51 Revised August 1994</td>
                <td style=" border-top: none !important; text-align: center;">ORIGINAL</td>

            </tr>
        </tbody>
    </table>
      <table class="contents">
        <tbody>

            <tr>
                <td style="width: 60%; border-top: none !important; ">
                PAYOR: {{ payor }}
                </td>
                <td style=" border-top: none !important; text-align: center;">FUND</td>

            </tr>
        </tbody>
    </table>
      <table class="contents">
        <tbody>

            <tr>
                <td style="width: 50%; border-top: none !important; font-weight: 900; ">
                NATURE OF COLLECTION
                </td>
                <td style=" border-top: none !important; text-align: center; width: 20%; font-weight: 900;">AMOUNT CODE</td>
                <td style=" border-top: none !important; text-align: center; font-weight: 900;">AMOUNT</td>

            </tr>
            <tr v-for="(or, index) in ors" :key="index">
                <td v-if="or.tax != 'TOTAL FEES FOR LGU'">{{  or.tax }}</td>
                <td v-else style="text-align: center; font-weight: bold;">TOTAL</td>
                <td></td>
                <td >{{formatAmount(or.total)}}</td>
            </tr>
        </tbody>
    </table>
    <table class="contents">
        <tbody>

            <tr v-for="(or, index) in ors" :key="index">
                <td v-if="or.tax == 'TOTAL FEES FOR LGU'" style=" border-top: none !important;">AMOUNT IN WORDS: {{ amountToWords(or.total) }}  </td>

            </tr>
        </tbody>
    </table>

    <table class="contents">
        <tbody>

            <tr>
                <td style="width: 50%; border-top: none !important; border-bottom: none !important; ">
                    <a-checkbox style="margin-left: 15px;" disabled checked="true" value="Single"><span style="color: black; ">Cash</span></a-checkbox><br>
                    <a-checkbox style="margin-left: 15px;" disabled value="Single"><span style="color: black">Check</span></a-checkbox><br>
                    <a-checkbox style="margin-left: 15px;" disabled value="Single"><span style="color: black">Money Order</span></a-checkbox>
                </td>
                <td style=" border-top: none !important; text-align: center; width: 20%; ">Drawee Bank</td>
                <td style=" border-top: none !important; text-align: center; ">Number</td>
                <td style=" border-top: none !important; text-align: center; ">Date</td>

            </tr>
            <tr>
                <td style=" border-top: none !important; "></td>
                <td></td>
                <td></td>
                <td>{{ claimed_date }}</td>
            </tr>

        </tbody>
    </table>

    <table class="contents">
        <tbody>

            <tr>
                <td style=" border-top: none !important;">Received the amount stated above
                <br><br>
                <div style="float: right; margin-right: 50px; text-align: center;">
                    {{ collecting_officer }}<br>
                <span style="text-decoration:overline; font-weight: 600;" >COLLECTING OFFICER</span>
             
                </div>
            </td>

            </tr>
            <p>Note: Write the date of this receipt at the back of check or money order received.</p>
        </tbody>
    </table>






    </div>
</template>

<script>
import { createVNode,defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import moment from "moment";

export default defineComponent({
components:{},
props: {
    data: {
      type: Object,
    },
    authName: {
      type: String,
    },
  },
setup(props){
    const form = ref(props.data);
    const ors = ref([])
    const claimed_date = ref()
    const collecting_officer = ref()
    const payor = ref()
    const loading = ref(false)
    const router = useRouter()
    const route = useRoute()


const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
const teens = ["", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
const thousands = ["", "Thousand", "Million"];

function convertHundreds(num) {
    let result = "";
    if (num > 99) {
        result += units[Math.floor(num / 100)] + " Hundred ";
        num %= 100;
    }
    if (num > 10 && num < 20) {
        result += teens[num - 10] + " ";
    } else {
        result += tens[Math.floor(num / 10)] + " ";
        result += units[num % 10] + " ";
    }
    return result.trim();
}

function convertToWords(num) {
    if (num === 0) return "Zero";

    let result = "";
    let i = 0;

    while (num > 0) {
        let remainder = num % 1000;
        if (remainder > 0) {
            result = convertHundreds(remainder) + " " + thousands[i] + " " + result;
        }
        num = Math.floor(num / 1000);
        i++;
    }

    return result.trim();
}

function amountToWords(amount) {
    let pesos = Math.floor(amount);
    let centavos = Math.round((amount - pesos) * 100);

    let pesoWord = convertToWords(pesos) + (pesos === 1 ? " Peso" : " Pesos");
    let centavoWord = convertToWords(centavos) + (centavos === 1 ? " Centavo" : " Centavos");

    if (centavos === 0) {
        return pesoWord;
    }

    return pesoWord + " and " + centavoWord;
}



   
      const formatAmount = (amount) => {
        return  parseFloat(amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
        }
        const index = () => {
            let id = route.params.id
            axios.get(`/backend/official_receipt/${id}`)
                    .then(res => {
                        ors.value = []
                        ors.value = res.data[0]
                        claimed_date.value = moment(res.data[1].claimed_date).format("MM/DD/YYYY") 
                        collecting_officer.value = res.data[1].actioned_by
                        payor.value = res.data[1].payor
                    })
                    .catch(function(error) {
                        console.log(error)
                    });
        }




    onMounted(index)

    return {
        ors,
        loading,
        claimed_date,
        collecting_officer,
        payor,
        amountToWords,
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
  border-radius: 10px 10px;
  margin: auto;
  font-size: 10pt;
  width: 60%;
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

.flex-container {
  display: flex;
  flex-direction: row;
  font-size: 30px;
  text-align: center;
}

.flex-item-left {
  flex: 30%;
}

.flex-item-right {
    line-height: 0.5;
  flex: 70%;
}

</style>