<template>
    <section class="top-header container-fluid">
        <div class="d-flex justify-content-center">
            <ul class="nav-wrap">
                <li class="border-0 shadow-none"><router-link to="/jobseeker-flow" class="href-link" target="_blank">求人応募の流れ</router-link></li>
                <li class="border-0 shadow-none"><router-link to="/recruiter-flow" class="href-link" target="_blank">求人掲載/人材スカウトの流れ</router-link></li>
                <li style="background-color:#fff; color:#333;"> 
                <svg version="1.1" id="_x32_" x="0px" y="0px" viewBox="0 0 512 512" style="width: 23px; height: 23px; opacity: 1;" xml:space="preserve">
                <g>
                    <path class="st0" d="M450.498,155.424h-22.156v137.153h22.156c15.036,0,27.214-12.185,27.214-27.213v-82.719
                        C477.711,167.61,465.533,155.424,450.498,155.424z" style="fill: rgb(96 133 163);"></path>
                    <path class="st0" d="M438.841,0h-29.7H73.158c-21.47,0-38.87,17.407-38.87,38.87v434.253c0,21.471,17.399,38.877,38.87,38.877
                        h297.106c21.471,0,38.877-17.406,38.877-38.877V137.146h41.356c15.036,0,27.214-12.179,27.214-27.207V38.87
                        C477.711,17.407,460.305,0,438.841,0z M161.891,207.088c0-35.871,29.071-64.948,64.948-64.948
                        c35.863,0,64.934,29.078,64.934,64.948v15.556c0,35.863-29.071,64.934-64.934,64.934c-35.877,0-64.948-29.071-64.948-64.934
                        V207.088z M306.245,389.825c-21.007,0-79.405,0-79.405,0s-58.412,0-79.419,0c-21.006,0-30.528-30.549-18.378-52.134
                        c9.15-16.278,24.142-34.714,48.563-45.199c13.964,9.764,30.928,15.514,49.234,15.514c18.285,0,35.256-5.75,49.22-15.514
                        c24.414,10.485,39.414,28.92,48.57,45.199C336.773,359.276,327.251,389.825,306.245,389.825z" style="fill: rgb(96 133 163);"></path>
                </g>
                </svg>
                <router-link to="/jobseeker/register" class="href-link">求職者会員新規登録</router-link></li>
                <li style="background-color:#fff; color:#333;">
                 <svg version="1.1" id="_x32_" x="0px" y="0px" viewBox="0 0 512 512" style="width: 23px; height: 23px; opacity: 1;" xml:space="preserve">
                <g>
                    <path class="st0" d="M450.498,155.424h-22.156v137.153h22.156c15.036,0,27.214-12.185,27.214-27.213v-82.719
                        C477.711,167.61,465.533,155.424,450.498,155.424z" style="fill: rgb(0 161 149)"></path>
                    <path class="st0" d="M438.841,0h-29.7H73.158c-21.47,0-38.87,17.407-38.87,38.87v434.253c0,21.471,17.399,38.877,38.87,38.877
                        h297.106c21.471,0,38.877-17.406,38.877-38.877V137.146h41.356c15.036,0,27.214-12.179,27.214-27.207V38.87
                        C477.711,17.407,460.305,0,438.841,0z M161.891,207.088c0-35.871,29.071-64.948,64.948-64.948
                        c35.863,0,64.934,29.078,64.934,64.948v15.556c0,35.863-29.071,64.934-64.934,64.934c-35.877,0-64.948-29.071-64.948-64.934
                        V207.088z M306.245,389.825c-21.007,0-79.405,0-79.405,0s-58.412,0-79.419,0c-21.006,0-30.528-30.549-18.378-52.134
                        c9.15-16.278,24.142-34.714,48.563-45.199c13.964,9.764,30.928,15.514,49.234,15.514c18.285,0,35.256-5.75,49.22-15.514
                        c24.414,10.485,39.414,28.92,48.57,45.199C336.773,359.276,327.251,389.825,306.245,389.825z" style="fill: rgb(0 161 149)"></path>
                </g>
                </svg>  
                <router-link to="/recruiter/register" class="href-link">企業会員新規登録</router-link></li>
                <li class="job-primary-color"><span class="icon icon-login" style="vertical-align: middle"></span><router-link to="/jobseeker/login" class="href-link">求職者会員ログイン</router-link></li>
                <li class="recruiter-primary-color"><span class="icon icon-login" style="vertical-align: middle"></span><router-link to="/recruiter/login" class="href-link">企業会員ログイン</router-link></li>
                <!-- <li class="language-icons">                
                    <div class="nav_ico" @click="toggleDropdown">                    
                    <span class="icon icon-language nav_ico job-primary-color-txt"></span>
                    <a class="nav-link" >{{$t('menu.language')}}</a>
                        <ul v-if="display" class="dropdown-itemList">              
                        <a class="dropdown-item text-left" style="color: #212529;">{{ $t('sidebar.recruiter[0]["language_change"]') }}</a>
                        <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('ja')">日本語</a>
                        <a class="dropdown-item text-left" style="color: #212529;" @click="changeLang('en')">English</a>                   
                    </ul>   
                    </div>                              
                </li>  -->
            </ul>
            <!--for mobile-->
           
            <ul class="sp-loginbtns">                
                <li style="color:#2e2e2e;"><router-link to="/jobseeker/register" class="href-link">新規登録<span class="sub-tit job-primary-color-txt">求職者会員</span></router-link></li>
                <li class="ml-1" style="color:#2e2e2e;"><router-link to="/recruiter/register" class="href-link">新規登録<span class="sub-tit recruiter-primary-color-txt">企業会員</span></router-link></li>
                <li class="ml-1 job-primary-color"><router-link to="/jobseeker/login" class="href-link">ログイン<span class="sub-tit">求職者会員</span></router-link></li>
                <li class="ml-1 mr-1 recruiter-primary-color"><router-link to="/recruiter/login" class="href-link">ログイン<span class="sub-tit">企業会員</span></router-link></li> 
            </ul> 
           <div class="spnav-wrap">               
                <p class="menu" @click="showMenu"><i class="fa fa-bars"></i><br>メニュー</p>
                <transition name="slide">
                    <ul class="sp-nav" v-show="isMenu">
                        <li><router-link to="/jobseeker-flow" class="href-link" target="_blank">求人応募の流れ</router-link></li>
                        <li><router-link to="/recruiter-flow" class="href-link" target="_blank">求人掲載/人材スカウトの流れ</router-link></li>
                        <li>
                        <svg version="1.1" id="_x32_" x="0px" y="0px" viewBox="0 0 512 512" style="width: 23px; height: 23px; opacity: 1;margin-right:8px;" xml:space="preserve"><g>
                            <path class="st0" d="M450.498,155.424h-22.156v137.153h22.156c15.036,0,27.214-12.185,27.214-27.213v-82.719
                                C477.711,167.61,465.533,155.424,450.498,155.424z" style="fill: rgb(96 133 163);"></path>
                            <path class="st0" d="M438.841,0h-29.7H73.158c-21.47,0-38.87,17.407-38.87,38.87v434.253c0,21.471,17.399,38.877,38.87,38.877
                                h297.106c21.471,0,38.877-17.406,38.877-38.877V137.146h41.356c15.036,0,27.214-12.179,27.214-27.207V38.87
                                C477.711,17.407,460.305,0,438.841,0z M161.891,207.088c0-35.871,29.071-64.948,64.948-64.948
                                c35.863,0,64.934,29.078,64.934,64.948v15.556c0,35.863-29.071,64.934-64.934,64.934c-35.877,0-64.948-29.071-64.948-64.934
                                V207.088z M306.245,389.825c-21.007,0-79.405,0-79.405,0s-58.412,0-79.419,0c-21.006,0-30.528-30.549-18.378-52.134
                                c9.15-16.278,24.142-34.714,48.563-45.199c13.964,9.764,30.928,15.514,49.234,15.514c18.285,0,35.256-5.75,49.22-15.514
                                c24.414,10.485,39.414,28.92,48.57,45.199C336.773,359.276,327.251,389.825,306.245,389.825z" style="fill:rgb(96 133 163);"></path>
                        </g></svg><router-link to="/jobseeker/register" class="href-link">求職者会員新規登録</router-link></li>
                        <li>
                        <svg version="1.1" id="_x32_" x="0px" y="0px" viewBox="0 0 512 512" style="width: 23px; height: 23px; opacity: 1;margin-right:8px;" xml:space="preserve"><g>
                            <path class="st0" d="M450.498,155.424h-22.156v137.153h22.156c15.036,0,27.214-12.185,27.214-27.213v-82.719
                                C477.711,167.61,465.533,155.424,450.498,155.424z" style="fill: rgb(0 161 149);"></path>
                            <path class="st0" d="M438.841,0h-29.7H73.158c-21.47,0-38.87,17.407-38.87,38.87v434.253c0,21.471,17.399,38.877,38.87,38.877
                                h297.106c21.471,0,38.877-17.406,38.877-38.877V137.146h41.356c15.036,0,27.214-12.179,27.214-27.207V38.87
                                C477.711,17.407,460.305,0,438.841,0z M161.891,207.088c0-35.871,29.071-64.948,64.948-64.948
                                c35.863,0,64.934,29.078,64.934,64.948v15.556c0,35.863-29.071,64.934-64.934,64.934c-35.877,0-64.948-29.071-64.948-64.934
                                V207.088z M306.245,389.825c-21.007,0-79.405,0-79.405,0s-58.412,0-79.419,0c-21.006,0-30.528-30.549-18.378-52.134
                                c9.15-16.278,24.142-34.714,48.563-45.199c13.964,9.764,30.928,15.514,49.234,15.514c18.285,0,35.256-5.75,49.22-15.514
                                c24.414,10.485,39.414,28.92,48.57,45.199C336.773,359.276,327.251,389.825,306.245,389.825z" style="fill:rgb(0 161 149);"></path>
                        </g></svg><router-link to="/recruiter/register" class="href-link">企業会員新規登録</router-link></li>
                        <li><span class="icon icon-login job-primary-color-txt"></span><router-link to="/jobseeker/login" class="href-link">求職者会員ログイン</router-link></li>
                        <li><span class="icon icon-login recruiter-primary-color-txt"></span><router-link to="/recruiter/login" class="href-link">企業会員ログイン</router-link></li>
                    </ul>
                </transition>
            </div>
            <!--end for mobile -->
        </div>
    </section>   
