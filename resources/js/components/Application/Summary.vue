<template>
    <div>
        <table class="contents">
        <thead>
            <tr>
                <th style="text-align: left;">Annex 1(Page 1 of 2)</th>
            </tr>
          <tr>
            <th>APPLICATION FORM FOR BUSINESS PERMIT <br>
                TAX YEAR 2024 <br>
                MUNICIPALITY OF CURRIMAO

            </th>
          </tr>
          <tr>
                <th style="text-align: left;">
                   <b>INSTRUCTIONS:</b>
                   <p style="margin-left: 10px;">
                    1. Provide accurate information and print legibly to avoid delays. Incomplete application form will be returned to the applicant. <br>
                    2. Ensure that all documents attached to this form (if any) are complete and properly filled out.
                   </p> 
                
                </th>
            </tr>
            <tr>
                <th style="text-align: left;"> <b>I. APPLICANT SECTION</b></th>
            </tr>
            <tr>
                <th style="text-align: left; padding-left: 15px;"> <b> 1. BASIC INFORMATION</b></th>
            </tr>
        </thead>
    </table>
    <table class="contents">
        <tbody>
          <tr>
            <td style="width: 25%; border-top: none !important;">
                <a-checkbox-group v-model:value="form.type_of_application" style="width: 100%">

                <a-col :span="9">
                    <a-checkbox disabled value="New"> <span style="color: black">New</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Renewal"><span style="color: black">Renewal</span></a-checkbox>
                </a-col>

            </a-checkbox-group>
            </td>
            <td  style="border-top: none !important;">
              <a-checkbox-group v-model:value="form.mode_of_payment" style="width: 100%">
                <a-row style="margin-left: 15px;">
                    <b>Mode of Payment: </b>
                <a-col :span="5">
                    <a-checkbox disabled value="Annually"><span style="color: black">Annually</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Semi"><span style="color: black">Semi-Annually</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Quarterly"><span style="color: black">Quarterly</span></a-checkbox>
                </a-col>
               
                </a-row>
            </a-checkbox-group>
            </td>
          </tr>

          <tr style="text-align: left;">
            <td>
                Date of Application: {{ form.date_app }}
            </td>
            <td>
                DTI/SEC/CDA Registration No.: {{ form.dti_registration_no }}
            </td>
          </tr>

          <tr style="text-align: left;">
            <td>
                TIN NO.: {{ form.tin }}
            </td>
            <td>
                DTI/SEC/CDA Registration Date.:  {{ form.date_dti }}
            </td>
          </tr>

        </tbody>
      </table>
      <table class="contents">
        <tbody>
          <tr>
            <td style="width: 20%; border-top: none !important;">
                <a-checkbox-group v-model:value="form.type_of_business" style="width: 100%">
                    Type of Business:
                <a-col :span="5">
                    <a-checkbox disabled value="Single"><span style="color: black">Single</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Partnership"><span style="color: black">Partnership</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Corporation"><span style="color: black">Corporation</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Cooperative"><span style="color: black">Cooperative</span></a-checkbox>
                </a-col>

            </a-checkbox-group>
            </td>
        </tr>
          <tr>
            <td style="width: 20%; border-top: none !important;">
                <a-checkbox-group v-model:value="form.ammendment_from" style="width: 100%">
                    Ammendment: From
                <a-col :span="5">
                    <a-checkbox disabled value="Single"><span style="color: black">Single</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Partnership"><span style="color: black">Partnership</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Corporation"><span style="color: black">Corporation</span></a-checkbox>
                </a-col>

            </a-checkbox-group>
            </td>
            </tr>
          <tr>
            <td style="width: 20%; border-top: none !important;">
                <a-checkbox-group v-model:value="form.ammendment_to" style="width: 100%; padding-left: 100px;">
                    To
                <a-col :span="5">
                    <a-checkbox disabled value="Single"><span style="color: black">Single</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Partnership"><span style="color: black">Partnership</span></a-checkbox>
                </a-col>
                <a-col :span="5">
                    <a-checkbox disabled value="Corporation"><span style="color: black">Corporation</span></a-checkbox>
                </a-col>

            </a-checkbox-group>
            </td>
         </tr>
          <tr>
            <td style="width: 20%; border-top: none !important;">
                <a-checkbox-group v-model:value="value" style="width: 100%;">
                   Are you enjoying tax incentive from any Government Entity? 
                <a-col :span="3">
                    <a-checkbox disabled value="A"><span style="color: black">YES</span></a-checkbox>
                </a-col>
                <a-col :span="2">
                    <a-checkbox disabled value="B"><span style="color: black">NO</span></a-checkbox>
                </a-col>
                <a-col :span="3">
                    If No please speficy the entity: 
                </a-col>

            </a-checkbox-group>
            </td>
         </tr>
        </tbody>
      </table>
      <table class="contents">
        <thead>
            <tr>
                <th style="border-top: none !important;">
                    Name of Taxpayer/Registrant
                </th>
            </tr>
        </thead>
      </table>
      <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Last Name: {{ form.last_name }}
                </td>
                <td style="border-top: none !important;">
                    First Name:  {{ form.first_name }}
                </td>
                <td style="border-top: none !important;">
                    Middle Name:  {{ form.middle_name }}
                </td>
            </tr>
        </tbody>

      </table>
      <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Business Name:  {{ form.business_name }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Trade Name: {{ form.trade_name }}
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 15px;"> <b style="font-weight: 900;"> 2. OTHER INFORMATION</b> <br>
                    <p style="font-weight: 700; padding-left: 15px;">
                        Note: For renewal applications, do not fill up this section unless certain information have changed.
                    </p>
                
                
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    <b>Business Address: </b>
                </td>
            </tr>
        </tbody>
      </table> 
      <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Postal Code: {{ form.business_postal }}
                </td>
                <td style="border-top: none !important;">
                    Email Address: {{ form.business_address }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Telephone No.: {{ form.business_tel_no }}
                </td>
                <td style="border-top: none !important;">
                    Mobile No.: {{ form.business_mobile_no }}
                </td>
            </tr>
        </tbody>
      </table>

    <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                   <b>Owner's Address: </b> 
                </td>
            </tr>
        </tbody>
      </table>

     <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Postal Code: {{ form.owner_postal }}
                </td>
                <td style="border-top: none !important;">
                    Email Address: {{ form.owner_email }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Telephone No.: {{ form.owner_tel_no }}
                </td>
                <td style="border-top: none !important;">
                    Mobile No.: {{ form.owner_mobile_no }}
                </td>
            </tr>
        </tbody>
      </table>

    <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                   In case of emergency, provide name of contact person: 
                </td>
            </tr>
        </tbody>
      </table>
      <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Mobile No: {{ form.contact_person_mobile_no }}
                </td>
                <td style="border-top: none !important;">
                    Email Address:  {{ form.contact_person_email }}
                </td>
            </tr>
        </tbody>
      </table>
      <table class="contents">
        <tbody>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Business Area (in sq, m):  {{ form.business_area }}
                </td>
                <td style="border-top: none !important;">
                    Total of Employees in Establishment : {{ form.total_employee }} 
                </td>
                <td style="border-top: none !important;">
                    No. of Employees Residing within LGU: {{ form.no_employee }} 
                </td>
            </tr>
        </tbody>
      </table>

      <table class="contents">
        <tbody>

            <tr>
                <td style="text-align: left; border-top: none !important;"> <b style="font-weight: 900;"> Note: Fill Up Only if Business Place is Rented</b> 
                
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Lessor's Full Name: {{ form.lessors_fullname }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Lessor's Full Address: {{  form.lessors_address }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Lessor's Full Telephone/Mobile No.: {{ form.lessors_tel_no }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Lessor's Full Email Address:  {{ form.lessors_email }}
                </td>
            </tr>
            <tr style="text-align: left;">
                <td style="border-top: none !important;">
                    Monthly Rental:  {{ form.monthly_rental }}
                </td>
            </tr>
        </tbody>
      </table> 
      <table class="contents">
        <tbody>

            <tr>
                <td style="text-align: left; padding-left: 15px; border-top: none !important;"> <b style="font-weight: 900;"> 3. BUSINESS ACTIVITY</b> 
                
                </td>
            </tr>
        </tbody>
      </table> 
      <table class="contents">
        <thead>

            <tr>
                <th style="border-top: none !important;"> 
                    Line of Business
                </th>
                <th style="border-top: none !important;"> 
                   No. of Units
                </th>
                <th style="border-top: none !important;"> 
                    Capitalization (for New Business)
                </th>
                <th style="border-top: none !important;"> 
                   Gross/Sales Receipts (Renewal) <br>
                   <b>Essential</b>
                </th>
                <th style="border-top: none !important;"> 
                   Gross/Sales Receipts (Renewal) <br>
                   <b>Non-Essential</b>
                </th>
               
            </tr>
        </thead>
        <tbody>
            <tr
            v-for="(activity, index) in form.business_activity"
            :key="index" 
            >
                <td>
                    {{ activity.line_of_business }}
                </td>
                <td>
                    {{ activity.units}}
                </td>
                <td>
                    {{activity.capitalization}}
                </td>
                <td>
                    {{activity.essential}}
                </td>
                <td>
                    {{activity.non_essential}}
                </td>
            </tr>

        </tbody>
      </table> 


      <div style=" text-align: center; width: 90%; margin: auto;">
        <p style="padding-top: 30px; ">
            <b>I DECLARE UNDER PENALTY OF PERJURY </b> that foregoing informationare true based on my personal knowledge and authentic records. Further, I agree to comply with the regulatory requirement and other deficiencies within 30 days from release of the business permit.
        </p>

      </div>

      <!-- <div style="margin-top: 80px;">
        <p style="text-decoration:overline">
            <b>SIGNATURE OF APPLICANT/TAXPAYER OVER PRINTED NAME</b>
           
        </p>
        <p>
            <b> POSITION/TITLE</b>
        </p>
      </div> -->



    </div>
</template>

<script>
import { defineComponent, ref, onMounted, reactive,toRefs } from 'vue';
import axios from "../../axios"
import { useRouter } from 'vue-router'

export default defineComponent({
components:{},
props: {
    data: {
      type: Object,
    }
  },
setup(props){
    const form = ref(props.data);
    const application = ref([])
    const loading = ref(true)
    const router = useRouter()

        const index = () => {
            console.log('summary',form.value)
        }




    onMounted(index)

    return {
        form
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