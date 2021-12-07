<template>           
    <div class="tab-panel row m-0">
        <div class="col-12">
            <ul class="tab-nav row">
                <li class="tab-item col-6" @click="activetab = 'detail'" :class="[activetab === 'detail'? 'active' : '', currentUser == null ? 'tab-detail' : 'job-tab-detail']">求人詳細</li>
                <li class="tab-item col-6" @click="activetab = 'profile'" :class="[activetab === 'profile'? 'active' : '', currentUser == null ? 'tab-profile' : 'job-tab-profile']">企業プロフィール</li>
            </ul>
            <div class="tab-content row tab-content-sp">
                <div class="tab-col col-12 p-0" v-if="activetab ==='detail'">
                    <label style="color: rgb(0, 98, 255); font-weight: bold;" v-if="details.updated">{{details.updated}}に求人詳細が修正されています</label>
                    <dl class="detail-list">
                        <dt class="detail-head">求人番号</dt>
                        <dd class="detail-data"><pre>{{details.job_number}}</pre></dd>
                        <dt class="detail-head">求人タイトル</dt>
                        <dd class="detail-data">
                            <pre v-if="details.title != '' && details.title != null">{{details.title}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <dt class="detail-head">職種</dt>
                        <dd class="detail-data">
                            <pre v-if="details.occupation_description != '' && details.occupation_description != null">{{details.occupation_description}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <dt class="detail-head">雇用形態</dt>
                        <dd class="detail-data">
                            <pre v-if="details.employment_types != '' && details.employment_types != null">{{details.employment_types}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <dt class="detail-head">仕事内容</dt>
                        <dd class="detail-data">
                            <pre v-if="details.job_description != '' && details.job_description != null">{{details.job_description}}</pre> 
                            <pre v-else> &nbsp; - </pre>       
                        </dd>
                        <dt class="detail-head">応募資格・語学力</dt>
                        <dd class="detail-data">
                            <pre v-if="details.qualification != '' && details.qualification != null">{{details.qualification}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <!-- {{job[0].country_name}} -->
                        <dt class="detail-head">給与・待遇・ビザサポート</dt>
                        <dd class="detail-data">
                            <pre v-if="details.allowance != '' && details.allowance != null">{{details.allowance}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <dt class="detail-head">勤務開始日</dt>
                        <dd class="detail-data">
                            <pre v-if="details.job_start_date != '' && details.job_start_date != null">{{details.job_start_date}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>
                        <dt class="detail-head">勤務地詳細</dt>
                        <dd class="detail-data">
                            <pre v-if="details.job_location != '' && details.job_location != null">{{details.job_location}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>                        
                        
                        <dt class="detail-head">{{ $t('jobcreate.message_jobs') }} </dt>
                        <dd class="detail-data">
                            <pre v-if="details.message != '' && details.message != null">{{details.message}}</pre>
                            <pre v-else> &nbsp; - </pre>
                        </dd>                        
                    </dl>
                </div>

                <div class="tab-col col-12 p-0" v-if="activetab ==='profile'">
                    <!-- <div  v-if="details.record_status != 2 "> -->
                        <h5 class="header">企業プロフィール</h5>
                        <div v-if="route_name == 'JobseekerScoutedDetail'" class="profile-wrap01">
                            <div class="profile-box-l">
                                <img :src="details.logo_url" class="img-fluid img-contain" alt="Logo" />
                            </div>
                            <!-- <div class="profile-box-r">
                                <p>{{details.recruiter_name}}</p>
                            </div> -->
                        </div>
                        <!-- <h5 v-if="currentUser == null || route_name == 'PublicJobDetails'" class="header">スカウト</h5> -->
                        <!-- <div v-if="currentUser == null || route_name == 'PublicJobDetails'" class="profile-wrap01">
                            <div class="profile-box-l">
                                <img :src="details.incharge_photo_url" class="img-fluid img-contain" alt="Incharge person photo" />
                            </div>
                        </div>          -->
                                                
                        <div class="row mt-3">
                        <!-- <div class="col-12"><span class="rec-num"> {{details.recruiter_number}} </span></div>                          -->
                            <div class="col-12">
                                <table class="profile-tbl">
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">会員番号</td>
                                        <td class="tbl-col-data"><span>{{details.recruiter_number}}</span></td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">企業名</td>
                                        <td class="tbl-col-data"><span>{{details.recruiter_name}}</span></td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">設立年月日</td>
                                        <td class="tbl-col-data">
                                            <span v-if="details.establishment_date">
                                                <span v-if="details.establishment_date.length > 1">
                                                    {{details.establishment_date}}
                                                <!-- {{details.establishment_date[0]}}年
                                                {{details.establishment_date[1]}}月
                                                {{details.establishment_date[2]}}日 -->
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">代表者氏名</td>
                                        <td class="tbl-col-data">{{details.representative_name}}</td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">従業員数</td>
                                        <td class="tbl-col-data">{{details.num_of_employees}}</td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td class="tbl-col-tit text-label-color">事業内容</td>
                                        <td class="tbl-col-data">{{details.business_description}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row m-t-30">
                            <h6 class="col-12 text-label-color">会社PR等</h6>
                        </div>
                        <div class="row about-row mt-0">
                            <div class="col-12">
                                <p>
                                    <pre>{{details.company_pr}}</pre>
                                </p>
                            </div>
                        </div>
                    
                        <div class="row movie-row">
                            <div class="col-12 col-sm-6">
                                <h6 class="text-label-color">関連画像</h6>
                                <div class="row ml-0" v-if="related_files.length != 0">
                                    <div class="col-6 mb-3 related-block" v-for="related in related_files" :key="related.id" @click="imagePopup(related.url)">
                                        <img @error="noImage" :src="'/uploads/recruiters/images/'+related.url" class="img-fluid img-contain img-cursor" alt="related-images" />
                                    </div>
                                    <div class="col-6 mb-3 related-block" v-for="relate in 4 - related_files.length" :key="relate.id">
                                        <img  src="/images/default.png" class="img-fluid img-contain" alt="related-images"/>                                           
                                    </div>                               
                                </div>
                                <div class="row m-0" v-else>                                    
                                    <div class="col-6 mb-3 related-block" v-for="relate in 4" :key="relate.id">
                                        <img  src="/images/default.png" class="img-fluid img-contain" alt="related-images"/>                                           
                                    </div>                               
                                </div>
                                <!-- popup images-->                                
                               <transition name="fade">
                                 <div class="img-modal-wrapper" v-if="showModal">
                                    <div class="img-modal-block">
                                        <div class="img-modal-container">
                                           <button class="cross-btn" @click="showModal = false">
                                                 <i class="fa fa-times pr-1"></i>閉じる</button>                                            
                                            <img :src="'/uploads/recruiters/images/'+this.imgUrl" class="img-popup"/>
                                        </div>
                                    </div>
                                </div>
                            </transition>                                
                            <!--end popup images-->
                            </div>
                                
                            <div class="col-12 col-sm-6">
                                <h6 class="text-label-color">関連動画</h6>
                                <div class="movie-wrapper">                                    
                                    <!-- <iframe class="movie-link" :src="'https://www.youtube.com/embed/'+details.video" controls></iframe> -->
                                    <iframe v-if="videoURL == false"  class="movie-link" :src="details.video" controls width="100" height="100"></iframe>     
                                    <img v-else src="/images/youtube-frame.jpg" class="img-fluid img-thumbnail" style="border:0px" alt="">                      
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                    <!-- <div v-else class="col-12 no-data-txt-border text-label-color text-center d-block border-0"> 
                    <svg x="0px" y="0px"
                        width="80" height="80"
                        viewBox="0 0 172 172"
                        style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g><path d="M86,166.625c-44.505,0 -80.625,-36.12 -80.625,-80.625c0,-44.505 36.12,-80.625 80.625,-80.625c44.505,0 80.625,36.12 80.625,80.625c0,44.505 -36.12,80.625 -80.625,80.625z" fill="#ffffff"></path><path d="M86,6.45c43.86,0 79.55,35.69 79.55,79.55c0,43.86 -35.69,79.55 -79.55,79.55c-43.86,0 -79.55,-35.69 -79.55,-79.55c0,-43.86 35.69,-79.55 79.55,-79.55M86,4.3c-45.15,0 -81.7,36.55 -81.7,81.7c0,45.15 36.55,81.7 81.7,81.7c45.15,0 81.7,-36.55 81.7,-81.7c0,-45.15 -36.55,-81.7 -81.7,-81.7z" fill="#cccccc"></path><path d="M107.5,77.4h-2.15v-9.675c0,-10.105 -8.6,-18.275 -19.35,-18.275c-10.75,0 -19.35,8.17 -19.35,18.275v9.675h-2.15v-9.675c0,-11.18 9.675,-20.425 21.5,-20.425c11.825,0 21.5,9.245 21.5,20.425z" fill="#cccccc"></path><path d="M52.675,123.625v-37.625c0,-4.085 3.44,-7.525 7.525,-7.525h51.6c4.085,0 7.525,3.44 7.525,7.525v37.625z" fill="#cccccc"></path><path d="M111.8,79.55c3.655,0 6.45,2.795 6.45,6.45v36.55h-64.5v-36.55c0,-3.655 2.795,-6.45 6.45,-6.45h51.6M111.8,77.4h-51.6c-4.73,0 -8.6,3.87 -8.6,8.6v38.7h68.8v-38.7c0,-4.73 -3.87,-8.6 -8.6,-8.6z" fill="#cccccc"></path><path d="M53.75,118.25h64.5v4.3h-64.5zM53.75,109.65h64.5v4.3h-64.5zM53.75,101.05h64.5v4.3h-64.5zM53.75,92.45h64.5v4.3h-64.5zM54.18,83.85c-0.215,0.645 -0.43,1.29 -0.43,2.15v2.15h64.5v-2.15c0,-0.86 -0.215,-1.505 -0.43,-2.15z" fill="#cccccc"></path></g></g></svg>
                        <p class="mt-4 primary">このプロフィールは非公開です</p> 
                    </div> -->
                </div>
            </div>
        </div>
    </div>    
</template>
<script>
import { mapGetters } from "vuex";
    export default {
        name: 'DetailsInfo',
        props: {
            jobId: Number,
            default: 0,
        },		
        data() {
            return {
                details: {
                    video:''
                },
                related_files: [],
                activetab: "detail",   
                route_name:'',
                imgPath:'/uploads/recruiters/images/',    		
                showModal : false,
                imgUrl: '',
            };
        },  
        watch: {                      
            jobId: function(newVal) {
                this.route_name = this.$route.name;
                if(this.$route.params.activeTag != undefined){
                        this.activetab = this.$route.params.activeTag
                }
                this.$api.get(`/jobs/${newVal}`).then((response) => {    
                                    
                    this.details = response.data.data.details;
                    this.videoURL = this.details.video.includes('youtube-frame.jpg');
                    this.related_files = response.data.data.related_files;
                    console.log('aa',response)
                });              
            },
        },
         computed: {             
            ...mapGetters(["currentUser"]),
            authError() {
                return this.$store.getters.authError;
            },            
        }, 
        // created(){
        //     window.addEventListener('resize', this.handleResize);
        //     this.handleResize();
        // },
        methods:{
            hasRelatedImage(index){
                return this.related_files && this.related_files[index]
            },
            noImage(e){
                if(e.target.src){
                    e.target.src = '/images/default.png'
                }
            },
           	imagePopup(url) {
                if(url.includes('images/default.png') || url.includes('images/no_public.png')){
                    return false;
                }
                else{
                    this.showModal =  true;
                    this.imgUrl = url;
                }
           
            },
            //  handleResize(){               
            //     this.clientWidth = window.innerWidth;
            //     console.log('handleresize-height',this.clientWidth); 
            //     let intViewportWidth = this.clientWidth;
            //     let result = intViewportWidth / 4;
            //     var elmnt = document.getElementsByClassName("img-modal-container");                
            //  }
        },  
    };
</script>
<style lang="scss">
@import "./resources/sass/variables.scss";
@media screen and (max-width:768px){
    .ml-0{
        margin-left: 0px;
    }
    .tab-content {  
        border-radius: 0px 0px 5px 5px !important;
    }
    .tab-content-sp {  
        padding:15px !important;
    }
}
    .tbl-row{
        display: flex;        
    }
    .tbl-col-tit{
        width: 10%;
        min-width: 100px;
        font-size: 15px;
        vertical-align: top;
    }
    .tbl-col-data{
        width: 90%;
        font-size: 14px;
    }
    .pre {
        padding: 0;
        margin: 0;
        white-space: pre-line;
        min-height: 40px;
    }
    .applied-row {
        display: flex;
        margin-bottom: 20px;
        padding: 25px;
        background: #d3e2ee;
    }
    .appplied-col01 {
        width: 48.5%;        
        margin-right: 1.5%;
        padding: 20px;
        background: #fff;
    }   
    .appplied-tit {
        font-size: 1.15rem;
    }
    .btn-wrap {
        display: flex;
    }   
    .apply-wrap {
        width: 30%;
        margin-left: auto;
    }
    .tab-panel {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-top: 1px solid #F4F2FA;
        overflow: hidden;
    }
    .tab-nav .tab-item {
        padding: 10px 0 15px 0;
        list-style: none;
        color: #fff;
        text-align: center;
        cursor: pointer;
        top: 0px;
    }
    .tab-nav .tab-detail {
        background: url("/images/jobseeker/tab_item_bg01.png") no-repeat top left;
        background-size: 100% 100%;
    }
    .tab-nav .tab-profile {
        color: #fff;
        background: url("/images/jobseeker/tab_item_bg02.png") no-repeat top center;
        background-size: 100% 100%;
    }
    .tab-nav .job-tab-detail {
        background: url("/images/jobseeker/job_tab_item_bg01.png") no-repeat top left;
        background-size: 100% 100%;
    }
    .tab-nav .job-tab-profile {
        color: #fff;
        background: url("/images/jobseeker/job_tab_item_bg02.png") no-repeat top center;
        background-size: 100% 100%;
    }    
    .tab-nav .active {
        color: #6085a3;
        background: #fff;
    }
    .tab-nav .active-profile {
        color: #6085A3;
        background: #fff;
    }
    .tab-content {
        padding: 20px 30px; 
        // border: 2px solid #c1c1c1;
        border-radius: 0px 0px 5px 5px !important;
    }
    .detail-list{
        display: flex;
        flex-wrap: wrap;
    }
    /*
    .detail-head,
    .detail-data {
        padding: 10px 0 50px 0;
        border-top: 1px solid #ddd;
        line-height: 2;
    }

    .detail-head {
        float: left;
        width: 34%;
        margin-right: 3%;
        font-weight: normal;
    }
    .detail-data {
        float: left;
        width: 63%;
        background-color: #fff;
    }*/
    .detail-group-topline {
        border-top: 1px solid color(primary-recruiter) !important;
        margin-top: 30px;
    }
    .detail-head-group {
        float: left;
        width: 34%;
        margin-right: 3%;
        font-weight: normal;
    }
    .detail-data-group {
        float: left;
        width: 63%;
        background-color: #fff;
    }
    .detail-head-group,
    .detail-data-group {
        padding: 10px 0 10px 0;
        border-top: 1px solid #ddd;
    }
    .profile-wrap01 {
        display: flex;
        margin: 20px 0;
        align-items: flex-start;
    }
    .profile-box-l {      
        display: flex;
        justify-content: center;
        align-items: center;
        width: 180px;
        height: 240px;
        padding: 10px;
        border: 1px solid #ddd;
        .img-contain{
            width: 100%;
            height: 100%;       
            object-fit: contain;      
            object-position: center;
        }
    }
    .rec-num{
        color: #8a8a8a;
        font-size: 16px;
        font-weight: 700;
        display: inline-block;
        text-align: center;
    }
    // .profile-box-r {
    //     display: flex;
    //     justify-content: center;
    //     align-items: center;
    //     width: 73%;
    //     margin-left: 1%;
    //     padding: 10px;
    //     border: 1px solid #ddd;
    //     p{
    //         margin: 0px;
    //         display: -webkit-box;
    //         -webkit-line-clamp: 6;
    //         -webkit-box-orient: vertical;
    //         overflow: hidden;
    //         text-overflow: ellipsis;
    //     }
    // }
    .profile-tbl {
        line-height: 2;
    }
    .about-row {
        padding: 10px;
        line-height: 2;
        border: 1px solid #ddd;
        margin: 30px 0;
        min-height: 100px;
        max-height: 300px;
        overflow-y: auto;
        
    }
    .movie-row {
        padding-bottom: 50px;
    }
    .movie-col {
        width: 400px;
        height: 300px;
    }
    /*.movie-link {
        width: 100%;
        height: 100%;
        border: none;
    }*/
    /*--form----*/
    .m-b-10 {
        margin-bottom: 20px;
    }
    .title{
        font-weight: bold;
        font-size: 25px;
        margin-left: 70px;
    }   
      /*------*/
    .movie-link{
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        border:none;
    }
    .photo-name {
        position: absolute;
        bottom: 0px;
        left: 0px;
        width: 100%;
        padding: 5px 10px;
        font-size: 16px;
        color: #333333;
        text-align: center;
        background-color: #ffffffad;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .header{
        margin-bottom: 20px !important;
    }
//popup view related images
.img-modal-wrapper {
     position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s ease;
    z-index: 10001;  
}
.img-modal-wrapper .img-modal-block {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 950px;
    height: 80vh;
    transition: all 0.3s ease;
    text-align: center;
}

.img-modal-wrapper .img-modal-container {
   height: 100%;  
}
.img-modal-wrapper .cross-btn {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 16px;
    background: #999;
    padding: 4px 10px;
    border-radius: 50px;
    color:#fff;
    border: 1px solid;
}
.img-cursor{
    cursor: pointer;
}
.img-popup{
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
