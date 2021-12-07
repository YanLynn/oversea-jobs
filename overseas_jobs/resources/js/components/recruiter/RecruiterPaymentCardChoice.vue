<template>
    <div class="login-card-body">   
    <!-- successful-->
        <div v-if="showCard" class="successfuldiv">
             <div class="successful-block">
                <a href="/" class="no-href" >               
                <span class="icon-new-logo-txt logo-img payment-form-logo recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
                </a>  
                <div class="successful-wrapper text-center" v-if="errorMsg!=null"> 
                    <p class="error" style="text-align:center;">{{errorMsg}}</p> 
                </div> 
                <div class="successful-wrapper text-center" v-if="errorMsg==null"> 
                    <dl class="detail-list">
                        <dt class="detail-head-group mt-4 text-label-color">管理番号</dt>
                        <dd class="detail-data-group mt-4">{{tran_info["management_number"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">求人タイトル</dt>
                        <dd class="detail-data-group">{{tran_info["title"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">求人者氏名</dt>
                        <dd class="detail-data-group">{{tran_info["recruiter_name"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">請求金額</dt>
                        <dd class="detail-data-group">{{Number(tran_info["invoice_amount"]).toLocaleString()}} 円</dd>
                    </dl>

                    <p class="text-label-color" style="margin-top:25px;">支払いに用いるクレジットカード</p>
                    <div class="text-left">
                        <p class="custom-radio-group" style="padding-left:2rem;">
                            <input type="radio" id="japan" v-model="card_choice" value="old" name="radio-group" checked class="custion-radio" >
                            <label for="japan" class="custom-radio-lable text-label-color" v-if="mask_card != ''">{{mask_card}}</label>
                        </p>
                        <p class="custom-radio-group" style="padding-left:2rem;">
                            <input type="radio" id="overseas" v-model="card_choice" value="new" name="radio-group" class="custion-radio">
                            <label for="overseas" class="custom-radio-lable text-label-color">他のクレジットカードを使用</label>
                        </p>
                    </div>

                </div>
            
                <!-- <div class="row data-block" v-if="errorMsg==null">
                    <div class="col-12">
                        <p class=" text-label-color">支払いに用いるクレジットカード</p>
                        <div class="col-8 offset-2 text-left">
                            <p class="custom-radio-group" style="padding-left:2rem;">
                                <input type="radio" id="japan" v-model="card_choice" value="old" name="radio-group" checked class="custion-radio" >
                                <label for="japan" class="custom-radio-lable text-label-color" v-if="mask_card != ''">{{mask_card}}</label>
                            </p>
                            <p class="custom-radio-group" style="padding-left:2rem;">
                                <input type="radio" id="overseas" v-model="card_choice" value="new" name="radio-group" class="custion-radio">
                                <label for="overseas" class="custom-radio-lable text-label-color">他のクレジットカードを使用</label>
                            </p>
                        </div>
                    </div>                          
                </div>  -->

                <div class="text-center" v-if="errorMsg==null">
                    <button class="btn searchbtn delete-color" @click="confirmChoice(card_choice)">確認</button>
                    <a href="/" ><span class="btn mr-2 searchbtncancel-color">閉じる</span></a>  
                </div>               
                <div class="text-center" v-if="errorMsg!=null">
                    <a href="/" ><span class="btn ml-0 searchbtn cancel-color">トップページへ</span></a>  
                </div>               
               
            </div>
        </div>
    <!--end successful-->
    </div>    
</template>
<script>
import {mapGetters} from 'vuex';
export default {
    data(){
        return {
            card_choice: 'old',
            card_info: [],
            tran_info: [],
            token_id: null,
            key: null,
            mask_card: '',
            errorMsg: null,
            showCard: false,
            loading: null,
        }
    },
    computed: {
        ...mapGetters(["currentUser","authError"]),    
    },
    created() {
        this.loading = this.$loading.show({
            color: "#0062ff",
            loader: process.env.MIX_LOADING_INDICATOR ?? "dots",
            backgroundColor: "#eee",
            width: 64,
            height: 64,
            opacity: 1,
            zIndex: 1000,  
            
        });
        if(this.currentUser != null){
            if(this.currentUser.role_id == 3){
                this.$store.commit('logout');
            }
        }   

        this.$nextTick(() => {
            this.token_id = this.$route.query.tokenid;
            this.type = this.$route.query.type;
            this.key = this.$route.query.key;    

            this.$api.get('recruiter/card-info/'+ 0, {params : {'tokenid': this.token_id, 'key': this.key}}).then((r) => {
                console.log("line118",r)
                this.card_info = r.data.card_info;
                this.card_info.transaction_id = Number(this.card_info.transaction_id);

                // No need to get card info
                // if(this.card_info.result == 1) {
                //     this.mask_card = this.card_info.card_number_mask;                                     
                // } 

                // else if(this.card_info.result == 2 && (this.card_info.xml_error_cd == '801' || this.card_info.xml_error_cd == 801)){
                //     loading.hide();
                // } 

                this.$api.get(`/recruiter/transaction-list/${this.card_info.transaction_id}/${this.type}/transaction-info`)
                .then((r) => {  
                    console.log("line133 tran info",r)                                     
                    this.tran_info = r.data.info; 
                    this.confirmChoice('new');
                    // if(this.card_info.result != 1) {
                    //     this.confirmChoice('new');
                    // }  
                    // else{
                    //     loading.hide();
                    //     this.showCard = true;
                    // }                    
                })  
                .catch(function (err) {
                    this.loading.hide();
                    this.showCard = true;
                    console.log("Error",err)
                    error('Error ' + err);
                }); 
                // else{
                //     loading.hide();
                //     // this.errorMsg = "このリンクは無効です。";
                //     // if(this.card_info.xml_error_cd == '604' || this.card_info.xml_error_cd == 604){
                //     //     this.errorMsg = "このリンクは無効です。";
                //     // }
                //     // else{
                //     //     this.errorMsg = this.card_info.xml_error_msg;
                //     // }  
                // }
            })
            .catch(function (err) {

            })
        });          

    },
    methods: {        
        confirmChoice(ch) {
            // let loader = this.$loading.show({
            //     color: "#0062ff",
            //     loader: process.env.MIX_LOADING_INDICATOR ?? "dots",
            //     backgroundColor: "#eee",
            //     width: 64,
            //     height: 64,
            //     opacity: 1,
            //     zIndex: 1000,
                
            // });
            this.tran_info.choice = ch;
            this.$api.post(`/recruiter/transaction-list/credit-payment`, this.tran_info)
            .then((res) => {
                console.log("line 182", res)
                if(res.data.status == 'success'){
                    window.location.href = res.data.link;
                    // if(this.card_choice == 'old'){
                    //     window.location.href = res.data.link;
                    // }
                    // else{
                    //     window.open(res.data.link, '_blank');
                    // }
                }
                else{
                    this.loading.hide();
                    this.showCard = true;
                    // this.errorMsg = res.data.msg;
                    if(res.data.error_code == '604' || res.data.error_code == 604){
                        this.errorMsg = "すでに決済済みです。";
                        // this.errorMsg = "このリンクは無効です。";
                    }
                    else{
                        this.errorMsg = res.data.msg;
                    }                    
                }
                
            })

        }
    }
}
</script>
<style lang="scss">
.successfuldiv{
    margin: 0 auto;
    display: flex;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    padding: .625em;
    overflow-x: hidden;
}
.successful-block{
    width: 450px; 
    margin: 0 auto;   
}
.payment-form-logo{
    margin: 0!important;
}
.successful-wrapper{
    width: 100%;
    margin: 0 auto;
    text-align: left;
    padding: 15px 30px 30px 30px;
    .detail-list{
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 0px;
        .detail-head-group, .detail-data-group{
            padding: 0px;
            border: none;
        }
        .detail-head-group{
            float:left;
            width: 30%;
            margin: 0px
        }
        .detail-data-group{
            float: left;
            width: 70%;
            background-color: #fff;
        }
    }
}
.pl-5r{
    padding-left: 5rem;
}
@media only screen and (max-device-width: 820px) and (orientation: landscape){
    .successfuldiv{
        position: relative;
    }
}
@media screen and (max-width:480px){
    .successful-block{
        width: 100%;
    }   
} 
</style>