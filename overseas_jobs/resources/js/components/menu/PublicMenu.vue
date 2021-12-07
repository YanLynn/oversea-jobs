<template>   
        <nav class="navbar navbar-expand-lg navbar-light user-nav">        
            <div class="navbar-header d-flex align-items-center">         
             <router-link to="/jobseeker" class="no-href navbar-brand" >
                <span class="icon-new-logo-txt menu-logo"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </router-link>         
            </div>  
            <div class="mx-auto order-0 pc">            
                <img :src="'/images/common/logo-txt.png'"  class="img-fluid p-l-200"  alt="ボーダレスワーキング" />           
            </div>   
          
            <div class="nav-wrap">
                <ul class="navbar-nav ml-auto align-items-end">            
                    <li class="nav-item">               
                        <span class="icon icon-login" style="color:#fff;"></span><router-link to="/jobseeker/login" class="nav-link">求職者会員ログイン</router-link>
                    </li> 
                    <li class="nav-item">               
                        <span class="icon icon-login" style="color:#fff;"></span><router-link to="/recruiter/login" class="nav-link">企業会員ログイン</router-link>
                    </li> 
                </ul>
            </div>
            <!--for mobile-->
            <div class="spnav-wrap">
            <ul class="sp-loginbtns">
                <li class="job-primary-color"><router-link to="/jobseeker/login" class="href-link">ログイン<span class="sub-tit">求職者会員</span></router-link></li>
                <li class="ml-2 recruiter-primary-color"><router-link to="/recruiter/login" class="href-link">ログイン<span class="sub-tit">企業会員</span></router-link></li>
            </ul>
                <!-- <p class="menu" @click="showMenu"><i class="fa fa-bars"></i><br>メニュー</p>
                <transition name="slide">
                    <ul class="sp-nav" v-show="isMenu">                       
                        <li><span class="icon icon-login"></span><router-link to="/jobseeker/login" class="href-link">求職者会員ログイン</router-link></li>
                        <li><span class="icon icon-login"></span><router-link to="/recruiter/login" class="href-link">企業会員ログイン</router-link></li>
                    </ul>
                </transition> -->
            </div>
            <!--end for mobile -->                     
        </nav>  
</template>
<script>
export default {
    data(){
        return {    
            display:false,     
            isMenu: false,              
        }
    },
    methods:{
        showMenu(){
            this.isMenu = !this.isMenu;
        }
    }
}
</script>
<style lang="scss" scoped>
.icon {   
    display: flex;
    justify-content: center;
    align-items: center;   
    height: 40px;
    font-size: 25px;
}
.menu-logo{
    font-size:70px;
    color:#012463;
}
.user-nav{
    background: url(/images/common/before-login-bg.jpg) no-repeat center 0 !important;
    background-size: cover !important;
}
.p-l-200{
    padding-left: 200px;
}
//mobile
.pc {
    display: block;
}
.sp {
    display: none;
}
.sp-loginbtns{
    display:none;
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
        padding: 14px 12px;
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
            list-style:none;
            padding: 5px;
            background: #fff;
            color: #fff;
            border: 1px solid #c4bcbc;
            margin: 0 auto;
            width: 81px;
            border-radius: 5px;
            text-align: center;
            line-height:1.5;//for publicmenu
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.2);
        }
    }
     .sub-tit{
        display: block;
        text-align: center;
        color: #fff;
        font-size: 12px;
    }
    .menu-logo{
        font-size: 60px;        
    }
    .icon{
        display:inline;
    }
    .top-header {
        display: block;
        padding: 10px 5px 10px 15px;
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
            top: 90px;
            left: 0px;
            width: 100%;
            background: #fff;
            color: #2e2e2e;
            padding: 0 15px;
            list-style:none;
            border-bottom: 1px solid #c5c1c1;
            opacity: 0.95;
            z-index: 9999;
            li {
                padding: 10px;
                border-bottom: 1px solid #b5b2b2;
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
    .user-nav{
        background: radial-gradient(closest-corner at 106px, #4972c2, #4972c2, #4972c2) !important;
    }  
}
</style>