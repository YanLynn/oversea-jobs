<template>
    <div class="user-main">
        <h2 class="user-head-tit recruiter-primarylight-color">企業会員管理画面</h2>
        <!-- <h1>Hello {{ currentUser.name }}</h1> -->
        <div class="list-wrap">
        <ul class="list-group">
            <li class="list-group-item d-flex align-items-center">
            <p class="post-description">
               <label class="post-label"> {{ $tc('recruitermypage.no_of_waiting_person_scouted_today')}}</label> 
                <span>{{new_jobseekers}}</span> 
                <label class="mb-0">{{ $tc('recruitermypage.unit') }}</label>
            </p>
            <router-link to="/recruiter" class="btn primary-btn rounded-pill recruiter-primary-color">{{ $t('recruitermypage.oversea_jobs_TOP_site')}}</router-link>
            </li>
            <li class="list-group-item d-flex align-items-center">
            <p class="post-description">
                <label class="post-label">{{ $tc('recruitermypage.no_of_job_applicants_today')}} </label>
                <span>{{job_applicants}}</span> 
                <label class="mb-0">{{ $tc('recruitermypage.unit') }}</label>
            </p>
            <router-link to="/recruiter/job-applicant" class="btn primary-btn rounded-pill recruiter-primary-color">{{ $t('recruitermypage.list_of_applicants')}}</router-link>
            </li>
            <li class="list-group-item d-flex align-items-center">
            <p class="post-description">
               <label class="post-label"> {{ $tc('recruitermypage.no_of_new_scout_trustees_today')}} </label>
                <span>{{scouted_trustees}}</span> 
                <label class="mb-0">{{ $tc('recruitermypage.unit') }}</label>
            </p>
            <router-link to="/recruiter/scouted-list" class="btn primary-btn rounded-pill recruiter-primary-color">{{ $t('recruitermypage.list_of_already_scouted')}}</router-link>
            </li>
            <li class="list-group-item d-flex align-items-center">
            <p class="post-description"></p>
            </li>
        </ul>
        </div>
    </div>
</template>
<script>
export default {
    metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	}, 
    data() {
        return {
            job_applicants : '',
            scouted_trustees : '',
            new_jobseekers : '',
        };
    },
    created() {
        this.$api.post("/recruiter/mypage")
        .then((response) => {
            console.log("mypage-data:", response.data);
            this.job_applicants   = response.data.data.job_applicants;
            this.new_jobseekers    = response.data.data.new_jobseekers;
            this.scouted_trustees = response.data.data.scouted_trustees;
        })
        .catch((errors) => {
            console.log(errors);
        });
    },
    methods: {
    },
    computed: {
        currentUser() {
        return this.$store.getters.currentUser;
        },
    },
};
</script>

<style scoped>
.list-wrap {
    background: #fff;
    padding: 30px 50px 100px 30px;
}
.list-group {
    max-width: 800px;
}
.list-group-item {
    padding: 0.75rem 0;
    border: none;
}
.post-description {
    display:flex;
    width: 50%;
    margin-bottom: 0px;
    align-items: baseline;
}
.post-description span {
    display: inline-block;
    margin: 0 2px 0 10px;
    font-size: 20px;
    color: #0071b4;
}
.rounded-pill {
    width: 220px;
    padding: 0.7rem 2rem;
}
.post-label{
    width: 205px;
    max-width: 100%;
    color: #333333;
}
@media only screen and (max-width: 812px) and (orientation : landscape){
    .post-description{
        width: 60%;
    }
    .rounded-pill{
        padding: 0.7rem 0;
    }
}
@media screen and (max-width: 480px){
    .list-wrap{
        padding: 30px 20px 50px 20px;
    }
    .list-group-item{
        display:block !important;
    }
    .post-description{
        width: 100%;
    }
    .rounded-pill {
        width: 180px;
        padding: 0.7rem 0;
        font-size: 12px;
        margin-top: 10px;
    }   
}
</style>