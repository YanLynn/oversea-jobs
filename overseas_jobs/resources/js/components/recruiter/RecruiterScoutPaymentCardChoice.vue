<template>
    <div class="login-card-body">   
    <!-- successful-->
    <div class="row col-12 text-center">        
        <div class="col-md-4 offset-md-4">
             <div class="successful-block">
                <a href="/" ><span class="icon icon-logo logo recruiter-primary-color-txt"></span></a>  
                <div class="successful-wrapper" v-if="errorMsg!=null"> 
                    <p class="error" style="text-align:center;">{{errorMsg}}</p> 
                </div> 
                <div class="successful-wrapper" v-if="errorMsg==null">    
                            
                    <dl class="detail-list">
                        <dt class="detail-head-group mt-4 text-label-color">管理番号</dt>
                        <dd class="detail-data-group mt-4">{{scout_info["management_number"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">求人タイトル</dt>
                        <dd class="detail-data-group">{{scout_info["title"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">求人者氏名</dt>
                        <dd class="detail-data-group">{{scout_info["recruiter_name"]}}</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group text-label-color">請求金額</dt>
                        <dd class="detail-data-group">{{Number(scout_info["invoice_amount"]).toLocaleString()}} 円</dd>
                    </dl>
                </div>
            
                <div class="row data-block" v-if="errorMsg==null">
                    <div class="col-12">
                        <p class=" text-label-color">支払いに用いるクレジットカード</p>
                        <div class="col-8 offset-2 text-left">
                        <p class="custom-radio-group pl-5r">
                            <input type="radio" id="japan" v-model="card_choice" value="old" name="radio-group" checked class="custion-radio" >
                            <label for="japan" class="custom-radio-lable text-label-color" v-if="mask_card != ''">{{mask_card}}</label>
                        </p>
                        <p class="custom-radio-group pl-5r">
                            <input type="radio" id="overseas" v-model="card_choice" value="new" name="radio-group" class="custion-radio">
                            <label for="overseas" class="custom-radio-lable text-label-color">他のクレジットカードを使用</label>
                        </p>
                        </div>
                    </div>                          
                </div> 
                <div class="text-center" v-if="errorMsg==null">
                    <button class="btn searchbtn delete-color" @click="confirmChoice()">確認</button>
                    <a href="/" ><span class="btn searchbtn cancel-color">キャンセル</span></a>  
                </div>               
                <div class="text-center" v-if="errorMsg!=null">
                    <a href="/" ><span class="btn searchbtn cancel-color">ホームページ</span></a>  
                </div>               
               
            </div>
        </div>
    </div>
    <!--end successful-->
    </div>    
</template>
<script>
export default {
    data(){
        return {
            card_choice: 'old',
            card_info: [],
            scout_info: [],
            token_id: null,
            scout_key: null,
            mask_card: '',
            errorMsg: null,
        }
    },
    created() {
        let loading = this.$loading.show();
        this.token_id = this.$route.query.tokenid;
        this.scout_key = this.$route.query.scoutkey;    
        console.log("sckey",this.scout_key)    

        this.$api.get('recruiter/card-info/'+ 0, {params : {'tokenid': this.token_id, 'scoutkey': this.scout_key}}).then((r) => {
            console.log("cardinfo rr",r.data.card_info)
            this.card_info = r.data.card_info;
            console.log("card info", this.card_info);
            if(this.card_info.result == 1) {
                this.card_info.scout_id = Number(this.card_info.scout_id);
                this.$api.get(`/recruiter/scouted-list/${this.card_info.scout_id}/scout-info`)
                .then((r) => {
                    loading.hide();
                    this.scout_info = r.data.info[0];
                    this.mask_card = this.card_info.card_number_mask;
                })
            }  
        })

    },
    methods: {        
        confirmChoice() {
            let loader = this.$loading.show();
            this.scout_info.choice = this.card_choice;
            this.$api.post(`/recruiter/scouted-list/${this.card_info.scout_id}/credit-payment`, this.scout_info)
            .then((res) => {
                console.log(res.data)
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
                    loader.hide();
                    this.errorMsg = res.data.msg;
                }
                
            })

        }
    }
}
</script>
<style lang="scss">
.successful-wrapper{
    width: 100%;
    margin: 0 auto;
    text-align: left;
    padding: 30px 50px;
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
            width: 25%;
            margin: 0px
        }
        .detail-data-group{
            float: left;
            width: 75%;
            background-color: #fff;
        }
    }
}
.pl-5r{
    padding-left: 5rem;
}
</style>