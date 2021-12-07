<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light user-nav">
        <!-- <a class="navbar-brand" href="#">{{$t('menu.recruiter')}}<br><span>ボーダレスワーキングのためのセカンドキャリア支援サイト</span></a> -->
         <div class="navbar-header d-flex align-items-center logo-sp">
        <!-- <router-link class="navbar-brand" to="/recruiter">           
           <img :src="'/images/common/login-logo.png'"  class="img-fluid"  alt="ボーダレスワーキング" />   
        </router-link>    -->
        <router-link to="/recruiter" class="no-href navbar-brand" >
                <span class="icon-new-logo-txt menu-logo"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </router-link>         
        </div>      
         <div class="mx-auto order-0 pc">            
            <img :src="'/images/common/logo-txt.png'"  class="img-fluid logo-txt"  alt="ボーダレスワーキング" />           
        </div>         
        <div class="pc">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item m-l-0">                             
                <router-link to="/recruiter/public-scout-favourite" class="nav-link">
                <span class="icon-mainmenu icon-brain"></span>
                <span class="count-no" v-if="count.fav_count > 0">{{count.fav_count}}</span>
                {{ $t('sidebar.recruiter[0]["recruiter_scouted_consideration_menu"]') }}
                </router-link>
            </li>  
            <!-- <li class="nav-item">                         
                <router-link to="/recruiter/job-applicant" class="nav-link">
                    <span class="icon-mainmenu icon-speaker"></span>
                    <span class="count-no" v-if="count.applicant_count > 0">{{count.applicant_count}}</span>
                    {{ $t('sidebar.recruiter[0]["Job_applicants"]') }}
                </router-link>
            </li>   -->
            <!-- <li class="nav-item">    
                <router-link to="/recruiter/scouted-list" class="nav-link">
                <span class="icon-mainmenu icon-note"></span>
                <span class="count-no" v-if="count.scouted_count > 0">{{count.scouted_count}}</span>                
                {{ $t('sidebar.recruiter[0]["recruiter_scouted_list_menu"]') }}</router-link>
            </li>  -->
            <li class="nav-item">   
                <router-link to="/recruiter/mypage" class="nav-link">
                 <img v-if="count.user_img && show " v-bind:src="user_image" @error="imageUrlAlt"  class="icon-mainmenu imageUrlAlt circle-shadow"/> 
                 <span v-else class="icon-mainmenu icon-profile"></span>
                {{ $t('sidebar.recruiter[0]["recruiter_mypage_menu"]') }}</router-link>
            </li>            
           <!-- <li class="nav-item">                
                <div class="nav_ico" @click="toggleDropdown"> 
                   <a class="nav-link" >
                   <span class="icon-mainmenu icon-language nav_ico job-primary-color-txt"></span>
                   {{$t('menu.language')}}</a>
                    <ul v-if="display" class="dropdown-itemList">              
                    <a class="dropdown-item text-left" style="color: #212529;">{{ $t('sidebar.recruiter[0]["language_change"]') }}</a>
                    <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('ja')">日本語</a>
                    <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('en')">English</a>                   
                </ul>   
                </div>                              
            </li>   -->
            </ul>
        </div>  
        <!--for mobile-->
        <div class="spnav-wrap sp">     
            <ul class="sp-nav">                       
                <li class="nav-item nav-item01">                             
                    <router-link to="/recruiter/public-scout-favourite" class="nav-link" style="color:#fff;">
                    <span class="icon icon-mainmenu icon-brain"></span>
                    <p class="count-no" v-if="count.fav_count > 0">{{count.fav_count}}</p>                   
                    </router-link>
                    <span class="sp-txt">スカウト検討中</span>
                </li>            
                <!-- <li class="nav-item">   
                    <router-link to="/recruiter/mypage" class="nav-link">
                        <img v-if="count.user_img && show " v-bind:src="user_image" @error="imageUrlAlt"  class="icon-mainmenu imageUrlAlt circle-shadow"/> 
                        <span v-else class="icon icon-mainmenu icon-profile"></span>
                    </router-link>
                </li>   -->
                <li> 
                    <p class="menu" @click="showSidebar"><i class="fa fa-bars"></i><br>メニュー</p>
                </li>
            </ul>
        </div>
        <!--end for mobile -->   
        <!--mobile for sidebar -->
        <div class="sp">            
            <div class="overlay" v-show="isSidebar">
                <p class="side-close" @click="hideSidebar"><i class="fa fa-times"></i></p>
            </div>
            <transition name="slideRight">
                <div class="side-panel" v-if="isSidebar">                    
                    <ul class="list-group">
                        <li v-for="recruiterInfo in recruiterInfo " :key="recruiterInfo.id"><div class="profile"><p> <!--会員番号 - --> {{ recruiterInfo.recruiter_number}}</p><h5 class="text-break"> <!-- 名前 - -->{{ recruiterInfo .recruiter_name}}</h5></div></li>
                        <span class="bd-bottom"></span>
                        <li><router-link to="/recruiter" class="recruiter-primary-color-txt">{{ $t('sidebar.recruiter[0]["recruiter_toppage"]') }}</router-link></li> 
                        <li class="mb-3"><router-link to="/recruiter/mypage" class="recruiter-primary-color-txt"> {{ $t('sidebar.recruiter[0]["recruiter_mypage"]') }}</router-link></li>           
                        <li><b class="pl-2 mb-2 d-block">{{ $t('sidebar.recruiter[0]["Job_applicants"]') }}</b></li>
                        <li class="mb-3"><router-link to="/recruiter/job-applicant" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["applicants_list"]') }}</router-link></li>  
                        <li><b class="pl-2 mb-2 d-block">{{ $t('sidebar.recruiter[0]["recruiter_jobs_posting"]') }}</b></li>
                        <li><router-link to="/recruiter/jobs/create" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_create"]') }}</router-link></li>
                        <li><router-link to="/recruiter/job-draft" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_draft"]') }}</router-link></li>
                        <li class="mb-3"><router-link to="/recruiter/jobs" class="recruiter-primary-color-txt sub-item"> {{ $t('sidebar.recruiter[0]["recruiter_jobs_list"]') }}</router-link></li>        
                        <li><b class="pl-2 mb-2 d-block">{{ $t('sidebar.recruiter[0]["scout"]') }}</b></li>
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
            </transition>
        </div>       
        <!--end mobile for sidebar-->   
    </nav>
  </div> 
