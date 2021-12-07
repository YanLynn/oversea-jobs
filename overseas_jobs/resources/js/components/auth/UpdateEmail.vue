<template>
  <div class="login">   
        <div class="login-card-body" v-if="role == 2">
         <div class="form-wrap" style="border-top: 3px solid #00a195;">
            <a href="/" class="no-href" >
                <span class="icon-new-logo-txt logo-img recruiter-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </a> 
            <div class="form-content">
                <h3 class="form-signin-heading text-center recruiter-primary-color-txt">ログイン用メールアドレスの変更</h3>  
                <div class="success-email text-center">
                    <label style="font-size:16px">{{message}}</label>
                    <a href="/" class="btn searchbtn recruiter-primary-color m-t-30">トップページへ</a>
                </div>
            </div>  
            </div>
        </div>   

        <div class="login-card-body" v-if="role == 3">
          <div class="form-wrap">
            <a href="/" class="no-href" >
                <span class="icon-new-logo-txt logo-img job-primary-color-txt"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span></span>
            </a> 
            
            <div class="form-content">
                <h3 class="form-signin-heading text-center job-primary-color-txt">ログイン用メールアドレスの変更</h3>  
                    <div class="success-email text-center">
                        <label style="font-size:16px">{{message}}</label>
                        <a href="/" class="btn searchbtn job-primary-color m-t-30" to="/jobseeker/login">トップページへ</a>
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
      message:'',
      role:null,
    };
  },

    created(){
            // replace  url amp;
            var amp = this.$route.query
            var newKey = {
                'amp;key' : "key",
                'amp;_id' : "_id",
                'amp;oe' : "oe"
            }
            _.each(amp, function(value, key) {
                key = newKey[key] || key;
                amp[key] = value;
            });

            if(this.$store.getters.currentUser != null){
                localStorage.removeItem('user');
            }
            if(this.$route.query.oe != null ){
                this.updateEmail();
            }

        },
    methods: {

        updateEmail(){
            this.token = this.$route.query.token;
            this.key = this.$route.query.key;
            this.oe = this.$route.query.oe;
            this._id = this.$route.query._id;
            //Universal Controller 
            this.$api.post('update-email',{token:this.token,key:this.key,oe:this.oe,id:this._id}).then(res => {
                // console.log(res.data)
                this.role = res.data.role
                let success = res.data.success
                if(success == 'email_changed'){
                    this.message = 'ログイン用メールアドレスは既に変更されています。';
                }else{
                    this.message = 'ログイン用メールアドレスの変更が完了しました。';
                }
            }).catch(res => {
               this.role = res.response.data.role
            //    this.message = 'Token_Expired!';
               this.message = 'リンクの有効期限が切れています。';
               
            })
        },
  },
  
};
</script>



<style scoped>    
    .custom-control-input {
        position: unset;
    }
    .icon-login { 
        margin-right: 10px;
        font-size: 40px;
        vertical-align: middle;
    }
    .close-ico {
        position: absolute;
        font-size: 23px;
        top: -15px;
        right: -12px;
        color: #f0fae3;
        background: #00a195   ;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        vertical-align: middle;
    }
   .login .error{
        margin-top:0px;
    }
    .success-email{
        position: absolute;
        top: 45%;
        right: 20%;
        left: 20%;
        bottom: 25%;
    }

</style>