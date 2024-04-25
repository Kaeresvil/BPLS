import { createWebHistory, createRouter } from "vue-router";

import Login from '../pages/Login.vue';
import Signup from '../pages/Signup.vue';
import  Forgot from '../pages/Forgot.vue';
import Dashboard from '../components/Dashboard/Index.vue';

const role1 = ['Admin', 'Staff', 'Applicant'];
const role2 = ['Admin', 'Staff'];
const role3 = ['Admin'];


const routes = [
  {
    name: 'login',
    path: '/',
    component: Login,
    meta: { requiresAuth: false, roles: role1  }
  },

  {
    name: 'forgot',
    path: '/forgot',
    component: Forgot,
    meta: { requiresAuth: true, roles: role1  }
},


  {
    name: 'dashboard',
    path: '/dashboard',
    component: Dashboard,
    children: [
        {
            name: 'signup',
            path: '/signup',
            component: Signup,
            meta: { requiresAuth: true, roles: role1  }
        },
      {
        name: 'home',
        path: '/home',
        meta: { requiresAuth: true,  roles: role1  },
        component: () => import("../components/Home/Index.vue")
      },
      {
        name: 'profile',
        path: '/profile',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../pages/Profile.vue")
      },
      {
        name: 'users',
        path: '/users',
        meta: { requiresAuth: true, roles: role3 },
        component: () => import("../components/User/Index.vue")
      },
      {
        name: 'users-new',
        path: '/users/new',
        meta: { requiresAuth: true, roles: role3 },
        component: () => import("../components/User/Form.vue")
      },
      {
        name: 'users-edit',
        path: '/edit/users/:id',
        meta: { requiresAuth: true, roles: role3 },
        component: () => import("../components/User/Form.vue")
      },
      {
        name: 'roles',
        path: '/roles',
        meta: { requiresAuth: true, roles: role3 },
        component: () => import("../components/Role/Index.vue")
      },
      {
        name: 'business-application',
        path: '/business-application',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Application/Index.vue")
      },
      {
        name: 'business-application-new',
        path: '/business-application/new',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Application/Form.vue")
      },
      {
        name: 'edit-business_application',
        path: '/edit/business-application/:id',
        meta: { requiresAuth: true, roles: role1 },
        component: ()=> import("../components/Application/Form.vue"),
        // props:true
    },
      {
        name: 'appointment',
        path: '/appointment',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Appointment/Index.vue")
      },
      {
        name: 'appointment-calendar',
        path: '/appointment/calendar/:id',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Appointment/Form.vue")
      },
      {
        name: 'edit-appointment-calendar',
        path: '/edit/appointment/calendar/:id',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Appointment/Form.vue")
      },
      {
        name: 'summary',
        path: '/summary',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Application/Summary.vue")
      },
      {
        name: 'notification',
        path: '/notification',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../components/Notification/Index.vue")
      },
      {
        name: 'not-found',
        path: '/404',
        meta: { requiresAuth: true, roles: role1 },
        component: () => import("../pages/NotFound.vue")
      }
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

router.beforeEach((to, from, next) => {
    console.log('to',to.name)
    console.log('auth',to.meta)
    console.log('token',window.localStorage.getItem("BPLS_TOKEN"))
    console.log('role',window.localStorage.getItem("AUTH_ROLE"))
    const userRoles = [window.localStorage.getItem("AUTH_ROLE")]


    if ( to.name == 'signup' )
        next();
    else if ( to.name == 'forgot' )
        next();
    else if (to.name !== 'login' && !window.localStorage.getItem("BPLS_TOKEN"))
        next({ name: "login" });
    else if(to.name == 'login' && window.localStorage.getItem("BPLS_TOKEN") && !to.meta.requiresAuth )
        next({ name: 'home' });
    else if (to.name == 'login' && !window.localStorage.getItem("BPLS_TOKEN")&& !to.meta.requiresAuth)
        next();
    else if(hasRequiredRoles(userRoles, to.meta.roles, to.name))
        next();
    else 
        next({name: 'not-found'});


});

  function hasRequiredRoles(userRoles, requiredRoles, to) {
    if(to == undefined){return false}

    return userRoles.every(role => requiredRoles.includes(role));
}

export default router;