</template>

<script>
export default {
    name: 'app-header',
    data(){
      return{
        langs:['日本語','English'],
        display:false,
        activeProfile:false,
        show:true,
        isMenu:false,
        isSidebar:false
      }
    },
    methods:{
        logout(){
            this.$store.commit('logout');
            this.$router.push('/recruiter/login');
        },
        toggleDropdown(){
            this.display = !this.display;
        },
        changeLang(type){
            this.$i18n.locale = type;
        },
        mouseOver(){
            this.activeProfile = !this.activeProfile;   
        },
        imageUrlAlt(e){
            this.show = false;
            
            // let img = '/uploads/recruiters/images/'+ this.count.user_img;
            // // let img = '/uploads/recruiters/images/'+ this.count.user_img + '_logo';
            // var payloadImage    = document.querySelector (".imageUrlAlt");
            // if (/\.png$/i.test (payloadImage.src) ) {
            //     return payloadImage.src = img + '.jpg';
            // } 
            // else if (/\.jpg$/i.test (payloadImage.src) ) {
            //     return payloadImage.src = img + '.jpeg';
            // }          
            // else{
            //     this.show = false;
            // }
        },
        showMenu() {
            this.isMenu = true;
        },
        hideMenu() {
            this.isMenu = false;
        },
        showSidebar() {
            this.isSidebar = true;
        },
        hideSidebar() {
            this.isSidebar = false;
        }
    },
    watch: {
        '$route' (to) {
            this.hideMenu(),
            this.hideSidebar()
        }
    },
    computed:{
        currentUser(){
            return this.$store.getters.currentUser
        },
        count(){
            return {
                fav_count:this.$store.getters.recFavCounter,
                applicant_count:this.$store.getters.recApplicantCounter,
                scouted_count:this.$store.getters.recScoutedCounter,
                user_img:this.$store.getters.getUserImage,
            }
        },
        user_image: function () {
            return  '/uploads/recruiters/images/'+ this.count.user_img;
            // return  '/uploads/recruiters/images/'+ this.count.user_img+'_logo.png';
        },
        recruiterInfo(){
            return this.$store.getters.getUserInfo
        }
    }
}
</script>
<style lang="scss" scoped>
.user-nav .nav-item:nth-child(3) .count-no {
    background: #FF0051;
}
.user-nav{
    background: url(/images/common/header-bg-recruiter.jpg) no-repeat center 0 !important;
    background-size: cover !important;
    
}
.p-l-360{
    padding-left: 360px;
}
.menu-logo{
    font-size:70px;
    color:#004842;
}
.profile-nav{
   border:0.5px dashed #fff;
   align-self: flex-end;
   padding: .5rem;
   .profile-name, .profile-num{
       width:100px
   }
   cursor:default;
    h4{
        font-size: 14px;
    }
    a{
        color: #00a195 ;
    }
    
    p{
        font-size: 13px;
    }
}

