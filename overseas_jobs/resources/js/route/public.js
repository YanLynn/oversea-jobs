import home from '../components/public/home/PublicHome';
import JobDetails from '../components/public/JobDetails';
import JobSearchAll from '../components/public/JobSearchAll';
import JobseekerAll from '../components/public/JobseekerAll';

const route = [{
        path: '/',
        name: 'Home',
        component: home,
    },
    {
        path:'/jobs/:id/public-job-details',
        name: "PublicJobDetails",        
        component: JobDetails,
    },
    {
        path:'/jobs/:id/job-details',
        name: "JobDetails",        
        component: JobDetails,
    },
    {
        path:'/public-jobsearch-all',
        name: "publicJobSearchAll",        
        component: JobSearchAll,
    },
    {
        path:'/login-jobSearch-all',
        name: "JobSearchAll",        
        component: JobSearchAll,
    },
    {
        path:'/public-jobseeker-all',
        name:"JobseekerAll",
        component : JobseekerAll,
    } 
];

export default route;

