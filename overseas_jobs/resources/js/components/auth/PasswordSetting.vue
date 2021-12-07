<template>
    <div class="user-main">
        <div v-if="currentUser.role_id == 2">
            <h2 class="user-head-tit recruiter-primarylight-color">{{ $t('password-setting.title') }}</h2>
            <!-- Password confirmation form -->
            <div class="content-wrap">
                <div class="form-box">
                    <!-- <h5 class="form-tit">{{ $t('password-setting.title') }}</h5> -->
                    <div class="form-in">
                        <form
                            class="form-signin searchform-one"
                            role="form"
                            v-show="!passwordAuthenticated"
                            @submit.prevent="authenticate"
                        >
                            <div class="searchform-one">          
                             <label for="password">{{ $t('password-setting.enter_current_password') }}</label>                     
                                <div class="col-12 col-sm-6 col-xl-5 p-0">                                
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        id="password"
                                        v-model.trim="$v.formLogin.password.$model"
                                        v-show="!showPass"
                                        @keyup="clearSuccess"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                    />
                                    <!-- show password-->
                                    
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formLogin.password.$model"
                                        v-show="showPass"
                                        @keyup="clearSuccess"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                    />
                                     <p v-show="isIcon" class="m-0">
                                        {{showIco}}
                                    <span class="showpwd" @click="showPass = !showPass">
                                        <span v-show="!showPass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                     </p>
                                    <span class="form-group has-error has-feedback" v-if="$v.formLogin.password.$error">
                                        <span class="error" v-if="!$v.formLogin.password.required">{{ $t('password-setting.password_required') }}</span>
                                    </span>                                    
                                     <div>
                                        <div class="has-error has-feedback" v-if="errors"> 
                                            <span for="inputError2" class="control-label error">{{ $t('password-setting.password_wrong') }}</span>
                                        </div>
                                        <div class="has-success has-feedback" v-if="success">
                                            <span for="inputError2" class="control-label error">{{ $t('password-setting.password_changed') }}</span>
                                        </div>
                                    </div>
                                </div>                              
                                 <div class="col-12 col-sm-6 col-xl-5 mt-4 p-0 w-50">
                                    <button class="btn recruiter-primary-color border-style btn-m" type="submit">次へ</button>
                                </div>
                            </div>
                           
                        </form>

                        <!-- Password change form -->
                        <form class="form-signin" role="form" v-show="passwordAuthenticated" @submit.prevent>
                            <div class="form-group searchform-one"> 
                                 <label for="password">{{ $t('password-setting.enter_new_password') }}</label>
                                  <div class="col-12 col-sm-6 col-xl-5 p-0">
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.newPassword.$model"
                                        v-show="!formChangePassword.showNewPass"
                                        :class="{'input-error': $v.formChangePassword.newPassword.$error}"
                                    />
                                    <!-- show password-->
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.newPassword.$model"
                                        v-show="formChangePassword.showNewPass"
                                        :class="{'input-error': $v.formChangePassword.newPassword.$error}"
                                    />
                                    <p v-if="formChangePassword.newPassword != ''" class="m-0">
                                    <span class="showpwd" @click="formChangePassword.showNewPass = !formChangePassword.showNewPass">
                                        <span v-show="!formChangePassword.showNewPass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="formChangePassword.showNewPass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                    </p>
                                    <span class="form-group has-error has-feedback" v-if="$v.formChangePassword.newPassword.$error">
                                        <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.required">{{ $t('password-setting.password_minlength') }}</label>
                                        <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.minLength">{{ $t('password-setting.password_minlength') }}</label>
                                    </span>
                                </div>                                
                            </div>
                            <div class="form-group searchform-one">    
                                 <label for="password">{{ $t('password-setting.enter_password_again') }}</label>
                                 <div class="col-12 col-sm-6 col-xl-5 p-0">
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.retypePassword.$model"
                                        v-show="!formChangePassword.showRetypePass"
                                        :class="{'input-error': $v.formChangePassword.retypePassword.$error}"
                                    />
                                    <!-- show password-->
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.retypePassword.$model"
                                        v-show="formChangePassword.showRetypePass"
                                        :class="{'input-error': $v.formChangePassword.retypePassword.$error}"
                                    />
                                    <p v-if="formChangePassword.retypePassword != ''" class="m-0"> 
                                    <span class="showpwd" @click="formChangePassword.showRetypePass = !formChangePassword.showRetypePass">
                                        <span v-show="!formChangePassword.showRetypePass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="formChangePassword.showRetypePass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                    </p>
                                    <span class="form-group has-error has-feedback" v-if="$v.formChangePassword.retypePassword.$error">
                                        <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.sameAsNewPassword">{{ $t('password-setting.password_match') }}</label>
                                    </span>
                                   
                                </div>                                
                            </div>
                            <div class="fomr-group col-12 p-0 m-t-30">
                                <button
                                    class="btn cancel-color border-style btn-m"
                                    type="button"
                                    @click="cancelChange"
                                >戻る</button>
                                <button
                                    class="btn  recruiter-primary-color border-style ml-2 btn-m"
                                    type="submit"
                                    :disabled="$v.formChangePassword.$invalid"
                                    @click="changePassword"
                                >変更</button>
                               
                            </div>
                        </form>

                        
                    </div>
                </div>
            </div>
        </div>
         <div v-if="currentUser.role_id == 3">
             <div class="job-wrapper m-0">
                <h4 class="form-group row col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 header">{{ $t('password-setting.title') }}</h4>    
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                        <div class="new-job-block">
                             <form
                            class="form-signin searchform-one"
                            role="form"
                            v-show="!passwordAuthenticated"
                            @submit.prevent="authenticate"
                        >
                            <div class="form-group">
                                <label for="password">{{ $t('password-setting.enter_current_password') }}</label>
                                <div class="input-group col-12 p-0">
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        id="password"
                                        v-model.trim="$v.formLogin.password.$model"
                                        v-show="!showPass"
                                        @keyup="clearSuccess"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                        style="margin-left:-1px"
                                    />
                                    <!-- show password-->
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formLogin.password.$model"
                                        v-show="showPass"
                                        @keyup="clearSuccess"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                    />
                                   <p v-if="formLogin.password != ''"  class="m-0">
                                    <span class="showpwd job-showpwd" @click="showPass = !showPass">
                                        <span v-show="!showPass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                   </p>
                                    
                                </div>
                                <div class="form-group has-error has-feedback" v-if="$v.formLogin.password.$error">
                                    <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.required">{{ $t('password-setting.password_required') }}</label>
                                </div>
                                 <div>
                                    <div class="has-error has-feedback" v-if="errors"> 
                                        <span for="inputError2" class="control-label error">{{ $t('password-setting.password_wrong') }}</span>
                                    </div>
                                    <div class="has-success has-feedback" v-if="success">
                                        <span for="inputError2" class="control-label error">{{ $t('password-setting.password_changed') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="fomr-group text-center m-t-30">
                                <button class="btn job-primary-color border-style btn-m" type="submit">次へ</button>
                            </div>
                        </form>

                        <!-- Password change form -->
                        <form class="form-signin searchform-one" role="form" v-show="passwordAuthenticated" @submit.prevent>
                            <div class="form-group">
                                <label for="password">{{ $t('password-setting.enter_new_password') }}</label>
                                <div class="input-group col-12 p-0">
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.newPassword.$model"
                                        v-show="!formChangePassword.showNewPass"
                                        :class="{'input-error': $v.formChangePassword.newPassword.$error}"
                                        style="margin-left:-1px"                                    />
                                    <!-- show password-->
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.newPassword.$model"
                                        v-show="formChangePassword.showNewPass"
                                        :class="{'input-error': $v.formChangePassword.newPassword.$error}"
                                    />
                                    <p v-if="formChangePassword.newPassword != ''" class="m-0">
                                    <span class="showpwd job-showpwd" @click="formChangePassword.showNewPass = !formChangePassword.showNewPass">
                                        <span v-show="!formChangePassword.showNewPass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="formChangePassword.showNewPass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                    </p>
                                </div>
                                <div class="form-group has-error has-feedback" v-if="$v.formChangePassword.newPassword.$error">
                                    <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.required">{{ $t('password-setting.password_minlength') }}</label>
                                    <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.minLength">{{ $t('password-setting.password_minlength') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ $t('password-setting.enter_password_again') }}</label>
                                <div class="input-group col-12 p-0">
                                    <!-- hide password-->
                                    <input
                                        class="form-control input_pass m-l1"
                                        type="password"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.retypePassword.$model"
                                        v-show="!formChangePassword.showRetypePass"
                                        :class="{'input-error': $v.formChangePassword.retypePassword.$error}"
                                        style="margin-left:-1px"
                                    />
                                    <!-- show password-->
                                    <input
                                        class="form-control input_pass"
                                        type="text"
                                        placeholder="パスワード"
                                        v-model.trim="$v.formChangePassword.retypePassword.$model"
                                        v-show="formChangePassword.showRetypePass"
                                        :class="{'input-error': $v.formChangePassword.retypePassword.$error}"
                                    />
                                    <p v-if="formChangePassword.retypePassword != ''" class="m-0"> 
                                    <span class="showpwd job-showpwd" @click="formChangePassword.showRetypePass = !formChangePassword.showRetypePass">
                                        <span v-show="!formChangePassword.showRetypePass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span v-show="formChangePassword.showRetypePass" class="fa fa-fw fa-eye-slash"></span>
                                    </span>
                                    </p>
                                </div>
                                <div class="form-group has-error has-feedback" v-if="$v.formChangePassword.retypePassword.$error">
                                    <label for="inputError2" class="control-label error" v-if="!$v.formLogin.password.sameAsNewPassword">{{ $t('password-setting.password_match') }}</label>
                                </div>
                            </div>
                            <div class="fomr-group m-t-30">                                
                                <button
                                    class="btn cancel-color border-style btn-m"
                                    type="button"
                                    @click="cancelChange"
                                >戻る</button>
                                <button
                                    class="btn job-primary-color border-style btn-m ml-2"
                                    type="submit"
                                    :disabled="$v.formChangePassword.$invalid"
                                    @click="changePassword"
                                >変更</button>
                            </div>
                        </form>

                                     
                        </div>
                    </div>         
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { recruiterLogin,jobseekerLogin } from "../../partials/auth";
import { mapGetters } from "vuex";
import { required, minLength, sameAs } from "vuelidate/lib/validators";

export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
    mounted() {},
    data() {
        return {
            formLogin: {
                password: ""
            },
            formChangePassword: {
                newPassword: "",
                showNewPass: false,
                retypePassword: "",
                showRetypePass: false
            },
            showPass: false,
            isIcon: false,
            passwordAuthenticated: false,
            errors: false,
            success: null,
            url:"",
        };
    },
    validations: {
        formLogin: {
            password: {
                required,                
            }
		},
		formChangePassword: {
			newPassword: {
				required,
				minLength: minLength(8),
			},
			retypePassword: {
				sameAsNewPassword: sameAs('newPassword')
			}
		}
    },
    computed: {
        ...mapGetters(["currentUser"]),
        authError() {
            return this.$store.getters.authError;
        },
        showIco() {
                if( this.formLogin.password === '' ) {
                    this.isIcon = false;
                }
                else{
                    this.isIcon = true;
                }
            }   
    },
    methods: {
        authenticate: function() {
			const loginForm = this.$v.formLogin;
            loginForm.$touch();	
            if (!loginForm.$invalid) {

                // if(this.$store.getters.currentUser.role_id == 3) {
                //     var url = jobseekerLogin;
                // } 
                // if(this.$store.getters.currentUser.role_id == 2){
                //     var url = recruiterLogin;
                // }
                // url({
                //     email: this.currentUser.email,
                //     password: this.$data.formLogin.password
                // })
                this.$api.post('check-password', { 
					email: this.currentUser.email,
                    password: this.$data.formLogin.password
				}) 
                .then(() => {
                    this.passwordAuthenticated = true;
                    this.errors = null;
                })
                .catch(() => {
                    this.errors = "パスワードが間違っています";
                });
            }
        },
        changePassword: function() {
            if(this.$store.getters.currentUser.role_id == 3) {
                this.url = "/jobseeker/password-change";
            } 
            if(this.$store.getters.currentUser.role_id == 2) {
                this.url = "/recruiter/password-change";
            } 
            
			const formChangePassword = this.$v.formChangePassword;
			formChangePassword.$touch();
			if (!formChangePassword.$invalid) { 
				this.$api.post(this.url, { 
					password: this.$data.formChangePassword.newPassword 
				}) 
				.then((res) => {
                    this.$alertService.showSuccessDialog(null, this.$t('password-setting.password_changed'), this.$t('common.close'));
					//this.success = "パスワードが変更されました"; 
                    this.cancelChange();
                    this.$store.commit('logout');

                    if(res.data.data.role_id == 3) {
                        this.$router.push('/jobseeker/login');
                    }
                     if(res.data.data.role_id == 2) {
                        this.$router.push('/recruiter/login');
                    }

				})
				.catch(err => {
					let errObj = err.response.data.error.message;
					for (let key in errObj) {
						this.errors += errObj[key];
					}
				});
			}            
        },
        cancelChange: function() {
            this.formChangePassword.newPassword = "";
			this.formChangePassword.retypePassword = "";
			this.$v.formChangePassword.$reset();
            this.formLogin.password = "";
			this.passwordAuthenticated = false;
			this.$v.formLogin.$reset();
            this.errors = false;
        },
        clearSuccess: function() {
            this.success = null;
            this.errors = false;
        }
    }
};
</script>


<style scoped>
.btn.disabled, .btn:disabled { 
    opacity: 0.4;
    color: #333;
}
.btn.disabled:hover, .btn:disabled:hover {   
    cursor: not-allowed;
}
.content-wrap {
    background: #fff;
    padding: 30px;
    border: 1px solid #dedede;
    box-shadow: 1px 3px rgba(0, 0, 0, 0.1);
    -webkit-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0px 0px 5px 5px;
}
.form-box {
    border: 1px solid #C4C4C4;
    border-radius: 8px;
    padding: 22px;
}
.form-in {
    border-radius: 3px;
}

.has-success .control-label {
    color: #5da017;
}

.form-tit {
    border-left: 5px solid #84be3f;
    padding-left: 5px;
    margin-bottom: 15px; 
}
.showpwd {
    position: absolute;
    top: 11px;
    z-index: 1000;
    right: 20px;
}
.job-showpwd{
    top: 10px  !important;
}
@media screen and (max-width:767px){
	.content-wrap{
		padding: 15px;
    }
    .form-box{
        padding: 15px;
    }
    .btn-m {
        width: 124px;
        padding: 8px 5px;
    }  
}
@media (max-width:320px){
    .btn-m{
        width: 106px   
    }
}
</style>