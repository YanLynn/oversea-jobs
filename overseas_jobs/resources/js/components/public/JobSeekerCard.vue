<template> 
<div :class="['outer-wrapper-bottom',[currentUser !== null] ? 'col-12 public-wrapper':'col-6']">
    <div class="new-job-block p-0">
    <div class="d-table">
        <div class="col-profile" >
            <div class="profile_img">
            <img v-if="currentUser != null && data.face_image_private_status == 1" v-bind:src="data.face_image_url" alt="job-img" class="img-fluid img-contain" />            
            <img v-if="currentUser == null && data.face_image_private_status == 1" v-bind:src="data.face_image_blur" alt="job-img" class="img-fluid img-contain"/>           
            <img v-if="data.face_image_private_status == 0" v-bind:src="'../images/no_public.png'" alt="job-img" class="img-fluid img-contain" />
                 <!-- <p class="mb-0 mt-2 profile-tit">{{data.jobseeker_nick_name}}</p> -->
            </div>
        </div>
        <div class="col-data">
            <div class="data-box">
                <div class="d-flex">
                    <p class="lbl-txt">{{ $t("scoutfavourite.occupation") }}</p>
                    <div class="lbl-data">
                        <!-- {{data}} -->
                        <!-- Test ID = {{data.id}} -->
                        <!-- <span v-for="(occ,index) in data.desired_occupation" :key="index">
                            <span v-if="index <= 2"> <span  class="txt-vertical-ellipsis-one d-flex align-items-center">{{ occ }}</span>
                            <span class="dotted" v-if="index == 2 ">...</span> </span>                                  
                        </span> -->

                        <span v-if="data.desired_occupation.length > 0">
                            <span v-for="(occ,index) in data.desired_occupation" :key="index">
                                <span v-if="index <= 2"> <span  class="txt-vertical-ellipsis-one d-flex align-items-center">{{ occ }}</span>
                                <span class="dotted" v-if="index == 2 ">...</span> </span>                                  
                            </span>
                        </span>
                        <span v-if="data.desired_occupation.length == 0 && data.desired_occupation_status != 1">-</span>
                        <span v-if="data.desired_occupation_status == 1">こだわらない </span>
                        
                    </div>
                </div>
                <div class="d-flex mt-1">
                    <p class="lbl-txt">{{  $t("scoutfavourite.desired_location")}}</p>
                    <div class="lbl-data" v-if="data.desired_location_1 != null || data.desired_location_2 != null || data.desired_location_3 != null">                   
                        <span v-if="data.desired_location_1 != null" class="txt-vertical-ellipsis-one"> {{ data.desired_location_1 }} <br /></span>
                        <span v-if="data.desired_location_2 != null" class="txt-vertical-ellipsis-one"> {{ data.desired_location_2 }} <br /> </span>
                        <span v-if="data.desired_location_3 != null" class="txt-vertical-ellipsis-one"> {{ data.desired_location_3 }} <br /></span>
                    </div>
                    <div class="lbl-data" v-else>
                        <span class="txt-vertical-ellipsis-one">-</span>
                    </div>
                </div>               
                <div class="d-flex mt-1">
                    <p class="lbl-txt">{{ $t("scoutfavourite.lang_name") }}</p>
                    <div class="lbl-data">     
                        <span v-if="data.language_level.length > 0">
                            <span v-for="(lang,index) in data.language_level" :key="index">
                                <div v-if="index <= 2"><span class="txt-vertical-ellipsis-one d-flex align-items-center"> {{ lang }}</span>
                                    <span class="dotted" v-if="index == 2 ">...</span>
                                </div>  
                            </span> 
                        </span>
                        <span v-else>
                            <span class="txt-vertical-ellipsis-one">-</span>
                        </span>
                                              
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="keyword-block col-12 buttons-block">
        <slot name="jobSeekerCardButton"></slot>
    </div>
    </div>
