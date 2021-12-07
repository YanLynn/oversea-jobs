<template>
	<div class="modal-wrapper" id="DetailInfoPopup" v-if="showPopup">
		<div class="modal-block">
			<div class="modal-container">
				<div class="sp-model-wrap">
				<div class="head-wrap m-b-5">
					<h3 class="text-left header recruiter-primary-color-txt">
                    <span class="tit-w100">{{ job.job_number }}&nbsp;{{ job.title }}</span> 				
                        <!-- <button class="cross-btn" @click="close">
                           <i class="fa fa-times pr-1"></i>閉じる
                        </button> -->
                    </h3>
                    <label v-if="!showJob" style="color:#000;">より、以下の条件で求職者会員をスカウトしました。</label>
				</div>	
                <div class="modal-body-wrapper" v-bind:class="isModalH ? m_h : ''">			
                    <div class="tab-col col-12 p-0">
                        <!-- <dl class="detail-list">
                            <dt class="detail-head">募集ポジション</dt>
                            <dd class="detail-data">{{job.occupation_description}}</dd>
                            <dt class="detail-head">雇用形態</dt>
                            <dd class="detail-data row m-0">
                                <div class="col-12 col-sm-6 mb-2 p-0" v-for="(result,indx) in types" :key="result.id">
                                    <input type="checkbox" :value="result.employment_type_name" :id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="employment_types"/>
                                    <label class="custom-control-label custom-checkbox-label">{{ $t('jobcreate.employment_type['+indx+']') }}</label>
                                </div>
                            </dd>
                            <dt class="detail-head">勤務地詳細</dt>
                            <dd class="detail-data">
                                <div><pre>{{job.job_location}}</pre></div>
                            </dd>
                            <dt class="detail-head">仕事の内容</dt>
                            <dd class="detail-data">
                                <div><pre>{{job.job_description}}</pre> </div>             
                            </dd>
                            <dt class="detail-head">応募資格</dt>
                            <dd class="detail-data">
                                <div><pre>{{job.qualification}}</pre></div>
                            </dd>
                            <dt class="detail-head">給与・待遇・ビザサポート</dt>
                            <dd class="detail-data">
                                <div><pre>{{job.allowance}}</pre></div>
                                <br />
                            </dd>
                        </dl> -->
                        <!-- <dl class="detail-list" v-if="!showAsScoutEntry">
							<dt class="detail-head">求人タイトル</dt>
							<dd class="detail-data">{{ job.title }}</dd>
                        </dl> -->
                        <label style="color: rgb(0, 98, 255); font-weight: bold;" v-if="job.updated && showJob">{{job.updated}}に求人詳細が修正されています</label>
                        <dl class="detail-list">
							<dt class="detail-head" v-if="showJob">職種</dt>
							<dt class="detail-head" v-if="!showJob">募集ポジション</dt>
							<dd class="detail-data" v-if=" showJob && job.occupation_description != '' && job.occupation_description != null">{{job.occupation_description}}</dd>
							<dd class="detail-data" v-if="!showJob && job.scout_occupation_description != '' && job.scout_occupation_description != null">{{job.scout_occupation_description}}</dd>
							<dd class="detail-data" v-if="(job.occupation_description == '' || job.occupation_description == null) && showJob"> - </dd>
							<dd class="detail-data" v-if="!showJob && (job.scout_occupation_description == '' || job.scout_occupation_description == null) "> - </dd>
							<dt class="detail-head">雇用形態</dt>
							<dd class="detail-data row m-0">	
								<div  class="col-12 col-sm-6 mb-2 p-0" v-for="(result,indx) in types" :key="result.id">
									<input v-if="showJob" type="checkbox" :value="result.employment_type_name" :id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="employment_types"/>
									<input v-else type="checkbox" :value="result.employment_type_name" :id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="job.scout_employment_types"/>
									<label class="custom-control-label custom-checkbox-label">{{ $t('jobcreate.employment_type['+indx+']') }}</label>
								</div>
							</dd>							
                        </dl>
                        <dl class="detail-list" v-if="!showAsScoutEntry">
							<dt class="detail-head">仕事内容</dt>
							<dd class="detail-data">
								<pre v-if="job.job_description != '' && job.job_description != null">{{job.job_description}}</pre>  
								<pre v-else> &nbsp; - </pre>            
							</dd>
							<dt class="detail-head">応募資格・語学力</dt>
							<dd class="detail-data">
								<pre v-if="job.qualification != '' && job.qualification != null">{{job.qualification}}</pre>
								<pre v-else> &nbsp; - </pre>   
							</dd>
							<dt class="detail-head">給与・待遇・ビザサポート</dt>
							<dd class="detail-data">
								<pre v-if="job.allowance != '' && job.allowance != null">{{job.allowance}}</pre>
								<pre v-else> &nbsp; - </pre>
							</dd>
							<dt class="detail-head">勤務開始日</dt>
							<dd class="detail-data">
								<pre v-if="job.job_start_date != '' && job.job_start_date != null">{{ job.job_start_date }}</pre>
								<pre v-else> &nbsp; - </pre>
							</dd>
                        </dl>
                        <dl class="detail-list">
                            <dt class="detail-head">勤務地詳細</dt>
							<dd class="detail-data">
								<pre v-if="showJob && job.job_location != '' && job.job_location != null">{{job.job_location}}</pre>
								<pre v-if="!showJob && job.scout_job_location != '' && job.scout_job_location != null">{{job.scout_job_location}}</pre>
								<pre v-if=" (job.job_location == '' || job.job_location == null) && showJob"> &nbsp; - </pre>
								<pre v-if=" !showJob && (job.scout_job_location == '' || job.scout_job_location == null)"> &nbsp; - </pre>
							</dd>
                        </dl>
                        <dl class="detail-list" v-if="!showAsScoutEntry">
							<dt class="detail-head">応募先アドレス</dt>
							<dd class="detail-data">
								<pre v-if="job.application_address != '' && job.application_address != null">{{ job.application_address }}</pre>
								<pre v-else> &nbsp; - </pre>
							</dd>
							<dt class="detail-head">求職者へのメッセージ</dt>
							<dd class="detail-data">
								<pre v-if="job.message != '' && job.message != null">{{ job.message }}</pre>
								<pre v-else> &nbsp; - </pre>
							</dd>
							<dt class="detail-head">勤務地 (検索用キーワード)</dt>
							<dd class="detail-data">{{ job.country_name }}</dd>
							<dt class="detail-head">職種(検索用キーワード) </dt>
							<dd class="detail-data">{{ job.occupation_name }}</dd>
							<dt class="detail-head">その他キーワード(求人情報に表示されます)</dt>
							<dd class="detail-data row m-0">
								<div class="col-12 col-sm-6 mb-2 p-0" v-for="(result,i) in all_keywords" :key="result">
									<input type="checkbox" :value="result" :id="result.employment_type_name"  class="custom-control-input custom-checkbox"  v-model="keywords"/>
									<label class="custom-control-label custom-checkbox-label">{{ $t('jobcreate.other_keywords['+i+']') }}</label>
								</div>
							</dd>
							
							<!-- 
							<dt class="detail-head">掲載開始日</dt>
							<dd class="detail-data">{{ job.job_post_date }}</dd>
							 -->
						</dl>
                    </div>
                </div>
				</div>				
				<div class="text-center">
					<button class="btn btn-m cancelbtn border-style" @click="close">閉じる</button>
                    <!-- <router-link :to="'/recruiter/jobs/' + job.job_id + '/edit'" class="btn btn-m cancelbtn border-style">{{ $t('common.edit')}}</router-link> -->
				</div>
				
			</div>
		</div>
	</div>