</template>

<script>
export default {
    name: 'app-header',
    data(){
        return {
            isLoggedin:null,      
            langs:['日本語','English'],              
            isMenu: false,              
        }
    },
    methods:{
        logout(){
            this.$store.commit('logout');
            this.$router.push('/login');
        }, 
        changeLang(type){
            this.$i18n.locale = type;
        },
        showMenu() {
            this.isMenu = !this.isMenu;
        }, 
    },
    mounted() {
       if(this.$store.getters.currentUser)
       {
        this.isLoggedin = this.$store.getters.currentUser
        
        if(this.isLoggedin.role_id === 2){
            this.routeUrl = 'recruiter'
        }else if(this.isLoggedin.role_id === 3){
            this.routeUrl = 'jobseeker'
        }
       }
       else{
           this.routeUrl = '/'

       }
        
    },
    computed:{
        currentUser(){
            return this.$store.getters.currentUser
        }
    }
}
</script>

<style lang="scss" scoped>
.language-icons{
   position: absolute;
    right: 13px;
    width: 85px;
    text-align: center;
    border: 1px solid #fff;
    padding: 2px 0px;
    top: 20px;
}
//mobile
.pc {
    display: block;
}
.sp {
    display: none;
}
.icon-sp{
    font-size: 23px;
    padding-right: 8px;
    vertical-align: bottom;
}
.nav-wrap {
    display:  flex;
    justify-content: center;
    height: 85px;
    align-items: center;
}
.logo{
    position: absolute;
    top: 9%;
    left: 1%;
}
.spnav-wrap {
    display: none;
}
.sp-loginbtns{
    display:none;
}
.slide-enter-active {
   transition: max-height 0.25s, opacity 0.25s, padding 0.25s, transform 0.5s;
}

