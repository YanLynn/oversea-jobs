import { getLoggedinUser } from '../../partials/auth';
import api from '../../api/apiBasePath';
import { reject } from 'vue-filter';
const user = getLoggedinUser();

export const state = {

    currentUser: user,
    isLoggedIn: !!user,
    loading: false,
    auth_error: null,
    reg_error: null,
    registeredUser: null,
    jobseeker_fav_count:0,
    jobseeker_scouted_count:0,
    rec_fav_count:0,
    rec_scouted_count:0,
    rec_applicant_count:0,
    get_user_image:'',
    get_user_info:null,
};

export const getters = {
    isLoading(state) {
        return state.loading;
    },
    isLoggedin(state) {
        return state.isLoggedin;
    },
    currentUser(state) {
        return state.currentUser;
    },
    authError(state) {
        return state.auth_error;
    },
    regError(state) {
        return state.reg_error;
    },
    registeredUser(state) {
        return state.registeredUser;
    },
    token(state) {

        if (state.currentUser && state.currentUser.token) {
            return state.currentUser.token;
        }
        return false;
    },

    jobseekerfavCounter(state) {
		return state.jobseeker_fav_count;
	},
    jobseekerScoutedCounter(state){
        
        return state.jobseeker_scouted_count;
    },
    
    recFavCounter(state) {
		return state.rec_fav_count;
    },
    recScoutedCounter(state)
    {
        return state.rec_scouted_count;
    },
    recApplicantCounter(state)
    {
        return state.rec_applicant_count;
    },
    getUserImage(state){
        return state.get_user_image;
    },
    getUserInfo(state){
        return state.get_user_info;
    }
	
};


export const mutations = {
    login(state) {
        state.loading = true;
        state.auth_error = null;
    },
    loginSuccess(state, payload) {
     
        state.auth_error = null;
        state.isLoggedin = true;
        state.loading = false;
        state.currentUser = Object.assign({}, payload.user, { token: payload.access_token });
        localStorage.setItem("user", JSON.stringify(state.currentUser));
    },

    loginFailed(state, payload) {
        state.loading = false;
        state.auth_error = payload.error;
    },
    logout(state) {
        localStorage.removeItem("user");
        localStorage.removeItem("count");
        state.isLoggedin = false;
        state.currentUser = null;
        state.jobseeker_fav_count = 0;
        state.jobseeker_scouted_count = 0;
        state.rec_fav_count=0;
        state.rec_scouted_count=0;
        state.rec_applicant_count=0;
        state.get_user_image='';
        state.get_user_info=null;
    },
    registerSuccess(state, payload) {
        state.reg_error = null;
        state.registeredUser = payload.user;
    },
    registerFailed(state, payload) {
        state.reg_error = payload.error;
    },
    addJobseekerFav(state, payload) {
		state.jobseeker_fav_count = payload;
	
    },
    jobseekerScoutedCount(state,payload){

        state.jobseeker_scouted_count = payload ;
	},
    addRecFav(state, payload) {
		state.rec_fav_count = payload;
    },
    recruiterScoutedCount(state,payload){
        state.rec_scouted_count = payload;
    },
    applicantCount(state,payload){
        state.rec_applicant_count = payload;
    },
    userImage(state,payload){
        state.get_user_image = payload;
    },
    userInfo(state,payload){
        state.get_user_info = payload
    },
	refreshToken(state, token) {
		state.currentUser.token = token;
		localStorage.setItem("user", JSON.stringify(state.currentUser));
	}
	
}

export const actions = {
    
    login(context) {
        context.commit("login");
    }, 
    
    jobseekerScoutedCount({ commit }, payload)
    {
       api.get(`/jobseeker-scouted-count`).then(r => {
            payload = r.data;
            commit('jobseekerScoutedCount',payload)
        });
    },
	jobseekerFavCount({ commit }, payload) {
		payload = 0
		api.get(`/jobseeker-fav-count`).then(r => {
			payload = r.data
			commit('addJobseekerFav', payload)

		})	
    },
    recFavCount({commit},payload){
        payload = 0
		api.get(`/recruiter-fav-count`).then(r => {
            payload = r.data
			commit('addRecFav', payload)

		})
    },
    recApplicantCount({commit},payload){
        payload = 0
		api.get(`/applicant-count`).then(r => {
            payload = r.data
			commit('applicantCount', payload)

		})
    },
    recScoutedCount({commit},payload){
        payload = 0
		api.get(`/recruiter-scouted-count`).then(r => {
            payload = r.data
			commit('recruiterScoutedCount', payload)

		})
    },
    user_image({commit},payload){
        api.get(`user-img`).then(r => {
            var dd = new Date();
            var sec = dd.getMilliseconds();
            if(r.data){
                payload = r.data + '?version=' + sec;
            }
            else{
                payload = r.data;
            }
            
            console.log("r.data",r.data)
            console.log("payload",payload)
            commit('userImage',payload)
        })
    },
    user_info({commit},payload){
        api.get(`user-info`).then(r => {
            payload = r.data
            commit('userInfo',payload)
        })
    },
	refreshToken({commit},payload){
		return new Promise((resolve) => {
			commit('refreshToken', payload.token);
			resolve();
		});
	}
}

export default {
    state,
    getters,
    mutations,
    actions

}



