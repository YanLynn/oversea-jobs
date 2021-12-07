import Vue from "vue";
import PublicPage from "../components/public/PublicLayout";
import Public from "./public";
import recruiter from "./recruiter";
import jobseeker from "./jobseeker";
import Unauthorized from "../components/Unauthorized.vue";
import NotFound from "../components/NotFound.vue";
import Recruiterlogin from "../components/recruiter/Recruiterlogin.vue";
import VueRouter from "vue-router";
import store from "../store";
import JobseekerLogin from "../components/jobseeker/JobseekerLogin";
import RecruiterRegistration from "../components/recruiter/RecruiterRegistration";
import RecruiterCreditPaymentCompleted from "../components/recruiter/RecruiterCreditPaymentCompleted";
import RecruiterPaymentCardChoice from "../components/recruiter/RecruiterPaymentCardChoice";
import ForgotPassword from "../components/auth/ForgotPassword";
import ResetPasswordForm from "../components/auth/ResetPasswordForm";
import JobseekerRegister from "../components/jobseeker/JobseekerRegister";
import Content from "../components/public/Content";
import PrivacyPolicy from '../components/public/PrivacyPolicy';
import Terms from '../components/public/Terms';
import SpecifiedCommercialTransactions from '../components/public/SpecifiedCommercialTransactions';
import JobFlow from '../components/public/JobFlow';
import RecruiterFlow from '../components/public/RecruiterFlow';
import About from '../components/public/About';
import UpdateEmail from '../components/auth/UpdateEmail'
import VueMeta from "vue-meta";

Vue.use(VueRouter);
Vue.use(VueMeta);

const routes = [
    {
        path: "/",
        component: PublicPage,
        children: Public
    },
    {
        path: "/recruiter/credit-payment",
        name: "RecruiterPaymentCardChoice",
        component: RecruiterPaymentCardChoice
    },
    {
        path: "/recruiter/payment-complete",
        name: "RecruiterCreditPaymentCompleted",
        component: RecruiterCreditPaymentCompleted
    },
    {
        path: "/recruiter/register",
        name: "recruiter/register",
        component: RecruiterRegistration
    },
    {
        name: 'recruiter/register/confirm',
        path: '/recruiter/register/confirm',
        component: RecruiterRegistration,
    },
    {
        name: 'recruiter/register/success',
        path: '/recruiter/register/success',
        component: RecruiterRegistration,
    },
    {
        path: "/jobseeker/register",
        name: "jobseeker/register",
        component: JobseekerRegister
	},
	{
		path: "/jobseeker/register/confirm",
		name: "jobseeker/register/confirm",
		component: JobseekerRegister
	},
	{
		path: "/jobseeker/register/success",
		name: "jobseeker/register/success",
		component: JobseekerRegister
    },
    {
        path: "/query",
        name: "query",
        component: Content
	},
	{
		path: "/query/confirm",
		name: "query/confirm",
		component: Content
	},
	{
		path: "/query/success",
		name: "query/success",
		component: Content
	},
    {
        path: "/jobseeker/login",
        name: "jobseeker/login",
        component: JobseekerLogin
    },
    {
        path: "/recruiter/login",
        name: "recruiter/login",
        // beforeEnter: guard,
        component: Recruiterlogin
    },

    {
        path: "/Unauthorized",
        name: "Unauthorized",
        component: Unauthorized
    },
    {
        path: "/recruiter",
        // name: 'recruiter',
        component: PublicPage,
        children: recruiter
    },
    {
        path: "/jobseeker",
        // name: 'jobseeker',
        component: PublicPage,
        children: jobseeker
    },

    {
        path: "/jobseeker/forgot-password",
        name: "jobseeker-forgot-password",
        component: ForgotPassword
    },
    {
        path: "/recruiter/forgot-password",
        name: "recruiter-forgot-password",
        component: ForgotPassword
    },
    {
        path: "/reset-password/",
        name: "reset-password-form",
        component: ResetPasswordForm
    },
    {
        path:'/privacy-policy',   
        name:"PrivacyPolicy",     
        component: PrivacyPolicy,
    },
    {
        path:'/terms',        
        component: Terms,
    },
    {
        path:'/specified-commercial-transactions',
        component: SpecifiedCommercialTransactions,
    },
    {
        path:'/jobseeker-flow',
        component:  JobFlow,
    },
    {
        path:'/recruiter-flow',
        component: RecruiterFlow,
    },
    {
        path:'/about',
        component: About,
    },
    {
        path:'/recruiter/update-email',
        component: UpdateEmail,
    },
    {
        path:'/jobseeker/update-email',
        component: UpdateEmail,
    },
    { 
        name: '404', 
        path: "*", 
        component: NotFound 
    }
];

// function guard(to, from, next){
//     next();
//     if(store.getters.currentUser != null) {
//         next('/'); // allow to enter route
//     } else{
//         next(); // go to '/login';
//     }
// }

// router============================
const router = new VueRouter({
    routes,
    mode: "history",
    scrollBehavior () {
        return { x: 0, y: 0 }
      }
});
// router============================
//permission=========================

// if (this.$store.getters.currentUser != null) {
//     localStorage.removeItem('user');
// }
// if (this.$route.query.oe != null) {
//     this.updateEmail();
// }

router.beforeEach((to, from, next) => { 
    // console.log("window.location.search",window.location.search)
    localStorage.setItem("previous_route_path", from.path ?? '/');
        if (
            to.name == "recruiter/login" ||
            to.name == "jobseeker/login" ||
            to.path == "/"
        ) {
            if (window.location.search != "") {
                localStorage.removeItem("user");  
                next();              
            }
            else{
                if (store.getters.currentUser != null) {
                    if (store.getters.currentUser["role_id"] == 2) {
                        next("/recruiter");
                    } else {
                        next("/jobseeker");
                    }
                } else {
                    next();
                }
            }
            
        }


    if (to.meta.reqiuresAuth) {
        const authUser = store.getters.currentUser;
        if (!authUser || !authUser.token || authUser == null) {
            next({ path: "/" });
        } else if (authUser || authUser.token) {
            // for recruiter
            if (to.meta.recruiter) {
                const authUser = store.getters.currentUser;
                if (authUser.role_id === 2) {
                    next();
                } else {
                    localStorage.removeItem("user");
                    next({ name: "Unauthorized" });
                }
            }
            // for User jobseeker
            if (to.meta.jobseeker) {
                const authUser = store.getters.currentUser;
				if (authUser.role_id === 3) {
                    next();
                } else {
                    localStorage.removeItem("user");
                    next({ name: "Unauthorized" });
                }
            }
            next();
        }
    } else {
        next();
    }
});
//permission=========================

export default router;
