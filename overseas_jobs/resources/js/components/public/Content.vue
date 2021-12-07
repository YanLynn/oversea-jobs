<template>
    <div class="register-wrapper content-wrapper" ref="progressContainer">
        <div class="signin-card-body ">
            <div class="form-content p-0 b-t">
            <a href="/" class="router-link-active no-href">
                <span class="icon-new-logo-txt logo-img main-color"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </a>
            <div class="register-block">
                <span v-if="currentPage != pages.activation_success && currentPage != pages.activation_fail && currentPage != pages.repeated_activation_block">
                   
                    <h1 v-if="currentPage == pages.registration" class="mb-5 form-signin-heading text-center main-color">お問い合わせフォーム</h1>
                    <h3 v-if="currentPage == pages.confirm" class="mb-5 form-signin-heading text-center main-color">お問い合わせ内容は以下のとおりでよいでしょうか。</h3>
                    <ul class="tab-list pc-767">
                        <li class="tab-item" :class="[{'active': currentPage == pages.registration}]">1.お問い合わせ入力</li>
                        <li class="tab-item" :class="[{'active': currentPage == pages.confirm}]">2.お問い合わせ確認</li>
                        <li class="tab-item" :class="[{'active': currentPage == pages.email_sent}]">3.送信完了</li>
                    </ul>
                    <!--mobile ui-->
                    <div class="mdl-card mdl-shadow--2dp sp-767">
                    <div class="mdl-card__supporting-text">
                        <div class="mdl-stepper-horizontal-alternative">
                        <div class="mdl-stepper-step active-step" :class="[{'active-step': currentPage == pages.registration}]">
                            <div class="mdl-stepper-circle"><span>1</span></div>
                            <div class="mdl-stepper-title">お問い合わせ入力</div>
                            <div class="mdl-stepper-bar-left"></div>
                            <div class="mdl-stepper-bar-right"></div>
                        </div>
                        <div class="mdl-stepper-step" :class="[{'active-step': currentPage == pages.confirm || currentPage == pages.email_sent}]">
                            <div class="mdl-stepper-circle"><span>2</span></div>
                            <div class="mdl-stepper-title">お問い合わせ確認</div>
                            <div class="mdl-stepper-bar-left"></div>
                            <div class="mdl-stepper-bar-right"></div>
                        </div>
                        <div class="mdl-stepper-step" :class="[{'active-step': currentPage == pages.email_sent}]">
                            <div class="mdl-stepper-circle"><span>3</span></div>
                            <div class="mdl-stepper-title">送信完了</div>      
                            <div class="mdl-stepper-bar-left"></div>
                            <div class="mdl-stepper-bar-right"></div>
                        </div>      
                        </div>
                    </div>
                    </div>
                    <!--end mobile ui-->
                </span>
                <!-- <div class="row form-row" v-show="currentPage == pages.registration || currentPage == pages.confirm"> -->
                <div class="mobile-xs">
                    <div class="row form-row" v-show="currentPage == pages.registration">
                            <form @submit.prevent="register" class="col-12 mobile-col">
                                <div class="mobile-bg">
                                <!-- Company Name -->
                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">企業団体名</label>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input type="text" name="corporate_name" class="form-control" v-model="formContent.corporate_name" placeholder="入力してください" />
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">お名前</label>
                                         <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input type="text" name="name" :class="['form-control',$v.formContent.name.$error?'is-invalid':'']" v-model.trim="$v.formContent.name.$model" placeholder="入力してください" />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formContent.name.required">お名前は必須です</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Furiga -->
                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">お名前(フリガナ)</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input type="text"
                                            name=""
                                            :class="['form-control',$v.formContent.furigana_name.$error?'is-invalid':'']"
                                            placeholder="全角カタカナで入力してください"
                                            v-model.trim="$v.formContent.furigana_name.$model"
                                        />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formContent.furigana_name.required">お名前(フリガナ)は必須です</div>
                                            <div class="error" v-if="!$v.formContent.furigana_name.isFurigana">全角カタカナで入力してください</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">メールアドレス</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input
                                            type="email"
                                            name="email"
                                            :class="['form-control',$v.formContent.email.$error?'is-invalid':'']"
                                            v-model.trim="$v.formContent.email.$model"
                                            placeholder="メールアドレス"
                                        />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formContent.email.required">メールアドレスは必須です</div>
                                            <div class="error" v-if="!$v.formContent.email.email">メールアドレスの形式が正しくありません</div>
                                            <!-- <div class="error" v-if="!$v.formContent.email.isUnique">このメールアドレスは、既に使われています</div> -->
                                        </div>
                                    </div>
                                </div>


                                 <!-- Email Address Confirm-->
                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">メールアドレス(確認)</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input
                                            type="email"
                                            name="email"
                                            :class="['form-control',$v.formContent.confirm_email.$error?'is-invalid':'']"
                                            v-model.trim="$v.formContent.confirm_email.$model"
                                            placeholder="メールアドレス(確認)"
                                        />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formContent.confirm_email.required">メールアドレス(確認)は必須です</div>
                                            <div class="error" v-if="!$v.formContent.confirm_email.email">※メールアドレス(確認)の形式が正しくありません</div>
                                            <!-- <div class="error" v-if="!$v.formContent.email.isUnique">メールアドレス(確認)は、すでに使われています</div> -->
                                            <div class="error" v-if="$v.formContent.confirm_email.email && !$v.formContent.confirm_email.samdAsEmail">メールアドレスが一致しません</div>
                        
                                        </div>
                                    </div>
                                </div>

                                <!-- Inquiry_details -->
                                <div class="form-group row align-items-top">
                                    <div class="col-md-5 col-lg-4">
                                        <label for="name">お問い合わせ内容詳細</label>
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <textarea name="inquiry_details" style="height:148px;" class="form-control" v-model="formContent.inquiry_details"  placeholder="入力してください" ></textarea>
                                    </div>
                                </div> 

                                 <!-- policy checkbox -->
                                <div class="form-group row align-items-center">
                                    <div class="col-md-5 col-lg-4">   
                                    </div>
                                    <div class="col-md-7 col-lg-8">
                                        <input type="checkbox" class="custom-control-input custom-checkbox" id="policy" @change="checkPolicy()" v-model="formContent.policy">
                                        <label class="custom-control-label custom-checkbox-label agree-check-label" for="policy">
                                        <a target="popup" class="link-href" onclick="window.open('/privacy-policy','popup','width=800,height=450'); return false;">プライバシーポリシー</a>に同意 </label>    <br>                                   
                                        <!-- <label class="custom-control-label custom-checkbox-label agree-check-label" for="policy"><a href="/privacy-policy" target="_blank">プライバシーポリシー</a>に同意 </label>  -->
                                       
                                        <span v-if="validated" class="error">プライバシーポリシーを確認してください</span>
                                         <span v-else class="error"> {{errors}} </span>
                                    </div>

                                   
                                   
                                </div> 
                                </div>
                                <!-- button -->
                                <div class="d-flex justify-content-center">
                                    <div class="form-group text-center mt-3 ml-5">
                                        <input type="submit" value="確認" class="btn register-btn main-bg-color btn-m border-style" @click="validate">
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                <!-- Confirm Area -->
                <div class="mobile-xs">
                    <div class="row vld-parent form-row" v-show="currentPage == pages.confirm" ref="progressContainer">
                        <div class="col-12 mobile-col">
                            <div class="mobile-bg">
                            <!--Corporate Name -->
                            <div class="form-group row align-items-center">
                                <div class="col-md-5 col-lg-4 confirm-label-block">
                                    <label for="name">企業団体名</label>                                   
                                </div>
                                <div class="col-md-7 col-lg-8 confirm-data-block">
                                    <p v-if="formContent.corporate_name">{{ formContent.corporate_name }}<span class="fa fa-check confirm-check sp-767"></span>  </p>
                                    <p v-else>-<span class="fa fa-check confirm-check sp-767"></span></p>   
                                </div>
                            </div>
                             <!-- Name -->
                            <div class="form-group row align-items-center">
                                <div class="col-md-5 col-lg-4 confirm-label-block">
                                    <label for="name">お名前</label>                                   
                                </div>
                                <div class="col-md-7 col-lg-8 confirm-data-block">
                                    <p type="text" name="name">{{ formContent.name }}<span class="fa fa-check confirm-check sp-767"></span> </p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <div class="col-md-5 col-lg-4 confirm-label-block">
                                    <label for="name">お名前(フリガナ)</label>                                  
                                </div>
                                <div class="col-md-7 col-lg-8 confirm-data-block">
                                     <p type="text" name="furigana_name">{{ formContent.furigana_name }} <span class="fa fa-check confirm-check sp-767"></span> </p>
                                </div>
                            </div>                            
                            <!-- Email Address -->
                            <div class="form-group row align-items-center">
                                <div class="col-md-5 col-lg-4 confirm-label-block">
                                    <label for="name">メールアドレス</label>                                    
                                </div>
                                <div class="col-md-7 col-lg-8 confirm-data-block">
                                    <p>{{ formContent.email }}<span class="fa fa-check confirm-check sp-767"></span>  </p>                                 
                                </div>
                            </div>
                            <!-- Email Address -->
                            <div class="form-group row">
                                <div class="col-md-5 col-lg-4 confirm-label-block answer-label">
                                    <label for="inquiry_details">お問い合わせ内容詳細</label>                                    
                                </div>
                                <div class="col-md-7 col-lg-8 confirm-data-block answer-data">
                                    <p v-if="formContent.inquiry_details" class="p-0">{{ formContent.inquiry_details }}<span class="fa fa-check confirm-check sp-767"></span>  </p>
                                    <p v-else>-<span class="fa fa-check confirm-check sp-767"></span> </p>                                     
                                </div>
                            </div>       
                             </div>                 
                            <div class="d-flex justify-content-center">
                            <div class="form-group text-center mt-3">
                                <input type="button" value="戻る" class="btn register-btn cancel-color btn-m border-style" @click="backRegister">
                            </div>
                            <div class="form-group text-center mt-3 ml-5">
                                <input type="submit" value="送信" class="btn register-btn main-bg-color btn-m border-style" @click="confirmRegistration">
                            </div>
                        </div> 
                        </div>  
                    </div>
                    </div>
                <!-- End Confirm Area -->

                <!-- Content Email Sent -->
                <div class="row form-row mobile-xs" v-show="currentPage == pages.email_sent">
                    <div class="col-12 text-center mobile-col">
                        <div class="mobile-bg">
                        <h3 class="pb-5 pt-5">お問い合わせありがとうございました。</h3>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-7">
                                <div class="form-group text-center mt-3">
                                    <a href="/" class="btn register-btn main-bg-color btn-m border-style">トップページへ</a>
                                    <!-- <input type="submit" value="トップページへ" class="btn register-btn job-primary-color btn-m border-style" @click="navigateToTop" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Email Sent -->

             
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";
    import Vuelidate from "vuelidate";
    import { required, minLength, maxLength, between, withParams, email, helpers, url, sameAs, numeric } from "vuelidate/lib/validators";

    const isFurigana = (value) => {
        let allow = true;
        let charArray = value.split("");
        for (let i = 0; i < charArray.length; i++) {
            let code = charArray[i].charCodeAt();
            if (!(code > 12448 && code < 12543)) {
                allow = false;
                break;
            }
        }
        return !helpers.req(value) || allow;
    };

    Vue.use(Vuelidate);

    export default {
        metaInfo: {
            title: 'お問い合わせフォーム｜海外就職・海外求人ならボーダレスワーキング',
            meta: [
            { name: 'description', content: 'お問い合わせフォームです。お気軽にお問い合わせください。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
        }, 
        mounted() {
			
			let activate = this.$route.query.activate;
            if (activate == "success") {
                this.currentPage = this.pages.activation_success;
            } else if (activate == "fail") {
                this.currentPage = this.pages.activation_fail;
                this.resend_mail = this.$route.query.email;
            } else if (activate == "activated") {
                this.currentPage = this.pages.repeated_activation_block;
            }
        },
        data() {
            return {
                policy_check: false,
                countries: [],
                city:[],
                selectedValue:0,                
                pages: {
                    registration: "registration",
                    confirm: "confirm",
                    email_sent: "email_sent",
                    activation_success: "activation_success",
                    activation_fail: "activation_fail",
                    repeated_activation_block: "repeated_activation_block",
                },
                currentPage: "registration",
                formContent: {
                    corporate_name:"",
                    name: "",
                    furigana_name: "",
                    email: "",
                    confirm_email :"",
                    inquiry_details:"",
                    subject:"",
                    policy:false,
            
                },
                resend_mail: "",
                disabled: 0,
                errors:'',
               
            };
        },

        validations: {
            formContent: {
                name: {
                    required,
                },
                furigana_name: {
                    required,
                    isFurigana,
                },
      
                email: {
                    required,
                    email,              
                },

                confirm_email: {
                    required,
                    email,
                    samdAsEmail:sameAs("email"),
                },
               
             
            },
        },
        created() {

		
			if(this.$route.path == '/query/confirm')
			{

				// this.disabled = 1;
				this.currentPage = this.pages.confirm;
				const dataStorage =  localStorage.getItem('formContent');
				if(dataStorage != null){
                    this.formContent = JSON.parse(localStorage.getItem('formContent')) ;                     
				}
				else if(dataStorage == null){
					this.$router.push('/query');
					this.currentPage = 'registration';
				}	
			}
			else if(this.$route.path == '/query/success'){
				
				this.currentPage = this.pages.email_sent;
			}
			else if(this.$route.path == '/query'){
				this.currentPage = "registration";
			}
			
        },

        methods: {
             validate() {
             this.policy_check = true
            },
            checkPolicy(){
               if(this.formContent.policy){
                   this.errors = '';
               }
               else{
                   this.errors = 'プライバシーポリシーを確認してください';
               }
            },
            register() {
            
                this.$v.formContent.$touch();
                if (this.$v.formContent.$invalid) {
                    return;
                }
               
              console.log('aaaa');
                if(!this.formContent.policy)
                {
                    this.errors = 'プライバシーポリシーを確認してください';
                }
                if(this.errors == '')
                {
                    // this.disabled = 1;
                    localStorage.setItem('formContent',JSON.stringify(this.formContent));
                    this.currentPage = this.pages.confirm; // load confirm page
                    console.log("reg",this.formContent)
                    this.$router.push('/query/confirm');

                }
				
            },
            backRegister() {
                // this.disabled = 0;
                this.currentPage = this.pages.registration;
                this.$router.push('/query');
            },

            confirmRegistration() {
				let loader = this.$loading.show();	
                setTimeout(() => {
                    this.$api.post("/query", this.formContent).then((res) => {
                            localStorage.removeItem('formContent');
                            this.$router.push('/query/success');
                            loader.hide();
                            this.currentPage = this.pages.email_sent; //-- load email_sent page
                            $('.first-tab').removeClass('active');
                            $('.second-tab').removeClass('active');
                            $('.third-tab').addClass('active');
                            
                        })
                        .catch((err) => {
                            this.$store.commit("registerFailed", { err });
                        });
                    }, 5000);
            },
             navigateToTop() {
                this.$router.push({ path: "/" });
            },
          

        },
         computed: {
            validated() {
            return this.policy_check && !this.formContent.policy
            }
  },
    };
</script>

<style lang="scss" scoped>  
.b-t{
    border-top:3px solid #4c76c2;
}
.answer-data{
    padding: 10px 15px;
}
.sp-767{
    display: none;
}
.pc-767{
    display: flex;
}
.signin-card-body {
    padding: 40px 0;
    min-height: 100vh;
}  
.signin-card-body .form-content .form-group label {
    color: #666666;
    padding: 10px 0px 10px 0px;
    line-height: 1.5;
    margin-bottom: 0px;        
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
.tab-list {
    display: flex;
    width: 80%;
    margin: 0 auto 50px;
}
.tab-item {
    position: relative;
    list-style: none;
    width: 33.3%;
    background: #b3d5f1;
    padding: 15px 20px;
    border-radius: 40px;
    margin: 0 2%;
    text-align: center;
    box-shadow: 0 0.2rem 0.7rem rgba(0, 0, 0, 0.15);
    color: #4c76c2;
    font-weight: bold;
}
.tab-item:after {
    position: absolute;
    content: "";
    width: 100px;
    height: 3px;
    background: #4c76c2;
    top: 50%;
    right: -97px;
}
.tab-item:last-child:after {
    width: 0;
}
.tab-item.active {
    background: #4c76c2;
    color: #fff;
}  
.register-btn{
    width: 150px;
    // line-height: 2;
    border-radius: 25px !important;
}    
.required{           
    margin-top: 12px;
    margin-right: 0px;
}
.agree-check-label{
    padding-top: 0px !important;
    padding-left: 30px !important;
    line-height: 20px !important;
}
//for confirm state (UI)
.confirm-label-block{
    background: #4c76c2;
    color: #fff;
    border: 1px solid #4c76c2;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;    
    label{
       color:#fff !important;
       font-size: 14px;
    }
}
.confirm-data-block{
    background: #dbe9f5;
    border: 1px solid #dbe9f5;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    overflow-wrap: break-word; 
    p{
        padding: 10px 0px 10px 0px;
        line-height: 1.5;
        word-break: break-word;
        margin: 0!important;
    } 
}
.link-href{
    color:#007bff;
    text-decoration:underline;
    cursor:pointer
}
//zkm 
@media screen and (max-width: 320px){
    .mdl-stepper-title {
    margin-top: 16px;
    font-size: 12px !important;
    font-weight: normal;
}
.mobile-bg{
    background-color: #fff;
    padding: 10px;
    h1{
       font-size: 1.7rem;
    }
    h3{
        font-size: 1.0rem !important;
    }
    .h5, h5{
        font-size: 1rem;
    }
}
}
@media screen and (max-width:767px) { 
.b-t{
    border-top: none;
}
.sp-767{
    display:block;
}
.pc-767{
    display: none;
}
.mdl-card {
  width:100%;
  min-height: 0;
  margin: 10px auto;
}

.mdl-card__supporting-text {
  width: 100%;
  padding: 0;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step {
  width: 33.33%;
  /* 100 / no_of_steps */
}

/* stepper css styles */

.mdl-stepper-horizontal-alternative {
  display: table;
  width: 100%;
  margin: 0 auto;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step {
  display: table-cell;
  position: relative;
  padding: 24px 0px;
 text-align: center;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step:active {
  background-color: inherit;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step:active {
  border-radius: 15% / 75%;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step:first-child:active {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step:last-child:active {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-circle {
    background-color: #4c76c2;
    color: #fff;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step:first-child .mdl-stepper-bar-left,
.mdl-stepper-horizontal-alternative .mdl-stepper-step:last-child .mdl-stepper-bar-right {
  display: none;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-circle {
  width: 30px;
  height: 30px;
  margin: 0 auto;
  background-color: #bfdaf1;
  border-radius: 50%;
  text-align: center;
  line-height: 30px;
  font-size: 12px;
  color: #4c76c2;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-circle {
    background-color: #4c76c2;
    color: #fff;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.step-done .mdl-stepper-circle:before {
  content: "\2714";
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.step-done .mdl-stepper-circle *,
.mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle * {
  display: none;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle {
  -moz-transform: scaleX(-1);
  /* Gecko */
  -o-transform: scaleX(-1);
  /* Opera */
  -webkit-transform: scaleX(-1);
  /* Webkit */
  transform: scaleX(-1);
  /* Standard */
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle:before {
  content: "\270E";
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-title {
  margin-top: 16px;
  font-size: 14px;
  font-weight: normal;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-title,
.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-optional {
  text-align: center;
  color: rgba(0, 0, 0, .26);
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-title {
  font-weight: 500;
  color: rgb(96 133 163);
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step.step-done .mdl-stepper-title,
.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step.editable-step .mdl-stepper-title {
  font-weight: 300;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-optional {
  font-size: 12px;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-optional {
  color: rgba(0, 0, 0, .54);
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-left,
.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-right {
  position: absolute;
  top: 36px;
  height: 1px;
  border-top: 1px solid #BDBDBD;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-right {
  right: 0;
  left: 50%;
  margin-left: 20px;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-left {
  left: 0;
  right: 50%;
  margin-right: 20px;
}
//register
.form-content .form-group label {
    font-size: 14px;
    color: #4c76c2;
}
.form-control{
    background: #f4faff;
}
.logo-img{
    margin:0px;
}
.signin-card-body{
    padding: 40px 0;   
    height: 100%;
}
.form-signin-heading{
    margin-bottom: 0px !important;
}
.form-content{
    border: none;
    width: 100%;
    background: inherit;
    box-shadow: none;
}
.register-wrapper {
    background-color: #f8f8f8;
    width: 100%;
    overflow: hidden;
}
.register-block{
   background: inherit;
    border: none;
    box-shadow: none;
    margin: 0px;
    padding: 30px 0px 0px;
    width: 100%;
}
.register-wrapper .form-row{
    width: 100%;
}
.mobile-xs .mobile-col {
    padding-left: 0px !important;
    padding-right:0px !important;
}
.mobile-bg{
    background-color: #fff;
    padding: 10px;
    h1{
       font-size: 1.7rem;
    }
    h3{
        font-size: 1.1rem;
    }
    .h5, h5{
        font-size: 1rem;
    }
}
.mb-res{
    margin-bottom: 10px !important;
}
.m-0-res{
    margin-left:0px !important;
    margin-right:0px !important;
}
.mt-4-res{
    margin-top: 1.5rem !important;
}
.confirm-label-block{
    background:none;
    border:none;
    label{
        color: #666666 !important;
    }
}
.confirm-data-block{
    background: inherit;
    border: none;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;   
    p{
        line-height: 1.5;
        margin: 0 !important;
        position: relative;
        margin-bottom: 0px;
        padding: 0px;
        background-color: #fff;
        border-bottom: 1px solid #4c76c2;
        width: 100%;      
        border-radius: 0px !important;
        font-size: 16px;
        box-sizing: border-box;
        padding-bottom: 0px;
        padding-top: 0px;
        color: #495057;
    }
}
.confirm-check{
    float: right;
    line-height: 1.5;
    font-size: 14px;
}
.fa-check:before{
    color: #4c76c2;
    
}
.confirm-row{
    .register-btn{
        width: 110px;
    }
}
.register-btn{
    width: 135px;
}
.ml-5{
    margin-left: 10px !important;
}
}
//zkm
</style>
