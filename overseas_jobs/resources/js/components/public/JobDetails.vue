<template>
<div :class="$route.name == 'PublicJobDetails'?'full-width' : 'user-main'" ref="progressContainer">
    <div v-if="$route.name == 'PublicJobDetails' && currentUser == null">
        <PublicMenu></PublicMenu>
    </div>   
    <!-- <a class="btn border-style job-primary-color" @click="$router.go(-1)">Back</a> -->
  
    <div :class="[$route.name == 'PublicJobDetails' && currentUser !== null ?'container' : '',$route.name =='PublicJobDetails'&& currentUser == null ?'container public-content':'']" ref="progressContainer" v-if="check_scroll == 'scroll'">    
        <div class="container p-0">
            <div class="col-12 applied-row">  
                <div class="new-job-block appplied-col01" id="info-box">
                    <job-card :data="details" class="job-detatil-top">
                       <div v-if="showBtn" :class="[$route.name == 'PublicJobDetails' && currentUser !== null ?'col-12 text-right p-0 buttons-block' : 'col-12 text-right p-0',$route.name =='PublicJobDetails'&& currentUser == null ?'col-12 text-right p-0':'buttons-block']">                           

                            <span v-if="currentUser != null && currentUser.role_id && ((jobappliedlist.length && !scout.length) || (jobappliedlist.length && scout.length && scout[0].scout_status == $configs.scouts.declined))">
                                <button type="button" class="show-status ml-2" disabled v-if="jobappliedlist[0].job_apply_status == $configs.job_apply.under_review">
                                    問合わせ/応募中
                                </button>                                
                                <button type="button" class="show-status ml-2" disabled v-if="jobappliedlist[0].job_apply_status == $configs.job_apply.payment_confirmed || jobappliedlist[0].job_apply_status == $configs.job_apply.unclaimed || jobappliedlist[0].job_apply_status == $configs.job_apply.billed">
                                    内定
                                </button> 

                                <!-- Decline -->
                                <span class="m-flex" v-if="jobappliedlist[0].job_apply_status == $configs.job_apply.declined">
                                    <button type="button" :class="$route.name =='PublicJobDetails'&& currentUser == null ?'btn primary-btn border-style btn-m ml-':'btn job-primary-color primary-btn border-style btn-m ml-'" @click="jobapplied(details.jobid,'apply-decline')">問合わせ/応募</button>

                                    <button class="btn appendbtn border-style ml-2"  v-if="fav_check == ''" @click="addJobFavourite(details.jobid,'details')">お気に入りへ</button>

                                    <button class="btn appendbtn-border border-style ml-2" v-if="fav_check == 'fav'" @click="removeJobFavourite(details.jobid,'details')">お気に入り</button>
                                </span>                         
                            </span>

                            <span v-if="currentUser != null && currentUser.role_id && ((!jobappliedlist.length && scout.length) || (jobappliedlist.length && scout.length && jobappliedlist[0].job_apply_status == $configs.job_apply.declined)) && scout[0].scout_status != $configs.scouts.expired">
                                <button type="button" class="show-status ml-2" disabled v-if="scout[0].scout_status == $configs.scouts.waiting || scout[0].scout_status == $configs.scouts.interested">
                                    スカウトされています
                                </button>                                
                                <button type="button" class="show-status ml-2" disabled v-if="scout[0].scout_status == $configs.scouts.payment_confirmed || scout[0].scout_status == $configs.scouts.unclaimed || scout[0].scout_status == $configs.scouts.billed">
                                    内定
                                </button> 
                                <button type="button" :class="$route.name =='PublicJobDetails'&& currentUser == null ?'btn primary-btn border-style btn-m ml-':'btn job-primary-color primary-btn border-style btn-m ml-'" v-if="scout[0].scout_status == $configs.scouts.expired" @click="jobapplied(details.jobid)">問合わせ/応募</button>

                                <!-- Decline -->
                                <span class="m-flex" v-if="scout[0].scout_status == $configs.scouts.declined && (!(jobappliedlist.length && jobappliedlist[0].job_apply_status == $configs.job_apply.declined) || !jobappliedlist.length)">
                                    <button type="button" :class="$route.name =='PublicJobDetails'&& currentUser == null ?'btn primary-btn border-style btn-m ml-':'btn job-primary-color primary-btn border-style btn-m ml-'" @click="jobapplied(details.jobid,'scout-decline')">問合わせ/応募</button>

                                    <button class="btn appendbtn border-style ml-2"  v-if="fav_check == ''" @click="addJobFavourite(details.jobid,'details')">お気に入りへ</button>

                                    <button class="btn appendbtn-border border-style ml-2" v-if="fav_check == 'fav'" @click="removeJobFavourite(details.jobid,'details')">お気に入り</button>
                                </span>
                            </span> 
                            <span class="m-flex">
                            <button type="button" :class="$route.name =='PublicJobDetails'&& currentUser == null ?'btn primary-btn border-style btn-m ml-':'btn job-primary-color primary-btn border-style btn-m ml-'" v-if="currentUser != null && currentUser.role_id && !jobappliedlist.length && !scout.length" @click="jobapplied(details.jobid)">問合わせ/応募</button>

                            <button class="btn appendbtn border-style ml-2"  v-if="currentUser != null && fav_check == '' && !scout.length && !jobappliedlist.length" @click="addJobFavourite(details.jobid,'details')">お気に入りへ</button>

                            <button class="btn appendbtn-border border-style ml-2" v-if="currentUser != null && fav_check == 'fav' && !scout.length && !jobappliedlist.length" @click="removeJobFavourite(details.jobid,'details')">お気に入り</button> 
                            </span>
                        </div>        
                    </job-card>
                </div>
                <div class="applied-col02" >  
                        <h5 class="d-flex align-items-center">
                            <strong class="txt-vertical-ellipsis flex-kara">
                                {{details.recruiter_name}}
                                <!-- <span v-if="details.recruiter_show_name == '正式名称で表示'">
                                    {{details.recruiter_name}}
                                </span>
                                <span v-else>
                                    <span v-if="details.recruiter_nick_name == '' || details.recruiter_nick_name == null">
                                        {{details.recruiter_name}}
                                    </span>
                                    <span v-else>
                                        {{details.}}
                                    </span>                                    
                                </span> -->
                                
                            </strong>
                            <!-- <div class="sp-768-inline">
                                 <strong>{{details.recruiter_name}}</strong>
                            </div> -->
                           
                            <span class="text-label-color scout-txt ml-4" style="font-size:15px;">からのお仕事です</span></h5>
                         <table class="profile-tbl">
                            <tr class="tbl-row">
                                <td class="tbl-col-tit text-label-color">設立年月日</td>
                                <td class="tbl-col-data">
                                <span v-if="details.establishment_date">
                                    {{details.establishment_date}}
                                    <!-- <span v-if="details.establishment_date.length > 1">
                                        {{details.establishment_date[0]}}年 {{details.establishment_date[1]}}月 {{details.establishment_date[2]}}日
                                    </span> -->
                                </span>
                                <span v-else>-</span>
                                </td>
                            </tr>
                             <tr class="tbl-row">
                                <td class="tbl-col-tit text-label-color">代表者氏名</td>
                                <td class="tbl-col-data txt-vertical-ellipsis">
                                    <span v-if="details.representative_name">{{details.representative_name}}</span>
                                    <span v-else>-</span>
                                </td>
                             </tr>
                             <tr class="tbl-row">
                                <td class="tbl-col-tit text-label-color">従業員数</td>
                                <td class="tbl-col-data">
                                    <span v-if="details.num_of_employees">{{details.num_of_employees}}</span>
                                    <span v-else>-</span>
                                </td>
                             </tr>
                             <tr class="tbl-row">
                                <td class="tbl-col-tit text-label-color">事業内容</td>
                                <td class="tbl-col-data txt-vertical-ellipsis-4">
                                    <span v-if="details.business_description">{{details.business_description}}</span>
                                    <span v-else>-</span>
                                </td>
                             </tr>
                         </table>
                         <div class="col-12 text-right pr-0 mt-2" v-if="details.record_status == 3"> 
                            <span class="show-status d-inline-block" style="color:red">退会</span>
                        </div>

                        <!--<p class="mb-1">
                            <span class="label-txt"> 設立年月日</span>                       
                            <span v-if="details.establishment_date">
                                <span v-if="details.establishment_date.length > 1">
                                    {{details.establishment_date[0]}}年 {{details.establishment_date[1]}}月 {{details.establishment_date[2]}}日
                                </span>
                            </span>
                        </p>
                        <p class="mb-1">
                            <span class="label-txt"> 代表者氏名</span>{{details.representative_name}}
                        </p>-->
                                                            
                </div>
            </div>
        </div>

        <!-- Job detail shared component -->
        <DetailsInfo :job-id="details.jobid"></DetailsInfo>

            <!--related_jobs-->
            <div class="row col-12 job-wrapper mt-5 ml-0 mr-0 mb-0  p-0" v-show="false">
                <div class="w-100">
                    <h2 class="col-12 underline header pb-2 pl-0">この求人を見た人は、こちらもみています</h2>
                    <div class="row">    
                    <!-- {{related_jobs}} or {{relatedJobs}} -->            
                        <job-card class="col-6 outer-wrapper-bottom" :data="data" v-for="(data,index) in related_jobs" :key="index">
                            
                        <div class="col-12 text-right p-0 buttons-block">
                        <!--<div :class="[$route.name == 'PublicJobDetails' && currentUser != null ?'d-none' : 'row underline']"></div>-->
                            <router-link :to="{ path: '/jobs/'+ data.id + jobdetails_route}" class="btn border-style" :class="currentUser != null ? 'detailbtn' : 'primary-btn'">求人詳細</router-link>
                            <button class="btn appendbtn border-style ml-2"  v-if="currentUser != null && currentUser.role_id == 3 && data.fav == ''" @click="addJobFavourite(data.id,'related')">
                                お気に入りへ
                            </button>
                            <button class="btn appendbtn-border border-style ml-2" v-if="currentUser != null && currentUser.role_id == 3 && data.fav == 'fav'" @click="removeJobFavourite(data.id,'related')">
                                お気に入り
                            </button>
                        </div>                       
                        </job-card>            
                        
                        <infinite-loading @distance="1" @infinite="getJobs" spinner="waveDots">
                            <div slot="no-results" class="row m-0 col-12">
                                <p class="no-data-txt">データがありません</p>
                            </div>                   
                            <div slot="no-more"></div>
                            <div slot="error" class="row m-0 col-12">
                                <p class="no-data-txt">エラーが発生しました</p>
                            </div>
                        </infinite-loading>   
                                
                    </div> 
                </div>           
            </div>        
        </div>
    </div>

