<template>
    <div class="user-main">
        <h3>プロフィール編集</h3>  
            <div class="col-12 tab-list sticky-top w-100 p-0" v-if="showHide" v-on:scroll="handleScroll">
                <ul class="list-group list-group-horizontal">   
                <li v-for="(item, index) in items" class="list-group-item" :key="index.id">                                          
                    <a  v-scroll-to="{el:'#'+(item,index),offset:-70}"
                    :id="item.text"                          
                    :class="{isActive: activeIndex === index ? 'active':''}"                       
                    class="list-link">{{ item.text }}</a> 
                </li>
                </ul>  
            </div>    
        <JobseekerProfile @menuShowHide="changeMenu"></JobseekerProfile>
    </div>
</template>
<script>
    import JobseekerProfile from "../jobseeker/JobseekerProfile";
    // const JobseekerProfile = () => import (/* webpackChunkName: "JobseekerProfile" */'../jobseeker/JobseekerProfile');

function elementInViewport(el) {
  var top = el.offsetTop;
  var height = el.offsetHeight;
  var bottom = top + height;

  while(el.offsetParent) {
    el = el.offsetParent;
    top += el.offsetTop;
  }

  return (
    !(top < window.pageYOffset && bottom < window.pageYOffset) &&
      !(top > (window.pageYOffset + (window.innerHeight)) && bottom > window.pageYOffset + (window.innerHeight))
  );
}
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        },
        components: { JobseekerProfile },
       
        props: ['isActive'],
        data(){
             return{
                  showHide:true,       
                  items: {
                     selfIntroEdit:{text:'自己紹介',id:1},
                     basicInfoEdit:{text:'基本情報',id:2},
                     careerEdit:{text:'経歴',id:3},
                     expQualificationEdit:{text:'経験・資格',id:4},
                     desiredConditionEdit:{text:'希望条件',id:5},                             
                 },    
                 activeIndex: null,  
            }
        },
        created(){
                // this.$store.dispatch('user_image');
                // this.$store.dispatch('user_info');
               window.addEventListener('scroll', this.handleScroll);
           },
        destroyed () {
                window.removeEventListener('scroll', this.handleScroll);
            },
        methods:{        
           
        //    ScrollTab(index) {   
                    
        //         if (this.activeIndex === index) {
        //             this.activeIndex = null;               
        //         } else {
        //             this.activeIndex = index;                    
        //         }
        //     },
            handleScroll (index) {
           	
                 let id = ['careerEdit','expQualificationEdit','desiredConditionEdit','selfIntroEdit','basicInfoEdit']
                 const elementsInViewArray = id.map(String => {
                 const el = document.getElementById(String);
                 if(elementInViewport(el)) {
                    return String;
                    }
                 });
                
                 this.activeIndex = elementsInViewArray.find(String => String)
 
            },
            changeMenu(load){
                this.$store.dispatch('user_image');
                this.$store.dispatch('user_info');
                this.showHide = load;
            }   
        }
        
    }
</script>
<style style="scss" scoped>   
    .isActive{
        color: #0062ff !important;
        font-weight: bold;       
        border-bottom: 1px solid #0062ff;     
    }
    .tab-list {
        margin: 20px 0;
        background: #fff;
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }
    .tab-list .list-group-item {
        border: none;
    }
    .list-link {
        color: #3377b2;
        cursor: pointer;
    }
    .tab-content {
        padding: 50px 20px;
        background: #fff;
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
        border-radius: 5px !important;
    }
    .tit-box {
        position: relative;
        display: flex;
        margin-bottom: 40px;
        border-bottom: 2px solid #c3c4c3;
    }
    .profile-edit-tit {
        font-size: 1.2rem;
        line-height: 0.8;
    }
    .icon {
        display: inline-block;
        margin-right: 5px;
        font-size: 40px;
    }
    .profile-edit-txt {
        position: absolute;
        top: -20px;
        right: 0;
        font-size: 16px;
        color: #3377b2;
        cursor: pointer;
    }
    .tit-box-edit .profile-edit-txt {
        top: -27px;
        padding: 8px 20px;
        border-radius: 50px;
        color: #919191;
        border: 1px solid;
    }

    .about-tit {
        color: #666766;
    }
    .about-box {
        min-height: 100px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px 20px;
        line-height: 2;
        border: 1px solid #ddd;
        margin: 15px 0 30px 0;
    }
 
    .gender-choice.disable {
        color: #d5d5d5;
        background: #fff;
    }
    .gender-choice .check-icon {
        padding-right: 10px;
    }

    .r-5 {
        right: 5px !important;
    }

@media (max-width:767px){        
    .list-group-horizontal > .list-group-item:first-child{
        padding-left: 0px !important;
    }
    .list-group-horizontal>.list-group-item:last-child{
        border-bottom-left-radius: 0px;
        border: none !important;
    } 
    .list-group-item{
        padding: 15px 2px;
        width: 20%;
        text-align: center;
        background: #fff;
        border-right: 1px solid #999898 !important;
        font-size: 12px;
    }
}
@media (max-width: 320px) { 
    .list-group-item{
         font-size: 10px;
    }
} 
</style>
