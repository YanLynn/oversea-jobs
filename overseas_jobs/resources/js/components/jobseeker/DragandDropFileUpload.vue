<template>
<div class="row col-12">
  
  <div class="col-4 form-group">
    <div @drop.prevent="addFile($event,'drop',1)" @dragover.prevent class="drag-wrapper">

      <div v-show="isShow">
        <h2> Drag files to upload</h2>
        <h4> OR </h4>
        <div>
            <input type="file" name="imageUpload" id="imageUpload" @change="addFile($event,'click',1)" class="hide" multiple/> 
            <label  for="imageUpload" class="btn btn-primary">Select file</label>
        </div>
      </div>

      <div class="row col-12">
        <div v-for="(arr,index) in arrayone" :key="arr.id" class="col-4 img-wrpper">
          <button @click="removeFile(index,1)" title="Remove" class="closeicon">X</button> 
              <div v-if="arr.photo.includes('png') || arr.photo.includes('jpg')">
                  <img :src="arr.path" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
              </div>
              <div v-if="arr.photo.includes('pdf') || arr.photo.includes('pdf')">
                <img :src="'/upload/extension/file-pdf.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
              </div>
              <div v-if="arr.photo.includes('docx') || arr.photo.includes('doc')">
                <img :src="'/upload/extension/microsoftword.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
              </div>
                <div v-if="arr.photo.includes('xlsx') || arr.photo.includes('xls')">
                <img :src="'/upload/extension/microsoftexcel.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
              </div>  
        </div>
      </div>
    </div>

    <div v-show="!isShow">
      <button style="margin-left:0px;margin-top:20px" :disabled="uploadDisabled" @click="upload(1) " class="btn btn-success"> Upload</button>
    </div> 
  </div>

  <div class="col-4 form-group">
    <div id="app" v-cloak @drop.prevent="addFile($event,'drop',2)" @dragover.prevent class="drag-wrapper">
          <div v-show="isShow1">
              <h2> Drag files to upload</h2>
              <h4 > OR </h4>
              <div >
                  <input type="file" name="imageUpload1" id="imageUpload1" @change="addFile($event,'click',2)" class="hide" multiple/> 
                  <label  for="imageUpload1" class="btn btn-primary">Select file</label>
              </div>
          </div>

          <div class="row col-12">
            <div v-for="(arr,index) in arraytwo" class="col-4 img-wrpper" :key="arr.id">
                <button @click="removeFile(index,2)" title="Remove" class="closeicon">X</button> 
                <div v-if="arr.photo.includes('png') || arr.photo.includes('jpg')">
                  <img :src="arr.path" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
                </div>
                <div v-if="arr.photo.includes('PDF') || arr.photo.includes('pdf')">
                  <img :src="'/upload/extension/file-pdf.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
                </div>
                <div v-if="arr.photo.includes('docx') || arr.photo.includes('doc')">
                  <img :src="'/upload/extension/microsoftword.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
                </div>
                <div v-if="arr.photo.includes('xlsx') ||  arr.photo.includes('xls')">
                  <img :src="'/upload/extension/microsoftexcel.svg'" alt="img" class="profile_logo m-b-8 img-fluid fit-image-profile">
                </div>
            </div>
          </div>
        
        
    </div>
     <div v-show="!isShow1">
      <button style="margin-left:0px;margin-top:20px" :disabled="uploadDisabled1" @click="upload(2) " class="btn btn-success"> Upload</button>
    </div> 
  </div>
</div>



</template>

<script>
// import jsPDF from 'jspdf'
// import GoogleMap from './GoogleMap.vue'

export default {
          data() {
            return {
                files:[],
                files1:[],
                arrayone:[],
                arraytwo:[],
                isShow:true,
                isShow1:true,
            }
          },
        computed: {
        uploadDisabled() {
            return this.files.length === 0;
        },
         uploadDisabled1() {
            return this.files1.length === 0;
        },
         
        
    }, 
    methods:{
          
          addFile(e,action,type) {
          
            if(action == 'click')
            {
              var action = e.target.files;
            }
            else{
              var action = e.dataTransfer.files;
            }
    
            if(type == 1)      
            {
           
                this.isShow=false;
                this.files = [];
                for(var i=0; i< action.length; i++)  {
                    this.files.push(action[i]);
                    this.arrayone.push({photo:action[i].name, path:URL.createObjectURL(action[i]), file:this.files[i]}); 
                }
            }   
            else{
           
                this.isShow1=false;
                // this.files1 = [];
                  for(var i=0; i< action.length; i++) {
                    this.files1.push(action[i]);
                    this.arraytwo.push({photo:action[i].name, path:URL.createObjectURL(action[i]), file:this.files1[i]});
                }  
            }      
        },
        removeFile(index,type){          
           if(type == 1)
           {
              this.arrayone.splice(index,1); 
              if(this.arrayone.length == 0)
              {
                this.isShow = true;
              }  
           }
           else{
               this.arraytwo.splice(index,1);   
               if(this.arraytwo.length == 0)
               {
                 this.isShow1 = true;
               } 
           }
               
        },
        upload(type) {
       
                let fd = new FormData();
                if(type == 1)
                {
                  for(var i = 0; i< this.arrayone.length; i++) {
                      if(this.arrayone[i]['path']!=''){
                          fd.append(i,this.arrayone[i]["file"] )
                      }
                  }
                
                }
                else{
                  for(var i = 0; i< this.arraytwo.length; i++) {
                      if(this.arraytwo[i]['path']!=''){
                          fd.append(i,this.arraytwo[i]["file"] )
                      }
                  }
                }
                  
             
               this.axios.post('/api/movefile/',fd).then(response=>{
                  
                });

        }
  }

}
</script>


<style scoped>
.hide{display:none;}
.drag-wrapper{
  display: block;
  border: 1px dashed #2d4f9f;
  text-align: center;
  padding: 20px;
}
.closeicon{
  position: absolute;
  right: 15px;
  border: none;  
  font-size: 12px !important;
  font-weight: bold;
}
.img-wrpper{
  padding: 15px 0px;
}
.fit-image-profile{
  width: 146px;
  height: 96px;
}
</style>