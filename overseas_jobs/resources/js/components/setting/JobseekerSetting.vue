<template>

    <div class="user-main">
        <div class="outer-wrapper-right job-wrapper m-0">
                <h4 class="form-group row col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 header">{{ $t('setting.change_email') }}</h4>    
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 inner-wrapper-right">
                        <div class="new-job-block">
                            <div class="row">
                            <div class="col-12">
                                <dl class="row">
                                    <dt class="col-xl-4 col-sm-5 label-txt ">求職者名</dt>
                                    <dd class="col-xl-8 col-sm-7">{{user.name}}</dd>

                                    <dt class="col-xl-4 col-sm-5 label-txt ">メールアドレス</dt>
                                    <dd class="col-xl-8 col-sm-7">{{user.email}}</dd>
                                </dl>  
                                <div class="msg" v-if="!errorShow">{{message.verify}}</div> 
                            </div>                          
                            </div>
                            <div class="row">
                            <div class="col-12">
                                <transition name="list">
                                    <div class="public-info-block mb-2">
                                    <div class="form-group searchform-one">
                                        <div class="form-group">
                                            <label for="">{{$t('setting.confirm_password')}}</label>
                                            <input type="password" v-model="request.password" v-show="!showPass" @keyup="removeError" class="form-control form-control-sm  mb-2">
                                            <input type="text" v-model="request.password" v-show="showPass" @keyup="removeError" class="form-control form-control-sm mb-2"> 
                                            <span class="text-danger row col-12">{{message.notMatch}}</span>

                                            <label for="">{{$t('setting.new_email')}}</label>
                                            <input type="email" v-model="request.email" @keyup="removeError" class="form-control form-control-sm">
                                            <span class="text-danger">{{message.exist}}</span>                                    
                                            <p v-show="isIcon" class="m-0">
                                                {{showIco}}
                                                <span class="showpwd" @click="showPass = !showPass">
                                                    <span v-show="!showPass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                    <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                                                </span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <!-- <div class="form-group searchform-one">
                                            <label for="">{{$t('setting.confirm_password')}}</label>
                                            <input type="password" v-model="request.password" @keyup="removeError" class="form-control form-control-sm"  
                                             >
                                            <span class="text-danger">{{message.notMatch}}</span>
                                        </div> -->
                                    <div class="text-center">
                                        <!-- <span><button @click="passwordShow('showPass')" class="btn border-style cancel-color btn-m">{{$t('setting.cancel')}}</button></span> -->
                                        <span class="ml-2"><button @click="changeEmail" class="btn border-style job-primary-color btn-m">{{$t('setting.submit')}}</button></span>
                                    </div>
                                        
                                    </div>
                                </transition>
                                <!-- <transition name="list">
                                    <div class="public-info-block mt-3" v-if="!showEmail">
                                        <div class="form-group searchform-one">
                                            <label for="">{{$t('setting.new_email')}}</label>
                                            <input type="email" v-model="request.email" @keyup="removeError" class="form-control form-control-sm">
                                            <span class="text-danger">{{message.exist}}</span>
                                        </div>
                                        <span><button @click="passwordShow('showEmail')" class="btn btn-m primary-btn border-style cancel-color">{{$t('setting.cancel')}}</button></span>
                                        <span class="ml-2"><button @click="changeEmail" class="btn btn-m primary-btn border-style job-primary-color">{{$t('setting.submit')}}</button></span>
                                        
                                    </div>
                                </transition> -->
                            </div>
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
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        }, 
        data(){
            return{
                showPass: false, 
                isIcon: false,   
                user:[],
                show:true,
                errorShow:true,
                showEmail:true,
                message:{
                    verify:'',
                    notMatch:'',
                    exist:''
                },
                request:{
                    email:'',
                    password:''
                },
            }
        },

        created(){
            
            if(this.$route.query.token != null){
                this.updateEmail();
            }
            this.userInfo();
            this.message.verify = '';
            this.message.notMatch = '';
            this.message.exist = '';

        },
        methods:{
            userInfo(){
                this.$api.get('user').then(res => {
                   this.user = res.data
                   this.$store.getters.currentUser.email = this.user.email
                   //set localstorge user email
                    let localStorageUser = JSON.parse(localStorage.user);
                        localStorageUser.email = this.user.email
                        localStorage.setItem('user',JSON.stringify(localStorageUser))
                })
            },
            passwordShow(e){
                
                if(e == 'showPass'){
                   this.show = !this.show
                }
                if(e == 'showEmail'){
                    this.showEmail = true;
                }
                 this.request.password = '';
                 this.request.email = '';
               
            },
            
            removeError(){
                this.message.notMatch = "";
                this.message.verify = "";
                this.message.exist = "";

            },
            changeEmail(){
                
                let loader = this.$loading.show({
                    container: this.$refs.loadingRef,
                    isFullPage: false
                });
                //Universal Controller 
                this.$api.post('check-password',this.request).then(res =>{

                    this.$api.post('change-email',this.request).then(res => {
                        // this.message.verify = res.data.success;
                        this.$alertService.showSuccessDialog(null, this.$t('setting.email_changed'), this.$t('common.close'));
                        this.errorShow = false;
                        setTimeout(() => this.errorShow = true, 5000);
                        setTimeout(() => this.show = true);
                        setTimeout(() => this.showEmail = true);
                        this.request.password = '';
                        this.request.email = '';
                        loader.hide();
                    }).catch(res => {
                        this.message.exist = res.response.data.error;
                        loader.hide();
                    })
                    
                }).catch(res => {
                    loader.hide();
                    this.showEmail = true;
                    this.message.notMatch = res.response.data.error;
                })
            },
            updateEmail(){
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
                this.token = this.$route.query.token;
                this.key = this.$route.query.key;
                //Universal Controller 
                this.$api.post('update-email',{token:this.token,key:this.key}).then(res => {
                    console.log(res.data)
                }).catch(res => {
                    this.message.verify = res.response.data.error;
                    this.errorShow = false;
                     setTimeout(() => this.errorShow = true, 5000);
                })
            },

        },
        computed:{
             showIco() {
                if( this.request.password === '' ) {
                    this.isIcon = false;
                }
                else{
                    this.isIcon = true;
                }
            } 
           
        }
};
</script>
<style lang="scss" scoped>
.public-info-block{
    background-color: #eaeaea;
    border: 1px solid #9b9898;
    border-radius: 5px;
    padding: 20px;
}
.title-div{
    padding: 5px;
    border-bottom: 6px #00a195 double;
}
.title-div > span{
    font-weight: bold;
    text-transform: uppercase;
}
.msg{
    background-color: #f7f7ce;
    border-radius: 5px;
    margin-top: 5px;
    text-align: center
}

.box{
    box-shadow: 0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);
    border-radius: 10px;
    padding: 10px;
    background-color: #fff;
    height: auto;
    /* width: 50%; */
    margin-top:20px;
}
.box-div{

}
.change-btn > button{
    background-color: #81f04e9e;
    border: 0px;
    padding: 4px 15px 4px 15px;
    border-radius: 26px;
    font-size: 11px;
    font-weight: bold;
}
.change-btn > button:hover{
    background-color: #56db199e;
    
}
.change-btn > button:active{
    background-color: #fff;
    
}
.list-enter,
.list-leave-to {
    visibility: hidden;
    height: 0;
    margin: 0;
    padding: 0;
    opacity: 0;
}
.list-enter-active,
.list-leave-active {
    transition: all 0.5s;
}

    .showpwd {
    position: absolute;
    top: 40px;
    z-index: 1000;
    right: 10px;
    }
    @media screen and (max-width:320px){
.showpwd{
    top: 60px;
}
}
</style>