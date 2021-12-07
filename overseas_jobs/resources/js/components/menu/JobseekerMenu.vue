<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light user-nav">
        <!-- <a class="navbar-brand" href="#">{{$t('menu.recruiter')}}<br><span>ボーダレスワーキングのためのセカンドキャリア支援サイト</span></a> -->
        <div class="navbar-header d-flex align-items-center logo-sp">           
        <router-link to="/jobseeker" class="no-href navbar-brand">
            <span class="icon-new-logo-txt menu-logo"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </router-link>        
        </div>      
        <div class="mx-auto order-0 pc">            
            <img :src="'/images/common/logo-txt.png'"  class="img-fluid logo-txt"  alt="ボーダレスワーキング" />           
        </div>  
        <div class="pc">
            <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item">
                <router-link to="/jobseeker/scouted-list" class="nav-link">
                <span class="icon-mainmenu icon-mail job-primary-color-txt"> <span class="badge badge-light" style="background-color: #004CFF;" v-if="count.scouted_count > 0">{{count.scouted_count}}</span> </span>
                 {{$t('sidebar.jobseeker[0]["scoutmail"]') }}  </router-link>
            </li>   -->
            <li class="nav-item m-l-0">
                <!-- <img :src="'/images/jobseeker/book.png'" class="nav_ico" alt="お気に入り求人" /> -->               
                <!-- <span class="count-no">5</span> -->
                <router-link to="/jobseeker/public-favourite-job" class="nav-link">
                <span class="icon-mainmenu icon-book"><span class="count-no" v-if="count.fav_count > 0">{{count.fav_count}}</span></span>
                {{ $t('sidebar.jobseeker[0]["jobConsideration"]') }}
                </router-link>
            </li>             
            <li class="nav-item">
                <!-- <img :src="'/images/jobseeker/profile.png'" class="nav_ico" alt="管理画面" /> -->               
                <router-link to="/jobseeker/mypage" class="nav-link">
                 <img  v-if="count.user_img && show" @error="imageUrlAlt" v-bind:src="'/uploads/jobseekers/images/' + count.user_img" class="icon-mainmenu imageUrlAlt circle-shadow"/> 
                 <span v-else class="icon-mainmenu icon-profile"></span>
                {{ $t('sidebar.jobseeker[0]["mypage_menu"]') }}
                </router-link>
            </li>             
             <!-- <li class="nav-item">                
                <div class="nav_ico" @click="toggleDropdown"> 
                    <a class="nav-link" >
                        <span class="icon-mainmenu icon-language nav_ico job-primary-color-txt"></span>
                        {{$t('menu.language')}}
                    </a>
                    <ul v-if="display" class="dropdown-itemList">              
                    <a class="dropdown-item text-left" style="color: #212529;">{{ $t('sidebar.jobseeker[0]["language_change"]') }}</a>
                    <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('ja')">日本語</a>
                    <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('en')">English</a>                   
                    </ul>   
                </div>                              
            </li>      -->
            </ul>
        </div> 
         <!--for mobile-->
        <div class="spnav-wrap sp">           
            <!--sidebar mobile--->
            <ul class="sp-nav">
                <li class="nav-item  nav-item01">
                    <router-link to="/jobseeker/public-favourite-job" class="nav-link" style="color:#fff;">
                        <span class="icon icon-mainmenu icon-book"><p class="count-no" v-if="count.fav_count > 0">{{count.fav_count}}</p></span>                      
                    </router-link>
                    <span class="sp-txt">お気に入り</span>
                </li>  
                <!-- <li class="nav-item">
                    <router-link to="/jobseeker/mypage" class="nav-link">
                        <img  v-if="count.user_img && show" @error="imageUrlAlt" v-bind:src="'/uploads/jobseekers/images/' + count.user_img" class="icon-mainmenu imageUrlAlt circle-shadow"/> 
                        <span v-else class="icon icon-mainmenu icon-profile"></span>
                       
                    </router-link>
                </li>  -->
                <li>
                    <p class="menu" @click="showSidebar"><i class="fa fa-bars"></i><br>メニュー</p>
                </li>
            </ul>
            <div class="sp">
                <div class="overlay" v-show="isSidebar">
                    <p class="side-close"  @click="hideSidebar"><i class="fa fa-times"></i></p>
                </div>                
                <transition name="slideRight">
                <div class="side-panel" v-if="isSidebar">                   
                    <ul class="list-group">
                        <li v-for="jobseekerInfo in jobseekerInfo" :key="jobseekerInfo.id">
                        <div class="profile"><p> <!--会員番号 - --> {{jobseekerInfo.jobseeker_number}}</p>
                        <h5 class="text-break"> <!--名前 - --> {{jobseekerInfo.jobseeker_name}}</h5></div></li>
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
            </transition>
        </div>
            <!--end sidebar mobile-->
        </div>
        <!--end for mobile -->  
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
        show:true,
        isMenu:false,
        isSidebar:false

      }
    },
    mounted(){
        var firstName = $('#firstName').text();
        var lastName = $('#lastName').text();
        var intials = $('#firstName').text().charAt(0) + $('#lastName').text().charAt(0);
        var profileImage = $('#profileImage').text(intials);
    }, 	
    methods:{
        logout(){
            this.$store.commit('logout');
            this.$router.push('/jobseeker/login');
        },
        toggleDropdown(){
            this.display = !this.display;
        },
        changeLang(type) {
            this.$i18n.locale = type;
        },
        imageUrlAlt(e){
            this.show = false;    
           // var payloadImage    = document.querySelector (".imageUrlAlt");        
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
        count(){
            return {
                fav_count:this.$store.getters.jobseekerfavCounter,
                scouted_count:this.$store.getters.jobseekerScoutedCounter,
                user_img:this.$store.getters.getUserImage,
            }
        },
        currentUser(){
            return this.$store.getters.currentUser
        },
        jobseekerInfo(){
            return this.$store.getters.getUserInfo
        },
        user_image() {
            return  '/uploads/jobseekers/images/'+ this.count.user_img;
        }
    },

}
</script>
<style lang="scss" scoped>
.count-no{  
    position: absolute;    
    background-color: #FF0051;
    width: 30px;
    height: 30px;
    border-radius: 50%;   
    text-align: center;
    color: #fff;
    vertical-align: middle;
    line-height: 30px;
    font-size: 14px;
}
.user-nav{
    background: url(/images/common/header-bg-job.jpg) no-repeat center 0 !important;
    background-size: cover !important;
    
}
.p-l-100{
    padding-left: 100px;
}
.menu-logo{
    font-size:70px;
    color:#073d65;
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
        color: #6085a3;
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
#profileImage {
 width: 40px;
height: 40px;
border-radius: 50%;
background: #487090;
font-size: 14px;
color: #fff;
text-align: center;
line-height: 40px;
margin: 15px 0;
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
.profile{
    display: inline-block;
    width: 100%;   
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
    background-color: #8DB7CC;
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
     .nav-item01{
        display: block !important;
        padding: 0px 8px !important;  
    } 
     .user-nav .nav-link{
        padding: 0px 0px 5px 0px !important;
    }
    .sp-nav li .icon{
        font-size: 25px;
        height: 23px;
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
            font-size: 11px;
        }  
        .count-no {            
            width: 25px;
            height: 25px;
            line-height: 25px;
            font-size: 12px;
            top: -10px;
            right: 18px;
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
        background: radial-gradient(closest-corner at 106px, #6085a3, #6085a3, #6085a3) !important
    }
}
@media screen and (max-width:767px){   
    .sp-nav li{
        font-size:12px;
    }
    .user-nav .count-no{
        right: 10px;
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