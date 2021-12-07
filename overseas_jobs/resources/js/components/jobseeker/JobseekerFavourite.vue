<template>
    <div :class="$route.name == 'PublicFavouriteJob'?'full-width' : 'user-main'">
        <!-- Search for public favourite  -->
        <!-- <div class="row job-wrapper"  v-if="$route.name == 'PublicFavouriteJob'">
            <div class="w-100">
                <h2 class="col-12 underline header pb-2  pl-0">{{ $t('jobseekerfavourite.favourite_job_search') }}</h2>
                一覧
                 <div class="row">
                    <div class="col-7">
                        <div class="new-job-block d-flex align-items-center">
                            <input type="search" class="search-word" placeholder="キーワードで絞り込む" v-model="search_word">
                            <button class="btn btn-m job-primary-color ml-2" @click="publicFavourite">
                                {{ $t('common.search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Start job favourite list   -->
        <div class="row job-wrapper pb-2">
            <h2 :class="$route.name != 'PublicFavouriteJob' ? 'underline': ''" class="col-12 header pb-2  pl-0" v-if="$route.name != 'PublicFavouriteJob'">{{ $t('jobseekerfavourite.favourite_job') }}</h2>
            <h2 :class="$route.name != 'PublicFavouriteJob' ? 'underline': ''" class="col-12 header pb-2  pl-0" v-if="$route.name == 'PublicFavouriteJob'">お気に入り求人検索</h2>
            <div class="col-12 col-lg-7 p-0 m-0 outer-wrapper-bottom" v-if="$route.name == 'PublicFavouriteJob'">
                <div class="new-job-block d-flex align-items-center pc-767-flex">               
                    <input type="search" class="search-word" placeholder="キーワードを入力してください" @input="chStatus()" v-model="search_word">
                    <button class="btn btn-m job-primary-color ml-2 border-style" @click="publicFavourite">
                        {{ $t('common.search') }}
                    </button>                   
                </div>
                <div class="new-job-block align-items-center sp-767">               
                    <input type="search" class="search-word w-100" placeholder="キーワードを入力してください" @input="chStatus()" v-model="search_word">
                    <div class="d-flex justify-content-center">
                    <button class="btn btn-m job-primary-color ml-2 border-style mt-4" @click="publicFavourite">
                        {{ $t('common.search') }}
                    </button>  
                    </div>                 
                </div>
             
            </div>
            <div class="w-100">
                <div class="row">
                    <job-card class="col-12 col-lg-6 outer-wrapper-bottom" :data="data" v-for="(data,index) in jobseeker_favourites" :key="index">   
                    <div class="col-12 text-right p-0 buttons-block">
                        <router-link :to="{ path: '/jobs/'+ data.job_id +jobdetails_route}" class="btn detailbtn border-style  ml-2">{{ $t('jobseekerfavourite.job_detail') }} </router-link>
                        <button class="btn deletebtn border-style  ml-2" @click="delFavouritejob(data.job_id,index)">{{ $t('jobseekerfavourite.remove') }} </button>
                    </div>
                    </job-card>
                </div>
                <infinite-loading @distance="1" @infinite="loadMore" spinner="waveDots" v-if="search_status == '0'">
                    <div slot="no-results"  class="row m-0">
                        <p class="no-data-txt col-12"> お気に入り求人はありません</p>
                    </div>
                    <div slot="no-more" class=""></div>
                    <div slot="error"  class="row m-0">
                        <p class="no-data-txt col-12"> エラーが発生しました</p>
                    </div>
                </infinite-loading>

                <!-- no data area for favourite -->
                <div v-if="(no_search_data && jobdetails_route == '/public-job-details')">
                    <div class="row m-0">
                        <p class="no-data-txt col-12" v-if="search_word == ''"> お気に入り求人はありません</p>
                        <p class="no-data-txt col-12" v-else> 検索条件に合致するデータがみつかりません</p>
                    </div>
                </div>
                <div v-if="!no_search_data && del_data && jobseeker_favourites.length == 0">
                    <div class="row m-0">
                        <p class="no-data-txt col-12"> 
                            <span v-if="search_word == ''">お気に入り求人はありません </span>
                            <span v-else>検索条件に合致するデータがみつかりません </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Recent Job list -->
        <div class="row job-wrapper pb-2" v-if="$route.name != 'PublicFavouriteJob'">
            <h2 class="col-12 underline header pl-0">最近見た求人</h2>
            <div class="w-100">
                <div class="row">
                <job-card class="col-12 col-lg-6 outer-wrapper-bottom" :data="data" v-for="(data,index) in popular_jobs" :key="index">   
                        <div class="col-12 text-right p-0 buttons-block">
                            <router-link :to="{ path: '/jobs/'+ data.job_id +jobdetails_route}" class="btn detailbtn border-style  ml-2">{{ $t('jobseekerfavourite.job_detail') }} </router-link>
                            <button class="btn appendbtn border-style ml-2" @click="addFavouritejob(data.job_id,index,data)">
                                {{ $t('jobseekerfavourite.add_favourite') }}                               
                            </button>
                        </div>
                </job-card>
                </div>
                <!-- Start Recent Job list doesn't exist Area -->
                <div v-if="popular_jobs == 0" class="job-wrapper row col-12 p-0">
                    <p class="no-data-txt col-12">
                     {{ $t('jobseekerfavourite.no_extist_popular_job') }} 
                    </p>
                </div>
            </div>
        </div>
        <!-- Start Recommented Job list -->
        <div class="row job-wrapper pb-2" v-if="$route.name != 'PublicFavouriteJob'">
            <h2 class="col-12 underline header pl-0">{{ $t('jobseekerfavourite.recommended_job') }}</h2>
            <div class="w-100">
                <div class="row">
                    <job-card class="col-12 col-lg-6 outer-wrapper-bottom" :data="data" v-for="(data,index) in recommended_jobs" :key="index" > 
                    <div class="col-12 text-right p-0 buttons-block">
                        <router-link :to="{ path: 'jobs/'+ data.job_id + jobdetails_route}" class="btn detailbtn border-style ml-2">{{ $t('jobseekerfavourite.job_detail') }} </router-link>
                        <button class="btn appendbtn border-style ml-2" @click="addFavouritejob(data.job_id,index,data)">
                            {{ $t('jobseekerfavourite.add_favourite') }}
                        </button>
                    </div>
                    </job-card>
                </div>
                <!-- recommented job doesn't exist Area -->
                <div v-if="recommended_jobs.length == 0" class="job-wrapper row col-12 p-0">
                    <p class="no-data-txt col-12">
                        {{ $t('jobseekerfavourite.no_exist_recommended_job') }}
                    </p>
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
        // loader: '',
        change_favourite:0,
        search_status:0,
        no_search_data:false,
        pages:1,
        page:"favourite",
        search_word:'',
        jobseeker_favourites:[],
        popular_jobs:[],
        recommended_jobs:[],
        del_data: false,
        jobdetails_route: this.$route.name == 'PublicFavouriteJob' ? '/public-job-details' : '/job-details',
    }
  },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    created(){
        // this.loader = this.$loading.show();
        this.recommendedjobList();
        this.popularJobList();
        // console.log("js",this.jobseeker_favourites)
        this.$store.dispatch('jobseekerFavCount')
    },
    methods: {
        loadMore: function($state) {
            this.$api.post(`/jobseeker/favourite-jobs?page=${this.pages}`,{ search_word:this.search_word,search_status:this.search_status }) 
            .then(res => {
                // this.loader.hide();
                // this.popular_jobs = res.data.popular_jobs;                
                let message = res.data.favJobMessage;
                let test_arr = res.data.favourite_job_list.data;
                if(message != ''){
                    this.$store.dispatch('jobseekerFavCount')
                    // this.$alertService.showInfoDialog(null, 'fav job have been delete!'); 
                    this.$alertService.showInfoDialog(null,this.$t('alertMessage.fav_job_check'),this.$t('common.close'))
                } 
                if(this.change_favourite == 0) {
                    if (test_arr.length > 0) {
                        this.jobseeker_favourites.push(...test_arr);
                        this.pages += 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                }
                else {
                    this.jobseeker_favourites = res.data.favourite_job_list;
                }
                               
                
            })
            .catch(err => { 
                console.log("errr",err)
                // this.$alertService.showErrorDialog(null, this.$t('common.err_msg'));   
            }) 
        },
        chStatus(){
            if(this.search_word == ''){
                this.publicFavourite();
            }
        },
        publicFavourite() {
            // this.loader = this.$loading.show();
            this.no_search_data = false;
            this.pages = 'search';
            this.search_status = 1;
            this.del_data = false;
            this.$api.post(`/jobseeker/favourite-jobs?page=${this.pages}`,{ search_word:this.search_word,search_status:this.search_status })
            .then(res => {
                // this.loader.hide();
                if(res.data.favourite_job_list.length > 0) {
                    this.jobseeker_favourites = res.data.favourite_job_list;
                } else {
                    this.jobseeker_favourites = [];
                    this.no_search_data = true;
                }
            });
        },
        recommendedjobList() {
            this.$api.post('/jobseeker/recommented-jobs',{ page:this.page })  
            .then(res => {
                this.recommended_jobs = res.data.recommended_job_list; 
            }) 
            .catch(err => {   
                console.log("errr",err)
                // this.$alertService.showErrorDialog(null, this.$t('common.err_msg'));
            }) 
        },

        popularJobList()
        {
             this.$api.post('/jobseeker/popular-jobs')  
            .then(res => {
                this.popular_jobs = res.data.popular_jobs; 
            }) 
            .catch(err => {   
                console.log("errr",err)
                // this.$alertService.showErrorDialog(null, this.$t('common.err_msg'));
            }) 

        },

        delFavouritejob(job_id,index) {
            
            this.$alertService.showConfirmDialog(null,this.$t('alertMessage.delete_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no'))
            .then(res => {
			    if (res.value) {
                    // this.loader = this.$loading.show();
                    this.$api.post('/jobseeker/favourite-job/'+job_id+'/remove')  
                    .then(res => {
                        // this.loader.hide();
                        // this.jobseeker_favourites.splice(index,1);
                        this.no_search_data = false;
                        this.del_data = true;
                        this.search_status = 1;
                        this.change_favourite = 1;
                        this.$store.dispatch('jobseekerFavCount');
                        this.loadMore();
                        this.recommendedjobList();
                        this.popularJobList();
                        
                    })
                    .catch(err => { 
                        console.log("errr",err)
                        // this.$alertService.showErrorDialog(null, this.$t('common.err_msg'));            
                    })
                } 
            });
        },
        addFavouritejob(job_id,index,data) {
            this.$api.post('/jobseeker/favourite-job/'+job_id+'/add').then(res => {
                this.search_status = 1;
                this.change_favourite = 1;
                this.jobseeker_favourites.push(data);
                this.recommendedjobList(); 
                this.popularJobList();
                this.$store.dispatch('jobseekerFavCount')
               
            })
            .catch(err => {  
                console.log("errr",err)
                // this.$alertService.showErrorDialog(null, this.$t('common.err_msg'));           
            }) 
        },
    }
};
</script>

<style scoped>
.job-wrapper {
    margin: 0;
}
.full-width {
    width: 100%;
    padding: 0px 15px;
}

/* .appendbtn {
    background-color: #EF8B1E;
    color: #fff;
} */
.search-word{
    width: 82%;
    height: 42px;
    border: 1px solid #CED4DA;
    padding: 10px;
    outline: none;
    box-shadow: none;
}

.tag-l {
    margin-right: 3%;
}
.job-wrapper {
    margin-top: 0;
    padding-bottom: 0;
    min-height: 0px;
}
/* mobile */
.sp-767{
    display:none  !important;
}
.pc-767-flex{
    display: flex  !important;
}
@media screen and (max-width:768px){
    .sp-767{
        display:block  !important;
    }
    .pc-767-flex{
        display: none  !important;
    }
}
</style>