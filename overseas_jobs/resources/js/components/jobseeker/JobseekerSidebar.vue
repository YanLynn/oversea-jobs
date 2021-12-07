<template>
  <div class="user-sidebar pc">
    <div class="side-panel">        
        <ul class="list-group">
            <li v-for="jobseekerInfo in jobseekerInfo" :key="jobseekerInfo.id"><div class="profile"><p> <!--会員番号 - --> {{jobseekerInfo.jobseeker_number}}</p><h5 class="text-break"> <!--名前 - --> {{jobseekerInfo.jobseeker_name}}</h5></div></li>
            <span class="bd-bottom"></span>
            <li><router-link to="/jobseeker/favourite-job" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["jobConsideration"]') }}</router-link></li>
            <li><router-link to="/jobseeker/applied-list" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["jobsapplied"]') }}</router-link></li>
            <li class="mb-3"><router-link to="/jobseeker/scouted-list" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["jobs_scouted"]') }}</router-link></li>
            
            <li><router-link to="/jobseeker/profile-edit" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["profile_edit"]') }}</router-link></li>
            <li class="mb-3"><router-link to="/jobseeker/resume-upload" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["Resume_upload"]') }}</router-link></li>

            <li><router-link to="/jobseeker/time-zone" class="job-primary-color-txt"> タイムゾーン設定</router-link></li>
            <li><router-link to="/jobseeker/scout-setting" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["scout_settings"]') }}</router-link></li>
            <li><router-link to="/jobseeker/password-setting" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["password_setting"]') }}</router-link></li>
            <li><router-link to="/jobseeker/setting" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["setting"]') }}</router-link></li>
            <li class="mb-3"><router-link to="/jobseeker/deactivate" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["withdraw"]') }}</router-link></li>
            
            <li><a href='' @click.prevent="logout" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["logout"]') }}</a>
              <span class="bd-bottom"></span>
            </li>
           
            <li><router-link to="/jobseeker/mypage" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["jobseeker_toppage"]') }}</router-link></li>
            <li><router-link to="/jobseeker" class="job-primary-color-txt"> {{ $t('sidebar.jobseeker[0]["oversea_toppage"]') }}</router-link></li>
        </ul>
      
    </div> 
  </div>
</template>
<script>
export default {
    methods:{
        logout(){
            localStorage.removeItem('job-keyword'); 
			this.$store.commit('logout');
			socket.disconnect();
            this.$router.push('/jobseeker/login');
        }
    },
    computed:{
        currentUser(){
            return this.$store.getters.currentUser
        },
        jobseekerInfo(){
            return this.$store.getters.getUserInfo
        }
    }
};
</script>
<style lang="scss" scoped>
.router-link-exact-active {
    background-color: #8DB7CC;
    color: #fff !important;
    font-weight: normal;
}
.profile{
    display: inline-block;
    width: 100%;
    padding: 0px 15px;
    text-align: left;
    h5{
        color:#008eff !important;  
        font-size: 16px;   
    }
    p{
        margin-bottom: 0px;
        color: #8a8a8a;
    }
}
.pc {
    display: block;
}
.sp {
    display: none;
}
@media screen and (max-width:1023px){
    .pc {
        display: none;
    }
    .sp {
        display: block;
    }   
}
</style>