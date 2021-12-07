<template>
	<div :class="$route.name == 'PublicRecruiterScoutEntry'?'full-width container' : 'user-main'" class="vld-parent" ref="mainContainer"> 
		<div v-show="loaded && jobseekerScoutable" ref="progressContainer">
			<div class="legends scout-box">
				<div class="d-sm-flex align-items-center pt-3 px-2 scout-padding">
					<h3 class="flex-grow-1">{{ $t('recruiter_scout_entry.detail_profile') }}</h3>
					<button class="btn recruiter-primary-color mx-sm-3 border-style btn-m" v-show="!showScoutForm" @click="showScoutForm = true" v-scroll-to="{ el:'#addscout' ,  offset: -150}">{{ $t('recruiter_scout_entry.scout') }}</button>
					<button class="btn appendbtn ml-3 border-style btn-m" v-if="favourite_addable" @click="addToFavourite">{{ $t('recruiter_scout_entry.add_favourite') }}</button>
					<button class="btn appendbtn-border ml-3 border-style btn-m" v-if="!favourite_addable" @click="removeFavourite">{{ $t('recruiter_scout_entry.remove_favourite') }}</button>
				</div>
				<div class="tab-list text-center">　　　　　　　　　　　
					<ul id="recr-scroll" class="list-group list-group-horizontal" v-on:scroll="handleScroll">
						 <li v-for="(item, index) in items" class="list-group-item" :key="index.id"> 
							  <a  v-scroll-to="{el:'#'+(item,index),offset:-150}"
							  		href="#"
									:id="item.text"                          
									:class="{isActive: activeIndex === index ? 'active':''}"                       
									class="list-link">{{ item.text }}</a>               
							
							</li>
						<!-- <a href="#" class="list-link" v-scroll-to="{ el:'#selfIntroEdit' ,  offset: -150}">自己紹介</a>
						<li class="list-group-item"><a href="#" class="list-link"  v-scroll-to="{ el:'#information' ,  offset: -150}">基本情報</a></li>
						<li class="list-group-item"><a href="#" class="list-link"  v-scroll-to="{ el:'#experience' ,  offset: -150}">経歴</a></li>
						<li class="list-group-item"><a href="#" class="list-link"  v-scroll-to="{ el:'#qualification' ,  offset: -150}">経験・資格</a></li>
						<li class="list-group-item"><a href="#" class="list-link" v-scroll-to="{ el:'#condition' ,  offset: -150}">希望条件</a></li> -->
					</ul>
				</div>
            </div>
			
			<!-- Scout Entry Form -->
			<div class="tab-content mb-3" v-show="showScoutForm" id="addscout">
				<div v-show="anyScoutableJob">
					<form @submit.prevent="addToScout">
						<div class="form-group">
							<label class="recruiter-primary-color-txt">求人を選択してください</label>
							
								<div class="aselect" :data-value="value" :data-list="available_jobs">
									<div class="selector" @click="toggle()">
										<div class="label">
												<span>{{ value.job_number }}&nbsp;&nbsp;&nbsp;{{ value.title }}</span>
										</div>
										<div class="arrow" :class="{ expanded : visible }"></div>
										<div :class="{ hidden : !visible, visible }">
											<ul >
												<li  :class="{ current : item === value }" :value="item.id" :key="item.id" v-for="item in available_jobs" @click="bindJobDetail(item)" >{{ item.job_number }}&nbsp;&nbsp;&nbsp;{{ item.title }}</li>
											</ul>
										</div>
									</div>
								</div>
                                <span class="bg" v-if="visible" @click="visible=!visible"></span>
								<div class="error" v-if="errors != ''">求人が選択されていません</div>
							<!-- <select :class="['form-control select custom-select col-sm-4',$v.scoutForm.job_id.$error?'input-error':'']" v-model="scoutForm.job_id" @change="bindJobDetail()" >
							
								<option v-for="job in available_jobs" :key="job.id" :value="job.id" class="option">
									{{ job.job_number }}&nbsp;&nbsp;&nbsp;{{ job.title }}
								</option>
							</select> -->
							
							<!-- <div class="input-group" v-if="$v.scoutForm.job_id.$dirty">
								<div class="error" v-if="!$v.scoutForm.job_id.required">求人が選択されていません</div> -->
								<!-- <div class="error" v-if="!$v.scoutForm.job_id.numeric">Job id is not numberic</div> -->
							<!-- </div> -->
						</div>
						<div v-show="!is_job_applied">
                            <div class="alert alert-warning" v-if="has_declined">
								この人材は選択された求人で以前に辞退/不採用となっています
							</div>
							<div class="form-group row m-0">
								<div class="col-md-3 form-left-block border py-2">
									<label class="">募集ポジション <br>(記入例)</label>
									<div class="pl-3">
										<ol class="color-for-example">
											<li>WEB ディレクター</li>
											<li>WEB デザイナー</li>
											<li class="list-unstyled">※ 契約更新は 1 年毎</li>
										</ol>
									</div>
								</div>
								<div class="col-md-9 form-right-block border py-2">
									<textarea class="form-control" :disabled="isDisabled" rows="3" v-model="scoutForm.occupation_description"></textarea>
								</div>
							</div>
							<div class="form-group row m-0">
								<div class="col-md-3 form-left-block border py-2">
									<label class="">雇用形態</label>
								</div>
								<div class="col-md-9 form-right-block border py-2">
										<div class="row col-12 p-0 mb-n2">
											<div class="col-6 col-md-4 mb-2" v-for="(result,indx) in employment_types" :key="result.id">
												<input type="checkbox" :disabled="isDisabled" :value="result.employment_type_name" :id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="scoutForm.employment_types"/>
												<label :for="result.employment_type_name" class="custom-control-label custom-checkbox-label">{{ $t('jobcreate.employment_type['+indx+']') }}</label>
											</div>
										</div>
								</div>
							</div>
							<div class="form-group row  m-0">
								<div class="col-md-3 form-left-block border py-2">
									<label class="">勤務地詳細 <br>(記入例)<br>インドネシアジャカルタ</label>
								</div>
								<div class="col-md-9 form-right-block border py-2">
									<textarea class="form-control" :disabled="isDisabled" rows="3" v-model="scoutForm.job_location"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group text-center  mb-0 mt-3" v-show="is_job_applied">
							<div class="alert alert-danger">
								問合せ/応募中の求人です
							</div>
						</div>
						<div class="form-group text-right mb-0 mt-3">							
							<button type="button" class="btn cancel-color border-style btn-m" @click="cancelScout">前にもどる</button>
                            <button class="btn recruiter-primary-color ml-3 border-style btn-m" v-show="!is_job_applied">スカウト確定</button>
						</div>
					</form>
				</div>
				<div v-show="!anyScoutableJob">
					<div class="alert alert-warning">
						スカウトを行うには、１件以上の求人登録が必要となります。まずは<router-link to="/recruiter/jobs/create" class="recruiter-primary-color-txt sub-item">こちら</router-link>から求人登録をお願いいたします。
					</div>					
				</div>
			</div>
			<!-- End Scout Entry Form -->
			
			
			<!-- Jobseeker Profile -->
			<JobSeekerDetails :jobseeker-id="jId" :status="$configs.scouts.waiting" :key="jId"></JobSeekerDetails>
			<!-- End Jobseeker Profile -->
		</div>
		<div v-show="loaded && !jobseekerScoutable">
			<div class="alert alert-danger">
				この求職者はスカウトされることを望んでいません。
			</div>
		</div>
	</div>
