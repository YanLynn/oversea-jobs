<template>
<div class="container">
    <!-- Jobseeker Profile Modal before login  -->
    <transition name="fade">
        <div class="modal-wrapper modal-wrapper-mb" v-if="showModal">
            <div class="modal-block">
                <div class="modal-container recruiter-modal">
                    <div class="head-wrap m-b-5">
                        <h3 class="user-head-tit">詳細情報</h3>    
                        <button @click="showModal=false" class="cross-btn"> <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span></button>
                        </div>
                    <div class="modal-body public-details-modal">
                        <div class="row mt-2 p-0">
                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="row m-0">
                                    <div class="col-8 data-txt pl0-res">
                                        <div class="row face-img-wrap">
                                            <div class="face-img col-12" v-if="jobseeker.face_image_private_status == 1">
                                                <img :src="jobseeker.face_img"  alt="job-img" />  
                                                <p class="show-info col-12">顔写真</p>                                              
                                            </div>
                                             <div class="face-img col-12" v-else>
                                                <img :src="'./images/no_public.png'"  alt="job-img" />
                                                <p class="show-info col-12">顔写真</p> 
                                            </div>         
                                                                            
                                        </div>                                        
                                       
                                    </div>
                                    <div class="col-4 pr0-res">
                                        <span v-for="(related_img,index) in related_images" :key="index" class="thumbnail-image">
                                            <img :src="related_img" alt="related-image" />                                           
                                        </span>
                                        <p class="show-info mt-0 w-145">関連画像</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 col-md-5 col-lg-5">
                                 <p class="no-video-wrapper">
                                 <img src="/images/youtube-blur.jpg" alt="動画なし" class="img-fluid">                               
                                 <!-- <i class="fa fa-exclamation-circle" aria-hidden="true"></i>動画は利用できません -->
                                 </p>
                                  <p class="show-info">関連動画</p>
                            </div>
                        </div>
                        <!--info-->                       
                        <dl class="detail-list clearfix intro-row p-0">                       
                            <dt class="detail-head">希望職種</dt>
                            
                            <dd class="detail-data">
                                <span v-if="jobseeker.desired_occupation.length > 0">
                                    <span v-for="occupation in jobseeker.desired_occupation" v-bind:key="occupation.id">{{occupation}} <br></span>
                                </span>
                                <span v-if="jobseeker.desired_occupation.length == 0 && jobseeker.desired_occupation_status != 1">未入力</span>
                                <span v-if="jobseeker.desired_occupation_status == 1">こだわらない </span>
                            </dd>

                            <dt class="detail-head">希望勤務地</dt>
                            <dd class="detail-data">                                
                                <p class="mb-0">
                                    <label class="desired-label">第一希望</label>
                                    <span v-if="jobseeker.desired_location_1 != null">{{jobseeker.desired_location_1}}</span>
                                    <span v-else>未入力</span>
                                </p>
                                <p class="mb-0">
                                    <label class="desired-label">第二希望</label>
                                    <span v-if="jobseeker.desired_location_2 != null">{{jobseeker.desired_location_2}}</span>
                                    <span v-else>未入力</span>
                                </p>
                                <p class="mb-0">
                                    <label class="desired-label">第三希望</label>
                                    <span v-if="jobseeker.desired_location_3 != null">{{jobseeker.desired_location_3}}</span>
                                    <span v-else>未入力</span>
                                </p>
                            </dd>
                            <!-- <dd class="detail-data" v-else>未入力</dd> -->

                            <dt class="detail-head">語学レベル</dt>
                            <dd class="detail-data">
                                <span v-if="jobseeker.language.length > 0">
                                    <span class="mb-0" v-for="(lang_name,index) in jobseeker.language" :key="index">
                                        {{ lang_name }} <br>
                                    </span>
                                </span>
                                <span v-else>未入力</span>
                            </dd>    
                        </dl>
                        <!--end info-->
                        <div class="row m-0">
                            <div class="col-12 pl0-res pr0-res">
                                <h6 class="about-tit">自己PR、海外で勤務したい理由等</h6>
                                <p class="about-description">
                                   <pre class="pre">{{jobseeker.self_info}}</pre>
                                </p>
                            </div>
                        </div>
                        <div class="about-box">
                            <p>
                                人材のさらに詳しい情報を閲覧するには企業会員として登録する必要があります。<br>
                                企業会員は以下の機能が利用できます。<br>
                                ・ぼかしなしの写真や関連する動画の閲覧<br>
                                ・履歴書や職務経歴書のダウンロード(スカウト後)
                            </p>
                            <a href="/recruiter/register" class="btn about-btn"> 企業会員新規登録</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
    <!-- End Jobseeker Profile Modal before login  -->
    <div v-if="currentPage == 'public'">
        <div class="row job-wrapper mt-4 mt0-768">
        <!--for mobile -->
        <div class="sp-812 row col-12 m-0 p-0">
        <ul role="tablist" class="nav nav-tabs sn-tabs-basic">
            <li role="presentation">
                <a id="tablabel1" aria-controls="aftertab1" tabindex="0" role="tab" data-toggle="tab" aria-selected="true" href="#aftertab1" class="active" ref="tablabel1" @click="tabClick('tablabel1')">
                求人
                </a>
            </li>
            <li role="presentation">
                <a id="tablabel2" aria-controls="aftertab2" tabindex="0" role="tab" data-toggle="tab" href="#aftertab2" aria-selected="false" ref="tablabel2" @click="tabClick('tablabel2')">スカウト待ち人材</a>
            </li>                        
        </ul>
        <div class="tab-content search-tab-content p15-768">
            <div id="aftertab1" aria-hidden="false" role="tabpanel" class="tab-pane active searchdiv" aria-labelledby="tablabel1">
                <p class="header underline mb-0" v-if="keyword == null">新着順 </p>
                <!-- <span v-if="keyword != null">検索結果</span> -->
                <div class="row job-wrapper m-0">               
                    <job-card class="col-12 p-0 outer-wrapper-bottom" :data="data" v-for="(data,index) in jobseeker_search_result" :key="index" > 
                        <div class="col-12 text-right p-0 buttons-block">
                            <router-link @click.native="addKeyword" :to="{ path: 'jobs/'+ data.job_id +'/public-job-details'}" class="btn primary-btn border-style">求人詳細 </router-link>
                            <button class="btn appendbtn border-style ml-2"  v-if="currentUser != null && currentUser.role_id == 3 && data.fav == ''" @click="addJobFavourite(data.job_id)">
                                    お気に入りへ
                                </button>
                            <button class="btn appendbtn-border border-style ml-2" v-if="currentUser != null && currentUser.role_id == 3 && data.fav == 'fav'" @click="removeJobFavourite(data.job_id)">
                                    お気に入り
                            </button>
                        </div>
                    </job-card> 
                    <div v-if="jobseeker_search_result.length == 0 && recruiter_search_result.length > 0  && check_scroll == 'scroll'" class="w-100">
                        <p class="no-data-txt col-12"> データがありません</p>
                    </div>          
                </div> 
            </div>
            <div id="aftertab2" aria-hidden="true" role="tabpanel" class="tab-pane searchdiv" aria-labelledby="tablabel2">
                <p class="header underline mb-0" v-if="keyword == null">新着順</p>
                <!-- <span v-if="keyword != null">検索結果</span> -->
              <div class="row">
                <jobSeeker-card :data="data" v-for="(data,index) in recruiter_search_result" :key="index">
                    <div slot="jobSeekerCardButton" class="d-flex justify-content-end">
                        <button class="btn primary-btn border-style" id="show-modal" @click="navigateJobseekerProfile(data.id)">詳細情報</button>
                    </div> 
                </jobSeeker-card>
                <div v-if="jobseeker_search_result.length > 0 && recruiter_search_result.length == 0 && check_scroll == 'scroll'" class="col-12 w-100">
                    <p class="no-data-txt col-12"> データがありません</p>
                </div>
              </div>
            </div>
        </div>
        </div>
        <!--end for mobile -->
        <div class="pc-812-flex p-0 col-12">
            <div class="col-6">
                <h4 class="underline header">
                     求人　新着順 
                    <!-- <div class="float-right viewall">
                        <span class="text-right">
                             <router-link :to="{ path: '/public-jobsearch-all'}" style="margin-left: 3px;"> 全て見る</router-link>
                             <span class="glyphicon glyphicon-chevron-right"></span>
                        </span>
                    </div> -->
                </h4>  
                <div class="row job-wrapper m-0">               
                    <job-card class="col-12 p-0 outer-wrapper-bottom" :data="data" v-for="(data,index) in jobseeker_search_result" :key="index" > 
                     <div class="col-12 text-right p-0 buttons-block">
                            <router-link @click.native="addKeyword" :to="{ path: 'jobs/'+ data.job_id +'/public-job-details'}" class="btn primary-btn border-style">求人詳細 </router-link>
                            <button class="btn appendbtn border-style ml-2"  v-if="currentUser != null && currentUser.role_id == 3 && data.fav == ''" @click="addJobFavourite(data.job_id)">
                                    お気に入りへ
                                </button>
                            <button class="btn appendbtn-border border-style ml-2" v-if="currentUser != null && currentUser.role_id == 3 && data.fav == 'fav'" @click="removeJobFavourite(data.job_id)">
                                    お気に入り
                            </button>
                     </div>
                    </job-card>
                    <div v-if="jobseeker_search_result.length == 0 && recruiter_search_result.length > 0  && check_scroll == 'scroll'" class="w-100">
                        <p class="no-data-txt no-data-job col-12"> データがありません</p>
                    </div>          
                </div>          
            </div>
            <div class="col-6" >
                <h4 class="underline header">
                    スカウト待ってます　新着順
                    <!-- <div class="float-right viewall">
                        <span  class="text-right">
                            <router-link :to="{ path:'/public-jobseeker-all'}" style="margin-left: 3px;"> 全て見る </router-link>
                         
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </span>
                    </div> -->
                </h4> 
                <div class="row">
                    <jobSeeker-card :data="data" v-for="(data,index) in recruiter_search_result" :key="index">
                            <div slot="jobSeekerCardButton" class="d-flex justify-content-end">
                               <button class="btn primary-btn border-style" id="show-modal" @click="navigateJobseekerProfile(data.id)">詳細情報</button>
                            </div> 
                    </jobSeeker-card>
                    <div v-if="jobseeker_search_result.length > 0 && recruiter_search_result.length == 0 && check_scroll == 'scroll'" class="col-12 w-100">
                        <p class="no-data-txt no-data-job col-12"> データがありません</p>
                    </div>
                    <!-- <div class="col-6 inner-wrapper-right outer-wrapper-bottom" v-for="data in recruiter_search_result" :key="data.id">
                        <div class="new-job-block h-auto">
                            <div class="row data-block" style="height:175px;">
                                <div class="col-5 sub-block">
                                   <div class="img-block">
                                     <img
                                        v-bind:src="data.face_image_blur"
                                        alt="job-img"
                                        class="img-fluid job-img"
                                    />
                                   </div>
                                </div>
                                <div class="col-7 pl-2">
                                    <p class="mb-1">
                                        <span class="label-txt">希望職種 ({{data.id}})</span><br>
                                       <span class="txt-vertical-ellipsis">{{data.desired_occupation}}</span>
                                    </p>
                                    <p>
                                        <span class="label-txt">希望勤務地</span><br><span class="txt-vertical-ellipsis">{{data.desired_location_1}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row col-12 p-0 m-0">
                                <p class="date">
                                    <span class="label-txt">語学レベル</span> ビジネス活用レベル
                                </p>
                            </div>
                            <div class="row underline"></div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button class="btn primary-btn border-style" id="show-modal" @click="showJobseekerProfile(data.id)">詳細情報</button>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        </div>        
    </div>
    <div v-if="currentPage == 'recruiter' && (see_all == 'see_all' || see_all == null)">    
        <div class="row job-wrapper mt-4">
            <div class="col-12">
                <h4 class="underline header">                    
                    <span class="pc-812" v-if="s_keyword == null"> スカウト待ってます　新着順 </span>
                    <span class="pc-812" v-if="s_keyword != null"> {{s_keyword}} </span>

                    <span class="sp-812">スカウト待ち人材</span>
                    <!-- <div class="float-right viewall">
                        <span  v-if="seeAll == null" class="text-right">
                            全て見る 
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </span>
                    </div> -->
                </h4>
                <span class="sp-812 sub-header" v-if="s_keyword == null">新着順</span>
                <span class="sp-812 sub-header" v-if="s_keyword != null">検索結果</span>
                <div class="row">
                    <jobSeeker-card :data="data" v-for="(data,index) in recruiter_search_result" :key="index" id="recruiter-col-3">                      
                            <div slot="jobSeekerCardButton" class="d-flex justify-content-end">
                              <button class="btn border-style" :class="currentUser == null ? 'primary-btn': 'recruiter-primary-color'" id="show-modal" @click="navigateJobseekerProfile(data.id)">
                                詳細情報
                            </button>
                          
                            <!-- <button class="btn border-style recruiter-primary-color" v-if="currentUser != null && currentUser.role_id == 2" @click="addJobSeekerFavourite(data.id)">
                            お気に入りへ
                            </button> -->
                            <button class="btn appendbtn ml-2 border-style" v-if="data.fav == '' && currentUser != null" @click="addJobSeekerFavourite(data.id)">{{ $t('recruiter_scout_entry.add_favourite') }}</button>
				        	<button class="btn appendbtn-border ml-2 border-style" v-if="data.fav == 'fav' && currentUser != null"  @click="removeJobSeekerFavourite(data.id)">{{ $t('recruiter_scout_entry.remove_favourite') }}</button>
                            </div> 
                    </jobSeeker-card>
                    
                </div>
                
                <div class="" v-if="recruiter_search_result.length == 0 && dataLoad && check_scroll == 'filter' && !isLoadingMore">
                    <p class="no-data-txt col-12" style="margin-bottom:0px">検索条件に合致するデータがみつかりません</p>
                </div>
               
            </div>
        </div>
    </div>
    <div v-if="currentPage == 'jobseeker' && (see_all == 'see_all' || see_all == null)">
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="underline header">               
                   <span class="pc-812" v-if="keyword == null"> 求人　新着順 </span>
                   <span class="pc-812" v-if="keyword != null"> {{keyword}} </span>   

                   <span class="sp-812"> 求人</span>                
                    <!-- <div class="float-right viewall">
                        <span class="text-right">
                            <router-link v-if="keyword == null" :to="{ path: '/public-jobSearch-all'}" style="margin-left: 3px;"> 全て見る </router-link>
                            <span v-if="keyword == null" class="glyphicon glyphicon-chevron-right"></span>
                        </span>
                    </div> -->
                </h4>
                <span class="sp-812 sub-header mb-0" v-if="keyword == null">新着順</span>
                <span class="sp-812 sub-header mb-0" v-if="keyword != null">検索結果</span>
            </div>
        </div>
        
        <div class="row job-wrapper">
            <job-card class="col-12 col-lg-6 outer-wrapper-bottom" :data="data" v-for="(data,index) in jobseeker_search_result" :key="index"> 
              <div class="col-12 text-right p-0 buttons-block">
                    <router-link @click.native="addKeyword" :to="{ path: 'jobs/'+ data.job_id +'/public-job-details'}" class="btn border-style" :class="currentUser == null ? 'primary-btn': 'job-primary-color'">求人詳細 </router-link>
                    <button class="btn appendbtn border-style ml-2" v-if="currentUser != null && currentUser.role_id == 3 && data.fav == ''" @click="addJobFavourite(data.job_id)">お気に入りへ</button>
                    <button class="btn appendbtn-border border-style ml-2" v-if="currentUser != null && currentUser.role_id == 3 && data.fav == 'fav'" @click="removeJobFavourite(data.job_id)">お気に入り</button>
              </div>
            </job-card>
      
             <div class="col-12" v-if="jobseeker_search_result.length == 0 && dataLoad && check_scroll == 'filter' && !isLoadingMore" style="margin-top:-10px;">
                <p class="no-data-txt" style="margin-bottom:0px;margin-top:10px;">検索条件に合致するデータがみつかりません</p>
            </div>           
        </div>
       
    </div> 

    <div v-if="jobseeker_search_result.length == 0 && recruiter_search_result.length == 0 && check_scroll == 'scroll' && !isLoadingMore" class="w-100">
            <p class="no-data-txt col-12" style="margin-top:-20px;margin-bottom:20px;"> データがありません</p>
    </div> 

    <img style="margin-left: 48%; width: 55px;" src="/images/loading.gif" alt="loading" v-if="isLoadingMore">
    <div v-if="jobseeker_search_result.length > 0 || recruiter_search_result.length > 0 ">        
        <button v-if="!isLoadingMore && ((jobseeker_search_result.length != jobseeker_total_page) || (recruiter_search_result.length != recruiter_total_page))" class="btn seemore-btn border-style" @click="searchJob()"> もっと見る <span data-v-6d07759d="" class="fa fa-angle-right"></span></button>
    </div>
<!-- 
    <div v-if="jobseeker_search_result.length != 0 && currentPage == 'jobseeker'" >
        <button style="margin-left:600px;margin-bottom:20px;" v-if="(jobseeker_search_result.length != jobseeker_total_page) " class="btn btn-primary" @click="filteredSearchJob('search',null)"> Load More b</button>
    </div>

    <div v-if="recruiter_search_result.length != 0 && currentPage == 'recruiter'" >
        <button  style="margin-left:600px;margin-bottom:20px;" v-if="(recruiter_search_result.length != recruiter_total_page) " class="btn btn-primary" @click="filteredSearchJobseekers(null)"> Load More c </button>
    </div> -->

    <!-- <infinite-loading @distance="1" @infinite="searchJob" spinner="waveDots" v-if="check_scroll == 'scroll'">
        <div slot="no-results"  class="row m-0 mt-n4">
            <p class="no-data-txt col-12"> データがありません</p>
        </div>
        <div slot="no-more" class=""></div>
        <div slot="error" class="py-3 text-danger">エラーが発生しました</div>
    </infinite-loading>   -->
    
</div>

</template>

<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {           
            page:1,
            check_scroll:'scroll',
            jobseeker_search_result:[],
            recruiter_search_result:[],
            showModal: false,
            jobseeker:{
                nick_name:'',
                face_img:'',
                desired_occupation:'',
                desired_location_1:'',
                desired_location_2:'',
                desired_location_3:'',
                preferred_workplace:'',
                language:[],
                self_info:'',
                desired_occupation_status: null
            },
            related_images:[],
            recruiter:'',
            job:'',
            res:'',
            recruiterPage:1, 
            dataLoad: false,
            lang:[],
            occupation:[],
            keyword:'',
            s_keyword:'',
            fav_jobseeker_ids:[],
            fav_job_ids:[],
            jobseeker_total_page:'',
            recruiter_total_page:'',
            seeker_filtered_page:1,
            recruiter_filtered_page:1,
            isLoadingMore: true, // --control variable for load more
        }
    },
    props: ['currentPage','keywordSearch', 'jobSeekerData','recruiterData','seeAll'],
    created() {
        this.initFun();
    },
    computed: {
        ...mapGetters(["currentUser", "authError"]),
    },
 
    methods: {
        initFun() {
            // console.log("this.jobseekerData",this.jobSeekerData);
            this.see_all = this.seeAll;
            if(JSON.parse(localStorage.getItem('job-keyword'))){
                
                this.keyword = '求人　検索結果';
                let data = JSON.parse(localStorage.getItem('job-keyword'));
                this.jobSeekerData.keyword  = data.keyword;
                this.jobSeekerData.country = data.country;
                this.jobSeekerData.occupation = data.occupation;
            
            }  
            if(JSON.parse(localStorage.getItem('jobseeker-keyword'))){
                this.s_keyword = 'スカウト待ってます　検索結果';
                let data = JSON.parse(localStorage.getItem('jobseeker-keyword'));
                this.recruiterData.keyword  = data.keyword;
                this.recruiterData.country = data.country;
                this.recruiterData.occupation = data.occupation;            
            }     
    
            if(this.jobSeekerData.keyword == '' && this.jobSeekerData.country.length == 0 && this.jobSeekerData.occupation.length == 0
            && this.recruiterData.keyword == '' && this.recruiterData.country.length == 0 && this.recruiterData.occupation.length == 0){
                this.searchJob();
            }
            else if((this.jobSeekerData.keyword != '' || this.jobSeekerData.country.length != 0 || this.jobSeekerData.occupation.length != 0) )
            {
                this.filteredSearchJob('search','求人　検索結果');
            }
            else if((this.recruiterData.keyword != '' || this.recruiterData.country.length != 0 || this.recruiterData.occupation.length != 0) && (this.$route.path == '/recruiter' ))
            {    
                this.filteredSearchJobseekers('スカウト待ってます　検索結果');
            }
        },
        tabClick(tabName){
            this.$emit('onTabChange', tabName);
            this.page = 1;
            this.recruiterPage = 1;
            this.jobseeker_search_result = [];
            this.recruiter_search_result = [];
            this.searchJob();
        },
        changeTab(tabName){
            this.$refs[tabName].click();
        },
        addKeyword(){           
            localStorage.setItem('job-keyword',JSON.stringify(this.jobSeekerData));
        },
        searchJob(){
            console.log(this.recruiter_search_result.length +'/'+ this.recruiter_total_page)
            this.isLoadingMore = true;
            // let loading = this.$loading.show();
            if(this.jobSeekerData.keyword == '')
            {
                this.keyword = null;
            }

            if(this.recruiterData.keyword == '')
            {
                this.s_keyword = null;
            }
           
            this.check_scroll = 'scroll';
            let searchData;

              if(this.$route.path == '/recruiter'){
                  searchData = JSON.stringify(this.recruiterData);;
              }
              if(this.$route.path == '/jobseeker'){
                  searchData = JSON.stringify(this.jobSeekerData);;
              }
              if(this.$route.path == '/'){
                  searchData = JSON.stringify(this.jobSeekerData);;
              }
           
            
            if(this.currentUser == null){
                Promise.all([
					// --Jobseeker search
					this.$api.get(`/jobseeker-search/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData} })
					.then(r => Promise.resolve(r.data)).catch(error => Promise.reject(error.response)),
					// --Recruiter search
					this.$api.get(`/recruiter-search/${this.check_scroll}?page=${this.recruiterPage}`, { params: {searchData : searchData} })
					.then(r => Promise.resolve(r.data)).catch(error => Promise.reject(error.response)),
				])
				.then(r => {
                    console.log("r0",r[0].data.data)
                    console.log("r1",r[1].data.original.result.data)
                    const search_result = r[0].data.data;
                    const recruiter_result = r[1].data.original.result.data;
                    
                    this.jobseeker_total_page = r[0].data.total;
                    this.job = search_result.length; 
                    if(search_result.length > 0 )
                    {  
                        this.page += 1;
                        this.jobseeker_search_result.push(...search_result);
                        
                        // $state.loaded();
                    } else { 
                        this.dataLoad = true;
                        // //$state.complete();  
                    }
                    
                    this.recruiter_total_page = r[1].data.original.result.total;
                    var lang = r[1].data.original.language;
                    var occupation = r[1].data.original.occupation;

                    this.res = recruiter_result.length;  
                    if(this.job > 0 || this.res > 0 )
                    {  
                        this.recruiterPage += 1;
                        this.recruiter_search_result.push(...recruiter_result);
                       
                        this.addOccupAndLang(lang,occupation);
                        //$state.loaded();
                    } else {   
                        //$state.complete();  
                    } 
                }).finally(() => {
                    // loading.hide();
                    setTimeout(() => {
						this.isLoadingMore = false;
					}, 500); 
                });
                
            }else if(this.currentUser.role_id == 3){

                // let loading = this.$loading.show();
                 this.$api.get(`/jobseeker-search/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData} }).then(res => {            
                    let search_result = res.data.data.data;
                    this.jobseeker_total_page = res.data.data.total;
                    this.job = search_result.length; 
                    if(search_result.length > 0 )
                    {  
                        this.page += 1;
                        this.jobseeker_search_result.push(...search_result);
                        this.fav_job_ids =[];
                        this.jobseeker_search_result.map(x=>{
                            
                            if(x.fav != ''){
                                this.fav_job_ids.push(x.job_id);
                            }
                        })

          
                        //$state.loaded();
                    } else { 
                        this.dataLoad = true;  
                        //$state.complete();  
                    }            
                }).finally(() => {
                    // loading.hide();
                    setTimeout(() => {
						this.isLoadingMore = false;
					}, 500); 
                });
            }else if (this.currentUser.role_id == 2){
                // let loading = this.$loading.show();
                this.$api.get(`/recruiter-search/${this.check_scroll}?page=${this.recruiterPage}`, { params: {searchData : searchData} }).then(res => {            
                    let recruiter_result = res.data.data.original.result.data;
                   
                    this.recruiter_total_page = res.data.data.original.result.total;
                     console.log("recruiters",this.recruiter_total_page)
                    var lang = res.data.data.original.language;
                    var occupation = res.data.data.original.occupation;
                    this.res = recruiter_result.length;  
                    if(recruiter_result.length > 0 )
                    {  
                        this.recruiterPage += 1;
                        this.recruiter_search_result.push(...recruiter_result);
                        this.fav_jobseeker_ids =[];
                        this.recruiter_search_result.map(x=>{
                            if(x.fav != ''){
                                this.fav_jobseeker_ids.push(x.id);
                            }
                        })
                        this.addOccupAndLang(lang,occupation);
                        
                        //$state.loaded();
                    } else {   
                        this.dataLoad = true;
                        //$state.complete();  
                    } 
                }).finally(() => {
                    // loading.hide();
                    setTimeout(() => {
						this.isLoadingMore = false;
					}, 500); 
                });
            }
            
        },
        // searchJob($state)
        // {
        //     if(this.jobSeekerData.keyword == '')
        //     {
        //         this.keyword = null;
        //     }

        //     if(this.recruiterData.keyword == '')
        //     {
        //         this.s_keyword = null;
        //     }
           
        //     this.check_scroll = 'scroll';
        //     let searchData;

        //       if(this.$route.path == '/recruiter'){
        //           searchData = JSON.stringify(this.recruiterData);;
        //       }
        //       if(this.$route.path == '/jobseeker'){
        //           searchData = JSON.stringify(this.jobSeekerData);;
        //       }
        //       if(this.$route.path == '/'){
        //           searchData = JSON.stringify(this.jobSeekerData);;
        //       }
           
            
        //     if(this.currentUser == null){
              
        //         this.$api.get(`/jobseeker-search/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData} }).then(res => {            
        //             let search_result = res.data.data.data;
        //             this.job = search_result.length; 
        //             if(search_result.length > 0 )
        //             {  
        //                 this.page += 1;
        //                 this.jobseeker_search_result.push(...search_result);
        //                 // //$state.loaded();
        //             } else { 
        //                 this.dataLoad = true;
        //                 // //$state.complete();  
        //             }            
        //         });
              

        //         this.$api.get(`/recruiter-search/${this.check_scroll}?page=${this.recruiterPage}`, { params: {searchData : searchData} }).then(res => {            
                
        //             let recruiter_result = res.data.data.original.result.data;
        //             var lang = res.data.data.original.language;
        //             var occupation = res.data.data.original.occupation;

        //             this.res = recruiter_result.length;  
        //             if(this.job > 0 || this.res > 0 )
        //             {  
        //                 this.recruiterPage += 1;
        //                 this.recruiter_search_result.push(...recruiter_result);
        //                 this.addOccupAndLang(lang,occupation);
        //                 //$state.loaded();
        //             } else {   
        //                 //$state.complete();  
        //             } 
        //          });
                
                
        //     }else if(this.currentUser.role_id == 3){
                
        //          this.$api.get(`/jobseeker-search/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData} }).then(res => {            
        //             let search_result = res.data.data.data;
        //             this.job = search_result.length; 
        //             if(search_result.length > 0 )
        //             {  
        //                 this.page += 1;
        //                 this.jobseeker_search_result.push(...search_result);
        //                 this.fav_job_ids =[];
        //                 this.jobseeker_search_result.map(x=>{
        //                     if(x.fav != ''){
        //                         this.fav_job_ids.push(x.job_id);
        //                     }
        //                 })
        //                 //$state.loaded();
        //             } else { 
        //                 this.dataLoad = true;  
        //                 //$state.complete();  
        //             }            
        //         });
        //     }else if (this.currentUser.role_id == 2){
                
        //         this.$api.get(`/recruiter-search/${this.check_scroll}?page=${this.recruiterPage}`, { params: {searchData : searchData} }).then(res => {            
        //             let recruiter_result = res.data.data.original.result.data;
        //             var lang = res.data.data.original.language;
        //             var occupation = res.data.data.original.occupation;
        //             this.res = recruiter_result.length;  
        //             if(recruiter_result.length > 0 )
        //             {  
        //                 this.recruiterPage += 1;
        //                 this.recruiter_search_result.push(...recruiter_result);
        //                 this.fav_jobseeker_ids =[];
        //                 this.recruiter_search_result.map(x=>{
        //                     if(x.fav != ''){
        //                         this.fav_jobseeker_ids.push(x.id);
        //                     }
        //                 })
        //                 this.addOccupAndLang(lang,occupation);
                        
        //                 //$state.loaded();
        //             } else {   
        //                 this.dataLoad = true;
        //                 //$state.complete();  
        //             } 
        //         });
        //     }
            
        // },
       
       filteredSearchJob(type,keyword){  
           this.isLoadingMore = true;
            let searchData = '';
            if(type == 'search'){
                searchData = JSON.stringify(this.jobSeekerData); 
            }
            else{
                searchData = JSON.stringify(this.keywordSearch); 
            }
           
            // this.check_scroll = 'filter';     
        
            this.$api.get(`/jobseeker-search/filter?page=${this.seeker_filtered_page}`, { params: {searchData : searchData} }).then(res => {      
                if(this.jobSeekerData.country.length == 0 && this.jobSeekerData.occupation.length == 0 && this.jobSeekerData.keyword == '' && res.data.data.length == 0)
                {
                    this.check_scroll = 'scroll';                    
                }
                else{
                    this.check_scroll = 'filter';   
                }

                this.$nextTick(() => {
                    this.jobseeker_search_result = res.data.data;  
                    this.jobseeker_total_page = this.jobseeker_search_result.length;
                    this.recruiter_search_result = [];
                    this.recruiter_total_page = this.recruiter_search_result.length;
                    this.see_all = 'see_all';           
                    this.seeker_filtered_page += 1;
                    if(this.jobseeker_search_result.length == 0){
                        this.dataLoad = true;
                    }
                    this.fav_job_ids =[];
                    this.jobseeker_search_result.map(x=>{
                        if(x.fav != ''){
                            this.fav_job_ids.push(x.job_id);
                        }
                    })
                    this.$parent.getJobseekerData();   
                });
                
                
            }).finally(() => {
                    setTimeout(() => {
						this.isLoadingMore = false;
					}, 500); 
                });
            
             this.keyword = keyword; 
        },
       
       filteredSearchJobseekers(keywords) {  
           this.isLoadingMore = true;
            this.s_keyword = keywords;
            // this.check_scroll = 'filter';   
            let searchData = JSON.stringify(this.recruiterData); 
            this.$api.get(`/recruiter-search/filter?page=${this.recruiter_filtered_page}`, { params: {searchData : searchData} }).then(res => {    
                if(this.recruiterData.country.length == 0 && this.recruiterData.occupation.length == 0 && this.recruiterData.keyword == '' && res.data.data.original.result.length == 0)
                {
                    this.check_scroll = 'scroll';                    
                }
                else{
                    this.check_scroll = 'filter';   
                } 
                
                this.$nextTick(() => {
                    this.recruiter_search_result = res.data.data.original.result;
                    this.recruiter_total_page = this.recruiter_search_result.length;
                    this.jobseeker_search_result = [];
                    this.jobseeker_total_page = this.jobseeker_search_result.length;
                    this.recruiter_filtered_page += 1;
                    this.see_all = 'see_all';
                    var lang = res.data.data.original.language;
                    var occupation = res.data.data.original.occupation;
                    this.addOccupAndLang(lang,occupation);
                    
                        if(this.recruiter_search_result.length == 0){
                            this.dataLoad = true;
                        }
                        this.fav_jobseeker_ids =[];
                        this.recruiter_search_result.map(x=>{
                            if(x.fav != ''){
                                this.fav_jobseeker_ids.push(x.id);
                            }
                        })
                        this.$parent.getRecruiterData();
                });              
               
            }).finally(() => {
                    setTimeout(() => {
						this.isLoadingMore = false;
					}, 500); 
                });
            
        },
       
       addOccupAndLang(lang,occupation){
            
             this.recruiter_search_result.map(e=>{
                    this.lang = [];
                    lang.filter(lan=>{
                        if(e.id === lan.id){
                            if(lan.language_level){
                                var split_arr = lan.language_level.split(',');
                                this.lang = split_arr;
                            }
                        }
                    });


                    this.occupation = [];
                    occupation.filter(occ=>{
                       
                       if(e.id === occ.id){
                           if(occ.occupation_name){
                                var split_arr = occ.occupation_name.split(',');
                                this.occupation = split_arr ;

                           }
                       }
                   })
                e.language_level = this.lang;
                e.desired_occupation = this.occupation;
            })

        },
      
       addJobFavourite($jobId) {

            this.fav_job_ids.push($jobId);
            this.jobseeker_search_result.map(x=>{
              if(this.fav_job_ids.includes(x.job_id)) {
                  x.fav = 'fav';
              }
            });

            this.$api.get(`/jobseeker/job-favourite/`+ $jobId +`/add`).then(res => {
             
                this.$store.dispatch('jobseekerFavCount')
            });

            // if(this.page != 1)
            // {
            //     this.page = this.page -1 ;
            // }
            // let searchData = JSON.stringify(this.jobSeekerData); 
            // this.$api.get(`/jobseeker/job-favourite/`+ $jobId +`/add/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData, type: 'public'} }).then(res => {
            //     this.jobseeker_search_result = res.data.data;
            //     this.page += 1;
            //     this.$store.dispatch('jobseekerFavCount')

            // });
        },
        
       removeJobFavourite($jobId) {

            const index = this.fav_job_ids.indexOf($jobId);
            if (index > -1) {
               this.fav_job_ids.splice(index, 1);
            }

            this.jobseeker_search_result.map(x=>{
              if(!this.fav_job_ids.includes(x.job_id)) {
                  x.fav = '';
              }
            });

            this.$api.get('/jobseeker/job-favourite/'+ $jobId+`/remove`).then(res => {
                this.$store.dispatch('jobseekerFavCount')
            });
            // if(this.page != 1)
            // {
            //     this.page = this.page -1 ;
            // }
            // // this.check_scroll = 'filter';
            // let searchData = JSON.stringify(this.jobSeekerData); 
            // this.$api.get('/jobseeker/job-favourite/'+ $jobId+`/remove/${this.check_scroll}?page=${this.page}`, { params: {searchData : searchData, type: 'public'} }).then(res => {
            //     this.jobseeker_search_result = res.data.data;
			// 	this.page += 1;
			// 	this.$store.dispatch('jobseekerFavCount')

            // });
        },

       addJobSeekerFavourite($id) {
         
            this.fav_jobseeker_ids.push($id);
            this.recruiter_search_result.map(x=>{
              if(this.fav_jobseeker_ids.includes(x.id)) {
                  x.fav = 'fav';
              }
            });
            this.$api.get(`/recruiter/jobseeker-favourite/`+ $id + `/add` ).then(res => {
          
                this.$store.dispatch('recFavCount')

            });
        },

       removeJobSeekerFavourite($id) {

            const index = this.fav_jobseeker_ids.indexOf($id);
            if (index > -1) {
               this.fav_jobseeker_ids.splice(index, 1);
            }

            this.recruiter_search_result.map(x=>{
              if(!this.fav_jobseeker_ids.includes(x.id)) {
                  x.fav = '';
              }
            });

            this.$api.get(`/recruiter/jobseeker-favourite/`+ $id + `/remove` ).then(res => {
          
                this.$store.dispatch('recFavCount')

            });

        },

       showJobseekerProfile(id) {
            this.related_images = [];
            this.showModal = true;
            this.$api.get('/jobseeker-profile/'+id)
            .then(res=>{
                let lang = res.data.data.language;
                this.jobseeker.desired_occupation_status = res.data.data.jobseeker_profile[0].desired_occupation_status;
                this.jobseeker.nick_name = res.data.data.jobseeker_profile[0].jobseeker_nick_name;
                this.jobseeker.face_image_private_status = res.data.data.jobseeker_profile[0].face_image_private_status;
                this.jobseeker.face_img = res.data.data.jobseeker_profile[0].face_image;
                if(res.data.data.jobseeker_profile[0].occupation != null) {
                     this.jobseeker.desired_occupation = res.data.data.jobseeker_profile[0].occupation.split(',');
                }
                else{
                    this.jobseeker.desired_occupation = [];
                }
                lang.filter(lan=>{
                        if(lan.language_level){
                            var split_arr = lan.language_level.split(',');
                            this.jobseeker.language  = split_arr;
                        }   
                    });
                
                this.jobseeker.desired_location_1 = res.data.data.jobseeker_profile[0].desired_location_1;
                this.jobseeker.desired_location_2 = res.data.data.jobseeker_profile[0].desired_location_2;
                this.jobseeker.desired_location_3 = res.data.data.jobseeker_profile[0].desired_location_3;
                this.jobseeker.self_info = res.data.data.jobseeker_profile[0].self_pr;
                

              
                // limit just only 4 for related images
                if(res.data.data.related_images.length == 0 || res.data.data.related_images.length<5) {
                    for(var i=0; i<4; i++) {
                        if(res.data.data.related_images[i]) {
                            this.related_images.push(res.data.data.related_images[i]['file_url']);
                            // if(res.data.data.jobseeker_profile[0].face_image_private_status == 1)
                            //     this.related_images.push(res.data.data.related_images[i]['file_url']);
                            // else 
                            //     this.related_images.push('/images/no_public.png');
                        } else {
                            this.related_images.push('/images/default.png');
                        }
                    }
                }
           });
        },

       navigateJobseekerProfile(id) {
           
            localStorage.setItem('jobseeker-keyword',JSON.stringify(this.recruiterData));
            if (this.currentUser && this.currentUser.role_id == 2) {
                this.$router.push({ name: 'PublicRecruiterScoutEntry', params: { jobseekerId: id }});
            }
            else {
                this.showModal = true;
                this.showJobseekerProfile(id); 
            }
        },
    },
}
</script>
<style lang="scss" scoped>
#recruiter-col-3{
    -ms-flex: 0 0 50% !important;
    flex: 0 0 50% !important;
    max-width: 50% !important;  
}
.job-wrapper{   
    margin-left: -15px;
    margin-right: -15px;
    min-height: 0px!important;
}
/*for same height in public search results
.job-wrapper .new-job-block {
    height: 260px;
  
}
.job-wrapper .btn {
    width: 125px;
    padding: .375rem .2rem;
}
*/
.face-img-wrap {
    height: 240px;
}
.face-img {
    width: 100%;
    height: 240px;
    margin-bottom: 10px;
}
.face-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.thumbnail-image {
    align-items: flex-start;
    cursor: default;
    width: 145px;
    height: 82px;
    display: flex;
    overflow: hidden;
    margin: 0px 0px 10px 0px;
  /*  border: 1px solid #eee;*/
    box-shadow: none;
}
.thumbnail-image > img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    transition: all 250ms;
}
.data-txt p {
    margin-bottom: .5rem;
}
.w-145{
    width:145px;
}
.about-tit {
    margin: 10px 0;
    color: #666;
     font-size: 14px;   
}
.about-description {
    height: 120px;
    padding: 10px 20px;
    line-height: 2;
    border: 1px solid #e1e1ee;
    margin: 0px 0 20px 0;
    overflow-y: auto;
}
.about-description::-webkit-scrollbar-thumb
{
	background-color: #000000;	
}
.about-description::-webkit-scrollbar {
  width: 10px;
}

