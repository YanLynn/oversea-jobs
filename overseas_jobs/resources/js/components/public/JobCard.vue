<template>  
        <div :class="($route.name != 'PublicJobDetails' && currentUser == null ) ? 'public-wrapper' : ''">
            <!-- {{data}} -->
            <div class="new-job-block">
                <div class="data-block d-table">
                    <div class="d-table-cell sub-block">
                        <div class="img-block">
                            <img :src="data.logo_url" alt="job-img" class="img-fluid" />                             
                        </div>
                        <p class="date mt-1">
                            <span class="label-txt pr-1 mb-1">掲載日</span>{{data.job_post_date}}
                        </p>
                    </div>
                    
                    <div class="d-table-cell align-top" style="padding-left:20px">                        
                        <h5 class="txt-vertical-ellipsis">{{data.title}}</h5>  
                        <p class="txt-vertical-ellipsis">{{data.occupation_description}}</p>                  
                        <p class="d-sm-flex">      
                            <span class="label-txt left-block">勤務地</span>
                            <span>{{data.country_name}}</span>
                        </p>                        
                    </div>                
                </div>  
                <!-- <div class="sp-767 mt-2">
                    <p class="d-flex mb-0">      
                        <span class="label-txt">勤務地</span>
                        <span class="txt-vertical-ellipsis">{{data.country_name}}</span>
                    </p>  
                    <p class="date mt-1 mb-0">
                        <span class="label-txt pr-1 mb-1">掲載日</span>{{data.job_post_date}}
                    </p>
                </div>    -->
                <div class="col-12 p-0 tag-gp">
                    <span v-if="data.other_keywords != ''">
                        <span v-for="keyword in data.other_keywords" :key="keyword.id" :class="keyword ? 'tag mr-1': ''">{{keyword}}</span>
                    </span>
                </div> 
                <slot></slot>                 
            </div>
        </div>
    
</template>

<script>
import { mapGetters  } from "vuex";
    export default {
        props: ['data'],
         computed: {
            ...mapGetters(["currentUser", "authError"])
         }
    }
</script>
<style lang="scss" scoped>
.tag-gp {
    width: 100%;
    height: 30px;
}
.date{
    margin-bottom: 5px;
    color: #86a4ac;
}
.public-wrapper  .new-job-block {
    height: 310px;
} 
.job-detatil-top .tag-gp {
    height: auto;
}
.sp-767{
    display:none !important;
}
.pc-767{
    display: flex !important;
}
@media screen and (max-width:767px){  
.sp-767{
    display:block !important;
}
.pc-767{
    display: none !important;
}
.public-wrapper .new-job-block{
    height: auto;
}
.job-wrapper .new-job-block .data-block{
    height: auto;
}
.tag-gp{
    height: auto;
}
.job-wrapper .new-job-block .btn {    
    padding: 0.5rem;
    min-width: 90px;
    width: auto;
}
.show-status { 
    padding: 7px 0px;  
    min-width: 60px;  
}
}
</style>

