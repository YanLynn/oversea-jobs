<template>
  <div class="user-main vld-parent" ref="progressContainer">
	  	<div v-if="scout.scout_status != $configs.scouts.declined">
			<div class="col-12 scouted-col scouted-row">
				<div class="col-12 scouted-col">
					<div class="box-content">
						<div class="sp-480">
							<h6 class="scouted-tit mb-3"><strong class="txt-vertical-ellipsis">{{ scout.recruiter_name }}</strong><span class="kara-txt">からのスカウト</span></h6>
							<label style="color:#0062ff;font-weight:bold;line-height:1.2;" class="sp-480">画面下の求人詳細より以下の条件でオファーいたします</label>
						</div>
						<div class="img-col">
							<div class="scouted-img">
								<img :src="scout.incharge_photo_url" class="img-fluid"  alt="Incharge person photo" />
							</div>							
							<span class="scout-btn">スカウト</span>
                            <div class="d-block text-center mt-2" v-if="scout.r_record_status == 3"> 
                                <button class="show-status text-danger" style="width:100%" disabled="disabled">退会</button>
                            </div>
						</div>
						<div class="txt-box">
						<div class="pc-480">
                        <h6 class="scouted-tit mb-3">
                            <strong class="txt-vertical-ellipsis flex-kara">
                                {{scout.recruiter_name}}
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
							</strong><span class="text-label-color scout-txt" style="font-size:15px;">からのスカウト</span>
                        </h6>
						<label style="color:#0062ff;font-weight:bold;line-height:1.2;">画面下の求人詳細より以下の条件でオファーいたします</label>
						</div>                        
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
                                <td class="tbl-col-data scout-date"><span>{{ scout.user_scouted_date }}</span></td>
                            </tr>
                        </table>
							<!--<h5 class="scouted-tit">{{ scout.recruiter_name }}からのスカウト</h5>
							<p class="scouted-txt"><span class="`scouted-span label-txt">職種</span>{{ scout.occupation_description }}</p>
							<p class="scouted-txt"><span class="scouted-span label-txt">勤務地</span>{{ scout.job_location }}</p>
							<p class="scouted-txt"><span class="scouted-span label-txt">雇用形態</span>{{ scout.employment_types }}</p>
							<p class="scouted-txt"><span class="scouted-span label-txt">スカウト日時</span>{{ scout.scouted_date }}</p>-->
						</div>
						<div class="btn-gp w-auto ml-auto align-self-start">
							<button type="button" class="btn interest-btn btn-m border-style" @click="makeFavourite" v-if="allowFavourite(scout.scout_status)"><i class="fa fa-bookmark pr-1"></i>興味あり</button>
							<button type="button" class="btn chat-btn btn-m border-style" v-if="allowChat(scout.scout_status)" @click="startChat(scout)"><i class="fa fa-comment pr-1"></i>チャット開始</button>
							<button type="button" class="btn decline-btn decline-color btn-m border-style" @click="declineScout" v-if="allowFavourite(scout.scout_status) || allowChat(scout.scout_status)"><i class="fa fa-thumbs-down pr-1"></i>辞退</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Job detail shared component -->
			<DetailsInfo :job-id="scout.job_id"></DetailsInfo>
		</div>
		<div v-else>
			<p class="text-danger py-3">You have declined the scout.</p>
		</div>
  </div>
</template>
<script>
import DetailsInfo from '../public/DetailsInfo';

