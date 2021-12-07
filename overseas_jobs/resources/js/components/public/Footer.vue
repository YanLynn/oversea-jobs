<template>
<div>
<div :class="footerClass" v-if="currentUser == null" id="public-footer">
        <div class="container">
            <div class="row col-12 pt-4 pb-lg-2 pb-sm-4 justify-content-between ftr-row m-0">
                <div class="ftr-col">
                <router-link to="/" class="sp-text-center">
                   <img :src="'/images/common/footer-logo.png'"  class="img-fluid"  alt="ボーダレスワーキング" />   
                </router-link>
                     <!-- <a class="navbar-brand" href="#"><span class="icon icon-slogo" style="color: #fff;"></span></a>                     -->
                     <div class="ftr-company-list">
                     <h3 class="ftr-tit mt-4">運営会社</h3>
                    <ul class="footer-list pt-lg-3">
                        <li>株式会社 Trust Growth</li>
                        <li>〒163-0713 東京都新宿区西新宿2-7-1小田急第一生命ビル13階</li>
                        <li>TEL：03-6911-3010</li>
                    </ul>
                    </div>
                </div>
                <div class="ftr-col ftr-contact-col">
                    <h3 class="ftr-tit sp-ftr-tit" @click="showToggle(1)">CONTACT US
                        <span class="sp-icon" :class="{'rotate': (current === 1) && (status == true)}"><i class="fa fa-angle-down"></i></span>
                    </h3>
                    <ul class="footer-list pt-lg-3 sp-ftr-list" id="1" :class="{'ftr-expand': (current === 1) && (status == true)}">
                        <li><router-link to="/query" style="color:#fff">{{ $t('footer.contact')}}</router-link></li>
                    </ul>
                </div>
                <div class="ftr-col pl-0 pr-0">
                    <h3 class="ftr-tit sp-ftr-tit" @click="showToggle(2)">INFORMATION
                    <span class="sp-icon" :class="{'rotate': (current === 2) && (status == true)}"><i class="fa fa-angle-down"></i></span>
                    </h3>
                    <ul class="footer-list pt-lg-3 sp-ftr-list" id="2" :class="{'ftr-expand': (current === 2) && (status == true)}">
                        <li><router-link to="/about" class="href-link" target="_blank">ボーダレスワーキングについて</router-link></li>
                        <li><router-link to="/privacy-policy" class="href-link" target="_blank">プライバシーポリシー</router-link></li>
                        <li><router-link to="/terms" class="href-link" target="_blank">利用規約</router-link></li>
                        <li><router-link to="/specified-commercial-transactions" class="href-link" target="_blank">特定商取引法に基づく表記</router-link></li>
                    </ul>
                </div>
                <div class="ftr-col  pl-0 pr-0">
                    <h3 class="ftr-tit sp-ftr-tit" @click="showToggle(3)">MEMBSERSHIP
                    <span class="sp-icon" :class="{'rotate': (current === 3) && (status == true)}"><i class="fa fa-angle-down"></i></span>
                    </h3>
                    <ul class="footer-list pt-lg-3 sp-ftr-list" id="3" :class="{'ftr-expand': (current === 3) && (status == true)}">
                        <li><router-link to="/jobseeker/register" class="href-link">求職者会員新規登録</router-link></li>
                        <li><router-link to="/jobseeker-flow" class="href-link" target="_blank">登録とスカウトを受けるまでの流れ</router-link></li>
                        <hr class="ftr-hr">
                        <li><router-link to="/recruiter/register" class="href-link">企業会員新規登録</router-link></li>
                        <li><router-link to="/recruiter-flow" class="href-link" target="_blank">登録とスカウトを行うまでの流れ</router-link></li>
                    </ul>
                </div>
                <div class="ftr-col">
                    <h3 class="ftr-tit sp-ftr-tit" @click="showToggle(4)">LOGIN
                    <span class="sp-icon" :class="{'rotate': (current === 4) && (status == true)}"><i class="fa fa-angle-down"></i></span>
                    </h3>
                    <ul class="footer-list pt-lg-3 sp-ftr-list" id="4" :class="{'ftr-expand': (current === 4) && (status == true)}">
                        <li><router-link to="/jobseeker/login" class="href-link">求職者会員ログイン</router-link></li>
                        <li><router-link to="/recruiter/login" class="href-link">企業会員ログイン</router-link></li>
                    </ul>
                </div>
                <div class="ftr-col ftr-col-sns">
                    <h3 class="ftr-tit sp-ftr-tit">Sns</h3>
                    <ul class="footer-list pt-lg-3">
                        <li><a href=""><img :src="'/images/common/facebook-old.svg'"  alt="Facebook" /></a></li>
                        <li><a href="https://www.instagram.com/borderless_w_/" target="_blank"><img :src="'/images/common/instagram.svg'" alt="Instagram" /></a></li>
                        <li><a href="https://twitter.com/borderless_w_" target="_blank"><img :src="'/images/common/twitter-squared.png'" alt="Twitter" /></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <p class="copy-right-txt">Copyright@Trust Growth Co,Ltd All Rights Reserved.</p>
                </div>
            </div>
        </div>        
    </div> 
    <div :class="footerClass"  v-if="currentUser != null" id="logged-footer">
        <div class="container">                             
                    <ul class="footer-top-list">
                        <li>  
                        <router-link to="/recruiter/login"  class="navbar-brand" v-if="currentUser.role_id== 2"> 
                            <img :src="'/images/common/footer-logo-eng.png'"  class="img-fluid pc-logo-1024"  alt="BORDERLESS WORKING" />
                            <img :src="'/images/common/footer-logo-eng-oneline.png'"  class="img-fluid sp-logo-1024"  alt="ボーダレスワーキング" />   
                        </router-link>
                        <router-link to="/jobseeker/login"  class="navbar-brand" v-if="currentUser.role_id== 3"> 
                            <img :src="'/images/common/footer-logo-eng.png'"  class="img-fluid pc-logo-1024"  alt="BORDERLESS WORKING" />  
                            <img :src="'/images/common/footer-logo-eng-oneline.png'"  class="img-fluid sp-logo-1024"  alt="ボーダレスワーキング" /> 
                        </router-link>                              
                        </li>
                        <li class="ftr-tit">{{ $t('footer.title')}}</li>
                        <li class="ftr-list-wrap">
                            <ul class="footer-list p-0">
                                <li>〒{{ $t('footer.postal_mark')}}</li>
                                <li>{{ $t('footer.buliding')}}</li>
                                <li>TEL：03-6911-3010</li>
                            </ul>
                        </li>
                        <li class="ftr-social-wrap">
                            <ul class="ftr-social-list p-0">                            
                                <li><a href=""><img :src="'/images/common/facebook-old.svg'"  alt="Facebook" /></a></li>
                                <li><a href="https://www.instagram.com/borderless_w_/" target="_blank"><img :src="'/images/common/instagram.svg'" alt="Instagram" /></a></li>
                                <li><a href="https://twitter.com/borderless_w_" target="_blank"><img :src="'/images/common/twitter-squared.png'"  alt="Twitter" /></a></li>
                            </ul>
                        </li>                        
                    </ul>      
                    <div class="d-flex justify-content-center">
                        <ul class="footer-bottom-list">                    
                            <!--<li class="border-btn-footer" v-if="currentUser != null && $route.path != '/recruiter' && $route.path != '/jobseeker'">{{ $t('footer.regirstation_scout')}}</li>-->
                            <li class="border-btn-footer" v-if="currentUser.role_id== 2">
                                <router-link to="/recruiter-flow" class="href-link" target="_blank">{{ $t('footer.regirstation_scout')}}</router-link></li>
                            <li class="border-btn-footer ml-2" v-if="currentUser.role_id == 3">
                                <router-link to="/jobseeker-flow" class="href-link" target="_blank">{{ $t('footer.job_application_flow')}}</router-link></li>   
                            <li><router-link to="/query" style="color:#fff">{{ $t('footer.contact')}}</router-link></li>
                            <li><router-link to="/privacy-policy" class="href-link" target="_blank">{{ $t('footer.policy')}}</router-link></li>
                            <li><router-link to="/terms" class="href-link" target="_blank">{{ $t('footer.terms')}}</router-link></li>
                            <li><router-link to="/specified-commercial-transactions" class="href-link" target="_blank">{{ $t('footer.Commercialtransactionlaw')}}</router-link></li>
                        </ul> 
                    </div>          
          
                      <!-- <ul class="footer-bottom-list">   
                        <span  v-if="currentUser != null" class="d-flex">
                        <li class="border-btn-footer" v-if="currentUser.role_id== 2">{{ $t('footer.regirstation_scout')}}</li>
                        <li class="border-btn-footer ml-2" v-if="currentUser.role_id == 3">{{ $t('footer.job_application_flow')}}</li>                        
                        </span>
                        <li class="pl-70">{{ $t('footer.contact')}}</li>
                        <li class="pl-20">{{ $t('footer.policy')}}</li>
                        <li class="pl-20">{{ $t('footer.terms')}}</li>
                        <li class="pl-20">{{ $t('footer.Commercialtransactionlaw')}}</li>
                    </ul> -->  
            <div class="row pb-2">
                <div class="col-12 text-center">
                    <p class="copy-right-txt">Copyright@Trust Growth Co,Ltd <span class="d-block-sp">All Rights Reserved.</span></p>
                </div>
            </div>
        </div>        
    </div> 
