<template>
    <div class="user-main">
        <h2 class="user-head-tit recruiter-primarylight-color">{{ page_title }}</h2>
        <div class="content-wrap">  
            <div class="row m-0">
                <div class="col-12 form-bordered">
                    <!-- <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name" style="line-height: 22px;" >{{ $t('jobcreate.title2') }}</label>
                        </div>
                        <div class="col-md-7 d-flex form-right-block">
                            <p class="custom-radio-group form-paragraph">
                                <input type="radio" id="companyname" name="gender" class="custion-radio" value="正式名称で表示" v-model="formRegister.recruiter_show_name" />
                                <label for="companyname" class="custom-radio-lable">{{ $t('jobcreate.com_name') }}</label>
                            </p>
                            <p class="custom-radio-group ml-4 form-paragraph">
                                <input type="radio" id="nickname" name="gender" class="custion-radio" value="愛称で表示" v-model="formRegister.recruiter_show_name" />
                                <label for="nickname" class="custom-radio-lable">{{ $t('jobcreate.nick_name') }}</label>
                            </p>
                        </div>
                    </div> -->

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name" style="line-height: 22px;"> {{ $t('jobcreate.title') }}</label>
                             <span class="required">必須</span>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block form-group">
                            <textarea  :class="['form-control mb-0',$v.formRegister.title.$error?'is-invalid':'']"  id="" rows="3" style="height: 100%;" v-model="$v.formRegister.title.$model"></textarea>
                            <div class="invalid-feedback">
                                 <span v-if="!$v.formRegister.title.required">{{ $t('jobcreate.title_required') }}</span>
                            </div>
                        </div>
                      
                    </div>


                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name" style="line-height: 22px;">{{ $t('jobcreate.occupation') }}<br/>{{ $t('jobcreate.occupation_ex') }}</label>
                            <div class="pl-3">
                            <ol class="color-for-example">
                                <li>{{ $t('jobcreate.occupation_ex1') }}</li>
                                <li>{{ $t('jobcreate.occupation_ex2') }}</li>
                                <li class="list-unstyled">{{ $t('jobcreate.occupation_ex3') }}</li>
                            </ol>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.occupation_description"></textarea>
                            <div class="invalid-feedback">
                                <span>職種(記入例)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.Employment_status') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <div class="row p-0 m-0">
                                    <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-2 p-0" v-for="(result,indx) in employment_types" :key="result.id">
                                        <input type="checkbox" v-bind:value="result.employment_type_name" v-bind:id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="formRegister.employment_types"/>
                                        <label :for="result.employment_type_name" class="custom-control-label custom-checkbox-label">{{$t('jobcreate.employment_type['+indx+']')}}</label>
                                    </div>      
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.Job_Description') }} <br/>{{ $t('jobcreate.Job_Description_ex1') }}</label>
                            <div class="pl-3">
                                <ol class="color-for-example">
                                    <li>{{ $t('jobcreate.Job_Description_ex2') }}</li>
                                    
                                    <li>{{ $t('jobcreate.Job_Description_ex3') }}</li>
                                    
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.job_description"></textarea>
                            <div class="invalid-feedback">
                                <span>仕事内容</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.Qualification') }} <br/>{{ $t('jobcreate.Qualification_ex1') }} </label>
                            <p class="color-for-example">{{ $t('jobcreate.Qualification_ex2') }}<br/>
                            {{ $t('jobcreate.Qualification_ex3') }}</p>
                            <p class="color-for-example">
                                {{ $t('jobcreate.Qualification_ex4') }}<br/>
                                {{ $t('jobcreate.Qualification_ex5') }}<br/>
                                {{ $t('jobcreate.Qualification_ex6') }}                                          
                            </p>
                            <p class="color-for-example">
                                {{ $t('jobcreate.Qualification_ex7') }}<br/>
                                {{ $t('jobcreate.Qualification_ex8') }}
                            </p>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.qualification"></textarea>
                            <div class="invalid-feedback">
                                <span>応募資格・語学力</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.allowance') }}<br/> {{ $t('jobcreate.allowance_ex1') }} </label>
                            <p class="color-for-example">
                                {{ $t('jobcreate.allowance_ex2') }}<br/>
                                {{ $t('jobcreate.allowance_ex3') }}<br/>
                                {{ $t('jobcreate.allowance_ex4') }}<br/>
                                {{ $t('jobcreate.allowance_ex5') }}<br/>
                                {{ $t('jobcreate.allowance_ex6') }}<br/>
                                {{ $t('jobcreate.allowance_ex7') }}<br/>                                           
                            </p>
                            <p class="color-for-example">
                                {{ $t('jobcreate.allowance_ex8') }}<br/>
                                {{ $t('jobcreate.allowance_ex9') }}<br/>
                                {{ $t('jobcreate.allowance_ex10') }}<br/>                                            
                            </p>
                            <p class="color-for-example">
                                {{ $t('jobcreate.allowance_ex11') }}<br/>
                                {{ $t('jobcreate.allowance_ex12') }}<br/>
                                {{ $t('jobcreate.allowance_ex13') }}<br/>
                                {{ $t('jobcreate.allowance_ex14') }}<br/>
                                {{ $t('jobcreate.allowance_ex15') }}
                            </p>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.allowance"></textarea>
                            <div class="invalid-feedback">
                                <span>給与・待遇・ビザサポート</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.startdate') }}<br/>{{ $t('jobcreate.startdate_ex1') }}</label>
                            <p class="color-for-example">{{ $t('jobcreate.startdate_ex2') }} <br>                                          
                                {{ $t('jobcreate.startdate_ex3') }}</p>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <!-- <input type="date" name="" class="form-control" placeholder="生年月日" v-model="formRegister.job_start_date" /> -->
                            <!-- <date-picker :lang="lang" placeholder="年 - 月 - 日" valueType="format" v-model="formRegister.job_start_date" class="form-control"></date-picker> -->
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.job_start_date"></textarea>
                            <div class="invalid-feedback">
                                <span>勤務開始日</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.location') }} <br/>{{ $t('jobcreate.location_ex1') }}</label>
                            <p class="color-for-example">{{ $t('jobcreate.location_ex2') }}</p>
                            <!-- <span class="required">必須</span> -->
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.job_location"></textarea>
                            <div class="invalid-feedback">
                                <span>勤務地詳細</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.Application_address') }}</label>
                            <span class="required">必須</span>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <p class="color-for-example">{{ $t('jobcreate.Application_address_txt') }}</p>
                            <input
                                type="text"
                                :class="['form-control',$v.formRegister.application_address.$error?'is-invalid':'']"
                                name="メールアドレス"
                                id="email"
                                placeholder="メールアドレス"
                                autocomplete="off"
                                v-model="$v.formRegister.application_address.$model"
                            />
                            <!-- <input type="text" v-model="currentUser.email" :class="['form-control',$v.formRegister.application_address.$error?'is-invalid':'']"> -->
                            <div class="invalid-feedback">
                                <span v-if="!$v.formRegister.application_address.required">メールが必須です</span>
                                <span v-if="!$v.formRegister.application_address.application_address">メールアドレスの形式が正しくありません</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name">{{ $t('jobcreate.message_jobs') }} <br/>{{ $t('jobcreate.message_jobs_ex1') }} </label>
                            <p class="color-for-example">
                                {{ $t('jobcreate.message_jobs_ex2') }}<br/>
                                {{ $t('jobcreate.message_jobs_ex3') }}<br/>
                                {{ $t('jobcreate.message_jobs_ex4') }}
                            </p>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <textarea class="form-control" id="" rows="3" style="height: 100%;" v-model="formRegister.message"></textarea>
                            <div class="invalid-feedback">
                                <span>求職者へのメッセージ</span>
                            </div>
                        </div>
                    </div>                              
                </div>
                <div class="col-12 form-bordered mt-5">
                        <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name"> {{ $t('jobcreate.area') }}</label>
                            <span class="required">必須</span>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                        <div id="disabledMask"></div>
                            <v-select v-model="$v.formRegister.country_id.$model"
                                        :options="countries" 
                                        label="country_name"
                                        :class="['auto-complete mb-0 a',$v.formRegister.country_id.$error?'is-invalid':'']"
                                        :placeholder="$t('jobcreate.area_select')"
                                        :reduce="countries => countries.id" 
                                        aria-autocomplete="on">
                                        <span slot="no-options" @click="$refs.select.open = false">
                                        検索条件に当てはまるデータはありません
                                    </span>
                            </v-select>
                            
                            <!-- <select v-model="$v.formRegister.country_id.$model" :class="['form-control mb-0',$v.formRegister.country_id.$error?'is-invalid':'']">
                                <option disabled value="">{{ $t('jobcreate.area_select') }}</option>
                                
                                <option v-for="countries in countries" :key="countries.id" v-bind:value="countries.id">
                                    {{countries.country_name}}
                                </option>
                            </select> -->
                            <div class="invalid-feedback">
                                <span v-if="!$v.formRegister.country_id.required">{{ $t('jobcreate.area_required') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-bottom-bordered">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name"> {{ $t('jobcreate.occupation_keyword') }}</label>
                            <span class="required">必須</span>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                             <v-select  v-model="$v.formRegister.occupation_id.$model"
                                        :options="occupations" 
                                        label="occupation_name"
                                        :class="['auto-complete mb-0 a',$v.formRegister.occupation_id.$error?'is-invalid':'']"
                                        :placeholder="$t('jobcreate.occupation_select')"
                                        :reduce="occupations => occupations.id" 
                                        aria-autocomplete="on">
                                        <span slot="no-options" @click="$refs.select.open = false">
                                        検索条件に当てはまるデータはありません
                                    </span>
                            </v-select>
                            <!-- <select v-model="$v.formRegister.occupation_id.$model" :class="['form-control mb-0',$v.formRegister.occupation_id.$error?'is-invalid':'']">
                                <option disabled value="">{{ $t('jobcreate.occupation_select') }}</option>
                                <option v-for="occupations in occupations" :key="occupations.id" v-bind:value="occupations.id">
                                    {{occupations.occupation_name}}
                                </option>
                            </select> -->

                            <div class="invalid-feedback">
                                <span v-if="!$v.formRegister.occupation_id.required">{{ $t('jobcreate.occupation_required') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name" style="line-height: 22px;"> {{ $t('jobcreate.other_keyword') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                                <div class="row col-12 p-0">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mb-2 pr-0-res" v-for="(keyword,indx) in other_keywords" :key="keyword.id">                                               
                                    <input type="checkbox" :value="keyword" :id="keyword" class="custom-control-input custom-checkbox" v-model="formRegister.other_keywords" />
                                    <label :for="keyword" class="custom-control-label custom-checkbox-label">{{ $t('jobcreate.other_keywords['+indx+']') }}</label>   
                                </div>
                                </div> 
                        </div>
                    </div>                                
                </div>
                <div class="col-12 form-bordered mt-5">
                    <div class="form-group row mb-0">
                        <div class="col-lg-4 col-md-5 form-left-block">
                            <label for="name"> {{ $t('jobcreate.update_date') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-7 form-right-block">
                            <date-picker :lang="lang" placeholder="年 - 月 - 日" valueType="format" v-model="formRegister.job_post_date[0]" type="date" class="datepicker-col mb-2 mb-sm-0"></date-picker>
                            
                            <date-picker v-model="formRegister.job_post_date[1]" valueType="format" type="time" class="datepicker-col"></date-picker>
                            
                            <div class="invalid-feedback">
                                <span>掲載開始日</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center row col-12 p-0 m-0 recru-btn">
                <div class="form-group text-center mt-3" v-if="job_post_status != 'post'">
                    <span id="draft" value="draft" class="btn btn-m primary-btn border-style recruiter-primary-color" @click="jobpostcreate('draft')">{{$t('jobcreate.draft')}}</span>
                </div>
                <div class="form-group text-center mt-3 ml-5 ml-s-1">
                    <span id="post" value="確認" class="btn btn-m primary-btn border-style delete-color" @click="jobpostcreate('post')">{{btn_text}}</span>
                    <!-- <span id="post" value="確認" class="btn btn-m primary-btn border-style delete-color" @click="jobpostcreate('post')">{{$t('jobcreate.confirm')}}</span> -->
                </div>
            </div>                
        </div>
    </div>
</template>

<script>
    import { required, email, minLength } from "vuelidate/lib/validators";
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        }, 
        data() {
            return {
                errors: {
                    permission: "",
                },
                formRegister: {
                    title:"",
                    employment_types: [],
                    application_address: "",
                    country_id: "",
                    occupation_id: "",
                    other_keywords: [],
                    job_post_date : '',
                    // job_post_date : (new Date(Date.now() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, -1).replace('T', ' '),
                },
                employment_types: [],
                other_keywords:[
                    '新卒歓迎',
                    '未経験歓迎',
                    'シニア歓迎',
                    '本社採用',
                    '語学不問'
                ],
                page_title: '新規求人投稿',
                occupations: [],
                countries: [],
                GMT_Offset:'',
                submitted: false,
                error: null,
                showPass: false,
                btn_text: '投稿',
                alert_text: '投稿しました。',
                job_post_status: 'draft',
                lang: {
                    days: ["日", "月", "火", "水", "木", "金", "土"],
                    months: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                    placeholder: {
                        //date: new Date().toISOString().slice(0,10),
                        date: "年 - 月 - 日",
                    },
                },
            };
        },

        mounted() {             
            // this.formRegister.job_post_date = new Date().toISOString().slice(0,10);
            // this.formRegister.job_post_date = new Date().toISOString().slice(0, 19).replace('T', ' ');   
                
            if(this.$route.params.id)
            {
                
                this.btn_text = '投稿';
                this.page_title = '求人編集';
                this.$api.get('recruiter/jobs/'+`${this.$route.params.id}`+'/edit').then(res=> {
                    this.formRegister = res.data.data;
                    this.formRegister.employment_types = this.formRegister.employment_types.split(',').filter(i => i);
                    this.formRegister.other_keywords = this.formRegister.other_keywords.split(',');
                    this.formRegister.job_post_date = this.formRegister.job_post_date.split(' ');
                    if(this.formRegister.job_post_status == 'post'){
                        this.btn_text = '保存';
                        this.alert_text = '保存しました。';
                    }
                    this.job_post_status = this.formRegister.job_post_status;
                });
            }          
            
            this.formRegister.recruiter_show_name = "正式名称で表示";
            //recruiter_job_email
            this.formRegister.application_address = this.$store.getters.currentUser.email;

            this.$api.get("recruiter/getalldata").then((response) =>{
                // console.log(response.data.GMT_Offset)
                this.GMT_Offset = response.data.GMT_Offset;
                this.occupations = response.data.occupation;
                this.countries  = response.data.country;
                this.employment_types = response.data.employment;
                this.callFunction()
                
            });
            
        },
        validations: {
            formRegister: {
                title:{required},
                application_address: { required, email },
                occupation_id: { required },
                country_id: { required },
            },
        },
        methods: { 
       

            callFunction: function () 
            {
            //     const getUtcOffset = (timeZone) => {
            //     const timeZoneName = Intl.DateTimeFormat("ia", {
            //         timeZoneName: "short",
            //         timeZone,
            //     })
            //         .formatToParts()
            //         .find((i) => i.type === "timeZoneName").value;
            //     const offset = timeZoneName.slice(3);
            //     if (!offset) return 0;

            //     const matchData = offset.match(/([+-])(\d+)(?::(\d+))?/);
            //     if (!matchData) throw `cannot parse timezone name: ${timeZoneName}`;

            //     const [, sign, hour, minute] = matchData;
            //     let result = parseInt(hour) * 60;
            //     if (sign === "-") result *= -1;
            //     if (minute) result + parseInt(minute);

            //     return result;
            //     };
            //     const offset = getUtcOffset("Asia/Tokyo");
            
            // var tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
            // var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0, 19).replace('T', ' ');

            // var gettime = localISOTime.split(' ');

            // console.log(gettime)
            if(!this.$route.params.id){
                const formatDate = (date) => {
                    let d = new Date(date),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2) month = '0' + month;
                    if (day.length < 2) day = '0' + day;

                    return [year, month, day].join('-');
                }

                const current_date = new Date().toLocaleDateString('en-US',{timeZone:this.GMT_Offset})
                const get_time = new Date().toLocaleTimeString('it-IT',{timeZone:this.GMT_Offset})
                const date = formatDate(current_date)+ ' ' + get_time;
                this.formRegister.job_post_date = date.split(' ')
            }     

        },

            jobpostcreate(status) {   
                   
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                let loading = this.$loading.show();

                if(this.$route.params.id)
                { 
                    this.formRegister.job_post_status = status;
                     this.$api.post('recruiter/jobs/'+`${this.$route.params.id}`+'/update',this.formRegister).then(res => {
                        loading.hide();

                        // if(res.data.data.job === 'new'){
                        //     this.$alertService.showConfirmDialog(null, 'Would you like to create a new job?', this.$t('alertMessage.yes'), this.$t('alertMessage.no')) 
                        //     .then(r => {
                        //         if(r.value){
                        //             let loading = this.$loading.show();
                        //             this.$api.post("recruiter/jobs/add", this.formRegister).then((response) => {
                        //                 loading.hide();
                        //                 this.$alertService.showSuccessDialog(null, this.$t('jobcreate.saveSuccess'), this.$t('common.close'));
                        //                 this.$router.push({ path: this.formRegister.job_post_status == 'post'? '/recruiter/jobs' : '/recruiter/job-draft' })
                        //             })
                        //         }
                        //     });
                        // }
                        // else{
                            if(status == 'post'){
                                this.$alertService.showSuccessDialog(null, this.alert_text, this.$t('common.close'));
                            }
                            else{
                                this.$alertService.showSuccessDialog(null, this.$t('jobcreate.updateSuccess'), this.$t('common.close'));
                            }
                            this.$router.push({ path: this.formRegister.job_post_status == 'post'? '/recruiter/jobs' : '/recruiter/job-draft' })
                        // }                        
                        
                     }).catch(e =>{
                         console.log(e)
                     }).finally(f => {
                         loading.hide();
                     })
                }
                else{ 
                    this.formRegister.job_post_status = status;                  
                     this.$api.post("recruiter/jobs/add", this.formRegister).then((response) => {
                         loading.hide();
                        if(status == 'post'){
                            this.$alertService.showSuccessDialog(null, this.alert_text, this.$t('common.close'));
                        }
                        else{
                            this.$alertService.showSuccessDialog(null, this.$t('jobcreate.updateSuccess'), this.$t('common.close'));
                        }
                        
                        this.$router.push({ path: this.formRegister.job_post_status == 'post'? '/recruiter/jobs' : '/recruiter/job-draft' })
                     })
                     .catch((error) => console.log(error))
                     .finally(() => (loading.hide()));
                }
               
            },
           
        },
        computed: {
            registeredUser() {
                return this.$store.getters.registeredUser;
            },
            currentUser() {
                return this.$store.getters.currentUser;
            },
        },
            // mounted() 
            // {
            //     // this.callFunction()
            // }
    };
</script>

<style lang="scss">    
@import "resources/sass/variables.scss";  
.form-bordered{
    border: 1px solid #919191;      
}
.form-bottom-bordered{
    border-bottom: 1px solid #919191;
    margin-bottom: 0px !important;
}
.form-left-block{
    padding: 15px;
    background: #eef1f3;
    
    border-right: 1px solid #919191;
}
.form-right-block{
    padding: 15px;        
}
.form-paragraph{
    margin-bottom: 0px !important;
}

.auto-complete{
    border: 1px solid #b9b9b9;
    border-radius: 3px;
    padding: 3px;
}
.vs__dropdown-toggle{
    border: none !important;
}
.vs__search, .vs__search:focus{
    padding: 0px !important;
    margin: 0px !important;
    color: #a1a1b6;
}
.vs__selected{
    margin: 0px !important;
    padding:0px !important; 
    color:#666666 !important;       
}

.color-for-example{
    color: #666;
}
.vs__search{
    color:#666666;
}

.vs__actions{
    padding: 0px !important;
}
// @media screen and (max-width: 370px){
//     .vs__actions {
//         margin-top: -20px !important;
//     }
// }
@media screen and (max-width: 1023px){
    .datepicker-col {
        width: 40% !important;
    }
    .required{
        margin-right:0px;
    }
}
@media screen and (max-width: 767px){
    .form-left-block{
        border-right: 0px;
        label{
            margin-bottom: 0px
        }
    }
    .required{
        margin-right:0px;
    }
    .pr-0-res{
        padding-right: 0px;
    }
    .plr-0-res{
        padding-right: 0px;
        padding-left: 0px;
    }
    .mb-s-2{
        margin-bottom: 10px;
    }
    .ml-s-1{
        margin-left: .5rem!important;
    }
    .custom-checkbox-label{
        padding-left: 25px;
        font-size: 12px
    }
    .recru-btn .btn-m {
        width: 124px;
        padding: 8px 5px;
    }
}
@media (max-width:480px){
     .datepicker-col {
        width: 100% !important;
    }
}
</style>
