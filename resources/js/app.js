import editUser_general from "./views/dashboard/editUser_tabs/editUser_general";
require('./bootstrap');
window.Vue = require('vue');
Vue.prototype.$eventBus = new Vue(); // Global event bus




import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
import VueRouter from 'vue-router'
Vue.use(VueRouter);
import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton);
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify';
import ru from 'vuetify/es5/locale/ru'
Vue.use(Vuetify);
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);
import vco from "v-click-outside";
Vue.use(vco);
import FormDatepickerPlugin from 'bootstrap-vue'
Vue.use(FormDatepickerPlugin);
///////////////////////////////////////
Vue.component('auth-spa', require('./components/appComponent.vue').default);  // spa authorization app
Vue.component('main-spa', require('./components/mainAppComponent.vue').default); // main spa app
Vue.component('vue-recaptcha', require('vue-recaptcha').default);    // global captcha
Vue.component('styled-input',require('./components/customTextInputComponent').default); // styled component

// modals
Vue.component('info-modal', require('./components/Modals/infoModal.vue').default);
Vue.component('change-password-modal',require("./components/Modals/changePasswordModal.vue").default);
Vue.component('change-backup_email-modal',require("./components/Modals/backupEmailModal.vue").default);
Vue.component('codeword-modal',require("./components/Modals/codewordModal.vue").default);
Vue.component('table-settings',require("./components/Modals/tableSettingsModal.vue").default);
Vue.component('employees-list',require("./components/Modals/employeesListModal").default);

Vue.component('pagination-links',require("./components/paginationLinksComponent.vue").default);

import authorization from './views/autorization/authorization'
import password_recovery from "./views/autorization/password_recovery/recovery_by-email/password_recovery";
import recovery_confirm from "./views/autorization/password_recovery/recovery_by-email/recovery_confirm";
import change_password from "./views/autorization/password_recovery/change_password";
import profile from './views/user_profile/profile'


// main app views
import main_page from "./views/main/mainPage"

// dashboard views
import dashboardUsers from "./views/dashboard/dashboardUsers";
import editUser from "./views/dashboard/editUser";
import createUser from "./views/dashboard/createUser";
import departments from "./views/dashboard/departments/departments";
import departmentsCreate from "./views/dashboard/departments/departmentsCreate";
import departmentsEdit from "./views/dashboard/departments/departmentsEdit";


// user security views
import security from "./views/user_profile/security";
import security_settings from  "./views/user_profile/security_tabs/security"
import security_history from  "./views/user_profile/security_tabs/history"
import editUser_accesses from "./views/dashboard/editUser_tabs/editUser_accesses";
/////////////////////////////////////

// Vue.js routes
const router = new VueRouter({
    mode: 'history',
    routes: [
        // auth routes
        {
            path: '/authorization',
            name: 'authorization',
            component: authorization,
        },
        {
            path: '/authorization/recovery',
            name: 'password_recovery',
            component: password_recovery,
        },
        {
            path: '/authorization/recovery/confirmation',
            name: 'recovery_confirm',
            component: recovery_confirm,
        },
        {
            path: '/authorization/recovery/change',
            name: 'change_password',
            component: change_password,
        },
        // main spa routes
        {
            path: '/',
            name: 'main_page',
            component: main_page,
        },

        // user profile routes
        {
            path: '/user/profile/security',
            name: 'user_security',
            component: security,
        },
        // dashboard user routes
        {
            path: '/dashboard/users',
            name: 'dashboardUsers',
            component: dashboardUsers,
        },
        {
            path: '/dashboard/user/:id/edit',
            name: 'editUser',
            component: editUser,
            redirect: '/dashboard/user/:id/edit/general',
            children: [
                {
                    path: '/dashboard/user/:id/edit/general',
                    name: 'editUser_general',
                    component: editUser_general,
                },
                {
                    path: '/dashboard/user/:id/edit/accesses',
                    name: 'editUser_accesses',
                    component: editUser_accesses,
                },
            ]
        },
        {
            path: '/dashboard/user/create',
            name: 'createUser',
            component: createUser,
        },
        // dashboard department routes
        {
            path: '/dashboard/department/:departmentId/employees',
            name: 'department_employees',
            component: editUser,
        },


        // user profile routes
        {
            path: '/user/profile',
            name: 'profile',
            component: profile,
        },
        {
            path: '/user/profile/security',
            name: 'user_security',
            component: security,
            children: [
                {
                    path: '/user/profile/security/settings',
                    name: 'security_settings',
                    component: security_settings,
                },
                {
                    path: '/user/profile/security/history',
                    name: 'security_history',
                    component: security_history,
                },
            ]
        },
        {
            path: '/dashboard/departments',
            name: 'departments',
            component: departments,
        },
        {
            path: '/dashboard/department/create',
            name: 'departmentsCreate',
            component: departmentsCreate,
        },
        {
            path: '/dashboard/department/:id/edit/',
            name: 'departmentsEdit',
            component: departmentsEdit,
        }
    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    },
});
router.beforeEach((to, from, next) => {
    if(/dashboard/.test(to.path)) {
        if (localStorage.is_admin) {
            if (localStorage.is_admin == true) {
                next()
            } else {
                next({name: 'main_page'})
            }
        } else {
            next({name: 'main_page'})
        }
    }
    if(/vuetify/.test(to.path)) {
        next({name: 'main_page'})
    }
    if (/js.map/.test(to.path)) {
        next({name: 'main_page'})
    }
    next();
});
const app = new Vue({
    el: '#app',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi',
        },
        lang: {
            locales: {ru},
            current: 'ru'
        }
    }),
    router
});
