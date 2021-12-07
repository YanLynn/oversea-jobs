<template>
    <div id="app">  
      <router-view></router-view>
      <transition name="fade">
        <div id="pagetop" class="top-btn" v-show="scY > 300" @click="toTop">
             <span class="glyphicon glyphicon-circle-arrow-up"></span>            
        </div>
      </transition>
    </div>
</template>

<script>
export default {
  data() {
    return {
      role: "",
      scTimer: 0,
      scY: 0,
    };
  },
  
  mounted() {
    if (!socket.connected) {
       socket.connect()
    }

    const self = this;
    window.setInterval(function() {
    //   socket.emit('checkSessionTimeOut');
        self.$api.get('user');
    }, 60000);
    
    // socket.on("sessionTimeOut", () => {
    //     this.$api.get('user');
    // });

    this.blockUsers();
    window.addEventListener('scroll', this.handleScroll);
    },

     methods: {
      blockUsers(){
        socket.on("block-user", (data) => {
          this.getBlockUser(data);
        });
      },
      getBlockUser(data){
        this.$api.get(`block-user/${data}`).then(res => {
            const currentUser = this.$store.getters.currentUser
            const status = res.data.status
            const user_data = res.data.user_data
            const user_type = res.data.user_type
            if(res.data != '' && res.data != null){
                if(currentUser != null) {
                    if(currentUser.email == user_data.email && currentUser.role_id == user_data.role_id){
                        this.$store.commit('logout');
                        if(status == 2) {
                            this.$alertService.showErrorDialog(null,"アカウントが停止されました。");
                        }
                        else if(status == 3) {
                            this.$alertService.showErrorDialog(null,"アカウントが退会されました。");
                        }
                        if(user_type == 'jobseeker'){
                            this.$router.push({path:"/jobseeker/login"});
                        }else{
                            this.$router.push({path:'/recruiter/login'});
                        }
                    }
                }
                // if(status == 2){
                //    this.$store.commit('logout');
                //    this.$alertService.showErrorDialog(null,"アカウントが停止されました。");
                //    if(user_type == 'jobseeker'){
                //       this.$router.push({path:"/jobseeker/login"});
                //    }else{
                //       this.$router.push({path:'/recruiter/login'});
                //    }
                // }
                // if(status == 3){
                //    this.$store.commit('logout');
                //    this.$alertService.showErrorDialog(null,"アカウントが退会されました。");
                //    if(user_type == 'jobseeker'){
                //       this.$router.push({path:"/jobseeker/login"});
                //    }else{
                //       this.$router.push({path:'/recruiter/login'});
                //    }
                // }
            }
        })
      },
  
      handleScroll: function () {
        if (this.scTimer) return;
        this.scTimer = setTimeout(() => {
          this.scY = window.scrollY;
          clearTimeout(this.scTimer);
          this.scTimer = 0;
        }, 10);
      },
      toTop: function () {
         $('html, body').stop().animate({
         scrollTop: document.getElementById('app').offsetTop - 0
        }, 1000); 
        // window.scrollTo({
        //   top: 0,
        //   behavior: "smooth"
        // });
      },
    },
};
</script>

<style scoped>
.top-btn {
    width: 50px;
    height: 50px;
    background: #c3c4c3;
    color: #000;
    text-align: center;
    border-radius: 50%;
    position: fixed;
    bottom: 85px;
    right: 25px;
    cursor: pointer;
    font-size: 18px;
    line-height: 50px;
    z-index: 10000;
    transition: .5s;
    border: 1px solid #eaeaea;
  /* background-image: radial-gradient(circle, #fff, #fff); */
  /* radial-gradient(circle, #17ab9d, #106a61) */
}
.top-btn::before {
  position: absolute;
  content: '';
  width: 100%;
  height: 0;
  background: #eaeaea;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: -1;
  border-radius: 50%;
  transition: .5s;
}
.top-btn:hover::before {
  height: 100%;
}
@media screen and (max-width:480px){
  .top-btn{
    bottom:15px;
    width:35px;
    height:35px;
    line-height:35px;
  }
  .m-0-res{
    margin-left:0px;
    margin-right:0px;
  }
}
</style>