.slide-leave-active {
   transition: max-height 0.25s, opacity 0.25s, padding 0.25s, transform 0.5s;
}

.slide-enter-to, .slide-leave {
  opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
    max-height: 1200px;
    padding-bottom: 20px;
}

.slide-enter, .slide-leave-to {
  overflow: hidden;
  opacity: 0;
  pointer-events: none;
  max-height: 0;
  transform: translateY(-20px);
        
}
/* Mobile view */
@media (max-width:1024px){
    .top-header ul li{
        padding: 10px;
        margin: 0 5px;
        font-size: 12px;
    }
}
@media (max-width: 1023px) { 
    .pc {
        display: none;
    }
    .sp {
        display: block;
    }
    .sp-loginbtns{
        display:block;
    }
    ul.sp-loginbtns{
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 0px;
        margin: 0px;
        li{
            padding: 5px 2px;
            background: #fff;
            color: #fff;
            font-size:10px;
            border: 1px solid #c4bcbc;
            margin: 0 auto;
            width: 68px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.2);
        }
    }
    .sub-tit{
        display: block;
        text-align: center;
        // color: #fff;
        font-size: 10px;
    }
    .top-header {
        display: block;
        padding: 10px 15px 10px 15px;
        align-items: center;
    }  
    .logo {
        position: unset;
        margin-bottom: 0;
        .logo_img {
            width: 80px;
        }
    }  
    .nav-wrap{
        display: none;
    }
    .spnav-wrap {
        display: block;
        margin-left: auto;
        padding-right: 0px;  
        .menu {
            margin-bottom: 0;
            color: #fff;
            text-align: center;
            font-size: 10px;
            i {
                font-size: 30px;
            }
            
        }
        .sp-nav {
            position: absolute;
            top: 83px;
            left: -10px;
            width: 100%;
            background: #fff;
            color: #2e2e2e;
            padding: 0 15px;
            opacity: 0.95;
            z-index: 9999;
            li {
                padding: 10px;
                border-bottom: 1px solid #b5b2b2;
                border-radius: 0px;
                box-shadow:none;
                &:last-child {
                    border-bottom: 0;
                }
                .icon {
                    font-size: 23px;
                    padding-right: 8px;
                    vertical-align: bottom;
                }
            }
        }
    }    
}
@media (max-width: 320px){
    .top-header{
        padding: 10px 15px 10px 15px;
    }
    ul.sp-loginbtns li{
        width: 60px;
        padding: 5px 2px;
    }
    .spnav-wrap{
        padding-right: 0px;
        .menu{
            font-size: 7px;
        }
    }

}
</style>