<template>
    <div>   
        <div :class="currentUser != null && $route.path == '/privacy-policy' ? 'content-bg': 'user-content'" v-if="currentUser != null && $route.path != '/recruiter' && $route.path != '/jobseeker' ">   
            <RecruiterMenu v-if="currentUser.role_id == 2"></RecruiterMenu>
            <JobseekerMenu v-if="currentUser.role_id == 3"></JobseekerMenu>
            <div class="user-container container">
                <div :class="$route.name == 'PublicJobDetails'?'row m-0' : 'row m-0-res'">
                    <router-view :key="$route.path"  @chatStarted="onChatStarted" v-on:refreshChattables="onRefreshChattables"></router-view>
                    <RecruiterSidebar v-if="currentUser.role_id == 2 && $route.name != 'PublicRecruiterScoutEntry' && $route.name != 'PublicScoutFavourite' && $route.name != 'JobseekerAll' && $route.name != 'PrivacyPolicy'"></RecruiterSidebar>
                    <JobseekerSidebar v-if="currentUser.role_id == 3 && $route.name != 'PublicJobDetails' && $route.name != 'publicJobSearchAll' && $route.name != 'PublicFavouriteJob' && $route.name != 'PrivacyPolicy'" ></JobseekerSidebar>
                </div>
            </div>            
            <Footer></Footer>
        </div>
        <div v-else>
            <router-view :key="$route.path"></router-view>
            <Footer></Footer>
        </div>
        <!-- ChatBox -->
		<ChatComponent  v-if="currentUser" ref="refChatComponent" />
		<!-- End Chatbox -->
    </div>
</template>
<script>
import JobseekerSidebar from "../jobseeker/JobseekerSidebar";
import RecruiterSidebar from "../recruiter/RecruiterSidebar";
import RecruiterMenu from "../menu/RecruiterMenu";
import JobseekerMenu from "../menu/JobseekerMenu";
import Footer from "./Footer";
import ChatComponent from './ChatComponent';
import { mapGetters } from "vuex";

export default {
    metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	},  
  data() {
    return {
    };
  },
  components: {
    JobseekerMenu,
    RecruiterMenu,
    JobseekerSidebar,
    RecruiterSidebar,
    Footer,
    ChatComponent,
  },
    created(){
     
        if(this.currentUser != null){
        this.$store.dispatch('user_image');
        this.$store.dispatch('user_info');
            if(this.currentUser.role_id == 2){
                this.$store.dispatch('recFavCount')
                this.$store.dispatch('recApplicantCount')
                this.$store.dispatch('recScoutedCount')

            }
            else if(this.currentUser.role_id == 3)
            {
                this.$store.dispatch('jobseekerFavCount')
                this.$store.dispatch('jobseekerScoutedCount')
            }
        }
      
    
    },
  computed: {
        ...mapGetters(["currentUser","authError"]),
        // authError() {
        //     return this.$store.getters.authError;
        // }
    },
  mounted() {
      console.log("path", this.$route.path)
  },
  methods: {
	onChatStarted(payload) {
		this.$refs.refChatComponent.isToggled = true;
		this.$refs.refChatComponent.getMessage(payload);
		this.$refs.refChatComponent.scrollUserIntoView();
	},
	onRefreshChattables() {
		this.$refs.refChatComponent.getUsers();
	}
  },
  watch: {
	// --Force chat user list refresh on chat pages
	$route (to, from) {
		if (to.path.includes('applied-list') || to.path.includes('scouted-list') || to.path.includes('job-applicant')) {
			this.onRefreshChattables();
		}
	}
  }
};
</script>
<style lang="scss" scoped>
.content-bg{
    background-color: #fff;
}
</style>
