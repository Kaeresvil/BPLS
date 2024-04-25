<template>
      
    <div
      class="section"
      style="
        padding: 10px;
        border-radius: 1rem;
        min-width: 400px;
        box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
      "
    >

    <a-skeleton v-if="loading2" active />
    <div v-if="!loading2">
    <a-page-header
    title=" BUSINESS PERMIT APPLICATIONS"
    @back="$router.push('/business-application')"
  />


    <!-- <h4 style="color:rgb(49, 49, 49); font-weight: bold;
      padding: 15px; ">
     BUSINESS PERMIT APPLICATIONS
      </h4> -->


<a-form
        
        layout="vertical"
        :labelCol="{ span: 30 }"
        :wrapperCol="{ span: 25 }"
        style="margin: 10px;"
        
 >   
 
  <!--Application Form -->
 
 <div v-if="$route.path.includes('edit') && !isApplicant || form.status == 'APPROVED' || form.status == 'CLAIMED'" style="margin-top: -20px; width: 70%; margin: auto;" class="steps-content">
        <a-row style="justify-content: center;">
            <h3 >Application Form  <h6 style="margin-top: 5px">( {{ form.ref_no +" "+ form.status}} )</h6> </h3>   
        </a-row>
         




        <Summary
        :data="form"
        ></Summary>

        <a-modal v-model:visible="visibleSummaryForm" title="Uploaded Documents" :footer="null">
          <div>

            <a-image-preview-group v-for="item in form.files" :key="item">
          <a-image style="margin-left: 2px;" :width="235"  :height="200" :src="'/storage/' + item.file_name" />
        </a-image-preview-group>
        </div>

        <a-textarea v-model:value="form.remarks" placeholder="Remarks" :rows="4" />


                <a-space v-if="form.status != 'APPROVED' && form.status != 'CLAIMED'" style="margin-left: 290px; margin-top: 15px;">
                  <a-button class="buttondelete" key="back" @click="returnApplication()" :loading="loading">
                    Return
                  </a-button>
                  <a-button
                    type="primary"
                    @click="approveApplication()"
                    class="buttoncreate"
                    :loading="loading"
                  >
                    Approve
                  </a-button>
                </a-space>

          </a-modal>
          <a-button
             style="margin-right: 5px"
                    @click="$router.push('/business-application')"
                    key="back"
                  >
                    Back
            </a-button>
          <a-button
            style="margin-right: 5px"
                    @click="visibleSummaryForm = true"
                    class="buttoncreate"
                    :loading="false"
                  >
                    Documents
            </a-button>
          <a-button
          v-if="form.status == 'APPROVED' && !isApplicant"
                    @click="applicationClaimed()"
                    class="buttonshow"
                    :loading="false"
                  >
                    Claimed
            </a-button>
          <a-button
          v-if="form.status == 'APPROVED' && isApplicant"
                    @click="toAppointment()"
                    class="buttonshow"
                    :loading="false"
                  >
                    Appoinment to claim
            </a-button>




      



    </div>

    <!-- creation and update Form -->

    <div v-else class="gutter-example">
