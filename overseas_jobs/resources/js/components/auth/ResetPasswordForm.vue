<template>   
    <div class="login-card-body">

      <div class="form-wrap" :class="[(tokenUser.role_id == 3 ? 'borderJ' :'borderR'),(roleID == 3 ? 'borderJ': 'borderR')]" style=""> 
         <a href="/" v-if="tokenUser.role_id == 3 || roleID == 3">
            <span class="icon-new-logo-txt logo-img job-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
         </a>   
         <a href="/" v-if="tokenUser.role_id == 2 || roleID == 2">
         <span class="icon-new-logo-txt logo-img recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
         </a>           
            <div v-if="tokenUser.length  != 0 && tokenUser.password_locked != 3 && isHidden == false && tokenUser.role_id == 2 && validatedToken == 1"> 
                <form @submit.prevent="checkAns" method="post" class="form-content">
                    <h3 class="form-signin-heading text-center recruiter-primary-color-txt">秘密の質問</h3>  
                    <div class="form-group mb-3">                       
                        <div class="input-group"> 
                             <label v-if="getQues">{{getQues.question}}</label>
                             <!-- <input type="text" v-if="getQues" id="quest" class="form-control input_pass m-l1 p-0 border-0" style="background:transparent;font-size:18px;" placeholder="" :value="getQues.question" hidden> -->
                        </div>  
                        <div class="input-group">
                            <input type="text" id="ans"  placeholder="回答" class="form-control" :class="['form-control',errors.msgs]"  v-model="ans">
                        </div> 
                        <span for="inputError2" class="error_font error"  v-if="errors.msgs">{{errors.msgs}}</span> 
                    </div>  
                    
                    <div class="fomr-group text-center m-t-30">
                        <button type="submit" class="btn searchbtn recruiter-primary-color">次へ</button>
                    </div>
                </form>               
            </div>
            <div v-if="tokenUser.role_id == 3">
                 <div v-if="(!isHidden && validatedToken == 1) && !isLogin">                   
                    <form @submit.prevent="resetPassword"  class="form-content">
                        <h3 class="form-signin-heading text-center job-primary-color-txt">求職者会員パスワード変更</h3>
                        <div class="form-group mb-3">
                            <label for="email">新しいパスワード</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control" placeholder="" 
                                    :class="['form-control',$v.password.$error?'is-invalid':'']" v-model="$v.password.$model" :disabled="validatedToken == 0">
                            </div>
                            <span v-if="$v.password.$error" class="error_font">
                                <span class="error" v-if="!$v.password.required">新しいパスワードを入力してください</span>
                                <span class="error" v-if="!$v.password.minLength">パスワードは8文字以上必要です</span>
                            </span>
                        </div>
                        <div class="form-group mb-1">
                            <label for="email">パスワード確認</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" 
                                    :class="['form-control',$v.password_confirmation.$error?'is-invalid':'']" 
                                    placeholder=""
                                    v-model="$v.password_confirmation.$model" :disabled="validatedToken == 0">
                            </div>                  
                            <span v-if="$v.password_confirmation.$error" class="error_font">
                                <span class="error" v-if="!$v.password_confirmation.required">新しいパスワードを入力してください</span>
                                <span class="error" v-else-if="!$v.password_confirmation.sameAsPassword">パスワードが一致しません</span>
                            </span>
                        </div>
                        <span class="form-group has-error has-feedback" v-if="errors.msg">
                            <label for="inputError2" class="control-label">{{errors.msg}}</label>           
                        </span>
                        <div class="fomr-group text-center m-t-30">
                            <button type="submit" class="btn searchbtn job-primary-color" :disabled="validatedToken == 0">変更</button>
                        </div>
                    </form>
                </div>
                <div v-if="(!isHidden && validatedToken == 1) && isLogin" >
                    <div class="form-content d-table">   
                        <h3 :class="['form-signin-heading text-center', tokenUser.role_id == 3?'job-primary-color-txt':'recruiter-primary-color-txt']">
                            求職者会員パスワード変更
                        </h3>
                        <div  class="form-group mb-3 register-wrapper text-center">
                            <span class="text-center">パスワードの変更が完了しました。</span>
                        </div>  
                        <div class="fomr-group text-center m-t-30">
                        <button type="submit" @click="login"  :class="['btn searchbtn', tokenUser.role_id == 3?'job-primary-color':'recruiter-primary-color']">ログイン</button>           
                        </div> 
                    </div>
                </div>
            </div>

            <div v-if="tokenUser.role_id == 2">
                 <div v-if="(isHidden && validatedToken == 1) && !isLogin">                   
                    <form @submit.prevent="resetPassword"  class="form-content">
                        <h3 class="form-signin-heading text-center recruiter-primary-color-txt">企業会員パスワード変更</h3>
                        <div class="form-group mb-3">
                            <label for="email">新しいパスワード</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control" placeholder="" 
                                    :class="['form-control',$v.password.$error?'is-invalid':'']" v-model="$v.password.$model" :disabled="validatedToken == 0">
                            </div>
                            <span v-if="$v.password.$error" class="error_font">
                                <span class="error" v-if="!$v.password.required">新しいパスワードを入力してください</span>
                                <span class="error" v-if="!$v.password.minLength">パスワードは8文字以上必要です</span>
                            </span>
                        </div>
                        <div class="form-group mb-1">
                            <label for="email">パスワード確認</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" 
                                    :class="['form-control',$v.password_confirmation.$error?'is-invalid':'']" 
                                    placeholder=""
                                    v-model="$v.password_confirmation.$model" :disabled="validatedToken == 0">
                            </div>                  
                            <span v-if="$v.password_confirmation.$error" class="error_font">
                                <span class="error" v-if="!$v.password_confirmation.required">新しいパスワードを入力してください</span>
                                <span class="error" v-else-if="!$v.password_confirmation.sameAsPassword">パスワードが一致しません</span>
                            </span>
                        </div>
                        <span class="form-group has-error has-feedback" v-if="errors.msg">
                            <label for="inputError2" class="control-label">{{errors.msg}}</label>           
                        </span>
                        <div class="fomr-group text-center m-t-30">
                            <button type="submit" class="btn searchbtn recruiter-primary-color" :disabled="validatedToken == 0">変更</button>
                        </div>
                    </form>
                </div>
                 <div v-if="(isHidden && validatedToken == 1) && isLogin" >
                    <div class="form-content d-table">   
                        <h3 class="form-signin-heading text-center recruiter-primary-color-txt">企業会員パスワード変更</h3>
                        <div  class="form-group mb-3 register-wrapper text-center">
                            <span class="text-center">パスワードの変更が完了しました。</span>
                        </div>  
                        <div class="fomr-group text-center m-t-30">
                        <button type="submit" @click="login" class="btn searchbtn recruiter-primary-color" >ログイン</button>           
                        </div> 
                    </div>
                 </div>
                 <div class="form-content d-table" v-if="tokenUser.password_locked == 3" >
                    <h3 :class="['form-signin-heading text-center', roleID == 3?'job-primary-color-txt':'recruiter-primary-color-txt']">
                        企業会員パスワード変更
                    </h3>
                    <div  class="form-group mb-3 register-wrapper text-center">
                        <span class="text-center">秘密の回答を規定回数以上間違えたため、このアカウントのパスワード変更はしばらくできません。</span>
                    </div>  
                    <div class="fomr-group text-center m-t-30">
                        <button type="submit" @click="login" :class="['btn searchbtn', roleID == 3?'job-primary-color':'recruiter-primary-color']">ログイン</button>           
                    </div> 
                </div>
            </div>

            <div class="form-content d-table" v-if="errors.msg == 'tokenInvalided!'">   
                <h3  :class="['form-signin-heading text-center', roleID == 3?'job-primary-color-txt':'recruiter-primary-color-txt']">
                    <span v-if="roleID == 3">求職者会員</span><span v-else>企業会員</span>パスワード変更
                </h3>
                <div  class="form-group mb-3 register-wrapper text-center">
                    <span class="text-center">このリンクは無効です。</span>
                </div>  
                <div class="fomr-group text-center m-t-30">
                     <a href="/" :class="['btn searchbtn', roleID == 3?'job-primary-color':'recruiter-primary-color']"> トップページへ</a>
                </div> 
            </div>
            
            <div class="form-content d-table" v-if="errors.msg == 'Token expired!'">   
                <h3 :class="['form-signin-heading text-center', roleID == 3?'job-primary-color-txt':'recruiter-primary-color-txt']">
                    <span v-if="roleID == 3">求職者会員</span><span v-else>企業会員</span>パスワード変更
                </h3>
                <div  class="form-group mb-3 register-wrapper text-center">
                    <span class="text-center">リンクの有効期限が切れています。</span>
                </div>  
                <div class="fomr-group text-center m-t-30">
                    <a href="/"  :class="['btn searchbtn', roleID == 3?'job-primary-color':'recruiter-primary-color']"> トップページへ</a>
                </div> 
            </div>
      </div>
    </div> 
