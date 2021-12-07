<template>
    <div>
        <div v-bind:class="[isSearch ? 'main-bg-img-search': 'main-bg-img', isMvheight ? mvheightClass : '']" v-if="currentUser == null" id="mainBanner">
            <PublicTopPageMenu></PublicTopPageMenu>
            <!--main banner-->
            <section v-if="!isSearch"  class="search-wrapper d-flex justify-content-center" v-bind:class="[isSticky ? stickyClass: '' || jobseekerInit.show == true ? 'mobile-search-wrapper':'']">                              

                <div class="row container clearfix p0-768">
                    <!--new design-->   
                    <div class="row container m-0-res" v-bind:class="[isSticky ? 'd-none': '']">
                        <div class="mb-txt-box" :class="[ mvTxt01 ? mv_txt01 : '' , mvTxt02 ? mv_txt02 : '' , mvTxt03 ? mv_txt03 : '' , ]">
                        <!-- <router-link to="/" > -->
                        <a @click="$router.go(0)" class="cursor-pointer">
                            <img src="/images/common/main-logo.png" alt="BORDERLESS WORKING ボーダレスワーキング" class="img-fluid">
                        </a>
                        <!-- </router-link> -->
                        <div>                        
                            <img src="/images/common/sub-logo.png" alt="BORDERLESS WORKING ボーダレスワーキング" class="img-fluid">                       
                        </div>
                        <p class="mb-txt">
                            <img src="/images/common/sub-text.png" alt="壁を突き抜け海外で活躍する人を応援するサイト" class="img-fluid">
                        </p>
                    </div>
                </div>
                <!--end new design-->  
                    <div class="col-sm-12 col-md-6 p-l">
                        <h4 class="pc-812">求人検索</h4>
                        <div class="searchdiv">
                            <table class="pc-812">
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="search-ul">
                                            <li class="form-control">                                                
                                                <input type="search" class="form-control-search-input" v-model="jobseeker.keyword"  placeholder="キーワードで絞り込む">
                                                <!-- <i class="fa fa-times search-ico" aria-hidden="true"></i> -->
                                                <!-- <span placeholder="キーワードで絞り込む">
                                                    キーワードで絞り込む
                                                    <span class="icon-delete"></span>
                                                </span> -->
                                            </li>  
                                       
                                            <li @click="callItemList('continent','jobseekerInit')" class="text-truncate">
                                                <span v-if="jobseekerInit.confirm_selected_country == 0 ">勤務地を選択</span>                                                
                                                <span v-if="jobseekerInit.confirm_selected_country == 1" class="text-truncate d-block w-179">                                                 
                                                     <span v-for="(country,index) in jobseekerInit.confirm_selected_country_list" :key="'c'+country" >   
                                                            <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>
                                                     </span>                                                    
                                                </span>
                                                <span class="fa fa-angle-down search-down"></span>
                                            </li>
                                      
                                            <li @click="callItemList('occupation','jobseekerInit')">
                                                <span v-if="jobseekerInit.confirm_selected_occupation  == 0">職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="jobseekerInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179" >
                                                    <span v-for="(occupation,index) in jobseekerInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                     </span>   
                                                    
                                                </span>
                                            </li>
                                            <!-- <li>
                                                <span id="showModalBtn" @click="showModalBtn = true">
                                                    職種を選択
                                                   <span class="drop-icon"></span>
                                                </span>
                                            </li> -->
                                        </ul>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn searchbtn clear-color" @click="clearData('jobseekerInit')">全てクリア </button>                                      
                                         <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJob()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>                                  
                                </tr>
                            </table>
                            <transition name="fade">                               
                                <div class="modal-wrapper modal-wrapper-mb" v-if="jobseekerInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5">                                                
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='country' || jobseekerInit.item =='continent'">勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='occupation'">職種</h3>
                                                <button class="cross-btn" @click="crossBtn(jobseekerInit.item, 'jobseekerInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>    
                                                </button>
                                            </div>
                                            
                                            <div v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>  
                                                     <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in jobseekerInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}  </p>
                                                        </div>
                                                    </ul>
                                                </div>
                                             
                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">                                                      
                                                        <div class="check-item" v-for="check in jobseekerInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }} <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                            <span v-if="check.checkcount" class="count-span"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>                 
                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country != null">                                                 
                                                    <h5 class="appplied-tit main-header">{{jobseekerInit.checked_country}}
                                                    <button class="float-right return-btn" @click="backData('jobseekerInit')"><i class="fa fa-arrow-left mr-2"></i>戻る</button>
                                                    </h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in jobseekerInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}</p> 
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                            </div>
                                            <div v-if="jobseekerInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in jobseekerInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(jobseekerInit.item,'jobseekerInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                     <button v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'" class="btn searchbtn" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{jobseekerInit.confirm_count}})</button>
                                                     <button v-if="jobseekerInit.item == 'occupation'" class="btn searchbtn" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{selectOccupationCount}})</button>                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 p-r">
                        <h4 class="pc-812">スカウト待ち人材検索</h4>
                        <div class="searchdiv">
                            <table class="pc-812">
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="search-ul">
                                            <li class="form-control">                                                
                                                <input type="search" class="form-control-search-input" v-model="recruiter.keyword"  placeholder="キーワードで絞り込む">
                                                <!-- <i class="fa fa-times search-ico" aria-hidden="true"></i>                                                -->
                                                <!-- <span placeholder="キーワードで絞り込む">
                                                    キーワードで絞り込む
                                                    <span class="icon-delete"></span>
                                                </span> -->
                                            </li>                                   
                                            <li @click="callItemList('continent','recruiterInit')" class="text-truncate">
                                                <span v-if="recruiterInit.confirm_selected_country == 0 ">希望勤務地を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_country == 1" class="text-truncate d-block w-179">
                                                    <span  v-for="(country,index) in recruiterInit.confirm_selected_country_list" :key="'c'+country" >   
                                                       <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>                                                    
                                                    </span>                                                    
                                                </span>
                                            </li>
                                            <li @click="callItemList('occupation','recruiterInit')">
                                                <span v-if="recruiterInit.confirm_selected_occupation  == 0">希望職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                                    <!-- <span  v-for="occupation in recruiterInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                        <span v-if="occupation.completed == true">
                                                            {{occupation.occupation_name}},
                                                        </span>
                                                    </span> -->
                                                    <span v-for="(occupation,index) in recruiterInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                    </span> 
                                                </span>
                                            </li>
                                       
                                        </ul>
                                    </td>
                                     <td class="align-middle">
                                        <button class="btn searchbtn clear-color" @click="clearData('recruiterInit')">全てクリア </button>
                                      
                                         <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>
                                    <!-- <td class="align-bottom">
                                        <span class="btn searchbtn shadow-none" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </span>
                                    </td> -->
                                </tr>
                            </table>
                            <transition name="fade">
                                <div class="modal-wrapper modal-wrapper-mb" v-if="recruiterInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5"> 
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='country' || recruiterInit.item == 'continent'">希望勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='occupation'">希望職種</h3>
                                                <button class="cross-btn" @click="crossBtn(recruiterInit.item, 'recruiterInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>
                                                </button>
                                            </div>
                                            <div v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>
                                                    <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in recruiterInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p>
                                                        </div>
                                                    </ul>
                                                </div>
                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="check in recruiterInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }}
                                                            <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                             <span class="count-span" v-if="check.checkcount"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country != null">
                                                    <h5 class="appplied-tit main-header">{{recruiterInit.checked_country}}
                                                    <button v-if="recruiterInit.btn_disabled" class="return-btn float-right" @click="backData('recruiterInit')"><i class="fa fa-arrow-left mr-2"></i>戻る</button>
                                                    </h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in recruiterInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}</p>
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                            </div>
                                            <div v-if="recruiterInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in recruiterInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(recruiterInit.item,'recruiterInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                     <button v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'" class="btn searchbtn" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{recruiterInit.confirm_count}})</button>
                                                     <button v-if="recruiterInit.item == 'occupation'" class="btn searchbtn" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{selectOccupationCountRecruiter}})</button>                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <!--before search mobile-->
                    <div class="col-12 p-0 sp-812">
                    <ul role="tablist" class="nav nav-tabs sn-tabs-basic">
                        <li role="presentation">
                            <a id="tablabel1" aria-controls="tab1" tabindex="0" role="tab" data-toggle="tab" aria-selected="true" href="#tab1" @click="tabClick('tablabel1')" class="active" ref="ptab1">
                            求人検索</a>
                        </li>
                        <li role="presentation">
                            <a id="tablabel2" aria-controls="tab2" tabindex="0" role="tab" data-toggle="tab" href="#tab2" aria-selected="false" @click="tabClick('tablabel2')" ref="ptab2">スカウト待ち人材検索</a>
                        </li>                        
                    </ul>                                

                    <div class="tab-content search-tab-content">
                        <div id="tab1" aria-hidden="false" role="tabpanel" class="tab-pane active searchdiv" aria-labelledby="tablabel1" ref="tabpane1">
                            <ul class="search-ul">                                                                                                                 
                                <li @click="callItemList('continent','jobseekerInit')" class="text-truncate">
                                    <span v-if="jobseekerInit.confirm_selected_country == 0 ">勤務地を選択</span>                                                
                                    <span v-if="jobseekerInit.confirm_selected_country == 1" class="text-truncate d-block w-179">                                                 
                                            <span v-for="(country,index) in jobseekerInit.confirm_selected_country_list" :key="'c'+country" >   
                                                <span v-if="index == 0">
                                                        {{country}}
                                                </span>
                                                <span v-else>
                                                        ,{{country}}
                                                </span>
                                            </span>                                                    
                                    </span>
                                    <span class="fa fa-angle-down search-down"></span>
                                </li>                                      
                                <li @click="callItemList('occupation','jobseekerInit')">
                                    <span v-if="jobseekerInit.confirm_selected_occupation  == 0">職種を選択</span>
                                    <span class="fa fa-angle-down search-down"></span>
                                    <span v-if="jobseekerInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179" >
                                        <span v-for="(occupation,index) in jobseekerInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                <span v-if="index == 0">
                                                        {{occupation.occupation_name}}
                                                </span>
                                                <span v-else>
                                                        ,{{occupation.occupation_name}}
                                                </span>
                                            </span> 
                                    </span>
                                </li>                                 
                                <li>                              
                                    <input type="search" class="form-control-search-input-mb" v-model="jobseeker.keyword"  placeholder="キーワードで絞り込む"> 
                               </li>   
                                <li class="search-li-mb justify-content-center">
                                 <button class="btn searchbtn clear-color ml-0" @click="clearData('jobseekerInit')">全てクリア </button>                                      
                                    <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJob()">
                                    <span class="icon icon-search"></span>検索
                                </button>  
                                </li>                                                                          
                            </ul>  
                            <section  v-if="!isSearch">
                            <div class="w-100 text-center">                                   
                                    <!-- <carousel :autoplay='false' :paginationEnabled='false' :perPage="2" class="sp-812"
                                        :navigationEnabled="true"
                                        navigationNextLabel="<span class='fa fa-angle-right'></span>"
                                        navigationPrevLabel="<span class='fa fa-angle-left'></span>">
                                        <slide v-for="(keyword,index) in other_keywords" :key="index">
                                            <div @click="filteredKeywordSearch(keyword)" class="cursor-pointer">
                                                <p class="slider-img-block"><img :src="'/images/'+images[index]" alt="job-img" class="img-fluid" /></p>
                                                <p class="slide-txt primary-btn">
                                                    <span class="icon icon-search"></span>{{ $t('jobcreate.other_keywords['+index+']') }}
                                                </p>
                                            </div>
                                        </slide>
                                    </carousel>   -->
                                    <ul class="keyword-btn-list">
                                       <li v-for="(keyword,index) in other_keywords" :key="index">
                                       <p @click="filteredKeywordSearch(keyword)" class="cursor-pointer mb-0">
                                        <span class="icon icon-search"></span>{{ $t('jobcreate.other_keywords['+index+']') }}
                                       </p>
                                       </li>
                                    </ul>  
                            </div>
                            </section>                    
                          
                        </div>
                        <div id="tab2" aria-hidden="true" role="tabpanel" class="tab-pane searchdiv" aria-labelledby="tablabel2" ref="tabpane2">
                          <ul class="search-ul">                                                                                     
                            <li @click="callItemList('continent','recruiterInit')" class="text-truncate">
                                <span v-if="recruiterInit.confirm_selected_country == 0 ">希望勤務地を選択</span>
                                <span class="fa fa-angle-down search-down"></span>
                                <span v-if="recruiterInit.confirm_selected_country == 1" class="text-truncate d-block w-179">
                                    <span  v-for="(country,index) in recruiterInit.confirm_selected_country_list" :key="'c'+country" >   
                                        <span v-if="index == 0">
                                                    {{country}}
                                            </span>
                                            <span v-else>
                                                    ,{{country}}
                                            </span>                                                    
                                    </span>                                                    
                                </span>
                            </li>
                            <li @click="callItemList('occupation','recruiterInit')">
                                <span v-if="recruiterInit.confirm_selected_occupation  == 0">希望職種を選択</span>
                                <span class="fa fa-angle-down search-down"></span>
                                <span v-if="recruiterInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                    <!-- <span  v-for="occupation in recruiterInit.confirm_occupation_list" :key="occupation.id">                                                         
                                        <span v-if="occupation.completed == true">
                                            {{occupation.occupation_name}},
                                        </span>
                                    </span> -->
                                    <span v-for="(occupation,index) in recruiterInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                            <span v-if="index == 0">
                                                    {{occupation.occupation_name}}
                                            </span>
                                            <span v-else>
                                                    ,{{occupation.occupation_name}}
                                            </span>
                                    </span> 
                                </span>
                            </li>  
                            <li>                                                
                                <input type="search" class="form-control-search-input-mb" v-model="recruiter.keyword"  placeholder="キーワードで絞り込む">                                
                            </li>                             
                            <li class="search-li-mb justify-content-center">
                              <button class="btn searchbtn clear-color ml-0" @click="clearData('recruiterInit')">全てクリア </button>                            
                              <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJobseekers()">
                                <span class="icon icon-search"></span>検索
                              </button>
                            </li>                        
                        </ul>
                       
                        </div>                       
                    </div>
                    </div>
                    <!--end before search mobile -->
                </div>
            </section>
            <section v-else class="search-wrapper d-flex justify-content-center" v-bind:class="[isSticky ? RecrustickyClass: '' || jobseekerInit.show == true ? 'mobile-search-wrapper':'']">                           
                <div class="row container clearfix p0-768">
                <!--new design-->   
                <div class="row container m-0-res" v-bind:class="[isSticky ? 'd-none': '']">
                    <div class="mb-txt-box-af-search">
                    <a @click="$router.go(0)" class="cursor-pointer">
                        <img src="/images/common/main-logo.png" alt="BORDERLESS WORKING ボーダレスワーキング" class="img-fluid">
                    </a>
                    <div>                        
                        <img src="/images/common/sub-logo.png" alt="BORDERLESS WORKING ボーダレスワーキング" class="img-fluid">                       
                    </div>                    
                </div>
                </div>
                <!--end new design--> 
                    <div class="col-sm-12 col-md-6 p-l">
                        <h4 class="pc-812">求人検索</h4>
                        <div class="searchdiv">
                            <table class="pc-812">
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="search-ul">
                                            <li class="form-control">                                                
                                                <input type="search" class="form-control-search-input" v-model="jobseeker.keyword"  placeholder="キーワードで絞り込む">
                                                <!-- <i class="fa fa-times search-ico" aria-hidden="true"></i> -->
                                                <!-- <span placeholder="キーワードで絞り込む">
                                                    キーワードで絞り込む
                                                    <span class="icon-delete"></span>
                                                </span> -->
                                            </li>      
                                         
                                            <li @click="callItemList('continent','jobseekerInit')" class="text-truncate">
                                                <span v-if="jobseekerInit.confirm_selected_country == 0 ">勤務地を選択</span>                                                
                                                <span v-if="jobseekerInit.confirm_selected_country == 1" class="text-truncate d-block w-179">                                                 
                                                     <span  v-for="(country,index) in jobseekerInit.confirm_selected_country_list" :key="'c'+country" >   
                                                           <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>
                                                    </span>                                                    
                                                </span>
                                                <span class="fa fa-angle-down search-down"></span>
                                            </li>
                                            <li @click="callItemList('occupation','jobseekerInit')">
                                                <span v-if="jobseekerInit.confirm_selected_occupation  == 0">職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="jobseekerInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                                    <!-- <span v-for="occupation in jobseekerInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                        <span v-if="occupation.completed == true">
                                                            {{occupation.occupation_name}},
                                                        </span>
                                                    </span> -->
                                                    <span v-for="(occupation,index) in jobseekerInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                     </span> 
                                                </span>
                                            </li>
                                          
                                        </ul>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn searchbtn clear-color" @click="clearData('jobseekerInit')">全てクリア </button>                                     
                                         <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJob()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>
                                   
                                </tr>
                            </table>
                            <transition name="fade">
                                <div class="modal-wrapper modal-wrapper-mb" v-if="jobseekerInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5">                                                
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='country' || jobseekerInit.item =='continent'">勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='occupation'">職種</h3>
                                                <button class="cross-btn" @click="crossBtn(jobseekerInit.item, 'jobseekerInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>
                                                </button>
                                            </div>
                                            <div v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>  
                                                     <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in jobseekerInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}  </p>
                                                        </div>
                                                    </ul>
                                                </div>

                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">                                                      
                                                        <div class="check-item" v-for="check in jobseekerInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }} <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                            <span v-if="check.checkcount" class="count-span"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>                 
                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country != null">                                                 
                                                    <h5 class="appplied-tit main-header">{{jobseekerInit.checked_country}}
                                                    <button class="float-right return-btn" @click="backData('jobseekerInit')"><i class="fa fa-arrow-left mr-2"></i>戻る</button>
                                                    </h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in jobseekerInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p> 
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                            </div>
                                            <div v-if="jobseekerInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in jobseekerInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(jobseekerInit.item,'jobseekerInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                     <button v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'" class="btn searchbtn" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{jobseekerInit.confirm_count}})</button>
                                                     <button v-if="jobseekerInit.item == 'occupation'" class="btn searchbtn" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{selectOccupationCount}})</button>                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 p-r">
                        <h4 class="pc-812">スカウト待ち人材検索</h4>
                        <div class="searchdiv">
                            <table class="pc-812">
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="search-ul">
                                            <li class="form-control">                                                
                                                <input type="search" class="form-control-search-input" v-model="recruiter.keyword"  placeholder="キーワードで絞り込む">
                                                <!-- <i class="fa fa-times search-ico" aria-hidden="true"></i>                                                -->
                                                <!-- <span placeholder="キーワードで絞り込む">
                                                    キーワードで絞り込む
                                                    <span class="icon-delete"></span>
                                                </span> -->
                                            </li>                                   
                                            <li @click="callItemList('continent','recruiterInit')" class="text-truncate">
                                                <span v-if="recruiterInit.confirm_selected_country == 0 ">希望勤務地を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_country == 1" class="text-truncate d-block w-179">
                                                    <span  v-for="(country,index) in recruiterInit.confirm_selected_country_list" :key="'c'+country" >   
                                                       <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>                                                     
                                                    </span>                                                    
                                                </span>
                                            </li>
                                            <li @click="callItemList('occupation','recruiterInit')">
                                                <span v-if="recruiterInit.confirm_selected_occupation  == 0">希望職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                                    <!-- <span  v-for="occupation in recruiterInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                        <span v-if="occupation.completed == true">
                                                            {{occupation.occupation_name}},
                                                        </span>
                                                    </span> -->
                                                     <span v-for="(occupation,index) in recruiterInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                     </span> 
                                                </span>
                                            </li>
                                         
                                        </ul>
                                    </td>
                                     <td class="align-middle">
                                        <button class="btn searchbtn clear-color" @click="clearData('recruiterInit')">全てクリア </button>
                                      
                                         <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>
                                    <!-- <td class="align-bottom">
                                        <span class="btn searchbtn shadow-none" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </span>
                                    </td> -->
                                </tr>
                            </table>
                            <transition name="fade">
                                <div class="modal-wrapper modal-wrapper-mb" v-if="recruiterInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5"> 
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='country' || recruiterInit.item == 'continent'">希望勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='occupation'">希望職種</h3>
                                                <button class="cross-btn" @click="crossBtn(recruiterInit.item, 'recruiterInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>
                                                </button>
                                            </div>
                                            <div v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>
                                                    <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in recruiterInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p>
                                                        </div>
                                                    </ul>
                                                </div>
                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="check in recruiterInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }}
                                                            <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                             <span class="count-span" v-if="check.checkcount"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country != null">
                                                    <h5 class="appplied-tit main-header">{{recruiterInit.checked_country}}
                                                    <button v-if="recruiterInit.btn_disabled" class="return-btn float-right" @click="backData('recruiterInit')"><i class="fa fa-arrow-left mr-2"></i>戻る</button>
                                                    </h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in recruiterInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}</p>
                                                        </div>                                                
                                                    </ul>
                                                </div>                                                
                                            </div>
                                            <div v-if="recruiterInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in recruiterInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(recruiterInit.item,'recruiterInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                     <button v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'" class="btn searchbtn" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{recruiterInit.confirm_count}})</button>
                                                     <button v-if="recruiterInit.item == 'occupation'" class="btn searchbtn" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{selectOccupationCountRecruiter}})</button>                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                     <!--after search mobile-->
                    <div class="col-12 p-0 sp-812">
                        <ul role="tablist" class="nav nav-tabs sn-tabs-basic">
                            <li role="presentation">
                                <a id="tablabel1" aria-controls="aftertab1" tabindex="0" role="tab" data-toggle="tab" aria-selected="true" href="#aftertab1" @click="searchTabClick('job')" ref="ptab1" :class="isPtab1 ? 'active' : ''">
                                求人検索</a>
                            </li>
                            <li role="presentation">
                                <a id="tablabel2" aria-controls="aftertab2" tabindex="0" role="tab" data-toggle="tab" href="#aftertab2" aria-selected="false" @click="searchTabClick('seeker')" ref="ptab2" :class="isPtab1 ? '' : 'active'">スカウト待ち人材検索</a>
                            </li>                        
                        </ul>
                        <div class="tab-content search-tab-content">
                            <div id="aftertab1" aria-hidden="false" role="tabpanel" class="tab-pane active searchdiv" aria-labelledby="tablabel1" ref="searchtabpane1">
                               <ul class="search-ul">  
                                    <li @click="callItemList('continent','jobseekerInit')" class="text-truncate">
                                        <span v-if="jobseekerInit.confirm_selected_country == 0 ">勤務地を選択</span>                                                
                                        <span v-if="jobseekerInit.confirm_selected_country == 1" class="text-truncate d-block w-179">                                                 
                                                <span  v-for="(country,index) in jobseekerInit.confirm_selected_country_list" :key="'c'+country" >   
                                                    <span v-if="index == 0">
                                                            {{country}}
                                                    </span>
                                                    <span v-else>
                                                            ,{{country}}
                                                    </span>
                                            </span>                                                    
                                        </span>
                                        <span class="fa fa-angle-down search-down"></span>
                                    </li>
                                    <li @click="callItemList('occupation','jobseekerInit')">
                                        <span v-if="jobseekerInit.confirm_selected_occupation  == 0">職種を選択</span>
                                        <span class="fa fa-angle-down search-down"></span>
                                        <span v-if="jobseekerInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                            <!-- <span v-for="occupation in jobseekerInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                <span v-if="occupation.completed == true">
                                                    {{occupation.occupation_name}},
                                                </span>
                                            </span> -->
                                            <span v-for="(occupation,index) in jobseekerInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                    <span v-if="index == 0">
                                                            {{occupation.occupation_name}}
                                                    </span>
                                                    <span v-else>
                                                            ,{{occupation.occupation_name}}
                                                    </span>
                                                </span> 
                                        </span>
                                    </li>
                                    <li>                                                
                                        <input type="search" class="form-control-search-input-mb" v-model="jobseeker.keyword"  placeholder="キーワードで絞り込む">
                                    </li>  
                                    <li class="search-li-mb justify-content-center">
                                        <button class="btn searchbtn clear-color" @click="clearData('jobseekerInit')">全てクリア </button>                                     
                                        <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJob()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </li> 
                                </ul>
                            </div>
                            <div id="aftertab2" aria-hidden="true" role="tabpanel" class="tab-pane searchdiv" aria-labelledby="tablabel2" ref="searchtabpane2">
                                <ul class="search-ul">                                                                   
                                    <li @click="callItemList('continent','recruiterInit')" class="text-truncate">
                                        <span v-if="recruiterInit.confirm_selected_country == 0 ">希望勤務地を選択</span>
                                        <span class="fa fa-angle-down search-down"></span>
                                        <span v-if="recruiterInit.confirm_selected_country == 1" class="text-truncate d-block w-179">
                                            <span  v-for="(country,index) in recruiterInit.confirm_selected_country_list" :key="'c'+country" >   
                                                <span v-if="index == 0">
                                                            {{country}}
                                                    </span>
                                                    <span v-else>
                                                            ,{{country}}
                                                    </span>                                                     
                                            </span>                                                    
                                        </span>
                                    </li>
                                    <li @click="callItemList('occupation','recruiterInit')">
                                        <span v-if="recruiterInit.confirm_selected_occupation  == 0">希望職種を選択</span>
                                        <span class="fa fa-angle-down search-down"></span>
                                        <span v-if="recruiterInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                            <!-- <span  v-for="occupation in recruiterInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                <span v-if="occupation.completed == true">
                                                    {{occupation.occupation_name}},
                                                </span>
                                            </span> -->
                                                <span v-for="(occupation,index) in recruiterInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                    <span v-if="index == 0">
                                                            {{occupation.occupation_name}}
                                                    </span>
                                                    <span v-else>
                                                            ,{{occupation.occupation_name}}
                                                    </span>
                                                </span> 
                                        </span>
                                    </li>
                                     <li>                                                
                                        <input type="search" class="form-control-search-input-mb" v-model="recruiter.keyword"  placeholder="キーワードで絞り込む">
                                        <!-- <i class="fa fa-times search-ico" aria-hidden="true"></i>                                                -->
                                        <!-- <span placeholder="キーワードで絞り込む">
                                            キーワードで絞り込む
                                            <span class="icon-delete"></span>
                                        </span> -->
                                    </li>  
                                    <li class="search-li-mb justify-content-center">
                                        <button class="btn searchbtn clear-color" @click="clearData('recruiterInit')">全てクリア </button>
                                      
                                         <button class="btn searchbtn shadow-none base-btn-mt" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </li>   
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--end after search mobile-->
                </div>
            </section>
            <section  v-if="!isSearch" class="search-carousel-wrapper d-flex justify-content-center">            
                <div class="row container searchresult-slide pc-812">
                    <div class="col-12 text-center p-0 carouslw">
                          <div class="searchblock">
                            <carousel :autoplay='false' :paginationEnabled='false' :perPage="5">
                                <slide v-for="(keyword,index) in other_keywords" :key="index">
                                    <div @click="filteredKeywordSearch(keyword)" class="cursor-pointer">
                                        <p class="slider-img-block"><img :src="'/images/'+images[index]" alt="job-img" class="img-fluid" /></p>
                                        <p class="slide-txt primary-btn">
                                            <span class="icon icon-search"></span>{{ $t('jobcreate.other_keywords['+index+']') }}
                                        </p>
                                    </div>
                                </slide>
                            </carousel>                           
                        </div>
                    </div>
                </div>
            </section>
            <!-- main banner-->
        </div>
        <div id="mainBanner" class="main-bg-img-loggined" v-else >
            <RecruiterMenu v-if="currentUser.role_id == 2"></RecruiterMenu>
            <JobseekerMenu v-if="currentUser.role_id == 3"></JobseekerMenu>
            <!--main banner-->
            <section class="search-wrapper-job d-flex justify-content-center" v-if="currentUser.role_id == 3" v-bind:class="[isStickyJob ? JobstickyClass: '']">         
                <div class="row container clearfix p-0">
                    <div class="col-sm-12 col-md-12 position-block">
                        <div class="inner-searchdiv inner-div">
                            <h4 class="job">求人検索</h4>
                            <table>
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="inner-search-ul">
                                            <li @click="callItemList('continent', 'jobseekerInit')" class="text-truncate">
                                                <span v-if="jobseekerInit.confirm_selected_country == 0 ">勤務地を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="jobseekerInit.confirm_selected_country == 1" class="text-truncate d-block w-179">
                                                     <span  v-for="(country,index) in jobseekerInit.confirm_selected_country_list" :key="'c'+country" > 
                                                           <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>
                                                    </span>
                                                </span>
                                            </li>
                                            <li @click="callItemList('occupation', 'jobseekerInit')">
                                                <span v-if="jobseekerInit.confirm_selected_occupation  == 0">職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="jobseekerInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                                    <!-- <span  v-for="occupation in jobseekerInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                        <span v-if="occupation.completed == true">
                                                            {{occupation.occupation_name}},
                                                        </span>
                                                    </span> -->
                                                     <span v-for="(occupation,index) in jobseekerInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                     </span> 
                                                </span>
                                            </li>

                                            <li class="w-50">                                                
                                                <input type="search" class="form-control-search-input-mb" v-model="jobseeker.keyword"  placeholder="キーワードで絞り込む">
                                            </li>
                                            <li class="sp-812 bg-transparent text-center mt-2">
                                                <button class="btn searchbtn clear-color m-tb-0" @click="clearData('jobseekerInit')">全てクリア </button>                            
                                                <button class="btn searchbtn job-primary-color shadow-none m-tb-0" @click="filteredSearchJob()">
                                                    <span class="icon icon-search"></span>検索
                                                </button>   
                                            </li>
                                           
                                        </ul>
                                    </td>
                                    <td class="align-middle d-flex pc-812-flex">
                                        <button class="btn searchbtn clear-color m-tb-0" @click="clearData('jobseekerInit')">全てクリア </button>                            
                                         <button class="btn searchbtn job-primary-color shadow-none m-tb-0" @click="filteredSearchJob()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>
                                     
                                </tr>
                            </table>
                            <transition name="fade">
                                <div class="modal-wrapper modal-wrapper-mb" v-if="jobseekerInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5">                                                
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='country' || jobseekerInit.item =='continent'">勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="jobseekerInit.item =='occupation'">職種</h3>
                                                <button class="cross-btn" @click="crossBtn(jobseekerInit.item, 'jobseekerInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>
                                                </button>
                                            </div>
                                            <div v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>
                                                    <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in jobseekerInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p>
                                                        </div>
                                                    </ul>
                                                </div>

                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="check in jobseekerInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }} 
                                                            <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                            <span class="count-span" v-if="check.checkcount"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>
                                                
                                                <div class="check-block mt-4 check-inner-block" v-if="jobseekerInit.checked_country != null">
                                                    <h5 class="appplied-tit main-header">{{jobseekerInit.checked_country}}
                                                    <button v-if="jobseekerInit.btn_disabled" class="return-btn float-right" @click="backData('jobseekerInit')"><i class="fa fa-arrow-left mr-2"></i>戻る</button></h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in jobseekerInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p>
                                                        </div>                                                
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                            <div v-if="jobseekerInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in jobseekerInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','jobseekerInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(jobseekerInit.item,'jobseekerInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                    <button v-if="jobseekerInit.item == 'country' || jobseekerInit.item =='continent'" class="btn searchbtn job-primary-color" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{jobseekerInit.confirm_count}})</button>
                                                    <button v-if="jobseekerInit.item == 'occupation'" class="btn searchbtn job-primary-color" @click="confirmSelectedItem(jobseekerInit.item,'jobseekerInit')">選択する ({{selectOccupationCount}})</button>
                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>                    
                </div>
                <!-- <div class="searchresult-slide">
                    <p>search result slide</p>
                </div>-->
            </section>
            <!-- main banner-->
            <section class="search-wrapper-recruiter d-flex justify-content-center" v-if="currentUser.role_id == 2" v-bind:class="[isStickyRecru ? RecrustickyClass: '']">         
                <div class="row container clearfix p-0">
                    <div class="col-sm-12 col-md-12 position-block">
                        <div class="inner-searchdiv inner-div">
                            <h4 class="recruiter">スカウト待ち人材検索 </h4>
                            <table>
                                <tr>
                                    <td class="align-bottom">
                                        <ul class="inner-search-ul">
                                            <li @click="callItemList('continent', 'recruiterInit')">
                                                <span v-if="recruiterInit.confirm_selected_country == 0 ">希望勤務地を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_country == 1" class="text-truncate d-block w-179">                                                   
                                                      <span  v-for="(country,index) in recruiterInit.confirm_selected_country_list" :key="'c'+country" >     
                                                            <span v-if="index == 0">
                                                                 {{country}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{country}}
                                                            </span>                                                    
                                                    </span>
                                                </span>
                                            </li>
                                            <li @click="callItemList('occupation', 'recruiterInit')">
                                                <span v-if="recruiterInit.confirm_selected_occupation  == 0">希望職種を選択</span>
                                                <span class="fa fa-angle-down search-down"></span>
                                                <span v-if="recruiterInit.confirm_selected_occupation == 1" class="text-truncate d-block w-179">
                                                    <!-- <span  v-for="occupation in recruiterInit.confirm_occupation_list" :key="occupation.id">                                                         
                                                      
                                                        <span v-if="occupation.completed == true">
                                                            {{occupation.occupation_name}},
                                                        </span>
                                                    </span> -->
                                                     <span v-for="(occupation,index) in recruiterInit.confirm_selected_occupaton_list" :key="occupation.id" >   
                                                            <span v-if="index == 0">
                                                                 {{occupation.occupation_name}}
                                                            </span>
                                                            <span v-else>
                                                                 ,{{occupation.occupation_name}}
                                                            </span>
                                                     </span> 
                                                </span>
                                            </li>

                                            <li class="w-50">                                                
                                                <input type="search" class="form-control-search-input-mb" v-model="recruiter.keyword"  placeholder="キーワードで絞り込む">
                                            </li>
                                            <li class="sp-812 bg-transparent text-center mt-2">
                                                <button class="btn searchbtn clear-color m-tb-0 ml-0" @click="clearData('recruiterInit')">全てクリア </button>                                      
                                                <button class="btn searchbtn recruiter-primary-color shadow-none m-tb-0" @click="filteredSearchJobseekers()">
                                                    <span class="icon icon-search"></span>検索
                                                </button>   
                                            </li>                                          
                                           
                                        </ul>
                                    </td>
                                     <td class="align-middle d-flex pc-812-flex">
                                        <button class="btn searchbtn clear-color m-tb-0" @click="clearData('recruiterInit')">全てクリア </button>                                      
                                         <button class="btn searchbtn recruiter-primary-color shadow-none m-tb-0" @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </button>
                                    </td>
                                    <!-- <td class="align-center">
                                        <span class="btn searchbtn recruiter-primary-color shadow-none mb-0"
                                            @click="filteredSearchJobseekers()">
                                            <span class="icon icon-search"></span>検索
                                        </span>
                                    </td> -->
                                </tr>
                            </table>
                            <transition name="fade">
                                <div class="modal-wrapper modal-wrapper-mb" v-if="recruiterInit.show">
                                    <div class="modal-block">
                                        <div class="modal-container">
                                            <div class="head-wrap m-b-5"> 
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='country' || recruiterInit.item == 'continent'">希望勤務地</h3>
                                                <h3 class="text-left main-header header" v-if="recruiterInit.item =='occupation'">希望職種</h3>
                                                <button class="cross-btn" @click="crossBtn(recruiterInit.item, 'recruiterInit')">
                                                    <i class="fa fa-times pr-1"></i><span class="pc-812">閉じる</span>
                                                </button>
                                            </div>
                                            <div v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header">人気エリア</h5>
                                                    <ul class="check-list" >
                                                        <div class="check-item" v-for="(check,index) in recruiterInit.popular_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'popular-country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }} </p>
                                                        </div>
                                                    </ul>
                                                </div>

                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country == null">
                                                    <!-- <h5 class="appplied-tit main-header">Continent Name</h5> -->
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="check in recruiterInit.continent_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(check.continent_name,'continent','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            {{ check.continent_name }}
                                                            <span class="glyphicon glyphicon-chevron-right seemore-span"></span>
                                                             <span class="count-span" v-if="check.checkcount"> ({{check.checkcount}})  </span>
                                                        </div>                                                
                                                    </ul>
                                                </div>
                                                
                                                <div class="check-block mt-4 check-inner-block" v-if="recruiterInit.checked_country != null">
                                                    <h5 class="appplied-tit main-header">{{recruiterInit.checked_country}}
                                                    <button v-if="recruiterInit.btn_disabled" class="return-btn float-right" @click="backData('recruiterInit')"><i class="fa fa-arrow-left mr-2"></i> 戻る </button>
                                                    </h5>
                                                    <ul class="check-list">
                                                        <div  class="check-item" v-for="(check,index) in recruiterInit.country_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'country','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                            <p style="margin:0;"><span class="fa fa-check p-r-2"></span>{{ check.country_name }}</p>
                                                        </div>                                                
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                            <div v-if="recruiterInit.item == 'occupation'">
                                                <div class="check-block">
                                                <h5 class="appplied-tit main-header"></h5>
                                                <ul class="check-list" >
                                                    <div  class="check-item" v-for="(check,index) in recruiterInit.occupation_list" :class="{ 'checks__completed': check.completed }"  @click="checkItem(index,'occupation','recruiterInit')"  transition="fade" :key="check.key">                                                     
                                                        <!--
                                                        <button class="to-check" @click.stop="removeTodo(index)" > 
                                                            <span class="fa fa-check p-r-2"></span>
                                                        </button>
                                                        -->
                                                         <p style="margin:0;"><span class="fa fa-check p-r-2"></span> {{ check.occupation_name }}</p>  
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <!--<div class="row underline"></div>-->
                                            <ul class="button-block">
                                                <li>
                                                    <button class="btn searchbtn clear-color list-wrap" @click="clearSelectedItem(recruiterInit.item,'recruiterInit')">全てクリア </button>
                                                </li>
                                                <li> 
                                                     <button v-if="recruiterInit.item == 'country' || recruiterInit.item == 'continent'" class="btn searchbtn recruiter-primary-color" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{recruiterInit.confirm_count}})</button>
                                                     <button v-if="recruiterInit.item == 'occupation'" class="btn searchbtn recruiter-primary-color" @click="confirmSelectedItem(recruiterInit.item,'recruiterInit')">選択する ({{selectOccupationCountRecruiter}})</button>
                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>                    
                </div>
                <!-- <div class="searchresult-slide">
                    <p>search result slide</p>
                </div>-->
            </section>
        </div>

        <!--search result -->
        <PublicSearchResult  :currentPage="search_result_current_page" :keywordSearch="keywordSearch" :jobSeekerData="jobseeker"  :recruiterData="recruiter" ref="searchResult" :seeAll="null" @onTabChange="searchResultTabChanged"></PublicSearchResult>
        <!--search result -->
    </div>
