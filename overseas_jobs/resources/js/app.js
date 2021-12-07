require("./bootstrap");
import Vue from "vue";
// import * as VeeValidate from 'vee-validate';
import store from "../js/store/index";
import router from "./route";
import App from "./components/App";
import VueI18n from "vue-i18n";
import English from "../js/lang/en";
import 日本語 from "./lang/jp";
import api from "./api/apiBasePath";
import Vuelidate from "vuelidate";
// ES6 Modules or TypeScript
import VueCarousel from "vue-carousel";
import DataTable from "../js/components/datatable/datatable";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
Vue.component("DataTable", DataTable);

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import 'vue2-datepicker/locale/zh-cn';
import InfiniteLoading from 'vue-infinite-loading';
import configs from './config';
import VueFilter from 'vue-filter';
import VueScrollTo from 'vue-scrollto';
import JobCard from "../js/components/public/JobCard";
import JobSeekerCard from "../js/components/public/JobSeekerCard";
Vue.component("job-card", JobCard);
Vue.component("jobSeeker-card", JobSeekerCard);
import VueSweetAlert from "vue-sweetalert2";
import alertService from './services/AlertService';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import io from 'socket.io-client';
import VueChatScroll from 'vue-chat-scroll';
import VueMeta from 'vue-meta'


// socket 
window.io = io;
window.socket = io(process.env.MIX_SOCKET_URL);

Vue.use(VueMeta);
Vue.component('v-select', vSelect)
Vue.use(DatePicker);
Vue.use(VueFilter);
Vue.use(Vuelidate);
Vue.use(VueCarousel);
Vue.use(VueScrollTo);
Vue.use(VueSweetAlert);
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.use(api);
Vue.use(VueChatScroll);
Vue.prototype.$api = api;
Vue.prototype.$configs = configs;
Vue.prototype.$alertService = alertService;


Vue.use(
    Loading, {
    color: "#0062ff",
    loader: process.env.MIX_LOADING_INDICATOR ?? "dots",
    backgroundColor: "#F0F0F0",
    width: 64,
    height: 64,
    opacity: 0.7,
    zIndex: 1000,  
    
}, {
    after: new Vue().$createElement("h3", { class: "loading-text" }, [
        "送信中"
    ])
}
);

// languages=========================
const languages = {
    en: English,
    ja: 日本語,
}
Vue.use(VueI18n);
const i18n = new VueI18n({
    locale: 'ja',
    messages: languages,
})
// languages=========================

Vue.use(InfiniteLoading);

// main app==========================
Vue.component("App", App);
// main app==========================
const app = new Vue({
    el: "#app",
    router,
    store,
    i18n,
});