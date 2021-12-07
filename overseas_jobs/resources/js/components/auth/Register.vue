<template>
    <div class="signin">
        <div class="signin-card-body">
            <div class="form-content">       
                <form @submit.prevent="register">
                    <h3 class="form-signin-heading text-center mb-5">企業会員新規登録</h3>
                     <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">企業の登記地</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7 d-flex">
                            <p class="custom-radio-group">
                                <input type="radio" id="japan" name="radio-group" checked class="custion-radio" >
                                <label for="japan" class="custom-radio-lable">日本</label>
                            </p>
                            <p class="custom-radio-group ml-4">
                                <input type="radio" id="overseas" name="radio-group" class="custion-radio">
                                <label for="overseas" class="custom-radio-lable">海外</label>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">企業名</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="name" class="form-control" value="formRegister.name"  v-model="formRegister.name" placeholder="企業名"> 
                        
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">企業名(愛称)</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="企業名(愛称)">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">設立年月日</label>
                        </div>
                        <div class="col-md-7">
                            <input type="date" name="" class="form-control"  placeholder="設立年月日">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">代表者名</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="代表者名">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">従業員数</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="従業員数">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">事業内容</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="事業内容">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">所在地</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="所在地">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">電話番号</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="" class="form-control"  placeholder="電話番号">           
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">メールアドレス</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="email" name="email" class="form-control" v-model="formRegister.email" placeholder="メールアドレス">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">ＷＥＢサイトURL</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="text" name="" class="form-control" placeholder="ＷＥＢサイトURL">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">担当者名</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="text" name="" class="form-control" placeholder="担当者名">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">担当者名(フリガナ)</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="text" name="" class="form-control" placeholder="担当者名(フリガナ)">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">パスワード</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="password" name="password" class="form-control" v-model="formRegister.password" placeholder="パスワード">         
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name">パスワード確認</label>
                            <span class="required">*</span>
                        </div>
                        <div class="col-md-7">
                        <input type="password" name="" class="form-control"  placeholder="パスワード確認">         
                        </div>
                    </div>
                    
                    <div class="row"  v-if="activetab ==='overseas'">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">名前</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="name" class="form-control"  v-model="formRegister.name" placeholder="名前">           
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">名前(フリガナ)</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="" class="form-control"  placeholder="名前(フリガナ)">           
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">生年月日</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" name="" class="form-control"  placeholder="生年月日">           
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">住所</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="" class="form-control"  placeholder="住所">           
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">電話番号</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="" class="form-control"  placeholder="電話番号">           
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">メールアドレス</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                <input type="email" name="email" class="form-control" v-model="formRegister.email" placeholder="メールアドレス">  
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">パスワード</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                <input type="password" name="password" class="form-control" v-model="formRegister.password" placeholder="パスワード">         
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">パスワード確認</label>
                                    <span class="required">*</span>
                                </div>
                                <div class="col-md-7">
                                <input type="password" name="" class="form-control"  placeholder="パスワード確認">         
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group text-center mt-3">
                        <input type="submit" value="確認" class="btn primary-btn btn-m">
                    </div> -->
                    <div class="d-flex justify-content-center">
                         <div class="form-group text-center mt-3">
                            <input type="submit" value="戻る" class="btn btn-m searchbtn">
                        </div>
                        <div class="form-group text-center mt-3 ml-5">
                            <input type="submit" value="送信" class="btn btn-m searchbtn">
                        </div>
                    </div>
                   
                </form>         
            </div>
        </div>
    </div>
</template>

<script>
import {registerUser} from '../../partials/auth';
export default {
    data(){
        return {
           
            formRegister: {
            name: '',
            email: '',
            password: ''
        },
        }
    },
    methods:{
        register(){
            registerUser(this.$data.formRegister)
            .then(res => {
                console.log(res);
                this.$store.commit("registerSuccess", res);
                this.$router.push({path: '/login'});
            })
            .catch(error => {
                this.$store.commit("registerFailed", {error});
            })
        }
    },
    computed:{
        regError(){
            return this.$store.getters.regError
        }
    }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>