<template>
    <div class="user-main">
        <h3>{{ $t('resumeupload.title') }}</h3>
        <div class="row card-row">
            <div class="col-12">
                <p class="alert-box">
                    <span class="alert-ico"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                    {{ $t('resumeupload.error') }}</p>
                <p class="mb-0">{{ $t('resumeupload.description') }}<br>
                    {{ $t('resumeupload.mark') }}</p>
            </div>
        </div>

        <div class="row card-row">
            <div class="col-12">
                <h4>{{ $t('resumeupload.resume') }}</h4>
                <div class="row mt-3">                    
                    <div class="col-12 col-md-4 drag-wrapper mb-res" @drop.prevent="changeCV($event,'jp')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.japan') }}</h5>  
                            <!-- <input type="file"  id="cv-jp"  class="hidden-input-file" @change="changeCV($event,'jp')"  /> -->
                            <label for="cv-jp" class="d-block" v-if="!japan_cv_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p class="delete-btn" @click="removeCV('jp')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="japan_cv_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_cv_jp!=''">アップロード日時 :<br/> {{date_time_cv_jp}}</p>
                                    <p class="txt-horizontal-ellipsis mb-0">{{resume_list.cv_jp.real_url}}</p>
                                </div>
                            </span>
                            
                            <div class="upload-footer">
                                <label v-if="!is_download.cv_jp" for="cv-jp" class="btn detailbtn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="cv-jp"  class="hidden-input-file" @change="changeCV($event,'jp')"  />
                                <!-- <button v-if="!is_download.cv_jp" :disabled="is_disabled.cv_jp" class="btn primary-btn rounded-sm w-75 m-auto"  @click="saveCV('jp')">{{ $t('resumeupload.button') }}</button> -->
                                <!-- <button v-if="is_download.cv_jp" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeCV('jp')">{{ $t('resumeupload.downloadbutton') }}</button> -->
                               
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.cv_jp.url" v-if="is_download.cv_jp" class="btn download-btn rounded-sm w-75 m-auto"  :download="[resume_list.cv_jp.real_url ? resume_list.cv_jp.real_url : resume_list.cv_jp.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-res" @drop.prevent="changeCV($event,'eng')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.eng') }}</h5>
                            <input type="file"  id="cv-eng"  class="hidden-input-file" @change="changeCV($event,'eng')"  />
                            <label for="cv-eng" class="d-block" v-if="!english_cv_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p  class="delete-btn" @click="removeCV('en')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="english_cv_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_cv_eng!=''">アップロード日時 : <br/>{{date_time_cv_eng}}</p>
                                    <p class="txt-horizontal-ellipsis mb-0">{{resume_list.cv_en.real_url}}</p>
                                </div>
                            </span>
                            <!-- <div class="upload-footer">
                                <button  v-if="!is_download.cv_eng"  :disabled="is_disabled.cv_eng" class="btn primary-btn rounded-sm w-75 m-auto" @click="saveCV('eng')">{{ $t('resumeupload.button') }}</button>
                                <button v-if="is_download.cv_eng" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeCV('en')">{{ $t('resumeupload.button') }}</button>
                            </div> -->
                            <div class="upload-footer">
                                <label v-if="!is_download.cv_eng" for="cv-eng" class="btn detailbtn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="cv-eng"  class="hidden-input-file" @change="changeCV($event,'eng')"  />
                               
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.cv_en.url" v-if="is_download.cv_eng" class="btn download-btn rounded-sm w-75 m-auto" :download="[resume_list.cv_en.real_url ? resume_list.cv_en.real_url : resume_list.cv_en.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-res" @drop.prevent="changeCV($event,'other')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.other') }}</h5>
                            <!-- <input type="file"  id="cv-other"  class="hidden-input-file" @change="changeCV($event,'other')"  /> -->
                            <label for="cv-other" class="d-block" v-if="!other_cv_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p class="delete-btn" @click="removeCV('other')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="other_cv_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_cv_other!=''">アップロード日時 :<br/> {{date_time_cv_other}}</p>
                                    <p class="txt-horizontal-ellipsis mb-0">{{resume_list.cv_other.real_url}}</p>
                                </div>
                            </span>
                            <!-- <div class="upload-footer">
                                <button v-if="!is_download.cv_other" :disabled="is_disabled.cv_other" class="btn primary-btn rounded-sm w-75 m-auto" @click="saveCV('other')">{{ $t('resumeupload.button') }}</button>
                                <button v-if="is_download.cv_other" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeCV('other')">{{ $t('resumeupload.button') }}</button>
                            </div> -->
                            <div class="upload-footer">
                                <label v-if="!is_download.cv_other" for="cv-other" class="btn detailbtn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="cv-other"  class="hidden-input-file" @change="changeCV($event,'other')"  />
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.cv_other.url" v-if="is_download.cv_other" class="btn download-btn rounded-sm w-75 m-auto" :download="[resume_list.cv_other.real_url ? resume_list.cv_other.real_url : resume_list.cv_other.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row card-row">
            <div class="col-12">
                <h4>{{ $t('resumeupload.Curriculum_vitae') }}</h4>
                <div class="row mt-3">
                    <div class="col-12 col-md-4 mb-res" @drop.prevent="changeExperience($event,'jp')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.japan') }}</h5>
                            <!-- <input type="file"  id="exp-jp"  class="hidden-input-file" @change="changeExperience($event,'jp')"  /> -->
                            <label for="exp-jp" class="d-block" v-if="!japan_exp_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                    
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p class="delete-btn" @click="removeExp('jp')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="japan_exp_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_exp_jp!=''">アップロード日時 :<br/> {{date_time_exp_jp}}</p>
                                    <p>{{resume_list.exp_jp.real_url}}</p>
                                </div>
                            </span>
                            <!-- <div class="upload-footer">
                                <button v-if="!is_download.exp_jp" :disabled="is_disabled.exp_jp" class="btn primary-btn rounded-sm w-75 m-auto" @click="saveExperience('jp')">{{ $t('resumeupload.button') }}</button>
                                <button v-if="is_download.exp_jp" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeExp('jp')">{{ $t('resumeupload.button') }}</button>
                            </div> -->
                            <div class="upload-footer">
                                <label v-if="!is_download.exp_jp" for="exp-jp" class="btn detailbtn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="exp-jp"  class="hidden-input-file" @change="changeExperience($event,'jp')"  />
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.exp_jp.url" v-if="is_download.exp_jp" class="btn download-btn rounded-sm w-75 m-auto"  :download="[resume_list.exp_jp.real_url ? resume_list.exp_jp.real_url : resume_list.exp_jp.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-res" @drop.prevent="changeExperience($event,'eng')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.eng') }}</h5>
                            <!-- <input type="file"  id="exp-eng"  class="hidden-input-file" @change="changeExperience($event,'eng')"  /> -->
                            <label for="exp-eng" class="d-block" v-if="!english_exp_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p class="delete-btn" @click="removeExp('en')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="english_exp_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_exp_eng!=''">アップロード日時 :<br/> {{date_time_exp_eng}}</p>
                                    <p>{{resume_list.exp_en.real_url}}</p>
                                </div>
                            </span>
                            <!-- <div class="upload-footer">
                                <button v-if="!is_download.exp_eng" :disabled="is_disabled.exp_eng" class="btn primary-btn rounded-sm w-75 m-auto" @click="saveExperience('eng')">{{ $t('resumeupload.button') }}</button>
                                <button v-if="is_download.exp_eng" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeExp('en')">{{ $t('resumeupload.button') }}</button>
                            </div> -->
                            <div class="upload-footer">
                                <label v-if="!is_download.exp_eng" for="exp-eng" class="btn detailbtn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="exp-eng"  class="hidden-input-file" @change="changeExperience($event,'eng')"  />
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.exp_en.url" v-if="is_download.exp_eng" class="btn download-btn rounded-sm w-75 m-auto" :download="[resume_list.exp_en.real_url ? resume_list.exp_en.real_url : resume_list.exp_en.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" @drop.prevent="changeExperience($event,'other')" @dragover.prevent>
                        <div class="upload-col">
                            <h5 class="upload-head">{{ $t('resumeupload.other') }}</h5>
                            <!-- <input type="file"  id="exp-other"  class="hidden-input-file" @change="changeExperience($event,'other')"  /> -->
                            <label for="exp-other" class="d-block" v-if="!other_exp_url">
                                <div class="upload-content">
                                    <p> <span class="icon icon-file-upload"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span> </p>
                                    <p>{{ $t('resumeupload.description2')}}</p>
                                </div>
                            </label>
                            <span class="d-block" v-else>
                                <div class="upload-content"> 
                                    <p class="delete-btn" @click="removeExp('other')" title="削除"><span class="glyphicon glyphicon-remove"></span></p>
                                    <img :src="other_exp_url" class="uploaded" alt="" >
                                    <p class="mb-0" v-if="date_time_exp_other!=''">アップロード日時 :<br/> {{date_time_exp_other}}</p>
                                     <p>{{resume_list.exp_other.real_url}}</p>
                                </div>
                            </span>
                            <!-- <div class="upload-footer">
                                <button v-if="!is_download.exp_other" :disabled="is_disabled.exp_other" class="btn primary-btn rounded-sm w-75 m-auto" @click="saveExperience('other')">{{ $t('resumeupload.button') }}</button>
                                <button v-if="is_download.exp_other" class="btn cancelbtn rounded-sm w-75 m-auto" @click="removeExp('other')">{{ $t('resumeupload.button') }}</button>
                            </div> -->
                            <div class="upload-footer">
                                <label v-if="!is_download.exp_other" for="exp-other" class="btn primary-btn rounded-sm w-75 m-auto">{{ $t('resumeupload.button') }}</label>
                                <input type="file"  id="exp-other"  class="hidden-input-file" @change="changeExperience($event,'other')"  />
                                <a :href="'/uploads/jobseekers/cvs/'+ resume_list.exp_other.url" v-if="is_download.exp_other" class="btn download-btn rounded-sm w-75 m-auto"  :download="[resume_list.exp_other.real_url ? resume_list.exp_other.real_url : resume_list.exp_other.url]" >{{ $t('resumeupload.downloadbutton') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        },
        data(){
            return{

                path : '/uploads/jobseekers/cvs/',
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

                cv:{
                   japan:'',
                   english:'',
                   other:'',
                },
                exp :{
                   japan:'',
                   english:'',
                   other:'',
                },
                cv_type:{
                    japan:'',
                    english:'',
                    other:'',
                },
                exp_type:{
                    japan:'',
                    english:'',
                    other:'',
                },
                is_download:{
                    cv_jp:false,
                    cv_eng:false,
                    cv_other:false,
                    exp_jp:false,
                    exp_eng:false,
                    exp_other:false,
                },
                is_disabled:{
                    cv_jp:true,
                    cv_eng:true,
                    cv_other:true,
                    exp_jp:true,
                    exp_eng:true, 
                    exp_other:true,
                },

                resume_list:{},
                file_ext:['pdf','doc','docx','xls','xlsx'],
            }
        },
        created(){
            this.resumeList();
           
        },
        methods:{

            resumeList(){

                let loading = this.$loading.show();
                this.$api.get('/jobseeker/resume-list').then(r => {  
                    console.log("resume",r.data)

                this.resume_list = r.data;

                if(this.resume_list.cv_jp != null)
                { 
                   this.is_download.cv_jp = true;
                   let ext =  this.resume_list.cv_jp.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.japan_cv_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.japan_cv_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.japan_cv_url = this.path + 'file-pdf.svg';
                    }

                    this.date_time_cv_jp = this.resume_list.cv_jp.user_time;

                }
                else{
                    this.is_disabled.cv_jp = true;   
                }   
             
                if(this.resume_list.cv_en != null)
                {
                    this.is_download.cv_eng = true;
                   let ext =  this.resume_list.cv_en.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.english_cv_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.english_cv_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.english_cv_url = this.path + 'file-pdf.svg';
                    } 
                    this.date_time_cv_eng = this.resume_list.cv_en.user_time;
                }
                else{
                    this.is_disabled.cv_eng = true;
                }

                if(this.resume_list.cv_other != null)
                {
                    this.is_download.cv_other = true;
                    let ext =  this.resume_list.cv_other.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.other_cv_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.other_cv_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.other_cv_url = this.path + 'file-pdf.svg';
                    } 
                    this.date_time_cv_other = this.resume_list.cv_other.user_time;
                }
                else{
                    this.is_disabled.cv_other = true;
                }

                if(this.resume_list.exp_jp != null)
                {
                   this.is_download.exp_jp = true;
                   let ext =  this.resume_list.exp_jp.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.japan_exp_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.japan_exp_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.japan_exp_url = this.path + 'file-pdf.svg';
                    } 
                    this.date_time_exp_jp = this.resume_list.exp_jp.user_time;
                }
                else{
                    this.is_disabled.exp_jp = true;
                }

                if(this.resume_list.exp_en != null)
                {
                    this.is_download.exp_eng = true;
                   let ext =  this.resume_list.exp_en.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.english_exp_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.english_exp_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.english_exp_url = this.path + 'file-pdf.svg';
                    }  
                    this.date_time_exp_eng = this.resume_list.exp_en.user_time;
                }
                else{
                     this.is_disabled.exp_eng = true;
                }

                if(this.resume_list.exp_other != null)
                {
                    this.is_download.exp_other = true;
                    let ext =  this.resume_list.exp_other.url.split('.')[1];
                    if(ext == 'doc' || ext == 'docx'){ 
                        this.other_exp_url = this.path + 'microsoftword.svg';
                    }
                    if(ext == 'xls' || ext == 'xlsx'){ 
                        this.other_exp_url = this.path + 'microsoftexcel.svg';
                    }
                    if(ext == 'pdf'){
                         this.other_exp_url = this.path + 'file-pdf.svg';
                    }
                    this.date_time_exp_other = this.resume_list.exp_other.user_time;
                }
                else{
                    this.is_disabled.exp_other = true;
                }
             })
             .catch((e) => { console.log(e) })
             .finally(() => { loading.hide() });

            },

            changeCV(e,lang){
              
                const files = e.target.files || e.dataTransfer.files ; 
                const file = files[0];
                var ext = file.name.split('.').pop();
                
                if(this.file_ext.includes(ext)){
                    
                    if(file.size/1024 <= 300){

                        if(lang == 'jp')  { this.is_download.cv_jp = false, this.is_disabled.cv_jp = false, this.cv.japan = file,this.cv_type.japan = 'cv_jp'};     
                        if(lang == 'eng')  { this.is_download.cv_eng = false,this.is_disabled.cv_eng = false, this.cv.english = file ,this.cv_type.english = 'cv_en'};         
                        if(lang == 'other')  { this.is_download.cv_other = false,this.is_disabled.cv_other = false, this.cv.other = file, this.cv_type.other = 'cv_other'};

                        if(ext == 'doc' || ext == 'docx'){ 

                            if(lang == 'jp')  this.japan_cv_url = this.path + 'microsoftword.svg';
                            if(lang == 'eng') this.english_cv_url = this.path + 'microsoftword.svg';
                            if(lang == 'other')  this.other_cv_url = this.path + 'microsoftword.svg';
                           
                        }
                        else if(ext == 'xls' || ext == 'xlsx'){

                            if(lang == 'jp')  this.japan_cv_url = this.path + 'microsoftexcel.svg';
                            if(lang == 'eng') this.english_cv_url = this.path + 'microsoftexcel.svg';
                            if(lang == 'other')  this.other_cv_url = this.path + 'microsoftexcel.svg';

                        }
                        else{
                            if(lang == 'jp')  this.japan_cv_url = this.path + 'file-pdf.svg';
                            if(lang == 'eng') this.english_cv_url = this.path + 'file-pdf.svg';
                            if(lang == 'other')  this.other_cv_url = this.path + 'file-pdf.svg';
                           
                        }

                        this.$nextTick(() => {
                            this.saveCV(lang);
                        });
                    }
                    else{
                        this.$alertService.showWarningDialog(null,　this.$t('alertMessage.fileSizeWarning'),this.$t('common.close')).then(r => { });
                    }
                }
                else{
                    this.$alertService.showWarningDialog(null, this.$t('alertMessage.fileTypeWarning'),this.$t('common.close')).then(r => { });
                 
                }
            },
            
            saveCV(lang)  
            {
               
                let fd = new FormData();
                if(lang == 'jp')  {  fd.append('type',this.cv_type.japan),fd.append('file',this.cv.japan)};
                if(lang == 'eng') {  fd.append('type',this.cv_type.english),fd.append('file',this.cv.english)};
                if(lang == 'other') { fd.append('type',this.cv_type.other),fd.append('file',this.cv.other)};
             
                this.$api.post('/jobseeker/resume-upload', fd).then(r => { 
                    if(lang == 'jp')  {
                        this.is_download.cv_jp = true;
                    } 
                    if(lang == 'eng') {
                        this.is_download.cv_eng = true;
                    }
                    if(lang == 'other'){
                        this.is_download.cv_other = true;
                    }    
                    this.resumeList();  
                    //   this.$alertService.showSuccessDialog(null, "File has been uploaded").then(r => {  
                           
                    //   });   
                })
                .catch((e) => { console.log(e) });
               
            },

            changeExperience(e,lang){
              
                const files = e.target.files || e.dataTransfer.files ; 
                const file = files[0];

                var ext = file.name.split('.').pop();
                let path = '/uploads/jobseekers/cvs/';
                if(this.file_ext.includes(ext)){

                    if(file.size/1024 <= 300){

                        if(lang == 'jp')  { this.is_download.exp_jp = false, this.is_disabled.exp_jp = false, this.exp.japan = file,this.exp_type.japan = 'exp_jp'};     
                        if(lang == 'eng')  { this.is_download.exp_eng = false, this.is_disabled.exp_eng = false, this.exp.english = file ,this.exp_type.english = 'exp_en'};         
                        if(lang == 'other')  { this.is_download.exp_other = false, this.is_disabled.exp_other = false, this.exp.other = file, this.exp_type.other = 'exp_other'};

                        if(ext == 'doc' || ext == 'docx'){ 

                            if(lang == 'jp')  this.japan_exp_url = this.path + 'microsoftword.svg'
                            if(lang == 'eng') this.english_exp_url = this.path + 'microsoftword.svg'
                            if(lang == 'other')  this.other_exp_url = this.path + 'microsoftword.svg'
                           
                        }
                        else if(ext == 'xls' || ext == 'xlsx'){

                            if(lang == 'jp')  this.japan_exp_url = this.path + 'microsoftexcel.svg'
                            if(lang == 'eng') this.english_exp_url = this.path + 'microsoftexcel.svg'
                            if(lang == 'other') this.other_exp_url = this.path + 'microsoftexcel.svg'

                        }
                        else{
                            if(lang == 'jp')  this.japan_exp_url = this.path + 'file-pdf.svg'
                            if(lang == 'eng') this.english_exp_url = this.path + 'file-pdf.svg'
                            if(lang == 'other')  this.other_exp_url = this.path + 'file-pdf.svg'
                           
                        }    
                        
                        this.$nextTick(() => {
                            this.saveExperience(lang);
                        });
                    }
                    else{
                        this.$alertService.showWarningDialog(null,　this.$t('alertMessage.fileSizeWarning'),this.$t('common.close')).then(r => { });

                    }
                }
                else{
                    this.$alertService.showWarningDialog(null, this.$t('alertMessage.fileTypeWarning'),this.$t('common.close')).then(r => { });
                    
                }
            },

            saveExperience(lang)  
            {
                let fd = new FormData();
                if(lang == 'jp')  { fd.append('type',this.exp_type.japan),fd.append('file',this.exp.japan)};
                if(lang == 'eng') { fd.append('type',this.exp_type.english),fd.append('file',this.exp.english)};
                if(lang == 'other') { fd.append('type',this.exp_type.other),fd.append('file',this.exp.other)};

                this.$api.post('/jobseeker/cv-upload', fd).then(r => {  
                    if(lang == 'jp')  {
                        this.is_download.exp_jp = true;
                    } 
                    if(lang == 'eng') {
                        this.is_download.exp_eng = true;
                    }
                    if(lang == 'other'){
                        this.is_download.exp_other = true;
                    } 
                    this.resumeList(); 
                    //     this.$alertService.showSuccessDialog(null, "File has been uploaded").then(r => {
                             
                    //   }).catch((e) => { console.log(e) });
                });
            },

            downloadCV(lang){
                console.log("File Downloaded!")
            },

            removeCV(lang)
            {
                // console.log("remove",lang)
                this.$alertService.showConfirmDialog(null,this.$t('alertMessage.delete_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((r)=>{
                    if (r.value) {
                        this.$api.get('/jobseeker/file-remove/cv/'+lang).then(r => {  
                            if(lang == 'jp')
                            {
                                this.japan_cv_url = '';
                                this.is_disabled.cv_jp = true;
                                this.is_download.cv_jp = false;
                            }
                            else if(lang == 'en'){
                                this.english_cv_url = '';
                                this.is_disabled.cv_eng = true;
                                this.is_download.cv_eng = false;
                            }
                            else{
                                this.other_cv_url = '';
                                this.is_disabled.cv_other = true;
                                this.is_download.cv_other = false;
                            } 
                            // this.$alertService.showSuccessDialog(null, "File has been deleted").then(r => {
                                
                            // });       
                        });
                    }
                });
            },

            removeExp(lang)
            {
                this.$alertService.showConfirmDialog(null,this.$t('alertMessage.delete_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((r)=>{
                    if (r.value) {

                        this.$api.get('/jobseeker/file-remove/exp/'+lang).then(r => {  
                            if(lang == 'jp')
                            {
                                this.japan_exp_url = '';
                                this.is_disabled.exp_jp = true;
                                this.is_download.exp_jp = false;

                            }
                            else if(lang == 'en'){
                                this.english_exp_url = '';
                                this.is_disabled.exp_eng = true;
                                this.is_download.exp_eng = false;
                            }
                            else{
                                this.other_exp_url = '';
                                this.is_disabled.exp_other = true;
                                this.is_download.exp_other = false;
                            } 
                            // this.$alertService.showSuccessDialog(null, "File has been deleted").then(r => {
                                
                            // });     
                        });
                    }
                });
            },

        }
        
    }
