import home from '../components/public/home/PublicHome';
import RecruiterMypage from '../components/recruiter/RecruiterMypage';
import RecruiterJobCreate from '../components/recruiter/RecruiterJobCreate';
import RecruiterJobApplicantList from '../components/recruiter/RecruiterJobApplicantList';
import RecruiterScoutFavourite from '../components/recruiter/RecruiterScoutFavourite';
import RecruiterProfileSetting from '../components/recruiter/RecruiterProfileSetting';
import RecruiterJobPostList from '../components/recruiter/RecruiterJobPostList';
import RecruiterJobDraftList from '../components/recruiter/RecruiterJobDraftList';
import RecruiterScoutedList from '../components/recruiter/RecruiterScoutedList';
import RecruiterRegisterCompleted from '../components/recruiter/RecruiterRegisterCompleted';
import RecruiterPaymentCardChoice from '../components/recruiter/RecruiterPaymentCardChoice';
import RecruiterCreditPaymentError from '../components/recruiter/RecruiterCreditPaymentError';
import RecruiterCreditPaymentCompleted from '../components/recruiter/RecruiterCreditPaymentCompleted';
import RecruiterProfileEdit from '../components/recruiter/RecruiterProfileEdit';
import Deactivate from '../components/auth/Deactivate';
import RecruiterSetting from '../components/setting/RecruiterSetting';
import PasswordSetting from '../components/auth/PasswordSetting';
import JobSeekerInfo from '../components/recruiter/JobSeekerInfo';
import RecruiterScoutEntry from '../components/recruiter/RecruiterScoutEntry';
import Timezone from '../components/public/TimeZone';

const route = [{
        path: '/',
        component: home,
        meta: {
            reqiuresAuth: true,
            recruiter: true, // for only admin component
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/mypage',
        component: RecruiterMypage,
        meta: {
            reqiuresAuth: true,
            recruiter: true, // for only admin component
            jobseeker: false,
        },
    },

    {
        path: '/recruiter/jobs/create',
        component: RecruiterJobCreate,
        meta: {
            reqiuresAuth: true,
            recruiter: true, // for only admin component
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/jobs/:id/edit',
        component: RecruiterJobCreate,
        meta: {
            reqiuresAuth: true,
            recruiter: true, // for only admin component
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/job-applicant',
        component: RecruiterJobApplicantList,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/scout-favourite',
        component: RecruiterScoutFavourite,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/public-scout-favourite',
        name: 'PublicScoutFavourite',
        component: RecruiterScoutFavourite,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/profile-setting',
        component: RecruiterProfileSetting,
        name: 'RecruiterProfileSetting',
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/profile-edit',
        component: RecruiterProfileEdit,
        name: "RecruiterProfileEdit",
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false,
        }
    },
    {
        path: '/recruiter/jobs',
        component: RecruiterJobPostList,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/job-draft',
        component: RecruiterJobDraftList,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false,
        },
    },
    {
        path: '/recruiter/scouted-list',
        name: "RecruiterScoutedList",
        component: RecruiterScoutedList,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false,
        }
    },
    {
        path: '/recruiter/register-completed',
        component: RecruiterRegisterCompleted,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/scout-payment-successful',
        component: RecruiterPaymentCardChoice,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/credit-payment-error',
        component: RecruiterCreditPaymentError,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/credit-payment-completed',
        component: RecruiterCreditPaymentCompleted,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/deactivate',
        component: Deactivate,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false // for only admin component
        }
    },
    {
        path: '/recruiter/setting',
        component: RecruiterSetting,
        meta: {
            reqiuresAuth: true,
            recruiter: true,
            jobseeker: false
        }
    },
    {
        path: '/recruiter/password-setting',
        component: PasswordSetting,
        meta: {
            reqiuresAuth: true,
            recruiter:true, // for only admin component
            jobseeker:false
        }
    },
    {
		path: '/recruiter/scout/:jobseekerId',
		component: RecruiterScoutEntry,
		name: 'RecruiterScoutEntry',
		props: (route) => ({jobseekerId: String(route.params.jobseekerId)}),
		meta: {
			reqiuresAuth: true,
			recruiter: true,
			jobseeker: false,
		}
	},
    {
		path: '/recruiter/public-scout/:jobseekerId',
		component: RecruiterScoutEntry,
		name: 'PublicRecruiterScoutEntry',
		props: (route) => ({jobseekerId: String(route.params.jobseekerId)}),
		meta: {
			reqiuresAuth: true,
			recruiter: true,
			jobseeker: false,
		}
	},
    {
		path: '/recruiter/jobseeker-detail/:type/:transactionId',
		component: JobSeekerInfo,
		name: 'JobSeekerInfo',
		props: (route) => ({transactionId: String(route.params.transactionId), type: String(route.params.type)}),
		meta: {
			reqiuresAuth: true,
			recruiter: true,
			jobseeker: false,
		}
    },
    {
        path: '/recruiter/time-zone',
        component: Timezone,
        meta: {
            reqiuresAuth: true,
            recruiter:true, // for only admin component
            jobseeker:false
        }
    },

];

export default route