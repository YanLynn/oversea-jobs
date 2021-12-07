<template>
  <div class="login">   
      <div
        v-if="registeredUser"
        class="text-success"
      >Thank you {{registeredUser.name}}.You can now login</div>
        <div class="login-card-body">
          <form class="form-content" @submit.prevent="authenticate">
             <h3 class="form-signin-heading text-center">求人企業ログイン</h3> 
             <div class="form-group has-error has-feedback" v-if="authError">
              <label for="inputError2" class="control-label">{{authError}}</label>           
            </div>

            <div class="form-group mb-4">
              <label for="email">メール</label>
              <div class="input-group">              
                <input type="email" class="form-control" name="メールアドレス" id="email" placeholder="メールアドレス" autocomplete="off" v-model="formLogin.email"/>
              </div>
            </div>

            <div class="form-group">
            <label for="password">パスワード</label>
                <div class="input-group">               
                    <!-- hide password-->
                   
                    <input class="form-control input_pass m-l1" type="password" placeholder="パスワード" id="password" v-model="formLogin.password" v-show="!showPass" />
                   
                    <!-- show password-->
                    <input class="form-control input_pass" type="text" placeholder="パスワード" v-model="formLogin.password" v-show="showPass" />
                   
                        <p  v-show="isIcon">
                            {{showIco}}
                            <span class="showpwd" @click="showPass = !showPass">
                                <span v-show="!showPass"  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <span v-show="showPass" class="fa fa-fw fa-eye-slash"></span>
                            </span>
                        </p>
                   
            
                </div>
            </div>
            <div class="form-group">
                <input type="checkbox" class="custom-control-input custom-checkbox" id="customCheck1">
                <label class="custom-control-label custom-checkbox-label" for="customCheck1">パスワードを記憶して⾃動ログインする
                </label>
            </div>

            <div class="fomr-group text-center m-t-30">
                <button class="btn searchbtn" type="submit">ログイン</button>
                <button class="btn searchbtn" type="submit">閉じる</button>
            </div>
          </form>
        </div>   
  </div>
</template>

<script>
import { login } from "../../partials/auth";
export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    },
  data() {
    return {
      isIcon: false,
      showPass: false,
      formLogin: {
        email: "",
        password: ""
      },
      error: null
    };
  },
  methods: {
    authenticate() {
      this.$store.dispatch("login");
      login(this.$data.formLogin)
        .then(res => {
          // console.log(this.$store.commit());
          this.$store.commit("loginSuccess", res);
          
          const getLoginUser = this.$store.getters.currentUser;
          const redirectTo = getLoginUser.role_id === 2 ? 'recruiter' :  'jobseeker'
          this.$router.push({path: redirectTo})
          //this.$router.push({ path: "/" });
        })
        .catch(error => {
          this.$store.commit("loginFailed", { error });
        });
    }
  },
  computed: {
    authError() { 
      return this.$store.getters.authError;
    },
    registeredUser() {
      return this.$store.getters.registeredUser;
    },
    showIco() {
        if( this.formLogin.password === '') {
            this.isIcon = false;
        }else{
             this.isIcon = true;
        }
    }
  }
};
</script>
<style lang="scss">
@import 'resources/sass/variables.scss'; 
.custom-control-input {
    position: unset;
}
</style>