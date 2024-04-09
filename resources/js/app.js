import {createApp} from 'vue';


import App from './App.vue';
import axios from 'axios';
import router from './router';
import Antd from "ant-design-vue";
import dayjs from 'dayjs';
import 'dayjs/locale/en'; // Import the locale you need
// import "ant-design-vue/dist/antd.css";
// import { BootstrapVue } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css';
import {
    UserOutlined,
    LockOutlined,
    SettingOutlined,
    HomeOutlined,
    FormOutlined,
    FileDoneOutlined,
    ProfileOutlined,
    PlusOutlined,
    EyeFilled,
    BellFilled,
  } from "@ant-design/icons-vue";
  



const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.use(Antd);
// app.use(BootstrapVue);
app.component("UserOutlined", UserOutlined);
app.component("LockOutlined", LockOutlined);
app.component("SettingOutlined", SettingOutlined);
app.component("HomeOutlined", HomeOutlined);
app.component("FormOutlined", FormOutlined);
app.component("FileDoneOutlined", FileDoneOutlined);
app.component("ProfileOutlined", ProfileOutlined);
app.component("PlusOutlined", PlusOutlined);
app.component("EyeFilled", EyeFilled);
app.component("BellFilled", BellFilled);
app.mount('#app');
