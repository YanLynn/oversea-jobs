<template>
  <div class="login">   
      <!-- <div v-if="registeredUser" class="text-success" >Thank you {{registeredUser.name}}.You can now login</div> -->
        <div class="login-card-body">
            <div class="form-wrap">
            <router-link to="/" class="no-href" >
                <span class="icon-new-logo-txt logo-img recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </router-link>    
            <form class="form-content" @submit.prevent="authenticate">                      
                <h1 class="form-signin-heading text-center recruiter-primary-color-txt"><span class="icon-login"></span>企業会員ログイン</h1>
                <div class="form-group has-error has-feedback m-l-42" v-if="errors.verify">
                    <label for="inputError2" class="control-label text-danger">{{errors.verify}}</label>    
                </div>  
            
            <div class="form-group mb-3">
              <label for="email">メールアドレス</label>
              <div class="input-group">              
                 <input type="email" :class="['form-control',$v.formLogin.email.$error?'is-invalid':'']" name="メールアドレス" id="email" placeholder="メールアドレス" autocomplete="off"  v-model="$v.formLogin.email.$model" />
                <div  class="invalid-feedback error">
                        <span v-if="!$v.formLogin.email.required">メールアドレスが入力されていません</span>
                        <span v-if="!$v.formLogin.email.email">メールアドレスの形式が正しくありません</span>
                </div>
              </div>
            </div>

            <div class="form-group mb-1">
            <label for="password">パスワード</label>
                <div class="input-group">               
                    <!-- hide password-->
                    <!-- hide password-->
              <input :class="['form-control',$v.formLogin.password.$error?'is-invalid':'']" type="password" placeholder="パスワード" id="password" v-show="!showPass"  v-model="$v.formLogin.password.$model"/>
              <!-- show password-->
              <input :class="['form-control',$v.formLogin.password.$error?'is-invalid':'']" type="text" placeholder="パスワード" v-show="showPass" v-model="$v.formLogin.password.$model"/>
              <!-- show password-->
              <p  v-if="formLogin.password != ''">
                <span class="showpwd" @click="showPass = !showPass">
                <span v-show="!showPass"  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                </span>
              </p>

                 <div v-if="$v.formLogin.password.$error" class="invalid-feedback error">
                        <span v-if="!$v.formLogin.password.required">パスワードが入力されていません</span>
                        <span v-if="!$v.formLogin.password.minLength">パスワードは8文字以上必要です</span>
                </div>
            
                </div>
            </div>
            <div class="form-group" style="text-align:center;">
                <router-link to="/recruiter/forgot-password" class="forgot-pwd">パスワードをお忘れですか？</router-link>
            </div>             
            <div class="form-group" style="text-align:center;">
                <router-link to="/recruiter/register" class="forgot-pwd">新規登録はこちら</router-link>
            </div>             

            <div class="fomr-group text-center m-t-30">
                <button class="btn searchbtn recruiter-primary-color " type="submit">ログイン</button>
                <!-- <button class="btn searchbtn cancel-color" @click="clearData()">リセット</button> -->
            </div>
            <div class="has-error has-feedback text-center error" v-if="errors.permission">
                  <label for="inputError2" class="error" @click="clearData()">{{errors.permission}}</label>    
            </div>
        
          </form>
            </div>
          
        </div>   
  </div>
</template>


<script>
import { login } from "../../partials/auth";
import { recruiterLogin } from "../../partials/auth";
import { required, email, minLength } from "vuelidate/lib/validators";
export default {
    metaInfo: {
        title: '企業会員ログイン｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: '企業会員ログインページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
    }, 
  data() {
    return {
     errors:{
          permission:'',
          verify:'',
           },
      errorShow:true,
      formLogin: {
        email: "",
        password: "",
      },
      submitted: false,
      error: null,
      showPass: false,
    };
  },
   validations: {
            formLogin: {
                email: { required, email },
                password: { required, minLength: minLength(6) },
                
            }
        },

    computed:{
        currentUser(){
            return this.$store.getters.currentUser
        },
        authError() { 
        return this.$store.getters.authError;
        },
        registeredUser() {
        return this.$store.getters.registeredUser;
        }
    },
    created(){
            // replace  url amp;
            var amp = this.$route.query
            var newKey = {
                'amp;key' : "key",
                'amp;_id' : "_id",
                'amp;oe' : "oe"
            }
            _.each(amp, function(value, key) {
                key = newKey[key] || key;
                amp[key] = value;
            });

            if(this.$store.getters.currentUser != null){
                localStorage.removeItem('user');
            }
            if(this.$route.query.oe != null ){
                this.updateEmail();
            }

        },
    methods: {

        authenticate() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            this.$store.dispatch("recruiterLogin");
            let loading = this.$loading.show();
            recruiterLogin(this.formLogin).then(res => {
                this.$store.commit("loginSuccess", res);   
                this.$router.push({path: '/recruiter'})   
                loading.hide();
            })
            .catch(error => {
                loading.hide();
                if(error.response.data.msg == undefined){
                    this.errors.permission = error.response.data.error.error;
                }
                else{
                    this.errors.permission = error.response.data.msg;
                }
                
            });
        },
        clearData()
        {
            this.formLogin.email = '';
            this.formLogin.password = '';
        },
        updateEmail(){
            this.token = this.$route.query.token;
            this.key = this.$route.query.key;
            this.oe = this.$route.query.oe;
            this._id = this.$route.query._id;
            //Universal Controller 
            this.$api.post('update-email',{token:this.token,key:this.key,oe:this.oe,id:this._id}).then(res => {
                console.log(res.data)
            }).catch(res => {
                this.errors.verify = res.response.data.error;
                this.errorShow = false;
                setTimeout(() => this.errorShow = true, 5000);
            })
        },
  },
  
};
</script>
<style lang="scss" scoped> 
.form-wrap {        
    border-top: 3px solid #00a195;      
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
    background: #00a195   ;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    vertical-align: middle;
}
.login .error{
    margin-top:0px;
}
@media only screen and (max-width: 812px) and (orientation : landscape){
     .icon-login { 
        font-size: 30px;
    }
    .login-card-body{
        background: none; 
        height: 100%;      
        .form-content{           
            width:80%;            
            margin-bottom: 0px;
            background: none;
            border: none;
            box-shadow: none;
        }
    }
    .form-wrap{
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;       
    }
    .logo-img{
        margin: 0 auto;
        margin-bottom: 10px;
    }
}

@media (max-width: 767px) and (orientation : portrait){
     .icon-login { 
        font-size: 30px;
    }
    .login-card-body{
        background: none;       
        .form-content{           
            width: 100%;            
            margin-bottom: 0px;
            background: none;
            border: none;
            box-shadow: none;
        }
    }
    .form-wrap{
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;
        max-height: 100vh;        
    }
    .logo-img{
        margin: 0 auto;
        margin-bottom: 10px;
    }
}
</style>