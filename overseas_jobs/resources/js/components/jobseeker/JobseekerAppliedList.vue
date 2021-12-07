<template>
   <div class="user-main">     
        <div class="row job-wrapper m-0">
           <h2 class="col-12 underline header pb-2  pl-0">問合わせ/応募中求人 
               <!-- <span class="badge badge-pill badge-dark ml-3">{{cardData.length}}&nbsp;件</span> -->
            </h2> 
          <div class="w-100">
            <div class="row">
            <job-card class="col-12 col-lg-6 outer-wrapper-bottom" :data="data"  v-for="(data,index) in cardData" :key="index"> 
                <div v-if="data.job_apply_status == $configs.job_apply.under_review" class="col-12 text-right p-0 buttons-block">
                    <router-link   :to="{ path: '/jobs/'+ data.job_id +'/job-details'}" class="btn my-1 job-primary-color border-style">
                        求人詳細
                    </router-link>
                    <button class="btn my-1 border-style decline-color ml-2" @click="changeStatus(data.job_apply_id)"> 
                        {{$configs.job_apply.jobseeker_under_review}} 
                    </button>
                    <button class="btn my-1 rounded-pill start-color ml-2" @click="startChat(data)"> チャット開始 </button>
                </div>
                <div v-else-if="data.job_apply_status == $configs.job_apply.declined" class="col-12 text-right p-0 buttons-block">
                    <span class="show-status ml-2 my-1">{{data.job_apply_status}}</span>
                </div> 
                <div v-else-if="data.job_apply_status == $configs.job_apply.payment_confirmed || data.job_apply_status == $configs.job_apply.unclaimed || data.job_apply_status == $configs.job_apply.billed" class="row m-0 d-flex">
                   <div class="col-12 d-flex p-0">
                    <div class="col-8 buttons-block d-flex p-0">
                        <router-link :to="{ path: '/jobs/'+ data.job_id +'/job-details'}" class="btn my-1 job-primary-color border-style">
                            求人詳細
                        </router-link>
                        <button class="btn my-1 rounded-pill start-color ml-2" @click="startChat(data)"> チャット開始 </button>
                    </div>
                    <div class="col-4 text-right buttons-block p-0">
                        <span class="show-status  ml-2 my-1">内定</span>
                    </div>    
                   </div>                
                </div> 
                <!-- <div v-if="data.job_apply_status == $configs.job_apply.payment_confirmed" class="row m-0 d-flex">
                 <div class="col-12 d-flex p-0">
                    <div class="col-6 buttons-block d-flex p-0">
                        <router-link :to="{ path: '/jobs/'+ data.job_id +'/job-details'}" class="btn my-1 job-primary-color border-style">
                            求人詳細
                        </router-link>
                    </div>
                    <div class="col-6 text-right buttons-block p-0">
                        <span class="show-status ml-2 my-1">内定</span>
                    </div> 
                 </div>                   
                </div>  -->
            </job-card>
            </div>
             <!-- empty data -->
             
            <div v-if="cardData == 0" class="job-wrapper row col-12 p-0 m-0">
                <p class="no-data-txt col-12">
                  問合わせ/応募中求人はありません
                </p>
            </div>
          </div>          		            
        </div>
        <infinite-loading @distance="1" @infinite="infiniteHandler" spinner="waveDots">
            <div slot="no-results" class="py-3 text-danger">&nbsp;</div>
            <div slot="no-more" class=""></div>
            <div slot="error" class="py-3 text-danger">エラーが発生しました</div>
        </infinite-loading>

    </div>
</template>
<script>

export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
props: ['data'],
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
},
    data(){

        return {
			cardData:[],
			page:1,
        }
    },
    methods:{
        infiniteHandler($state){
            this.$api.get(`jobseeker/applied-list?page=${this.page}`).then(res => {
                let newData = res.data.data				
				if (newData.length > 0) {
					this.page += 1;
                    this.cardData.push(...newData);
					$state.loaded();
				} else {
					$state.complete();
				}
            })
        },
        changeStatus(applyId)
        {
			this.$alertService.showConfirmDialog(null,this.$t('alertMessage.decline'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
				if(dialogResult.value){
                    let loading = this.$loading.show();
					if(this.page != 1)
						{
							this.page = this.page -1 ;
						} 
					 		this.$api.get(`jobseeker/apply-changes-status/${applyId}/status?page=${this.page}`).then(res => {
							this.cardData = res.data
                            this.page += 1;
                            loading.hide();
						})
				}
			})

			
		},
		startChat(jobapply) {
			const payload = {
				recruiter_id: jobapply.recruiter_id,
				jobseeker_id: jobapply.jobseeker_id,
				scoutid_or_applyid: jobapply.job_apply_id,
				type: 'job-apply',
                status: jobapply.job_apply_status,
			};
			this.$emit('chatStarted', payload);
		},
    },

  
};
</script>






<style scoped>
.list-wrap {
  background: #fff;
  padding: 50px 50px 100px 50px;
  border-radius: 5px 5px 0px 0px;
}
.list-group {
    max-width: 800px;
    
}
.list-group-item {
    padding: .75rem 0;
    border: none;
}
.post-description {
    width: 50%;
}
.post-description span {
    display: inline-block;
    margin: 0 2px 0 10px;
    font-size: 20px;
    color: #0071B4;
}
.count {
    background: #0062ff;
    border-radius: 10%;
    color: white;
    font-weight: bold;
    padding: 5px 10px;
    float: right;
}

</style>