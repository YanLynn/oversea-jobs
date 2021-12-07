<template>
<div :class="$route.name == 'PublicScoutFavourite'?'full-width' : 'user-main'">
        <div class="searchform-one">
            <div class="job-wrapper m-0">
                    <h4 class="form-group row col-12 header">{{ $t('scoutfavourite.title')}}</h4>                            
                    <div class="form-group row">                       
                        <div class="col-12 col-sm-9 col-lg-6 mb-2-767">                           
                            <input type="search" :placeholder="[[ $t('scoutfavourite.placeholder')]]" class="form-control" @input="chStatus()" v-model="fav.keyword">
                            <!-- <i data-v-66b15f9f="" aria-hidden="true" class="fa fa-times search-ico" style="position: absolute;right: 30px;top: 15px;"></i> -->
                        </div>     
                        <div class="col-3 align-self-center">
                            <button class="btn border-style recruiter-primary-color btn-m"  @click="filterFav('filter')">{{ $t('common.search')}}</button>
                        </div>                 
                    </div>      
                    <div class="row">
                        <jobSeeker-card :data="data" v-for="data in favItemList" :key="data.id" id="recruiter-col-4">
                            <div slot="jobSeekerCardButton" class="text-right">
                                <button class="btn recruiter-primary-color btn-action border-style btn-m" @click="navigateJobseekerProfile(data.id)">{{ $t('scoutfavourite.profile')}}</button>  
                                <button class="btn cancel-color ml-2 border-style btn-m" @click="removeFav(data.id)">{{ $t('common.delete')}}</button>
                            </div>
                        </jobSeeker-card>
                        <div class="col-12" v-if="(!favItemList.length && status == 'filter') || (!favItemList.length && status == 'delete' && fav.keyword != '')" >
                            <p class="new-job-block no-data-txt recruiter-primary-color-txt" >
                                検索条件に合致するデータがみつかりません
                            </p> 
                        </div>         
                        <div class="col-12" v-if="(!favItemList.length && status == 'delete' && fav.keyword == '')" >
                            <p class="new-job-block no-data-txt recruiter-primary-color-txt" >
                                データがありません
                            </p> 
                        </div>         
                    </div>
            </div>
        </div>  
        <!-- empty state and infinite loading-->       
       
        <infinite-loading @distance="1" @infinite="infiniteHandler" spinner="waveDots" v-if="status == 'scroll'" >
            <div slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">データがありません</div>
            <div slot="no-more" class=""></div>
            <div slot="error" class="no-data-txt-border recruiter-primary-color-txt">エラーが発生しました</div>
        </infinite-loading> 
      
        <!-- <div v-if="favItemList.length == 0 && status == 'delete'" slot="no-results" class="no-data-txt-border recruiter-primary-color-txt">データがありません</div>  -->
       
       
        <!-- end empty state and infinite loading-->    
</div>	
	
</template>

<script> 
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    }, 
    data(){
        return{
            favItemList:[],
			page:1,
            fav:{
                keyword:''
            },
            // loader: '',
            status:'scroll',
            routeCheck: this.$route.name == 'PublicScoutFavourite' ? 'PublicRecruiterScoutEntry' : 'RecruiterScoutEntry',         
        }
    },
    created(){
        this.$store.dispatch('recFavCount')
        // this.loader = this.$loading.show();
        // this.$alertService.showInfoDialog(null,this.$t('alertMessage.fav_seeker_check'),this.$t('alertMessage.yes'))
    },
    methods:{ 
        infiniteHandler($state){  
            
            let fav = JSON.stringify(this.fav);
            this.$api.get(`recruiter/fav-list/`+this.status+`?page=${this.page}`,{ params: {fav : fav} }).then(res => {
                // this.loader.hide();
                let data = res.data.getJobseekerList;
                let language = res.data.language;
                let occupation = res.data.occupation;
                let message = res.data.fav_jobseeker_message;
                if(message != ''){
                 
                    this.$store.dispatch('recFavCount')
                    this.$alertService.showInfoDialog(null,this.$t('alertMessage.fav_seeker_check'),this.$t('common.close'))
                }
				if (data.data.length > 0) {
					this.page += 1;
                    this.favItemList.push(...data.data);
                    this.addOccupAndLang(language,occupation);
					
					$state.loaded();
				} else {
					$state.complete();
				}
            })
        },
        addOccupAndLang(lang,occupation){
             this.favItemList.map(e=>{
                    this.lang = [];
                    lang.filter(lan=>{
                        if(e.id === lan.id){
                            if(lan.language_level){
                                var split_arr = lan.language_level.split(',');
                                this.lang = split_arr;
                            }
                        }
                    });

                    this.occupation = [];
                    occupation.filter(occ=>{
                       if(e.id === occ.id){
                           if(occ.occupation_name){
                                var split_arr = occ.occupation_name.split(',');
                                this.occupation = split_arr ;

                           }
                       }
                   })
                e.language_level = this.lang;
                e.desired_occupation = this.occupation;
                // this.loader.hide();
            })

        },
        chStatus(){
            if(this.fav.keyword == ''){
                this.status = 'filter';
                this.filterFav(this.status);
            }
        },
		filterFav(status){
            // this.loader = this.$loading.show();
            let fav = JSON.stringify(this.fav);
            this.status = status;
           
			if(status == 'delete')
			{
				this.$api.get(`recruiter/fav-list/delete`+`?page=${this.page}`,{ params: {fav : fav} }).then(res => {
                let lang = res.data.language;
                    let occupation = res.data.occupation;
                    this.favItemList = res.data.getJobseekerList
                    this.addOccupAndLang(lang,occupation);
                    // this.loader.hide();
					if(status == 'delete')
					{
						this.page += 1;
                    }
                   
				})

			}else{
                
				this.$api.get(`recruiter/fav-list/filter`,{ params: {fav : fav} }).then(res => {
                    // this.loader.hide();
                    let lang = res.data.language;
                    let occupation = res.data.occupation;
                    this.favItemList = res.data.getJobseekerList
                    this.addOccupAndLang(lang,occupation);
				})
			}			
		},
        removeFav(id){	
            
            this.$alertService.showConfirmDialog(null,this.$t('alertMessage.delete_confirm_message'),this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
				if(dialogResult.value){
                    // this.loader = this.$loading.show();
					 this.$api.get(`recruiter/fav-list/${id}/delete`).then(res => {		
                    //    this.loader.hide();
                        this.page -= 1;
                        this.filterFav('delete');
                        
                        this.$store.dispatch('recFavCount')
                    })
				}
			})		
           
        },
        navigateJobseekerProfile(id) {
            this.$router.push({ name: this.routeCheck, params: { jobseekerId: id }});
        },
    }
}
</script>
<style lang="scss" scoped>
#recruiter-col-4{
    -ms-flex: 0 0 50% !important;
    flex: 0 0 50% !important;
    max-width: 50% !important;
    .col-profile{
        width: 200px !important;
    }
}

.job-wrapper{
    min-height: 0px;
}
.btn-action {
    width : 48%;
    padding: 8px 0;
}
.full-width {
    width: 100%;
    padding: 0px 15px;
}
.table{
margin-bottom: -3px;
}
@media (max-width:1024px){
    .user-main #recruiter-col-4{
        -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}
@media screen and (max-width: 767px){
.mb-2-767 {
    margin-bottom: 1rem !important;
}
}
</style>
