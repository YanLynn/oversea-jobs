<template>
  <div class="login">   
      <div v-if="registeredUser" class="text-success">Thank you {{registeredUser.name}}.You can now login</div>
        <div class="login-card-body">
        <div class="form-wrap">
        <router-link to="/" class="no-href" >
            <span class="icon-new-logo-txt logo-img job-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </router-link> 
          <form class="form-content" @submit.prevent="authenticate">            
            <h1 class="form-signin-heading text-center job-primary-color-txt"><span class="icon-login"></span>求職者会員ログイン</h1> 
            <div class="form-group has-error has-feedback m-l-42" v-if="errors.verify">
                <label for="inputError2" class="control-label text-danger">{{errors.verify}}</label>    
            </div>  
            <!-- <div class="form-group has-error has-feedback" v-if=" errors.permission">
              <label for="inputError2" class="control-label">{{errors.permission}}</label>           
            </div> -->

            <div class="form-group mb-3">
              <label for="email">メールアドレス</label>
              <div class="input-group">              
                <input type="email" :class="['form-control input_pass m-l1', $v.formLogin.email.$error ? 'is-invalid' : '']" v-model="$v.formLogin.email.$model" name="メールアドレス" id="email" placeholder="メールアドレス" autocomplete="off" />
                <div  class="invalid-feedback error_font error">
                    <span class="error" v-if="!$v.formLogin.email.required">メールアドレスが入力されていません</span>
                    <span class="error" v-if="!$v.formLogin.email.email">メールアドレスの形式が正しくありません</span>
               </div>
              </div>
            </div>

            <div class="form-group mb-1">
                <label for="password">パスワード</label>
                <div class="input-group">               
                    <!-- hide password-->                   
                    <input :class="['form-control input_pass m-l1', $v.formLogin.password.$error ? 'is-invalid' : '']" v-model="$v.formLogin.password.$model" type="password" placeholder="パスワード" id="password"  v-show="!showPass" />
                   
                    <!-- show password-->
                    <input class="form-control input_pass" type="text" placeholder="パスワード" v-model="formLogin.password" v-show="showPass" />                   
                        <p v-show="isIcon">
                            {{showIco}}
                            <span class="showpwd" @click="showPass = !showPass">
                                <span v-show="!showPass"  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                            </span>   
                        </p>
                        <div class="invalid-feedback error">
                            <span class="error" v-if="!$v.formLogin.password.required">パスワードが入力されていません</span>
                            <span class="error" v-if="!$v.formLogin.password.minLength">パスワードは8文字以上必要です</span>
                            <!-- <span class="error" v-if="errors.permission">{{errors.permission}}</span> -->
                        </div>
                        
                   
            
                </div>
            </div>
            <div class="form-group text-center">
                <router-link to="/jobseeker/forgot-password" class="forgot-pwd">パスワードをお忘れですか？</router-link>
            </div>
            <div class="form-group text-center">
                <router-link to="/jobseeker/register" class="forgot-pwd">新規登録はこちら</router-link>
            </div>
          
            <div class="fomr-group text-center m-t-30">
                <button class="btn searchbtn job-primary-color" type="submit">ログイン</button>
                <!-- <button class="btn searchbtn cancel-color" type="submit" @click="clearData()">リセット</button> -->
            </div>

            <div class="form-group text-center error" v-if="errors.permission">
                  <span for="inputError2" class="error">{{errors.permission}}</span>    
            </div>
        
          </form>
        </div>
        </div>   
  </div>
</template>

<script>
import { required, email, minLength } from "vuelidate/lib/validators";
import { login } from "../../partials/auth";
import { jobseekerLogin } from "../../partials/auth";
export default {
    metaInfo: {
        title: '求職者ログイン｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: '求職者ログインページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
    },
  data() {
    return { 
            scouted_count:0,
            isIcon: false,
            showPass: false,   
            errors:{
                permission:'',
                verify:'',
            },
            formLogin: {
                email: "",
                password: ""
            },
        };
   },
   validations: {
        formLogin: {            
           email: { required, email },
           password: { required, minLength: minLength(8) },
        },
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
            if(this.$route.query.oe != null){
                this.updateEmail();
            }
            // console.log('this.$route.query',this.$route.query)
            
        },
    methods: {
        authenticate() {
         
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            
            this.$store.dispatch("jobseekerLogin");
            let loading = this.$loading.show();
            jobseekerLogin(this.formLogin).then(res => { 

                this.$store.commit("loginSuccess", res);     
                // this.$router.push({path: '/jobseeker'})

                let prev_route_path = localStorage.getItem("previous_route_path");
                this.$router.push({path: prev_route_path.includes("/public-job-details") ? prev_route_path :'/jobseeker'});

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
                // if(this.errors.permission == undefined){
                //     this.errors.permission = 'あなたのアカウントは停止されていました'
                // }
                // this.errors.permission = error.response.data.msg;
                // this.errors.permission = error.response.data.error.error;
                
            });

            // this.$api.get(`/jobseeker/scouted-count`).then(r => {
			// 	this.scouted_count = r.data;
			// 	this.$store.commit('increment',this.scouted_count)
			// });

            


            // localStorage.setItem("user", JSON.stringify(state.currentUser));
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
  computed: {
        registeredUser() { 
           return this.$store.getters.registeredUser;
        },
        showIco() {
            if( this.formLogin.password === '') {
                this.isIcon = false;
            }else{
                this.isIcon = true;
        }
    }
  },
  
    
};
</script>

<style lang="scss" scoped>  
.form-wrap {        
    border-top: 3px solid #6085a3 ;       
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
            width: 80%;            
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