.about-description::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px #ddd; 
  border-radius: 10px;
}

.about-description::-webkit-scrollbar-thumb {
  background: #0062ff; 
  border-radius: 10px;
}

.about-description::-webkit-scrollbar-thumb:hover {
  background: #0062ff; 
}
.pre {
    padding: 0;
    margin: 0;
    white-space: pre-line;
    min-height: 40px;
}
.about-box {
    position: relative;
    float: right;
    width: 65%;
    padding: 10px 30px 5px 30px;
    border: 1px solid #0062ff;
    border-radius: 10px;
    color: #0062ff;
    margin-right: 1.4%;
    min-height: 100px;
    max-height: 300px;
    overflow-y: auto;
}
.about-btn {
    position: absolute;
    bottom: 30px;
    right: 40px;
    padding: 10px 20px;
    color: #fff;
    background-color: #0062ff;
    border-radius: 25px;
}
/*for details for recruiter*/
.modal-wrapper{
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.2);
    transition: opacity 0.3s ease;
    z-index: 9998;
}
.modal-wrapper .modal-container {
    padding: 0 0 30px 0;
}
.user-head-tit {
    border-radius: 0;
}
.modal-wrapper .cross-btn {  
    background: #fff;
    z-index: 99;
    top: 8px;
}
.icon-times:before {
    content: "\e91e";
    color: #fff;
}
/*
.job-wrapper {
    margin: 30px 0 70px 0;
}
*/

