<template>
	<div>
		<form enctype="multipart/form-data">
			<div class="row tab-content mb-3" id="selfIntroEdit">
				<div class="col-12">
					 <div class="row movie-row">
                        <div class="col-12 col-md-7">
                            <div class="card-carousel row mt-3">
                                <!--image sliders-->
                                <transition name="list">
                                    <div class="col-9 pr-0">
                                        <div class="row col-12 face-img-block img-wrapper">                                            
											<img v-if="currentImage" :src="currentImage" alt="currentimage" class="img-fluid img-cursor" @click="imagePopup(currentImage)"/>                                           
                                        </div>
                                        <div class="row col-12">                 
                                            <div class="col-md-4 offset-md-8 pr-0 text-right face-image-wrapres">
                                                <span :class="['face-image', (activeImage == 4) ? 'active' : '']" @click="activateImage('face_image',4)" v-if="selfIntro.face_image_private_status == 1">
                                                    <img :src="selfIntro.face_image_url" alt="Jobseeker Face Image" class="img-fluid cursor-pointer img-contain"/>
                                                </span>
												<span v-else class="face-image">
													 <img :src="'/images/no_public.png'"  alt="job-img" @click="activateImage('private')"/>
												</span>
                                                    <p class="show-info">顔写真</p>  
                                            </div>                                           
                                        </div>
                                        <!-- popup images-->  
                                            <div class="img-modal-wrapper" v-if="showModal">
                                                <div class="img-modal-block">
                                                    <div class="img-modal-container">
                                                    <button class="cross-btn" @click="showModal = false">
                                                            <i class="fa fa-times pr-1"></i>閉じる</button>                                            
                                                        <img :src="this.imgUrl" class="img-popup"/>                                        
                                                    </div>
                                                </div>
                                            </div>
                                                                   
                                        <!--end popup images-->
                                    </div>                                    
                                </transition>
                                 <div class="thumbnails col-3 text-center">									
									 <span v-for="(image, index) in selfIntro.related_images" :key="image.id" :class="['thumbnail-image', (activeImage == index) ? 'active' : '']"  @click="activateImage('related',index)">
                                        <img :src="image.file_url" class="cursor-pointer" />
                                    </span>
								
						            <span v-if="selfIntro.related_images">
										<span v-for="(n,index) in 4 - (selfIntro.related_images.length)" :key="n.id" :class="['thumbnail-image', (activeImage == selfIntro.related_images.length + index) ? 'active' : '']" @click="activateImage('related',selfIntro.related_images.length+index) ">
                                           <img :src="defaultImageUrl" class="cursor-pointer" alt="related-images"/>                                           
                                        </span>  
						            </span>									
                                    <p class="show-info mt-0">関連画像</p>
                                </div>
                                <!--end image slider-->
                            </div>
                        </div>
                        <div class="col-12 col-md-5 mt-3 text-right">
                            <div v-if="selfIntroDetails.video" class="movie-wrapper">                                
                                <iframe class="movie-link" :src="selfIntroDetails.video" :key="'youtube'+selfIntroDetails.video" width="530" height="315"></iframe>                               
                            </div>
                             <div v-if="!selfIntroDetails.video" class="movie-wrapper">
                                <p class="no-video movie-link">
                                 <img src="/images/youtube.png" alt="動画なし" class="img-fluid">  
                                <!-- <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 動画は利用できません -->
                                </p>
                            </div>
                             <p class="show-info">関連動画</p>
                        </div>
                    </div>
                      <!--info-->
                      <dl class="detail-list clearfix intro-row p-0">                       
                        <dt class="detail-head">希望職種</dt>
                        <dd class="detail-data"> 
                            <span v-if="desired_condition.occupation_name">
                                <span v-for="occupation in desired_condition.occupation_name" v-bind:key="occupation.id">{{occupation.occupation_name}} <br></span>
                            </span>
                            <span  v-if="desired_condition.occupation_name.length == 0 && desired_condition.desired_occupation_status == 0">未入力</span>
                            <span v-if="desired_condition.desired_occupation_status == 1">こだわらない </span>
                        </dd>

                        <dt class="detail-head">希望勤務地</dt>
                        <dd class="detail-data">
                         <table class="history-edit-tbl">
                            <tr>
                                <td>第一希望</td>
                                <td>
                                    <span v-if="selfIntroDetails.desired_location_1 != null">{{selfIntroDetails.desired_location_1}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            </tr>
                            <tr>
                                <td>第二希望</td>
                                <td>
                                    <span v-if="selfIntroDetails.desired_location_2 != null">{{selfIntroDetails.desired_location_2}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            </tr>
                            <tr>
                                <td>第三希望</td>
                                <td>
                                    <span v-if="selfIntroDetails.desired_location_3 != null">{{selfIntroDetails.desired_location_3}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            </tr>
                        </table>
                            <!-- <p class="mb-0">
                                <label class="desired-label">第一希望</label>
                                <span v-if="selfIntroDetails.desired_location_1 != null">{{selfIntroDetails.desired_location_1}}</span>
                                <span v-else>未入力</span>
                            </p>
                            <p class="mb-0">
                                <label class="desired-label">第二希望</label>
                                <span v-if="selfIntroDetails.desired_location_2 != null">{{selfIntroDetails.desired_location_2}}</span>
                                <span v-else>未入力</span>
                            </p>
                            <p class="mb-0">
                                <label class="desired-label">第三希望</label>
                                <span v-if="selfIntroDetails.desired_location_3 != null">{{selfIntroDetails.desired_location_3}}</span>
                                <span v-else>未入力</span>
                            </p> -->
                        </dd>

                        <dt class="detail-head">語学レベル</dt>
                        <dd class="detail-data"> 
                            <!-- <span v-if="selfIntroDetails.language_level">
                                <span v-for="foreign_language_level in selfIntroDetails.language_level" :key="foreign_language_level.id">
                                    {{foreign_language_level}} <br>                                 
                                </span>
                            </span>
                            <span v-else>未入力</span> -->
                            <div v-for="foreign_language_level in experience_qualification.foreign_language_level_experiences" :key="foreign_language_level.id">
                                <span v-if="foreign_language_level.foreign_language != '' && foreign_language_level.foreign_language != null">
                                    <span v-for="language in languages" :key="'language'+language.id">
                                        <span v-if="language.id == foreign_language_level.foreign_language">{{language.language_name}}</span>
                                    </span>
                                    <span v-if="foreign_language_level.language_level != null">・{{foreign_language_level.language_level}}</span>
                                </span>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
                      </dl>
                    <!-- info-->
					<div class="row">
						<div class="col-12">
							<h6 class="about-tit">自己PR、海外で勤務したい理由等</h6>
							<pre class="about-box">{{ selfIntro.self_pr }}</pre>
						</div>
					</div>
				</div>
			</div>
			<!-- Basic Info -->
			<div class="row tab-content mb-3" id="information">
				<div class="col-12">
					 <div class="tit-box">
                        <h3 class="profile-edit-tit">基本情報</h3>
                    </div>
					<dl class="detail-list clearfix">
						<!-- Number -->
						<dt class="detail-head" >
							{{ $t('jobseekerprofile.jobseeker_number') }}
						</dt>
						 <dd class="detail-data">
                            <span v-if="basicInfo.jobseeker_number != ''">
                                {{ basicInfo.jobseeker_number}}
                            </span>
                            <span v-else>未入力</span>
                        </dd>

						<!-- Name -->
						<dt class="detail-head" v-show="showPrivateLabel">
							{{ $t('jobseekerprofile.jobseeker_name') }}
						</dt>
                        <dd class="detail-data" v-show="showPrivateLabel">
                            <span v-if="basicInfo.jobseeker_name != ''">
                                {{ basicInfo.jobseeker_name }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>

						<!-- jobseeker_furigana_name -->
						<dt v-if="basicInfo.jobseeker_furigana_name_status == 0" class="detail-head" >
							{{ $t('jobseekerprofile.furigana') }}
						</dt>
						 <dd  v-if="basicInfo.jobseeker_furigana_name_status == 0"  class="detail-data">
                            <span v-if="basicInfo.jobseeker_furigana_name != ''">
                                {{ basicInfo.jobseeker_furigana_name}}
                            </span>
                            <span v-else>未入力</span>
                        </dd>

						<!-- gender -->
						<dt v-if="basicInfo.gender_status == 0" class="detail-head" >
							{{ $t('jobseekerprofile.gender') }}
						</dt>
						 <dd  v-if="basicInfo.gender_status == 0"  class="detail-data">
                            <span v-if="basicInfo.gender != '' && basicInfo.gender != null">
                                {{ basicInfo.gender}}
                            </span>
                            <span v-else>未入力</span>
                        </dd>

						<!-- dob -->
						<dt v-if="basicInfo.dob_status == 0" class="detail-head" >
							{{ $t('jobseekerprofile.date') }}
						</dt>
					
						 <dd  v-if="basicInfo.dob_status == 0"  class="detail-data">
                            <span v-if="basicInfo.dob != '' && basicInfo.dob != null">
                               {{ basicInfo.dob}}
                            </span>
                            <span v-else>未入力</span>
                        </dd>


						<dt class="detail-head" >
							{{ $t('jobseekerprofile.Language') }}
						</dt>
					
						 <dd  class="detail-data">
                            <span v-if="basicInfo.language_name != null">
                               {{ basicInfo.language_name}}
                            </span>
                            <span v-else>未入力</span>
                        </dd>


						<!-- address -->
						<dt v-if="basicInfo.current_address_status == 0" class="detail-head" >
							{{ $t('jobseekerprofile.location') }}
						</dt>
						 <dd  v-if="basicInfo.current_address_status == 0"  class="detail-data">
                            <span v-if="(basicInfo.country_name != '' && basicInfo.country_name != null) || (basicInfo.city_name != '' && basicInfo.city_name != null)">
                                {{ basicInfo.country_name}}<span v-if="basicInfo.city_name">,{{basicInfo.city_name}}</span>
                            </span>
                            <span v-else>未入力</span>
                        </dd>

						
						<!-- Gender -->
						<!-- <dt class="detail-head">{{ $t('jobseekerprofile.gender') }}</dt>
                        <dd class="detail-data">{{ basicInfo.gender }}</dd> -->
						<!-- DOB -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.date') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
                        <dd class="detail-data" v-show="showPrivateFields">
                            <span v-if="basicInfo.dob">
                                {{basicInfo.dob}}
                            </span>
							<span v-else>未入力</span>
						</dd>
						<!-- Email -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.email') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
						<dd class="detail-data" v-show="showPrivateFields">{{ basicInfo.email }}</dd>
						<!-- Phone -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.phone') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
						<dd class="detail-data" v-show="showPrivateFields">{{ basicInfo.phone }}</dd>
						<!-- Skype Account -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.skype') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
						<dd class="detail-data" v-show="showPrivateFields">{{ basicInfo.skype_account }}</dd>
						<!-- Current city -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.location') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
                        <dd class="detail-data" v-show="showPrivateFields">{{ basicInfo.country_name }}, {{ basicInfo.city_name }}</dd>
						<!-- Final Education -->                       

						<dt class="detail-head">{{ $t('jobseekerprofile.education') }}</dt>
                        <dd class="detail-data" v-if="basicInfo.final_education != '' && basicInfo.final_education != null">{{basicInfo.final_education}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <!-- <dd class="detail-data">{{ basicInfo.final_education }}</dd> -->
						<!-- Current Situation -->
						<dt class="detail-head">{{ $t('jobseekerprofile.status') }}</dt>
                        <dd class="detail-data" v-if="basicInfo.current_situation != '' && basicInfo.current_situation !=null">{{basicInfo.current_situation}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <!-- <dd class="detail-data">{{ basicInfo.current_situation }}</dd> -->
					</dl>
				</div>
			</div>
			<!-- End Basic Info -->
			<!-- Career -->
			<div class="row tab-content mb-3" id="experience">
				<div class="col-12">
					 <div class="tit-box">
                        <h3 class="profile-edit-tit">経歴</h3>
                    </div>
					<dl class="detail-list clearfix">
                        <dt class="detail-head">{{$t('jobseekerprofile.edbackground')}}</dt>
                        <dd class="detail-data">
                            <!-- <div v-if="educations.length > 0">
                                <div v-for="edu in educations" :key="edu.id">
                                    <span v-if="edu.school_name">{{edu.school_name}}</span>
                                    <span v-if="edu.subject">({{edu.subject}})</span>
                                    <span v-if="!edu.school_name && !edu.subject">未入力</span>
                                </div>
                            </div>
                            <div v-else> 未入力 </div> -->

                            <div v-if="educations.length > 0">
                                <div v-for="(edu,index) in educations" :key="edu.id">
                                    <div v-if="(educations.length ==1 && edu.id != null) || educations.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>学校名</td>
                                            <td>
                                                <span v-if="edu.school_name">{{edu.school_name}}</span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>学部学科</td>
                                            <td>
                                                <span v-if="edu.subject">{{edu.subject}}</span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>                                        
                                    </table>
                                    <hr v-if="index != (educations.length-1)">
									
                                    </div>
                                    <span v-else> 未入力 </span>                                    
                                </div>
                            </div>
                            <div v-else> 未入力 </div>
                        </dd>

                        <dt class="detail-head">{{$t('jobseekerprofile.experiencecompany')}}</dt>
                        <dd class="detail-data">
                            <span v-if="(carrers.num_of_experienced_companies != '' && carrers.num_of_experienced_companies != null) || (carrers.num_of_experienced_companies == 0)"> 
                                {{carrers.num_of_experienced_companies}}    
                            </span>
                            <span v-else>未入力</span>
                        </dd>

                        <dt class="detail-head"> 勤務先 </dt>
						<dd class="detail-data" v-if="experiences">
                            <div style="white-space:unset" v-for="(exp,index) in experiences" :key="exp.id">
                                <div style="white-space:unset" v-if="(experiences.length ==1 && exp.id != null) || experiences.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr v-if="exp.private_status == 0">
                                            <td>企業名</td>
                                            <td>
                                                <span v-if="exp.job_location">{{exp.job_location}} <span class="private" v-if="exp.private_status == 1 || exp.private_status == 2">{{$t('jobseekerprofile.private')}}</span></span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr v-if="exp.private_status == 0 || exp.private_status == 2">
                                            <td>在籍期間</td>
                                            <td>
                                                <span  v-if="exp.current == 0 || exp.current == null">
                                                    <span v-if="exp.from_year != '年' || exp.from_month != '月'">
                                                        <span v-if="exp.from_year != '年'">{{exp.from_year}}</span>
                                                        <span v-if="exp.from_month != '月'">{{exp.from_month}}</span>

                                                        <span v-if="exp.to_year != '年'  && exp.to_month != '月'"> ~ {{exp.to_year}}{{exp.to_month}}</span>
                                                        <span v-if="exp.to_year != '年'  && exp.to_month == '月'"> ~ {{exp.to_year}}</span>
                                                        <span v-if="exp.to_year == '年'  && exp.to_month != '月'"> ~ {{exp.to_month}}</span>
                                                        <span v-if="exp.to_year == '年'  && exp.to_month == '月'"> ~ 在籍中</span>
                                                    </span>
                                                    <span v-else> - </span>
                                                </span>
                                                <span v-else>
                                                    <span v-if="exp.from_year != '年' && exp.from_month != '月'">{{exp.from_year}}{{exp.from_month}} ~ 在籍中</span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="exp.private_status == 0 || exp.private_status == 2">
                                            <td>ポジション</td>
                                            <td>
                                                <span v-if="exp.position_name"> {{exp.position_name}} <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                        <tr v-if="exp.private_status == 0 || exp.private_status == 2">
                                            <td>雇用形態</td>
                                            <td>
                                                <span v-if="exp.employment_type_name"> {{exp.employment_type_name}} <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                        <tr v-if="exp.private_status == 0 || exp.private_status == 2">
                                            <td>主な業務</td>
                                            <td>
                                                <span v-if="exp.main_duty"> {{exp.main_duty}} <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="(index != (experiences.length-1)) && (exp.private_status == 0 || exp.private_status == 2)">
                                </div>
                                <span v-else>未入力</span>
                            </div> 
                        </dd>

                        <!-- <dd class="detail-data" v-if="experiences">
                            <div style="white-space:nowrap;" v-for="(exp,index) in experiences" :key="exp.id">
								<span v-if="exp.private_status == 0">
									<span v-if="exp.job_location">{{exp.job_location}} </span>
									<span v-else>未入力</span>
                                    <br>
								</span>
                                <span v-if="exp.private_status == 0 || exp.private_status == 2">
									<span  v-if="exp.current == 0">	
										<span v-if="exp.from_year != '年' && exp.from_month != '月' && exp.to_year != '年'  && exp.to_month != '月' "> 
											{{exp.from_year}}{{exp.from_month}} ~ {{exp.to_year}}{{exp.to_month}}
										</span>
										<span v-if="exp.from_year != '年' && exp.from_month != '月' && exp.to_year == '年' && exp.to_month == '月'">
											{{exp.from_year}}{{exp.from_month}} ~ 在籍中
										</span>
                                    </span>
									<span style="white-space:nowrap;" v-else>
										<span v-if="exp.from_year != '年' && exp.from_month != '月'">
											{{exp.from_year}}{{exp.from_month}} ~ 在籍中
										</span>
									</span>	
									<span style="white-space:nowrap;" v-if="exp.from_year == '年' && exp.from_month == '月' && exp.to_year == '年'  && exp.to_month == '月' ">
										未入力
									</span>
                                    <br>
                                
									<span v-if="exp.position_name || exp.employment_type_name"> {{exp.position_name}} {{exp.employment_type_name}} </span>
									<span v-if="!exp.position_name && !exp.employment_type_name">未入力</span>
                                    <br>

									<span v-if="exp.main_duty"> {{exp.main_duty}} </span>
									<span v-else> 未入力 </span>
                                </span>
                               
                                <hr v-if="index != experiences.length-1 && (exp.private_status == 0 || exp.private_status == 2)">
                            </div> 
                        </dd> -->

                        <dt class="detail-head">{{$t('jobseekerprofile.annualincome')}}</dt>
                        <dd class="detail-data" v-if="carrers.last_currency != null || carrers.last_annual_income != null">
                            <span v-if="carrers.last_annual_income != null && carrers.last_annual_income != null">{{ carrers.last_annual_income}}</span> <span v-if="carrers.last_currency != null && carrers.last_currency != null">{{carrers.last_currency}}</span>
                        </dd>
                        <dd class="detail-data" v-else>未入力</dd>
                    </dl>
					<!-- <dl class="detail-list clearfix">
						<dt class="detail-head">学歴</dt>
                        <dd class="detail-data">
                            <div v-if="educations.length > 0">
                                <div v-for="edu in educations" :key="edu.id">
                                    <span v-if="edu.school_name">{{edu.school_name}}</span>
                                    <span v-if="edu.subject">({{edu.subject}})</span>
                                    <span v-if="!edu.school_name && !edu.subject">未入力</span>
                                </div>
                            </div>
                            <div v-else> 未入力 </div>
                        </dd>
						<dt class="detail-head">経験社数</dt>
                        <dd class="detail-data">
                            <span v-if="carrers.num_of_experienced_companies != '' && carrers.num_of_experienced_companies != null"> 
                                {{carrers.num_of_experienced_companies}}    
                            </span>
                            <span v-else>未入力</span>
                        </dd>
						<dt class="detail-head">勤務先</dt>
                        <dd class="detail-data">
							<div v-for="exp in experiences" :key="exp.id">
								<span>{{ exp.job_location }}</span>
								<span class="private" v-if="exp.private_status == 1">非公開</span>
							</div>
						</dd>
						<dt class="detail-head">最終年収</dt>
                        <dd class="detail-data">{{ carrers.last_annual_income }} {{ carrers.last_currency }}</dd>
					</dl> -->
				</div>
			</div>
			<!-- End Career -->
			<!-- Experience Qualifications -->
			<div class="row tab-content mb-3" id="qualification">
				<div class="col-12">
					 <div class="tit-box">
                        <h3 class="profile-edit-tit">経験・資格</h3>
                    </div>
					<dl class="detail-list clearfix">
						<!-- Job Experience -->
						<dt class="detail-head">経験業種・職種</dt>
                        <dd class="detail-data">
                            <div v-for="(exp_job,index) in experience_qualification.experience_jobs" :key="exp_job.id">
                                <!-- <div v-if="exp_job.experience_year != ''"> -->
                                <div v-if="(experience_qualification.experience_jobs.length ==1 && exp_job.industry_history_id != null) || experience_qualification.experience_jobs.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>経験年数</td>
                                            <td>
                                                <span v-if="exp_job.experience_year != 0 && exp_job.experience_year != null && exp_job.experience_year != ''">{{exp_job.experience_year}}年</span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>経験業種</td>
                                            <td>
                                                <span v-if="exp_job.experience_occupation != '' && exp_job.experience_occupation != null">
                                                    <span v-for="occupation in occupation_list" :key="'occupation'+occupation.id">
                                                        <span v-if="occupation.id == exp_job.experience_occupation">{{occupation.occupation_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>経験職種</td>
                                            <td>
                                                <span v-if="exp_job.experience_industry != '' && exp_job.experience_industry != null">
                                                    <span v-for="industry in industry_list" :key="'industry'+industry.id">
                                                        <div v-if="industry.id == exp_job.experience_industry">{{industry.industry_name}}</div>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ポジション</td>
                                            <td>
                                                <span v-if="exp_job.experience_position != '' && exp_job.experience_position != null">
                                                    <span v-for="position in positions" :key="'position'+position.id">
                                                        <span v-if="position.id == exp_job.experience_position">{{position.position_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.experience_jobs.length-1)">
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>                       
                        
						<!-- <dt class="detail-head">経験業種・職種</dt>
						<dd class="detail-data" v-if="experience_qualification.experience_jobs.length > 0">
							<p v-for="exp_job in experience_qualification.experience_jobs" :key="exp_job.id">
								<span>{{ exp_job.experience_year }}年</span>
								<span v-for="occupation in occupation_list" :key="'occupation'+occupation.id">
									<span v-if="occupation.id == exp_job.experience_occupation">{{ occupation.occupation_name }}</span>
								</span>・
								<span v-for="position in positions" :key="'position'+position.id">
									<span v-if="position.id == exp_job.experience_position">{{ position.position_name }}</span>
								</span>
								<span v-for="industry in industry_list" :key="'industry'+industry.id">
									<div v-if="industry.id == exp_job.experience_industry">{{ industry.industry_name }}</div>
								</span>
							</p>
						</dd>
                        <dd class="detail-data" v-else>
                            <p>未入力</p>
                        </dd> -->
						<!-- Abroad Education -->
						<dt class="detail-head" >留学経験</dt>
                        <dd class="detail-data">
                            <div v-for="(study_abroad,index) in experience_qualification.study_abroad_experiences" :key="study_abroad.id">
                                <!-- <div v-if="study_abroad.study_abroad_country != ''"> -->
                                <div v-if="(experience_qualification.study_abroad_experiences.length == 1 && study_abroad.study_abroad_id != null) || experience_qualification.study_abroad_experiences.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>国地域名</td>
                                            <td>
                                                <!-- {{study_abroad.study_abroad_country}} -->
                                                <span v-if="study_abroad.study_abroad_country != '' && study_abroad.study_abroad_country != null">
                                                    <span v-for="country in country_list" :key="'country'+country.id">
                                                        <span v-if="country.id == study_abroad.study_abroad_country">{{country.country_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>期間</td>
                                            <td>
                                                <span v-if="study_abroad.study_abroad_period != '' && study_abroad.study_abroad_period != null">{{study_abroad.study_abroad_period}}</span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>留学の目的</td>
                                            <td>
                                                <span>{{study_abroad.study_abroad_purpose}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.study_abroad_experiences.length-1)">
                                    
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
						<!-- <dt class="detail-head" >留学経験</dt>
						<dd class="detail-data" v-if="experience_qualification.study_abroad_experiences.length > 0">
							<p v-for="study_abroad in experience_qualification.study_abroad_experiences" :key="study_abroad.id">
								<span v-for="country in country_list" :key="'country'+country.id">
									<span v-if="country.id == study_abroad.study_abroad_country">{{ country.country_name }}</span>
								</span>・
								<span>{{ study_abroad.study_abroad_period }}</span>・
								<span>{{ study_abroad.study_abroad_purpose }}</span>
							</p>
						</dd>
						<dd class="detail-data" v-else>
							<p>未入力</p>
						</dd> -->
						<!-- Aborad Working Experience -->
						<dt class="detail-head">海外での勤務経験</dt>
                        <dd class="detail-data">
                            <div v-for="(working_abroad,index) in experience_qualification.working_abroad_experiences" :key="working_abroad.id">
                                <!-- <div v-if="working_abroad.working_abroad_country != ''"> -->
                                <div v-if="(experience_qualification.working_abroad_experiences.length ==1 && working_abroad.working_abroad_id != null) || experience_qualification.working_abroad_experiences.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>国・地域名</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_country != '' && working_abroad.working_abroad_country != null">
                                                    <span v-for="country in country_list" :key="'country'+country.id">
                                                        <span v-if="country.id == working_abroad.working_abroad_country">{{country.country_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>期間</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_period != '' && working_abroad.working_abroad_period != null"> {{working_abroad.working_abroad_period}} </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>ポジション</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_position != '' && working_abroad.working_abroad_position !=0 && working_abroad.working_abroad_position != null">
                                                    <span v-for="position in positions" :key="'position'+position.id">
                                                        <span v-if="position.id == working_abroad.working_abroad_position">{{position.position_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.working_abroad_experiences.length-1)">
                                    <!-- <span v-for="country in country_list" :key="'country'+country.id">
                                        <span v-if="country.id == working_abroad.working_abroad_country">{{country.country_name}}</span>
                                    </span>・
                                    <span>{{working_abroad.working_abroad_period}}</span>・
                                    <span v-for="position in positions" :key="'position'+position.id">
                                        <span v-if="position.id == working_abroad.working_abroad_position">{{position.position_name}}</span>
                                    </span> -->
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
						<!-- <dt class="detail-head">海外での勤務経験</dt>
						<dd class="detail-data" v-if="experience_qualification.working_abroad_experiences.length > 0">
							<p v-for="working_abroad in experience_qualification.working_abroad_experiences" :key="working_abroad.id">
								<span v-for="country in country_list" :key="'country'+country.id">
									<span v-if="country.id == working_abroad.working_abroad_country">{{ country.country_name }}</span>
								</span>・
								<span>{{working_abroad.working_abroad_period}}</span>・
								<span v-for="position in positions" :key="'position'+position.id">
									<span v-if="position.id == working_abroad.working_abroad_position">{{ position.position_name }}</span>
								</span>
							</p>
						</dd>
						<dd class="detail-data" v-else>
							<p>未入力</p>
						</dd> -->
						<!-- Working Visa -->
						<dt class="detail-head">就労ビザ</dt>
						<dd class="detail-data">
							<p v-if="experience_qualification.work_visa.status == 1">
                                <span v-if="experience_qualification.work_visa.country == '' || experience_qualification.work_visa.country == null">
                                    有り
                                </span>
                                <span v-else>
                                    <span v-for="country in country_list" :key="'country'+country.id">
                                        <span v-if="country.id == experience_qualification.work_visa.country">{{country.country_name}}</span>
                                    </span>
                                </span>                                
                            </p>
                            <p v-else>無し</p>
						</dd>
						<!-- Foreign Language Level -->
						<dt class="detail-head">語学レベル</dt>
						<dd class="detail-data">
                            <div v-for="foreign_language_level in experience_qualification.foreign_language_level_experiences" :key="foreign_language_level.id">
                                <span v-if="foreign_language_level.foreign_language != '' && foreign_language_level.foreign_language != null">
                                    <span v-for="language in languages" :key="'language'+language.id">
                                        <span v-if="language.id == foreign_language_level.foreign_language">{{language.language_name}}</span>
                                    </span>
                                    <span v-if="foreign_language_level.language_level != null">・{{foreign_language_level.language_level}}</span>
                                </span>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
						<!-- Toeic Score -->
						<dt class="detail-head">TOEICスコア</dt>
						<dd class="detail-data">
							{{ experience_qualification.other_qualifications.TOEIC_score ? experience_qualification.other_qualifications.TOEIC_score : '未入力' }}
						</dd>
						<!-- Other Language -->
						<dt class="detail-head">その他語学関連資料</dt>
						<dd class="detail-data">
							{{ experience_qualification.other_qualifications.language_qualifications ? experience_qualification.other_qualifications.language_qualifications : '未入力' }}
						</dd>
						<!-- Other Qualifications -->
						<dt class="detail-head">その他資格</dt>
						<dd class="detail-data">
							{{experience_qualification.other_qualifications.qualifications ? experience_qualification.other_qualifications.qualifications : '未入力' }}
						</dd>
					</dl>
				</div>
			</div>
			<!-- End Experience Qualifications -->
			<!-- Desire Conditions -->
			<div class="row tab-content mb-3" id="condition">
				<div class="col-12">
					 <div class="tit-box">
                        <h3 class="profile-edit-tit">希望条件</h3>
                    </div>
					<dl class="detail-list clearfix">
						<dt class="detail-head">転職意欲</dt>
                        <dd class="detail-data" v-if="desired_condition.job_change_reason != null && desired_condition.job_change_reason != ''">{{desired_condition.job_change_reason}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>

						<dt class="detail-head">転職活動状況</dt>
                        <dd class="detail-data" v-if="desired_condition.job_search_activity != '' && desired_condition.job_search_activity != null">
                            {{ desired_condition.job_search_activity }}</dd>
                        <dd class="detail-data" v-else>未入力</dd>

						<dt class="detail-head">転職で最も重視すること</dt>
                        <dd class="detail-data" v-if="desired_condition.main_fact_when_change != null && desired_condition.main_fact_when_change != ''">
                            {{ desired_condition.main_fact_when_change }}</dd>
                        <dd class="detail-data" v-else>未入力</dd>

						<dt class="detail-head">転職希望時期</dt>
                        <dd class="detail-data" v-if="desired_condition.desired_change_period != null && desired_condition.desired_change_period != ''">
                            {{ desired_condition.desired_change_period }}</dd>
                        <dd class="detail-data" v-else>未入力</dd>

						<dt class="detail-head">希望勤務地</dt>
                        <dd class="detail-data">
                        <table class="history-edit-tbl">
                            <tr>
                                <td>第一希望</td>
                                <td>
                                    <span v-if="desired_condition.desired_locations_1 != null">{{desired_condition.desired_locations_1}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            <tr>
                            <tr>
                                <td>第一希望</td>
                                <td>
                                    <span v-if="desired_condition.desired_locations_2 != null">{{desired_condition.desired_locations_2}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            <tr>
                            <tr>
                                <td>第三希望</td>
                                <td>
                                    <span v-if="desired_condition.desired_locations_3 != null">{{desired_condition.desired_locations_3}}</span>
                                    <span v-else>未入力</span>
                                </td>
                            </tr>
                        </table>
                            <!-- <p class="mb-0">
                                <label class="desired-label">第一希望</label>
                                <span v-if="desired_condition.desired_locations_1 != null">{{desired_condition.desired_locations_1}}</span>
                                <span v-else>未入力</span>
                            </p>
                            <p class="mb-0">
                                <label class="desired-label">第二希望</label>
                                <span v-if="desired_condition.desired_locations_2 != null">{{desired_condition.desired_locations_2}}</span>
                                <span v-else>未入力</span>
                            </p>
                            <p class="mb-0">
                                <label class="desired-label">第三希望</label>
                                <span v-if="desired_condition.desired_locations_3 != null">{{desired_condition.desired_locations_3}}</span>
                                <span v-else>未入力</span>
                            </p> -->
                        </dd>

						<!-- Desire Industry -->
						<dt class="detail-head">希望業種</dt>
                        <dd class="detail-data">
                            <span v-if="desired_condition.industry_name">
                                <span v-if="desired_condition.js_industry_name != null">{{ desired_condition.js_industry_name }}</span>

                                <span v-if="desired_condition.industry_name != ''">
                                    <span v-for="industry in desired_condition.industry_name" v-bind:key="industry.id">{{ industry.industry_name }}<br></span>
                                </span>

                                <span  v-if="desired_condition.industry_name.length == 0 && desired_condition.desired_industry_status == 0">未入力</span>

                                <span v-if="desired_condition.desired_industry_status == 1">こだわらない </span>
                            </span>
							
						</dd>
						<!-- Desire Occupation -->
						<dt class="detail-head">希望職種</dt>
                        <dd class="detail-data">
                            <span v-if="desired_condition.occupation_name">
                                <span v-for="occupation in desired_condition.occupation_name" v-bind:key="occupation.id">{{occupation.occupation_name}} <br></span>
                            </span>
                            <span  v-if="desired_condition.occupation_name.length == 0 && desired_condition.desired_occupation_status == 0">未入力</span>
                            <span v-if="desired_condition.desired_occupation_status == 1">こだわらない </span>
                        </dd>
						<!-- Desire Income -->
						<dt class="detail-head">希望年収</dt>
                        <dd class="detail-data" v-if="!desired_condition.desired_min_annual_income && !desired_condition.desired_max_annual_income"> 未入力 </dd>
                        <dd class="detail-data" v-if="desired_condition.desired_min_annual_income && !desired_condition.desired_max_annual_income">{{ desired_condition.desired_min_annual_income }} {{desired_condition.desired_currency}}以上 </dd>
                        <dd class="detail-data" v-if="!desired_condition.desired_min_annual_income && desired_condition.desired_max_annual_income">{{ desired_condition.desired_max_annual_income }} {{desired_condition.desired_currency}} </dd>
                        <dd class="detail-data" v-if="desired_condition.desired_min_annual_income && desired_condition.desired_max_annual_income">{{desired_condition.desired_min_annual_income }} ~ {{ desired_condition.desired_max_annual_income}} {{desired_condition.desired_currency}}</dd>
						<!-- <dd class="detail-data" v-if="!desired_condition.desired_max_annual_income">
							{{ desired_condition.desired_min_annual_income }}万 円以上
						</dd>
						<dd class="detail-data" v-else>
							{{ desired_condition.desired_min_annual_income }}万 ~ {{ desired_condition.desired_max_annual_income }}万 {{ desired_condition.desired_currency }}
						</dd> -->
					</dl>
				</div>
			</div>
			<!-- End Desire Conditions -->
		</form>
	</div>
</template>
<script>
/**
 * @component Jobseeker Profile Child Component for Scout
 * @path recruiter/scouted/{scout_id}
 * @path recruiter/scout/{jobseeker_id}
 * @author Myo Ko Ko @ 2020/08/20
 * @last_maintained Myo Ko Ko @ 2020/09/04
 */
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
	name: 'JobSeekerDetails',
	props: {
		jobseekerId: {
			type: [Number, String]
		},
		status: {
			type: String
		}
	},
	data() {
		return {
			allowedStatus: [
				this.$configs.scouts.unclaimed,
				this.$configs.scouts.billed,
				this.$configs.scouts.payment_confirmed,
				this.$configs.scouts.interested,
				this.$configs.scouts.declined,
				this.$configs.scouts.expired,
				// this.$configs.job_apply.unclaimed,
				// this.$configs.job_apply.billed,
				// this.$configs.job_apply.payment_confirmed,
            ],
            showPrivateLabel: false, 
			activeImage: 4,
			currentImage: '',
			country_list: [],
			occupation_list: [],
			industry_list: [],
			positions: [],
			languages: [],
			basicInfo: {
				city_name:0,
				dob: [],
				final_education :"",
				current_situation :"",
			},
			selfIntro: [],
			selfIntroDetails: {},
			carrers: [    
				{
					last_annual_income : "",
					num_of_experienced_companies : "",
					last_currency : "",
				}
			],
			educations: [
				{
					id: null,
					jobseeker_id: null,
					school_name: "",
					subject: "",
					degree: 0,
					from_year: "年",
					from_month: "月",
					to_year: "年",
					to_month: "月",
					education_status: 0,
				},
			],
			experiences: [
				{
					id: null,
					jobseeker_id: null,
					position_id: 0,
					employment_type_id: 0,
					job_location: "",
					main_duty: "",
					from_year: "年",
					from_month: "月",
					to_year: "年", 
					to_month: "月",
					current: "",
					private_status: 0,
				},
			],
			experience_qualification : {
				experience_jobs: [],
				study_abroad_experiences: [],
				working_abroad_experiences: [],
				foreign_language_level_experiences: [],
				work_visa : {
					status : '',
					country : '',
				},
				other_qualifications : {
					TOEIC_score : '',
					language_qualifications : '',
					qualifications : '',
				},
			},
			desired_condition: {},
            defaultImageUrl:'',
            showModal : false,
            imgUrl: '',
		}
	},
	computed: {
		showPrivateFields() {
			return false;
			// return this.allowedStatus.includes(this.status);
		},
		showPrivate() {
            // return false;
            if(this.$route.name != 'PublicRecruiterScoutEntry'){
                return (this.allowedStatus.includes(this.status) || this.$route.path.includes('/recruiter/jobseeker-detail/apply/'));
            }
			
		},
	},
	methods: {
		activateImage(type, index) {
			if (type == "related") {
				this.activeImage = index;
				 if(index > this.selfIntro.related_images.length || index == this.selfIntro.related_images.length){
                        return false;
                        // this.currentImage = this.defaultImageUrl;
                    }
                    else{
                        if(this.selfIntro.related_images[index].file_url.includes('images/default.png')){
                            return false;
                        }
                        else{
                            this.currentImage = this.selfIntro.related_images[index].file_url;
                        }
                        // this.currentImage = this.selfIntro.related_images[index].file_url;
                    }
			}
			else if(type =='private'){
				this.currentImage =	'/images/no_public.png'
			}
			else {
				this.activeImage = index;
				this.currentImage = this.selfIntro.face_image_url;
			}
        },
         imagePopup(url) {
			if(url.includes('images/default.png') || url.includes('images/no_public.png')){
				return false;
			}
			else{
				this.showModal =  true;
				this.imgUrl = url;
			}
          
        },
	},
	created() {
		this.$api.get("/default-image").then((r) => {
            this.defaultImageUrl = r.data.data;
        });
		
		this.$api.get(`/recruiter/scoutable/${this.$props.jobseekerId}/profile`)
		.then(r => {
            console.log("sta",r)
            if(this.$route.name == 'PublicRecruiterScoutEntry'){
                if(r.data.data.apply_status_check < 1) {
                    var status_check = r.data.data.scout_status_check.filter(item => {
                        return item.scout_status != '回答待ち';
                    });
                    if(status_check.length > 0) {
                        this.showPrivateLabel = true;
                    }
                }
                else{
                    this.showPrivateLabel = true;
                }
            }
            else{
                this.showPrivateLabel = (this.allowedStatus.includes(this.status) || this.$route.path.includes('/recruiter/jobseeker-detail/apply/'));
            }            
            
			this.country_list = r.data.data.countries;
			this.languages = r.data.data.languages;
			this.occupation_list = r.data.data.occupation_list;
			this.industry_list = r.data.data.industries;
			this.positions = r.data.data.positions;
			this.basicInfo = r.data.data.profile;
			
            if( this.basicInfo.only_country == 1){ 
                this.basicInfo.city_name = null;
            }
			this.file_names = r.data.data.hashedFile;
			this.selfIntro = r.data.data.selfIntro;
			
			if(this.selfIntro.face_image_private_status == 1){
				this.currentImage = this.selfIntro.face_image_url;
			}else{
				this.currentImage =	'/images/no_public.png';
			}
			
			this.selfIntroDetails = r.data.data.selfIntroDetails;
			var lang = r.data.data.language;
			 lang.filter(lan=>{
				if(lan.language_level){
					var split_arr = lan.language_level.split(',');
					this.selfIntroDetails.language_level = split_arr;
				}   
			});
			if(this.selfIntroDetails.occupation_name != null) {
                this.selfIntroDetails.occupation_name = this.selfIntroDetails.occupation_name.split(',');
            }
            else{
                this.selfIntroDetails.occupation_name = [];
            }
			this.activeImage = 4;
			this.educations = r.data.data.educations;
			this.experiences = r.data.data.experiences;
			this.carrers = r.data.data.carrers;
			this.desired_condition = r.data.data.desired_condition;
			// --Sanitize educations
			if (this.educations.length > 0) {
				this.educations.forEach(element => {
					element.degree = element.degree ?? 0;
					element.education_status = element.education_status ?? 0;
					element.from_year = element.from_year ? element.from_year + 1920 + " 年" : '年';
					element.from_month = element.from_month ? element.from_month + 12 + " 月" : '月';
					element.to_year = element.to_year ? element.to_year + 1920 + " 年" : '月';
					element.to_month = element.to_month ? element.to_month + 12 + " 月" : '月';
				});
			} else {
				this.educations.push({
					id: null,
					jobseeker_id: null,
					school_name: "",
					subject: "",
					degree: 0,
					from_year: "年",
					from_month: "月",
					to_year: "年",
					to_month: "月",
					education_status: 0,
				});
			}

			// --Sanitize experiences
			if (this.experiences.length > 0) {
				this.experiences.forEach(element => {
					element.position_id = element.position_id ?? 0;
					element.employment_type_id = element.employment_type_id ?? 0;
					element.from_year = element.from_year ? element.from_year + 1920 + " 年" : '年';
					element.from_month = element.from_month ? element.from_month + 12 + " 月" : '月';
					element.to_year = element.to_year ? element.to_year + 1920 + " 年" : '年';
					element.to_month = element.to_month ? element.to_month + 12 + " 月" : '月';
				});
			} else {
				this.experiences.push({
					id: null,
					jobseeker_id: null,
					position_id: 0,
					employment_type_id: 0,
					job_location: "",
					main_duty: "",
					from_year: "年",
					from_month: "月",
					to_year: "年",
					to_month: "月",
					current: null,
					private_status: 0,
				});
			}

			// --Sanitize exp & qualifications
			let industry_histories = r.data.data.industry_histories;
			let education_overseas = r.data.data.education_overseas;
			let working_overseas = r.data.data.working_overseas;
			let languages_levels = r.data.data.languages_levels;
			// --experience job type
			this.experience_qualification.experience_jobs = [];
			if (industry_histories.length > 0) {
				for (const industry_history of industry_histories) {
					this.experience_qualification.experience_jobs.push({
						industry_history_id : industry_history.id,
						experience_year : industry_history.experience_year,
						experience_industry : industry_history.industry_id,
						experience_occupation : industry_history.occupation_keyword_id,
						experience_position : industry_history.position_id,
					});
				}
			}else {
				this.experience_qualification.experience_jobs.push({
					experience_year: '',
					experience_industry: '',
					experience_occupation: '',
					experience_position: '',
				});
			}
			// --study abroad experience
			this.experience_qualification.study_abroad_experiences = [];
			if (education_overseas.length > 0) {
				for(const education_oversea of education_overseas){
					this.experience_qualification.study_abroad_experiences.push({
						study_abroad_id : education_oversea.id,
						study_abroad_country : education_oversea.country_id,
						study_abroad_period : education_oversea.period,
						study_abroad_purpose : education_oversea.purpose
					});
				}
			} else {
				this.experience_qualification.study_abroad_experiences.push({
					study_abroad_country : '',
					study_abroad_period : '',
					study_abroad_purpose : ''
				});
			}
			// --working at overseas/abroad experience
			this.experience_qualification.working_abroad_experiences = [];
			if (working_overseas.length > 0) {
				for (const working_oversea of working_overseas) {
					this.experience_qualification.working_abroad_experiences.push({
						working_abroad_id : working_oversea.id,
						working_abroad_country : working_oversea.country_id,
						working_abroad_position : working_oversea.position_id,
						working_abroad_period : working_oversea.period
					});
				}
			} else {
				this.experience_qualification.working_abroad_experiences.push({
					working_abroad_country: '',
					working_abroad_position: '',
					working_abroad_period: ''
				});
			}
			// --work visa
			this.experience_qualification.work_visa.status   = this.selfIntro.visa_status;
			this.experience_qualification.work_visa.country  = this.visa_status !== 0 ? this.selfIntro.visa_country : '';

			// --other qualifications
			this.experience_qualification.other_qualifications.TOEIC_score = this.selfIntro.toeic_score;
			this.experience_qualification.other_qualifications.language_qualifications  = this.selfIntro.other_language_certificate;
			this.experience_qualification.other_qualifications.qualifications = this.selfIntro.other_certificate;

			// --foreign language level
			this.experience_qualification.foreign_language_level_experiences = [];
			if (languages_levels.length > 0) {
				for(const languages_level of languages_levels){
					this.experience_qualification.foreign_language_level_experiences.push({
						foreign_language_id : languages_level.id,
						foreign_language : languages_level.language_id,
						language_level : languages_level.language_level,
					});
				}
			} else {
				this.experience_qualification.foreign_language_level_experiences.push({
					foreign_language: '',
					language_level: '',
				});
			}
		})
		.catch(err => {
			
		});
	}
};
</script>
<style lang="scss" scoped>

.form-control {
	border: 1px solid #919191;
	border-radius: 0;
}

.profile-edit-img {
	width: 100%;
}

.tab-content {
	padding: 50px 20px;
	margin: 0;
	background: #fff;
	box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
	border-radius: 5px !important;
}

.tit-box {
	position: relative;
	display: flex;
	margin-bottom: 40px;
	border-bottom: 2px solid #c3c4c3;
}
.profile-edit-tit {
	font-size: 1.2rem;
	line-height: 0.8;
}

.profile-edit-txt {
	position: absolute;
	top: -20px;
	right: 0;
	font-size: 16px;
	color: #619873;
	cursor: pointer;
}
.tit-box-edit .profile-edit-txt {
	top: -27px;
	padding: 8px 20px;
	border-radius: 50px;
	color: #919191;
	border: 1px solid;
}

.about-tit {
	color: #666766;
    font-size: 14px;
}
.about-box {
	min-height: 100px;
    max-height: 300px;
    overflow-y: auto;
    padding: 10px 20px;
    line-height: 2;
    font-size: 14px;
    border: 1px solid #ddd;
    margin: 15px 0 0px 0;
   
}
.intro-tit {
	margin-bottom: 20px;
	border-bottom: none;
}
.movie-row {
	padding-bottom: 0px;
}
.movie-link {
	width: 100%;
	height: 100%;
}
.detail-list {
    margin: 50px 0;
}

.detail-head,
.detail-data {
	padding: 15px 0 5px 0;
	border-top: 1px solid #ddd;
	line-height: 2;
}
.detail-head {
	display: flex;
	float: left;
	width: 34%;
	min-width: 5px;
	margin-right: 3%;	
	font-weight: normal;
    align-items: flex-start;
}
.detail-data {
	float: left;
	width: 63%;
	min-width: 5px;
	background-color: #fff;
}
.thumbnails {
    display: block;
    justify-content: space-evenly;
    flex-direction: row;
}
.thumbnail-image {   
    align-items: flex-start;
    cursor: pointer;    
    width: 100%;
    height: 82px;
    display: flex;
    overflow: hidden;
    margin: 0px 0px 10px 0px;
   /* border: 1px solid #eee;*/
    box-shadow: none;
}
.thumbnail-image > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 250ms;
}
.thumbnail-image:hover > img, 
.thumbnail-image.active > img {
    opacity: 0.6;
    box-shadow: 2px 2px 6px 1px rgba(0,0,0, 0.5);
}

// .img-wrapper {
// 	max-height: 245px;
// }

// .img-wrapper img {
// 	width: 100%;
// 	height: 100%;
// 	object-fit: contain;
// }
// .img-wrapper .thumbnail-image {
// 	width: 115px;
// }
.face-img-block{
    min-height: 240px;
    margin-bottom: 10px;
    img{
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: contain;      
    }
}
.face-image-wrapper{    
    position: relative;
    display: inline-block;
    .delete-btn {
        right: -5px;
    }
}
.face-image{
    height: 82px;
    display: block;
    img{
        width: 100%;
        height: 100%;       
        object-fit: contain;  
    }
}
.img-wrapper {
    max-height: 266px;
    
}
.img-wrapper img {
    height: 266px;
}
.private {
	margin: 0 20px 0 auto;
	padding: 0 10px;
	background: #0071b4;
	color: #fff;
}
.show-info{    
    border: 1px solid #d2d2d2;
    width: 100%;
    display: inline-block;
    line-height: 1.5;
    padding: 6px;
    margin-top: 10px;
    background: #fff;
    color: #333;
    text-align: center;
}
.detail-data div {
    white-space: unset!important;
}
.history-edit-tbl td {
    padding: 5px 0;
    vertical-align: top;
}
.history-edit-tbl td:first-child {
    display:block;
    width: 200px;
    color: #666;
}
//responsive
@media (max-width: 1024px) { 
    .img-modal-wrapper .img-modal-block {
        width: 80%;
    }
    .movie-row{
        padding-bottom: 0px;
    }
    .thumbnails{
        padding-left: 0px;
    }
}
@media (max-width: 768px) { 
    .detail-head {
        flex-wrap: wrap;
        float: none;
    }
    .view-permission {
        display: inline-block;
        float: none !important;
        margin: 0 20px 2px 0;
    }
    .history-edit-tbl td:first-child {
        width: 190px;
    }
}
@media (max-width: 480px) {     
    .p-0-res{
        padding-left: 0px;
        padding-right: 0px;
    }
    .kara-txt{
        margin-left: 5px;
    }
    .tab-content {
        padding: 40px 0;
    }
    .profile-edit-tit {
        font-size: 18px;
    }
    .profile-edit-txt {       
        font-size: 12px;
        padding:2px 8px !important;
    }
    .profile-edit-wrap{
        margin-bottom: 20px;
    }
    .icon-edit {
        margin-right: 2px;
        font-size: 30px;
    }
    .delete-btn{
        right:0px;
    }
    .upload-content{
        flex-wrap: wrap;
    }
    .tit-box {
        margin-bottom: 0;
    }
    .popup-databox{
        padding: 10px 15px 10px 15px;
    }
    .school-box, .experience-box{
        padding: 15px;
    }
    .face-img-block {
        height: 153px;
        min-height: 200px;
    }
    .face-image{
        height: 60px;
    }
    .faceimg-wrap {
        width: 40%;
        margin-left: auto;
    }
    .face-image-wrapres{
        width: 40%;
        margin-left: auto;
    }
    .img-wrapper-res{
        margin-bottom: 20px;
    }
    .thumbnail-image {
        height: 60px;
    }
    .explation-note {
        margin: 0;
        padding: 10px;
    }
    .about-box {
        padding: 10px;
    }
    .detail-head {
        width: 42%;
    }
    .detail-data {
        width: 52%;
    }
    .view-permission {
        width: 100%;
        padding: 0 3px 2px 3px;
        margin: 0 0 2px 0;
        font-size: 12px;
    }
    .explation-note .view-permission {
        width: auto;
        font-size: 14px;
    }
    .history-edit-tbl td {
        display: block;
        width: 100%;
        margin: 5px 0;
        padding: 5px;        
    }
    .history-edit-tbl td:first-child {
        width: 200px;
        background: #f1eeee;
    }
    .img-wrapper img{
        height: 206px;
    }
    .private{
        margin:0 0px 2px auto;
    }
    .show-info{
        font-size:12px;
    }
    .popup-databox{
        margin: 15px 0px;
    }
    .mb-2-res{
        margin-bottom: .5rem!important
    }     
}
@media (max-width: 380px) {
    .profile-edit-tit{
        font-size: 15px;
    }
     .history-edit-tbl td:first-child {
        width:100%;
    }
    
}
@media (max-width: 320px) {
    .profile-edit-tit{
        font-size: 13px;
    }
    .profile-edit-txt {
        top: -8px;
        font-size: 12px;
    }
    .icon-edit {
        margin-right: 2px;
        font-size: 20px;
    }
    .view-permission {
        font-size: 10px;
    }
    .private{
        margin:0px;
        padding: 0px 2px;
    }
}
</style>