</template>
<script>
    import DetailsInfo from "../public/DetailsInfo";
    import PublicMenu from "../menu/PublicMenu";
	import { mapGetters  } from "vuex";
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        }, 
        data() {
            return {
                job_id_list:[],
                popular:[],
                jobappliedlist : [],
                scout :[],
                page:1,
                jobid: Number(this.$route.params.id),
                details: {},
                scout: {},
                related_jobs: [],
                related_job_list:[],
                jobdetails_route: this.$route.name == 'PublicJobDetails' ? '/public-job-details' : '/job-details',
                check_scroll:'scroll',
                loader: '',
                fav_check: '',
                fav_job_ids:[],
                showBtn: false,
            };
        },
        components: { DetailsInfo, PublicMenu },
        props: {
            keyword: {
                type: String,
                default:'',
             }
         },
        // props:['keyword'],
        computed: {
			...mapGetters(["currentUser", "authError"]),  
        },
        created(){
        
            // var job_arr = {};var country_arr = {};
            // const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December" ];
            // const d = new Date();
            // let currentMonthJob =  monthNames[d.getMonth()]+'SearchHistoryJob';
            // let currentMonthCountry = monthNames[d.getMonth()]+'SearchHistoryCountry';

         
            // var popular =JSON.parse(localStorage.getItem('popular')) ;
            // if(popular)
            // {
            //     this.popular = popular;
            //     Object.keys(this.popular[0]['job']).filter(x=>{
            //         if(x == currentMonthJob)
            //         {
            //             var id_list =  this.popular[0]['job'][currentMonthJob].id;
            //             var res = id_list.split(",");
            //             if(!res.includes(JSON.stringify(this.jobid))){ 
            //                 res.push(JSON.stringify(this.jobid));
            //                 this.popular[0]['job'][currentMonthJob].id =  res.toString();
            //                 localStorage.setItem('popular',JSON.stringify(this.popular));
            //                 this.$api.post(`/popular-jobs/`+this.jobid).then((response)=>{ 
            //                     console.log(response);
            //                 });
            //             }
            //         }
            //         else{
            //             delete  this.popular[0]['job'][x];
            //             this.popular[0]['job'][currentMonthJob] = {'id':JSON.stringify(this.jobid)};
            //             localStorage.setItem('popular',JSON.stringify(this.popular));
            //             this.$api.post(`/popular-jobs/`+this.jobid).then((response)=>{

            //             });
            //         }
            //     })
 
            // }else{
            //     job_arr[currentMonthJob] = {'id':JSON.stringify(this.jobid)};
            //     this.popular.push({"job":job_arr});
            //     country_arr[currentMonthCountry] = {'id':""};
            //     this.popular.push({"country":country_arr});  
            //     localStorage.setItem('popular', JSON.stringify(this.popular));
            //     this.$api.post(`/popular-jobs/`+this.jobid).then((response)=>{

            //     });
            // }
            this.loader = this.$loading.show();

            this.$api.post(`/popular-jobs/`+this.jobid).then((response)=>{});

            this.$api.get(`/jobs/`+ this.jobid).then((response) => {
                this.loader.hide();
                this.details = response.data.data.details;
                this.related_files = response.data.data.related_files;
                this.fav_check = this.details.fav;
                this.fav_job_ids =[];
                if(this.fav_check != '')
                {
                    this.fav_job_ids.push(this.details.jobid);
                }
                this.related_files.map(x=>{
                if(x.fav != ''){
                    this.fav_job_ids.push(x.id);
                }
                });                
            });  

            $("html").animate({ scrollTop: 0 }, "slow");            
            if(this.currentUser != null){
                this.getJobAppliedlist();
            }              
        },

        methods: {
            getJobs($state) {               
            this.check_scroll = 'scroll';                       
            this.$api.get(`/jobs/${this.jobid}/details?page=${this.page}`).then((response) => {       
               this.loader.hide();            
               let result =  response.data.data.related_jobs.data;               
                           
                if (result.length > 0) {
                   
                    this.page += 1;
                    this.related_jobs.push(...result);
                    $state.loaded();                   
                } else {                     
                    $state.complete();
                }                
            })
            .catch(() => {
                // alert("エラーが発生しました。");
            })
        },
            jobapplied($jobid, isDecline = null){
                if(isDecline == null) {
                    var msg = this.$t('alertMessage.apply');
                }
                else {
                    var msg = 'この求人は以前に辞退/不採用となっていますがよろしいでしょうか。';
                }

                this.$alertService.showConfirmDialog(null, msg,this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                   if(dialogResult.value){
                        let loading = this.$loading.show();  
                        this.$api.post(`/jobseeker/job-applied/`+ $jobid  ).then(res => {
                            this.$alertService.showSuccessDialog(null, this.$t('alertMessage.applied'), this.$t('common.close'));
                            this.getJobAppliedlist();
							this.$store.dispatch('jobseekerFavCount')
							
							// --Fire an event to refresh chattable list
							this.$emit('refreshChattables');
                        }).catch((e) => {
                          console.log(e);
                        }).finally(() => {
                           loading.hide();
                        });;
                    }
                })
            },
             getJobAppliedlist(){
                this.$api.get("jobseeker/jopappliedlist/"+ Number(this.$route.params.id)).then((response) => {
                    this.loader.hide();
                    this.jobappliedlist = response.data.data.jobapplied;
                    this.scout = response.data.data.scout;
                    this.showBtn = true;
                });
             },

            addJobFavourite($jobId,$type) {  
                
                this.fav_job_ids.push($jobId);
                if($type == 'details'){
                     this.fav_check = 'fav';
                }else{
                    this.related_jobs.map(x=>{
                    if(this.fav_job_ids.includes(x.id)) {
                        x.fav = 'fav';
                    }
                  });
                }
                this.$api.get(`/jobseeker/job-favourite/`+ $jobId +`/add`).then(res => {
                
                    this.$store.dispatch('jobseekerFavCount')
                });
            },
            removeJobFavourite($jobId,$type) {
                const index = this.fav_job_ids.indexOf($jobId);
                if (index > -1) {
                   this.fav_job_ids.splice(index, 1);
                }

                if($type == 'details'){
                    this.fav_check= '';
                }
                else{
                   this.related_jobs.map(x=>{
                        if(!this.fav_job_ids.includes(x.id)) {
                            x.fav = '';
                        }
                    });
                }

                this.$api.get('/jobseeker/job-favourite/'+ $jobId+`/remove`).then(res => {
                    this.$store.dispatch('jobseekerFavCount')
                });
            }
        }
    };