</template>
<script>
import { required, numeric } from 'vuelidate/lib/validators';
import JobSeekerDetails from '../jobseeker/JobSeekerDetails';

function elementInViewport(el) {
  var top = el.offsetTop;
  var height = el.offsetHeight;
  var bottom = top + height;

  while(el.offsetParent) {
    el = el.offsetParent;{
    top += el.offsetTop;
  }

  }return (
    !(top < window.pageYOffset && bottom < window.pageYOffset) &&
      !(top > (window.pageYOffset + (window.innerHeight)) && bottom > window.pageYOffset + (window.innerHeight))
  );
}
/**
 * @component Recruiter scout jobseeker
 * @author Myo Ko Ko @ 2020/08/17
 * @last_maintained Myo Ko Ko @ 2020/08/27
 */
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    }, 
	components: { JobSeekerDetails },
	props: ['isActive'],
	props: {		
		jobseekerId: {
			type: String, default: ''
		}
	},
	data() {
		return {

			errors:'',
			visible:false,
			value: '',
			loaded: false, /* control variable to defer rendering of template */
			showScoutForm: false,
			favourite_addable: true,
			/* control variable to determin if jobseeker has applied selected job */
            is_job_applied: false,
            /* control variable to determin if jobseeker has declined once */
			has_declined: false,
			jobseeker: {},
			scoutForm: {
				jobseeker_id: this.$props.jobseekerId,
				employment_types: [],
			},
			available_jobs: [],
			employment_types: [],
			items: {

                     selfIntroEdit:{text:'自己紹介',id:1},
                     information:{text:'基本情報',id:2},
                     experience:{text:'経歴',id:3},
                     qualification:{text:'経験・資格',id:4},
					 condition:{text:'希望条件',id:5},  
				 },
            activeIndex:null,
            isDisabled: true,
		}
	},
	methods: {
		 toggle() {
				this.visible = !this.visible;
			},
			select(option) {
			    this.value = option;
			},
		handleScroll (index) {

                 let id = ['selfIntroEdit','information','experience','qualification','condition']
                 const elementsInViewArray = id.map(String => {
                 const el = document.getElementById(String);
                 if(elementInViewport(el)) {
                    return String;
                    }
                 });
                
                 this.activeIndex = elementsInViewArray.find(String => String)
 
        },
		bindJobDetail(item) {
            this.isDisabled = false;
			console.log(item)
			this.scoutForm.job_id = item.id;
			this.errors = '';
			this.value = item;
			// const vm = this;
			let job = this.available_jobs.find((x) => x.id == item.id);
			if (job) {
				// if (!job.jobapply_id) {
					this.scoutForm.occupation_description = job.occupation_description;
					this.scoutForm.employment_types = job.employment_types.split(",").filter(i => i);
					this.scoutForm.job_location = job.job_location;
				// }
                // this.is_job_applied = job.jobapply_id > 0;
                this.is_job_applied = job.jobapply_id > 0 && job.job_apply_status != this.$configs.job_apply.declined;
				this.has_declined = job.scout_status == this.$configs.scouts.declined | job.job_apply_status == this.$configs.job_apply.declined;
			} else {
				this.is_job_applied = false;
				this.has_declined = false;
			}
		},
		cancelScout() {
			this.showScoutForm = false;
			// --reset form
			this.value = '';
			this.scoutForm = {
				jobseeker_id: this.$props.jobseekerId,
				employment_types: [],
			}
            this.is_job_applied = false;
            this.has_declined = false;
            this.isDisabled = true;
			// this.$v.scoutForm.$reset();
		},
		addToScout() {
			// this.$v.scoutForm.$touch();
			// if (this.$v.scoutForm.$invalid) {				
			// 	return;
			// }
			console.log("job_id",this.scoutForm);
			if(this.scoutForm.job_id == null || this.scoutForm.job_id == '')
			{
				this.errors = "errors";
			}
			else{
				this.errors = '';
			}
			if(this.errors == '')
			{
				this.$alertService.showConfirmDialog(null,this.$t('alertMessage.scout'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                if(dialogResult.value){
					const loading = this.$loading.show();
					this.$api.post('/recruiter/scout', this.scoutForm)
					.then(r => {
						this.$alertService.showSuccessDialog(null, "スカウトが完了しました。",this.$t('alertMessage.close'));
						this.$store.dispatch('recFavCount');
						this.$router.push({ name: 'RecruiterScoutedList'});
						// .then(() => {
							
							// --Reset form
							// this.scoutForm = {
							// 	jobseeker_id: this.$props.jobseekerId,
							// 	employment_types: [],
							// }
							// this.$v.scoutForm.$reset();
							// // --Rebind scoutable jobs
							// this.initScoutableJobs();
						// });
					})
					.catch(err => console.log(err))
					.finally(() => { loading.hide(); });
				};
			});

			}
			
						
		},
		addToFavourite() {
			
			const loading = this.$loading.show({ isFullPage: false, container: this.$refs.progressContainer });
			this.$api.put(`/recruiter/fav-list/add/${this.jobseekerId}`)
			.then(r => {
				let ids = r.data.data.favourite_jobseeker_ids;
				if (ids) {
					ids = ids.split(',');
					this.favourite_addable = !ids.includes(this.jobseekerId);
				}
				this.$store.dispatch('recFavCount')
			})
			.finally(() => loading.hide());
		},
		removeFavourite() {
			const loading = this.$loading.show({ isFullPage: false, container: this.$refs.progressContainer });
			this.$api.get(`/recruiter/fav-list/${this.jobseekerId}/delete`)
			.then(r => {
				this.favourite_addable = true;
				this.$store.dispatch('recFavCount')
			})
			.finally(() => loading.hide());
		},
		initScoutableJobs() {
			// --Fetch scoutable jobs from API
			this.$api.get('/recruiter/scoutable-jobs/' + this.jobseekerId)
			.then(r => this.available_jobs = r.data.data);
		}
	},
	computed: {
		anyScoutableJob() {			
			return this.available_jobs.length > 0;
		},
		jobseekerScoutable() {
			return this.jobseeker.scout_setting_status == this.$configs.scout_setting.scoutable;
		},
		jId() {
			return this.$props.jobseekerId;
		}
	},
	// validations: {
	// 	scoutForm: {
	// 		job_id: { required, numeric },
	// 	}
	// }, 
	created() {
	
		window.addEventListener('scroll', this.handleScroll);
		const loading = this.$loading.show({ isFullPage: false, container: this.$refs.mainContainer });
		Promise.all([
			// --Fetch jobseeker scout setting from API
			this.$api.get(`/recruiter/scoutable/${this.jobseekerId}`).then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response)),
			// --Fetch employment types from API
			this.$api.get('/recruiter/getalldata').then(r => Promise.resolve(r.data)).catch(error => Promise.reject(error.response)),
			// --Fetch scoutable jobs from API
			this.$api.get('/recruiter/scoutable-jobs/' + this.jobseekerId).then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response)),
			// --Fetch recruiter profile to allow favourite
			this.$api.get('/recruiter/profile').then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response))
		])
		.then(response => {
			// --Bind jobseeker data
			this.jobseeker = response[0];

			// --Bind employment types for scout form
			this.employment_types = response[1].employment;

			// --Bind scoutable jobs
			this.available_jobs = response[2];

			// --Check add favourite button availability
			let ids = response[3].favourite_jobseeker_ids;
			if (ids) {
				ids = ids.split(',');
				this.favourite_addable = !ids.includes(this.$props.jobseekerId);
			}
		})
		.catch(err => { 
			console.log(err)
		})
		.finally(() => { 
			loading.hide();
			this.loaded = true; 
		});
	},
	destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
}
</script>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.tab-content {
	padding: 30px;
	margin: 0;
	background: #fff;
	box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
	border-radius: 5px !important;
}
.legends  {	
	position: -webkit-sticky; /* Safari */
  	position: sticky;
	z-index: 1000;
	top: 0px;
	padding-top: 20px;
	background:rgb(234 234 234);
	backdrop-filter: blur(15px);
	-webkit-backdrop-filter: blur(15px);	
}
.isActive{
        color: #00a195 !important;
        font-weight: bold;       
        border-bottom: 1px solid #00a195;     
    }