.public-wrapper .inner-wrapper-right .new-job-block {
    height: 290px;
}
.mb-30 {
    margin-bottom: 30px;
}
.recruiter-modal{
    width: 950px;
    max-height: 810px;
    margin: 0px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    overflow-y: auto;
    z-index: 999;
}
.detail-list{
    width: 95%;
    margin: 0 auto;
}
.show-info{    
    border: 1px solid #d2d2d2;
    width: 100%;
    display: inline-block;
    line-height: 1.5;
    padding: 6px;
    margin-top: 10px;
    background: #fff;
    color: #333;
    text-align: center;
}
/*mobile*/
.sn-tabs-basic > li{
    padding: 15px 30px 10px;
}
.sn-tabs-basic > li > .active{
    background: #0062ff;
    padding: 15px 30px 10px;
    border-bottom: 3px solid #0062ff;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    color: #fff;
    &:focus{
        opacity:1;
    }
}
.active{
    display: initial;
    border-bottom: 1px solid #fff;
    padding-bottom: 5px;    
}
.sp-812{
    display:none  !important;
}
.pc-812{
    display:block !important;
}
.pc-812-flex{
    display: flex  !important;
}
@media screen and (max-width:812px){  
.sp-812{
    display:block !important;
}
.pc-812{
    display: none !important;
}
.pc-812-flex{
    display: none !important;
}
.pl0-res{
    padding-left:0px !important;
}
.pr0-res{
    padding-right: 0px !important;
}
.tab-content{
    padding: 15px 15px;
}
.nav-tabs{
    width: 100%;
    border-bottom: 3px solid #0062ff;
    padding: 10px 15px;
    background: #ebebeb;
    padding-top: 40px;    
    li{
        margin: 0px;
        padding: 0px 10px;
        min-width: 83px;
        text-align: center;
    }
}
.nav-tabs li a{
    font-size: 18px;
    color: #4772bf;
    text-decoration: none;
}
.sub-header{
    font-size: 16px;
    color: #4772bf;
    margin-bottom: 10px;
    margin-top: 0px;
}
.active{
    display: initial;
    border-bottom: 3px solid #0062ff;
    padding-bottom: 0px;    
}
#recruiter-col-3{
    -ms-flex: 0 0 100% !important;
    flex: 0 0 100% !important;
    max-width: 100% !important;
    padding-right: 15px !important;
    padding-left: 15px !important;
}
.public-details-modal{
    max-height: 100%;
}
.modal-wrapper-mb{
    display: block;
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    padding-bottom: 150px;
    background: #fff;
    overflow-y: auto;
    z-index: 1;
    .modal-container{
        padding: 0px;
        width: 100%;       
        border: none;
        box-shadow: none;
    }
    .recruiter-modal{
        width: 100% !important;
        max-height: 100%;
    }
    .cross-btn{
        position: absolute;
        top: 4px;
        right: 0px;
        font-size: 20px;
        background: transparent;
        border: none;
        color: #ffffff;
    }
}
.thumbnail-image{
    width: 100%;
    height: 80px;
}
.about-box{
    width: 100%;
    margin-right:0px;
}
.detail-list{
    width: 100%;
}
}
@media screen and (max-width: 768px){
    .p15-768{
        padding-left:15px;
        padding-right: 15px;
    }
    .mt0-768{
        margin-top: 0px !important;
    }
}
@media screen and (max-width: 767px){
    .about-btn{
    position:relative;
    bottom:0px;
    right: 0px;
}
}
@media screen and (max-width:480px){
    .nav-tabs li a{
        font-size: 14px;
    }
}
</style>
