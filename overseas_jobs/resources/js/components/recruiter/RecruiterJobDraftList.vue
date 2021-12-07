<template>
<div class="user-main">       
    <h2 class="user-head-tit recruiter-primarylight-color">{{ $t('draftList.title') }}</h2>         
    <div class="content-wrap">
        <div class="container p-0">
        <div class="searchform-one">
                <div class="inner-wrapper">
                <div class="form-group row">                       
                    <div class="col-12 col-md-3 col-sm-4 mb-sm-0 mb-3 searchform-one">
                        <label for="企業番号">{{ $t('draftList.inbox') }}</label>
                            <input type="text" class="form-control" :placeholder="[[$t('draftList.placeholder') ]]" id="inputGroup" v-model="filteredData.job_title"/>
                    </div> 
                    <div class="col-12 col-md-9 col-sm-8 text-right align-self-end">
                        <span class="btn recruiter-primary-color primary-btn border-style btn-m" @click="filterFun()">{{ $t('common.search') }}</span>
                    </div>                      
                </div>  
                </div>
                <!-- <div class="row col-12"><label>{{ $t('draftList.status') }}</label></div>
                <div class="form-group row" style="margin-left:18px;">
                        <div class="col-md-1 p-0">
                            <input type="checkbox" class="custom-control-input custom-checkbox" value="1"
                                v-model="filteredData.job_list_status" @change="filterFun()" id="有効" />
                            <label for="有効" class="custom-control-label custom-checkbox-label"> {{ $t('draftList.private') }} </label>
                            
                        </div>
                        <div class="col-md-1 p-0">
                            <input type="checkbox" class="custom-control-input custom-checkbox" value="2"
                                v-model="filteredData.job_list_status" @change="filterFun()" id="無効" />
                            <label for="無効" class="custom-control-label custom-checkbox-label">{{ $t('draftList.public') }}</label>
                        </div>
                        <div class="col-md-1 p-0">
                            <input type="checkbox" class="custom-control-input custom-checkbox" value="3"
                                v-model="filteredData.job_list_status" @change="filterFun()" id="退会" />
                            <label for="退会" class="custom-control-label custom-checkbox-label">{{ $t('draftList.withdraw') }}</label>
                        </div>                           
                </div> -->
                
                <div class="form-group row mb-0">
                    <!--
                    <div class="col-md-2">
                        <select v-model="tableData.length" @change="getData()" >
                            <option v-for="(records, index) in perPage" :key="index" :value="records">{{records}}
                            </option>
                        </select>
                    </div>                       
                    -->
                    <div class="col-12 col-sm-5 mb-2 pr-0">
                        <button class="btn primary-btn border-style cancel-color" :disabled="isDisabled" @click="deleteData('draft')">{{ $t('common.alldelete') }}</button>
                    </div>
                    <div class="col-12 col-sm-7 text-right align-self-lg-center pl-0">
                        <span class="text-label-color">{{ $t('draftList.result') }}：<span class="pl-1">{{projects.length}}</span> {{ $t('draftList.total') }}</span>
                    </div>
                    <div class="col custom-table">
                            <div class="pc">  
                            <DataTable ref="datatable" :columns="$t('draftList.columns') " :showCheckbox="true" :sortOrders="sortOrders" class="table-check"
                                    @check-all="selectAll" >
                                    <tbody>
                                        <tr v-for="project in projects" :key="project.id">
                                            <td class="text-center">
                                                <label class="form-checkbox">
                                                    <span v-if="delete_ids_transactions.length > 0"  >
                                                    <span v-for="undelete_id in delete_ids_transactions" :key="undelete_id.id" >
                                                    <!--   {{ undelete_id == project.id ? '削除できません' : ''}}-->
                                                    <i :class="undelete_id == project.id ? 'glyphicon glyphicon-exclamation-sign':''" title="削除できません"></i>
                                                    </span>                                               
                                                    </span>                                                
                                                    <input type="checkbox" :value="project.id" v-model="selected" />
                                                </label>
                                            </td>
                                            <td class="recruiter-primary-color-txt cursor-pointer">
                                                <span @click="showJobDetail(project)">
                                                    <span class="txt-vertical-ellipsis">{{project.title}}
                                                    </span>
                                                </span>
                                            </td>                                           
                                            <td class="tbl-wl text-center">{{project.post_date}}</td>                                            
                                            <td class="tbl-ws text-center">
                                            <router-link :to="'/recruiter/jobs/' + project.id + '/edit'" class="btn btn-sm table-btn shadow-sm">{{ $t('common.edit') }}</router-link>                                            
                                            </td>

                                        </tr>
                                        
                                    </tbody>
                                </DataTable>
                            </div>
                             <div class="sp">  
                            <DataTable ref="datatable" :columns="$t('draftList.titles') " :showCheckbox="true" :sortOrders="sortOrders" class="table-check"
                                    @check-all="selectAll" >
                                    <tbody>
                                        <tr v-for="(project,index) in projects" :key="index">
                                            <td class="text-center">
                                                <label class="form-checkbox">
                                                    <span v-if="delete_ids_transactions.length > 0"  >
                                                    <span v-for="undelete_id in delete_ids_transactions" :key="undelete_id.id" >
                                                    <!--   {{ undelete_id == project.id ? '削除できません' : ''}}-->
                                                    <i :class="undelete_id == project.id ? 'glyphicon glyphicon-exclamation-sign':''" title="削除できません"></i>
                                                    </span>                                               
                                                    </span>                                                
                                                    <input type="checkbox" :value="project.id" v-model="selected" />
                                                </label>
                                            </td>
                                             <td class="tbl-col">
                                                <span class="txt-vertical-ellipsis recruiter-primary-color-txt job-tit" @click="showJobDetail(project)">{{project.title}}</span>
                                                <span v-on:click="showToggle(index)" class="toggle-menu" v-bind:class="{'rotate': (current === index) && (status == true)}"><i class="fa fa-caret-down"></i></span>
                                                <ul class="tbl-data" :id="project.id" v-bind:class="{'tbl-expand': (current === index) && (status == true)}">                                                    
                                                    <li class="toggle-list">
                                                        <div class="list-l">
                                                            掲載開始日
                                                        </div>
                                                        <div class="list-r">
                                                            {{project.post_date}}
                                                        </div>
                                                    </li>
                                                   
                                                    <li class="toggle-list">
                                                        <div class="list-l">
                                                            
                                                        </div>
                                                        <div class="list-r ml-auto pt-2 pb-2">
                                                            <router-link :to="'/recruiter/jobs/' + project.id + '/edit'" class="btn btn-sm table-btn shadow-sm">{{ $t('common.edit') }}</router-link>
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
                                <div slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">データがありません</div>
                                <div slot="no-more" class=""></div>
                                <div slot="error" class="no-data-txt-border recruiter-primary-color-txt">エラーが発生しました</div>
                            </infinite-loading> 
                           <!--end empty state and infinite loading-->                                                    
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
            base_url: "/recruiter/job-list"+ `${'/draft'}`,
            sortOrders: sortOrders,
            filteredData: filteredData,
            selected_Option: '',

            showJobDetailsPopup: false,
            selectedJob: {},
            employment_types: [],
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
                job_post_date: project.job_post_date,
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

         textEllipsis(index,event){
            if(event.target.className == "txt-vertical-ellipsis") {
                event.target.className = "";
            } 
            else if(event.target.className == "") {
                event.target.className = "txt-vertical-ellipsis";
            }
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
.table{
    margin-bottom: -3px;
}
.cancel-color{  
    padding: 10px;
}
//mobile responsive
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
    .inner-wrapper {
        padding: 15px;
    }
    .table td:first-child {
        width: 5%;
        vertical-align: top;
    }
    .table td:last-child {
        width: 95%;
        padding: 10px;
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
        top: 5px;
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
@media (max-width:812px){
    .searchform-one .form-group{
        margin-bottom: 0px;
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