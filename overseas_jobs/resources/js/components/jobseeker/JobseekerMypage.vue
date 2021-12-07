<template>
    <div class="user-main">
        <h2 class="user-head-tit job-primary-color">{{ $t('jobseekermypage.title') }}</h2>                            
        <div class="list-wrap mb-4">
            <!-- count of unread scount email -->
            <ul class="list-group row mb-5">
                <li class="list-group-item d-flex  align-items-center">
                    <p class="post-description">{{ $t('jobseekermypage.scount_mail') }}　<span class="count-no">{{unread_email}}</span>{{ $t('jobseekermypage.count') }}</p>
                    <router-link to="/jobseeker/scouted-list" class="btn job-primary-color rounded-pill">{{ $t('jobseekermypage.button') }}</router-link>
                </li>
            </ul>
            <!-- Recommmended Jobs -->
            <div class="row">
                <h2 class="col-12 underline header pb-2  pl-0">{{ $t('jobseekermypage.recommended_job') }}</h2>
                <div class="w-100">
                    <div class="row">
                        <job-card class="col-12 col-lg-6 job-wrapper m-0 outer-wrapper-bottom" :data="data" v-for="(data,index) in recommended_jobs" :key="index" > 
                          <div class="col-12 text-right p-0 buttons-block">
                            <router-link :to="{ path: '/jobs/'+ data.job_id +'/job-details'}" class="btn detailbtn border-style btn-m">{{ $t('jobseekerfavourite.job_detail') }} </router-link>
                            <button v-if="data.disable" class="btn appendbtn-border border-style ml-2 btn-m" @click="removeFavouritejob(data.job_id)">
                                {{ $t('jobseekermypage.remove_favourite') }}
                            </button>
                            <button v-else class="btn appendbtn border-style ml-2 btn-m" @click="addFavouritejob(data.job_id)">
                                {{ $t('jobseekerfavourite.add_favourite') }}
                            </button>
                          </div>
                        </job-card>                        
                    </div>
                    <div>
                        <div v-if="recommended_jobs.length == 0" class="row m-0">
                            <p class="no-data-txt col-12">
                                {{ $t('jobseekerfavourite.no_exist_recommended_job') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
      unread_email:"",
      page:"home",
      recommended_jobs:[]
    }
  },
  created(){
      this.$api.get('/jobseeker/unread-scoutemail/'+this.$store.getters.currentUser.id,)  
            .then(res => {
              this.unread_email = res.data.unread_scout_mail;
              console.log('UnreadEmail');
              console.log(res.data);
            })
            .catch(err => {                
            }) 
       this.recommendedjobList();
  },
  methods:{
    recommendedjobList() {
        this.$api.post('/jobseeker/recommented-jobs/',{ page:this.page })  
            .then(res => {
                this.recommended_jobs = res.data.recommended_job_list;
        })
            .catch(err => {                
        }) 
    },
    addFavouritejob(job_id) {
        this.$api.post('/jobseeker/favourite-job/'+job_id+'/add')  
            .then(res => {
                this.recommendedjobList();
                this.$store.dispatch('jobseekerFavCount')
        })
        .catch(err => { 
            console.log(err);               
        }) 
    },
    removeFavouritejob(job_id) {
        this.$api.post('/jobseeker/favourite-job/'+job_id+'/remove')  
            .then(res => {
                this.recommendedjobList();
                this.$store.dispatch('jobseekerFavCount')
        })
        .catch(err => { 
            console.log(err);               
        }) 
    }
  }
};
</script>

<style scoped>
.list-wrap {
    background: #fff;
    padding: 50px 50px 100px 50px;
}
.list-group {
    padding: 20px;
    background: #f3f3f3;
    border: 1px solid #DEDEDE;
    border-radius: 5px;
}
.list-group-item {
    padding: .75rem 0;
    border: none;
    background: #f3f3f3;
}
.post-description {
    width: 50%;
    margin-bottom: 0px;
}
.post-description .count-no {
    display: inline-block;
    margin: 0 2px 0 10px;
    font-size: 20px;
    color: #6085a3;
}
.rounded-pill {
    width: 250px;
    padding: 0.7rem 2rem;
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
        padding: 50px 30px 100px 30px;
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