<a-row :gutter="30">

    <a-col class="gutter-row" :span="6">
 
        <div class="steps-content-1" style="margin-top: -5px;" >
          <h4>{{ form.ref_no }}</h4>
          <br>

        <h6>TYPE OF APPLICATION</h6>

        <a-radio-group name="radioGroup" v-model:value="form.type_of_application">
            <a-radio value="New">New </a-radio>
            <a-radio value="Renewal">Renewal</a-radio>
        </a-radio-group>

        <a-form-item  label="Mode of Payment *" name="mode_of_payment" style="margin-top: 15px;">

            <a-select
            ref="select"
            v-model:value="form.mode_of_payment"
            placeholder="Mode of Payment"
            @focus="focus"
            @change="handleChange"
            >
            <a-select-option value="Annually">Annually</a-select-option>
            <a-select-option value="Semi">Semi-Annually</a-select-option>
            <a-select-option value="Quarterly">Quarterly</a-select-option>
            </a-select>
                   
            
         </a-form-item>
        <a-form-item  label="Date of Application *" name="date_of_application">
            <a-date-picker 
            v-model:value="form.date_of_application"
            @change="selectDateApplication"
            
            type="date"
  
             required />
            
        </a-form-item>

        <a-form-item v-if="form.status == 'RETURNED'" label="Returned Remarks" name="remarks" >
        <a-textarea v-model:value="form.remarks" placeholder="Remarks" :rows="4" />
      </a-form-item>




       </div>
  
  </a-col>
        
  <a-col class="gutter-row" :span="18">
  <div>


    <a-steps style="border: 1px solid rgb(235, 237, 240); height: 100px; padding: 3% 15px 0 15px;" :current="current" :items="items"></a-steps>

    <div class="steps-action-next">
      <a-button   class="buttoncreate" v-if="current < steps.length - 1" type="primary" @click="next">Next</a-button>
      <a-button
        v-if="current == steps.length - 1 && !$route.path.includes('edit')"
        class="buttonshow"
        @click="submitApplication"
        :loading="loading"
      >
        Submit
      </a-button>
      <a-button
        v-if="current == steps.length - 1 && $route.path.includes('edit')"
        class="buttoncreate"
        @click="submitApplication"
        :loading="loading"
      >
        Update
      </a-button>
 
 
    </div>
    <div class="steps-action-prev">
      <a-button v-if="current > 0" style="margin-left: 8px;" @click="prev">Previous</a-button>
    </div>

    <!-- step 1 -->
    <div v-if="steps[current].content == 'basic-info'" class="steps-content">

      <!-- {{ steps[current].content }} -->

      <a-row>
            <h3 style="margin-bottom: 20px;">1. BASIC INFORMATION</h3>
        </a-row>

        <a-row :gutter="30">
          
            <a-col class="gutter-row" :span="12">
            <div class="gutter-box" >

                <h6 style="margin-bottom: 20px;">BASIC INFORMATION</h6>

                <a-form-item  label="TIN NO. *" name="tin">
                    <a-input
                    type="text"
                    id="tin"
                    name="tin"
                    required 
                    v-model:value="form.tin"
                    placeholder="TIN No."
                    />
                </a-form-item>
                <a-form-item class="formItem" label="Type of Business *" name="type_of_business"> 

                    <a-select
                        ref="select"
                        v-model:value="form.type_of_business"
                        placeholder="Type of Business"
                        @focus="focus"
                        @change="handleChange"
                        >
                        <a-select-option value="Single">Single</a-select-option>
                        <a-select-option value="Partnership">Partnership</a-select-option>
                        <a-select-option value="Corporation">Corporation</a-select-option>
                        <a-select-option value="Cooperative">Cooperative</a-select-option>
                    </a-select>
          
                </a-form-item>
                <a-form-item class="formItem" label="Ammendment *" name="ammendment_from">
                    <span>From</span>
                    <a-select
                        ref="select"
                        v-model:value="form.ammendment_from"
                        placeholder="From"
                        @focus="focus"
                        @change="handleChange"
                        >
                        <a-select-option value="Single">Single</a-select-option>
                        <a-select-option value="Partnership">Partnership</a-select-option>
                        <a-select-option value="Corporation">Corporation</a-select-option>
                    </a-select>
                    <span>To</span>

                    <a-select
                        ref="select"
                        
                        v-model:value="form.ammendment_to"
                        placeholder="To"
                        @focus="focus"
                        @change="handleChange"
                        >
                        <a-select-option value="Single">Single</a-select-option>
                        <a-select-option value="Partnership">Partnership</a-select-option>
                        <a-select-option value="Corporation">Corporation</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item class="formItem" label="DTI/SEC/CDA Registration No *" name="dti_registration_no"> 
                    <a-input
                    type="text"
                    id="dti_registration_date"
                    v-model:value="form.dti_registration_no" 
                    placeholder="DTI/SEC/CDA Registration No"
                        
                    />
                </a-form-item>

                <a-form-item class="formItem" label="DTI/SEC/CDA Registration Date *" name="dti_registration_date"> 
                    <a-date-picker 
                    v-model:value="form.dti_registration_date"
                    style="width: 100%;"
                    @change="selectDateDTI"
                    required />

                </a-form-item>



            </div>
            </a-col>

            <a-col class="gutter-row" :span="12">
            <div class="gutter-box" >
                <h6 style="margin-bottom: 20px;">NAME OF TAXPAYER/REGISTRANT</h6>

                <a-form-item class="formItem" label="Last Name *" name="last_name"> 
                    <a-input
                    type="text"
                    id="last_name"
                    v-model:value="form.last_name" 
                    placeholder="Last Name"
                        
                    />
                </a-form-item>
                <a-form-item class="formItem" label="First Name *" name="first_name"> 
                    <a-input
                    type="text"
                    id="first_name"
                    v-model:value="form.first_name" 
                    placeholder="First Name"
                        
                    />
                </a-form-item>
                <a-form-item class="formItem" label="Middle Name *" name="middle_name"> 
                    <a-input
                    type="text"
                    id="middle_name"
                    v-model:value="form.middle_name" 
                    placeholder="Middle Name"
                        
                    />
                </a-form-item>
                <a-form-item class="formItem" label="Business Name *" name="business_name"> 
                    <a-input
                    type="text"
                    id="business_name"
                    v-model:value="form.business_name" 
                    placeholder="Business Name"
                        
                    />
                </a-form-item>
                <a-form-item class="formItem" label="Trade Name/Franchise *" name="trade_name"> 
                    <a-input
                    type="text"
                    id="trade_name"
                    v-model:value="form.trade_name" 
                    placeholder="Trade Name/Franchise"
                        
                    />
                </a-form-item>

                

            </div>
            </a-col>
        </a-row>

      

    </div>

    <!-- step 2 -->
    <div v-if="steps[current].content == 'other-info'" class="steps-content">
        <a-row>
            <h3 style="margin-bottom: 20px;">2. OTHER INFORMATION</h3>
        </a-row>

        <a-row :gutter="30">
          
            <a-col class="gutter-row" :span="12">
            <div class="gutter-box" >

                <h6 style="margin-bottom: 20px;">BUSINESS ADDRESS</h6>

                <a-form-item  label="Business Address *" name="business_address">
                        <a-input
                        type="text"
                        id="business_address"
                        name="business_address"
                        required 
                        v-model:value="form.business_address"
                        placeholder="Address"  
                        />
                    </a-form-item>
                    <a-form-item class="formItem" label="Postal Code *" name="business_postal"> 
                        <a-input
                        type="text"
                        id="business_postal"
                        v-model:value="form.business_postal"
                        placeholder="Postal Code"  
                            
                        />
                    </a-form-item>
                    <a-form-item class="formItem" label="Telephone No. *" name="business_tel_no"> 
                        <a-input
                        type="text"
                        id="business_tel_no"
                        v-model:value="form.business_tel_no" 
                        placeholder="Tel. No." 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Email Address *" name="business_email"> 
                        <a-input
                        type="email"
                        id="business_email"
                        v-model:value="form.business_email" 
                        placeholder="Email Address" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Mobile No. *" name="business_mobile_no"> 
                        <a-input
                        type="text"
                        id="business_mobile_no"
                        v-model:value="form.business_mobile_no" 
                        placeholder="(+63)" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Business Area(in sq. m.) *" name="business_area"> 
                        <a-input
                        type="text"
                        id="business_area"
                        v-model:value="form.business_area" 
                        placeholder="Business Area(in sq. m.)" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Total Employees in Establishment *" name="total_employee"> 
                        <a-input
                        type="text"
                        id="total_employee"
                        v-model:value="form.total_employee" 
                        placeholder="Total Employees in Establishment" 
                            
                        />
                    </a-form-item>
                    <a-form-item class="formItem" label="No. of Employees Residing within LGU *" name="no_employee"> 
                        <a-input
                        type="text"
                        id="no_employee"
                        v-model:value="form.no_employee" 
                        placeholder="No. of Employees Residing within LGU" 
                            
                        />
                    </a-form-item>

                    <span style="font-weight: 700;"><i>Note: Fill Up Only if Business Place is Rented</i></span>

                    <a-form-item style="margin-top: 25px;" class="formItem" label="Lessor's Full Name" name="lessors_fullname"> 
                        <a-input
                        type="text"
                        id="lessors_fullname"
                        v-model:value="form.lessors_fullname" 
                        placeholder="Lessor's Full Name"
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Lessor's Full Address" name="lessors_address"> 
                        <a-input
                        type="email"
                        id="lessors_address"
                        v-model:value="form.lessors_address"
                        placeholder="Lessor's Full Address" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Lessor's Full Tel./Mobile No." name="lessors_tel_no"> 
                        <a-input
                        type="email"
                        id="lessors_tel_no"
                        v-model:value="form.lessors_tel_no"
                        placeholder="Lessor's Full Tel./Mobile No." 
                            
                        />
                    </a-form-item>


                    <a-form-item class="formItem" label="Lessor's Email Address" name="lessors_email"> 
                        <a-input
                        type="email"
                        id="lessors_email"
                        v-model:value="form.lessors_email"
                        placeholder="Lessor's Email Address" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Monthly Rental" name="monthly_rental"> 
                        <a-input
                        type="email"
                        id="monthly_rental"
                        v-model:value="form.monthly_rental"
                        placeholder="Monthly Rental" 
                            
                        />
                    </a-form-item>



            </div>
            </a-col>

            <a-col class="gutter-row" :span="12">
            <div class="gutter-box" >
                <h6 style="margin-bottom: 20px;">OWNER'S ADDRESS</h6>

                    <a-form-item  label="Owner Address *" name="owner_address">
                        <a-input
                        type="text"
                        id="owner_address"
                        name="owner_address"
                        required 
                        v-model:value="form.owner_address"
                        placeholder="Address"  
                        />
                    </a-form-item>
                    <a-form-item class="formItem" label="Postal Code *" name="owner_postal"> 
                        <a-input
                        type="text"
                        id="owner_postal"
                        v-model:value="form.owner_postal"
                        placeholder="Postal Code"  
                            
                        />
                    </a-form-item>
                    <a-form-item class="formItem" label="Telephone No. *" name="owner_tel_no"> 
                        <a-input
                        type="text"
                        id="owner_tel_no"
                        v-model:value="form.owner_tel_no" 
                        placeholder="Tel. No." 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Email Address *" name="owner_email"> 
                        <a-input
                        type="email"
                        id="owner_email"
                        v-model:value="form.owner_email" 
                        placeholder="Email Address" 
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Mobile No. *" name="owner_mobile_no"> 
                        <a-input
                        type="text"
                        id="owner_mobile_no"
                        v-model:value="form.owner_mobile_no" 
                        placeholder="(+63)" 
                            
                        />
                    </a-form-item>

                    <span><i>In case of emergency, please provide name of contact person.</i></span>

                    <a-form-item style="margin-top: 25px;" class="formItem" label="Mobile No. *" name="contact_person_mobile_no"> 
                        <a-input
                        type="text"
                        id="contact_person_mobile_no"
                        v-model:value="form.contact_person_mobile_no" 
                        placeholder="Mobile No. of Contact Person"
                            
                        />
                    </a-form-item>

                    <a-form-item class="formItem" label="Email Address *" name="contact_person_email"> 
                        <a-input
                        type="email"
                        id="contact_person_email"
                        v-model:value="form.contact_person_email"
                        placeholder="Email Address of Contact Person" 
                            
                        />
                    </a-form-item>

                

            </div>
            </a-col>
        </a-row>

    </div>

    <!-- step 3 -->
    <div v-if="steps[current].content == 'business-act'" class="steps-content">
        <a-row>
            <h3 style="margin-bottom: 20px;">3. BUSINESS ACTIVITY</h3>
        </a-row>

        <a-button
              class="buttoncreate"
              @click="businessActivityAdd"
              style="margin-bottom: 8px; float: right"
              >Add</a-button
            >

        <a-table
              bordered
              :data-source="businessActivityData"
              :columns="businessActivityCulomn"
            >
              <template #line_of_business="{ record }">
                <a-input
                        v-if="businessActivityData[0].remarks !== 'No Event'"
                        type="text"
                        id="middle_name"
                        v-model:value=" businessActivityData[record.no - 1]
                      .line_of_business"
                            
                        />
              </template>
              <template #units="{ record }">
              
                        <a-input
                        v-if="businessActivityData[0].remarks !== 'No Event'"
                        type="text"
                        id="middle_name"
                        v-model:value=" businessActivityData[record.no - 1]
                      .units"
                            
                        />
                   
              </template>
              <template #capitalization="{ record }">
                <a-input
                        v-if="businessActivityData[0].remarks !== 'No Event'"
                        type="text"
                        id="middle_name"
                        v-model:value=" businessActivityData[record.no - 1]
                      .capitalization"
                            
                        />
              </template>
              <template #essential="{ record }">
                <a-input
                        v-if="businessActivityData[0].remarks !== 'No Event'"
                        type="text"
                        id="middle_name"
                        v-model:value=" businessActivityData[record.no - 1]
                      .essential"
                            
                        />
              </template>
              <template #non_essential="{ record }">
                <a-input
                        v-if="businessActivityData[0].remarks !== 'No Event'"
                        type="text"
                        id="middle_name"
                        v-model:value=" businessActivityData[record.no - 1]
                      .non_essential"
                            
                        />
              </template>
            
              <template #action="{ record }">

                  <a-button
                  v-if="
                    businessActivityData[record.no - 1].remarks !==
                    'No Event'
                  "
                    style="
                      background-color: #dd4b39;
                      border-color: #d73925;
                      color: white;
                      display: inline-block;
                    "
                    @click="businessActivityRemove(record)"
                  >
                    Remove
                  </a-button>
              </template>
            </a-table>



    </div>
    <!-- step 3 -->
    <div v-if="steps[current].content == 'upload_documents'" class="steps-content">
        <a-row>
            <h3 style="margin-bottom: 20px; margin-right: 8px ;">4. UPLOAD DOCUMENTS</h3> 
              <a-button class="buttoncreate" type="primary"  @click="visible = true">
                      Add
              </a-button>
        </a-row>

         
          <b>Instruction: </b>Please upload only documents listed below; <br>
           1. Single Proprietorship (One Owner) - DTI only.<br>
           2. Partnership (2-3 members with articles of partnership) - Security and Exchange Commision.<br>
           3. Corporation (could be 1 or sole CEO have 4 members and up with articles of incorporation)  - Securities and Exchange Commision.<br>
           4. Cooperative (many members with articles of cooperation) - Cooperative Development Authority. 
          


              <a-table row-key="id" size="small" :columns="columns" :data-source="form.files" bordered style="overflow-x: auto;overflow-y: auto;" >
                <template v-slot:action="{ record }">
                  <a-space>
                      <a-button class="buttondelete" type="danger" size="small" @click="remove(record)" > Remove</a-button>
                  </a-space>
                </template>
                <template v-slot:file="{ record }">
                  
                  <a-image v-if="!record.name" style="margin-left: 2px;" :width="120"  :height="120" :src="'/storage/' + record.file_name" />
                  <a-space v-if="record.name" >
                       {{ record.name }}
                  </a-space>
                </template>
                <template v-slot:status="{ record }">
                  
                  <a-space v-if="record.name" >
                      NEW
                  </a-space>
                  <a-space v-if="!record.name" >
                      UPLOADED
                  </a-space>
                </template>
            </a-table>


            <a-modal v-model:visible="visible" title="Upload Valid ID" :footer="null">
    
                <a-form
                  :labelCol="{ span: 4 }"
                  :wrapperCol="{ span: 18 }"
                  id="myForm"
                >

                  <a-form-item label="File">
                    <a-input
                      type="file"
                      @change="handlefile($event)"
                      ref="fileInput"
                      :accept="acceptedFiles"
                    />
                  </a-form-item>

                  <a-form-item :wrapper-col="{ span: 18, offset: 4 }">
                    <a-space>
                      <a-button key="back" @click="visible = false">
                        Back
                      </a-button>
                      <a-button
                        type="primary"
                        @click="addFile()"
                        class="buttoncreate"
                        :loading="false"
                      >
                        Add
                      </a-button>
                    </a-space>
                  </a-form-item>
                </a-form>
              </a-modal>


    </div>


    <!-- step 4 -->
    <div v-if="steps[current].content == 'summary'" class="steps-content">
        <a-row>
            <h3 style="margin-bottom: 20px;">5. SUMMARY</h3>
        </a-row>

        <Summary
        :data="form"
        ></Summary>





    </div>


    <div class="steps-action-next">
      <a-button   class="buttoncreate" v-if="current < steps.length - 1" type="primary" @click="next">Next</a-button>
      <a-button
        v-if="current == steps.length - 1 && !$route.path.includes('edit')"
        class="buttonshow"
        @click="submitApplication"
        :loading="loading"
      >
        Submit
      </a-button>
      <a-button
        v-if="current == steps.length - 1 && $route.path.includes('edit')"
        class="buttoncreate"
        @click="submitApplication"
        :loading="loading"
      >
        Update
      </a-button>
 
    </div>
    <div class="steps-action-prev">
      <a-button v-if="current > 0" style="margin-left: 8px;" @click="prev">Previous</a-button>
    </div>


    </div>
  </a-col>
  

