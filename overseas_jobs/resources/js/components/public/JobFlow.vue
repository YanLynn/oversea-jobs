<template>
<div>
    <nav class="navbar navbar-expand-lg navbar-light user-nav">        
        <div class="navbar-header d-flex align-items-center logo-res">         
            <a class="no-href navbar-brand" href="/">
            <span class="icon-new-logo-txt menu-logo"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
        </a>         
        </div>  
        <div class="mx-auto order-0 pc">            
            <img :src="'/images/common/logo-txt.png'"  class="img-fluid p-l-200"  alt="ボーダレスワーキング" />           
        </div>  
    </nav>       
    <div class="container mb-80">
        <h1 class="page-tit">スカウトされるまでの流れ</h1>  
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">1</span></td>
                <td class="tbl-col-data text-center">
                    <p class="mb-4 text-left">まだ会員登録が済んでいない場合、以下のリンクから登録を行います。</p>
                    <router-link to="/jobseeker/register"  class="btn btn-large border-style no-href" >求職者会員新規登録</router-link>
                </td>
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">2</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left"><router-link to="/jobseeker/login" target="_blank">求職者会員管理画面</router-link>にログインし詳細なプロフィールの入力や</p>
                    <p class="text-left">履歴書・職務経歴書のアップロードを行います。</p>
                    <p class="text-left">企業からスカウトを受けたい場合はスカウトを受ける設定を有効にします。</p>                
                </td>
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">3</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left"><a href="/" target="_blank">トップ画面</a>より求人を検索し、求人に問合せ/応募します。</p>
                    <p class="text-left">この時点で企業会員とチャットで会話が可能となります。</p>
                    <p class="text-left">チャットにて求人について質問や面接日の設定等を行ってください。</p>     
                    <p class="text-left">チャット機能は求職者会員管理画面からのみ利用できます。</p>          
                    <p class="text-left">※チャット上での電話・メール・住所等連絡先の交換は禁止していますので、ご注意願います。</p>
                    <div class="screenshot-wrapper">                    
                        <img src="/images/jobseeker/for-chat.jpg" alt="背景" class="img-fluid">
                        <!--images here-->
                    </div>
                </td>
            </tr>      
        </table>

        <h4 class="sub-tit mb-4 mt-5">企業よりスカウトされた場合</h4>
        <h4 class="sub-tit">スカウトを受ける設定が有効になっている場合は企業会員からスカウトを受けることがあります。</h4>
        <h4 class="sub-tit mb-4">掲載されている求人への応募の手順１と２はスカウトの場合も必要です。それ以降の手順が異なります。</h4>

        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">3</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left"> あなたのプロフィールを見た企業会員からスカウトしたい旨のメールが届きます。</p>
                    <p class="text-left">スカウトを受ける場合、<router-link to="/jobseeker/login" target="_blank">求職者会員管理画面</router-link>にログイン後、スカウトを受けている求人のリスト上の「興味あり」ボタンをクリックします。</p>
                    <p class="text-left">この時点で企業会員とチャットで会話が可能となります。</p>    
                    <p class="text-left">チャットにてスカウトについて質問や面接日の設定等を行ってください。</p>
                    <p class="text-left">チャット機能は求職者会員管理画面からのみ利用できます。</p> 
                    <p class="text-left">※チャット上での電話・メール・住所等連絡先の交換は禁止していますので、ご注意願います。</p> 
                       <div class="screenshot-wrapper">
                        <img src="/images/jobseeker/scoutedlist.png" alt="スカウトを受けている求人" class="img-fluid">
                        <!--images here-->
                    </div>
                </td>
            </tr>      
        </table>
    </div>
</div>
</template>
<script>
export default { 
	metaInfo: {
		title: 'スカウトされるまでの流れ｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: 'スカウトされるまでの流れのページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
	},        
};
</script>
<style lang="scss" scoped>
.menu-logo{
    font-size:70px;
    color:#012463;
}
.user-nav{
    background: url(/images/common/before-login-bg.jpg) no-repeat center 0 !important;
    background-size: cover !important;
}
.border-border-box-row {
    margin-bottom: 1.5rem;
    border: 1px solid #818181;
    padding: 20px;
    display: inline-block;
    width: 100%;
    border-radius: 5px;
}
.page-tit {
    margin: 70px 0 50px 0;
    font-size: 28px;  
}
.sub-tit {    
    font-size: 21px;
}
.mb-80 {
    margin-bottom: 80px;
}
.profile-tbl{
    width: 100% !important;
}
.num{
    background: #4c76c2;
    padding: 10px 20px;
    color: #fff;
    border-radius: 5px;
    font-size: 1.5rem;
}
.btn{
    background-color: #0062ff;
}
.tbl-col-tit{
    vertical-align: top; 
    width: auto;   
}
.tbl-col-data{
    width: 100%;
    p{
        margin-bottom: 0px;
        font-size: 16px;
    }
}
.screenshot-wrapper{
    background-color: #eaeaea;
    max-width: 80%;
    margin-top: 20px;       
}
.pc{
    display:block;
}
@media (max-width: 1023px) { 
    .user-nav{
        background: radial-gradient(closest-corner at 106px, #4972c2, #4972c2, #4972c2) !important
    }
    .menu-logo{
        font-size: 60px;        
    }
}
@media only screen and (max-width: 812px) and (orientation: portrait){
    .container, .container-md, .container-sm {
        max-width: 810px;
    }
}
@media (max-width:812px){
    .pc{
        display:none;
    }
}
@media only screen and (max-width:767px){
    .border-border-box-row{
        padding: 10px;
    }
    .page-tit{
        margin: 30px 0px 30px 0px;
    }
    .tbl-col-tit{
        min-width: 40px;
    }
    .num{       
        padding: 2px 8px;
        font-size: 1.2rem;
    }
    .screenshot-wrapper{
        max-width: 100%;
    }   
    .logo-res{
        margin: 0 auto;
    }
}
</style>