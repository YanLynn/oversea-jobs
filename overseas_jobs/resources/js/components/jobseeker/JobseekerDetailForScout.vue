<template>
	<div>
		<form enctype="multipart/form-data">
			<div class="row tab-content mb-3" id="selfIntroEdit">
				<div class="col-12">
					 <div class="row movie-row">
                        <div class="col-7">
                            <div class="card-carousel row mt-3">
                                <!--image sliders-->
                                <transition name="list">
                                    <div class="col-8 pr-0">
                                       <div class="row col-12 face-img-block">
                                            <div class="img-wrapper">
												<img v-if="currentImage" :src="currentImage" alt="currentimage" class="img-fluid"  />
                                            </div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="col-5 pr-0">
												<p>{{ selfIntroDetails.occupation_name }}</p>
												<p>{{ selfIntroDetails.language_level }}</p>
												<p>{{ selfIntroDetails.desired_location_1 }}</p>
                                            </div>
                                            <div class="col-7 pr-0 align-self-start">
													<span :class="['face-image', (activeImage == 4) ? 'active' : '']" @click="activateImage('face_image',4)">
														<img :src="selfIntro.face_image_url" alt="Jobseeker Face Image" class="img-fluid cursor-pointer img-contain"/>
													</span>
                                            </div>
                                            </div>
                                    </div>
                                    
                                </transition>
                                <div class="thumbnails col-4">
									 <span v-for="(image, index) in selfIntro.related_images" :key="image.id" :class="['thumbnail-image', (activeImage == index) ? 'active' : '']"  @click="activateImage('related',index)">
                                        <img :src="image.file_url" class="cursor-pointer" />
                                    </span>
                                </div>

                                <!--end image slider-->
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row mt-3">
                                <div class="col-12">
                                    <iframe  class="movie-link" :src="selfIntroDetails.video" :key="'youtube'+selfIntroDetails.video"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="col-12">
							<h6 class="about-tit">自己PR等</h6>
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
						<!-- Name -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.jobseeker_name') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
                        <dd class="detail-data" v-show="showPrivateFields">{{ basicInfo.jobseeker_name }}</dd>
						<!-- Gender -->
						<dt class="detail-head">{{ $t('jobseekerprofile.gender') }}</dt>
                        <dd class="detail-data">{{ basicInfo.gender }}</dd>
						<!-- DOB -->
						<dt class="detail-head" v-show="showPrivateFields">
							{{ $t('jobseekerprofile.date') }}
							<span class="private">{{ $t('jobseekerprofile.private') }}</span>
						</dt>
                        <dd class="detail-data" v-show="showPrivateFields">
							{{ basicInfo.dob[0] }}年 {{ basicInfo.dob[1] }}月 {{ basicInfo.dob[2] }}日
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
                        <dd class="detail-data">{{ basicInfo.final_education }}</dd>
						<!-- Current Situation -->
						<dt class="detail-head">{{ $t('jobseekerprofile.status') }}</dt>
                        <dd class="detail-data">{{ basicInfo.current_situation }}</dd>
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
						<dt class="detail-head">学歴</dt>
                        <dd class="detail-data">	
							<div v-for="edu in educations" :key="edu.id">
								<span v-if="edu.school_name">{{ edu.school_name }}</span>
								<span v-if="edu.school_name && edu.subject"> ({{ edu.subject }})</span>
							</div>
						</dd>
						<dt class="detail-head">経験社数</dt>
                        <dd class="detail-data">
							<span v-if="carrers.num_of_experienced_companies"> 
								{{ carrers.num_of_experienced_companies }}
							</span>
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
					</dl>
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
                        </dd>
						<!-- Abroad Education -->
						<dt class="detail-head" >留学経験</dt>
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
						</dd>
						<!-- Aborad Working Experience -->
						<dt class="detail-head">海外での勤務経験</dt>
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
						</dd>
						<!-- Working Visa -->
						<dt class="detail-head">就労ビザ</dt>
						<dd class="detail-data">
							<p v-if="experience_qualification.work_visa.status == 1">
								<span v-for="country in country_list" :key="'country'+country.id">
									<span v-if="country.id == experience_qualification.work_visa.country">{{ country.country_name }}</span>
								</span>
							</p>
							<p v-else>無し</p>
						</dd>
						<!-- Foreign Language Level -->
						<dt class="detail-head">語学レベル</dt>
						<dd class="detail-data" v-if="experience_qualification.foreign_language_level_experiences.length > 0">
							<p v-for="foreign_language_level in experience_qualification.foreign_language_level_experiences" :key="foreign_language_level.id">
								<span v-for="language in languages" :key="'language'+language.id">
									<span v-if="language.id == foreign_language_level.foreign_language">{{ language.language_name }}</span>
								</span>・
								<span>{{ foreign_language_level.language_level }}</span>
							</p>
						</dd>
						<dd class="detail-data" v-else>
							<p>未入力</p>
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
                        <dd class="detail-data">{{ desired_condition.job_change_reason }}</dd>
						<dt class="detail-head">転職活動状況</dt>
                        <dd class="detail-data">{{ desired_condition.job_search_activity }}</dd>
						<dt class="detail-head">転職で最も重視すること</dt>
                        <dd class="detail-data">{{ desired_condition.main_fact_when_change }}</dd>
						<dt class="detail-head">転職希望時期</dt>
                        <dd class="detail-data">{{ desired_condition.desired_change_period }}</dd>
						<dt class="detail-head">希望勤務地</dt>
                        <dd class="detail-data">
							{{ desired_condition.desired_location_1 }}&nbsp;
							{{ desired_condition.desired_location_2 }}&nbsp;
							{{ desired_condition.desired_location_3 }}
						</dd>
						<!-- Desire Industry -->
						<dt class="detail-head">希望業種</dt>
                        <dd class="detail-data">
							<span v-if="desired_condition.js_industry_name != null">{{ desired_condition.js_industry_name }}</span>
                            <span v-if="desired_condition.industry_name != ''">
                                <span v-for="industry in desired_condition.industry_name" v-bind:key="industry.id">{{ industry.industry_name }}</span>
                            </span>
                            <span v-if="desired_condition.industry_name == '' && desired_condition.js_industry_name == null">こだわらない </span>
						</dd>
						<!-- Desire Occupation -->
						<dt class="detail-head">希望職種</dt>
                        <dd class="detail-data">
							<span v-if="desired_condition.js_occupation_name != null">{{ desired_condition.js_occupation_name }}</span>
							<span v-if="desired_condition.occupation_name != ''">
								<span v-for="occupation in desired_condition.occupation_name" v-bind:key="occupation.id">{{ occupation.occupation_name }}</span>
							</span>
							<span v-if="desired_condition.occupation_name == '' && desired_condition.js_occupation_name == null">こだわらない </span>
						</dd>
						<!-- Desire Income -->
						<dt class="detail-head">希望年収</dt>
						<dd class="detail-data" v-if="!desired_condition.desired_max_annual_income">
							{{ desired_condition.desired_min_annual_income }}万 円以上
						</dd>
						<dd class="detail-data" v-else>
							{{ desired_condition.desired_min_annual_income }}万 ~ {{ desired_condition.desired_max_annual_income }}万 {{ desired_condition.desired_currency }}
						</dd>
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
	name: 'JobseekerDetailForScout',
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
				this.$configs.job_apply.unclaimed,
				this.$configs.job_apply.billed,
				this.$configs.job_apply.payment_confirmed,
			],
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
					private_status: "",
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
		}
	},
	computed: {
		showPrivateFields() {
			return this.allowedStatus.includes(this.status);
		},
	},
	methods: {
		activateImage(type, index) {
			if (type == "related") {
				this.activeImage = index;
				this.currentImage = this.selfIntro.related_images[index].file_url;
			} else {
				this.activeImage = index;
				this.currentImage = this.selfIntro.face_image_url;
			}
		}
	},
	created() {
		this.$api.get(`/recruiter/scoutable/${this.$props.jobseekerId}/profile`)
		.then(r => {
			this.country_list = r.data.data.countries;
			this.languages = r.data.data.languages;
			this.occupation_list = r.data.data.occupation_list;
			this.industry_list = r.data.data.industries;
			this.positions = r.data.data.positions;
			this.basicInfo = r.data.data.profile;
			this.file_names = r.data.data.hashedFile;
			this.selfIntro = r.data.data.selfIntro;
			this.currentImage = this.selfIntro.face_image_url;
			this.selfIntroDetails = r.data.data.selfIntroDetails;
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
					element.from_month = element.from_month ? element.from_month + 1920 + " 月" : '月';
					element.to_year = element.to_year ? element.to_year + 1920 + " 年" : '月';
					element.to_month = element.to_month ? element.to_month + 1920 + " 月" : '月';
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
					element.from_month = element.from_month ? element.from_month + 1920 + " 月" : '月';
					element.to_year = element.to_year ? element.to_year + 1920 + " 年" : '月';
					element.to_month = element.to_month ? element.to_month + 1920 + " 月" : '月';
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
					private_status: "",
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
}
.about-box {
	min-height: 300px;
	padding: 10px 20px;
	line-height: 2;
	border: 1px solid #e1e1ee;
	margin: 15px 0 30px 0;
    font-size: 14px;
}
.intro-tit {
	margin-bottom: 20px;
	border-bottom: none;
}
.movie-row {
	padding-bottom: 100px;
}
.movie-col {
	height: 200px;
}
.movie-link {
	width: 100%;
	height: 100%;
}
.detail-list {
	margin: 0 0 50px 0;
	padding: 0 20px;
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
	width: 40%;
	min-width: 5px;
	margin-right: 6%;	
	font-weight: normal;
    align-items: flex-start;
}
.detail-data {
	float: left;
	width: 54%;
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
	width: 145px;
	height: 82px;
	display: flex;
	overflow: hidden;
	margin: 0px 0px 10px 0px;
	border: 1px solid #eee;
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
}
.face-image-wrapper{    
    position: relative;
    display: inline-block;
    .delete-btn {
        right: -5px;
    }
}
.face-image{
    height: 115px;
    display: block;
    img{
        width: 100%;
        height: 100%;       
        object-fit: contain;       
        object-position: top;
    }
}
.img-wrapper {
    max-height: 245px;
    
}
.img-wrapper img {
    max-height: 245px;
}
.private {
	margin: 0 20px 0 auto;
	padding: 0 10px;
	background: #0071b4;
	color: #fff;
}


</style>