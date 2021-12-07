<template>    
    <div class="user-main">       
        <h2 class="user-head-tit recruiter-primarylight-color">{{ $t('joblist.title') }}</h2>         
        <div class="content-wrap">
            <div class="container p-0">
                <div class="searchform-one">
                <div class="inner-wrapper">
                    <div class="form-group row">                       
                        <div class="col-12 col-md-3 col-sm-4 mb-sm-0 mb-3 searchform-one">
                            <label for="企業番号">{{ $t('joblist.job_num')}}</label>
                            <input type="text" class="form-control" :placeholder="[[$t('joblist.placeholder_job_num') ]]" id="inputGroup" v-model="filteredData.job_num"/>
                        </div> 
                        <div class="col-12 col-md-3 col-sm-4  mb-sm-0 mb-3 searchform-one">
                            <label for="求人タイトル">{{ $t('joblist.jobtitle')}}</label>
                            <input type="text" class="form-control" :placeholder="[[$t('joblist.placeholder_jobtitle') ]]" id="inputGroup" v-model="filteredData.job_title"/>
                        </div>     
                        <div class="col-12 col-md-6 col-sm-4 text-right align-self-end">
                            <span @click="filterFun()" class="btn recruiter-primary-color primary-btn border-style btn-m">{{ $t('common.search')}}</span>
                        </div>                
                    </div>  
                </div>
                <div class="form-group row col-12 mb-4">
                    <div class="row col-12"><label for="ステータス">{{ $t('joblist.status')}}</label>
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
                        <tr><td class="w-105"> 公開</td><td>求職者会員全員に公開している求人。スカウトに用いることも可能。</td></tr>
                        <tr><td class="w-105">非公開</td><td>スカウト専用の非公開の求人</td></tr>
                        <tr><td class="w-105">停止</td><td>募集を停止している求人</td></tr>                        
                     </tbody>
                     </table>
                     </span>
                    <!-- end status click -->
                        </div>
                        <div class="d-sm-flex">                            
                            <div class="mr-5 mb-sm-0 mb-2">
                                <input type="checkbox" class="custom-control-input custom-checkbox" value="1"
                                    v-model="filteredData.job_list_status" @change="filterFun()" id="release" />
                                <label for="release" class="custom-control-label custom-checkbox-label">{{ $t('joblist.release')}}</label>
                                <!--<span class="tooltiptext">{{ $t('joblist.release')}}</span>-->
                            </div>    
                            <div class="mr-5 mb-sm-0 mb-2">
                                <input type="checkbox" class="custom-control-input custom-checkbox" value="2"
                                    v-model="filteredData.job_list_status" @change="filterFun()" id="private" />
                                <label for="private" class="custom-control-label custom-checkbox-label">{{ $t('joblist.private')}}</label>
                               <!-- <span class="tooltiptext">{{ $t('joblist.private')}}</span> -->
                            </div> 
                            <div class="mr-5 mb-sm-0 mb-2">
                                <input type="checkbox" class="custom-control-input custom-checkbox" value="3"
                                    v-model="filteredData.job_list_status" @change="filterFun()" id="stop" />
                                <label for="stop" class="custom-control-label custom-checkbox-label">{{ $t('joblist.stop')}}</label>
                                <!--<span class="tooltiptext">{{ $t('joblist.stop')}}</span>-->
                            </div>                          
                        </div>                         
                </div>
                <div class="form-group row mb-0">
                    
                    <div class="col-12 col-sm-5 mb-2 pr-0">
                        <button class="btn primary-btn border-style cancel-color" :disabled="isDisabled" @click="deleteData('post')">{{ $t('common.alldelete')}}</button>
                    </div>
                    <div class="col-12 col-sm-7 text-right align-self-lg-center pl-0">
                         <span class="text-label-color">{{ $t('joblist.result')}} :{{projects.length}} {{ $t('joblist.total')}}</span>
                    </div>
                    <div class="col-12 custom-table pc">
                        <DataTable ref="datatable" class="table-check" :columns="$t('joblist.columns') " :showCheckbox="true" :sortOrders="sortOrders" @check-all="selectAll" >
                            <tbody>
                                <tr v-for="(project,index) in projects" :key="index">
                                    <td class="text-center">
                                        <label class="form-checkbox ">
                                            <span v-if="delete_ids_transactions.length > 0" class="tooltip-box" >
                                                <span v-for="undelete_id in delete_ids_transactions" :key="undelete_id.id" >
                                                 <!--   {{ undelete_id == project.id ? '削除できません' : ''}}-->
                                                 <!-- <span :class="undelete_id == project.id" class="tooltiptext">削除できません</span> -->
                                                  <i :class="undelete_id == project.id ? 'glyphicon glyphicon-exclamation-sign':''" title="削除できません"></i>

                                                </span>                                               
                                            </span>
                                            <input type="checkbox" :value="project.id" v-model="selected"/>
                                        </label>      
                                    </td>
                                    <td class="tbl-wxs recruiter-primary-color-txt cursor-pointer text-center num-txt-wrap"><span @click="showJobDetail(project)">{{project.job_number}}</span></td>
                                    <td class="tbl-wxl recruiter-primary-color-txt cursor-pointer"><span @click="showJobDetail(project)">
                                        <span class="txt-vertical-ellipsis">{{project.title}}</span></span>
                                    </td>
                                    <td class="tbl-count text-left">
                                        <span class="tbl-label">問合わせ/応募</span><span class="tbl-fullcol">:</span>
                                        <span class="tbl-data txt-horizontal-ellipsis">{{project.job_applies_count}}</span>
                                        
                                        <br/>

                                        <span class="tbl-label">スカウト興味あり</span><span class="tbl-fullcol">:</span>
                                        <span class="tbl-data txt-horizontal-ellipsis">{{project.scouts_count}}</span>

                                    </td>
                                   
                                    <td class="tbl-wm text-center"><pre class="mb-0 m-auto">{{project.jobPostDate}} ~ <br>{{project.addOneMonth}}</pre></td>
                                    <td class="tbl-status text-center">
                                        <select name="" id="" v-model="project.record_status" @change="changeStatus(project.id,project.record_status)">
                                            <option  v-for="status in record_status" :value="status.value" :key="status.value">{{$t(status.text)}}</option>
                                        </select>
                                    </td>
                                    <td class="tbl-ws text-center"> 
                                        <router-link :to="'/recruiter/jobs/' + project.id + '/edit'" class="btn btn-sm table-btn shadow-sm">{{ $t('common.edit')}}</router-link>                                           
                                    </td>
                                </tr>  
                                                                                               
                            </tbody>
                        </DataTable>
                        <!-- empty state and infinite loading-->
                        <div v-if="projects.length < 1 && check == 'filter'" class="no-data-txt-border recruiter-primary-color-txt">
                            検索条件に合致するデータがみつかりません
                        </div>  
                        <infinite-loading @distance="1" @infinite="getData" spinner="waveDots" v-if="check == 'scroll' || check == 'change_status'">
                            <div slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">データがありません</div>
                            <div slot="no-more" class=""></div>
                            <div slot="error" class="no-data-txt-border recruiter-primary-color-txt">エラーが発生しました</div>
                        </infinite-loading>  
                         <!-- end empty state and infinite loading-->                         
                    </div>  
                     <div class="col-12 custom-table sp">
                        <DataTable ref="datatable" class="table-check" :columns="$t('joblist.titles')" :showCheckbox="true" :sortOrders="sortOrders" @check-all="selectAll" >
                            <tbody>
                                <tr v-for="(project,index) in projects" :key="index">
                                    <td class="text-center">
                                        <label class="form-checkbox ">
                                            <span v-if="delete_ids_transactions.length > 0" class="tooltip-box" >
                                                <span v-for="undelete_id in delete_ids_transactions" :key="undelete_id.id" >
                                                 <!--   {{ undelete_id == project.id ? '削除できません' : ''}}-->
                                                 <!-- <span :class="undelete_id == project.id" class="tooltiptext">削除できません</span> -->
                                                  <i :class="undelete_id == project.id ? 'glyphicon glyphicon-exclamation-sign':''" title="削除できません"></i>

                                                </span>                                               
                                            </span>
                                            <input type="checkbox" :value="project.id" v-model="selected"/>
                                        </label>      
                                    </td>
                                    <td class="tbl-col">
                                        <span class="txt-vertical-ellipsis job-tit">{{project.title}}</span>
                                        <span class="num-color">({{project.job_number}})</span><br>
                                        <span v-on:click="showToggle(index)" class="toggle-menu" v-bind:class="{'rotate': (current === index) && (status == true)}"><i class="fa fa-caret-down"></i></span>
                                        <ul class="tbl-data" :id="project.id" v-bind:class="{'tbl-expand': (current === index) && (status == true)}">
                                            <li class="toggle-list">
                                                <div class="list-l">
                                                    求人番号
                                                </div>
                                                <div class="list-r recruiter-primary-color-txt" @click="showJobDetail(project)">
                                                    {{project.job_number}}
                                                </div>
                                            </li>
                                            <li class="toggle-list">
                                                <div class="list-l">
                                                    求人タイトル
                                                </div>
                                                <div class="list-r recruiter-primary-color-txt" @click="showJobDetail(project)">
                                                    <span class="txt-vertical-ellipsis"> {{project.title}}</span>
                                                </div>
                                            </li>
                                            <li class="toggle-list">
                                                <div class="list-l">問合わせ/応募/興味あり数</div>
                                                <div class="list-r">
                                                    <p class="mb-0"><span class="tbl-m">問合わせ/応募</span>: {{project.job_applies_count}}</p>
                                                    <p class="mb-0"><span class="tbl-m">スカウト興味あり</span>: {{project.scouts_count}}</p>
                                                </div>
                                            </li>
                                            <li class="toggle-list">
                                                <div class="list-l">
                                                    掲載期間
                                                </div>
                                                <div class="list-r">
                                                    <pre>{{project.jobPostDate}} ~ {{project.addOneMonth}}</pre>
                                                </div>
                                            </li>
                                            <li class="toggle-list">
                                                <div class="list-l">
                                                    ステータス
                                                </div>
                                                <div class="list-r">
                                                    <select name="" id="" class="form-control status-select" v-model="project.record_status" @change="changeStatus(project.id,project.record_status)">
                                                    <option  v-for="status in record_status" :value="status.value" :key="status.value">{{$t(status.text)}}</option>
                                                </select>
                                                </div>
                                            </li>
                                            <li class="toggle-list">
                                                <div class="list-l">
                                                    
                                                </div>
                                                <div class="list-r ml-auto pt-2 pb-2">
                                                    <router-link :to="'/recruiter/jobs/' + project.id + '/edit'" class="btn btn-sm table-btn shadow-sm">{{ $t('common.edit')}}</router-link>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>  
                                                                                               
                            </tbody>
                        </DataTable>
                        <!-- empty state and infinite loading-->
                        <div v-if="projects.length < 1 && check == 'filter'" class="no-data-txt-border recruiter-primary-color-txt">
                            検索条件に合致するデータがみつかりません
                        </div>  
                        <infinite-loading @distance="1" @infinite="getData" spinner="waveDots" v-if="check == 'scroll' || check == 'change_status'">
                            <div slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">データがありません</div>
                            <div slot="no-more" class=""></div>
                            <div slot="error" class="no-data-txt-border recruiter-primary-color-txt">エラーが発生しました</div>
                        </infinite-loading>  
                         <!-- end empty state and infinite loading-->                         
                    </div>                
                </div>
                </div> 
                   
            </div> 
            <transition name="fade">
                <DetailsInfoPopup v-if="showJobDetailsPopup" :show="showJobDetailsPopup" @closed="PopupClosed" :job="selectedJob" :types="employment_types" :show-job="true"></DetailsInfoPopup>
            </transition>             
        </div>
        
    </div>    
