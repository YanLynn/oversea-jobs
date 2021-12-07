<template>
    <div class="user-main">   
    <!-- successful-->
    <div class="row col-12 text-center">        
        <div class="col-md-6 offset-md-3 inner-wrapper-right">
             <div class="successful-block">
                <span class="icon icon-logo logo recruiter-primary-color-txt"></span>
                <div class="successful-wrapper">              
                    <dl class="detail-list">
                        <dt class="detail-head-group mt-4">管理番号</dt>
                        <dd class="detail-data-group mt-4">S2020-0005</dd>
                    </dl>
                    <dl class="detail-list">
                        <dt class="detail-head-group">求人タイトル</dt>
                        <dd class="detail-data-group">レジ打ち</dd>
                    </dl>
                     <dl class="detail-list">
                        <dt class="detail-head-group">求人者氏名</dt>
                        <dd class="detail-data-group">西村</dd>
                    </dl>
                     <dl class="detail-list">
                        <dt class="detail-head-group">請求金額</dt>
                        <dd class="detail-data-group">200,000 円</dd>
                    </dl>
                </div>
               
                <div class="row data-block">
                    <div class="col-12">
                        <p>支払いに用いるクレジットカード</p>
                        <div class="col-8 offset-2 text-left">
                        <p class="custom-radio-group pl-5r">
                            <input type="radio" id="japan" name="radio-group" checked class="custion-radio" >
                            <label for="japan" class="custom-radio-lable">4***-****-****-1111</label>
                        </p>
                        <p class="custom-radio-group pl-5r">
                            <input type="radio" id="overseas" name="radio-group" class="custion-radio">
                            <label for="overseas" class="custom-radio-lable">他のクレジットカードを使用</label>
                        </p>
                        </div>
                    </div>                          
                </div> 
                <div class="text-center">
                    <button class="btn searchbtn delete-color">確認</button>
                    <button class="btn searchbtn cancel-color">キャンセル</button>
                </div>               
               
            </div>
        </div>
    </div>
    <!--end successful-->
    </div>    
</template>
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