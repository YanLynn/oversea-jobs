<template>
    <div class="user-main">
        <h3>スカウトを受けている求人<small class="pl-4" style="color:#666666;font-size:20px;">未読 <span class="text-primary" style="font-size:25px">{{ notReadCount }}</span> 件 (全 {{ allCount }} 件)</small></h3>
        <div class="scout-list-container">
            <!-- {{scouts}} -->
			<div class="card mb-4" v-for="(scout, index) in scouts" :key="scout.id">
				<div class="card-body custom-box">
					<div class="sp-480">
						<h6 class="scouted-tit"><span class="txt-vertical-ellipsis font-weight-bold scouted-name">{{scout.recruiter_name}}</span><span class="text-label-color kara-txt">からのスカウト</span></h6>
					</div>
					<div class="box-content">						
						<div class="img-col">
                            <div class="thumbnail-image d-inline-block">							
							<img :src="scout.incharge_photo_url" class="img-thumbnail" alt="Recruiter Logo" />
                            <span class="scout-btn">スカウト</span>
					    	<!-- {{scout.scout_status}}	 -->
                            </div>

                            <div class="d-block text-center mt-2" v-if="!scout.read_job && scout.scout_status == '回答待ち'"> <button class="show-status text-danger" disabled="disabled">未読</button></div>

                            <div class="d-block text-center mt-2" v-if="scout.scout_status == '興味あり' || scout.scout_status == '不採用/辞退'"> <button class="show-status" disabled="disabled">{{scout.scout_status}}</button></div>

                            <div class="d-block text-center mt-2" v-if="scout.scout_status == '内定未請求' || scout.scout_status == '入金確認済' || scout.scout_status == '請求済'"> <button class="show-status" disabled="disabled">内定</button></div>
                        </div>
						<div class="txt-box">
						<div class="pc-480">														
							<h6 class="scouted-tit">
                                <span class="txt-vertical-ellipsis font-weight-bold scouted-name">{{scout.recruiter_name}}</span>
								<!-- <span v-if="scout.recruiter_show_name == '正式名称で表示'">
                                    {{scout.recruiter_name}}
                                </span>
                                <span v-else>
                                    <span v-if="scout.recruiter_nick_name == '' || scout.recruiter_nick_name == null">
                                        {{scout.recruiter_name}}
                                    </span>
                                    <span v-else>
                                        {{scout.recruiter_nick_name}}
                                    </span>                                    
                                </span> -->
								<span class="text-label-color kara-txt">からのスカウト</span>
							</h6>
						</div> 							
							<!-- data box -->
							<table class="profile-tbl">
							<tr>
								<td class="tbl-col-tit text-label-color">募集ポジション</td>
								<td class="tbl-col-data txt-vertical-ellipsis"><span v-if="scout.occupation_description != null && scout.occupation_description != ''">{{scout.occupation_description}}</span><span v-else>-</span></td>
							</tr>
                            <tr>
								<td class="tbl-col-tit text-label-color">雇用形態</td>
							 	<td class="tbl-col-data txt-vertical-ellipsis"><span v-if="scout.employment_types != null && scout.employment_types != ''">{{scout.employment_types}}</span><span v-else>-</span></td>
							</tr>
							<tr>
								<td class="tbl-col-tit text-label-color">勤務地詳細</td> 
								<td class="tbl-col-data txt-vertical-ellipsis"><span v-if="scout.job_location != null && scout.job_location != ''">{{scout.job_location}}</span><span v-else>-</span></td>
							</tr>
							
							<tr>
							 <td class="tbl-col-tit text-label-color">スカウト日時</td> 
							 <td class="tbl-col-data txt-vertical-ellipsis"><span>{{ scout.user_scouted_date }}</span></td>
							</tr>
							 </table>
							<!-- end data box -->
						</div>
						<div class="w-auto align-self-end ml-auto buttons-block-sp">
							<div class="btn-wrap">
								<button class="btn my-1 rounded-pill consider-color" @click="makeFavourite(scout.id, index)" v-if="allowFavourite(scout.scout_status)"><i class="fa fa-bookmark pr-1"></i>興味あり</button>
								<button class="btn my-1 rounded-pill start-color" v-if="allowChat(scout.scout_status)" @click="startChat(scout)"><i class="fa fa-comment pr-1"></i>チャット開始</button>
								<button class="btn my-1 rounded-pill decline-color" @click="declineScout(scout.id, index)" v-if="allowFavourite(scout.scout_status) || allowChat(scout.scout_status)"><i class="fa fa-thumbs-down pr-1"></i>辞退</button>
								<button class="btn my-1 recruiter-primary-color rounded-pill text-white" @click="navigateToDetail(scout.id,'profile')" v-if="allowViewDetail(scout.scout_status)"><i class="fa fa-info-circle pr-1"></i>企業プロフィール</button>
								<button class="btn my-1 rounded-pill detail-color" @click="navigateToDetail(scout.id,'detail')" v-if="allowViewDetail(scout.scout_status)">
                                <!-- <i class="fa fa-file-o pr-1"></i> -->
                                <svg viewBox="0 0 14.56 17.89" class="file-ico"><defs></defs><g id="Layer_2" data-name="Layer 2"><g id="レイヤー_1" data-name="レイヤー 1"><path class="cls-1" d="M11.56,0H3A3,3,0,0,0,0,3V14.89a3,3,0,0,0,3,3h7.65l.33-.32,3.25-3.26.33-.33V3A3,3,0,0,0,11.56,0Zm1.89,13.52H11.76a1.57,1.57,0,0,0-1.57,1.58v1.68H3a1.89,1.89,0,0,1-1.89-1.89V3A1.89,1.89,0,0,1,3,1.11h8.56A1.89,1.89,0,0,1,13.45,3Z"/><path class="cls-1" d="M8.73,9.73a2.5,2.5,0,0,1-1.45.46,2.56,2.56,0,0,1-1.46-.46,3,3,0,0,0-1.43,1.33c-.36.64-.08,1.54.54,1.54H9.62c.63,0,.91-.9.55-1.54A3,3,0,0,0,8.73,9.73Z"/><path class="cls-1" d="M7.28,9.58A1.91,1.91,0,0,0,9.2,7.67V7.21a1.92,1.92,0,1,0-3.84,0v.46A1.91,1.91,0,0,0,7.28,9.58Z"/></g></g></svg>
                                求人詳細</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<infinite-loading @distance="1" @infinite="infiniteHandler" spinner="waveDots">				
                 <div slot="no-results" class="row m-0 col-12 p-0">
                    <p class="no-data-txt">データがありません</p>
                </div>
				<div slot="no-more" class=""></div>
				<div slot="error" class="py-3 text-danger">エラーが発生しました</div>
			</infinite-loading>

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
			page: 1,
			unreadCount: 0,
			totalScout: 0,
			scouts: [],
			scouted_count:0
		
		}
	},



	computed:{
	
        currentUser() {
            return this.$store.getters.currentUser
		},
		notReadCount() {
			return this.unreadCount;
		},
		allCount() {
			return this.totalScout;
		},		
	},
	methods: {

		// getCount()
		// {
		// 	this.$api.get(`/jobseeker/scouted-count`).then(r => {
		// 		this.scouted_count = r.data;
		// 		this.$store.commit('increment',this.scouted_count)
		// 	});
		// },

		allowFavourite(status) {

			return status == this.$configs.scouts.waiting;
		},
		allowChat(status) {
			return status == this.$configs.scouts.interested || 
				status == this.$configs.scouts.unclaimed ||
				status == this.$configs.scouts.payment_confirmed ||
				status == this.$configs.scouts.billed;
		},
		allowViewDetail(status) {
			return status != this.$configs.scouts.declined;
		},
		makeFavourite(scoutId, index) {	
			let loading = this.$loading.show();
	      
			this.$api.post('/jobseeker/scouts/make-action', {
				scoutId: scoutId, action: 'favourite'
			})
			.then(() => {
				loading.hide();
				this.scouts[index].scout_status = this.$configs.scouts.interested;
				this.unreadCount -= 1;

				// --Fire an event to refresh chattable list
				this.$emit('refreshChattables');
			})
			.catch(err => {
				loading.hide();
                // alert("エラーが発生しました。")
                console.log(err);
			})
			this.$store.dispatch('jobseekerScoutedCount')
			
		},
		declineScout(scoutId, index) {
			this.$alertService.showConfirmDialog(null,this.$t('alertMessage.decline'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                if(dialogResult.value){
					let loading = this.$loading.show();
					this.$api.post('/jobseeker/scouts/make-action', {
						scoutId: scoutId, action: 'decline'
					})
					.then(() => {
						loading.hide();
						this.scouts[index].scout_status = this.$configs.scouts.declined;

						// --Fire an event to refresh chattable list
						this.$emit('refreshChattables');
					})
					.catch(err => {
						loading.hide();
                        // alert("エラーが発生しました。")
                        console.log(err);
					})
				}
			});			
		},
		navigateToDetail(scoutId,activeTag) {
            
			this.$router.push({ name: 'JobseekerScoutedDetail', params: { 'scoutId': scoutId,'activeTag':activeTag } });
		},
		infiniteHandler($state) {        
			this.$api.get(`/jobseeker/${this.currentUser.id}/scouts?page=${this.page}`)
			.then(r => {
                console.log(r)
				let newScouts = r.data.data;
				this.totalScout = r.data.total;
				this.unreadCount = r.data.unread_count;
				if (newScouts.length > 0) {
					this.page += 1;
					this.scouts.push(...newScouts);
					$state.loaded();
				} else {
					$state.complete();
				}
				
			})
			.catch(() => {
				// alert("エラーが発生しました。");
            })
            
            // this.$api.get('/recruiter/getalldata').then(r => Promise.resolve(r.data)).catch(error => Promise.reject(error.response))
            // .then(response => {			
            //     this.employment_types = response[0].employment;
            // })
		},
		startChat(scout) {
			const payload = {
				recruiter_id: scout.recruiter_id,
				jobseeker_id: scout.jobseeker_id,
				scoutid_or_applyid: scout.id,
				type: 'scout',
				status: scout.scout_status,
			};
			this.$emit('chatStarted', payload);
		},
	}
};
</script>
<style lang="scss" scoped>
.txt-vertical-ellipsis-1{
	line-height: 2;
}
.show-status{
    width: 100%;
}
.thumbnail-image {   
    cursor: default;
    width: 125px;
    height: 125px;   
    box-shadow: none;
}
.thumbnail-image > img {
   width: 100%;
    height: 100%;  
    object-fit: cover;
    transition: all 250ms;
    object-position: top;
}
.rounded-pill {
    width: 195px;
    padding: 0.7rem 2rem;
}
.scout-btn {
    width: 100%;
    margin-top: 10px;
    background: #86A4AC;
    color: #fff;
    padding: 2px 0px;
    display: block;
    text-align: center;
    border-radius: 5px;
    cursor: default;
}
.w-60{
    width: 60%;
}
.kara-txt{
   flex: 0 0 120px;
   font-size:15px;
}
.file-ico {
	width: 18px;
	padding-right: .25rem;
	margin-bottom: 2px;
}
.cls-1{
	fill:#fff;
}
.btn:hover .cls-1{
	opacity: .9;
	fill:#212529;
} 
.btn-wrap{
    display: flex;
    flex-direction: column;
}
.box-content{
	display:flex;
}
.scouted-tit{
    display: flex;
	align-items: center;
	margin-bottom: 1rem;	
	.scouted-name{
		padding-right:2px;
	}
}
.profile-tbl 
.tbl-col-data{
	width: 100%;
	line-height: 1.5;
}
tr{
	border-bottom: 10px solid transparent;
}
.tbl-col-tit{
    width: 150px;
    vertical-align: top;
    line-height: 1.5;
    font-size: 15px;
}

