<template>
  <div>    
    <!--jobseeker forgot password-->
    <div class="login-card-body" v-if="$route.name == 'jobseeker-forgot-password'">
        <div class="form-wrap job-bordertop">
        <a href="/" class="no-href">
         <span class="icon-new-logo-txt logo-img job-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </a>                     
          <form class="form-content" @submit.prevent="requestResetPassword" method="" ref="progressContainer">            
            <h3 class="form-signin-heading text-center job-primary-color-txt"><span class="icon-login"></span>求職者会員パスワード再設定</h3>   
            <div class="form-group mb-3" v-if="success.msg == ''">
                <label for="email">メールアドレス</label>
                <div class="input-group">              
                    <input type="email" id="email" :class="['form-control input_pass m-l1',$v.email.$error?'is-invalid':'']" placeholder="メールアドレス" autocomplete="off"  v-model="$v.email.$model"/>
                    <span  class="invalid-feedback error_font error">
                        <span v-if="!$v.email.required">メールアドレスは必須です</span>
                        <span v-if="!$v.email.email">メールアドレスの形式が正しくありません</span>
                        <!-- <span v-if="errors.msg">{{errors.msg}}</span> -->
                        <!-- <span v-if="success.msg">{{success.msg}}</span> --> 
                    </span> 
                </div>               
                <span v-if="errors.msg" class="error">{{errors.msg}}</span>
                <!-- <span v-if="success.msg" class="error">{{success.msg}}</span>  -->
            </div> 
            <div v-else class="form-group mb-3">
                <span v-if="success.msg">{{success.msg}}</span>
            </div>  
            <div v-if="success.msg == ''" class="fomr-group text-center m-t-30">
                <button class="btn searchbtn job-primary-color" type="submit">送信</button>             
            </div> 
            <div v-else class="fomr-group text-center m-t-30">
                <a href="/" class="btn searchbtn job-primary-color">トップページへ</a>             
            </div> 
          </form>
        </div>
    </div>   
	<div class="login-card-body" v-else>
        <div class="form-wrap recruiter-bordertop">
        <a href="/" class="no-href">
         <span class="icon-new-logo-txt logo-img recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </a>            
          <form class="form-content" @submit.prevent="requestResetPassword" method="" ref="progressContainer">            
            <h3 class="form-signin-heading text-center recruiter-primary-color-txt"><span class="icon-login"></span>企業会員パスワード再設定</h3>   
            <div class="form-group mb-3" v-if="success.msg == ''">
                <label for="email">メールアドレス</label>
                <div class="input-group">              
                    <input type="email" id="email" :class="['form-control input_pass m-l1',$v.email.$error?'is-invalid':'']" placeholder="メールアドレス" autocomplete="off"  v-model="$v.email.$model"/>
                    <span  class="invalid-feedback error_font error">
                        <span v-if="!$v.email.required">メールアドレスは必須です</span>
                        <span v-if="!$v.email.email">メールアドレスの形式が正しくありません</span>
                        <!-- <span v-if="errors.msg">{{errors.msg}}</span> -->
                        <!-- <span v-if="success.msg">{{success.msg}}</span> --> 
                    </span> 
                </div>               
                <span v-if="errors.msg" class="error">{{errors.msg}}</span>
                <!-- <span v-if="success.msg" class="error">{{success.msg}}</span>  -->
            </div> 
            <div v-else class="form-group mb-3">
                <span v-if="success.msg">{{success.msg}}</span>
            </div>  
            <div v-if="success.msg == ''" class="fomr-group text-center m-t-30">
                <button class="btn searchbtn recruiter-primary-color" type="submit">送信</button>             
            </div> 
            <div v-else class="fomr-group text-center m-t-30">
                <a href="/" class="btn searchbtn recruiter-primary-color">トップページへ</a>             
            </div> 
          </form>
        </div>
    </div> 
    <!--recruiter forgot password-->
     <!-- <div class="login-card-body" v-else>
        <div class="form-wrap" style="border-top: 3px solid #00a195;">
        <router-link to="/" ><span class="icon icon-logo logo-img recruiter-primary-color-txt"></span></router-link>            
          <form class="form-content" @submit.prevent="requestResetPassword" method="">            
            <h3 class="form-signin-heading text-center recruiter-primary-color-txt"><span class="icon-login"></span>パスワード再設定</h3>   
            <div class="form-group mb-3" v-if="success.msg == ''">
                <label for="email">メールアドレス</label>
                <div class="input-group">              
                    <input type="email" id="email" :class="['form-control input_pass m-l1',$v.email.$error?'is-invalid':'']" placeholder="メールアドレス" autocomplete="off"  v-model="$v.email.$model"/>
                </div>    
                <span class="invalid-feedbac  k error_font">
                    <span v-if="!$v.email.required">メールアドレスは必須です</span>
                    <span v-if="!$v.email.email">メールアドレスの形式が正しくありません</span>
                    <span v-if="errors.msg">{{errors.msg}}</span>
                    <span v-if="success.msg">{{success.msg}}</span>
                </span> 
                <span v-if="errors.msg" class="error_font">{{errors.msg}}</span>
                <span v-if="success.msg" class="error_font">{{success.msg}}</span>              
            </div> 
            <div v-else class="form-group mb-3">
                <span v-if="success.msg">{{success.msg}}</span>
            </div>  
            <div v-if="success.msg == ''" class="fomr-group text-center m-t-30">
                <span class="btn searchbtn recruiter-primary-color"> 送信</span>             
            </div> 
            <div v-else class="fomr-group text-center m-t-30">
                <router-link to="/" class="btn searchbtn recruiter-primary-color"> トップページへ</router-link>             
            </div> 
          </form>
        </div>
    </div>    -->
  </div>
