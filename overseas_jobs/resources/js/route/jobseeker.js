import home from '../components/public/home/PublicHome';
import JobseekerMypage from '../components/jobseeker/JobseekerMypage';
import JobseekerProfileEdit from '../components/jobseeker/JobseekerProfileEdit';
import JobseekerFavourite from '../components/jobseeker/JobseekerFavourite';
import JobseekerScoutSetting from '../components/jobseeker/JobseekerScoutSetting';
import JobseekerScoutedDetail from '../components/jobseeker/JobseekerScoutedDetail';
import JobseekerResumeUpload from '../components/jobseeker/JobseekerResumeUpload';
import JobseekerScoutedList from '../components/jobseeker/JobseekerScoutedList';
import JobseekerAppliedList from '../components/jobseeker/JobseekerAppliedList';
import Deactivate from '../components/auth/Deactivate';
import JobseekerSetting from '../components/setting/JobseekerSetting';
import PasswordSetting from '../components/auth/PasswordSetting';
import Timezone from '../components/public/TimeZone';

const route = [{
        path: '/',
        name:'home',
        component: home,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/mypage',
        component: JobseekerMypage,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    
    {
        path: '/jobseeker/profile-edit',
        component: JobseekerProfileEdit,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/public-favourite-job',
        name: 'PublicFavouriteJob',
        component: JobseekerFavourite,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/favourite-job',
        name: 'FavouriteJob',
        component: JobseekerFavourite,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/scout-setting',
        component: JobseekerScoutSetting,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/scouted-detail/:scoutId/:activeTag',
        component: JobseekerScoutedDetail,
        name: 'JobseekerScoutedDetail',
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/resume-upload',
        component: JobseekerResumeUpload,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/scouted-list',
        component: JobseekerScoutedList,
        name: 'JobseekerScoutedList',
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/applied-list',
        component: JobseekerAppliedList,
        name: 'JobseekerAppliedList',
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/deactivate',
        component: Deactivate,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true // for only admin component
        }
    },
    {
        path: '/jobseeker/setting',
        component: JobseekerSetting,
        meta: {
            reqiuresAuth: true,
            recruiter: false,
            jobseeker: true
        }
    },
    {
        path: '/jobseeker/password-setting',
        component: PasswordSetting,
        meta: {
            reqiuresAuth: true,
            recruiter:false,
            jobseeker:true // for only admin component
        }
    },
    {
        path: '/jobseeker/time-zone',
        component: Timezone,
        meta: {
            reqiuresAuth: true,
            recruiter:false,
            jobseeker:true // for only admin component
        }
    },
    
];

export default route;