</script>

<style lang="scss" scoped>
.card-row {
    margin: 20px 0;
    padding: 20px;
    background: #fff;
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
.alert-box {
    padding: 10px 20px;
    background: #EAEAEA;
    border: 1px solid #ABABAB;
    border-radius: 5px;
    color: #FF0000;
}
.alert-ico {
    display: inline-block;
    width: 20px;
    height: 20px;
    background: #FF0000;
    border-radius: 50%;
    text-align: center;
}
.alert-box .fa-exclamation {
    color: #fff;
    font-style: italic;
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
.upload-content {
    position: relative;
    padding: 40px 10px 10px 10px;
    margin: 0 11% 25px 11%;
    background: #fff;
    color: #B5C4CC;
    border-radius: 5px;
    min-height:220px;
    cursor:pointer;
}
.upload-content::after {
    content: "";
    position: absolute;
    background: linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%);
    background-position: top, right, bottom, left;
    background-repeat: repeat-x, repeat-y;
    background-size: 17px 3px, 3px 17px;
    top: -2px;
    bottom: -2px;
    left: -2px;
    right: -2px;
    border-radius: 5px;
}
// .upload-content:before {
//     content: "";
//     position: absolute;
//     background: linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%);
//     background-position: top, right, bottom, left;
//     background-repeat: repeat-x, repeat-y;
//     background-size: 17px 3px, 3px 17px;
//     top: 60px;
//     bottom: 100px;
//     left: 46px;
//     right: 46px;
//     border-radius: 5px;
// }
.upload-footer {
    padding: 15px 0;
    border-top: 1px solid #DEDEDE;
    background: #EAEAEA;
     min-height: 76px;
    display: flex;
}
.icon {
    font-size: 70px;
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

.hidden-input-file{
    width: 1px;
    position: absolute;
    overflow: hidden;
    opacity: 0;
    height: 1px;
}

.download-btn {
    font-weight: bold;
    border: 1px solid #6085a3;
    color: #fff;
    background: #6085a3;
}

.delete-btn {
    position: absolute;
    top: -15px;
    right: -10px;
    width: 35px;
    height: 35px;
    font-size: 16px;
    text-align: center;
    background: #fff;
    border-radius: 50%;
    color: #919191;
    border: 1px solid;
    line-height: 32px;
    vertical-align: middle;
    z-index: 8;   
}
.uploaded{
    width: 65px;
    margin-bottom: 1.3rem;
}
.detailbtn{
    background-color: #0062ff;
}
@media screen and (max-width:1024px){
    .uploaded{
        height: auto;
    }    
}
@media only screen and (max-width: 812px) and (orientation : landscape){
    .mb-res{
        margin-bottom: 10px;
    }
}
@media (max-width:580px){
	.upload-col{
		margin-bottom: 30px;
	}
}
@media screen and (max-width:480px){
    .card-row{
        padding-left: 0px;        
        padding-right: 0px;        
    }
    .mb-res{
        margin-bottom: 10px;
    }   
}
</style>