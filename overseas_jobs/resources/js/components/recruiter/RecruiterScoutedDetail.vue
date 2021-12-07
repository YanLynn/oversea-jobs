<template>
	<div class="user-main vld-parent" ref="mainContainer">
		<div v-show="loaded">
			<div class="legends" ref="progressContainer">
				<div class="d-flex align-items-center pt-3 px-2">
					<h3 class="flex-grow-1">{{ jobseeker.name }} 詳細プロフィール</h3>
					<span @click="$router.go(-1)" class="btn cancelbtn mx-3 border-style" >戻る</span>
					<button class="btn recruiter-primary-color border-style" @click="downloadCVs">履歴書経歴書ダウンロード</button>
				</div>
				<div class="tab-list text-center">　　　　　　　　　　　
					<ul class="list-group list-group-horizontal" v-on:scroll="handleScroll">
						 <li v-for="(item, index) in items" class="list-group-item" :key="index.id"> 
							  <a  v-scroll-to="{el:'#'+(item,index),offset:-150}"
							  		href="#"
									:id="item.text"                          
									:class="{isActive: activeIndex === index ? 'active':''}"                       
									class="list-link">{{ item.text }}</a>               
							
							</li>
					</ul>
				</div>
			</div>            
			<!-- Jobseeker Profile -->
			<JobseekerDetailForScout v-if="jobseeker.id" :jobseeker-id="jobseekerId" :status="transaction.transaction_status" :key="jobseekerId"></JobseekerDetailForScout>
			<!-- End Jobseeker Profile -->
		</div>
		<!-- Download CV Dialog -->
		<transition name="fade">
			<div class="modal-wrapper" v-if="showDownloadPopup" style="z-index:9998;">
				<div class="modal-block">
					<div class="modal-container">
						<div class="head-wrap m-b-5">
							<h3 class="text-left header recruiter-primary-color-txt">履歴書経歴書ダウンロード
							<button class="cross-btn" @click="showDownloadPopup = false">
								<svg x="0px" y="0px" width="40" height="40" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="none" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none" stroke="none" stroke-width="1"></path><g><path d="M10.75,10.75h150.5v150.5h-150.5z" fill="#ffffff" stroke="none" stroke-width="1"></path><path d="M159.1,12.9v146.2h-146.2v-146.2h146.2M163.4,8.6h-154.8v154.8h154.8v-154.8z" fill="#ffffff" stroke="none" stroke-width="1"></path><path d="M50.2756,50.2756l71.4488,71.4488M121.7244,50.2756l-71.4488,71.4488" fill="none" stroke="#808080" stroke-width="12.9"></path></g></g></svg>
                            </button>
                            </h3>
						</div>
                        <div class="modal-body-wrapper">
                            <div class="card-row mt-0">
                                <div class="col-12">
                                    <h5>{{ $t('resumeupload.resume') }}</h5>
                                    <div class="row mt-3">
                                        <div class="col-4 drag-wrapper">
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.japan') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="japan_cv_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_cv_jp!=''">アップロード日 : {{date_time_cv_jp | date('%Y-%m-%d')}}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="cv_jp.id" @click="downloadFile(cv_jp)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.eng') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="english_cv_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_cv_eng!=''">アップロード日 : {{ date_time_cv_eng|date('%Y-%m-%d') }}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="cv_en.id" @click="downloadFile(cv_en)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.other') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="other_cv_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_cv_other!=''">アップロード日 : {{ date_time_cv_other|date('%Y-%m-%d') }}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="cv_other.id" @click="downloadFile(cv_other)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-row">
                                <div class="col-12">
                                    <h5>{{ $t('resumeupload.Curriculum_vitae') }}</h5>
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.japan') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="japan_exp_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_exp_jp!=''">アップロード日 : {{ date_time_exp_jp|date('%Y-%m-%d') }}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="exp_jp.id" @click="downloadFile(exp_jp)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.eng') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="english_exp_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_exp_eng!=''">アップロード日 : {{ date_time_exp_eng|date('%Y-%m-%d') }}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="exp_en.id" @click="downloadFile(exp_en)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4" @drop.prevent="changeExperience($event,'other')" @dragover.prevent>
                                            <div class="upload-col">
                                                <h5 class="upload-head">{{ $t('resumeupload.other') }}</h5>
                                                <span class="d-block">
                                                    <div class="uploaded-content">
                                                        <img :src="other_exp_url" class="uploaded" alt="" >
                                                        <p v-if="date_time_exp_other!=''">アップロード日 : {{ date_time_exp_other|date('%Y-%m-%d') }}</p>
                                                    </div>
                                                </span>
                                                <div class="upload-footer">
                                                    <label class="btn recruiter-primary-color w-75 m-auto" v-if="exp_other.id" @click="downloadFile(exp_other)">ダウンロードする</label>
                                                    <label class="text-danger w-75 m-auto" v-else>ファイルがない</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="text-center">
							<button class="btn btn-m cancelbtn border-style" @click="showDownloadPopup = false">閉じる</button>
						</div>
					</div>
				</div>
			</div>
		</transition>
		<!-- End Download CV Dialog -->
	</div>
