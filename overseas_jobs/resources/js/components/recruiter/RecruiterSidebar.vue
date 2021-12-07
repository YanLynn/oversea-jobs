<template>
  <div class="user-sidebar pc">
        <div class="side-panel">
            <ul class="list-group">
                <li v-for="recruiterInfo in recruiterInfo " :key="recruiterInfo.id"><div class="profile"><p> <!--会員番号 - --> {{ recruiterInfo.recruiter_number}}</p><h5 class="text-break"> <!-- 名前 - -->{{ recruiterInfo .recruiter_name}}</h5></div></li>
                <span class="bd-bottom"></span>
                <li><router-link to="/recruiter" class="recruiter-primary-color-txt">{{ $t('sidebar.recruiter[0]["recruiter_toppage"]') }}</router-link></li> 
                <li class="mb-3"><router-link to="/recruiter/mypage" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["recruiter_mypage"]') }}</router-link></li>           
                <li><b class="pl-10">{{ $t('sidebar.recruiter[0]["Job_applicants"]') }}</b></li>
                <li class="mb-3"><router-link to="/recruiter/job-applicant" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["applicants_list"]') }}</router-link></li>  
                <li><b class="pl-10">{{ $t('sidebar.recruiter[0]["recruiter_jobs_posting"]') }}</b></li>
                <li><router-link to="/recruiter/jobs/create" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_create"]') }}</router-link></li>
                <li><router-link to="/recruiter/job-draft" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_draft"]') }}</router-link></li>
                <li class="mb-3"><router-link to="/recruiter/jobs" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_list"]') }}</router-link></li>        
                <li><b class="pl-10">{{ $t('sidebar.recruiter[0]["scout"]') }}</b></li>
                <li><router-link to="/recruiter/scouted-list" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_scouted_list"]') }}</router-link></li>
                <li><router-link to="/recruiter/scout-favourite" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_scouted_consideration"]') }}</router-link></li>  
                <span class="bd-bottom"></span>         
                <li><router-link to="/recruiter/profile-setting" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["recruiter_profile_edit"]') }}</router-link></li>
                <!-- <li><router-link to="/recruiter/password-setting" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["payment_setting"]') }}</router-link></li> -->
                <li><router-link to="/recruiter/time-zone" class="recruiter-primary-color-txt"> タイムゾーン設定</router-link></li>
                <li><router-link to="/recruiter/password-setting" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["password_setting"]') }}</router-link></li>
                <li><router-link to="/recruiter/setting" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["setting"]') }}</router-link></li> 
                <li><router-link to="/recruiter/deactivate" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["withdraw"]') }}</router-link></li>   
                <!-- <li><router-link to="/recruiter/successful-register" class="recruiter-primary-color-txt">register thanks</router-link></li>   
                <li><router-link to="/recruiter/register-completed" class="recruiter-primary-color-txt"> register completed</router-link></li>   
                <li><router-link to="/recruiter/scout-payment-successful" class="recruiter-primary-color-txt"> scout payment</router-link></li>   
                <li><router-link to="/recruiter/credit-payment-error" class="recruiter-primary-color-txt"> credit payment error</router-link></li>   
                <li><router-link to="/recruiter/credit-payment-completed" class="recruiter-primary-color-txt"> credit payment completed</router-link></li>    -->
                 <span class="bd-bottom"></span>                   
                <li><a href="#!" @click.prevent="logout" class="recruiter-primary-color-txt"> {{$t('sidebar.recruiter[0]["logout"]')}}</a></li>
            </ul>
            
        </div>
  </div>
</template>
<script>
export default {
    data(){
      return{
        langs:['日本語','English'],
      
      }
    },
    methods:{
        logout(){
            localStorage.removeItem('jobseeker-keyword'); 
			this.$store.commit('logout');
			socket.disconnect();
            this.$router.push('/recruiter/login');
            
        }
    },
    computed:{ 
        currentUser(){
            return this.$store.getters.currentUser
        },
        recruiterInfo(){
            return this.$store.getters.getUserInfo
        }
    }
};
</script>
<style lang="scss" scoped>
.router-link-exact-active {
    background-color: #52ccc2;
    color: #fff !important;
    font-weight: normal;
}
.profile{
    display: inline-block;
    width: 100%;
    padding: 0px 15px;
    text-align: left;
    h5{
        color:#095a54 !important;
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