</template>

<script>
import { required,  minLength, maxLength,  sameAs } from "vuelidate/lib/validators";
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        },
        data(){
            return {
                password:'',
                password_confirmation:'',
                token:'',
                key:'',
                errors:{
                    msg:'',
                    msgs:''
                },
                validatedToken:1,
                tokenUser:[],
                getQues:[],
                ans:'',
                answer:{
                    match:''
                },
                isHidden: false,
                isLogin:false,
                borderJ: 'borderJ',
				borderR: 'borderR',
				roleID:'',
            
            }
       },
       validations: {
            password: { required, minLength: minLength(8) },
            password_confirmation: { required, sameAsPassword: sameAs("password") },
        },
        created(){
           this.getUserInfo();
       },
       methods:{
        getUserInfo(){ 
				 var amp = this.$route.query;
                 var newKey = {
                        'amp;RID_key' : "RID_key",
                        'amp;key' : "key",
                        'amp;_id' : "_id",
                        'amp;oe' : "oe"
                    }
                    _.each(amp, function(value, key) {
                        key = newKey[key] || key;
                        amp[key] = value;
                    });
                this.token = this.$route.query.token;
				this.key = this.$route.query.key;
                this.rol_id = this.$route.query.RID_key;
                console.log("route",this.$route.query)
                console.log("token",this.token)
                console.log("key",this.key)
                console.log("rol",this.rol_id)
                var jsonData = {
                    token: this.token,
                    key: this.key,
                    role_id: this.rol_id
                } 
                this.$api.get('auth/reset-password/',{params:{jsonData}}).then(res => {
                    console.log("res",res)
				this.tokenUser = res.data.data.getExitUser
                this.getQues = res.data.data.getQues
            
            }).catch(error => {
                console.log(error)
                    if(error.response.data.error){
                        this.validatedToken = 0
                        this.errors.msg  = error.response.data.error
						this.roleID = error.response.data.role_id
						console.log(this.role_id)
                    }
                });
        },
        login(){
            console.log("role_id",this.tokenUser);
            if(this.tokenUser.role_id == 2){
                this.$router.push({ path: "/recruiter/login" });
            }
            if(this.tokenUser.role_id == 3){
                this.$router.push({ path: "/jobseeker/login" });
            }
         
        },
        resetPassword(){
                 this.$v.$touch();
                    if (this.$v.$invalid) {
                        return;
                    }
                let jsonData = {
                   password:this.password,
                   token:this.token,
                   key:this.key,
                   role_id:this.rol_id
                   }
               this.$api.post('auth/updateResetPassword', jsonData).then(res =>{
                   console.log("res",res)
                       this.isLogin = true;
                   }).catch(error => {       
                    console.log('e',error.response)
                    });
           },
           checkAns(){
               if(this.ans == ''){
                this.errors.msgs  = '回答を入力してください';
               }else{
                   this.errors.msgs =''
                   this.$api.post('auth/checkAns',{
                   ans:this.ans,
                   locakedCount:this.tokenUser
                    }).then(res => {
                        this.getUserInfo();
                        this.answer.match = res.data.answer
                        this.isHidden = true
                        
                    }).catch(e => {
                        this.errors.msgs ='回答が間違っています';
                        this.tokenUser.password_locked  += 1;
                        this.isHidden = false
                    })

               }
               
           }
       }

    };
</script>
<style lang="scss" scoped>
    .login-card-body .form-content{
        min-height: 355px;
    }      
    .form-wrap {       
        width: 460px;      
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
   
    .empty-block{
        line-height: 280px;
        text-align: center;
        font-size: 20px;
        margin: 0 auto;
    }

    .borderR {
        border-top: 3px solid #00a195;
    }

    .borderJ {
        border-top: 3px solid #6085a3;
    }
    @media only screen and (max-width: 812px) and (orientation : landscape){
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
    }
    @media (max-width: 767px) and (orientation : portrait) {
        .form-wrap {
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;
        max-height: 100vh;
    }
        .login-card-body {
        align-items: end !important;  
        background: none;
       .form-content{
            width: 100%;
            margin-bottom: 0px;
            background: none;
            border: none;
            box-shadow: none;
        }
    }
    }
    
    
</style>