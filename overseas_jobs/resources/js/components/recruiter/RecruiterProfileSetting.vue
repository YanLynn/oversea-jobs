<template>
   <div class="user-main">
    <h2 class="user-head-tit recruiter-primarylight-color">企業プロフィール
    	<button @click="editProfile" class="btn btn-m border-style border-btn float-right title-btn">編集</button>
    </h2>   
    <div class="vld-parent" ref="progressContainer">
      <ul>
          <li class="list-group-item d-flex  align-items-center tab-content">
             <div class="col-12 p-0">
                     <dl class="detail-list">
                        <dt class="detail-head-group detail-group-topline mt-4">企業の登記地</dt>
                        <dd class="detail-data-group detail-group-topline mt-4">{{ recruiter.registered_place_type == 'oversea' ? '海外企業' : '国内企業' }}</dd>
                        <dt class="detail-head-group">企業会員番号</dt>
                        <dd class="detail-data-group">{{ recruiter.recruiter_number }}</dd>
                        <dt class="detail-head-group">企業名</dt>
                        <dd class="detail-data-group">{{ recruiter.recruiter_name }}</dd>
                        <!-- <dt class="detail-head-group">企業名（愛称）</dt>
                        <dd class="detail-data-group">{{ recruiter.recruiter_nick_name }}</dd> -->
                        
                        <dt class="detail-head-group detail-group-topline">設立年月</dt>
                        <!-- <dd class="detail-data-group detail-group-topline">{{recruiter.establishment_date[0]}} 年 {{recruiter.establishment_date[1]}} 月</dd> -->
                        <dd class="detail-data-group detail-group-topline">
                            <span v-if="recruiter.establishment_date != '' && recruiter.establishment_date != null">
                                {{ recruiter.establishment_date }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">代表者名</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.representative_name != '' && recruiter.representative_name != null">
                                {{ recruiter.representative_name }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">従業員数</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.num_of_employees != '' && recruiter.num_of_employees != null">
                                {{ recruiter.num_of_employees }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">事業内容</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.business_description != '' && recruiter.business_description != null">
                                {{ recruiter.business_description }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">所在地</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.address != '' && recruiter.address != null">
                                {{ recruiter.address }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">電話番号 1</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.phone1 != '' && recruiter.phone1 != null">
                                {{ recruiter.phone1 }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>
                        <dt class="detail-head-group">電話番号 2</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.phone2 != '' && recruiter.phone2 != null">
                                {{ recruiter.phone2 }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>                        
                        <dt class="detail-head-group">ご担当者様メールアドレス</dt>
                        <dd class="detail-data-group">{{ recruiter.email }}</dd>
                        <dt class="detail-head-group">WEB サイト URL</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.website != '' && recruiter.website != null">
                                <a :href="recruiter.website" target="_blank">{{ recruiter.website }}</a>
                            </span>
                            <span v-else>未入力</span>
                        </dd>

                        <dt class="detail-head-group detail-group-topline">ご担当者名</dt>
                        <dd class="detail-data-group detail-group-topline">
                            <span v-if="recruiter.incharge_name != '' && recruiter.incharge_name != null">
                                {{ recruiter.incharge_name }}
                            </span>
                            <span v-else>未入力</span>
                        </dd> 
                         <dt class="detail-head-group">ご担当者名(フリガナ)</dt>
                        <dd class="detail-data-group">
                            <span v-if="recruiter.incharge_name_furigana != '' && recruiter.incharge_name_furigana != null">
                                {{ recruiter.incharge_name_furigana }}
                            </span>
                            <span v-else>未入力</span>
                        </dd>                    
                        <dt class="detail-head-group">秘密の質問</dt>
                        <dd class="detail-data-group">{{ recruiter.question }}</dd>
                        <dt class="detail-head-group">秘密の質問の答え</dt>
                        <dd class="detail-data-group">{{ recruiter.answer }}</dd>                     
                    </dl>
                    <div class="row m-t-30 mb-4">
                       <div class="col-12 col-sm-6">
                           <h6 class="text-label-color">会社ロゴ</h6>
                                <div class="img-block">
                                    <img :src="recruiter.logo_url" class="img-fluid img-contain"  alt="Recruiter Logo" />
                                </div>
                       </div>
                       <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                           <h6 class="text-label-color">担当者顔写真（スカウトした人材のみ閲覧可）</h6>   
                                <div class="profile-box-l">
                                   <img :src="recruiter.incharge_photo_url" class="img-fluid img-contain"  alt="Recruiter Incharge Photo"/>
                               </div>        
                       </div>
                   </div>
                    <div class="row mb-4">
                        <div class="col-12 col-sm-6">
                            <h6 class="text-label-color">関連画像</h6>
                            <div class="row col-12 p-0 m-0" v-if="recruiter.related_images">
                                 <div v-for="(image,index) in recruiter.related_images" :key="image.id" class="col-6 mb-3 related-block img-cursor" @click="imagePopup(image.file_url)">
                                    <img v-if="hasRelatedImage(index) " :src="image.file_url" class="img-fluid img-contain" :alt="'Recruiter related image'+(index+1)" />                                  
                                </div>
                                <!-- <div class="col-6 mb-3 image-block border-0">
                                    <img v-if="hasRelatedImage(0) " :src="recruiter.related_images[0].file_url" class="img-fluid img-contain" alt="Recruiter related image 1" />
                                </div>
                                <div  class="col-6 mb-3 image-block border-0">
                                    <img v-if="hasRelatedImage(1)" :src="recruiter.related_images[1].file_url" class="img-fluid img-contain" alt="Recruiter related image 2" />
                                </div>
                                <div class="col-6 mb-3 image-block border-0">
                                    <img v-if="hasRelatedImage(2)" :src="recruiter.related_images[2].file_url" class="img-fluid img-contain" alt="Recruiter related image 3" />
                                </div>
                                <div class="col-6 mb-3 image-block border-0">
                                    <img v-if="hasRelatedImage(3)" :src="recruiter.related_images[3].file_url" class="img-fluid img-contain" alt="Recruiter related image 4" />
                                </div> -->
                               	<div class="col-6 mb-3 related-block" v-for="n in 4 - recruiter.related_images.length" :key="n.id">
								    <img :src="defaultImageUrl" class="img-contain" alt="Recruiter related image 4" />								
						     	</div>
                            </div>
                             <!-- popup images-->                                
                               <transition name="fade">
                                 <div class="img-modal-wrapper" v-if="showModal">
                                    <div class="img-modal-block">
                                        <div class="img-modal-container">
                                           <button class="cross-btn" @click="showModal = false">
                                                 <i class="fa fa-times pr-1"></i>閉じる</button>                                            
                                            <img :src="this.imgUrl" class="img-popup"/>                                        
                                        </div>
                                    </div>
                                </div>
                            </transition>                                
                            <!--end popup images-->
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6 class="text-label-color">関連画像</h6>    
                                <div v-if="!recruiter.video" class="movie-wrapper">
                                    <p class="no-video movie-link">
                                    <!-- <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 動画は利用できません -->
                                     <img src="/images/youtube.png" alt="動画なし" class="img-fluid">  
                                    </p>
                                </div>
                         
                                <div v-else class="movie-wrapper">
                                     <iframe class="movie-link" :src="recruiter.video_url" frameBorder="0" width="530" height="315"></iframe>
                                </div>
                        </div>
                    </div>
                    <div class="row col-12"> 
                        <h6 class="text-label-color">会社 PR 等</h6>
                    </div>
                    <div class="row about-row mt-0">
                       <div class="col-12">                           
                           <pre>{{ recruiter.company_pr }}</pre>
                       </div>
                   </div>
                </div>     
          </li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    }, 
    data() {
        return {
            getdate:'',
			recruiter: {
                establishment_date:'',
            },
            defaultImageUrl:'',
            showModal : false,
            imgUrl: '',
        }
	},
	computed: {
        
		currentUser() {
			return this.$store.getters.currentUser;
		}		
    },
    created()
    {   
        
        this.$store.dispatch('user_image');	
        this.$api.get('/default-image')
		.then(r => { this.defaultImageUrl = r.data.data; });

    },
 
	mounted() {
         this.$store.dispatch('user_image');
        this.$store.dispatch('user_info');
		let loading = this.$loading.show();
		this.$api.get('/recruiter/profile')
		.then(r => {
            console.log("rec",r.data);
            this.recruiter = r.data.data;
            // this.getdate = r.data.data.establishment_date;
            // this.recruiter.establishment_date = this.getdate.split('-');	
		})
		.catch((e) => {			
            // this.$alertService.showErrorDialog(null, 'エラーが発生しました。');
            console.log(e);
		})
        .finally(() => loading.hide());

        
	},
	methods: {
		editProfile() {
			this.$router.push({ name: "RecruiterProfileEdit" });
		},
		hasRelatedImage(index) {
			return this.recruiter.related_images && this.recruiter.related_images[index];
        },
        imagePopup(url) {
            if(url.includes('images/default.png' || url.includes('images/no_public.png'))){
                return false;
            }
            else{
                this.showModal =  true;
                this.imgUrl = url;
            }
          
        },
	}
}
</script>
<style lang="scss" scoped>
//popup view related images
.img-modal-wrapper {
     position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s ease;
    z-index: 10001;  
}
.img-modal-wrapper .img-modal-block {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 950px;
    height: 80vh;
    transition: all 0.3s ease;
    text-align: center;
}

.img-modal-wrapper .img-modal-container {
   height: 100%;  
}
.img-modal-wrapper .cross-btn {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 16px;
    background: #999;
    padding: 4px 10px;
    border-radius: 50px;
    color:#fff;
    border: 1px solid;
}
.img-cursor{
    cursor: pointer;
}
.img-popup{
    width: 100%;
    height: 100%;
    object-fit: contain;
}
@media (max-width: 480px){
    .tab-content{
        padding: 0 15px 15px 15px;
    }
    .user-head-tit{
        padding: 15px;
        font-size:17px;
    }
    .border-btn{
        width: 80px;
        min-width: auto;
        margin-top: -1px;
        padding: 3px 0;
        top:15px;
        right:15px;
    }
}

</style>