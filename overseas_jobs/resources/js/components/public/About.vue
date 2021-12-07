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
        <h1 class="page-tit">ボーダーレスワーキングについて</h1>
        <p>
        本サイトは海外で働きたい日本人を応援するために、求職者と企業団体の出会いの場を提供するプラットフォームです。<br>
        掲載されている求人に応募したり、海外での勤務を希望する人材を検索し企業がスカウトを行うことが可能です。
        </p>
        <div class="box-row mt-4">
             <img  src="/images/about.jpg" class="img-fluid" />
        </div>
        <div class="box-row">
            <h4 class="mb-4">ボーダレスワーキングが選ばれる理由</h4>
            <div class="row bg-wrap m-0">
                <div class="col-12 col-sm-4">
                    <div class="reason-col">
                        <p class="num">1</p>
                        <p>世界各国の企業の求人を検索できるばかりではなく、求職者の情報を登録し企業団体からのスカウトを待つことが可能です。</p>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="reason-col">
                        <p class="num">2</p>
                        <p>求職者と企業団体間でチャットを使って手軽に問い合わせができます。</p>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="reason-col">
                        <p class="num">3</p>
                        <p>当社の経験豊富な海外就職コーディネーターが求職者や企業団体担当者を手厚くサポートします。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-row">
            <h4 class="mb-4">料金体系</h4>
            <div class="bg-wrap">
                <table class="table table-bordered">
                    <tr>
                        <td class="w-25">求職者会員 </td>
                        <td class="w-75">無料</td>
                    </tr>
                    <tr>
                        <td class="w-25">企業会員</td>
                        <td class="w-75">仲介手数料　200,000 円＋消費税<br>
                            ※内定が確認できた時点で請求を行います。</td>
                    </tr>
                </table>
                <div class="card-row">
                    <p class="mb-4">キャンセルを行った場合</p>
                    <div class="card-box">
                       入金後はいかなる理由でも返金いたしません。
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
		title: 'ボーダレスワーキングについて｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: 'ボーダレスワーキングについてのページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
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
.container {
    line-height: 1.7;
    max-width: 1220px !important;
    p{
        font-size: 16px;
    }
}
.box-row {
    margin-bottom: 75px;
}
.page-tit {
    margin: 50px 0;
    font-size: 28px;  
}
.mb-80 {
    margin-bottom: 80px;
}
.login-container {
    margin: 0 30px 30px 30px;
}
.bg-wrap {
    padding: 30px 20px;
    background: #f1f1f1;
    border-radius: 15px;
}
.reason-col {
    min-height: 250px;
    padding: 20px;
    background: #fff;
    border: 1px solid #818181;
    border-radius: 5px;
    height: 100%;
}
.num {
    display: inline-block;
    margin-bottom: 40px;
    padding: 8px 20px;
    background: #4c76c2;
    color: #fff;
    border-radius: 5px;
    font-size: 1.5rem;
}
.table {
    width: 70%;
    margin: 0 auto 50px;
    background: #fff;
    border-collapse: collapse !important;   
}
.table-bordered td, .table-bordered th {
    padding: 10px;
    border: 1px solid #818181;
}
.card-row {
    width: 70%;
    margin: 0 auto;
}
.card-box {
    padding: 10px;
    border: 1px solid #818181;
    background: #fff;
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
     .page-tit{
        margin: 30px 0px 30px 0px;
    }    
    .num{       
        padding: 2px 8px;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    .screenshot-wrapper{
        max-width: 100%;
    }
    .table{
        width: 100%;
    }
    .card-row{
        width: 100%;
    }
    .bg-wrap .col-12{
        margin-bottom:20px;
    }
    .reason-col{
        min-height: 180px;
    }
    .logo-res{
        margin: 0 auto;
    }
}
@media only screen and (max-width:414px){
    .bg-wrap .col-12{
        padding-left: 0px;
        padding-right: 0px;
    }
}
</style>