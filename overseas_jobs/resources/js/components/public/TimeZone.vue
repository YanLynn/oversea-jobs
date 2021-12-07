  <template>
   <div class="user-main">
         <div>
            <h4 :class="['user-head-tit', role_id == 2 ? 'recruiter-primarylight-color': 'job-primary-color']" >タイムゾーン設定</h4>            
            <div class="content-wrap">             
                <div class="form-box">
                    <!-- <label class="label-txt">Time Zone</label> -->
                    <div class="form-in">
                        <div class="col-12 p-0">
                            <dl class="row col-12">
                                <dt class="label-txt pr-2">タイムゾーン</dt>
                                <dd>{{show_time_zone}}</dd>                               
                            </dl> 
                             <p style="color:#007bff;margin-bottom:10px;font-weight:bold;">チャット機能で表示される時刻が選択されたゾーンの時刻となります。</p>
                        </div>

                        <div class="row searchform-one m-0">
                            <div class="col-12 p-0">
                                <form class="form-signin" role="form"  @submit.prevent="insertTimezone">
                                    <div class="form-group col-12 col-lg-6 p-0 m-0">
                                        <div class="input-group">
                                        <v-select v-model="timezone"                                        
                                                :options="zoneList" 
                                                label="zoneList"
                                                class="form-control"
                                                :reduce="zoneList=>zoneList" 
                                                aria-autocomplete="on">
                                                <span slot="no-options" @click="$refs.select.open = false">
                                                検索条件に当てはまるデータはありません
                                                </span>
                                            </v-select> 
                                        </div>
                                         <span v-if="validated" class="error">タイムゾーンを選択してください</span>
                                        <div class="fomr-group m-t-30">
                                            <button @click="validate" :class="['btn border-style btn-m',role_id == 2 ? 'recruiter-primary-color' : 'job-primary-color']" type="submit">
                                            変更
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
            <div>
            </div>
        </div>         
    </div>
</template>
<script>
 import Vuelidate from "vuelidate";
import { required } from "vuelidate/lib/validators";
export default {
    metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	}, 
    data(){
        return {
            time_check: false,
            zoneList:[],
            timezone:'',
            role_id:'',
            show_time_zone: null,
            save_time_zone: null,
            get_List: null,
            response_user_data: null,
        }
    },
    created(){
        this.timeZoneInit();                  
    },
    methods:{
        timeZoneInit() {
            this.role_id = this.$store.getters.currentUser.role_id;
            this.$api.get('time-zone-list').then(res => {
                    this.get_List = res.data.get_timezone
                    this.get_List.map(zone => {
                            this.zoneList.push('('+zone.GMT_Offset+') ' + zone.Time_Zone);
                    })
                    this.zoneList.sort();
                    this.userDataUpdate();
            });
        },
        userDataUpdate() {            
            this.$api.get('user-time-zone').then(res => {
                if(res.data.user_timezone == '' || res.data.user_timezone == null || res.data.user_timezone.timezone == '' || res.data.user_timezone.timezone == null){
                    const get_Tokyo = this.get_List.find(e => e.Time_Zone == 'Asia/Tokyo');
                    this.timezone =  '('+get_Tokyo.GMT_Offset+') ' + get_Tokyo.Time_Zone;
                    this.save_time_zone =  get_Tokyo.Time_Zone;
                    this.show_time_zone = this.timezone;
                    
                }else{
                    const get_TimeZone = this.get_List.find(e => e.Time_Zone == res.data.user_timezone.timezone);
                    this.timezone =  '('+get_TimeZone.GMT_Offset+') ' + get_TimeZone.Time_Zone;
                    this.save_time_zone =  get_TimeZone.Time_Zone;                
                    this.show_time_zone = this.timezone;
                }                
            });
        },
        validate() {
        this.time_check = true
        },
        insertTimezone(){
            if(this.timezone != null) {
                var arr = this.timezone.split(') ');
                var offset = arr[0].split(' ');                
                this.save_time_zone = arr[1];
                this.$alertService.showConfirmDialog(null,'タイムゾーンを変更していいですか。',this.$t('alertMessage.yes'),this.$t('alertMessage.no')).then((dialogResult)=>{
                    if(dialogResult.value){
                        this.$api.post('time-zone',{timezone:this.save_time_zone, offset: offset[1]})
                        .then((res) => {
                            this.$alertService.showSuccessDialog(null, '変更しました。', this.$t('alertMessage.close'));
                            this.userDataUpdate();
                        })
                        .catch(err => {
                            
                        });
                    }
                    
                })
            }             
            
        }
    },
     computed: {
    validated() {
      return this.time_check && !this.timezone
    }
  },
}
</script>
<style lang="scss" scoped>
.form-box {
    border: 1px solid #C4C4C4;
    border-radius: 8px;
    padding: 22px;
}
@media screen and (max-width:767px){
	.content-wrap{
		padding: 15px;
    }
    .form-box{
        padding: 15px;
    }
    .searchform-one .form-control {
        border-radius: 0px !important;
        border: 1px solid #9b9898;
        font-size: 14px;       
    }     
    .btn-m {
        width: 124px;
        padding: 8px 5px;
    }   
}
@media screen and (max-width: 375px){
    .searchform-one .form-control {
        height: 63.6px !important;
    }
}
</style>