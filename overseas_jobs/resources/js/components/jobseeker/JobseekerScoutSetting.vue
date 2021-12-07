<template>
   <div class="user-main">     
        <div class="outer-wrapper-right job-wrapper m-0">
            <h4 class="form-group row col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 header">{{ $t('scoutsetting.scout_setting') }}</h4>    
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 inner-wrapper-right">
                    <div class="new-job-block">
                        <div class="row data-block">
                           <div class="col-12">
                                <p class="custom-radio-group">
                                    <input type="radio" name="radio-group" id="no_scout" @change="changeScout(scout_status)" value="0" v-model="scout_status" class="custion-radio">
                                    <label for="no_scout" class="custom-radio-lable">{{ $t('scoutsetting.no_scout') }}</label>
                                </p>
                                <p class="custom-radio-group">
                                    <input type="radio" name="radio-group" id="receive_company" @change="changeScout(scout_status)" value="1" v-model="scout_status" class="custion-radio">
                                    <label for="receive_company" class="custom-radio-lable">{{ $t('scoutsetting.receive_company') }}</label>
                                </p>
                               <!-- <p class="custom-radio-group" v-for="(scout, key) in scout_setting_status" :key="scout.id">
                                    <input type="radio" name="radio-group" :id="key" @change="changeScout(key)" :value="key" v-model="scout_status" class="custion-radio">
                                    <label :for="key" class="custom-radio-lable">{{ $t(scout) }}</label>
                                </p> -->
                           </div>                          
                        </div>
                        <div class="row">
                            <div class="col-12">
                            <div class="public-info-block">
                                <p>{{ $t('scoutsetting.profile_edit') }}<br>
                                    ※{{ $t('scoutsetting.mark') }}
                                </p>
                                <p>
                                    <router-link to="/jobseeker/profile-edit">{{ $t('scoutsetting.confirm') }}</router-link>
                                </p>
                            </div>
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
    data() {
        return {
            // scout_setting_status: {
            //      0:'scoutsetting.no_scout',
            //      1:'scoutsetting.receive_company'
            // },
            scout_status : null,
        }
    },
    created() {
        let user_data = {};
        let user_id = this.$store.getters.currentUser.id;
        this.$set(user_data, "id", user_id);
        this.$api.post('/jobseeker/info', user_data)
            .then(response => {
                console.log(response)
                this.scout_status = response.data.scout_setting_status;
            })
            .catch(errors => {
                console.log(errors);
            });
    },
    methods: {
        changeScout(key) {
            // this.scout_status = key;
            let scout_setting_data = {};
            this.$set(scout_setting_data, "scout_setting_status", key);
            this.$api.post('/jobseeker/scout-setting/update', scout_setting_data)
                .then(response => {
                    console.log(response.data);
                })
                .catch(errors => {
                    console.log(errors);
                });
        }
    }
}
</script>

<style  scoped>
.public-info-block{
    background-color: #eaeaea;
    border: 1px solid #9b9898;
    border-radius: 5px;
    padding: 20px;
}
.job-wrapper .new-job-block .data-block{
    height: auto;
}
</style>