</template>
<script>
import DataTableServices from "../datatable/DataTableServices";
import DetailsInfoPopup from '../public/DetailsInfoPopup';
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    }, 
    mixins: [DataTableServices],
    components: {
        DetailsInfoPopup
    },
    data() {
        let sortOrders = {};

        let filteredData = {
                job_num: '',
                job_title: '',
                job_list_status: [],
            };
        
        return {
            selectedOption:0,
            actualOption:0,
            base_url: "/recruiter/job-list/post",
            sortOrders: sortOrders,
            filteredData: filteredData,
            selected_Option: '',
            record_status:[
                {value:1,text:'joblist.release'},
                {value:2,text:'joblist.private'},
                {value:3,text:'joblist.stop'}
                ],
            showJobDetailsPopup: false,
            selectedJob: {},
            employment_types: [],
            visible:false,
            current:null,
            old_index:"",
            status:false
            
        };
    },
    created() {
        this.$api.get('/recruiter/getalldata')
		.then(r => {
			this.employment_types = r.data.employment;	
		});
    },
 
    methods: {
        textEllipsis(){
            if(event.target.className == "txt-vertical-ellipsis"){
                event.target.className = "";
            }
            else if(event.target.className == ""){
                event.target.className = "txt-vertical-ellipsis";
            }
        },
        showJobDetail(project) {
                this.selectedJob = {
                    job_id: project.id,
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
					job_post_date: project.jobPostDate,
					country_name: project.country_name,
					occupation_name: project.occupation_name,
                    other_keywords: project.other_keywords,
                    created_at: project.created,
                    updated: project.updated,
                };
                this.showJobDetailsPopup = true;
            },

        PopupClosed(e) {
			this.showJobDetailsPopup = e.show;
			this.selectedJob = {};
        },

        changeStatus(id, recordstatus) {
            console.log("new post",recordstatus);
            // console.log(this.projects)
            let statusVal = (recordstatus == 1? '公開': (recordstatus == 2 ? '非公開' : '停止'));
            this.$alertService.showConfirmDialog(null, this.$tc('alertMessage.change_confirm_message', statusVal, { n:statusVal }), this.$t('alertMessage.yes'), this.$t('alertMessage.no'))
            .then((dialogResult) => {
                if(dialogResult.value){
                   
                    this.$api.post("/recruiter/job-list/change-status", { id, recordstatus })
                        .then(() => {
                            // this.page = 1;
                            // this.getData();
                            this.filterFun(); 
                        });
                }else{
                //    this.projects = [];
                    if(this.page > 1 ){
                        this.page -= 1;
                    }
                    this.filterFun(); 
                    this.page += 1;                        
                }
            });
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
    
    
};
</script>
<style lang="scss" scoped>
.pc {
    display: block;
}
.sp {
    display: none;
}
.num-color{
    color:#8a8a8a;
}
.table{
    margin-bottom: -3px;
}
.glyphicon-exclamation-sign{
    color:red;
}
.cancel-color{
   padding: 10px;    
}
.tbl-count{
    width: 135px;
}
.tbl-label{
    width: 115px;
    display: inline-block;
}
.tbl-fullcol{
    width: 1px;
}
.tbl-data{
    width: 40px;
    display: inline-block;
    vertical-align: middle;
    padding-left: 2px;
}
@media (max-width:1250px){
    .tbl-label{
        width: 112px;
    }
    .tbl-data{
        width: 38px;      
        padding-left: 0px;
    }
}
//table
.tbl-wxl {
    width: 220px;
}
.tbl-wm {
    width: 85px;
}
.tbl-ws {
    width: 90px;
}
//tooltip for status
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
//mobile responsive
@media (max-width:1280px){
    .tbl-status {
        width: 90px;
    }
}
@media (max-width: 1024px) { 
    
    .table td:nth-child(3) {
        min-width: 120px;
    }
    .table td:last-child {
        width: 90px;
    }
    .tbl-wm{
        width: 265px;
    }
}
@media (max-width: 812px) {
    .pc {
        display: none;
    }
    .sp {
        display: block;
    } 
    .status-select {
        width: auto;
        height: auto;
        padding: .05rem  .1rem;
        border: 1px solid;
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
}
@media (max-width: 480px) {
    .job-tit {
        width: 85%;
    }   
}
</style>