</template>
<script>

import PublicTopPageMenu from "../../menu/PublicTopPageMenu";
import JobseekerMenu from "../../menu/JobseekerMenu";
import RecruiterMenu from "../../menu/RecruiterMenu";
import PublicSearchResult from '../home/PublicSearchResult';
import { mapGetters } from "vuex";
import _ from 'lodash';
import { registerUser } from '../../../partials/auth';


export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        meta: [
            { name: 'description', content: 'ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。 オンラインで手続きができ、チャットで企業とやり取りができます。海外就職コーディネーターが徹底的にサポートいたします。｜海外就職・海外求人ならボーダレスワーキング' },
        ]
    },
    data() {
        return {  
            isPtab1: true,       
            searchType:'',
            popular:[],
            scouted_count:0,            
            search_result_current_page:'',  
            isSearch:false,  
          
            jobseekerInit:{
                show: false,
                continent_list:[],
                popular_list:[],
                confirm_popular_list:[],
                country_list:[],
                confirm_country_list:[],
                main_country_list:[],
                confirm_main_country_list:[],
                selected_country_list:[],
                confirm_selected_country_list:[],
                occupation_list:[],
                confirm_occupation_list:[],   
                confirm_selected_occupaton_list:[],
                confirm_selected_country:0,
                confirm_selected_occupation:0,
                item:'',
                btn_disabled:false,
                checked_country:null,
                confirm_count:0,
                continent_name:'',
               
            },
            jobseeker:{
                keyword:'',
                country:[],
                historyCountry:[],
                occupation:[],
            },
            keywordSearch:{
                keyword:'',
                country:[],
                historyCountry:[],
                occupation:[],
            },
            recruiterInit:{
                show: false,
                continent_list:[],
                country_list:[],
                occupation_list:[],
                confirm_country_list:[],
                popular_list:[],
                confirm_popular_list:[],
                confirm_occupation_list:[],
                confirm_selected_occupaton_list:[],
                main_country_list:[],
                confirm_main_country_list:[],
                confirm_selected_country:0,
                confirm_selected_occupation:0,
                item:'',
                btn_disabled:false,
                checked_country:null,
                confirm_count:0,
                selected_country_list:[],
                confirm_selected_country_list:[],
                continent_name:'',
            },
            recruiter:{
                keyword:'',
                country:[],
                occupation:[],
            },
            isSticky:false,
            isStickyJob:false,
            isStickyRecru:false,
            stickyClass:'is_sticky',
            JobstickyClass:'is_stickyjob',
            RecrustickyClass:'is_stickyrecru',
            scrollPosition:0,
            other_keywords:['新卒歓迎','未経験歓迎','シニア歓迎','本社採用','語学不問'],
            images:['work1.png','work2.png','work3.png','work4.png','work5.png'],
            clientHeight: 0,
            mv_txt01 : 'mv_txt01',
            mvTxt01 : false,
            mv_txt02 : 'mv_txt02',
            mvTxt02 : false,
            mv_txt03 : 'mv_txt03',
            mvTxt03 : false,
            isMvheight : false,
            mvheightClass : 'mv_height', 
        };
    },
    components: {
        PublicTopPageMenu,
        JobseekerMenu,
        RecruiterMenu,
        PublicSearchResult
    },
    created() {
       
        let jobseeker_data = JSON.parse(localStorage.getItem('job-keyword')); 
        let recruiter_data = JSON.parse(localStorage.getItem('jobseeker-keyword')); 
        let searchType = '';
        if(jobseeker_data){
            searchType = 'jobseekerInit';
            this.searchType = searchType;
        }
        else{
            searchType = 'recruiterInit';
           this.searchType = searchType;
        }
      
        this.$api.get('country_occupation_list/jobseeker', this.formRegister).then(res => {
            this.jobseekerInit.main_country_list = _.cloneDeep(res.data.country);   
            this.jobseekerInit.confirm_main_country_list = _.cloneDeep(res.data.country);  
            this.jobseekerInit.popular_list = res.data.popular_country;    
            this.jobseekerInit.confirm_popular_list = _.cloneDeep(res.data.popular_country);    
            this.jobseekerInit.continent_list  = _.cloneDeep(res.data.continent);
            this.jobseekerInit.occupation_list = _.cloneDeep(res.data.occupation);
            this.jobseekerInit.confirm_occupation_list = _.cloneDeep(res.data.occupation);               
           
            if(jobseeker_data)
            {
                // country 
                this[searchType].confirm_selected_country_list = jobseeker_data.country;
                this[searchType].selected_country_list = jobseeker_data.country;
                if(jobseeker_data.country.length > 0){
                    this[searchType].confirm_selected_country = 1;
                }else{
                    this[searchType].confirm_selected_country = 0;
                }
               
                jobseeker_data.country.map(x=>{
                     this[searchType].popular_list.filter(y=>{
                        if(y.country_name == x)
                        {
                            y.completed = true;
                        }
                        return y;
                    })

                    this[searchType].main_country_list.filter(y=>{
                        if(y.country_name == x)
                        {
                            y.completed = true;
                        }
                        return y;
                    })

                    this[searchType].selected_country_list.filter(z=>{
                        if(z.country_name == x){
                            z.completed = true;
                        }
                        return z;
                    })
                })

                this[searchType].confirm_popular_list = _.cloneDeep(this[searchType].popular_list) ;
                this[searchType].confirm_main_country_list = _.cloneDeep(this[searchType].main_country_list);

              
               this.continentCount(searchType,'continent');
               this[searchType].confirm_count = this[searchType].confirm_selected_country_list.length;
               
            // country 
                
                
               
            // occupation
        
                if(jobseeker_data.occupation.length > 0)  {
                    
                    this[searchType].confirm_selected_occupation = 1;
                }
                else{
                   
                    this[searchType].confirm_selected_occupation = 0;
                }
                jobseeker_data.occupation.map(x=>{
                   this[searchType].occupation_list.filter(y=>{
                        if(y.id ==  x)  {
                            y.completed = true;
                        }
                        return y;
                    })
                 
                })

                this[searchType].confirm_occupation_list = _.cloneDeep(this[searchType].occupation_list);
                this[searchType].confirm_selected_occupaton_list = this[searchType].confirm_occupation_list.filter(z=>{
                    return z.completed === true;
                })
               
            }
           
            // occupation

             localStorage.removeItem('job-keyword'); 
            

        });

        this.$api.get('country_occupation_list/recruiter', this.formRegister).then(res => {
            this.recruiterInit.main_country_list = _.cloneDeep(res.data.country);   
            this.recruiterInit.confirm_main_country_list = _.cloneDeep(res.data.country); 
            this.recruiterInit.continent_list  = _.cloneDeep(res.data.continent);
            this.recruiterInit.popular_list = _.cloneDeep(res.data.popular_country);
            this.recruiterInit.confirm_popular_list = _.cloneDeep(res.data.popular_country);
            this.recruiterInit.occupation_list = _.cloneDeep(res.data.occupation);
            this.recruiterInit.confirm_occupation_list = _.cloneDeep(res.data.occupation);    
            
             if(recruiter_data)
            {
                // country 
                this[searchType].confirm_selected_country_list = recruiter_data.country;
                this[searchType].selected_country_list = recruiter_data.country;
                if(recruiter_data.country.length > 0){
                    this[searchType].confirm_selected_country = 1;
                }else{
                    this[searchType].confirm_selected_country = 0;
                }
               
                recruiter_data.country.map(x=>{
                     this[searchType].popular_list.filter(y=>{
                        if(y.country_name == x)
                        {
                            y.completed = true;
                        }
                        return y;
                    })

                    this[searchType].main_country_list.filter(y=>{
                        if(y.country_name == x)
                        {
                            y.completed = true;
                        }
                        return y;
                    })

                    this[searchType].selected_country_list.filter(z=>{
                        if(z.country_name == x){
                            z.completed = true;
                        }
                        return z;
                    })
                })

                this[searchType].confirm_popular_list = _.cloneDeep(this[searchType].popular_list) ;
                this[searchType].confirm_main_country_list = _.cloneDeep(this[searchType].main_country_list);

              
               this.continentCount(searchType,'continent');
               this[searchType].confirm_count = this[searchType].confirm_selected_country_list.length;
               
            // country 
                
                
               
            // occupation
        
                if(recruiter_data.occupation.length > 0)  {
                    
                    this[searchType].confirm_selected_occupation = 1;
                }
                else{
                   
                    this[searchType].confirm_selected_occupation = 0;
                }
                recruiter_data.occupation.map(x=>{
                   this[searchType].occupation_list.filter(y=>{
                        if(y.id ==  x)  {
                            y.completed = true;
                        }
                        return y;
                    })
                 
                })

                this[searchType].confirm_occupation_list = _.cloneDeep(this[searchType].occupation_list);
                this[searchType].confirm_selected_occupaton_list = this[searchType].confirm_occupation_list.filter(z=>{
                    return z.completed === true;
                })
               
            }
              localStorage.removeItem('jobseeker-keyword'); 
        });


       if(this.$route.path == '/recruiter')
       {
      
          this.search_result_current_page = 'recruiter';
       }
       else if(this.$route.path == '/jobseeker')
       {
        
           this.search_result_current_page = 'jobseeker'
       }
       if(jobseeker_data && this.currentUser == null)
       {
         
      
            if(this.$route.path == '/' && (jobseeker_data.keyword != '' || jobseeker_data.country.length != 0 || jobseeker_data.occupation.length != 0) )
            {
                this.search_result_current_page = 'jobseeker';
            }
            else{
                this.search_result_current_page = 'public'
            }

       }
       else if(!jobseeker_data && this.currentUser == null){
      
            this.search_result_current_page = 'public'
       }
       
        window.addEventListener('scroll',this.handleScroll);      
        window.addEventListener('resize', this.handleResize);
        this.handleResize();
    },
    destroyed(){
        window.removeEventListener('scroll',this.handleScroll);
    },
    computed: {
        ...mapGetters(["currentUser","authError"]),
        
        selectOccupationCount: function () {
            let check = this.jobseekerInit.occupation_list.filter((value) => {
                return value.completed
            });           
            return check.length; 
        }, 
        selectOccupationCountRecruiter: function () {
            let check = this.recruiterInit.occupation_list.filter((value) => {
                return value.completed
            });           
            return check.length; 
        },
    },
      
    methods: {
        tabClick(tabName){
            this.$refs.searchResult.changeTab(tabName);
        },
        searchTabClick(tabName){
            if (tabName == 'job') {
                this.search_result_current_page = 'jobseeker';
                this.filteredSearchJob();
            }
            else if (tabName == 'seeker') {
                this.search_result_current_page = 'recruiter';
                this.filteredSearchJobseekers();
            }
        },
        searchResultTabChanged(e) {
			if (e == 'tablabel1') {
				this.$refs['ptab2'].classList.remove('active');
				this.$refs['tabpane2'].classList.remove('active');
				this.$refs['ptab1'].classList.add('active');
				this.$refs['tabpane1'].classList.add('active');
			} else {
				this.$refs['ptab1'].classList.remove('active');
				this.$refs['tabpane1'].classList.remove('active');
				this.$refs['ptab2'].classList.add('active');
				this.$refs['tabpane2'].classList.add('active');
			}
		},
        getJobseekerData:function()
        {
            this.$api.get('country_occupation_list/jobseeker', this.formRegister).then(res => {
            let popular = res.data.popular_country;
            if(this.jobseekerInit.confirm_selected_country_list.length > 0){
                popular.map(e=>{
                    if(this.jobseekerInit.confirm_selected_country_list.includes(e.country_name)){
                        e.completed = true;
                    }
                })
            }
            this.jobseekerInit.popular_list = popular;    
            this.jobseekerInit.confirm_popular_list = _.cloneDeep(popular);    
           });

        },
        getRecruiterData()
        {
            this.$api.get('country_occupation_list/recruiter', this.formRegister).then(res => {
            let popular = res.data.popular_country;
            if(this.recruiterInit.confirm_selected_country_list.length > 0){
                popular.map(e=>{
                    if(this.recruiterInit.confirm_selected_country_list.includes(e.country_name)){
                        e.completed = true;
                    }
                })
            }
            this.recruiterInit.popular_list = _.cloneDeep(popular);
            this.recruiterInit.confirm_popular_list = _.cloneDeep(popular);
                    
           });      

        },

        backData(searchType) 
        {
            
            this[searchType].btn_disabled = false;
            this[searchType].checked_country = null;
            this[searchType].item = 'continent';
            this[searchType].show = true;
            this.continentCount(searchType,this[searchType].item)
            // this[searchType].country_list = [];
           
        },
         handleResize(){
             this.clientHeight = window.innerHeight;
             this.clientWidth = window.innerWidth;

             console.log('handleresize-height',this.clientHeight);               
             console.log('handleresize-width',this.clientWidth);  
            let intViewportWidth = window.innerWidth;
            let intViewportHeight = window.innerHeight;
            //   alert(intViewportHeight);
                if (intViewportHeight <= 700) {
                    this.mvTxt01 = true;
                    this.isMvheight =  true;
                    console.log("thismv",intViewportHeight);
                } else if (intViewportHeight <= 800) {
                    this.mvTxt02 = true;
                    this.isMvheight =  true;
                     console.log("thismv",intViewportHeight);
                } else if (intViewportHeight <= 960) {
                    this.mvTxt03 = true;
                    this.isMvheight =  false;
                     console.log("thismv",intViewportHeight);
                }                         
        }, 

        handleScroll(event){
            this.scrollPosition = window.scrollY                  
            var elmnt = document.getElementById("mainBanner");          
            var txt = elmnt.clientHeight;
            // console.log('mainbanner-height',txt);

            if(this.scrollPosition >=txt){
                this.isSticky = true
            }
            else{
                this.isSticky = false
            }
            if(this.scrollPosition >=txt){
                this.isStickyJob = true
             
            }
            else{
                this.isStickyJob = false
            
            }
            if(this.scrollPosition >=txt){
                this.isStickyRecru = true
               
            }
            else{
                this.isStickyRecru = false
               
            }
        },

        crossBtn($value, $searchType)
        { 
            
            this[$searchType].show = !this[$searchType].show; 
            if($value == 'country' || $value == 'continent'){    

                this[$searchType].popular_list = _.cloneDeep(this[$searchType].confirm_popular_list);
                this[$searchType].main_country_list = _.cloneDeep(this[$searchType].confirm_main_country_list);
                this[$searchType].country_list = _.cloneDeep(this[$searchType].confirm_country_list);
                this[$searchType].selected_country_list = _.cloneDeep(this[$searchType].confirm_selected_country_list);  
                this[$searchType].confirm_count = this[$searchType].selected_country_list.length;

            
                if(this[$searchType].confirm_selected_country_list.length)
                {
                    var not_include = this[$searchType].country_list.filter(e=> !this[$searchType].confirm_selected_country_list.includes(e.country_name));
                    var include = this[$searchType].country_list.filter(e=> this[$searchType].confirm_selected_country_list.includes(e.country_name))
                    not_include.map(element => { element.completed = false; });
                    include.map(element => { element.completed = true; });
                }
                else{
                    
                    this[$searchType].country_list.map(e=> {e.completed = false;});
                }

                  this.continentCount($searchType,$value); //to show count for each continent 
            }
            else{
                this[$searchType].occupation_list = _.cloneDeep(this[$searchType].confirm_occupation_list);
            }
        },
        continentCount($searchType,$value)
        {
            //to show count for each continent
            if($value == 'country') {
            
                let check = this[$searchType].country_list.filter((value) => {   
                    return value.completed == true
                });  
                let continent_index = this[$searchType].continent_list.findIndex(continent => continent.continent_name == this[$searchType].checked_country);

                this[$searchType].continent_list[continent_index]['checkcount'] = check.length; 
               
            }
            else{

                let check = this[$searchType].main_country_list.filter((value) => {   
                          return value.completed == true 
                 }); 
                 
                var count = {};
                check.map(function(i) { 
                    count[i.continent_name] = (count[i.continent_name]||0) + 1;
                });

                this[$searchType].continent_list.map(element=>{
                     if(Object.keys(count).includes(element.continent_name)){ 
                         element.checkcount = count[element.continent_name]
                     }
                     else{
                         element.checkcount = '';
                     }   
                })
            }
            //to show count for each continent 
           

        },
        callItemList($value,$searchType)
        {   
            this[$searchType].item = $value;
            this[$searchType].show = !this[$searchType].show;  
        },
        checkItem($index,$value,$searchType)
        {
            if($value == 'continent')
            {
                this[$searchType].country_list = [];
                this[$searchType].confirm_country_list = [];
                this[$searchType].checked_country = $index;

                let temp_country  = this[$searchType].main_country_list.filter((value) => {
                    return value.continent_name === this[$searchType].checked_country
                });

                this[$searchType].country_list = temp_country;
                this[$searchType].confirm_country_list = temp_country;
                this[$searchType].btn_disabled = true;
                this[$searchType].item = 'country';
            }
            if($value == 'popular-country')
            { 
               
                this[$searchType].popular_list[$index].completed = !this[$searchType].popular_list[$index].completed;

                if(this[$searchType].popular_list[$index].completed == true)
                {
                    //to increase count in confirm button
                    this[$searchType].selected_country_list.push(this[$searchType].popular_list[$index]['country_name']) 
                    this[$searchType].confirm_count += 1;   
                    //to increase count in confirm button

                    //check data also in country_list
                    this[$searchType].main_country_list.map(element => {
                     
                        if(element.country_name == this[$searchType].popular_list[$index]['country_name'])  
                        {
                            element.completed = true;
                        }
                    });  
                    //check data also in country_list
                }
                else{

                    //to reduce count in confirm button
                    let $selected_country_index = this[$searchType].selected_country_list.findIndex(continent => continent == this[$searchType].popular_list[$index]['country_name']);
                    this[$searchType].selected_country_list.splice($selected_country_index,1)
                    this[$searchType].confirm_count -= 1;  
                    //to reduce count in confirm button


                    //remove check data also in country_list
                    this[$searchType].main_country_list.map(element => {
                     
                        if(element.country_name == this[$searchType].popular_list[$index]['country_name']) 
                        {
                          
                            element.completed = false;
                            console.log(element);
                        }    
                        else{
                           
                        }
                    }); 
                    //remove check data also in country_list

                }
                this.continentCount($searchType,$value); //to show count for each continent 
            }
            if($value == 'country')
            {
       
                this[$searchType].country_list[$index].completed = !this[$searchType].country_list[$index].completed;

            
                if(!this[$searchType].selected_country_list.includes(this[$searchType].country_list[$index]['country_name'])){

                    //to increase count in confirm button
                    this[$searchType].selected_country_list.push(this[$searchType].country_list[$index]['country_name']);
                    this[$searchType].confirm_count += 1; 
                    //to increase count in confirm button

                    //check data also in popular_list
                    this[$searchType].popular_list.map(element => {
                        if(element.country_name == this[$searchType].country_list[$index]['country_name'])
                        {
                            element.completed = true;
                        }
                    }); 
                    //check data also in popular_list
                }
                else{

                    //to reduce count in confirm button
                    let $selected_country_index = this[$searchType].selected_country_list.findIndex(continent => continent == this[$searchType].country_list[$index]['country_name']);
                    this[$searchType].selected_country_list.splice($selected_country_index,1)
                    this[$searchType].confirm_count -= 1; 
                    //to reduce count in confirm button
                    
                    //remove check data also in popular_list
                    this[$searchType].popular_list.map(element => {
                        if(element.country_name == this[$searchType].country_list[$index]['country_name'])
                        {
                            element.completed = false;
                        }
                    });
                    //remove check data also in popular_list
                }

                this.continentCount($searchType,$value);
            }
            else if($value == 'occupation'){
                this[$searchType].occupation_list[$index].completed = !this[$searchType].occupation_list[$index].completed;
            }
                   
        },
        confirmSelectedItem($value,$searchType)
        {
            if($value == 'continent' || $value == 'country')
            {
               
                this[$searchType].confirm_selected_country_list = _.cloneDeep(this[$searchType].selected_country_list);
                this[$searchType].confirm_popular_list = _.cloneDeep(this[$searchType].popular_list)
                this[$searchType].confirm_country_list = _.cloneDeep(this[$searchType].country_list)
                this[$searchType].confirm_main_country_list = _.cloneDeep(this[$searchType].main_country_list);
                
                if(this[$searchType].confirm_selected_country_list.length == 0)
                {
                    this[$searchType].confirm_selected_country = 0;
                    this.jobseeker.country = [];
                }else{
                   this[$searchType].confirm_selected_country = 1;
                }
            }
            else{

                let check = this[$searchType].occupation_list.filter((value) => {
                    return value.completed
                });
                if(check.length == 0)
                {
                    this[$searchType].confirm_selected_occupation = 0;
                    this.jobseeker.occupation = [];
                }
                else{
                    this[$searchType].confirm_selected_occupation = 1;
                }
                this[$searchType].confirm_occupation_list = _.cloneDeep(this[$searchType].occupation_list);  
              
                this[$searchType].confirm_selected_occupaton_list = this[$searchType].confirm_occupation_list.filter(x=>x.completed == true);   
            }
            this[$searchType].show = false; 
        },
        
        clearSelectedItem($value,$searchType)
        {
        
            if($value == 'continent' || $value == 'country'){
                this[$searchType].continent_list.map(element => {
                    element.checkcount = '';
                });

                 this[$searchType].popular_list.map(element => {
                    element.completed  = false;
                });

                 this[$searchType].country_list.map(element => {
                    element.completed  = false;
                });

                this[$searchType].main_country_list.map(element => {
                    element.completed  = false;
                });

                this[$searchType].selected_country_list = [];
                this[$searchType].confirm_count = 0;
            }
            else if($value == 'occupation'){
                 this[$searchType].occupation_list.map(element => {
                    element.completed  = false;
                });
            }     
        },

        filteredKeywordSearch(keyword){
          
            this.keywordSearch.keyword = keyword;
            this.keywordSearch.country = [];
            this.keywordSearch.occupation = [];
            this.search_result_current_page = 'jobseeker';
            this.$refs.searchResult.filteredSearchJob('keyword',this.keywordSearch.keyword);
        },
            
        filteredSearchJob()    
        {
            if(this.$refs.ptab1) {
                this.$refs.ptab1.click();
            }
            
            this.isPtab1 = true;
            this.$refs.searchResult.seeker_filtered_page = 1;
            this.jobseeker.historyCountry = [];
            var country_arr = {};
            const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December" ];
            const d = new Date();
            let currentMonthCountry = monthNames[d.getMonth()]+'SearchHistoryCountry';

            var popular =JSON.parse(localStorage.getItem('popular'));
            if(popular){
                localStorage.removeItem('popular');
            }

            var search_histories =  JSON.parse(localStorage.getItem('search_histories'));
           
            if(search_histories)
            {
                this.popular = search_histories;
                Object.keys(this.popular[0]['country']).filter(x=>{
                    if(x == currentMonthCountry)
                    {
                        var id_list =  this.popular[0]['country'][currentMonthCountry].id;
                        var res = id_list.split(",");
                        this.jobseekerInit.confirm_selected_country_list.filter(e=>{
                            if(!res.includes(e.toString())){ 
                                res.push(e.toString());
                                this.jobseeker.historyCountry.push(e);
                                this.popular[0]['country'][currentMonthCountry].id =  res.toString();
                                localStorage.setItem('search_histories',JSON.stringify(this.popular));
                            } 

                        })  
                    }
                    else{
                      
                        delete  this.popular[0]['country'][x];
                        this.popular[0]['country'][currentMonthCountry] = {'id':this.jobseekerInit.confirm_selected_country_list.toString()};
                        this.jobseeker.historyCountry = this.jobseekerInit.confirm_selected_country_list;
                        localStorage.setItem('search_histories',JSON.stringify(this.popular));
                       
                    }
                })
 
            }else{
               
                country_arr[currentMonthCountry] = {'id':this.jobseekerInit.confirm_selected_country_list.toString()};
                this.jobseeker.historyCountry = this.jobseekerInit.confirm_selected_country_list;
                this.popular.push({"country":country_arr});   
                localStorage.setItem('search_histories', JSON.stringify(this.popular));
              
            }


            this.isSearch = true;
            this.jobseeker.country = this.jobseekerInit.confirm_selected_country_list;
            this.jobseeker.occupation = this.jobseekerInit.occupation_list.filter((value) => {
                return value.completed == true;
            }).map(function(item) {
                return item.id;
            });
            this.search_result_current_page = 'jobseeker';
            if(this.jobseeker.keyword == "" && this.jobseeker.country.length == 0 && this.jobseeker.occupation.length == 0)
            {
                 this.$refs.searchResult.filteredSearchJob('search',null);
            }
            else{
                 this.$refs.searchResult.filteredSearchJob('search','求人　検索結果');
            }
              
        },

        clearData(searchType){
                    
            this.clearSelectedItem('country',searchType);
            this.clearSelectedItem('occupation',searchType)
            this[searchType].confirm_popular_list.map(x=>{
                return x.completed = false;
            });
            this[searchType].confirm_main_country_list.map(x=>{
                return x.completed = false;
            });
            this[searchType].confirm_occupation_list.map(x=>{
                return x.completed = false;
            });
            this[searchType].confirm_selected_occupaton_list = [];
            this[searchType].confirm_selected_country_list=[];
            this[searchType].confirm_count = 0;
            this[searchType].confirm_selected_country = 0;
            this[searchType].confirm_selected_occupation = 0;

            if(searchType == 'jobseekerInit'){
                this.jobseeker.keyword = '';
                this.jobseeker.occupation = [];
                this.jobseeker.country = [];
                this.filteredSearchJob();

            }
            else{
                this.recruiter.keyword = '';
                this.recruiter.occupation = [];
                this.recruiter.country = [];
                this.filteredSearchJobseekers();
            }

            this.$refs.searchResult.check_scroll = 'scroll';

        },

        filteredSearchJobseekers()    
        { 
            if(this.$refs.ptab2) {
                this.$refs.ptab2.click();
            }
            this.isPtab1 = false;             
            this.$refs.searchResult.recruiter_filtered_page = 1;
            this.isSearch = true;
            this.recruiter.country = [];
            this.recruiter.country = this.recruiterInit.confirm_selected_country_list;

            this.recruiter.occupation = this.recruiterInit.occupation_list.filter((value) => {
                return value.completed == true;
            }).map(function(item) {
                return item.id;
            });
            this.search_result_current_page = 'recruiter';
            if(this.recruiter.keyword == "" && this.recruiter.country.length == 0 && this.recruiter.occupation.length == 0)
            {
                this.$refs.searchResult.filteredSearchJobseekers(null);
            }
            else{
                this.$refs.searchResult.filteredSearchJobseekers('スカウト待ってます　検索結果');
            }
              
        },

        closeDialog() {
            this.showModal = false;
        },
        

    }
};
</script>
<style lang="scss" scoped>
.searchbtn{
    margin-left: 10px;
}
.keyword-btn-list{
    display:inline-block;
    width:100%;
    list-style: none;   
    background-color: #b1b5d240 !important;
    padding-bottom: 10px;
    margin-bottom: 0px;
    li{         
        background: #0062ff;
        color: #fff;
        float: left;
        width: 18.3%;
        padding: 8px;
        margin: 10px 0 0 10px;       
        &:last-child{
            margin-right:10px;
        }
    }
   
    
}
.count-span{   
    width:35px;
    text-align:left;
    float:right;
}
.seemore-span{
    top:3px;
    font-size:12px;
    float:left;
    padding-right: 5px;
}
.is_stickyjob{
    position: fixed !important;
    top: 0;
    width: 100%;
    margin-top: 0px !important;
    background: #fff;
    z-index: 999;
    border-bottom: 1px solid #C5C2C2;
    box-shadow: 2px 0px 4px #D2D3D4;
    background: url(/images/common/inner_banner_jobsearch.jpg) no-repeat top;
    padding-bottom: 5px;
    padding-top: 5px;
    height: auto;
    max-width: 100%;
    background-size: cover;
    .position-block {          
            top: 0px;
            .search-down{
                top: 12px;
            }
            .inner-div {
                display: table;
                width: 100%;
                border-radius: 5px;
                box-sizing: border-box;
                // background: rgba(0, 0, 0, .3);
                background:#00002270;
                padding:10px 20px;
                table {
                    width: 100%;
                    td {
                        width: 100%;
                    }
                    .searchbtn{
                        margin-bottom: 0px;
                        line-height: 1.5;
                        .icon-search{
                            font-size: 20px;
                        }
                    }
                }
                ul.inner-search-ul {
                    display: contents;
                    vertical-align: middle;
                    li {
                        line-height: 1.5;
                        margin-bottom: 5px;
                        height: 40px;
                        min-height: 40px;
                        &:last-child {
                            width: 50%;
                        }  
                        .form-control-search-input{
                            width: 100%;
                            border: none;
                            height: 100%;
                        }                      
                    }
                }
            }
        }   
    h4{
        color:#fff;
        font-size: 1.2rem;
        padding-top: 5px;
    }
    .searchdiv{
        ul.search-ul{
            li{
                line-height: 1.5;
                margin-bottom: 5px;
                height: 40px;
                min-height: 40px;
                .form-control-search-input{
                    height: 40px;
                    padding: 0px 20px;                 
                }
                .search-down{
                    top: 12px;
                }               
            }
        }
        .searchbtn{
            margin-bottom: 5px;   
            line-height: 0px;        
            .icon-search{
                font-size: 20px;
            }
        }
    }
}
.is_stickyrecru{
    position: fixed !important;
    top: 0;
    width: 100%;
    margin-top: 0px !important;
    background: #fff;
    z-index: 999;
    border-bottom: 1px solid #C5C2C2;
    box-shadow: 2px 0px 4px #D2D3D4;
    background: url(/images/common/after-search-banner.png) no-repeat center 0;
    padding-bottom: 5px;
    padding-top: 5px;
    // height: 150px;
    height: auto;
    max-width: 100%;
    background-size: cover;
    .position-block{          
            top: 0px;
            .search-down{
                top: 12px;
            }
            .inner-div {
                display: table;
                width: 100%;
                border-radius: 5px;
                box-sizing: border-box;
                // background: rgba(0, 0, 0, .3);
                background:#00002270;
                padding:10px 20px;
                table {
                    width: 100%;
                    td {
                        width: 100%;
                    }
                    .searchbtn{
                        margin-bottom: 0px;
                        line-height: 1.5;
                        .icon-search{
                            font-size: 20px;
                        }
                    }
                }
                ul.inner-search-ul {
                    display: contents;
                    vertical-align: middle;
                    li {
                        line-height: 1.5;
                        margin-bottom: 5px;
                        height: 40px;
                        min-height: 40px;
                        &:last-child {
                            width: 50%;
                        }  
                        .form-control-search-input{
                            width: 100%;
                            border: none;
                            height: 100%;
                        }                      
                    }
                }
            }
        }  
      h4{
        color:#fff;
        font-size: 1.2rem;
        padding-top: 5px;
    }
    .searchdiv{       
        ul.search-ul{
            li{
                line-height: 1.5;
                margin-bottom: 5px;
                height: 40px;
                min-height: 40px;
                .form-control-search-input{
                    height: 40px;
                    padding: 0px 20px;                 
                }
                .search-down{
                    top: 12px;
                }               
            }
        }
       table{
            .searchbtn{
             margin-bottom: 0px;
            line-height: 1.5;       
            .icon-search{
                font-size: 20px;
            }
        }    
       }    
    }
}
.is_sticky{
   position: fixed !important;
    top: 0;
    width: 100%;
    margin-top: 0px !important;
    background: #fff;
    z-index: 999;
    border-bottom: 1px solid #c5c2c2;
    box-shadow: 2px 0px 4px #d2d3d4;
    background: url(/images/common/main-banner.jpg) no-repeat top;
    padding-bottom: 6px;
    max-width: 100%;
    background-size: cover;
    h4{
        color:#0062ff;
        font-size: 1.2rem;
        padding-top: 5px;
    }
    .searchdiv{
        ul.search-ul{
            li{
                line-height: 1.5;
                margin-bottom: 5px;
                height: 40px;
                min-height: 40px;
                .form-control-search-input{
                    height: 40px;
                    padding: 0px 20px;                 
                }
                .search-down{
                    top: 12px;
                }               
            }
        }
        table{
            .searchbtn{
            margin-bottom: 0px;   
            line-height: 1.5;        
            .icon-search{
                font-size: 20px;
            }
        }    
       }   
    }
}
.search-ico{
    position: absolute;
    content: '';
    top: 10px;
    right: 10px;
    font-size: 20px;
    color:#84BE3F;
}
.form-control-search-input{
   height: 51px;
    width: 100%;
    border: 1px solid #9b9898;
    padding: 10px 20px;
    overflow: hidden;
    &:focus{
        border:none !important;
        outline: none !important;
    }
}
.slider-img-block{
    height: 100px;
    overflow: hidden;
    margin: 0px;
    background-color: #fff;
    img{
        width: 100%;
        height: 100%;       
        object-fit: cover;       
        // object-position: top;
    }
}
.searchresult-slide{
    height: 170px;
    overflow: hidden;
    margin-bottom: 10px;
}
.searchresult-slide-mb{
    height: auto;
    overflow: hidden;
    margin-bottom: 10px;
    margin: 0 auto;
}
.checkcountry-btn{
    position: relative;
    width: 280px;
    list-style: none;
    padding: 10px 10px;
    margin: 0 10px 10px 0;
    background: #fff;
    display: inline-block;
    color: #999;
    border: 1px solid #9B9898;
    border-radius: 30px;
    text-align: center;
    cursor: pointer;
}
.checkcountry-btn:hover{
    background: rgba(0,0,0,0.03);
}
.return-btn{
    box-shadow: none;
    border: 1px solid #0062FF;
    font-size: 16px;
    color: inherit;
    background: #E4EBF6;
    padding: 10px 14px;
    border-radius: 5px;
    color: #0062FF;
    margin-bottom: 20px;
}
/*main banner */
.mb-txt-box {
    margin: 12rem 0 12rem 0;
    text-align: right;
    width: 100%;
}
.mb-txt-box-af-search{
    margin: 70px 0 70px 0px;
    text-align: right;
    width: 100%;
}
.mb-txt{
    margin: 0px 0 0 50px;
}
//viwheight calculate with clientHeight
.mv_txt01{
    margin:2.5rem 0 2.5rem 0px !important;
}
.mv_txt02{
    margin:6rem 0 6rem 0px !important;
}
.mv_txt03{
    margin:10rem 0 10rem 0px !important;
}
.mv_height{
    height: 100%;
}
.base-btn-mt{
    margin-top:5px;
}
.m-tb-0{
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
.sp-812{
    display:none  !important;
}
.pc-812{
    display: block  !important;
}
.sp-812-flex{
    display: none  !important;
}
.pc-812-flex{
    display: flex  !important;
}
.form-control-search-input-mb{
    border: none;
    width: 100%;
}
//mobile
@media only screen and (max-width:1024px){
     .searchbtn{
        width: 135px;
    }    
}
@media only screen and (max-width: 812px) and (orientation : portrait){
    .container, .container-md, .container-sm {
        max-width: 810px;
    }     
}
@media screen and (max-width:812px){  
.sp-812{
    display:block !important;
}
.pc-812{
    display: none !important;
}
.sp-812-flex{
    display:flex  !important;
}
.pc-812-flex{
    display: none  !important;
}
.inner-div {
    h4.recruiter{color: #00a195;}
    h4.job{color: #6085a3;}
}

.searchdiv ul.search-ul li{
    width: 100%;
}
.searchdiv ul.search-ul li:first-child{
    border:1px solid #9b9898;
}
.searchdiv ul.search-ul li:last-child{
    margin-left: 0px;
}
.header {
    margin-bottom: 10px !important;
}
.search-carousel-wrapper{
    margin-top: 2px !important;
    margin-bottom: 0px !important;
}
// .search-down{
//     top:inherit;
// }
.icon-search{
    font-size: 18px;
    // line-height: 0.7;
}
.filter-search{
    border: none;
    background: inherit;
    position: absolute;
    right: 7px;
}
.clear-btn-mb{
    width: 27% !important;
    float: right !important;
    padding: 0px !important;
    min-height: 40px !important;   
    background: #cecece !important;
    color: #646262 !important;
}
.return-btn{
    font-size: 14px;
    color: inherit;
    background: #E4EBF6;
    padding: 5px;
    border-radius: 5px;
    color: #0062FF;    
}
.modal-wrapper-mb{
    display: block;
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    padding-bottom: 150px;
    background: #f7f7f7;
    overflow-y: auto;
    z-index: 999;
    .modal-block{
        display:block;
    }
    .modal-container{
        padding: 10px;
        width: 100%;
        background: none;
        border: none;
        box-shadow: none;
    }
    .cross-btn{
        position: absolute;
        top: 0px;
        right: 0px;
        font-size: 20px;
        background: transparent;
        border: navajowhite;
        color: #807979;
        display: flex;
        float: right;
        padding: 12px 25px;
    }
    .check-block{
        padding:10px;
        max-height: 400px;
        overflow-y: auto;
        .check-list {
            margin: 0;
            padding: 0;
            clear: both;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
            display: block;
        }        
        .check-item{
            position: relative;
            width: 100%;
            list-style: none;
            padding: 5px;
            margin: 0 0 0.33% 0;
            background: #fff;
            display: inline-block;
            color: #999;
            border: 1px solid #9b9898;
            font-size: 12px;
        }
    }
}
//sticky remove mobile
.is_sticky, .is_stickyrecru, .is_stickyjob{
    position: inherit !important;
}
.mobile-search-wrapper{
    position: fixed;
    z-index: 9999;
}
.search-li-mb{
    border: none !important;
    background: none !important;
    margin-bottom: 0px !important;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}   
//after login search wrapper
.position-block .inner-div {
    background-color:inherit;
    padding: 0px;
    margin: 15px 0px;
}
ul.inner-search-ul li{
    width: 100% !important;
    margin-bottom: 5px !important;  
}
.w-179 {       
    width: 700px;
}
}
@media (max-width: 768px){  
    .w-179{     
        width: 600px;
    }
    .mb-txt-box{
        margin: 5rem 0 6rem 0 !important;
    }
    .mb-txt-box-af-search{
         margin: 30px 0 30px 0 !important;
    }
    .p0-768{
        padding-left:0px;
        padding-right: 0px;
    }
    //search banner
    .sn-tabs-basic > li > .active{
        background-color: rgba(0, 0, 56, 0.5);
        padding: 15px 15px 12px;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
        color: #0062ff;
        font-weight: 400;
        &:focus{
            opacity:1;
        }
    }
    .nav-tabs{
        width: 100%;
        border-bottom:none;
        padding: 10px 15px;
        margin-bottom: 2px;
        li{
            margin: 0px;
            padding: 0px 10px;
        }
    }
    .nav-tabs li a{
        color: #fff;
        font-size: 18px;
        text-decoration: none;
    }

    .active{
        display: initial;
        padding-bottom: 5px;    
    }
    .search-tab-content{   
        display:flex;
        background:rgba(0, 0, 56, 0.5);
        padding: 20px 15px;
        border-radius: 0px !important;
    }
    //end search banner
}
@media (max-width: 414px){  
    .w-179{     
        width: 300px;
    }
    .mb-txt-box{
        margin: 1rem 0 2rem 0 !important;
    }
    .mb-txt-box-af-search{        
         margin: 30px 0 30px 0 !important;    
    }
}

//carousel mobile
@media only screen and (max-width: 812px) and (orientation : landscape){
.VueCarousel-wrapper .slide-txt {
    font-size: 17px;
    margin-top: 0px;
    margin-bottom: 0px;
    padding: 2px 0px;
    line-height: 25px;
}
.searchblock {
    background-color: rgba(255, 255, 255, 0.3) !important;
    padding: 0px 2px;
}
.container, .container-lg, .container-md, .container-sm{
    max-width: 100%;
}
.p0-768{
    padding-left: 0px;
    padding-right: 0px;
}
//search banner
.sn-tabs-basic > li > .active{
    background: #f6f6f6;
    padding: 15px;
    border-bottom: 3px solid #f6f6f6;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    color: #0062ff;
    font-weight: 400;
    &:focus{
        opacity:1;
    }
}
.nav-tabs{
    width: 100%;
    // border-bottom: 3px solid #f6f6f6; fixed for landscape
    padding: 10px 15px;
    li{
        margin: 0px;
        padding: 0px 10px;
    }
}
.nav-tabs li a{
    color: #fff;
    font-size: 18px;
    text-decoration: none;
}

.active{
    display: initial;
    padding-bottom: 5px;    
}
.search-tab-content{   
    display:flex;
    background:#f6f6f6;
    padding: 20px 15px;
    border-radius: 0px !important;
}
//end search banner
}
@media (max-width: 767px) and (orientation : portrait) {
.searchblock {
    background-color: rgba(255, 255, 255, 0.3) !important;
    padding: 0px 2px;
}
.VueCarousel-wrapper .VueCarousel-slide {
    padding: 20px 4px !important;
    color: #fff;
}
.icon {
    font-size: 18px;
    padding-right: 5px;
    vertical-align: text-top;
}
.VueCarousel-wrapper .slide-txt {
    font-size: 17px;
    margin-top: 0px;
    margin-bottom: 0px;
    padding: 2px 0px;
    line-height: 25px;
}
//search banner
.sn-tabs-basic > li > .active{
        background: #f6f6f6;
        padding: 15px;
        border-bottom: 3px solid #f6f6f6;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
        color: #0062ff;
        font-weight: 400;
        &:focus{
            opacity:1;
        }
    }
    .nav-tabs{
        width: 100%;
        border-bottom: 3px solid #f6f6f6;
        padding: 10px 15px;
        margin-bottom: 0px;
        li{
            margin: 0px;
            padding: 0px 10px;
        }
    }
    .nav-tabs li a{
        color: #fff;
        font-size: 18px;
        text-decoration: none;
    }

    .active{
        display: initial;
        padding-bottom: 5px;    
    }
    .search-tab-content{   
        display:flex;
        background:#f6f6f6;
        padding: 20px 15px;
        border-radius: 0px !important;
    }
    //end search banner
}
@media (max-width:667px) and (orientation : landscape){
    .keyword-btn-list li{
        width: 18.8%;
        margin: 10px 0 0 6px;
    }
     .keyword-btn-list li:last-child{
        margin-right: 0px;
    }

}
@media (max-width:568px) and (orientation : landscape){
    .keyword-btn-list li{
        width: 18.8%;
        margin: 10px 0 0 5px;
        padding: 8px 0px;
    }
    .keyword-btn-list li:last-child{
        margin-right: 0px;
    }
}
@media (max-width:480px){
    .nav-tabs li a{
        font-size: 14px;
    }
    .keyword-btn-list{
        padding:10px;
    }
    .keyword-btn-list li{
        position: relative;
        display: inline-block;
        width: 32%;
        list-style: none;
        padding: 8px 2px;
        background: #0062ff;
        float: none;
        margin: 5px 0 0 5px; 
        &:first-child{
            margin-left:0px;
        }      
        &:last-child{
           margin-right:0px;
        }
    }
}
@media (max-width:414px){
    .searchbtn{
        width: 140px;
    }
}
@media (max-width:320px){
    .searchbtn{
        width: 120px;
    }
     .keyword-btn-list li{
        width: 130px;
    }
}
</style>