</script>
<style scoped lang="scss">
h5 strong{
    line-height: 1.4 !important;
}
.scout-txt{
	flex: 0 0 120px;
}
/*zkm*/
// .sp-768-inline{
//     display: none;
// }
// .pc-768-inline{
//     display: -webkit-inline-box;
// }
@media screen and (max-width:768px)
{
// .sp-768-inline{
//     display:inline;
// }
// .pc-768-inline{
//     display: none;
// }
.m-flex{
    margin-top: 0px;
    padding-top: 2px !important;
    display: flex;
    }
.buttons-block .btn {
   width: 110px;
   padding: 0.5rem;
   font-size: 10px !important; 
}
.applied-row {
    display: block !important;
    margin-bottom: 20px;
    padding: 15px;
    background: #d3e2ee;
}
.appplied-col01 {
    width: 100%;
    margin-right: 0px;
    padding: 20px;  
}
.applied-col02 {
    width: 100%;
    margin-left: 0px;
    padding: 20px;
    background: #fff;
    border-top: 8px solid #d3e2ee;
    // .d-flex{
    //      display: block !important;
    // }
    // strong { 
    //     word-break:break-all
    // }
}
}
@media screen and (max-width:767px){
.buttons-block .btn {
   width: 100px;
   padding: 0.5rem;
   font-size: 10px !important; 
}  
  
}
/*zkm*/
    .scouted-row {
        margin-bottom: 20px;
        padding: 25px;
        background: #d3e2ee !important;
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }
    .scouted-col {
        padding: 30px 40px;
        background: #fff;
    }
    .scouted-img {
        border: 1px solid #ddd;
    }
    .scouted-tit {
        font-size: 1.15rem;
    }
    .scouted-txt {
        line-height: 0.8;
    }
    .scouted-span {
        display: inline-block;
        min-width: 150px;
    }
    .scout-btn {
        width: 100%;
        margin-top: 10px;
        background: #86a4ac;
        color: #fff;
    }
    .btn-gp .btn {
        min-width: 150px;
        color: #fff;
        border-radius: 50px;
    }
    .chat-btn {
        background: #39ad4a;
    }
    .decline-btn {
        margin-top: 10px;
    }
    .full-width {
        width: 100%;
    }
    .infinite-loading-container {
        width: 100%;
    }
    .public-content {
        padding: 50px 15px 60px 15px;
    }
    .tbl-col-tit{
        width: 20%;
        min-width: 100px;
        font-size: 15px;
        vertical-align: top;
    }
    .flex-kara{
        max-width: 65%;        
        line-height: 1.5;
        font-size: 16px;
    }
    .txt-vertical-ellipsis, .txt-vertical-ellipsis-4 {
        line-height: 2;
    }
</style>
