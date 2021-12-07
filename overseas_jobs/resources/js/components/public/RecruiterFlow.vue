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
        <h1 class="page-tit">求人掲載と人材スカウトの流れ</h1>  
        <h4>求人の掲載</h4>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">1</span></td>
                <td class="tbl-col-data text-center">
                    <p class="mb-4 text-left">まだ会員登録が済んでいない場合、以下のリンクから登録を行います。</p>
                    <router-link to="/recruiter/register"  class="btn btn-large border-style no-href">企業会員新規登録</router-link>
                </td>
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">2</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left"><router-link to="/recruiter/login" target="_blank">企業会員管理画面</router-link>にログインし、必要に応じて詳細なプロフィールの入力を行います。</p>                                
                </td>
            </tr>      
        </table>
         <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">3</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left">新規求人の登録を行います。</p>                                
                </td>
            </tr>      
        </table>

        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">4</span></td>
                <td class="tbl-col-data text-center">                   
                    <p class="text-left">求職者会員が掲載されている求人に問合わせ/応募するとチャットで会話が可能となります。</p>
                    <p class="text-left">チャットにて質問や面接日の設定等を行ってください。</p>     
                    <p class="text-left">チャット機能は企業会員管理画面から利用できます。</p>          
                    <p class="text-left mb-4">※チャット上での電話・メール・住所等連絡先の交換は禁止していますので、ご注意願います。</p>

                    <p class="text-left">面接の他、求職者会員の詳細情報やアップロードされた履歴書や業務経歴書も参考に 採用の可否を判断してください。</p>
                     <div class="screenshot-wrapper">                    
                        <img src="/images/recruiter/for-chat.jpg" alt="背景" class="img-fluid">
                        <!--images here-->
                    </div>
                </td>
            </tr>      
        </table>

        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">5</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left">採用の可否を採否通知機能で運営管理者までお知らせください。</p>  
                     <div class="screenshot-noti-wrapper">                    
                        <img src="/images/recruiter/notification.png" alt="採否通知" class="img-fluid">                       
                    </div>                              
                </td>
            </tr>      
        </table>        

        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">6</span></td>
                <td class="tbl-col-data text-center">
                    <p class="text-left">採用の連絡をいただいた後、運営管理者よりメールにて仲介手数料の請求があります。</p>                                
                    <p class="text-left">メールに指示に従ってお支払いをお願いいたします。</p>                                
                </td>
            </tr>      
        </table>

        <h4 class="sub-tit mb-4 mt-5">人材のスカウト</h4>
        <h4 class="sub-tit mb-4">求人の掲載の１と２はスカウトを行う場合も必要です。それ以降の手順が異なります。</h4>

        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">3</span></td>
                <td class="tbl-col-data text-center">   
                    <p class="text-left"><a href="/" target="_blank">トップ画面</a>よりスカウトを希望している人材 ( 求職者会員 ) を検索します。</p>
                    <p class="text-left">スカウトを行う場合、企業会員管理画面へのログインが必要です。</p>                   
                </td>
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">4</span></td>
                <td class="tbl-col-data text-center">   
                    <p class="text-left">求職者会員の詳細プロフィール上の「スカウトする」ボタンを押しスカウトを行います。</p>   
                       <div class="screenshot-wrapper">
                        <img src="/images/recruiter/recruiter-scout.png" alt="スカウトを受けている求人" class="img-fluid">
                        <!--images here-->
                    </div>                  
                </td>
            </tr>      
        </table>       
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">5</span></td>
                <td class="tbl-col-data text-center">   
                    <p class="text-left">求職者会員がスカウトに興味を示すとチャットで会話が可能となります。</p>
                    <p class="text-left">チャットにて質問や面接日の設定等を行ってください。</p>                   
                    <p class="text-left">チャット機能は企業会員管理画面から利用できます。</p>                   
                    <p class="text-left">※チャット上での電話・メール・住所等連絡先の交換は禁止していますので、ご注意願います。</p>                   
                    <p class="text-left">面接の他、求職者会員の詳細情報やアップロードされた履歴書や業務経歴書も参考に採用の可否を判断してください。</p>                   
                </td>               
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">6</span></td>
                <td class="tbl-col-data text-center">                       
                    <p class="text-left">採用の可否を採否通知機能で運営管理者までお知らせください。</p>   
                      <div class="screenshot-noti-wrapper">                    
                        <img src="/images/recruiter/notification.png" alt="採否通知" class="img-fluid">                       
                    </div>                   
                </td>
            </tr>      
        </table>
        <table class="profile-tbl">
            <tr class="border-border-box-row">
                <td class="tbl-col-tit text-label-color"><span class="num">7</span></td>
                <td class="tbl-col-data text-center">   
                    <p class="text-left">採用の場合、運営管理者よりメールにて仲介手数料の請求を行います。</p>
                    <p class="text-left">メールに指示に従ってお支払いをお願いいたします。</p>                   
                </td>
            </tr>      
        </table>
        
    </div>
</div>
</template>
<script>
export default { 
	metaInfo: {
		title: '求人掲載と人材スカウトの流れ｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: '求人掲載と人材スカウトの流れのページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
	},        
};
</script>
<style lang="scss" scoped>
.menu-logo{
    font-size:70px;
    color:#004842;
}
.user-nav{
    background: url(/images/common/header-bg-recruiter.jpg) no-repeat center 0 !important;
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
    background: #00a195 ;
    padding: 10px 20px;
    color: #fff;
    border-radius: 5px;
    font-size: 1.5rem;
}
.btn{
    background-color: #00a195 ;
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
    margin-top: 20px;   
    max-width: 80%;    
}
.screenshot-noti-wrapper{
    background: #eaeaea;    
    text-align: center;
    padding: 20px;
    display: table-cell;
}
.pc{
    display: block
}
@media (max-width: 1023px) { 
.user-nav{
        background: radial-gradient(closest-corner at 106px, #00a195, #00a195, #00a195) !important;
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