</div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    data(){
        return{ 
            footerClass:'',
            current:null,
            old_index: "",
            status: false,
        }
    },
  computed: {
    ...mapGetters(["currentUser","authError"]),
    // currentUser() {
    //   return this.$store.getters.currentUser;
    // }
  },
  created()
  {
     if(this.currentUser == null)
     {
         this.footerClass = "footer-block";
     }
     else
     {
         if(this.currentUser.role_id == 2)
         {
             this.footerClass = "footer-block recruiter-primary-color";
         }
         else{
             this.footerClass = "footer-block job-primary-color";
         }
     }
  },
  methods: {
      showToggle(index) {
            this.current = index;
            console.log(this.current);
            if (this.status == true) {
                if (this.current == this.old_index) this.status = false;
            } else {
                this.status = true;
            }
            this.old_index = index;
            console.log(this.old_index);
            console.log(this.status);
        }
  },

};
</script>
<style lang="scss" scoped>
.contact-btn {
    box-shadow: none!important;
    background: #EF8B1E;    
}
.ftr-link {
    color: #fff;
}
.sp-icon {
    display: none;
}
.pc-logo-1024{
    display:block;
}
.sp-logo-1024{
    display:none
}
@media (max-width: 1024px) { 
    /* public*/
    .sp-text-center{
        text-align: center;
    }
    .ftr-col {               
        margin-right:0px;
        &:first-child {
            display: flex;
            align-items: baseline;
            width: 100%;
            margin-bottom: 30px;
        }
    }
    a{
        display:block;        
    }
    .ftr-company-list {
        display: flex;
        align-items: baseline;
        margin-left: auto;
        .ftr-tit {
            margin-top: 0 !important;
            margin-right: 40px;
        }
    }
    /* After login*/
    .pc-logo-1024{
        display:none;
    }
    .sp-logo-1024{
        display:block;
        margin: 0 auto;
    }
    #logged-footer.footer-block {
        a{ 
            margin: 0 auto;
            padding: 0px;
        }
         .ftr-tit {
            padding-left:0rem;
            margin-left: auto;
        }
        .footer-list {
            display: inherit;
            padding: 0;
            li {
                padding-left: 0;
            }
        }
        .footer-top-list {
            align-items: baseline;
        }
        .ftr-social-list li {
            // padding-left: 0px;
        }        
    }
}
@media (max-width: 1023px) {    
    #public-footer.footer-block {
        padding-top: 20px;
    }
    .ftr-col {
        &:first-child {
            display: block;
            width: 100%;
        }
    }
    .ftr-company-list {
        display: block;
        margin-left: unset;
        .ftr-tit {
            margin-right: 0;
            margin-top: 1.5rem!important;
        }
    }
    .ftr-row {
        display: block;
        padding-left: 0px;
        padding-right: 0px;
    }
    .ftr-logo {
        display: block;
        text-align: center;
    }
    .sp-ftr-tit {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #fff;
    }
    .ftr-col-sns {
        .sp-ftr-tit {
            display: none;
        }
        .footer-list {
            display: flex !important;
            justify-content: center;
            li {
                padding: 0 2.5px;
            }
        }
    }
    .sp-icon {
        display: block;
        padding-right: 10px;
        margin-left: auto;
        font-size: 25px;
        i {
            transition: ease-in-out .3s;
        }
    }
     .rotate {
        i {
            transform: rotate(180deg);
        }
    }
    .ftr-company-list { 
        text-align: center;
        .footer-list {
            margin-bottom: 3rem!important;
        }
        .ftr-tit {
            &:after{
                left: 0;
                right: 0;
                margin: 0 auto;
            }
        }
        
    }
 
    #public-footer.footer-block .footer-list.sp-ftr-list  {
        height: 0;
        margin-bottom: 10px;
        transform: scaleY(0);    
        transform-origin: top;
        transition: transform 0.4s ease;
        text-align: left;
    }
    #public-footer.footer-block .ftr-tit.sp-ftr-tit:after {
        display: none;
    }
    .ftr-expand {
        height: auto !important;
        transform: scaleY(1) !important;
        margin: 10px 10px 30px 10px;
    }
   
    .ftr-hr {
        display: none;
    }
    #public-footer.footer-block .container {
        padding: 0 15px;
    }
    #logged-footer.footer-block  {
        .d-flex{
            display:block !important;
        }
        .sp-logo-1024{
            margin-bottom:1.5rem!important
        }
        .footer-top-list {
            display: block;
            text-align: center;
        }
        .ftr-tit {
            padding-left: 0;
            margin-bottom: 10px;
        }
        .ftr-list-wrap {
            margin-bottom: 20px;
            padding-left:0px;
        }
        .footer-list {
            display: inherit;
            padding: 0;
        }
        .sp-d-block {
            display: block !important;
        }
        .ftr-social-wrap {
            padding-left: 0px;
        }
        .footer-bottom-list {
            display: block;
            li {
                padding-left: 0;         
                padding: 5px 0 !important;
                text-align: center;
                border-bottom: 1px solid #a8adb0 !important
            }
            .border-btn-footer {
                border: none;
            }
        }
        .jobseeker-ftr-list {
            li {
                border-bottom: 1px solid #82a189 !important;
            }
        }
        .recruiter-ftr-list {
            li {
                border-bottom: 1px solid #5f7d3b !important;
            }
        }
    }
}
  
   
</style>