export default {	
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
    data() {
        return {
           scout: {},        
        }
	},
	components: { DetailsInfo },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
		}
    },
    methods: {
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
		allowFavourite(status) {
			return status == this.$configs.scouts.waiting;
		},
		allowChat(status) {
			return status == this.$configs.scouts.interested || 
				status == this.$configs.scouts.unclaimed ||
				status == this.$configs.scouts.payment_confirmed ||
				status == this.$configs.scouts.billed;
		},
		makeFavourite() {
			let loading = this.$loading.show();
			this.$api.post('/jobseeker/scouts/make-action', {
				scoutId: this.scout.id, action: 'favourite'
			})
			.then(() => {
				loading.hide();
				this.scout.scout_status = this.$configs.scouts.interested;
				
				// --Fire an event to refresh chattable list
				this.$emit('refreshChattables');
			})
			.catch(err => {
                loading.hide();
                console.log(err);
				// this.$alertService.showErrorDialog(null, "エラーが発生しました。");
			})
		},
		declineScout() {
			let loading = this.$loading.show();
			this.$alertService.showConfirmDialog(null,this.$t('alertMessage.decline'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                if(dialogResult.value){
					this.$api.post('/jobseeker/scouts/make-action', {
						scoutId: this.scout.id, action: 'decline'
					})
					.then(() => {
						loading.hide();
						this.scout.scout_status = this.$configs.scouts.declined;
						// --Job detail is no longer available after declined the scout
						this.$router.push({ 'name': 'JobseekerScoutedList' });
					})
					.catch(err => {
						loading.hide();
                        // this.$alertService.showErrorDialog(null, "エラーが発生しました。");
                        console.log(err);
					})
				}
			})
		}
	},
	mounted() {
		let scoutId = this.$route.params.scoutId;
		let loading = this.$loading.show({ container: this.$refs.progressContainer, isFullPage: false });
		this.$api.get(`/jobseeker/scouts/${scoutId}`)
		.then(r => {			
			loading.hide();
			this.scout = r.data.data;
		})
		.catch(() => {
			loading.hide();
            // alert("エラーが発生しました。")
		})
	}
};
</script>
<style lang="scss" scoped>
.tbl-col-tit {
    width: 20%;
    min-width: 150px;
    font-size: 15px;
    vertical-align: top;
}
.scouted-row {
    margin-bottom: 20px;
    padding: 25px;
    background: #D3E2EE !important;
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
.scouted-col {
    padding: 30px;
    background: #fff;
}
.scouted-img {		
    width: 125px;
	height: 125px;
	padding: .25rem;
	background-color: #fff;
	border: 1px solid #dee2e6;
	box-shadow: none;
	cursor: default;
	img{
		width: 100%;
		height: 100%;		
		object-fit: cover;
		transition: all 250ms;
		object-position: top;
	}
}
.scouted-tit {
	display: flex;
    align-items: center;
	font-size: 1rem; 
	strong{
		line-height: 1.4;
	}
} 
.scouted-tit .txt-vertical-ellipsis {
	-webkit-line-clamp: 2;
}
.scouted-txt {
    line-height: .8;
}
.scouted-span  {
	display: inline-block;
    min-width: 150px;
}
.scout-btn {
    width: 125px;
    margin-top: 10px;
    background: #86A4AC;
    color: #fff;
    display: block;
    padding: 5px;
    border-radius: 5px;
    text-align: center;
    cursor: default;
}
.btn-gp{
	display: flex;
    flex-direction: column;
}
.interest-btn {
    background: #EF8B1E ;
}
.chat-btn {
    background: #39AD4A;
}
.decline-btn {
    margin-top: 10px;    
}
.scout-txt{
	flex: 0 0 120px;
}
.flex-kara{   
    line-height: 1.4;
    font-size: 16px;
}
.txt-vertical-ellipsis {
	line-height: 2;
	word-break:break-all;
}
.box-content{
	display:flex;
}
.txt-box{	
    padding: 0 3rem;
	width: 100%;
}
.tbl-col-data{
	width:100%;
	font-size: 14px;
	line-height: 1.4;
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
@media screen and (max-width:1024px){
	.scouted-col{
		padding: 30px 20px;
	}	
}
@media (max-width: 820px) { 
	.txt-box {
		padding: 0 1rem;
	}
	.tbl-col-tit {
		width: 120px;
	}
	.scouted-row {
		padding: 15px;
	}
	.scouted-col {
		padding: 12px;
	}

}
@media (max-width: 768px) { 
	.btn-gp .btn {
		width: 160px;
	}
}
@media (max-width:767px){
	.tbl-col-data span{
		padding-left: 10px;
	}
}
@media (max-width: 680px) {
	.scouted-tit {
		margin-bottom: 10px !important;
	}
	.profile-tbl {
		line-height: 1.5;
	}
	.profile-tbl tr {
		border-bottom: 3px solid transparent;
	}
	.tbl-col-tit {
		display: block;
		width: 100%;
	} 	
	.btn-gp .btn {
		width: 140px;
	}
	.scout-date{
		display:block;
		word-break: break-all;  
	}
	.txt-vertical-ellipsis {
		-webkit-line-clamp: 1;
	}
}
@media (max-width: 480px) { 
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
		width: 41%;
	}
	.txt-box {
		width: 59%;
		padding: 0 0 0 .5rem;
	}
	.scouted-tit {
		flex-wrap: wrap;
	}
	// .txt-vertical-ellipsis {
	// 	-webkit-line-clamp: 1;
	// }	
	.btn-gp {
		flex-direction: row;
		margin: 0 auto;
		margin-top:10px;
	}
	.btn-gp .btn {
		margin: 0 3px;
	}
}
@media (max-width: 380px) { 
	.img-col {
		width: 44%;
	}
	.txt-box {
		width: 56%;
	}
	.btn-gp .btn {
		width: 120px;
		font-size: 12px;
	}
	.btn-gp{
		display: flex;
		flex-direction: row;
		margin: 0 auto;	
		margin-top:10px;
		.decline-btn{
			margin-top:0px;
			margin-left:5px;
		}
	}
}
@media (max-width: 320px) {	
	.tbl-col-tit{
		display:contents;
	}
	.scouted-img{
		width: 100px;
		height: 100px;		
	}
	.img-col {
		width: 41%;
	}	
	.scout-btn {
		width: 100px;
	}	
	.scout-date{
		font-size: 12px;
	}
	.txt-box {
		width: 59%;
		font-size: 12px;
	}
	.btn-gp .btn{
		width: 110px;
		font-size: 12px;	
	}
}
</style>