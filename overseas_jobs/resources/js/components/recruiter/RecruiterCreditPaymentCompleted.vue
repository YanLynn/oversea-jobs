<template>
    <div class="login-card-body successfuldiv" v-if="showCard">   
    <!-- successful-->    
    <div class="successful-block">
        <a href="/" class="no-href">
        <!-- <span class="icon icon-logo logo recruiter-primary-color-txt"></span> -->
        <span class="icon-new-logo-txt logo-img payment-form-logo recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </a>    
        <div class="successful-wrapper" v-if="p_data != null && p_data != 'error'">           
            <h6 class="text-center" v-if="$route.query.result == 1">クレジットカードでの支払いが完了しました</h6>   
            <h6 class="text-center" v-else>クレジットカードでの支払いがエラーとなりました</h6>   
            <dl class="detail-list">
                <dt class="detail-head-group mt-4 text-label-color">管理番号</dt>
                <dd class="detail-data-group mt-4">{{p_data.management_number}}</dd>
            </dl>
            <dl class="detail-list">
                <dt class="detail-head-group text-label-color">求人タイトル </dt>
                <dd class="detail-data-group">{{p_data.title}}</dd>
            </dl>
                <dl class="detail-list">
                <dt class="detail-head-group text-label-color">求人者氏名</dt>
                <dd class="detail-data-group">{{p_data.recruiter_name}}</dd>
            </dl>
                <dl class="detail-list">
                <dt class="detail-head-group text-label-color">請求金額</dt>
                <dd class="detail-data-group">{{Number(p_data.invoice_amount).toLocaleString()}} 円</dd>
            </dl>
        </div>
        <div class="successful-wrapper" v-if="p_data == 'error'">
            <h6 class="text-center">クレジットカードでの支払いがエラーとなりました</h6>   
        </div>
        <div class="text-center">                 
            <!-- <button class="btn searchbtn cancel-color">閉じる</button> -->
            <a href="/" class="recruiter-primary-color btn searchbtn ml-0"> トップページへ</a>
        </div>               
        
    </div>       
    <!--end successful-->
    </div>    
</template>
<script>
export default {
    data(){
        return {
            p_data: null,
            showCard: false,
        }
    },
    created(){
        this.loading = this.$loading.show({
            color: "#0062ff",
            loader: process.env.MIX_LOADING_INDICATOR ?? "dots",
            backgroundColor: "#eee",
            width: 64,
            height: 64,
            opacity: 1,
            zIndex: 1000,            
        });
        console.log(this.$route.query.order_number)
        console.log(this.$route.query.result)
        console.log(this.$route.query.trans_code)
        console.log(this.$route.query.user_id)
        let payment_info = {
            order_number: this.$route.query.order_number,
            user_id: this.$route.query.user_id,
        }
        if(this.$route.query.result == 1) {
            this.$api.post('recruiter/payment-complete', payment_info).then(res => {                
                this.p_data = res.data.data[0];
                this.loading.hide();
                this.showCard = true;
            }).catch(error => {
                this.p_data = "error";
                this.loading.hide();
                this.showCard = true;
            });
        }
        else{
            this.p_data = "error";
            this.loading.hide();
            this.showCard = true;
        }
        
    },
}
</script>
<style lang="scss" scoped>
.successful-block{
   width: 450px;    
}
@media only screen and (max-device-width: 820px) and (orientation: landscape){
    .login-card-body {
        height: auto;
        padding: 50px 0;
    }
}
@media screen and (max-width:380px){    
    .successful-wrapper{
        padding: 15px;
    }
    .detail-head-group{
        width: 40% !important;
    }
    .detail-data-group{
        width: 60% !important;
    }
}
</style>