</template>
<script>
export default {
	name: 'DetailsInfoPopup',
	props: {
		show: {
			type: Boolean,
			default: false,
		},
		job: {
			type: Object,
			default: () => ({
                job_id: null,
				title: '',
				job_number: '',
				occupation_description: '',
				employment_types: '',
				job_description: '',
				qualification: '',
				job_location: '',
                allowance: '',
				job_start_date: '',
				scout_occupation_description:'',
				scout_job_location:'',
				scout_employment_types:[],
			}),
		},
		types: {
			type: Array,
			default: () => [],
        },
        showAsScoutEntry: {
			type: Boolean,
			default: false,
		},
        showJob: {
			type: Boolean,
			// default: false,
		}
	},
	data() {
		return {
			showPopup: this.show,
            employment_types: [],          
            all_keywords:[ '新卒歓迎', '未経験歓迎', 'シニア歓迎', '本社採用', '語学不問' ],
            isModalH : false,
            m_h : 'modal_h'
		}
	},
     destroyed () {
        window.removeEventListener('resize', this.handleResize);
    },
	methods: {
    handleResize() {
            let intViewportHeight = window.innerHeight;
            if (intViewportHeight <= 650) {
                this.isModalH = true;
        }  
    },
	close() {
        this.showPopup = false;
        this.$emit('closed', { show: false });
	}
    },   
	created() {
        this.employment_types = this.job.employment_types.split(',');
        this.keywords = this.job.other_keywords.split(',');
        window.addEventListener('resize', this.handleResize);
        this.handleResize();
        
    },    
}
</script>
<style lang="scss" scoped>
.modal_h {
	height: 300px !important; 
}
.modal-body-wrapper{
    height: 500px;
    overflow-y: scroll;
    margin: 0px 0px 10px 0px;
    max-height: 600px;
    width: 100%; 
    border-bottom: 1px solid #e1e1e1;
    
}
.cross-btn {
    padding: 8px 20px;
    border-radius: 50px;
    color: #919191;
    border: 1px solid;
    font-size: 16px;
    top: 15px;
}

.tit-w100{
    width: 100%;
    display: block;
}
@media (max-width: 1400px){
    .modal-body-wrapper {      
        height: 400px;
    }   
}
@media (max-width: 1366px){
    .modal-wrapper .modal-container {
        max-width: 950px;
    }
}
@media only screen and (max-width: 812px) and (orientation: landscape){
	.sp-model-wrap{
		height: 250px;
    	overflow-y: scroll;
	}
	.modal-body-wrapper{
		height: auto;
		overflow: unset;
		max-height: unset;
	}
	.modal_h {
		height: unset !important; 
	}
}
@media screen and (max-width:480px){
	.sp-model-wrap{
		height: 450px;
    	overflow-y: scroll;
	}
	.modal-body-wrapper{
		height: auto;
		overflow: unset;
		max-height: unset;
	}
	.modal_h {
		height: unset !important; 
	}
}
</style>