</template>
<script>
import JobseekerDetailForScout from '../jobseeker/JobseekerDetailForScout';

const preview = (ext) => {
	let preview = '';
	switch (ext) {
		case 'doc': case 'docx':
			preview = 'microsoftword.svg';
			break;

		case 'xls': case 'xlsx':
			preview = 'microsoftexcel.svg';
			break;

		case 'pdf':
			preview = 'file-pdf.svg';
			break;
	}
	return  preview;
}

function elementInViewport(el) {
  var top = el.offsetTop;
  var height = el.offsetHeight;
  var bottom = top + height;

  while(el.offsetParent) {
    el = el.offsetParent;
    top += el.offsetTop;
  }

  return (
    !(top < window.pageYOffset && bottom < window.pageYOffset) &&
      !(top > (window.pageYOffset + (window.innerHeight)) && bottom > window.pageYOffset + (window.innerHeight))
  );
}

/**
 * @component Recruiter scout jobseeker
 * @author Myo Ko Ko @ 2020/08/20
 * @last_maintained Myo Ko Ko @ 2020/09/15
 */
export default {
	components: { JobseekerDetailForScout },
	props: {
		transactionId: {
			type: String, 
			default: ''
		},
		type: {
			type: String, 
			default: ''
		}
	},
	data() {
		return {
			loaded: false, /* control variable to defer rendering of template */
			path : '/uploads/jobseekers/cvs/',
			scout: {},
			jobseeker: {},
			cv_en: {},
			cv_jp: {},
			cv_other: {},
			exp_en: {},
			exp_jp: {},
			exp_other: {},

			japan_cv_url:'',
			english_cv_url:'',
			other_cv_url:'',
			japan_exp_url:'',
			english_exp_url:'',
			other_exp_url:'',

			date_time_cv_jp: '',
			date_time_cv_eng: '',
			date_time_cv_other: '',

			date_time_exp_jp: '',
			date_time_exp_eng: '',
			date_time_exp_other: '',
			/* control variable for download dialog */
			showDownloadPopup: false,
			items: {
				selfIntroEdit:{text:'自己紹介',id:1},
				information:{text:'基本情報',id:2},
				experience:{text:'経歴',id:3},
				qualification:{text:'経験・資格',id:4},
				condition:{text:'希望条件',id:5},  
				 },
			activeIndex:null,
		};
	},
	computed: {
		jobseekerId() {
			return this.jobseeker.id;
		}
	},
	methods: {
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
		downloadCVs() {
			this.showDownloadPopup = true;
		},
		downloadFile(uploadedFile) {
			if (uploadedFile.id) {
				this.$api.post(`/recruiter/scouted/download/${uploadedFile.id}`, {}, { responseType: "arraybuffer" })
				.then((r) => {
					const type = r.headers["content-type"];
					const blob = new Blob([r.data], { type: type, encoding: "UTF-8" });
					const filename = uploadedFile.url;
					const objectUrl = window.URL.createObjectURL(blob);
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
				.catch(err => {
					console.log(err.message);
				});
			}
		}
	},
	destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
	created() {
		window.addEventListener('scroll', this.handleScroll);
		const loading = this.$loading.show({ isFullPage: false, container: this.$refs.mainContainer });
		this.$api.get(`/recruiter/jobseeker-detail/${this.type}/${this.transactionId}`)
		.then(r => {
			let data = r.data.data;
			let file_configs = this.$configs.uploaded_file_type;
			this.transaction = data;
			this.jobseeker = {
				id: data.jobseeker_id,
				name: data.jobseeker_name,
				furigana_name: data.jobseeker_furigana_name,
			};

			this.cv_en = data.jobseeker_files.find(x => x.file_type == file_configs['cv-en']) ?? {};
			this.cv_jp = data.jobseeker_files.find(x => x.file_type == file_configs['cv-jp']) ?? {};
			this.cv_other = data.jobseeker_files.find(x => x.file_type == file_configs['cv-other']) ?? {};
			this.exp_en = data.jobseeker_files.find(x => x.file_type == file_configs['exp-en']) ?? {};
			this.exp_jp = data.jobseeker_files.find(x => x.file_type == file_configs['exp-jp']) ?? {};
			this.exp_other = data.jobseeker_files.find(x => x.file_type == file_configs['exp-other']) ?? {};
						
			// --File Icon Previews
			let ext = this.cv_jp.url ? this.cv_jp.url.split('.')[1] : false;
			if (ext) {
				this.japan_cv_url = this.path + preview(ext);
				this.date_time_cv_jp = this.cv_jp.updated_at;
			}
			ext = this.cv_en.url ? this.cv_en.url.split('.')[1] : false;
			if (ext) {
				this.english_cv_url = this.path + preview(ext);
				this.date_time_cv_eng = this.cv_en.updated_at;
			}
			ext = this.cv_other.url ? this.cv_other.url.split('.')[1] : false;
			if (ext) {
				this.other_cv_url = this.path + preview(ext);
				this.date_time_cv_other = this.cv_other.updated_at;
			}
			ext = this.exp_jp.url ? this.exp_jp.url.split('.')[1] : false;
			if (ext) {
				this.japan_exp_url = this.path + preview(ext);
				this.date_time_exp_jp = this.exp_jp.updated_at;
			}
			ext = this.exp_en.url ? this.exp_en.url.split('.')[1] : false;
			if (ext) {
				this.english_exp_url = this.path + preview(ext);
				this.date_time_exp_eng = this.exp_en.updated_at;
			}
			ext = this.exp_other.url ? this.exp_other.url.split('.')[1] : false;
			if (ext) {
				this.other_exp_url = this.path + preview(ext);
				this.date_time_exp_other = this.exp_other.updated_at;
			}
		})
		.catch(() => {
			this.loaded = false;
		})
		.finally(() => {
			loading.hide();
			this.loaded = true;
		});
	}
}
</script>
<style scoped>
.isActive{
        color: #0062ff !important;
        font-weight: bold;       
        border-bottom: 1px solid #0062ff;     
    }
.tab-content {
	padding: 50px 60px;
	margin: 0;
	background: #fff;
	box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
	border-radius: 5px !important;
}
.legends  {		
  	position: sticky;
	z-index: 1000;
	top: 0px;
	background: rgba(244, 242, 250, 0.8);
	backdrop-filter: blur(15px);
	-webkit-backdrop-filter: blur(15px);
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
    color: #00a195;
}
.alert {
	font-size: 1.1rem;
}

.modal-wrapper .modal-container {
	max-width: 950px;
	padding: 20px 30px;
}
.modal-body-wrapper{
    min-height: 500px;
    overflow-y: scroll;
    margin: 0px 0px 10px 0px;
    max-height: 600px;
    width: 100%; 
    border-bottom: 1px solid #e1e1e1;
    
}

.card-row {
    margin: 20px 0;
    padding: 20px 5px;
    background: #fff;
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	border: 1px solid #ddd;
}
.upload-col {
    padding: 20px 0 0 0;
    background: #F0F0F0;
    border: 1px solid #DEDEDE;
    border-radius: 5px;
    text-align: center;
}
.upload-head {
    margin-bottom: 20px;
    color: #808080;
}
.uploaded-content {
    position: relative;
    padding: 40px 10px 10px 10px;
    margin: 0 9.5% 25px 9.5%;
    background: #fff;
    color: #b5ccc7f2;
    border-radius: 5px;
    min-height:200px;
    cursor:pointer;
}

.uploaded-content::after {
    content: "";
    position: absolute;
    background: linear-gradient(to right, #b5ccc7f2 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#b5ccc7f2 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(to right, #b5ccc7f2 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#b5ccc7f2 50%, rgba(255, 255, 255, 0) 0%);
    background-position: top, right, bottom, left;
    background-repeat: repeat-x, repeat-y;
    background-size: 17px 3px, 3px 17px;
    top: -2px;
    bottom: -2px;
    left: -2px;
    right: -2px;
    border-radius: 5px;
}
.upload-footer {
    padding: 15px 0;
    border-top: 1px solid #DEDEDE;
    background: #EAEAEA;
    min-height: 70px;
    display: flex;
}

.btn.disabled, .btn:disabled {    
    cursor: not-allowed;
    background: #a3a3a3;
    z-index: 9;
    opacity: 0.3;

}
.btn:disabled:hover, .btn:disabled:focus{
    color: #fff !important;
}
.uploaded {
    width: 70px;
    height: 100px;
}
.cross-btn {
    border:none !important;
    padding:0px !important;
    top:-8px !important;
}
</style>