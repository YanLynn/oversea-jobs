<template>
    <div class="register-wrapper" ref="progressContainer">
        <div class="signin-card-body ">
            <div class="form-content p-0">
            <a href="/" class="router-link-active no-href">                         
             <span class="icon-new-logo-txt logo-img job-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </a>
            <div class="register-block">
                <span v-if="currentPage != pages.activation_success && currentPage != pages.activation_fail && currentPage != pages.repeated_activation_block">
                    <h1 class="form-signin-heading text-center job-primary-color-txt">求職者会員新規登録</h1>
                    <ul class="tab-list pc-767">
                        <li class="tab-item" :class="[{'active': currentPage == pages.registration}]">1.会員情報の入力</li>
                        <li class="tab-item" :class="[{'active': currentPage == pages.confirm}]">2.入力内容の確認</li>
                        <li class="tab-item" :class="[{'active': currentPage == pages.email_sent}]">3.送信完了</li>
                    </ul>
                    <!--mobile ui-->
                    <div class="mdl-card mdl-shadow--2dp sp-767">
                    <div class="mdl-card__supporting-text">
                        <div class="mdl-stepper-horizontal-alternative">
                        <div class="mdl-stepper-step active-step" :class="[{'active-step': currentPage == pages.registration}]">
                            <div class="mdl-stepper-circle"><span>1</span></div>
                            <div class="mdl-stepper-title">会員情報の入力</div>
                            <div class="mdl-stepper-bar-left"></div>
                            <div class="mdl-stepper-bar-right"></div>
                        </div>
                        <div class="mdl-stepper-step" :class="[{'active-step': currentPage == pages.confirm || currentPage == pages.email_sent}]">
                            <div class="mdl-stepper-circle"><span>2</span></div>
                            <div class="mdl-stepper-title">入力内容の確認</div>
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
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">名前</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="name" :class="['form-control mb-0',$v.formRegister.name.$error?'is-invalid':'']" v-model.trim="$v.formRegister.name.$model" placeholder="名前" />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.name.required">名前は必須です</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Contact Furiga -->
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">名前(フリガナ)</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input
                                            type="text"
                                            name=""
                                            :class="['form-control mb-0',$v.formRegister.jobseeker_furigana_name.$error?'is-invalid':'']"
                                            placeholder="フリガナ"
                                            v-model.trim="$v.formRegister.jobseeker_furigana_name.$model"
                                        />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.jobseeker_furigana_name.required">名前（フリガナ）は必須です</div>
                                            <div class="error" v-if="!$v.formRegister.jobseeker_furigana_name.isFurigana">全角カタカナで入力してください</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">生年月日</label>
                                    </div>                                    
                                    <div class="col-md-8">
                                        <input type="text" class="form-control mb-0 col-md-5" placeholder="生年月日" v-model="formRegister.dob"/>         
                                    <!-- <div class="col-md-8 row col-12 m-0 p-0">
                                    </div> -->
                                        <!-- <date-picker :lang="lang" placeholder="年 - 月 - 日" valueType="format" v-model="formRegister.dob" class="w-100"></date-picker> -->
                                        <!-- date -->
                                        <!-- <div class="col-md-4">
											<select class="form-control" v-model="dob.year">
												<option disabled :value="null"> 年 </option>
												<option v-for="year in 100" :key="year">{{ (new Date().getFullYear() + 1) - year }}{{' 年'}}</option>
											</select>
										</div>
										<div class="col-md-4">
											<select class="form-control" v-model="dob.month">
												<option disabled :value="null"> 月 </option>
												<option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
											</select>
										</div>
										<div class="col-md-4">
											<select class="form-control" v-model="dob.day"> 
											<option disabled :value="null"> 日 </option>
											<option v-for="day in day_check" :key="day">{{ day }}{{' 日'}}</option>
											</select>
										</div> -->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">現住所</label>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <input type="text" :disabled="disabled == 1" class="form-control" placeholder="country" v-model.trim="formRegister.address" /> -->
                                        <select v-model="formRegister.country_name"  @change="getCity(formRegister.country_name)" class="form-control mb-0 mb-res qa-select">
                                            <option value="0" selected>国・地域を選択</option>
                                            <option v-for="country in countries" :key="country.id" v-bind:value="country.country_name">
                                                    {{country.country_name}}
                                                </option>
                                            </select>
                                        </div>

                                    <div class="col-md-4">
                                        <select v-model="formRegister.country_id" @change="storedCityName" class="form-control mb-0 qa-select" >
                                            <option value="0" selected>都市を選択</option>
                                            <option v-for="c_name in city" :key="c_name.id" v-bind:value="c_name.id">
                                                {{c_name.city_name}}
                                            </option>
                                        </select>
                                    </div>                                   
                                     <div class="offset-md-4 col-md-8 info">※ 日本以外を選択した場合、都市は選択できません。</div>
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">電話番号</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="phone" :class="['form-control mb-0',$v.formRegister.phone.$error?'is-invalid':'']" placeholder="電話番号" v-model.trim="$v.formRegister.phone.$model" />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.phone.required">電話番号は必須です</div>
                                            <div class="error" v-else-if="!$v.formRegister.phone.alphaNumAndValidator">半角数字+-スペースを使って入力してください</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Email Address -->
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">メールアドレス</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input
                                            type="email"
                                            name="email"
                                            :class="['form-control mb-0',$v.formRegister.email.$error?'is-invalid':'']"
                                            v-model.lazy="$v.formRegister.email.$model"
                                            placeholder="メールアドレス"
                                        />
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.email.required">メールアドレスは必須です</div>
                                            <div class="error" v-if="!$v.formRegister.email.email">メールアドレスの形式が正しくありません</div>
                                            <div class="error" v-if="!$v.formRegister.email.isUnique">このメールアドレスは、既に使われています</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">パスワード</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">                                       
                                        <!-- hide password-->                   
                                            <input :class="['form-control mb-0 input_pass m-l1', $v.formRegister.password.$error ? 'is-invalid' : '']" v-model="$v.formRegister.password.$model" type="password" placeholder="パスワード" id="password"  v-show="!showPass" />
                                        
                                        <!-- show password-->
                                        <input class="form-control mb-0 input_pass" type="text" placeholder="パスワード" v-model="formRegister.password" v-show="showPass" />                   
                                        <p v-show="isIcon">
                                            {{showPwd}}
                                            <span class="showpwd" @click="showPass = !showPass">
                                                <span v-show="!showPass"  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                            </span>   
                                        </p>                                           
                                      
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.password.required">パスワードは必須です</div>
                                            <div class="error" v-if="!$v.formRegister.password.minLength">パスワードは8文字以上必要です</div>
                                        </div>
                                    </div>
                                </div> 

                                <!-- Confrim Password -->
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="name">パスワード確認</label>
                                        <span class="required">必須</span>
                                    </div>
                                    <div class="col-md-8">
                                        <!-- <input
                                            type="password"
                                            name=""
                                            :class="['form-control',$v.formRegister.password_confirmation.$error?'is-invalid':'']"
                                            placeholder="パスワード確認"
                                            v-model.trim="$v.formRegister.password_confirmation.$model"
                                        /> -->
                                        <!-- hide password-->                   
                                            <input :class="['form-control mb-0 input_pass m-l1', $v.formRegister.password_confirmation.$error ? 'is-invalid' : '']" v-model="$v.formRegister.password_confirmation.$model" type="password" placeholder="パスワード" id="password"  v-show="!showPasstwo" />
                                        
                                        <!-- show password-->
                                        <input class="form-control mb-0 input_pass" type="text" placeholder="パスワード" v-model="formRegister.password_confirmation" v-show="showPasstwo" />                   
                                        <p v-show="isIcontwo">
                                            <span class="showpwd" @click="showPasstwo = !showPasstwo">
                                                <span v-show="!showPasstwo"  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                <span v-show="showPasstwo" class="fa fa-fw fa-eye-slash"></span>
                                            </span>   
                                        </p>      
                                        <div class="invalid-feedback">
                                            <div class="error" v-if="!$v.formRegister.password_confirmation.required">パスワードが一致しません</div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                                <div class="d-flex justify-content-center">
                                        <div class="form-group text-center mt-3">
                                            <input type="submit" value="確認" class="btn register-btn job-primary-color border-style">
                                        </div>
                                </div>
                                <!-- <div class="d-flex justify-content-center">
                                    <div class="form-group text-center mt-3 ml-5" v-if="currentPage == pages.registration">
                                        <input type="submit" value="確認" class="btn register-btn job-primary-colorbtn-m border-style" />
                                    </div>
                                    <div class="form-group text-center mt-3" v-if="currentPage == pages.confirm">
                                        <input type="button" value="戻る" class="btn register-btn job-primary-colorbtn-m border-style" @click="backRegister()" />
                                    </div>
                                    <div class="form-group text-center mt-3 ml-5" v-if="currentPage == pages.confirm">
                                        <input type="submit" value="送信" class="btn register-btn job-primary-colorbtn-m border-style" @click="confirmRegistration" />
                                    </div>
                                </div> -->
                            </form>
                       
                    </div>
                  </div>
                <!-- Confirm Area -->
                    <div class="mobile-xs">
                    <div class="row vld-parent form-row confirm-row" v-show="currentPage == pages.confirm" ref="progressContainer">                   
                        <div class="col-12 mobile-col">
                         <div class="mobile-bg">
                            <!-- Name -->
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">名前</label>                                   
                                </div>
                                <div class="col-md-8 confirm-data-block">
                                    <p type="text" name="name">{{ formRegister.name }}<span class="fa fa-check confirm-check sp-767"></span> </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">名前(フリガナ)</label>                                  
                                </div>
                                <div class="col-md-8 confirm-data-block">
                                    <p v-if="formRegister.jobseeker_furigana_name">{{ formRegister.jobseeker_furigana_name }}<span class="fa fa-check confirm-check sp-767"></span> </p>
                                    <p v-else>-</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">生年月日</label>
                                </div>
                                <div class="col-md-8 confirm-data-block">
                                    <p  v-if="formRegister.dob">{{formRegister.dob}}<span class="fa fa-check confirm-check sp-767"></span></p>
                                    <!-- <p v-if="formRegister.dob">{{ formRegister.dob }} </p>      -->
                                    <p v-else>-<span class="fa fa-check confirm-check sp-767"></span></p>    
                                </div>
                            </div>
                            <!-- Contact Name -->
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block confirm-address-lbl-block">
                                    <label for="name">現住所</label>
                                </div>
                                <div class="col-md-8 confirm-data-block confirm-address-block">
                                    <p v-if="formRegister.country_name != 0">
                                        {{formRegister.country_name}} <span v-if="formRegister.city_name != null && formRegister.city_name != ''">, {{formRegister.city_name}}</span><span class="fa fa-check confirm-check sp-767"></span>
                                    </p>
                                    <p v-else>-<span class="fa fa-check confirm-check sp-767"></span></p>
                                    <!-- <span v-if="selectedValue != 0">{{ selectedValue }} ,</span>
                                    <p v-else>-</p>
                                    <span v-for="city in cityList" :key="city.id" v-bind:value="city.id">
                                        <span >
                                            {{city.city_name}}
                                        </span>                                        
                                    </span> -->
                                </div>
                            </div>        
                            <!-- Phone Number -->
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">電話番号</label>                                   
                                </div>
                                <div class="col-md-8 confirm-data-block">
                                    <p v-if="formRegister.phone">{{ formRegister.phone }}<span class="fa fa-check confirm-check sp-767"></span> </p>
                                    <p v-else>-</p> 
                                </div>
                            </div>
                            <!-- Email Address -->
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">ご担当者様メールアドレス</label>                                    
                                </div>
                                <div class="col-md-8 confirm-data-block">
                                    <p v-if="formRegister.email">{{ formRegister.email }} <span class="fa fa-check confirm-check sp-767"></span></p>
                                    <p v-else>-</p>                                     
                                </div>
                            </div>   
                            <!-- Password -->
                            <div class="form-group row">
                                <div class="col-md-4 confirm-label-block">
                                    <label for="name">パスワード</label>                                   
                                </div>
                                <div class="col-md-8 confirm-data-block pc-767">
                                    <p class="star-pwd" v-for="pass in formRegister.password.length" :key="pass">*</p>
                                </div>
                                <div class="col-md-8 confirm-data-block sp-767">
                                    <p style="height:20px;"><span v-for="pass in formRegister.password.length" :key="pass">*</span><span class="fa fa-check confirm-check sp-767"></span></p>
                                </div>
                            </div>                             
                        </div>  
                        </div>                     
                        <div class="col-12 d-flex justify-content-center">
                            <div class="form-group text-center mt-3">
                                <input type="button" value="戻る" class="btn register-btn cancel-color border-style" @click="backRegister">
                            </div>
                            <div class="form-group text-center mt-3 ml-5">
                                <input type="submit" value="送信" class="btn register-btn job-primary-color border-style" @click="confirmRegistration">
                            </div>
                        </div>
                    </div>
                    </div>
                <!-- End Confirm Area -->

                <!-- Registration Email Sent -->
                <div class="row form-row w-100 mobile-xs" v-show="currentPage == pages.email_sent">
                    <div class="col-12 text-center mobile-col">
                     <div class="mobile-bg">
                        <h1 class="pb-4 mt-4-res">ボーダレスワーキング</h1>
                        <h3 class="pb-5">この度は求職者会員としてご登録いただきありがとうございます</h3>
                        <div class="row justify-content-center m-0-res">
                            <div class="col-12 col-lg-8 p-0">
                                <p class="h5 pb-5">
                                    現在、仮登録の状態です。ご入力いただいたメールアドレス宛にご本人様確認用のメールをお送りいたしました。 メール本文内のURLをクリックすると、会員登録完了となります。
                                </p>                               
                            </div>
                        </div>
                     </div>
                      <div class="form-group text-center mt-3">
                            <a href="/" class="btn register-btn job-primary-color border-style">トップページへ</a>
                            <!-- <input type="submit" value="トップページへ" class="btn register-btn job-primary-color btn-m border-style" @click="navigateToTop" /> -->
                        </div>
                    </div>
                </div>
                <!-- End Email Sent -->
                <!-- Activation Account Success -->
                <div class="row form-row mobile-xs" v-show="currentPage == pages.activation_success">
                    <div class="col-12 text-center mobile-col">
                     <div class="mobile-bg">
                        <h1 class="pb-4 mt-4-res">ボーダレスワーキング</h1>
                        <p class="h5 pb-5">求職者会員登録が完了いたしました。</p>
                     </div>
                        <div class="form-group text-center mt-3">
                            <a href="/" class="btn job-primary-color btn-m border-style">トップページへ</a>
                            <!-- <input type="submit" value="トップページへ" class="btn job-primary-color btn-m border-style" @click="navigateToTop" /> -->
                        </div>
                    </div>
                </div>
                <!-- End Account Success -->

                <!-- Activation Account Fail -->
                <div class="row form-row mobile-xs" v-show="currentPage == pages.activation_fail">
                    <div class="col-12 text-center mobile-col">
                     <div class="mobile-bg">
                        <h1 class="pb-4 mt-4-res">ボーダレスワーキング</h1>
                        <p class="h5 pb-5">リンクの有効期限が切れています。</p>
                     </div>
                        <div class="form-group text-center mt-3">
                            <a href="/" class="btn job-primary-color btn-m border-style">トップページへ</a>
                            <!-- <button class="btn btn-dark btn-m border-style" @click="navigateToTop">トップページへ</button> -->
                            <!-- <button class="btn btn-primary searchbtn btn-m border-style" style="margin-bottom:0px" @click="resendEmail">再送メール</button> -->
                        </div>
                    </div>
                </div>
                <!-- End Activation Account Fail -->
                <!-- Block Repeatly Send  Email  -->
                <div class="row form-row mobile-xs" v-show="currentPage == pages.repeated_activation_block">
                    <div class="col-12 text-center mobile-col">
                     <div class="mobile-bg">
                        <h1 class="pb-4 mt-4-res">ボーダレスワーキング</h1>
                        <p class="h5 pb-5">求職者会員としてすでに登録済みです。</p>
                     </div>
                        <div class="form-group text-center mt-3">
                            <a href="/" class="btn job-primary-color btn-m border-style">トップページへ</a>
                            <!-- <button class="btn btn-success btn-m borde-style" @click="navigateToTop">トップページへ</button> -->
                        </div>
                    </div>
                </div>
                <!-- End Block Repeatly Send Resend Email  -->
                
            </div>            
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";
    import Vuelidate from "vuelidate";
    import { required, minLength, between, withParams, email, helpers, url, sameAs, numeric } from "vuelidate/lib/validators";

  const alphaNumAndValidator = (ph_number) => {
  if (typeof ph_number === 'undefined' || ph_number === null || ph_number === '') {
    return true;
    }
    return (/^[\+\d]?(?:[\+\][\d-.\s()]*)$/m).test(ph_number);
    }
    
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
            title: '求職者会員新規登録｜海外就職・海外求人ならボーダレスワーキング',
            meta: [
                { name: 'description', content: '求職者会員新規登録のページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
            ]
        },
        mounted() {
			
			let activate = this.$route.query.activate;
            if (activate == "success") {
                this.currentPage = this.pages.activation_success;
            } else if (activate == "fail") {
                this.currentPage = this.pages.activation_fail;
                this.resend_mail = this.$route.query.email;
                this.registered_id = this.$route.query.registeredid;
            } else if (activate == "activated") {
                this.currentPage = this.pages.repeated_activation_block;
            }
        },
        data() {
            return {
                countries: [],
                city:[],
                selectedValue:0,    
                dob: {
					year: null,
					month: null,
					day: null,
				},            
                pages: {
                    registration: "registration",
                    confirm: "confirm",
                    email_sent: "email_sent",
                    activation_success: "activation_success",
                    activation_fail: "activation_fail",
                    repeated_activation_block: "repeated_activation_block",
                },
                currentPage: "registration",
                formRegister: {
                    name: "",
                    jobseeker_furigana_name: "",
                    dob: "",
                    dob_year: null,
					dob_month: null,
					dob_day: null,
                    country_id: 0,
                    country_name:0,
                    city_name:"",
                    phone: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                    frontend_url: window.location.protocol + "//" + window.location.host,
                },
                resend_mail: "",
                disabled: 0,
                lang: {
                    
                    monthBeforeYear: true,
                    days: ["日", "月", "火", "水", "木", "金", "土"],
                    months: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                    placeholder: {
                        //date: new Date().toISOString().slice(0,10),
                        date: "年 - 月 - 日",
                    },
                },
                isIcon: false,
                showPass: false,  
                isIcontwo: false,
                showPasstwo: false,
                registered_id: null,
            };
        },
        computed: {
            // cityList: function(){
            //     let { country_id } = this.formRegister;
            //     var filterVal = this.city.filter(v => v.id === country_id);
            //     this.formRegister.country_name = filterVal[0].country_name;
            //     this.formRegister.city_name = filterVal[0].city_name;
                
            //     return filterVal;
            // }
            showPwd() {
                if( this.formRegister.password === '') {
                    this.isIcon = false;
                }else{
                    this.isIcon = true;
                }               
            },
            showPwdConfirm() {
                if( this.formRegister.password_confirmation === '') {
                    this.isIcontwo = false;
                }else{
                    this.isIcontwo = true;
                }               
            }, 
        },
        validations: {
            formRegister: {
                name: {
                    required,
                },
                phone: {
                    required,
                    alphaNumAndValidator,
                },
                email: {
                    required,
                    email,
                    isUnique(value) {
                    if (value === '') return true;
                    return new Promise((resolve, reject) => {
                        this.$api.post('/jobseeker/mail-unity', {
                            email: value
                        })
                        .then(res => {
                            resolve(res);
                        })
                        .catch(err => {
                            if (err.response.status == 422)
                                reject(err);
                            else 
                                resolve(err);
                        })
                    })
                }
              
                },
                jobseeker_furigana_name: {
                    required,
                    isFurigana,
                },
                password: {
                    required,
                    minLength: minLength(8),
                },
                password_confirmation: {
                    sameAsPassword: sameAs("password"),
                },
            },
        },
        created() {

			this.$api.get("/jobseeker/country-list").then((response) =>{
                this.countries  = response.data;
                console.log(this.countries)
			});

			if(this.$route.path == '/jobseeker/register/confirm')
			{

				// this.disabled = 1;
				this.currentPage = this.pages.confirm;
				const dataStorage =  localStorage.getItem('formData');
				if(dataStorage != null){
                    this.formRegister = JSON.parse(localStorage.getItem('formData')) ;
                    // this._parseDob();
                    this.$api.get("/jobseeker/selected-country?selectedCountry="+ this.formRegister.country_id).then((response) =>{
                        this.countries  = response.data;
                    });
                    
				}
				else if(dataStorage == null){
					this.$router.push('/jobseeker/register');
					this.currentPage = 'registration';
				}
			
					
			}
			else if(this.$route.path == '/jobseeker/register/success'){
				
				this.currentPage = this.pages.email_sent;
			}
			else if(this.$route.path == '/jobseeker/register'){
				this.currentPage = "registration";
			}
			
        },

        methods: {
            storedCityName(){
                if(this.formRegister.country_id == 0) {
                    this.formRegister.city_name = null;
                }
                else{
                    let { country_id } = this.formRegister;
                    var filterVal = this.city.filter(v => v.id === country_id);
                    // this.formRegister.country_name = filterVal[0].country_name;
                    this.formRegister.city_name = filterVal[0].city_name;
                }                
            },

            getCity(selectedValue)
            {
                if(this.formRegister.country_name == 0){
                    this.formRegister.country_id = 0;
                    this.city = [];
                }
                else {
                    this.$api.get("/jobseeker/city-list/"+selectedValue).then((res) => {
                            this.formRegister.country_id = 0;
                            this.formRegister.city_name = null;
                            this.city = res.data;
                            // this.city = res.data.filter(function (e) {
                            //     return e.city_name != '日本';
                            // });
                    });
                }        
            },
            
            register() {

                this.$v.formRegister.$touch();
                if (this.$v.formRegister.$invalid) {
                    return;
                }
                this.disabled = 1;
                // this._sanitizeDob();
				localStorage.setItem('formData',JSON.stringify(this.formRegister));
                this.currentPage = this.pages.confirm; // load confirm page
                console.log("reg",this.formRegister)
                console.log("dob",this.dob)
				this.$router.push('/jobseeker/register/confirm');
            },
            backRegister() {
                // this.disabled = 0;
                this.currentPage = this.pages.registration;
                this.$router.push('/jobseeker/register');
            },

            confirmRegistration() {
				let loader = this.$loading.show();	
                console.log(this.formRegister)
				
            setTimeout(() => {
                this.$api
                    .post("/jobseeker/register", this.formRegister)
                    .then((res) => {
						localStorage.removeItem('formData');
						this.$router.push('/jobseeker/register/success');
                        loader.hide();
                        this.currentPage = this.pages.email_sent; //-- load email_sent page
                        //  $('.first-tab').addClass('active');
                        //  $('.second-tab').addClass('active');
						//  $('.third-tab').addClass('active');
						 
                    })
                    .catch((err) => {
                        const code = err.response.status;
                        if (code == 422) {
                            // alert("このメールアドレスは、既に使われています");
                        }
                        this.$store.commit("registerFailed", { err });
                    });
                          }, 5000);
            },
            navigateToTop() {
                this.$router.push({ path: "/" });
            },
            resendEmail() {
                this.$api
                    .post("/jobseeker/resend-mail", {
                        email: this.resend_mail,
                        encrypted: 1,
                        registered_id: this.registered_id,
                        frontend_url: this.formRegister.frontend_url,
                    })
                    .then((res) => {
                     
                        this.currentPage = this.pages.email_sent; //-- load email_sent page
                    })
                    .catch((error) => {
                        this.$alertService.showErrorDialog(null, 'メールを送信できません');
                        this.$store.commit("registerFailed", { error });
                    });
            },

            _parseDob() {
				if (this.formRegister.dob) {
					const date = new Date(this.formRegister.dob);
					this.dob = {
						year: date.getFullYear() + ' 年',
						month: date.getMonth() + 1 + ' 月',
						day: date.getDate() + ' 日',
					}
				} else {
					this.dob = {
						year: null,
						month: null,
						day: null,
					}
				}
			},
			_sanitizeDob() {
				if (this.dob.year && this.dob.month && this.dob.day) {
					let year = this.dob.year.replace(' 年','');
					let month = this.dob.month.replace(' 月','');
					let day = this.dob.day.replace(' 日','');
					month = month.length < 2 ? '0' + month : month;
					day = day.length < 2 ? '0' + day : day;					 
					this.formRegister.dob_year = year;
					this.formRegister.dob_month = month;
					this.formRegister.dob_day = day;
					this.formRegister.dob = `${year}-${month}-${day}`;
				} else {
					this.formRegister.dob_year = null;
					this.formRegister.dob_month = null;
					this.formRegister.dob_day = null;
					this.formRegister.dob = null;
				}
			}
        },
    };
