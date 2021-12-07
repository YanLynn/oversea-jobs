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
        <h1 class="page-tit">特定商取引法に基づく表記</h1>
        <div class="box-row">
            <h4 class="sub-tit">本サイト運営会社</h4>
            <p class="mt-4">
                名称：株式会社 TrustGrowth<br>
                運営統括責任者：久保田直一<br>
                本店所在地：〒163-0713 東京都新宿区西新宿 2-7-1 小田急第一生命ビル 13 階<br>
                お問合せ窓口：<a href="/query" target="_blank">https://borderless-working.jp/query</a>
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">サービス費用</h4>
            <p class="mt-4">
                仲介手数料 200,000 円
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">その他の費用</h4>
            <p class="mt-4">
                その他の費用
                消費税<br>
                銀行振り込み手数料<br>
                インターネット接続料金その他の電気通信回線の通信に関する費用<br>
                （料金はお客様がご利用の契約事業者が定める通り）
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">支払い方法</h4>
            <p class="mt-4">
            クレジットカード決済<br>
                銀行振り込み
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">支払い時期</h4>
            <p class="mt-4">
                クレジットカード決済：当社に内定の通知をいただいた後、メールによる請求あります。メールの指示に従い14 日以内にお支払いください。<br>
                銀行振り込み：当社に内定の通知をいただいた後、請求書がメールにより送付されますので、その指示によりお支払いください。
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">仕事提供の時期</h4>
            <p class="mt-4">
                企業会員と求職者会員で合意した日時
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">仕事提供者</h4>
            <p class="mt-4">
               仕事提供者の住所、電話番号、仕事提供に関する責任者その他の法定記載事項については、請求があり次第提供いたしますので、必要な方は上記本サイト運営会社のお問合せ窓口までご連絡ください。
            </p>
        </div>
        <div class="box-row">
            <h4 class="sub-tit">キャンセルポリシー</h4>
            <p class="mt-4">
                内定をキャンセルする場合は入金前に弊社にご連絡ください。<br>
                入金された後はいかなる理由があっても返金いたしません。
                <!-- <router-link to="/about" target="_blank">こちら</router-link>をご覧ください。 -->
            </p>
        </div>
    </div>
</div>
</template>
<script>
import PublicMenu from "../menu/PublicMenu";
export default {
    metaInfo: {
		title: '特定商取引法に基づく表記｜海外就職・海外求人ならボーダレスワーキング',
        meta: [
            { name: 'description', content: '特定商取引法に基づく表記のページです。ボーダレスワーキングは、海外就職をサポートするマッチングプラットフォームです。海外就職コーディネーターが徹底的にサポートいたします。｜ 海外就職・海外求人ならボーダレスワーキング' },
        ]
	},    
    components: {
        PublicMenu,
    },            
};
</script>
<style  scoped>
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
}
.box-row {
    margin-bottom: 1.5rem;
}
.page-tit {
    margin: 70px 0 50px 0;
    font-size: 28px;  
}
.sub-tit {
    display: block;
    padding: 10px 10px 10px 18px;
    font-size: 21px;
    background: #e5e5e5;
    border-radius: 3px;  
}
.mb-80 {
    margin-bottom: 80px;
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
    .logo-res{
        margin: 0 auto;
    }
}
</style>