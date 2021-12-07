<template>
    <div class="user-main">
         <div v-if="currentUser.role_id == 2">
            <h4 class="user-head-tit recruiter-primarylight-color">{{ $t('deactivate.title') }}</h4>
            <div class="content-wrap">
                <div class="form-box">
                    <label class="label-txt">{{ $t('deactivate.enter_password') }}</label>
                    <div class="form-in searchform-one">
                        <form class="form-signin" role="form"  @submit.prevent="authenticate" >
                            <div class="form-group col-12 col-sm-6 col-xl-5 p-0 m-0">
                                <div class="input-group">
                                    <input 
                                        class="form-control input_pass m-l1" 
                                        type="password" 
                                        :placeholder="$t('deactivate.password')" 
                                        id="password"
                                        v-model.trim="$v.formLogin.password.$model"
                                        v-if="!showPass"
                                        @keyup="clearSuccess"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                    />
                                    
                                       <input 
                                        class="form-control input_pass m-l1" 
                                        type="text" 
                                        :placeholder="$t('deactivate.password')" 
                                        id="password"
                                        v-show="showPass"
                                        style="width: 314px !important;"
                                        v-model.trim="$v.formLogin.password.$model"
                                        :class="{'input-error': $v.formLogin.password.$error}"
                                    />
                                    <div v-show="isIcon">
                                        {{showIco}}
                                        <span class="showpwd" @click="showPass = !showPass">
                                            <span v-show="!showPass" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                                            <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                        </span>
                                    </div>
                                    
                                    
                                </div>
                                <div class="has-error has-feedback" v-if="$v.formLogin.password.$error">
                                        <label for="inputError2" class="error" v-if="!$v.formLogin.password.required">{{ $t('deactivate.password_required') }}</label>
                                    </div>
                                 <div class="form-group has-error has-feedback" v-if="errors">
                                    <label for="inputError2" class="control-label error">{{ $t('deactivate.password_wrong') }}</label>
                                </div>
                                <div class="fomr-group m-t-30">
                                    <button class="btn recruiter-primary-color border-style btn-m" type="submit">
                                    {{ $t('deactivate.button') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div>
               
                <!-- <div class="form-group has-success has-feedback" v-if="success">
                    <label for="inputError2" class="control-label">{{ success }}</label>
                </div> -->
            </div>
        </div>
        <div v-if="currentUser.role_id == 3">
             <div class="job-wrapper m-0">
                <h4 class="form-group row col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 header">{{ $t('deactivate.title') }}</h4>    
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                        <div class="new-job-block searchform-one">
                            <form role="form"  @submit.prevent="authenticate">
                                <div class="form-group mb-0">
                                    <label for="password">{{ $t('deactivate.enter_password') }}</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control input_pass m-l1"
                                            type="password"
                                            :placeholder="$t('deactivate.password')"
                                            id="password"
                                            v-model.trim="$v.formLogin.password.$model"
                                            v-if="!showPass"
                                            @keyup="clearSuccess"
                                            :class="{'input-error': $v.formLogin.password.$error}"
                                        />
                                    </div>
                                    
                                        <input type="text" v-model="formLogin.password" v-show="showPass"  class="form-control input_pass m-l1"> 
                                        <div v-show="isIcon">
                                        {{showIco}}
                                        <span class="showpwd showpwd-1" @click="showPass = !showPass">
                                            <span v-show="!showPass" class="fa fa-fw fa-eye field-icon toggle-password"> </span>
                                            <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                        </span>
                                    </div>
                                    <div class="has-error has-feedback mb-0" v-if="$v.formLogin.password.$error">
                                        <label for="inputError2" class="error" v-if="!$v.formLogin.password.required">{{ $t('deactivate.password_required') }}</label>
                                    </div>
                                     <div class="has-error has-feedback" v-if="errors">
                                        <label for="inputError2" class="error">{{ $t('deactivate.password_wrong') }}</label>
                                    </div>
                                    <div class="fomr-group text-center m-t-30">
                                    <button class="btn job-primary-color border-style btn-m" type="submit">
                                    {{ $t('deactivate.button') }}
                                    </button>
                                    </div>
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
import { required } from "vuelidate/lib/validators";

export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
    data() {
        return {
            showPass: false, 
            isIcon: false, 
            formLogin: {
                password: ""
            },
            url:'',
            passwordAuthenticated: false,
            showPass: false,
            errors: null
        }
    },
    validations: {
        formLogin: {
            password: {
                required,                
            }
        },
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
                    this.deactivate();
				})
				.catch(() => {
					this.errors = "パスワードが間違っています";
				});
            }
        },
        deactivate: function() {
            if(this.$store.getters.currentUser.role_id == 3) {
                this.url = "/jobseeker/deactivate";
            } 
            if(this.$store.getters.currentUser.role_id == 2) {
                this.url = "/recruiter/deactivate";
            } 

            this.$alertService.showConfirmDialog(null,this.$t('alertMessage.deactivate_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                if(dialogResult.value){
                    this.$api.post(this.url, { id: this.$store.getters.currentUser.id })
                    .then((res) => {
                        this.$alertService.showSuccessDialog(null, this.$t('alertMessage.deactivate_success'), this.$t('alertMessage.close'));
                        this.$store.commit('logout');
						socket.disconnect();
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
                
            })

            // if (confirm("Are you sure?")) {
                
            // }      
        },
        clearSuccess: function() {
            this.success = null;
            this.errors = null;
        }
  }
};
</script>

<style scoped>
.deactivatebtn {
    /* background-color: #004bb1; */
    background-color: rosybrown;
    color: #fff;
    width: 120px;
    padding: 10px 0;
    border-radius: 20px;
    margin-right: 20px;
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

  .showpwd {
        position: absolute;
        top: 11px;
        z-index: 1000;
        right: 10px;
      }
 .showpwd-1{
     top: 55px !important;
     right: 28px !important;
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

</style>