@media (max-width: 1250px) { 
    .profile-nav{
        align-self: flex-end;
    .profile-name, .profile-num{
        width:80px
    }
    ul.dropdown-itemList .dropdown-item{
        padding: 0.05rem;
    }
}
}
ul.dropdown-itemList{    
    min-width: 120px;
    background: #fff;
    border: 1px solid #999;
    border-radius: 0px;
    z-index: 9998;
}
ul.dropdown-itemList .dropdown-item{
    padding: 0.5rem;
    width: auto;
    background-color:transparent;
    white-space: pre-wrap;
    cursor: default;
}
.circle-shadow {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin: 0 auto;
    border: 2px solid #c1c1c1;
    -o-object-fit: fill;
    object-fit: cover;
    margin-bottom: 5px;
    margin-top: 0px;
}
//////responsive
//mobile
.pc {
    display: block;
}
.sp {
    display: none;
}
.nav-wrap {
    display:  flex;
    flex-grow: 1;
}
.profile{
    display: inline-block;
    width: 100%;   
    text-align: left;
    h5{
        color: #095a54 !important;  
        font-size: 16px;   
    }
    p{
        margin-bottom: 0px;
        color: #8a8a8a;
    }
}
/* .user-nav .nav-item:nth-child(2) .count-no {
    right: 13px;
    background: #FF0051;
} */
/* transistion */
.slide-enter-active,
    .slide-leave-active
    {
        transition: transform 0.2s ease;
    }
    .slide-enter,
    .slide-leave-to {
        transform: translateX(-100%);
        transition: all 150ms ease-in 0s;
    }

.slideRight-enter-active,
    .slideRight-leave-active
    {
        transform: translateX(0);
        transition: transform 0.2s ease;
    }

    .slideRight-enter,
    .slideRight-leave-to {
        transform: translateX(100%);
        transition: all 150ms ease-in 0s;
    }
/* transistion */
.side-panel .router-link-exact-active {
    background-color: #52ccc2;
}
/* Mobile view */
@media (max-width: 1023px) { 
    .pc {
        display: none;
    }
    .sp {
        display: block;
    }
    .sp-txt{
        display:block;
        font-size: 12px;
    }
    .menu-logo{
        font-size: 60px;        
    }
    .nav-item01{
        display: block !important;
        padding: 0px 8px !important;  
    } 
     .user-nav .nav-link{
        padding: 0px 0px 5px 0px !important;
    }
    .sp-nav li .icon{
        font-size: 25px;
        height: 25px;
    }
    .profile{
        padding:0px 8px;
    }
    .logo-txt {
        display: block;
        margin: 0 auto;
        font-size: 24px;
        color: #fff;
        text-align: center;
        font-weight: bold;
        padding: 18px 0;
        background: #2E2E2E; 
        span {
            color: #72aa30;
        }
    }
    .spnav-wrap {
        display: block;
        margin-left: auto;
    }
    .logo-img {
        left: 15px;
    }
    .sp-nav {
        display: flex;
        margin: 0;
        li {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 40px;
            padding: 8px;
            margin: 0;
            color: #fff;   
            text-align: center;
            .icon {
                color: #fff;
                padding-right: 0px;
                font-size: 25px;
                vertical-align: text-bottom;               
                margin-top: 0px;
            }
        }
    }
    .user-nav {
        .nav-link {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }  
        .count-no {
            top: -5px;
            right: 30px;
            width: 25px;
            height: 25px;
            line-height: 25px;
            font-size: 12px;
        } 
    }
    .menu {
        margin-bottom: 0;
        font-size: 12px;
        i {
            font-size: 30px;
        }
    }
     .overlay {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgba(0,0,0,.5);
        z-index: 9999;
    }

    .side-panel {
        position: fixed;
        top: 0;
        right: 0;
        width: 80%;
        height: 100%;
        padding: 20px 10px;
        background-color: #fff;
        border: 0;
        border-left: 5px double #e7eadb;
        box-shadow: 0 0.2rem 0.7rem rgba(0, 0, 0, 0.15);
        z-index: 99999;
        border-radius: 0;
        overflow-y: scroll;
        cursor:pointer;
        .bd-bottom{
            width:100%;
        }
    }
    .side-close {
        position: absolute;
        left: 30px;
        top: 3px;
        font-size: 30px;
        font-weight: bold;      
        color: #fff;
        z-index: 99;
    }
     .user-nav{
         background: radial-gradient(closest-corner at 106px, #00a195, #00a195, #00a195) !important;
    }
}
@media screen and (max-width:767px){
    .sp-nav li{
        font-size:12px;
    }
    .user-nav .count-no{
        right: 20px;
    }
}
@media screen and (max-width:320px){
    .menu-logo{
        font-size: 60px;        
    }
    .circle-shadow{
        width: 40px;
        height: 40px;
    }    
}
</style>