</template>


<script>
import { required, email } from "vuelidate/lib/validators";
import {mapGetters} from 'vuex';
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
    data() {       
      return {
        email: '',
        errors:{
                msg:'',
            },
        success:{
            msg:'',
        },      
      }
    },
    computed: {
        ...mapGetters(["currentUser","authError"]),
    
    },
    validations: {
            email: { required, email },
		},
    methods: {
        requestResetPassword() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
			let loading = this.$loading.show();
			let routeName = this.$route.name;
            this.$api.post("/auth/forgot-password", {email: this.email,routeName:routeName}).then(result => {             
                this.errors.msg ='';
                this.success.msg = '';
                this.success.msg = result.data.success
                loading.hide();
            }, error => {
                console.log("e",error)
                loading.hide();
                this.errors.msg ='';
                this.success.msg = '';
                if(error.response.data.error){
                    
                    this.errors.msg = error.response.data.error
                }else{
                    this.errors.msg = error.response.data.errors.email
                }                
            });
        }
    }
}
</script>
<style lang="scss" scoped>
.login-card-body .form-content{
    min-height: 260px;
}    
.custom-control-input {
    position: unset;
}
.icon-login { 
    margin-right: 10px;
    font-size: 40px;
    vertical-align: middle;
}
.close-ico {
    position: absolute;
    font-size: 23px;
    top: -15px;
    right: -12px;
    color: #f0fae3;
    background: #6085a3 ;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    vertical-align: middle;
}
.job-bordertop{
    border-top: 3px solid rgb(96, 133, 163);
}
.recruiter-bordertop{
    border-top: 3px solid #00a195;
}
@media only screen and (max-width: 812px) and (orientation : landscape){
      .icon-login { 
        font-size: 30px;
    }
    .job-bordertop{
        border:none;
    }
    .recruiter-bordertop{
        border:none;
    }
    .form-wrap {
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;
        max-height: 100vh;
    }
    .login-card-body {
        background: none;
       .form-content{
            width: 80%;
            margin-bottom: 0px;
            background: none;
            border: none;
            box-shadow: none;
        }
    }
    .icon-login{
        font-size: 30px;
    }
}
@media (max-width: 767px) and (orientation : portrait) {
     .icon-login { 
        font-size: 30px;
    }
    .job-bordertop{
        border:none;
    }
    .recruiter-bordertop{
        border:none;
    }
    .form-wrap {
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;
        max-height: 100vh;
    }
    .login-card-body {
        background: none;
       .form-content{
            width: 100%;
            margin-bottom: 0px;
            background: none;
            border: none;
            box-shadow: none;
        }
    }
    .icon-login{
        font-size: 30px;
    }
}
    
</style>