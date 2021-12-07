<template>
    <div class="full-width">
        <PublicMenu></PublicMenu>
        <PublicSearchResult currentPage="jobseeker"  :keywordSearch="keywordSearch" :jobSeekerData="jobseeker"  :recruiterData="recruiter" seeAll="see_all" ref="searchResult"></PublicSearchResult>
    </div>  
</template>

<script>

import PublicSearchResult from '../public/home/PublicSearchResult';
import PublicMenu from "../menu/PublicMenu";

export default {
    metaInfo: {
		title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
	},   
 components: {  PublicSearchResult, PublicMenu },

 data(){
     return{
        jobseeker:{
            keyword:'',
            country:[],
            historyCountry:[],
            occupation:[],
        },
        keywordSearch:{
            keyword:'',
            country:[],
            historyCountry:[],
            occupation:[],
        },
        recruiter:{
            keyword:'',
            country:[],
            occupation:[],
        },
     }
 },

    
}
</script>