</a-row>
</div>


<!-- 
        <a-form-item>
          <a-button
            class="login-form-button"
            :loading="loading"
            @click="handleFinish()"
           
          >
            Update Profile
          </a-button>
        </a-form-item> -->
    </a-form>
  </div>
</div>
 
</template>

<script >
import { defineComponent, onMounted,reactive, ref } from "vue";
import axios from "../../axios"
import { useRouter, useRoute } from "vue-router";
import moment from "moment";
import dayjs from 'dayjs';
import { Modal } from 'ant-design-vue';

import Swal from "sweetalert2";
import Summary from "./Summary.vue"


export default defineComponent({
  components: {
    Summary
  },
setup() {
  const router = useRouter();
  const route = useRoute()
  const loading = ref(false)
  const loading2 = ref(false)
  const visible = ref(false)
  const isApplicant = ref(true)
  const visibleSummaryForm = ref(false)
  const dateFormat = "YYYY-MM-DD";
      const acceptedFiles = ref(['.png','.jpeg','.jpg'])
//   const api = 'http://127.0.0.1:8000/api/';

    const current = ref(0);

    const next = () => {
    current.value++;
    if (steps[current.value].content == 'upload_documents'){
      form.business_activity = businessActivityData.value
    }
    if (steps[current.value].content == 'other-info'){
      if(form.type_of_application == '' || form.mode_of_payment == '' || form.date_of_application == ''){
        current.value--;
          Modal.error({
            title: () => 'Error!',
            content: () => 'Please fill out first "TYPE OF APPLICATION" fields.',
            okButtonProps: {
            style: {
            backgroundColor: '#24792f', // Change the color of the OK button here
            borderColor: '#24792f', // Change the border color of the OK button here
            color: 'white', // Change the text color of the OK button here
            },
        },
          }); 


      }
    }
    };

    const prev = () => {
    current.value--;
    };

  const form = reactive({
  id: '',
  type_of_application: '',
  mode_of_payment: '',
  date_of_application: '',
  date_app: '',
  date_dti: '',
  tin: '',
  type_of_business: '',
  ammendment_from: '',
  ammendment_to: '',
  dti_registration_no: '',
  dti_registration_date: '',
  first_name: '',
  last_name: '',
  middle_name: '',
  business_name: '',
  trade_name: '',
  business_address: '',
  business_postal: '',
  business_tel_no: '',
  business_email: '',
  business_mobile_no: '',
  owner_address: '',
  owner_postal: '',
  owner_tel_no: '',
  owner_email: '',
  owner_mobile_no: '',
  contact_person_mobile_no: '',
  contact_person_email: '',
  business_area: '',
  total_employee: '',
  no_employee: '',
  lessors_fullname: '',
  lessors_address: '',
  lessors_tel_no: '',
  lessors_email: '',
  monthly_rental: '',
  business_activity: [],
  file: '',
  files: [],
  files_remove: [],
  claimed_by:'',
  claimed_date:''
})

    const columns = [
      {
          title: 'File',
          dataIndex: 'name',
          slots: { customRender: "file" },
          width: '35%',
          align: 'center'
      },
      {
          title: 'Status',
          dataIndex: 'name',
          slots: { customRender: "status" },
          align: 'center'
      },
      {
        title: "Action",
        dataIndex: "action",
        slots: { customRender: "action" },
        align: 'center'
      }
    ];

    const steps = [
    {
        title: 'BASIC INFORMATION',
        content: 'basic-info',
    },
    {
        title: 'OTHER INFORMATION',
        content: 'other-info',
    },
    {
        title: 'BUSINESS ACTIVITY',
        content: 'business-act',
    },
    {
        title: 'UPLOAD DOCUMENTS',
        content: 'upload_documents',
    },
    {
        title: 'SUMMARY',
        content: 'summary',
    },
    ];
    const items = steps.map(item => ({
        key: item.title,
        title: item.title,
    }));


    const businessActivityCulomn = [
      {
        title: "No.",
        dataIndex: "no",
      },
      {
        title: "Line of Business",
        dataIndex: "line_of_business",
        slots: { customRender: "line_of_business" },
      },
      {
        title: "No. of  Units",
        dataIndex: "units",
        slots: { customRender: "units" },
      },
      {
        title: "Capitalization(for New Business)",
        dataIndex: "capitalization",
        slots: { customRender: "capitalization" },
      },
      {
        title: "Gross/Sales Receipts (Renewal) 'Essential'",
        dataIndex: "essential",
        slots: { customRender: "essential" },
      },
      {
        title: "Gross/Sales Receipts (Renewal) 'Non-Essential'",
        dataIndex: "non_essential",
        slots: { customRender: "non_essential" },
      },
      {
        title: "Action",
        dataIndex: "action",
        slots: { customRender: "action" },
      },
    ];

    const businessActivityData =
      ref([
        {
          key: "1",
          no: 1,
          id: "",
          line_of_business: "",
          units: "",
          capitalization: "",
          essential: "",
          non_essential: "",
          remarks: "No Event"

        },
      ]);


    const businessActivityRemove = (key) => {
        businessActivityData.value.splice(
        businessActivityData.value.indexOf(key),
        1
      );
      let count = 1;
      businessActivityData.value.forEach((data) => {
        data.no = count;
        count++;
      });

      if (businessActivityData.value.length === 0) {
        businessActivityData.value.push({
          key: "1",
          no: 1,
          id: "",
          line_of_business: "",
          units: "",
          capitalization: "",
          essential: "",
          non_essential: "",
          remarks: "No Event",
        });
      }

    };
    const businessActivityAdd = () => {
        const countReserve = businessActivityData.value.length;
        const lastReserve =
        businessActivityData.value[countReserve - 1];
        let setData = {
            key: `${parseInt(lastReserve.key) + 1}`,
            no: lastReserve.no + 1,
            id: "",
            line_of_business: "",
            units: "",
            capitalization: "",
            essential: "",
            non_essential: "",
            remarks: ""
        };

        if (businessActivityData.value[0].remarks === "No Event") {
            businessActivityData.value[0].remarks = "";
        } else {
            businessActivityData.value.push(setData);
      }
    };



    onMounted(() =>{

      loading.value = true
            axios.get('backend/auth_user')
                        .then(response => {
                        form.first_name = response.data.first_name
                        form.middle_name = response.data.middle_name
                        form.last_name = response.data.last_name
                        isApplicant.value = response.data.role == 'Applicant' ? true:false
                        loading.value = false
            })


        if(route.path.includes("edit")){
            let id = route.params.id
            loading2.value = true
            var list = []
                   axios.get(`/backend/application/${id}`)
                    .then(res => {
                       form.type_of_application = res.data.type_of_application
                       form.mode_of_payment = res.data.mode_of_payment
                       form.date_app = res.data.date_of_application
                       form.date_dti = res.data.dti_registration_date
                       form.date_of_application = dayjs(res.data.date_of_application, dateFormat)
                        form.tin = res.data.tin
                        form.type_of_business = res.data.type_of_business
                        form.ammendment_from = res.data.ammendment_from
                        form.ammendment_to = res.data.ammendment_to
                        form.dti_registration_no = res.data.dti_registration_no
                        form.dti_registration_date =  dayjs(res.data.dti_registration_date, dateFormat)
                        form.last_name = res.data.last_name
                        form.middle_name = res.data.middle_name
                        form.business_name = res.data.business_name
                        form.trade_name = res.data.trade_name
                        form.ref_no = res.data.ref_no

                        form.files = res.data.documents
                        form.business_activity = res.data.business_activity

                        form.business_address = res.data.business_information.business_address
                        form.business_area = res.data.business_information.business_area
                        form.business_email = res.data.business_information.business_email
                        form.business_mobile_no = res.data.business_information.business_mobile_no
                        form.business_postal = res.data.business_information.business_postal
                        form.business_tel_no = res.data.business_information.business_tel_no
                        form.no_employee = res.data.business_information.no_employee
                        form.total_employee = res.data.business_information.total_employee

                        form.owner_address = res.data.owner_information.owner_address
                        form.owner_email = res.data.owner_information.owner_email
                        form.owner_mobile_no = res.data.owner_information.owner_mobile_no
                        form.owner_postal = res.data.owner_information.owner_postal
                        form.owner_tel_no = res.data.owner_information.owner_tel_no
                        form.contact_person_email = res.data.owner_information.contact_person_email
                        form.contact_person_mobile_no = res.data.owner_information.contact_person_mobile_no

                        form.lessors_fullname = res.data.lessor.lessors_fullname
                        form.lessors_email = res.data.lessor.lessors_email
                        form.lessors_address = res.data.lessor.lessors_address
                        form.lessors_tel_no = res.data.lessor.lessors_tel_no
                        form.monthly_rental = res.data.lessor.monthly_rental
                        form.status = res.data.status
                

                        let $counter = 1;
                        businessActivityData.value = form.business_activity.length != 0 ? [] : businessActivityData.value;

                        form.business_activity.forEach((activities) => {
   
                            businessActivityData.value.push({
                            key: $counter,
                            no: $counter,
                            id: activities.id,
                            line_of_business: activities.line_of_business,
                            units: activities.units,
                            capitalization: activities.capitalization,
                            essential: activities.essential,
                            non_essential: activities.non_essential,
                            remarks: '',
                           
                          });

                         
                          $counter++;
                        })
                        form.remarks = res.data.remarks


                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                    setTimeout(() => {
                        loading2.value = false
                }, 800);

        }

                

    });

  const submitApplication = async() => {

    if(!route.path.includes("edit")){
    const formData = new FormData();

        for (var i = 0; i < form.files.length; i++) {
            let file = form.files[i];
            formData.append("files[" + i + "]", file);
          }
   
    loading.value = true
   axios.post(`/backend/application`,form)
        .then(res => { 
          formData.append("id", res.data.data.id)

           axios.post("backend/documents",formData)
                .then(response => { 
                  loading.value = false
                  router.push('/business-application')
                })
                .catch(function (error) {
                    loading.value = false
                });
            loading.value = false
        })
        .catch(function (error) {
            loading.value = false
        });
      }else{
        loading.value = true
        const formData = new FormData();
        formData.append("id", route.params.id);
          for (var i = 0; i < form.files_remove.length; i++) {
            formData.append("remove[" + i + "]", form.files_remove[i]);
          }
          for (var i = 0; i < form.files.length; i++) {
              if(form.files[i].name != null){
                let file = form.files[i];
                formData.append("files[" + i + "]", file);
              }else{
                formData.append("f_id[" + i + "]", form.files[i].id);
              }
            }
        axios.put(`/backend/application/update/${route.params.id}`,form)
        .then(response => { 
          axios.post("backend/documents/update",formData)
                .then(response => { 
                  router.push('/business-application')
                })
                .catch(function (error) {
                    loading.value = false
                });
            loading.value = false
            // router.push('/business-application')

                 
        })
        .catch(function (error) {
            loading.value = false
        });

      }

  }
  const selectDateDTI = (e) => {
    let date_of_application = moment(e.$d, dateFormat)
    form.date_dti =moment(date_of_application).format("YYYY-MM-DD")

  }
  const selectDateApplication = (e) => {
   let date_of_application = moment(e.$d, dateFormat)
   form.date_app =moment(date_of_application).format("YYYY-MM-DD")
   

  }
  const handleFinish = async() => {

   loading.value = true
   axios.post(`/backend/profile/${form.id}`,form)
        .then(res => { 
            form.first_name= res.data.data.first_name,
            form.last_name= res.data.data.last_name,
            form.middle_name= res.data.data.middle_name,
            form.extension_name= res.data.data.extension_name,
            form.email= res.data.data.email,
            form.password = '',
            form.confirmpassword = '',
            form.old_password = '',
            loading.value = false

        })
        .catch(function (error) {
            loading.value = false
        });


 
  };

  const remove = (row) => {
      form.files.map(function (value, key) {
        if (value.id == row.id) {
            form.files.splice(key, 1);
            form.files_remove.push(row.file_path)
        }
      });
    };

    const handlefile = (e) => {

      form.file =  e.target.files[0]
      const input = ref(fileInput);
      input.value = ''; // Clear the selected file
     

    };
    const approveApplication = () => {

      loading.value = true
   axios.put(`/backend/application/approve/${route.params.id}`,form)
        .then(res => { 
          loading.value = false
          router.push('/business-application')
        })
        .catch(function (error) {
            loading.value = false
        });
     
    };
    const returnApplication = () => {
      loading.value = true
   axios.put(`/backend/application/return/${route.params.id}`,form)
        .then(res => {
          loading.value = false 
          router.push('/business-application')
        })
        .catch(function (error) {
            loading.value = false
        });
    };
    const applicationClaimed = () => {
      axios.put(`/backend/application/claimed/${route.params.id}`,form)
            .then(res => { 
              router.push('/business-application')
            })
            .catch(function (error) {
                loading.value = false
            });
    };

    const addFile = () => {
      if(form.file != ''){
      form.files.push(form.file)
      form.file = ''
      }else{
        visible.value = false
      }

    }
    const toAppointment = () => {
      router.push({path: '/appointment/calendar/' + route.params.id
            })
    }




  return {
    form,
    loading,
    loading2,
    current, 
    steps, 
    items,
    businessActivityCulomn,
    businessActivityData,
    visible,
    visibleSummaryForm,
    acceptedFiles,
    isApplicant,
    dateFormat,

    handlefile,
    addFile,
    columns,
    remove,

    next,
    prev,
    businessActivityAdd,
    businessActivityRemove,
    handleFinish,
    selectDateDTI,
    selectDateApplication,
    submitApplication,
    approveApplication,
    returnApplication,
    applicationClaimed,
    toAppointment

  };
},
})
</script>
<style lang="css" scoped>
html,
body {
margin: 0px;
height: 100%;
}

.login-logo {
font-size: 25px;
text-align: center;
margin-bottom: 25px;
font-weight: 300;
}

.login-logo a {
color: #444;
}

.main {
width: 100%;
height: 500px;
/* margin-bottom: 30px; */
/* display: flex; */
justify-content: center;
align-items: center;

}

.section {
border: 1px solid black;
color: #fff;
background: #2d2d2d;
width: 98%;
background: red;
background-color: #fff;
height: auto;
margin: 2% auto;


}

.login-form-button {
width: 20%;
margin-top: 20px;
margin-left: 40%;
background-color: #24792f;
color: white;
font-weight: bold;
text-align: center;
/* font-size: 15px; */
}

.linebottom{
width: 100%;
height: 5px;
background-color: #af1818;
}
.headers{
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

.gutter-example :deep(.ant-row > div) {
background: transparent;
border: 0;
}
.gutter-box {
color: #555454;
padding: 15px 15px;
border: 1px solid #b7b7b7;
/* border-top-width: 5px; */

border-radius: 10px;
}

.formItem{
    margin-top: -12px;
}

.steps-content {
  margin-top: 75px;
  border: 1px solid #e9e9e9;
  border-radius: 6px;
  background-color: #f9f9f9;
  min-height: 200px;
  /* text-align: center; */
  padding: 20px;
}
.steps-content-1 {
  margin-top: 75px;
  border: 1px solid #e9e9e9;
  border-radius: 6px;
  background-color: #f9f9f9;
  min-height: 200px;
  text-align: center;
  padding: 20px;
}

.steps-action-next {
  margin-top: 30px;
  float: right;
}
.steps-action-prev {
  margin-top: 30px;
  float: left;
}

[data-theme='dark'] .steps-content {
  background-color: #2f2f2f;
  border: 1px dashed #404040;
}
</style>