</script>

<style lang="scss" scoped> 
.info{
    display:inline-block;
    color:#666666;
    font-size:13px;
    margin-top:5px;
}  
.form-signin-heading{
    margin-bottom: 3rem;
}
.form-content{
    border-top:3px solid #6085a3;
} 
.signin-card-body {
    padding: 40px 0;
    min-height: 100vh;
}  
.signin-card-body .form-content .form-group {
    margin-bottom: 20px;
}
label {
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
    background: #bfdaf1;
    padding: 15px 20px;
    border-radius: 40px;
    margin: 0 4%;
    text-align: center;
    box-shadow: 0 0.2rem 0.7rem rgba(0, 0, 0, 0.15);
    color: #6085a3 ;
    font-weight: bold;
}
.tab-item:after {
    position: absolute;
    content: "";
    width: 100px;
    height: 3px;
    background: #6085a3 ;
    top: 50%;
    right: -97px;
}
.tab-item:last-child:after {
    width: 0;
}
.tab-item.active {
    background: #6085a3 ;
    color: #fff;
}   
.register-btn{
    width: 150px;
    // line-height: 2;
    border-radius: 25px !important;
}        
.form-content .showpwd {       
    right: 20px;       
}
.required{           
    margin-top: 12px;
    margin-right: 0px;
}
.star-pwd{
    vertical-align: middle;
    display: inline-block;
    color: #666666;    
    line-height: 1.5;
    padding-top: 15px !important;
    padding-bottom: 5px !important;
}
//for confirm state (UI)
.confirm-label-block{
    background: #6085a3;
    color: #fff;
    border: 1px solid #6085a3;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    
    label{
       color:#fff;
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
        margin: 0!important;
    }
}
.confirm-address-lbl-block{
    padding: 0px 15px;
    // height: 61px;
}
.confirm-address-block{
    padding: 10px;
    // height: 61px;
    p{
        padding: 0px;
    }
}
.sp-767{
    display: none;
}
.pc-767{
    display: flex;
}
/*mobile ui (stepper)*/
@media screen and (max-width:768px) {  
    .tab-item{
        margin: 0 2%;
    }
    .qa-select{
        overflow: hidden
    }
}
@media screen and (max-width:767px) {  
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
    background-color: #6085a3;
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
  color: #6085a3;
}

.mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-circle {
    background-color: #6085a3;
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
    color: #6085a3;
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
    margin-bottom: 0px;
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
        font-size: 1.2rem;
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
        color: #666666;
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
        border-bottom: 1px solid #6085a3;
        width: 100%;      
        border-radius: 0px !important;
        font-size: 16px;
        box-sizing: border-box;
        padding-bottom: 0px;
        padding-top: 0px;
        color: #495057;
    }
}
.confirm-address-lbl-block{
    padding: 0 15px;
    height: auto;
}
.confirm-address-block{
    padding: 0px 15px;
    height: auto;
}
.confirm-check{
    float: right;
    line-height: 1.5;
    font-size: 14px;
}
.fa-check:before{
    color: #6085a3;
    
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
</style>