</div> 
  
</template>
<script>
import {mapGetters} from "vuex";
export default {
    props: ["data"],   
    computed: {
        ...mapGetters(["currentUser", "authError"]),
    },
};
</script>
<style lang="scss" scoped>
// .job-wrapper .new-job-block .data-block{
//     height: auto;
// }
// .txt-vertical-ellipsis-one{
//     display: -webkit-box;
//     -webkit-line-clamp: 1;
//     -webkit-box-orient: vertical;
//     overflow: hidden;
//     text-overflow: ellipsis;
// }
// .label-txt-lang-level{
//     min-width: 85px;
//     padding-right: 0.5rem;
//     font-size: 15px;
//     color: #666666;
//     font-weight: normal;
// }
// .lang-level-block{
//     height: 40px;
//     overflow: hidden;
// }
// .occ-block{
//     height: 62px;
//     overflow: hidden;
// }
// .loca-block{
//     height: 63px;
//     overflow: hidden;
// }
.d-table{
    display: table!important;
    table-layout: fixed;
    width: 100%;
}
.lbl-txt{
    font-size: 15px;
    color: #666666;
    font-weight: normal;
    min-width: 85px;
    width: 15%;
}
.lbl-data {
    flex: 1;
    width: 85%;
}
.public-wrapper  .new-job-block {
    height: 310px;
} 
.btn {
    padding: 8px 3px;
}
.col-profile {
   display: table-cell;
    width: 215px;
    height: 237px;
    vertical-align: middle;
    border-right: 1px solid #ddd;
    background-color: #ddd;
}
.col-data {
   display: table-cell;
    vertical-align: top;
    padding: 15px  0 0;
}
.data-box {
   padding: 0 15px 0 1rem;
}
.keyword-block {
    height: 65px;
}
.txt-vertical-ellipsis-one{
    display: -webkit-box !important;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 22.5px;
}
.profile-tit{
    font-size: 13px;
    color: #4772bf;
    letter-spacing: 2px;
    text-align:center;
    padding: 0px 15px;
}
 #recruiter-col-4{
    -ms-flex: 0 0 50% !important;
    flex: 0 0 50% !important;
    max-width: 50% !important;
    // .col-profile{
    //     width: 180px !important;
    // }
}
// .profile_img {
//     text-align: center;
//     height: 237px;
//     background-color: #ddd;
//     overflow: hidden;
//     line-height: 237px;
//     img {
//         object-fit: contain;
//         padding: 20px;
//         max-height: 250px;          
//     }
// }
.profile_img {
    width: 150px;
    height: 200px;
    margin: 0 auto;
    img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
}
.buttons-block{
    border-top: 1px solid #ddd;
    margin-top: 0px;
    padding-top: 13px!important;
}
.dotted{ 
    display: -webkit-box !important;
    width: 35px;  
    background-color: #dddddd;
    padding: 0px 10px;
    display: inline-block;
    height: 6px;
    line-height: 0.5px;    
}
@media (max-width: 1280px){    
    .w-75{
        width: 72% !important;
    }
    .w-25{
        width: 28% !important;
    }
    // #recruiter-col-4 .col-profile{
    // width: 160px !important;
    // }
}
@media screen and (max-width: 812px){
    #recruiter-col-4{        
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}
@media (max-width: 480px){ 
    .public-wrapper .new-job-block{
        height:auto;
        .btn{
            padding: 0.5rem;
            min-width: 85px;
            width: auto;
        }
    }
    .col-profile{
        display:block;
        width:100%;
        height:100%;
        padding: 15px;
        .profile_img{
            width: 100%;
            height: 105px;
        }
    }
    .col-data{
        display:block;
        padding: 15px 0px;
    }
    .lbl-txt{
        font-size: 14px;
    }
    .txt-horizontal-ellipsis{
        white-space: pre-wrap !important;
    }
    .inner-wrapper-right{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
}
</style>
