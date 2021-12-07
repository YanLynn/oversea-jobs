<template>
   <div class="user-main vld-parent" ref="progressContainer">
        <h2 class="user-head-tit recruiter-primarylight-color">{{ $t('jobapplicant.title') }}</h2>   
        <div class="content-wrap">            
            <div class="searchform-one">
                <!--advanced search--> 
                <div class="inner-wrapper">
                    <div class="form-group row"> 
                        <div class="col-12 col-md-3 col-sm-4  mb-sm-0 mb-3">
                            <label for="企業番号">{{ $t('jobapplicant.management_number') }}</label>
                            <input type="text" :placeholder="$t('jobapplicant.management_number')" class="form-control" v-model.trim="filteredData.management_number"/>
                        </div>                          
                        <div class="col-12 col-md-3  col-sm-4  mb-sm-0 mb-3">
                            <label for="企業番号">{{ $t('jobapplicant.job_title') }}</label>
                            <input type="text" :placeholder="$t('jobapplicant.job_title')" class="form-control" v-model.trim="filteredData.job_title"/>
                        </div>                                            
                    </div>         
                    <div class="row col-12">
                        <label for="応募⽇時">{{ $t('jobapplicant.application_period') }}</label>
                    </div>          
                    <div class="form-group row">                    
                        <div class="col-12 col-md-3 col-sm-4 mb-sm-0  mb-5 datepicker-wrapper">                            
                            <date-picker v-model="filteredData.from_date" valueType="format" class="datepicker" :lang="lang" :placeholder="$t('jobapplicant.date')" ></date-picker>
                        </div>              
                        <div class="col-12 col-md-3 col-sm-4 mb-sm-0 mb-3 datepicker-wrapper similarto position-relative">                      
                            <date-picker  v-model="filteredData.to_date"  valueType="format" class="datepicker" :lang="lang" :placeholder="$t('jobapplicant.date')"></date-picker>
                        </div>  
                        <div class="col-12 col-md-6 col-sm-4 text-right">
                            <button class="btn primary-btn border-style recruiter-primary-color btn-m" @click="filterFun">{{ $t('jobapplicant.search') }}</button>
                        </div>                      
                    </div>
                </div> 
                <!-- Search by Status -->
                <div class="form-group row m-0">
                    <div class="col-12 p-0"><label for="ステータス">{{ $t('jobapplicant.status') }}</label>
                    <!--status click-->                    
                    <span @click="visible = !visible" ref="tooltip-box" style="cursor:pointer;margin-left: 5px; margin-top: -1px;">                   
                        <svg x="0px" y="0px" viewBox="0 0 512 512" style="width: 15px; height: 15px; opacity: 1;"><g>
                        <path class="st0" d="M256,0C114.616,0,0,114.612,0,256s114.616,256,256,256s256-114.612,256-256S397.385,0,256,0z M207.678,378.794
                        c0-17.612,14.281-31.893,31.893-31.893c17.599,0,31.88,14.281,31.88,31.893c0,17.595-14.281,31.884-31.88,31.884
                        C221.959,410.678,207.678,396.389,207.678,378.794z M343.625,218.852c-3.596,9.793-8.802,18.289-14.695,25.356
                        c-11.847,14.148-25.888,22.718-37.442,29.041c-7.719,4.174-14.533,7.389-18.769,9.769c-2.905,1.604-4.479,2.95-5.256,3.826
                        c-0.768,0.926-1.029,1.306-1.496,2.826c-0.273,1.009-0.558,2.612-0.558,5.091c0,6.868,0,12.512,0,12.512
                        c0,6.472-5.248,11.728-11.723,11.728h-28.252c-6.475,0-11.732-5.256-11.732-11.728c0,0,0-5.645,0-12.512
                        c0-6.438,0.752-12.744,2.405-18.777c1.636-6.008,4.215-11.718,7.508-16.694c6.599-10.083,15.542-16.802,23.984-21.48
                        c7.401-4.074,14.723-7.455,21.516-11.281c6.789-3.793,12.843-7.91,17.302-12.372c2.988-2.975,5.31-6.05,7.087-9.52
                        c2.335-4.628,3.955-10.067,3.992-18.389c0.012-2.463-0.698-5.702-2.632-9.405c-1.926-3.686-5.066-7.694-9.264-11.29
                        c-8.45-7.248-20.843-12.545-35.054-12.521c-16.285,0.058-27.186,3.876-35.587,8.62c-8.36,4.776-11.029,9.595-11.029,9.595
                        c-4.268,3.718-10.603,3.85-15.025,0.314l-21.71-17.397c-2.719-2.173-4.322-5.438-4.396-8.926c-0.063-3.479,1.425-6.81,4.061-9.099
                        c0,0,6.765-10.43,22.451-19.38c15.62-8.992,36.322-15.488,61.236-15.429c20.215,0,38.839,5.562,54.268,14.661
                        c15.434,9.148,27.897,21.744,35.851,36.876c5.281,10.074,8.525,21.43,8.533,33.38C349.211,198.042,347.248,209.058,343.625,218.852
                        z" style="fill: rgb(145 145 145);"></path>
                        </g>
                        </svg>                  
                    </span>
                    <span class="bg" v-if="visible" @click="visible=!visible"></span>
                    <span class="tooltip-box" v-if="visible">
                        <table class="tooltiptext">
                        <thead>
                            <tr><th colspan="2" class="tooltip-head">ステータスの説明</th></tr>
                        </thead>
                        <tbody>
                            <tr><td class="w-105">検討中</td><td>求職者会員から求人に応募があった。</td></tr>
                            <tr><td class="w-105">辞退/不採用</td><td>企業会員からの不採用又は求職者会員の辞退。</td></tr>
                            <tr><td class="w-105">内定</td><td>運営管理者が内定を確認済。</td></tr>                            
                        </tbody>
                        </table>
                     </span>
                    <!-- end status click -->
                    </div>
                    <div class="d-sm-flex">
                    <div class="mr-5 mb-sm-0 mb-2" v-for="status in arr_status" v-bind:key="status.id">                          
                        <input type="checkbox" :id="status.id" class="custom-control-input custom-checkbox" :value="status.id" :checked="status.checked" v-model="filteredData.jobapply_status" @change="filterFun()">
                        <label :for="status.id" class="custom-control-label custom-checkbox-label">{{status.id}}</label>                          
                    </div>
                    </div>
                </div>
                <!--end search by search-->
                <!--end advanced search-->
            </div>
            <!--table-->
            <div class="row m-0 custom-table">
                <div class="col-sm-12 p-0">
                    <div class="row mt-4 mb-3">
                        <div class="col-sm-12 select text-right">
                            <p class="mb-0"><span class="label-txt pr-2">{{ $t('jobapplicant.no_page') }}：</span> {{projects.length}}件</p>
                            <!-- <span>1{{ $t('jobapplicant.page_display') }}&nbsp;</span>
                            <select v-model="tableData.length" @change="getData()">
                                <option v-for="(records, index) in perPage" :key="index" :value="records">
                                    {{records}}
                                </option>
                            </select> -->
                        </div>
                    </div>
                    <div class="pc">
                    <DataTable ref="datatable" :columns="$t('jobapplicant.columns')" :showCheckbox="false" :sortOrders="sortOrders">
                        <tbody>
                            <tr v-for="(project, index) in projects" :key="index">
                                <td class="tbl-ws text-center">{{project.job_apply_date}}</td>
                                <td class="tbl-wxs text-center">{{project.management_number}}</td>
                                <!-- <td class="tbl-wxs text-center recruiter-primary-color-txt cursor-pointer"><span @click="showJobDetail(project,true)">{{project.management_number}}</span></td> -->
                                <td class="tbl-wxs recruiter-primary-color-txt text-center cursor-pointer num-txt-wrap"><span @click="showJobDetail(project)">{{project.job_number}}</span></td>
                                <td class="tbl-wm recruiter-primary-color-txt cursor-pointer"><span @click="showJobDetail(project)"><span class="txt-vertical-ellipsis">{{project.title}}</span></span></td>
                                <td class="tbl-wxl recruiter-primary-color-txt text-center cursor-pointer tbl-1024">
                                    <span class="d-flex" @click="navigateToSeekerDetail(project.jobapply_id)">
                                        {{project.jobseeker_number}}
                                        <span class="ml-xl-2 txt-vertical-ellipsis-1 w-100 text-left">{{project.jobseeker_name}}</span>
                                    </span>
                                </td>           
                                <td class="tbl-status text-center"><span>{{project.job_apply_status_replace}} </span></td>
                                <td class="tbl-ws text-center">
                                    <span class="btn btn-sm table-btn shadow-sm" @click="startChat(project)" v-if="allowChat(project.job_apply_status)">{{ $t('jobapplicant.chat') }}</span>
                                    <span class="btn btn-sm table-btn shadow-sm" v-if="noticeAccept(project)" @click="showNotice(project)">採否通知</span>
                                    <label class="noti-txt" v-if="project.send_noti == 1">採否通知済</label>
                                    <span class="btn btn-sm table-btn shadow-sm" @click="creditPay(project.jobapply_id)" v-if="unclaimed(project.job_apply_status_replace,project.job_apply_status) && project.registered_place_type == 'oversea'">{{ $t('jobapplicant.pay') }}</span>
                                    <span class="btn btn-sm table-btn shadow-sm" @click="generateBill(project.jobapply_id, index)" v-if="unclaimed(project.job_apply_status_replace,project.job_apply_status) && project.registered_place_type == 'local'">{{ $t('jobapplicant.display_invoice') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </DataTable>
                    </div>
                    <div class="sp">
                    <DataTable ref="datatable" :columns="$t('jobapplicant.titles')" :showCheckbox="false" :sortOrders="sortOrders">
                        <tbody>
                            <tr v-for="(project, index) in projects" :key="index">                               
                                <td class="tbl-col">
                                    <span class="txt-vertical-ellipsis job-tit">{{project.title}}</span>
                                    <span class="num-color">({{project.management_number}})</span><br>
                                    <!-- <span class="num-color" @click="showJobDetail(project)">({{project.job_number}})</span><br> -->
                                    <span v-on:click="showToggle(index)" class="toggle-menu" v-bind:class="{'rotate': (current === index) && (status == true)}"><i class="fa fa-caret-down"></i></span>
                                    <ul class="tbl-data" :id="project.id" v-bind:class="{'tbl-expand': (current === index) && (status == true)}">
                                        <li class="toggle-list"> 
                                        <div class="list-l">
                                            問合わせ/応募日時
                                        </div>
                                        <div class="list-r">
                                            {{project.job_apply_date}}
                                        </div>
                                        
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l">管理番号</div>
                                            <div class="list-r">
                                                {{project.management_number}}
                                            </div>
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l">求人番号</div>
                                            <div class="list-r recruiter-primary-color-txt" @click="showJobDetail(project)">
                                                {{project.job_number}}
                                            </div>
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l">求人タイトル</div>
                                            <div class="list-r recruiter-primary-color-txt" @click="showJobDetail(project)">
                                               <span class="txt-vertical-ellipsis">{{project.title}}</span>
                                            </div>
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l">
                                                    求職者
                                            </div>
                                            <div class="list-r">
                                                <p class="d-flex recruiter-primary-color-txt mb-0" @click="navigateToSeekerDetail(project.jobapply_id)">
                                                    {{project.jobseeker_number}} <span class="ml-2 txt-vertical-ellipsis w-100">{{project.jobseeker_name}}</span>
                                                </p>
                                            </div>
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l">
                                                ステータス
                                            </div>
                                            <div class="list-r">
                                                {{project.job_apply_status_replace}} 
                                            </div>
                                        </li>
                                        <li class="toggle-list">
                                            <div class="list-l"></div>
                                            <div class="list-r ml-auto pt-2 pb-2">
                                                <span class="btn btn-sm table-btn shadow-sm" @click="startChat(project)" v-if="allowChat(project.job_apply_status)">{{ $t('jobapplicant.chat') }}</span>
                                                <span class="btn btn-sm table-btn shadow-sm" v-if="noticeAccept(project)" @click="showNotice(project)">採否通知</span>
                                                <label class="noti-txt" v-if="project.send_noti == 1">採否通知済</label>
                                                <span class="btn btn-sm table-btn shadow-sm" @click="creditPay(project.jobapply_id)" v-if="unclaimed(project.job_apply_status_replace,project.job_apply_status) && project.registered_place_type == 'oversea'">{{ $t('jobapplicant.pay') }}</span>
                                                <span class="btn btn-sm table-btn shadow-sm" @click="generateBill(project.jobapply_id, index)" v-if="unclaimed(project.job_apply_status_replace,project.job_apply_status) && project.registered_place_type == 'local'">{{ $t('jobapplicant.display_invoice') }}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </td>                               
                            </tr>
                        </tbody>
                    </DataTable>
                    </div>
                     <!-- empty state and infinite loading-->
                    <div v-if="projects.length < 1 && check == 'filter'" class="no-data-txt-border recruiter-primary-color-txt">
                        検索条件に合致するデータがみつかりません
                    </div>
                    <infinite-loading @distance="1" @infinite="getData" spinner="waveDots" v-if="check == 'scroll'">
                        <div slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">
                            データがありません
                        </div>
                        <div slot="no-more" class=""></div>
                        <div slot="error" class="no-data-txt-border recruiter-primary-color-txt">
                           エラーが発生しました 
                        </div>
                    </infinite-loading>
                </div>

                <transition name="fade">
                    <div class="modal-wrapper" v-if="show">
                        <div class="modal-block">
                            <div class="modal-container">                                   
                                <div class="successful-block">
                                    <!-- <p class="icon icon-logo logo recruiter-primary-color-txt text-center"></p> -->
                                    <div class="successful-wrapper" v-if="errorMsg!=null"> 
                                        <p class="error" style="text-align:center;">{{errorMsg}}</p> 
                                    </div>
                                    <div class="successful-wrapper" v-else>              
                                        <dl class="detail-list">
                                            <dt class="detail-head-group mt-4 text-label-color">管理番号</dt>
                                            <dd class="detail-data-group mt-4">{{apply_info["management_number"]}}</dd>
                                        </dl>
                                        <dl class="detail-list">
                                            <dt class="detail-head-group text-label-color">求人タイトル</dt>
                                            <dd class="detail-data-group">{{apply_info["title"]}}</dd>
                                        </dl>
                                        <dl class="detail-list">
                                            <dt class="detail-head-group text-label-color">求人者氏名</dt>
                                            <dd class="detail-data-group">{{apply_info["recruiter_name"]}}</dd>
                                        </dl>
                                        <dl class="detail-list">
                                            <dt class="detail-head-group text-label-color">請求金額</dt>
                                            <dd class="detail-data-group">{{Number(apply_info["invoice_amount"]).toLocaleString()}} 円</dd>
                                        </dl>

                                        <p class=" text-center text-label-color">支払いに用いるクレジットカード</p>
                                        <div class="col-8 offset-2 text-left">
                                        <p class="custom-radio-group pl-5r">
                                            <input type="radio" id="japan" v-model="card_choice" value="old" name="radio-group" checked class="custion-radio" >
                                            <label for="japan" class="custom-radio-lable text-label-color">{{mask_card}}</label>
                                        </p>
                                        <p class="custom-radio-group pl-5r">
                                            <input type="radio" id="overseas" v-model="card_choice" value="new" name="radio-group" class="custion-radio">
                                            <label for="overseas" class="custom-radio-lable text-label-color">他のクレジットカードを使用</label>
                                        </p>
                                        </div>
                                    </div>
                                
                                    <!-- <div class="row data-block">
                                        <div class="col-12">
                                            <p class=" text-center text-label-color">支払いに用いるクレジットカード</p>
                                            <div class="col-8 offset-2 text-left">
                                            <p class="custom-radio-group pl-5r">
                                                <input type="radio" id="japan" v-model="card_choice" value="old" name="radio-group" checked class="custion-radio" >
                                                <label for="japan" class="custom-radio-lable text-label-color">{{mask_card}}</label>
                                            </p>
                                            <p class="custom-radio-group pl-5r">
                                                <input type="radio" id="overseas" v-model="card_choice" value="new" name="radio-group" class="custion-radio">
                                                <label for="overseas" class="custom-radio-lable text-label-color">他のクレジットカードを使用</label>
                                            </p>
                                            </div>
                                        </div>                          
                                    </div>  -->
                                    <div class="text-center">
                                        <button class="btn searchbtn delete-color btn-m" v-if="errorMsg==null" @click="confirmChoice(card_choice)">確認</button>
                                        <button class="btn searchbtn cancel-color ml-0" @click="show=!show">閉じる</button>
                                    </div>             
                                
                                </div>                                    
                            </div>
                        </div>
                    </div>
                </transition> 

                <!-- Notice Box -->
                <transition name="fade">
                    <div class="modal-wrapper" v-if="showNoticeBox">
                        <div class="modal-block">
                            <div class="modal-container notice-box"> 
                              <div class="notice-body-wrapper">		                                  
                                <div class="modal-header notice-header">
                                    採否通知    
                                </div>  
                                <p class="m-t-20"> 採用/不採用の旨を運営管理者に通知します。 運営管理者側で確認を行った後、求職者に対して通知を行います。 </p>                                
                                <div class="text-left">
                                    <p class="custom-radio-group" style="padding-left:2rem;">
                                        <input type="radio" id="japan" v-model="notice_data.accept_decline" value="採用" name="radio-group" checked class="custion-radio" >
                                        <label for="japan" class="custom-radio-lable text-label-color">採用</label>
                                    </p>
                                    <p class="custom-radio-group" style="padding-left:2rem;">
                                        <input type="radio" id="overseas" v-model="notice_data.accept_decline" value="不採用" name="radio-group" class="custion-radio">
                                        <label for="overseas" class="custom-radio-lable text-label-color">不採用</label>
                                    </p>
                                </div>
                                <p>運営管理者に対してコメント等ございましたら、以下に入力をお願いいたします。</p>
                                <textarea name="notice_comment" v-model="notice_data.notice_comment" class="col-12" rows="5"></textarea>
                                </div>
    
                                <div class="text-center m-t-20">
                                    <button class="btn btn-m cancelbtn border-style" @click="showNoticeBox=!showNoticeBox">キャンセル</button>
                                    <button class="btn btn-m delete-color border-style ml-2" v-if="errorMsg==null" @click="sendNotice()">送信</button>                                    
                                </div> 
                            </div>
                        </div>
                    </div>
                </transition> 
                <!-- End Notice Box -->
            </div>          
            <!--end for table-->
        </div>
    <!-- successful-->
    <!-- <div class="row col-12 text-center">
        <div class="col-6">
            <div class="successful-block">
                <span class="icon icon-logo logo recruiter-primary-color-txt"></span>
                <p class="mt-5 mb-5">企業会員登録が完了いたしました</p>
                <button class="btn recruiter-primary-color primary-btn ">トップページへ</button>
            </div>
        </div>
        <div class="col-6">
            <div class="successful-block">
                <span class="icon icon-logo logo recruiter-primary-color-txt"></span>
                <p class="mt-5 mb-5">この度、企業会員にご登録いただき、<br>
                誠にありがとうございます</p>
                <p class="mt-5 mb-5">
                    現在、仮会員の状態です。<br/>
                    ご入力いただいたメールアドレス宛に<br/>
                    ご本人様確認用のメールをお送りいたしました。<br/>
                    メール本文内の URL をクリックすると、<br/>
                    本会員登録完了となります。
                </p>
                <button class="btn recruiter-primary-color primary-btn ">トップページへ</button>
            </div>
        </div>
    </div> -->
    <!--end successful-->

    <transition name="fade">
        <DetailsInfoPopup v-if="showJobDetailsPopup" :show="showJobDetailsPopup" @closed="PopupClosed" :job="selectedJob" :types="employment_types" :show-as-scout-entry="selectedJob.showAsScoutEntry" :show-job="true"></DetailsInfoPopup>
    </transition> 

  </div>
</template>
<script>
import DataTableServices from "../datatable/DataTableServices";
import DetailsInfoPopup from '../public/DetailsInfoPopup';
import RecruiterPaymentCardChoice from "./RecruiterPaymentCardChoice";
import {mapGetters} from 'vuex';
export default {
    metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	}, 
    components: {
        RecruiterPaymentCardChoice,
		DetailsInfoPopup,
    },
        mixins: [DataTableServices],
        data(){
            let sortOrders = {};
            // let columns = [];
            // columns.forEach(column => {
            //     sortOrders[column.name] = -1;
            // });
           let filteredData = {
                    job_title: '',
                    management_number:'',
                    from_date: '',
                    to_date:'',
                    jobapply_status: [],
            };
            return {
                errorMsg: null,
                showNoticeBox: false,
                notice_data: {
                    accept_decline: '採用',
                    notice_comment: null,
                    recruiter_name: null,
                    recruiter_number: null,
                    jobseeker_name: null,
                    jobseeker_furigana_name: null,
                    jobseeker_number: null,
                    management_number: null,
                    job_number: null,
                    job_id: null,
                    title: null,
                },
                card_choice: 'old',
                card_info: [],
                apply_info: [],
                show: false,
                appliedId: 0,
                base_url: "/recruiter/jobpost/jobapply-list",
                // columns: columns,
                sortOrders: sortOrders,
                filteredData: filteredData,
              
                arr_status: [
                    { id: this.$configs.job_apply.under_review, checked: false },
                    { id: this.$configs.job_apply.declined, checked: false },
                    { id: this.$configs.job_apply.jobseeker_claimed, checked: false },
                    // { id: this.$configs.job_apply.billed, checked: false },
                    // { id: this.$configs.job_apply.payment_confirmed, checked: false }
                ],
                showJobDetailsPopup: false,
                selectedJob: {},
			    employment_types: [],   
                lang:{
                    days: ['日', '月', '火', '水', '木', '金', '土'],
                    months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                    placeholder: {
                        date: '年 - 月 - 日',
                    }
                },  
                visible:false,
                current:null,
                old_index:"",
                status:false
            }
        },
        computed: {
            ...mapGetters(["currentUser","authError"]),  
            totaljob_apply: function() {
                return this.$data.projects.total;
            }  
        },
        created() {
            // No need to get card info
            // this.$api.get('/recruiter/card-info/'+ this.currentUser.id).then((r) => {
            //     this.card_info = r.data.card_info;
            //     if(this.card_info.result == 1) {
            //         this.mask_card = this.card_info.card_number_mask;
            //     }
            // })

            this.$api.get('/recruiter/getalldata')
            .then(r => {
                console.log("emp",r)
                this.employment_types = r.data.employment;	
            });
        },
        methods: {
            showJobDetail(project,showAsScoutEntry = false) {
                this.selectedJob = {
                    job_id: project.job_id,
					recruiter_show_name: project.recruiter_show_name,
                    job_number: project.job_number,
                    title: project.title,
                    occupation_description: project.occupation_description,
                    employment_types: project.employment_types,
                    job_description: project.job_description,
                    qualification: project.qualification,
                    job_location: project.job_location,
					allowance: project.allowance,
					job_start_date: project.job_start_date,
					application_address: project.application_address,
					message: project.message,
					job_post_date: project.job_post_date,
					country_name: project.country_name,
					occupation_name: project.occupation_name,
                    other_keywords: project.other_keywords,
                    showAsScoutEntry: showAsScoutEntry,
                    created_at: project.created,
                    updated: project.updated,
                };
                this.showJobDetailsPopup = true;
            },

            PopupClosed(e) {
                this.showJobDetailsPopup = e.show;
                this.selectedJob = {};
            },

            textEllipsis(index,event){
                if(event.target.className == "txt-vertical-ellipsis") {
                    event.target.className = "";
                } 
                else if(event.target.className == "") {
                    event.target.className = "txt-vertical-ellipsis";
                }
            },
            navigateToSeekerDetail(applyId) {
                this.$router.push({name: 'JobSeekerInfo', params: { transactionId: Number(applyId), type: 'apply' }});
            },
			
            allowChat(status) {   
                return (status == this.$configs.job_apply.under_review || status == this.$configs.job_apply.unclaimed || status == this.$configs.job_apply.billed || status == this.$configs.job_apply.payment_confirmed);
            },
			// allowDownloadInvoice(status) {
			// 	return status == this.$configs.job_apply.billed;
			// },
			// allowPayment(status) {
			// 	return status == this.$configs.job_apply.billed;
            // },
            unclaimed(status, realStatus){   
                return (this.$configs.job_apply.unclaimed.includes(status) && (this.$configs.job_apply.billed == realStatus));
            },
            noticeAccept(proj) {   
                return (proj.job_apply_status == this.$configs.job_apply.under_review && proj.send_noti == 0);
            },
            showNotice(p) {
                this.notice_data.type = 'apply';
                this.notice_data.id = p.jobapply_id;
                this.notice_data.recruiter_name = p.recruiter_name;
                this.notice_data.recruiter_number = p.recruiter_number;
                this.notice_data.jobseeker_name = p.jobseeker_name;
                this.notice_data.jobseeker_number = p.jobseeker_number;
                this.notice_data.jobseeker_furigana_name = p.jobseeker_furigana_name;
                this.notice_data.management_number = p.management_number;
                this.notice_data.job_number = p.job_number;
                this.notice_data.job_id = p.job_id;
                this.notice_data.title = p.title;

                this.notice_data.accept_decline = '採用',
                this.notice_data.notice_comment = null,
                this.showNoticeBox = true;
            }, 
            sendNotice() {
                let loader = this.$loading.show();
                
                this.$api.post("/recruiter/notice-send", this.notice_data).then(() => {
                    // loader.hide();
                    this.showNoticeBox = false;
                    location.reload();
                })
            },
			startChat(jobapply) {
				const payload = {
					recruiter_id: jobapply.recruiter_id,
					jobseeker_id: jobapply.jobseeker_id,
					scoutid_or_applyid: jobapply.jobapply_id,
					type: 'job-apply',
                    status: jobapply.job_apply_status,
				};
				this.$emit('chatStarted', payload);
			},
			generateBill(jobapplyId, index) {
				const loading = this.$loading.show({
					isFullPage: false,
					container: this.$refs.progressContainer,
				});
				this.$api.post(`/recruiter/jobapply-list/${jobapplyId}/invoice`, {}, { responseType: "arraybuffer" })
				.then((r) => {
					const type = r.headers["content-type"];
					const blob = new Blob([r.data], { type: type, encoding: "UTF-8" });
					const filename = r.headers["content-disposition"]
						.split("=")[1]
                        .replace(/^"+|"+$/g, "");
                    const objectUrl = URL.createObjectURL(blob);
                    // if (window.navigator && window.navigator.msSaveOrOpenBlob) {
    				// 	window.navigator.msSaveOrOpenBlob(blob, filename);  
					// } else {
    				// 	const objectUrl = URL.createObjectURL(blob);
    				// 	window.open(objectUrl);
					// }
					// const objectUrl = window.URL.createObjectURL(blob);
					// window.open(objectUrl);
					const link = document.createElement("a");
					link.href = objectUrl;
					link.download = filename;
					link.click();
					setTimeout(function () {
    					// For Firefox it is necessary to delay revoking the ObjectURL
    					window.URL.revokeObjectURL(objectUrl);
  					}, 100);
				})
				.catch(error => console.log(error))
				.finally(() => { loading.hide() });
			},
			creditPay(applyId) {
            this.appliedId = applyId;
            this.$api.get(`/recruiter/transaction-list/${this.appliedId}/apply/transaction-info`)
            .then((r) => { 
                this.apply_info = r.data.info;
                this.confirmChoice('new');
                // No need to get card info
                // if(this.card_info.result == 1) {
                //     this.show = true;
                // }
                // else{
                //     this.confirmChoice('new');
                // }
                
            })          
        },
        confirmChoice(ch) {
            let loader = this.$loading.show();
            this.apply_info.choice = ch;
            // this.apply_info.choice = this.card_choice;
            this.$api.post(`/recruiter/transaction-list/credit-payment`, this.apply_info)
            .then((res) => {
                if(res.data.status == 'success'){
                    window.location.href = res.data.link;
                    // window.open(res.data.link, '_blank');
                }
                else{
                    loader.hide(); 
                    this.show = true;                   
                    // this.errorMsg = res.data.msg;
                    if(res.data.error_code == '604' || res.data.error_code == 604){
                        this.errorMsg = "すでに決済済みです。";
                        // this.errorMsg = "このリンクは無効です。";
                        this.filterFun(); 
                    }
                    else{                        
                        this.errorMsg = res.data.msg;
                        this.filterFun(); 
                    }                    
                }
                // if(this.card_choice == 'old'){
                //     window.location.href = res.data.link;
                // }
                // else{
                //     loader.hide();
                //     window.open(res.data.link, '_blank');
                // }
            })

        },
         showToggle(index) {
            this.current = index;
            if (this.status == true) {
                if (this.current == this.old_index) this.status = false;
            } else {
                this.status = true;
            }
            this.old_index = index;
        }
        },
        
    }
</script>
<style scoped>
.pc {
    display: block;
}
.sp {
    display: none;
}
.searchbtn{
    margin-left: 10px;
}
.modal-wrapper .modal-container {
    width: 650px;
    margin: 0px auto;
    padding: 27px 40px;
    background-color: transparent;
    border-radius: 5px;
    box-shadow: none;
    transition: all 0.3s ease;
}
.notice-box {
    padding-top: 15px!important;
    background: #fff!important;
    width: 500px!important;
}

.notice-header {
    font-weight: bold!important;
    padding-left: 0px!important;
}
.table {
    margin-bottom: -3px;
}
.noti-txt{
    color:#8dacb2;
    margin-bottom:10px !important;
}
/*
.txt-underline {
    padding-bottom: 3px;
    border-bottom: 1px solid;
    cursor: pointer;
}
*/
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
/*tooltip for status*/
.tooltip {
    width: 500px;
    background-color: #5cb1ab;
    color: #fff;   
    border-radius: 6px;
    padding: 5px 10px;
    position: absolute;
    z-index: 1;
    bottom: 150%;
}
.tooltip-box .tooltiptext {     
    visibility: visible;
    background-color: rgba(94,94,99,1);
    color: #fff;
    text-align: center;
    word-break: break-word;
    border: 1px solid #363030ee;
    border-radius: 5px;
    padding: 20px;
    position: absolute;
    z-index: 33;
    bottom: 20%;
    opacity: 0.9;
    min-width: 517px;
    text-align: left;
    margin-left: 0px;
}
.tooltip-box .tooltiptext::after{
    display: none;
}
.tooltip-box table{
    border-collapse: initial !important;
}
.tooltip-box table tr{
    vertical-align: top;
    background: none !important;
}
.tooltip-head{
    font-size: 16px;
    font-weight: bold;
    padding-bottom: 10px;
}
.w-105{
    width: 105px;
}
.tbl-wxs{
    width: 100px;
}
.bg {
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 10000;
  transition: .3s;
}
/* mobile responsive */
@media (max-width:1280px){
    .tbl-status {
        width: 90px;
    }
}
@media (max-width: 1200px) {    
    .table td:nth-child(3) {
        width: 80px;
    }
    .table td:last-child {
        width: 90px;
    }
    .tbl-wm{
        width: 100px;
    }
    .tbl-wxl{
        width: 100px;
    }    
    .tbl-1024 .d-flex{
        display:block !important;
         text-align: left;
    }
    .table-btn{
        width:80px;
    }
}
@media (max-width: 812px) {
    .pc {
        display: none;
    }
    .sp {
        display: block;
    } 
    .noti-txt{
        margin: 0px 10px;
    }
    .inner-wrapper {
        padding: 15px;
    }
    .table td:first-child {
        width: 5%;
        vertical-align: top;
    }
    .table td:last-child {
        width: 95%;
    }
    .tbl-col {
        position: relative;
        padding: 10px;
    } 
    .job-tit {
        width: 90%;
    }   
    .tbl-data {
        width: auto;
        display: block;
        vertical-align: middle;
        padding-left: 0px;
        height: 0;
        margin-bottom: 0;
        transform: scaleY(0);
        transform-origin: top;
        transition: transform 0.4s ease;
        text-align: left;
        border: 1px solid #ebe8e8;
    }
    .tbl-expand {
         height: auto !important;
        transform: scaleY(1) !important;
        margin: 15px 15px 30px 15px;
       
    }
    .toggle-menu {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #8eacb2;
        width: 30px;
        height: 30px;
        font-size: 20px;
        text-align: center;
        line-height: 30px;
        vertical-align: middle;
        border: 1px solid;
        border-radius: 50%;
    }
    .toggle-menu i {
        transition: ease-in-out .3s;
    }
     .rotate  i {
        transform: rotate(180deg);
    }
    .toggle-list {
        display: flex;
        border-bottom: 1px solid #ebe8e8;   
    }
    .list-l {
        width: 38%;
        color: #666;
        font-weight: bold;
        padding: 10px;
        border-right: 1px solid #ebe8e8;
    }
    .list-r {
        width: 62%;
        padding: 10px;
    }
}
@media (max-width: 767px) { 
.btn-m {
    width: 124px;
    padding: 8px 5px;
}
.noti-txt{
    margin:0px 0px 10px !important;
}
}
@media only screen and (max-width: 812px) and (orientation: landscape){	
	.notice-body-wrapper{
		height: 210px;
        min-height: 150px !important;
        overflow-y: scroll;
        padding: 0px 20px;
	}	
}

@media (max-width: 480px) {
    .job-tit {
        width: 85%;
    }    
    .no-data-txt-border {
        padding: 50px 5px !important;
        font-size: 13px !important;
    }
    /*tooltipbox*/
    .tooltip-box .tooltiptext{
        min-width: 300px;
    }
    .w-105{
        width: 50px;
    }    
    /* modal wrapper*/
    .modal-wrapper .modal-container {
        width: 95%!important;
        padding: 20px;
    }    
	.notice-body-wrapper{
		height: auto;
		overflow: unset;
		max-height: unset;
	}
}

</style>