.tab-list {
    margin: 20px 0;
    background: #fff;
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    border: 1px solid rgba(0,0,0,.125);
}
.tab-list .list-group-item {
    padding: 20px 40px;
    border: none;
}
.list-link {
    color: #00a195 ;
}
.alert {
	font-size: 1.1rem;
}   

.aselect .selector {
  border: 1px solid gainsboro;
  background: #F8F8F8;
  position: relative;
  z-index: 600;

}
.aselect .selector .arrow {
  position: absolute;
  right: 10px;
  top: 40%;
  width: 0;
  height: 0;
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  border-top: 10px solid #888;
  transform: rotateZ(0deg) translateY(0px);
  transition-duration: 0.3s;
  transition-timing-function: cubic-bezier(0.59, 1.39, 0.37, 1.01);
}
.aselect .selector .expanded {
  transform: rotateZ(180deg) translateY(2px);
}
.aselect .selector .label {
  display: block;
  padding: 12px;
  font-size: 16px;
  color: #888;
  cursor: pointer;
}
.aselect ul {
  width: 100%;
  list-style-type: none;
  padding: 0;
  margin: 0;
  font-size: 16px;
  border: 1px solid gainsboro;
  position: absolute;
  z-index: 1;
  background: #fff;
  height: 337px;
  overflow-x: auto;
}
.aselect li {
  padding: 4px;
  color: #666;
  cursor: pointer;
  position: initial;
}
.aselect li:hover {
  color: white;
  background: #5d7bf0;
}
.aselect .current {
  background: #eaeaea;
}
.aselect .hidden {
  visibility: hidden;
}
.aselect .visible {
  visibility: visible;
}

.bg {
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 500;
  transition: .3s;
  /* background-color: red; */
}
@media (max-width: 767px) { 
.btn-m {
    width: 124px;
    padding: 8px 5px;
}
.tab-content{
	padding: 15px;
}
/* profile edit tab*/ 
	.list-group-horizontal > .list-group-item:first-child{
        padding-left: 0px !important;
    }
    .list-group-horizontal>.list-group-item:last-child{
        border-bottom-left-radius: 0px;
        border: none !important;
    } 
    .list-group-item{
        padding: 15px 2px !important;
        width: 20%;
        text-align: center;
        background: #fff;
        border-right: 1px solid #999898 !important;
        font-size: 12px;
    }
}

@media (max-width: 320px) { 
   .list-group-item{
         font-size: 10px;
	}
	.btn-m {
		width: 120px;
	}
}
</style>