.txt-box{	
    padding: 0 3rem;
	width: 100%;
}
.pc-480 {
	display: block;
}
.sp-480 {
	display: none;
}
@media screen and (max-width:1280px){
	.txt-box{		
		padding: 0 1rem;
	}
}
@media screen and (max-width:820px){
.tbl-col-tit {
    width: 120px;
}
.txt-box{
	width: 66%;
    padding: 0 0 0 0.5rem;
}
}
@media screen and (max-width:767px){
.custom-box{
	padding:15px;
}
.txt-vertical-ellipsis{
	word-break: break-all;
}
.rounded-pill{
    width: 135px;
	padding: 0.7rem 0.5rem;
	margin: 0 3px;
    width: 130px;
    font-size: 12px;
}
.tbl-col-data span{
	padding-left: 10px;
}
}
@media (max-width: 680px){
.tbl-col-tit{
	display: block;
	width: 100%;
}
.profile-tbl tr{
    border-bottom: 3px solid transparent;
}
.txt-vertical-ellipsis {
	-webkit-line-clamp: 1;
}
}
@media screen and (max-width: 480px){
.pc-480 {
	display: none;
}
.sp-480 {
	display: block;
} 
.custom-box {
		padding: 10px;
}	
.box-content {
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
}
.img-col {
		width: 125px;
}
.txt-box {
	width: calc(100% - 125px);
	padding: 0  0 0 .5rem;
}
.scouted-tit {
	flex-wrap: wrap;
}
.scouted-tit .txt-vertical-ellipsis{
	-webkit-line-clamp: 2;
}
.btn-wrap {
	display: inline-block;
	text-align: center;
}
.btn {
	margin: 0 3px;
}
}
@media screen and (max-width: 414px){
	.buttons-block-sp{
		margin: 0 auto !important;
	}	
}
@media (max-width: 340px) { 
	.img-col {
		width: 100px;
	}
	.txt-box {
		width: calc(100% - 100px);
		font-size: 12px;
	}
	.btn {
		width: 120px;
		font-size: 12px;
	}
	.img-cover {
		width: 100px;
		height: 100px;
	}
	.scout-btn , .show-status{
		width: 100px;
	}
}
@media screen and (max-width:320px){
	.img-col{
		width: 39%;
	}
	.thumbnail-image{
		width: 100px;
		height: 100px;
	}
	.txt-box {
		width: 60%;
		font-size: 12px;
	}
	.rounded-pill{
		padding: 6px 1px;
		margin: 0 3px;
		width: 130px;
		font-size: 12px;
		width: 120px;
		font-size: 12px;
		line-height: 2;
	}
}
</style>
