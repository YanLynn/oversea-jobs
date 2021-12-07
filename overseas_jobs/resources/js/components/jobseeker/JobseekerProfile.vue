<template>
    <div class="profile-body">        
        <form enctype="multipart/form-data">
            <!-- self-intro -->
            <div class="row tab-content introduction-content mb-3 m-0" id="selfIntroEdit" v-if="!selfIntroEdit && showDetails">
                <!-- <div class="col-12"><h2 class="header mb-4"> {{selfIntroDetails.jobseeker_nick_name}} </h2></div> -->
                <div class="col-12">                  
                   <!-- <div class="intro-tit tit-box">-->
                    <div class="tit-box">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.waitingscout')}} </h3>
                        <p class="profile-edit-txt" @click="editBox('selfIntroEdit','open')"><span class="icon icon-edit align-text-bottom"></span>{{$t('common.edit')}}</p>
                    </div>
                    <div class="row movie-row">
                        <div class="col-12 col-md-7">
                            <div class="card-carousel row mt-3">
                                <!--image sliders-->
                                <transition name="list">
                                    <div class="col-9 pr-0">
                                        <div class="row col-12 face-img-block img-wrapper">                                      
                                            <img v-if="currentImage" :src="currentImage" alt="faceimage" class="img-fluid img-cursor"  @click="imagePopup(currentImage)"/>
                                        </div>                                       
                                        <div class="row col-12">                                            
                                            <div class="col-md-4 offset-md-8 pr-0 text-right face-image-wrapres">
                                                <div :class="['face-image' , (activeImage == 4) ? 'active' : '']" @click="activateImage('face_image',4) ">
                                                   <img :src="selfIntro.face_image_url" alt="face-image" class="img-fluid cursor-pointer img-contain"/> 
                                                   <p class="show-info">顔写真</p>                                                   
                                                </div>
                                            </div>
                                        </div>
                                         <!-- popup images-->  
                                            <div class="img-modal-wrapper" v-if="showModal">
                                                <div class="img-modal-block">
                                                    <div class="img-modal-container">
                                                    <button class="cross-btn" @click="showModal = false">
                                                            <i class="fa fa-times pr-1"></i><span>閉じる</span></button>                                            
                                                        <img :src="this.imgUrl" class="img-popup"/>                                        
                                                    </div>
                                                </div>
                                            </div>
                                                                   
                                        <!--end popup images-->
                                        <!--
                                            <div class="actions">
                                                <span @click="prevImage" class="prev">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                                <span @click="nextImage" class="next">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </div>
                                            -->
                                    </div>
                                </transition>
                                
                                <div class="thumbnails col-3 text-center">
                                    <transition-group tag="span" name="list">
                                           <span v-for="(image, index) in  selfIntro.related_images" :key="image.id" :class="['thumbnail-image', (activeImage == index) ? 'active' : '']" @click="activateImage('related',index) ">
                                            <img :src="image.file_url" class="cursor-pointer" alt="related-images"/>                                           
                                           </span>
                                     </transition-group>     

                                    <span v-for="(n,index) in 4 - selfIntro.related_images.length" :key="n.id" :class="['thumbnail-image', (activeImage == selfIntro.related_images.length + index) ? 'active' : '']" @click="activateImage('related',selfIntro.related_images.length+index) ">
                                        <img :src="defaultImageUrl" class="cursor-pointer" alt="related-images"/>                                           
                                    </span>    
                                     <p class="show-info mt-0">関連画像</p>
                                </div>

                                <!--end image slider-->
                            </div>
                        </div>
                        <div class="col-12 col-md-5 mt-3 text-right">
                            <div v-if="!selfIntroDetails.video" class="movie-wrapper">
                                <p class="no-video movie-link">
                                <!-- <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 動画は利用できません -->
                                 <img src="/images/youtube.png" alt="動画なし" class="img-fluid">  
                                </p>
                            </div>                            
                            <div v-if="selfIntroDetails.video" class="movie-wrapper">
                                <iframe class="movie-link" :src="selfIntroDetails.video" width="530" height="315"></iframe>                              
                            </div>           
                            <p class="show-info">関連動画</p>
                        </div>
                    </div>
                    <!--info-->
                      <dl class="detail-list clearfix intro-row p-0">                       
                        <dt class="detail-head">希望職種</dt>
                        <dd class="detail-data"> 
                            <span v-if="desired_condition.occupation_name">
                                <span v-for="occupation in desired_condition.occupation_name" v-bind:key="occupation.id">{{occupation.occupation_name}} <br></span>
                            </span>
                            <span  v-if="desired_condition.occupation_name.length == 0 && desired_condition.desired_occupation_status == 0">未入力</span>
                            <span v-if="desired_condition.desired_occupation_status == 1">こだわらない </span>
                        </dd>
                        <!-- <dd class="detail-data" v-else>未入力</dd> -->

                        <dt class="detail-head">希望勤務地</dt>
                        <dd class="detail-data">
                            <table class="history-edit-tbl">
                                <tr>
                                    <td>第一希望</td>
                                    <td>
                                        <span v-if="selfIntroDetails.desired_location_1 != null">{{selfIntroDetails.desired_location_1}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第二希望</td>
                                    <td>
                                        <span v-if="selfIntroDetails.desired_location_2 != null">{{selfIntroDetails.desired_location_2}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第三希望</td>
                                    <td>
                                        <span v-if="selfIntroDetails.desired_location_3 != null">{{selfIntroDetails.desired_location_3}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                            </table>                           
                        </dd>
                         

                        <dt class="detail-head">語学レベル</dt>
                        <dd class="detail-data">
                            <!-- <span v-if="selfIntroDetails.language_level">
                                <span v-for="foreign_language_level in selfIntroDetails.language_level" :key="foreign_language_level.id">
                                    {{foreign_language_level}} <br>                                 
                                </span>
                            </span>
                            <span v-else>未入力</span> -->

                            <div v-for="foreign_language_level in experience_qualification.foreign_language_level_experiences" :key="foreign_language_level.id">
                                <span v-if="foreign_language_level.foreign_language != '' && foreign_language_level.foreign_language != null">
                                    <span v-for="language in languages" :key="'language'+language.id">
                                        <span v-if="language.id == foreign_language_level.foreign_language">{{language.language_name}}</span>
                                    </span>
                                    <span v-if="foreign_language_level.language_level != null">・{{foreign_language_level.language_level}}</span>
                                </span>
                                <span v-else>未入力</span>
                            </div>

                        </dd>
                      </dl>
                    <!-- info-->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="about-tit">{{$t('jobseekerprofile.instruction')}}</h6>
                            <pre class="about-box">{{selfIntro.self_pr}}</pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row tab-content introduction-content mb-3 m-0" v-if="selfIntroEdit">
                <div class="col-12">
                    <div class="tit-box tit-box-edit" style="margin-bottom:15px;">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.waitingscout')}}</h3>
                        <p class="profile-edit-txt" @click="editBox('selfIntroEdit','close')"><i class="fa fa-times pr-1"></i>{{$t('common.close')}}</p>
                    </div>
                    <label class="info"><span class="glyphicon glyphicon-info-sign"></span>
                        「希望職種・希望勤務地→希望条件」「語学レベル→経験・資格」にて設定してください。
                    </label>
                    <!-- <div class="popup-databox">
                        <div class="form-group">
                            <label for="" class="mb-3">{{$t('jobseekerprofile.nickname')}} (スカウト向けの情報に本名の代わりに表示されます)</label>
                            <div class="col-md-6 p-0 m-auto">     
                            <input type="text" class='form-control' v-model="selfIntro.jobseeker_nick_name" />
                            </div>
                        </div>
                    </div> -->
                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="" class="mb-3">{{$t('jobseekerprofile.facephoto')}}</label><br/>
                            <span class="label-txt">{{$t('jobseekerprofile.faceimagesize')}}</span>

                            <div class="col-md-12 p-0 drag-wrapper mt-2" @drop.prevent="changeFaceImage($event)" @dragover.prevent>
                                <div class="upload-content">
                                    <p>
                                        <span class="icon icon-upload"><span class="path1"></span><span class="path2"></span></span>
                                    </p>
                                    <label for="" class="pl-3 pr-3">{{$t('jobseekerprofile.dragphoto')}}&amp;{{$t('jobseekerprofile.dragphoto2')}}</label>      
                                    <div class="upload-btn-wrapper">
                                        <span class="btn upload-btn">{{$t('jobseekerprofile.selectfiles')}}</span>
                                        <input type="file" class="upload-file hide" name="face_image" accept="image/x-png,image/jpeg" @change="changeFaceImage($event)" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-7">                                
                                <div class="img-wrapper face-image-wrapper img-wrapper-res">
                                <img :src="selfIntro.face_image_url" class="img-fluid" />
                                <!-- {{selfIntro.face_image.width}} -->
                                <button v-show="selfIntro.face_image_url != defaultImageUrl" type="button" @click="deleteFacImage" class="delete-btn" title="削除"><span class="glyphicon glyphicon-remove"></span></button>
                                
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <h3 class="intro-tit">{{$t('jobseekerprofile.toppages')}}</h3>
                                <p class="custom-radio-group">
                                    <input type="radio" id="overseas" value="1" name="radio-group" class="custion-radio" v-model="selfIntro.face_image_private_status" />
                                    <label for="overseas" class="custom-radio-lable">{{$t('jobseekerprofile.release')}}</label>
                                </p>
                                <p class="custom-radio-group">
                                    <input type="radio" id="japan" value="0" name="radio-group" checked class="custion-radio" v-model="selfIntro.face_image_private_status" />
                                    <label for="japan" class="custom-radio-lable">{{$t('jobseekerprofile.private')}}</label>
                                </p>
                                
                            </div>
                        </div>
                    </div>
                    <!-- @drop.prevent="addFile($event,'drop','profile')" @dragover.prevent -->
                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="" class="mb-3">{{$t('jobseekerprofile.relatedimages')}}</label><br>
                            <span class="label-txt">{{$t('jobseekerprofile.imagesize')}}</span>
                            <div class="col-md-12 p-0 mt-2" @drop.prevent="changeRelatedImages($event)" @dragover.prevent>
                                <div class="upload-content">
                                    <p>
                                        <span class="icon icon-upload"><span class="path1"></span><span class="path2"></span></span>
                                    </p>
                                    <label for="" class="pl-3 pr-3">{{$t('jobseekerprofile.dragphoto')}}&amp;{{$t('jobseekerprofile.dragphoto2')}}</label>
                                    
                                    <div class="upload-btn-wrapper">
                                        <button class="upload-btn">{{$t('jobseekerprofile.selectfiles')}}</button>
                                        <input type="file" class="upload-file" name="myfile" @change="changeRelatedImages($event)" multiple />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row p-0">
                            <div class="col-6 col-sm-3 profile-edit-wrap" v-for="(path,indx) in selfIntro.related_images" :key="path.id">
                                <img :src="path.file_url" width="100px;" height="150px;" class="profile-edit-img img-fluid" alt="" />
                                <button type="button" @click="deleteRelatedImage(indx)" class="delete-btn" title="削除"><span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                            <div v-if="imageError != ''">
                                <span class="error"> {{imageError}} </span>
                            </div>
                        </div>
                    </div>
                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="" class="mb-3">{{$t('jobseekerprofile.relatedvideos')}}</label>
                            <div class="row">
                                <label for="YouTubeURL" class="col-2 label-txt mt-2">YouTubeURL</label>
                                <div class="col-12 col-sm-10">
                                <input type="text" :class="['form-control',$v.selfIntro.video.$error?'is-invalid':'']" v-model.trim="$v.selfIntro.video.$model" />
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.selfIntro.video.matchYoutubeUrl">URLの形式が正しくありません</div>
                                </div>
                                </div>
                                <!-- <input type="text" class="form-control ml-3"  v-model="selfIntro.video"> -->
                            </div>
                        </div>
                    </div>

                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="" class="mb-3">{{$t('jobseekerprofile.selfpr')}}</label>
                            <div class="col-12 p-0">
                                <textarea v-model="selfIntro.self_pr" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="w-100 text-center mt-3">
                    <span class="btn appendbtn btn-large border-style" @click="saveSelfIntro">{{$t('common.save')}}</span>
                </p>
            </div>
            <!-- end self-intro -->

            <!-- basic-info -->
            <div class="row tab-content information-content mb-3 m-0" id="basicInfoEdit" v-if="!basicInfoEdit && showDetails">
                <div class="col-12">
                    <div class="tit-box">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.basicinfo')}}</h3>
                        <p class="profile-edit-txt" @click="editBox('basicInfoEdit','open')"><span class="icon icon-edit align-text-bottom"></span>{{$t('common.edit')}}</p>
                    </div>
                    <dl class="detail-list clearfix">
                       
                        <dt class="detail-head">{{$t('jobseekerprofile.jobseeker_number')}}</dt>
                        <dd class="detail-data" v-if="basicInfo.jobseeker_number != ''">{{basicInfo.jobseeker_number}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.jobseeker_name')}}</dt>
                        <dd class="detail-data" v-if="basicInfo.jobseeker_name != ''">{{basicInfo.jobseeker_name}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.furigana')}} 
                            <span v-if="basicInfo.jobseeker_furigana_name_status == 1" class="private">{{$t('jobseekerprofile.private')}}</span>
                        </dt>
                        <dd class="detail-data" v-if="basicInfo.jobseeker_name != ''">{{basicInfo.jobseeker_furigana_name}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.gender')}}
                             <span v-if="basicInfo.gender_status == 1" class="private">{{$t('jobseekerprofile.private')}}</span>
                        </dt>
                        <dd class="detail-data" v-if="basicInfo.gender != '' && basicInfo.gender != null">{{basicInfo.gender}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                       <!-- {{checkDOB}} -->
                        <dt class="detail-head">{{$t('jobseekerprofile.date')}} 
                            <span v-if="basicInfo.dob_status == 1" class="private">{{$t('jobseekerprofile.private')}}</span>
                        </dt>
                        <!-- <dd class="detail-data" v-if="basicInfo.dob != '' && checkDOB != '0000,00,00'">{{basicInfo.dob[0]}}年 {{basicInfo.dob[1]}}月 {{basicInfo.dob[2]}}日</dd> -->
                        <dd class="detail-data" v-if="basicInfo.dob">{{basicInfo.dob}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.Language')}}</dt>
                        <!-- {{basicInfo}} -->
                        <dd class="detail-data" v-if="basicInfo.language_name != null">{{basicInfo.language_name}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
  
                        <dt class="detail-head">{{$t('jobseekerprofile.location')}}
                            <span v-if="basicInfo.current_address_status == 1" class="private">{{$t('jobseekerprofile.private')}}</span> 
                        </dt>
                        <dd class="detail-data" v-if="basicInfo.country_id != '' && basicInfo.country_id != null">{{basicInfo.country_name}} <span v-if="basicInfo.city_name">, {{basicInfo.city_name}}</span></dd>
                        <dd class="detail-data" v-else>未入力</dd>

                        <dt class="detail-head">住所詳細
                            <span class="view-permission">{{$t('jobseekerprofile.admin')}}</span>
                        </dt>
                        <dd class="detail-data" v-if="basicInfo.address != '' && basicInfo.address != null">{{basicInfo.address}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>

                        <dt class="detail-head">{{$t('jobseekerprofile.phone')}}<span class="view-permission">{{$t('jobseekerprofile.admin')}}</span></dt>
                        <dd class="detail-data" v-if="basicInfo.phone != ''">{{basicInfo.phone}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.email')}}<span class="view-permission">{{$t('jobseekerprofile.admin')}}</span></dt>
                        <dd class="detail-data" v-if="basicInfo.email !=''">{{basicInfo.email}}<br /></dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.skype')}}<span class="view-permission">{{$t('jobseekerprofile.admin')}}</span></dt>
                        <dd class="detail-data" v-if="basicInfo.skype_account != '' && basicInfo.skype_account != null">{{basicInfo.skype_account}}<br /></dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.education')}}</dt> 
                        <dd class="detail-data" v-if="basicInfo.final_education != '' && basicInfo.final_education != null">{{basicInfo.final_education}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dd class="detail-head">{{$t('jobseekerprofile.status')}}</dd>
                        <dd class="detail-data" v-if="basicInfo.current_situation != '' && basicInfo.current_situation !=null">{{basicInfo.current_situation}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                    </dl>
                    <div class="explation-note">
                        <span class="private">{{$t('jobseekerprofile.private')}}</span> {{$t('jobseekerprofile.details')}}<br>
                        <span class="view-permission">{{$t('jobseekerprofile.admin')}}</span>{{$t('jobseekerprofile.detailsadmin')}}
                    </div>
                </div>
            </div>
            <div class="row tab-content information-content mb-3 m-0" v-if="basicInfoEdit">
                <div class="col-12">
                    <div class="tit-box tit-box-edit">
                        <h3 class="profile-edit-tit">基本情報</h3>
                        <p class="profile-edit-txt" @click="editBox('basicInfoEdit','close')"><i class="fa fa-times pr-1"></i>{{$t('common.close')}}</p>
                    </div>
                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.jobseeker_name')}} </label>
                            <div class="col-md-8 pl-0">
                                <input type="text" class="form-control" :class="['form-control',$v.basicInfo.jobseeker_name.$error?'is-invalid':'']" v-model.trim="$v.basicInfo.jobseeker_name.$model" />
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.basicInfo.jobseeker_name.required">{{$t('jobseekerprofile.name_req')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">                           
                            <label >{{$t('jobseekerprofile.furigana')}} </label>
                                <!-- <span class="private ml-4">{{$t('jobseekerprofile.private')}}</span> -->                            
                            <span class="status">
                                <input type="checkbox" id="jobseeker_furigana_name_status_id" class="custom-control-input custom-checkbox" v-model="basicInfo.jobseeker_furigana_name_status" />
                                <label for="jobseeker_furigana_name_status_id" class="custom-control-label custom-checkbox-label" style="color:#636363">非公開</label>
                                
                                <!-- <input type="checkbox" name="jobseeker_furigana_name_status" id="jobseeker_furigana_name_status_id"  v-model="basicInfo.jobseeker_furigana_name_status" >  -->
                                <!-- <label  for="jobseeker_furigana_name_status_id">  -->
                                    <!-- 非公開 -->
                                    <!-- <span v-if="basicInfo.jobseeker_furigana_name_status">非公開</span> 
                                    <span v-else>  公開  </span> -->
                                <!-- </label> -->
                            </span>                           
                            
                            <div class="col-md-8 pl-0 mt-2">
                                <input type="text" :class="['form-control',$v.basicInfo.jobseeker_furigana_name.$error?'is-invalid':'']" v-model.trim="$v.basicInfo.jobseeker_furigana_name.$model" />
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.basicInfo.jobseeker_furigana_name.required">{{$t('jobseekerprofile.furigana_req')}}</div>
                                    <div class="error" v-if="!$v.basicInfo.jobseeker_furigana_name.isFurigana">{{$t('jobseekerprofile.furigana_type')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group gender-group">
                            <label for="">{{$t('jobseekerprofile.gender')}}</label>
                            <span class="status">
                                <input type="checkbox" id="gender_status_id" class="custom-control-input custom-checkbox" v-model="basicInfo.gender_status" />
                                <label for="gender_status_id" class="custom-control-label custom-checkbox-label" style="color:#636363">非公開</label>

                                <!-- <input type="checkbox"  name="gender_status"  id="gender_status_id" v-model="basicInfo.gender_status" >  -->
                                <!-- <label  for="gender_status_id">  -->
                                    <!-- 非公開 -->
                                    <!-- <span v-if="basicInfo.gender_status">非公開</span> 
                                    <span v-else>  公開  </span> -->
                                <!-- </label> -->
                            </span>
                            <div class="row col-12 p-0 m-0 mt-2">      
                                    <div class="check-item" transition="fade" @click="checkGender('女性')">
                                        <p class="m-0">
                                        <span :class="[basicInfo.gender == '女性'?'fa fa-check':'fa fa-check disabled']"></span>{{$t('jobseekerprofile.female')}}
                                       </p>
                                    </div>
                                    <div class="check-item" transition="fade" @click="checkGender('男性')">
                                        <p class="m-0">
                                        <span :class="[basicInfo.gender == '男性'?'fa fa-check':'fa fa-check disabled']"></span>{{$t('jobseekerprofile.male')}}
                                      </p>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.date')}} 
                                <!-- <span class="private ml-4">{{$t('jobseekerprofile.private')}}</span> -->
                            </label>
                            <span class="status">
                                <input type="checkbox" id="dob_status_id" class="custom-control-input custom-checkbox" v-model="basicInfo.dob_status" />
                                <label for="dob_status_id" class="custom-control-label custom-checkbox-label" style="color:#636363">非公開</label>
                                <!-- <input type="checkbox"  name="dob_status" id="dob_status_id"  v-model="basicInfo.dob_status" > 
                                <label  for="dob_status_id"> 
                                    非公開                                    
                                </label> -->
                            </span>
                            
                            <div class="row mt-2">
                                <div class="col-md-4">
                                      <input type="text" class="form-control" placeholder="生年月日" v-model="basicInfo.dob"/>
                                </div>
                                 <!-- <option v-for="year in years" :key="year">{{ year }}{{' 年'}}</option> -->
                                <!-- <div class="col-md-2">
                                    <select class="form-control" v-model="basicInfo.dobyears">
                                        <option  :value="null"> 年 </option>
                                         <option v-for="year in 100" :key="year">{{ 1920 + year}}{{' 年'}}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="basicInfo.dobmonth">
                                        <option  :value="null"> 月 </option>
                                        <option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="basicInfo.dobday">
                                     <option  :value="null"> 日 </option>
                                     <option v-for="day in 31" :key="day">{{ day }}{{' 日'}}</option>
                                    </select>
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.location')}} </label>
                            <span class="status"> 
                                <input type="checkbox" id="current_address_status_id" class="custom-control-input custom-checkbox" v-model="basicInfo.current_address_status" />
                                <label for="current_address_status_id" class="custom-control-label custom-checkbox-label" style="color:#636363">非公開</label>
                                <!-- <input type="checkbox"   name="current_address_status" id="current_address_status_id"  v-model="basicInfo.current_address_status" > 
                                <label  for="current_address_status_id"> 
                                    非公開                                    
                                </label> -->
                            </span>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-2-res">
                                  <select v-model.trim="basicInfo.country_name" @change="getCity()" class="form-control">
                                            <!-- <option value="0" disabled selected>国・地域を選択</option> -->
                                            <option :value="null" v-if="basicInfo.country_name == null" selected>国・地域を選択</option>
                                            <option selected  v-else :value="0">国・地域を選択</option>
                                            <option v-for="country in countries" :key="country.id" v-bind:value="country.country_name">
                                                {{country.country_name}}
                                            </option>
                                         
                                        </select>
                                    </div>
                                <div class="col-md-4">
                                        <select class="form-control" v-model.trim="basicInfo.city_name">
                                            <!-- <option value="0" disabled selected>都市を選択</option> -->
                                            <option :value="null" v-if="basicInfo.city_name == null" selected>都市を選択</option>
                                            <option selected  v-else :value="0">都市を選択</option>
                                            <option v-for="city in city_list" :key="city.id" v-bind:value="city.city_name">
                                                {{city.city_name}}
                                            </option>
                                             <!-- <span class="sort-desc">&#9662;</span> -->
                                        </select>
                                 </div>
                                 <div class="col-12"><span class="info-choose">※ 日本以外を選択した場合、都市は選択できません。</span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.Language')}} </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control" v-model.trim="basicInfo.language_id">
                                        <option :value="null" v-if="basicInfo.language_id  == null" selected>{{$t('jobseekerprofile.selectlang')}}</option>
                                        <option v-else :value="null" selected >{{$t('jobseekerprofile.selectlang')}}</option>
                                        <option v-for="lang in languages" v-bind:value="lang.id" :key="lang.id">{{lang.language_name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.loca_details')}} <span class="view-permission" style="padding: 6px 10px;">{{$t('jobseekerprofile.admin')}}</span></label>
                            <div class="col-md-8 pl-0 mt-2">
                                <textarea name="" id="" class="form-control" v-model="basicInfo.address"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.phone')}} <span class="view-permission" style="padding: 6px 10px;">{{$t('jobseekerprofile.admin')}}</span></label>
                            <div class="col-md-8 pl-0 mt-2">
                                <input type="text" :class="['form-control',$v.basicInfo.phone.$error?'is-invalid':'']" v-model.trim="$v.basicInfo.phone.$model" />
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.basicInfo.phone.required">{{$t('jobseekerprofile.phone_req')}}</div>
                                    <div class="error" v-else-if="!$v.basicInfo.phone.alphaNumAndValidator">{{$t('jobseekerprofile.number_only')}}</div>
                                    <!-- <div class="error" v-if="!$v.basicInfo.phone.minLength || !$v.basicInfo.phone.maxLength">{{$t('jobseekerprofile.phone_length')}}</div> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.email')}} <span class="view-permission" style="padding: 6px 10px;">{{$t('jobseekerprofile.admin')}}</span></label>
                            <div class="col-md-8 pl-0 mt-2">
                                <input type="text" :class="['form-control',$v.basicInfo.email.$error?'is-invalid':'']" v-model.trim="$v.basicInfo.email.$model" readonly="readonly"/>
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.basicInfo.email.required">{{$t('jobseekerprofile.email_req')}}</div>
                                    <div class="error" v-if="!$v.basicInfo.email.email">{{$t('jobseekerprofile.email_type')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.skype')}} <span class="view-permission" style="padding: 6px 10px;">{{$t('jobseekerprofile.admin')}}</span></label>
                            <div class="col-md-8 pl-0 mt-2">
                                <input type="text" class="form-control" v-model="basicInfo.skype_account" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.education')}} </label>
                            <div class="row">
                                <div class="col-md-4">    
                                    <div>                             
                                        <select class="form-control"  v-model.trim="basicInfo.final_education">   
                                            <option :value="null" v-if="basicInfo.final_education == null" disabled>{{$t('jobseekerprofile.finaledu')}}</option>                                                                                
                                            <option :value="null" v-else selected >{{$t('jobseekerprofile.finaledu')}}</option>
                                            <option  v-for="status in finaleducation" :key="status.id" v-bind:value="status.id">
                                                {{status.id}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.status')}} </label>
                            <div class="row">
                                <div class="col-md-4">
                                        <select class="form-control" v-model.trim="basicInfo.current_situation">
                                            <option :value="null" v-if="basicInfo.current_situation  == null" selected disabled>{{$t('jobseekerprofile.currentpos')}}</option>
                                            <option v-else :value="null" selected >{{$t('jobseekerprofile.currentpos')}}</option>
                                            <option v-for="status in currentposition" :key="status.id" v-bind:value="status.id">
                                                  {{status.id}}
                                            </option>
                                        </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="w-100 text-center mt-3">
                    <span class="btn appendbtn btn-large border-style" @click="saveBasicInfo">{{$t('common.save')}}</span>
                </p>
            </div>
            <!-- End basic-info -->
     
            <!-- career -->
            <div class="row tab-content experience-content mb-3 m-0" id="careerEdit" v-if="!careerEdit && showDetails">
                <div class="col-12">
                    <div class="tit-box">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.carrer')}}</h3>
                        <p class="profile-edit-txt" @click="editBox('careerEdit','open')"><span class="icon icon-edit align-text-bottom align-text-bottom"></span>{{$t('common.edit')}}</p>
                    </div>
                    <dl class="detail-list clearfix">
                        <dt class="detail-head">{{$t('jobseekerprofile.edbackground')}}</dt>
                        <dd class="detail-data">
                            <div style="white-space:unset" v-if="educations.length > 0">
                                <div style="white-space:unset" v-for="(edu,index) in educations" :key="edu.id">
                                    <div style="white-space:unset" v-if="(educations.length ==1 && edu.id != null) || educations.length > 1">
                                        <table class="history-edit-tbl">
                                            <tr>
                                                <td>学校名</td>
                                                <td>
                                                    <span v-if="edu.school_name">{{edu.school_name}}</span>
                                                    <span v-else> - </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>学部学科</td>
                                                <td>
                                                    <span v-if="edu.subject">{{edu.subject}}</span>
                                                    <span v-else> - </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>学位 <span class="view-permission float-right">{{$t('jobseekerprofile.admin')}}</span></td>
                                                <td>
                                                    <span v-if="edu.degree">{{edu.degree}}</span>
                                                    <span v-else> - </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>在籍期間 <span class="view-permission float-right">{{$t('jobseekerprofile.admin')}}</span></td>
                                                <td>
                                                    <span v-if="edu.from_year != null || edu.from_month != null">
                                                        <span v-if="edu.from_year != null">{{edu.from_year}}</span>
                                                        <span v-if="edu.from_month != null">{{edu.from_month}}</span>

                                                        <span v-if="edu.to_year != null  && edu.to_month != null"> ~ {{edu.to_year}}{{edu.to_month}}</span>
                                                        <span v-if="edu.to_year != null  && edu.to_month == null"> ~ {{edu.to_year}}</span>
                                                        <span v-if="edu.to_year == null  && edu.to_month != null"> ~ {{edu.to_month}}</span>
                                                        <span v-if="edu.to_year == null  && edu.to_month == null"> ~ 在籍中</span>
                                                    </span>
                                                    <span v-else>
                                                        <span v-if="edu.to_year != null || edu.to_month != null">
                                                            ~ <span v-if="edu.to_year != null">{{edu.to_year}}</span>
                                                            <span v-if="edu.to_month != null">{{edu.to_month}}</span>
                                                        </span>
                                                        <span v-else> - </span>
                                                    </span>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ステータス <span class="view-permission float-right">{{$t('jobseekerprofile.admin')}}</span></td>
                                                <td>
                                                    <span v-if="edu.education_status">{{edu.education_status}} </span>
                                                    <span v-else> - </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <hr v-if="index != (educations.length-1)">
                                    </div>
                                    <span v-else> 未入力 </span>
                                </div>
                            </div>
                            <div v-else> 未入力 </div>
                        </dd>

                        <dt class="detail-head">{{$t('jobseekerprofile.experiencecompany')}}</dt>
                        <dd class="detail-data">
                            <span v-if="(carrers.num_of_experienced_companies != '' && carrers.num_of_experienced_companies != null) || (carrers.num_of_experienced_companies == 0)"> 
                                {{carrers.num_of_experienced_companies}}    
                            </span>
                            <span v-else>未入力</span>
                        </dd>

                      
                        <!-- <dt class="detail-head">{{$t('jobseekerprofile.oldjob')}} </dt>
                        <dd class="detail-data" v-if="experiences">
                            <div v-for="exp in experiences" :key="exp.id">
                                <span v-if="exp.job_location">{{exp.job_location}} </span>
                                <span v-else>未入力</span>
                                <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span>
                            </div>
                        </dd> -->
                        <!-- {{experiences}} -->

                        <dt class="detail-head"> 勤務先  </dt>
                        <dd class="detail-data" v-if="experiences">
                            <div style="white-space:nowrap;" v-for="(exp,index) in experiences" :key="exp.id">
                                <div v-if="(experiences.length ==1 && exp.id != null) || experiences.length > 1">
                                    <!-- <label>勤務先{{index + 1}}</label> -->
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>企業名 <span class="private float-right" style="margin" v-if="exp.private_status == 1 || exp.private_status == 2">{{$t('jobseekerprofile.private')}}</span></td>
                                            <td>
                                                <span v-if="exp.job_location">{{exp.job_location}} </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>在籍期間 <span class="private float-right" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></td>
                                            <td>
                                                <span  v-if="exp.current == 0 || exp.current == null">
                                                    <span v-if="exp.from_year != '年' || exp.from_month != '月'">
                                                        <span v-if="exp.from_year != '年'">{{exp.from_year}}</span>
                                                        <span v-if="exp.from_month != '月'">{{exp.from_month}}</span>

                                                        <span v-if="exp.to_year != '年'  && exp.to_month != '月'"> ~ {{exp.to_year}}{{exp.to_month}}</span>
                                                        <span v-if="exp.to_year != '年'  && exp.to_month == '月'"> ~ {{exp.to_year}}</span>
                                                        <span v-if="exp.to_year == '年'  && exp.to_month != '月'"> ~ {{exp.to_month}}</span>
                                                        <span v-if="exp.to_year == '年'  && exp.to_month == '月'"> ~ 在籍中</span>
                                                    </span>
                                                    <span v-else> - </span> 
                                                </span>
                                                <span v-else>
                                                    <span v-if="exp.from_year != '年' && exp.from_month != '月'">{{exp.from_year}}{{exp.from_month}} ~ 在籍中</span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ポジション <span class="private float-right" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></td>
                                            <td>
                                                <span v-if="exp.position_name"> {{exp.position_name}} </span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>雇用形態 <span class="private float-right" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></td>
                                            <td>
                                                <span v-if="exp.employment_type_name"> {{exp.employment_type_name}} </span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>主な業務 <span class="private float-right" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span></td>
                                            <td>
                                                <span v-if="exp.main_duty"> {{exp.main_duty}} </span>
                                                <span v-else> - </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experiences.length-1)">
                                </div>
                                <span v-else>未入力</span>
                                
                                <!-- <span v-if="exp.job_location">{{exp.job_location}} </span>
                                <span v-else>未入力</span>
                                <span class="private" v-if="exp.private_status == 1 || exp.private_status == 2">{{$t('jobseekerprofile.private')}}</span><br>

                                <span  v-if="exp.current == 0">
                                    <span v-if="exp.from_year != '年' && exp.from_month != '月' && exp.to_year != '年'  && exp.to_month != '月' "> 
                                        {{exp.from_year}}{{exp.from_month}} ~ {{exp.to_year}}{{exp.to_month}}
                                    </span>
                                    <span v-if="exp.from_year != '年' && exp.from_month != '月' && exp.to_year == '年' && exp.to_month == '月'">
                                        {{exp.from_year}}{{exp.from_month}} ~ 在籍中
                                    </span>
                                    <span v-if="exp.from_year == '年' && exp.from_month != '月'">
                                        {{exp.from_month}} ~ 在籍中
                                    </span>
                                    <span v-if="exp.from_year != '年' && exp.from_month == '月'">
                                        {{exp.from_year}} ~ 在籍中
                                    </span>
                                    <span style="white-space:nowrap;" v-if="exp.from_year == '年' && (exp.from_month == '月') && exp.to_year == '年'  && exp.to_month == '月' ">
                                     未入力
                                    </span><span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span><br>
                                </span>
                                <span style="white-space:nowrap;" v-else>
                                     <span v-if="exp.from_year != '年' && exp.from_month != '月'">
                                        {{exp.from_year}}{{exp.from_month}} ~ 在籍中
                                    </span><span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span><br>
                                </span> -->
                                <!-- <span style="white-space:nowrap;" v-if="exp.from_year == '年' && exp.from_month == '月' && exp.to_year == '年'  && exp.to_month == '月' ">
                                    未入力
                                </span><span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span><br> -->

                               
                                <!-- <span v-if="exp.position_name || exp.employment_type_name"> {{exp.position_name}} {{exp.employment_type_name}} </span>
                                <span v-if="!exp.position_name && !exp.employment_type_name">未入力</span>
                                <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span><br>

                                <span v-if="exp.main_duty"> {{exp.main_duty}} </span>
                                <span v-else> 未入力 </span>
                                <span class="private" v-if="exp.private_status == 1">{{$t('jobseekerprofile.private')}}</span> -->
                             
                                
                                
                            </div> 
                        </dd>

                        <!-- <dt class="detail-head">position and employment_type_name  </dt>
                        <dd class="detail-data" v-if="experiences">
                            <div v-for="exp in experiences" :key="exp.id">
                               <span> {{exp.position_id}} {{exp.employment_type_id}} </span>
                            </div>
                            
                        </dd>

                        <dt class="detail-head">main_duty </dt>
                        <dd class="detail-data" v-if="experiences">
                            <div v-for="exp in experiences" :key="exp.id">
                                <span v-if="exp.main_duty"> {{exp.main_duty}} </span>
                                <span v-else> 未入力 </span>
                            </div>
                        </dd>            -->

                        <dt class="detail-head">{{$t('jobseekerprofile.annualincome')}}</dt>
                        <dd class="detail-data" v-if="carrers.last_currency != null || carrers.last_annual_income != null">
                            <span v-if="carrers.last_annual_income != null && carrers.last_annual_income != null">{{ carrers.last_annual_income}}</span> <span v-if="carrers.last_currency != null && carrers.last_currency != null">{{carrers.last_currency}}</span>
                        </dd>
                        <dd class="detail-data" v-else>未入力</dd>
                    </dl>
                    <div class="explation-note">
                        <span class="private">{{$t('jobseekerprofile.private')}}</span>{{$t('jobseekerprofile.details')}}<br>
                        <span class="view-permission">{{$t('jobseekerprofile.admin')}}</span>{{$t('jobseekerprofile.detailsadmin')}}
                    </div>
                </div>
            </div>
        
            <div class="row tab-content experience-content mb-3 m-0" v-if="careerEdit">
                <div class="head-wrap col-12">
                    <!-- <h3 class="text-left main-header header" >経歴 <span class="close-btn float-right" @click="careerEdit = !careerEdit"> X 閉じる </span></h3> -->
                    <div class="tit-box tit-box-edit">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.carrer')}}</h3>
                        <p class="profile-edit-txt" @click="editBox('careerEdit','close')"><i class="fa fa-times pr-1"></i>{{$t('common.close')}}</p>
                    </div>

                    <div class="popup-databox" >
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.edbackground')}}</h6>
                        <!-- education array -->
                        <div class="col-md-12 school-box mb-4" v-for="(edu,indx) in educations" :key="edu.id">
                        
                            <span class="close-btn float-right" @click="deleteEducation(indx)" v-if="educations.length > 1" title="削除">
                                <span class="glyphicon glyphicon-remove"></span>
                            </span>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.schoolname')}}</label>
                                <div class="col-12 col-md-8 p-0">
                                      <input type="text" class="form-control" v-model="edu.school_name" placeholder="学校名を入力"  />
                                    <!-- <input type="text" :class="['form-control',$v.educations.$each.$iter[indx].school_name.$error?'is-invalid':'']" v-model.trim="$v.educations.$each.$iter[indx].school_name.$model " placeholder="学校名を入力"  /> -->
                                </div>
                               <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].school_name.$error">{{$t('jobseekerprofile.school_name')}}</span> -->     
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.departments')}}</label>
                                <div class="col-12 col-md-8 p-0">
                                    <input type="text" class="form-control" v-model="edu.subject" placeholder="学部学科を入力" />
                                </div>
                                <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].subject.$error">{{$t('jobseekerprofile.subject')}}</span> -->
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.degree')}}</label>
                                <div class="row">
                                    <div class="col-sm-8 col-xl-4">
                                        <select class="form-control" v-model="edu.degree">
                                            <option :value="null" selected>{{$t('jobseekerprofile.selectdregree')}}</option>                                                                            
                                            <option  v-for="status in finaleducation" :key="status.id" v-bind:value="status.id">
                                                {{status.id}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experiencetype')}}</label>
                                <div class="row mb-2 date-360">
                                    <div class="date-col col-6 col-sm-4 col-xl-2">
                                        <div class="select-wrap">
                                            <select class="form-control" v-model.trim="edu.from_year">
                                                <option  :value="null">年</option>
                                                <option v-for="year in yearArr" :key="year">{{ 1920 + year}}{{' 年'}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].from_year.$error">{{$t('jobseekerprofile.from_year')}}</span> -->
                                    </div>
                                    <div class="date-col col-4 col-sm-4 col-xl-2 p-0-res">
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="edu.from_month">
                                                <option  :value="null">月</option>
                                                <option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].from_month.$error">{{$t('jobseekerprofile.from_month')}}</span> -->
                                    </div>
                                    <label class="kara-txt">から</label>
                                </div>
                                <div class="row date-360">
                                    <div class="date-col col-6 col-sm-4 col-xl-2">
                                        <div class="select-wrap">
                                             <select class="form-control" v-model="edu.to_year">
                                                <option  :value="null">年</option>
                                                <option v-for="year in yearArr" :key="year">{{ 1920 + year}}{{' 年'}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].to_year.$error">{{$t('jobseekerprofile.to_year')}}</span> -->
                                    </div>
                                    <div class="date-col col-4 col-sm-4 col-xl-2 p-0-res">
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="edu.to_month">
                                                <option  :value="null">月</option>
                                                <option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].to_month.$error">{{$t('jobseekerprofile.to_month')}}</span> -->
                                    </div>
                                    <label for="" class="kara-txt">まで</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.carstatus')}}</label>
                                <div class="row">
                                    <div class="col-sm-8 col-xl-4">
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="edu.education_status">
                                                <option :value="null" selected>{{$t('jobseekerprofile.selectstatus')}}</option>  
                                                <option value="卒業">卒業</option>
                                                <option value="卒業予定">卒業予定</option>
                                                <option value="中退">中退</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                         <!-- <span class="error" v-if="$v.educations.$each.$iter[indx].education_status.$error">{{$t('jobseekerprofile.education_status')}}</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end education array -->
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addEducation">+ {{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.workhistory')}}</h6>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.experiencepeople')}}</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" v-model="carrers.num_of_experienced_companies " placeholder="経験社数を入力" @paste="(e)=> { e.preventDefault(); return false}" @input="numberOnlyInput" @keydown="numberOnlyInput"/>
                                <!-- <span class="error" v-if="$v.carrers.num_of_experienced_companies.$error">{{$t('jobseekerprofile.num_of_exp_comp')}}</span> -->
                            </div>
                             
                        </div>

                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.oldjob')}}</h6>
                        <!-- <p class="mb-0">{{$t('jobseekerprofile.company')}}</p> -->
                        <!-- experience array -->
                        <div class="col-md-12 experience-box mb-4" v-for="(exp,indx) in experiences" :key="exp.id">

                            <!-- <div class="form-group">
                             
                                <input type="checkbox" :id="'非公開'+indx" class="custom-control-input custom-checkbox" :value='0' v-model="exp.private_status"/>
                                <label :for="'非公開'+indx" class="custom-control-label custom-checkbox-label">{{$t('jobseekerprofile.private')}}</label>
                            </div> -->
               

                            <div class="form-group">
                                <div class="row col-12 p-0 m-0 same-check">   
                                    <span class="check-txt">勤務先 {{indx+1}}  </span>
                                    <div>
                                        <div class="check-private" transition="fade" @click="checkPrivateStatus(exp.private_status,indx,1)">
                                            <p class="m-0">
                                            <span :class="[exp.private_status == 1?'fa fa-check':'fa fa-check disabled']"></span>
                                        </p>
                                        </div>
                                        <label for="非公開にする">非公開に</label>   
                                    </div>

                                    <div>
                                        <div class="check-private" transition="fade" @click="checkPrivateStatus(exp.private_status,indx,2)">
                                            <p class="m-0">
                                            <span :class="[exp.private_status == 2?'fa fa-check':'fa fa-check disabled']"></span>
                                        </p>
                                        </div>
                                        <label for="企業名のみ非公開にする">企業名のみ非公開に</label> 
                                    </div>  
                                </div>
                            </div>

                            <span class="close-btn float-right" @click="deleteExperience(indx)" v-if="experiences.length > 1" title="削除">
                                <span class="glyphicon glyphicon-remove"></span>
                            </span>

                            <div class="form-group">
                                <label for="">企業名</label>
                                <div class="col-md-8 pl-0">
                                    <input type="text" class="form-control" placeholder="勤務先を入力" v-model="exp.job_location"/>
                                </div>
                            </div>                            

                            <div class="form-group">
                                <h6 class="font-weight-bold mb-3">{{$t('jobseekerprofile.experiencetype')}}</h6>
                                <div class="form-group">
                                    <input type="checkbox" :id="'現在も在籍中'+indx" class="custom-control-input custom-checkbox" @change="yearChange(indx,exp.current)" v-model="exp.current"/>
                                    
                                    <label :for="'現在も在籍中'+indx" class="custom-control-label custom-checkbox-label">{{$t('jobseekerprofile.currentperoid')}}</label>
                                </div>
                            
                                <div class="row mb-1 date-360">
                                    <div class="date-col col-6 col-sm-4 col-xl-2">
                                            <select class="form-control" v-model="exp.from_year" @change="change('year',exp.from_year,indx)">
                                                <option  value="年">年</option>
                                                <option v-for="year in yearArrJob" :key="year">{{ 1920 + year}}{{' 年'}}</option>
                                            </select>
                                             <span class="error" v-if="exp.current == 1 && exp.from_year == '年' "> 開始年を入力してください </span>
                                    </div>
                                    <div class="date-col col-4 col-sm-4 col-xl-2 p-0-res">
                                        <select class="form-control" v-model="exp.from_month" @change="change('month',exp.from_month,indx)" >
                                                <option  value="月">月</option>
                                                <option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
                                        </select>
                                        <span class="error" v-if="exp.current == 1 && exp.from_month == '月' "> 開始月を入力してください </span>
                                    </div>
                                    <label for="" class="kara-txt">から</label>
                                   
                                </div>

                                <div class="row date-360">
                                    <div class="date-col col-6 col-sm-4 col-xl-2">
                                         <select class="form-control" v-model="exp.to_year" v-bind:disabled="(exp.current != 0 && exp.current != null) || (exp.from_year == '年' && exp.from_month == '月')">
                                                <option  value="年">年</option>
                                                <option v-for="year in yearArrJob" :key="year">{{ 1920 + year}}{{' 年'}}</option>
                                         </select>
                                    </div>
                                    <div class="date-col col-4 col-sm-4 col-xl-2 p-0-res">
                                        <select class="form-control" v-model="exp.to_month" v-bind:disabled="(exp.current != 0 && exp.current != null) || (exp.from_year == '年' && exp.from_month == '月')">
                                                <option  value="月">月</option>
                                                <option v-for="month in 12" :key="month">{{ month }}{{' 月'}}</option>
                                        </select>
                                    </div>
                                    <label for="" class="kara-txt">まで</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.position')}}</label>
                                <div class="col-sm-8 col-xl-4 pl-0">                                   
                                    <div class="select-wrap">
                                        <select v-model="exp.position_id" id="" class="form-control">
                                             <option value="0" selected>{{$t('jobseekerprofile.selectposition')}}</option>
                                             <option v-for="pos in positions" :key="pos.id" :value="pos.id">{{ pos.position_name }}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.employmentstatus')}}</label>
                                <div class="col-sm-8 col-xl-4 pl-0">
                                    <div class="select-wrap">                                      
                                        <select v-model="exp.employment_type_id" id="" class="form-control">
                                            <option value="0" selected>{{$t('jobseekerprofile.selectemployment')}}</option>
                                            <option v-for="etype in employment_types" :key="etype.id" :value="etype.id">{{ etype.employment_type_name }}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.mainbusinesscontent')}}</label>
                                <div class="col-md-8 pl-0">
                                    <textarea name="" id="" class="form-control" v-model="exp.main_duty" :placeholder="[[$t('jobseekerprofile.company') ]]"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- end experience array -->
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addExperience">+ {{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.annualincome')}}</h6>
                        <div class="form-group row">
                            <div class="col-12 col-lg-8 col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <input type="text" v-model="carrers.last_annual_income" placeholder="金額を入力" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <v-select v-model="carrers.last_currency"
                                                    :options="iso_list" 
                                                    label="iso_list"
                                                    class="form-control"
                                                    :placeholder="$t('jobseekerprofile.selectcurrency')"
                                                    :reduce="iso_list=>iso_list" 
                                                    aria-autocomplete="on">
                                                    <span slot="no-options" @click="$refs.select.open = false">
                                                    検索条件に当てはまるデータはありません
                                                    </span>
                                        </v-select>
                        
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row underline"></div> -->
                <p class="w-100 text-center mt-3">
                    <span class="btn appendbtn btn-large border-style" @click="saveCarrer">{{$t('common.save')}}</span>
                </p>
                <!-- <ul class="button-block">
                    <li>
                        <span class="btn searchbtn job-primary-color" @click="saveCarrer">保存する</span>
                    </li>
                </ul> -->
            </div>
            <!-- End career -->

            <!-- exp-qualification -->
            <div class="row tab-content qualification-content mb-3 m-0" id="expQualificationEdit" v-if="!expQualificationEdit && showDetails">
                <div class="col-12">
                    <div class="tit-box">
                        <h3 class="profile-edit-tit">経験・資格</h3>
                        <p class="profile-edit-txt" @click="editBox('expQualificationEdit','open')"><span class="icon icon-editicon icon-edit align-text-bottom align-text-bottom"></span>編集</p>
                    </div>
                    <dl class="detail-list clearfix">
                        <dt class="detail-head">経験業種・職種</dt>
                        <dd class="detail-data">
                            <div v-for="(exp_job,index) in experience_qualification.experience_jobs" :key="exp_job.id">
                                <!-- <div v-if="exp_job.experience_year != ''"> -->
                                <div v-if="(experience_qualification.experience_jobs.length ==1 && exp_job.industry_history_id != null) || experience_qualification.experience_jobs.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>経験年数</td>
                                            <td>
                                                <span v-if="exp_job.experience_year != 0 && exp_job.experience_year != null && exp_job.experience_year != ''">{{exp_job.experience_year}}年</span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>経験業種</td>
                                            <td>
                                                <span v-if="exp_job.experience_occupation != '' && exp_job.experience_occupation != null">
                                                    <span v-for="occupation in occupation_list" :key="'occupation'+occupation.id">
                                                        <span v-if="occupation.id == exp_job.experience_occupation">{{occupation.occupation_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>経験職種</td>
                                            <td>
                                                <span v-if="exp_job.experience_industry != '' && exp_job.experience_industry != null">
                                                    <span v-for="industry in industry_list" :key="'industry'+industry.id">
                                                        <div v-if="industry.id == exp_job.experience_industry">{{industry.industry_name}}</div>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ポジション</td>
                                            <td>
                                                <span v-if="exp_job.experience_position != '' && exp_job.experience_position != null">
                                                    <span v-for="position in positions" :key="'position'+position.id">
                                                        <span v-if="position.id == exp_job.experience_position">{{position.position_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.experience_jobs.length-1)">
                                    <!-- <span>{{exp_job.experience_year}}年</span>
                                    <span v-for="occupation in occupation_list" :key="'occupation'+occupation.id">
                                        <span v-if="occupation.id == exp_job.experience_occupation">{{occupation.occupation_name}}</span>
                                    </span>
                                    <span v-for="position in positions" :key="'position'+position.id">
                                        <span v-if="position.id == exp_job.experience_position">{{position.position_name}}</span>
                                    </span>
                                    <span v-for="industry in industry_list" :key="'industry'+industry.id">
                                        <div v-if="industry.id == exp_job.experience_industry">{{industry.industry_name}}</div>
                                    </span>-->
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
                        <dt class="detail-head" >留学経験</dt>
                        <dd class="detail-data">
                            <div v-for="(study_abroad,index) in experience_qualification.study_abroad_experiences" :key="study_abroad.id">
                                <!-- <div v-if="study_abroad.study_abroad_country != ''"> -->
                                <div v-if="(experience_qualification.study_abroad_experiences.length == 1 && study_abroad.study_abroad_id != null) || experience_qualification.study_abroad_experiences.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>国地域名</td>
                                            <td>
                                                <!-- {{study_abroad.study_abroad_country}} -->
                                                <span v-if="study_abroad.study_abroad_country != '' && study_abroad.study_abroad_country != null">
                                                    <span v-for="country in country_list" :key="'country'+country.id">
                                                        <span v-if="country.id == study_abroad.study_abroad_country">{{country.country_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>期間</td>
                                            <td>
                                                <span v-if="study_abroad.study_abroad_period != '' && study_abroad.study_abroad_period != null">{{study_abroad.study_abroad_period}}</span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>留学の目的</td>
                                            <td>
                                                <span>{{study_abroad.study_abroad_purpose}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.study_abroad_experiences.length-1)">
                                    <!-- <span v-for="country in country_list" :key="'country'+country.id">
                                        <span v-if="country.id == study_abroad.study_abroad_country">{{country.country_name}}</span>
                                    </span>・
                                    <span>{{study_abroad.study_abroad_period}}</span>・
                                    <span>{{study_abroad.study_abroad_purpose}}</span> -->
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
                        <dt class="detail-head">海外での勤務経験</dt>
                        <dd class="detail-data">
                            <div v-for="(working_abroad,index) in experience_qualification.working_abroad_experiences" :key="working_abroad.id">
                                <!-- <div v-if="working_abroad.working_abroad_country != ''"> -->
                                <div v-if="(experience_qualification.working_abroad_experiences.length ==1 && working_abroad.working_abroad_id != null) || experience_qualification.working_abroad_experiences.length > 1">
                                    <table class="history-edit-tbl">
                                        <tr>
                                            <td>国・地域名</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_country != '' && working_abroad.working_abroad_country != null">
                                                    <span v-for="country in country_list" :key="'country'+country.id">
                                                        <span v-if="country.id == working_abroad.working_abroad_country">{{country.country_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>期間</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_period != '' && working_abroad.working_abroad_period != null"> {{working_abroad.working_abroad_period}} </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>ポジション</td>
                                            <td>
                                                <span v-if="working_abroad.working_abroad_position != '' && working_abroad.working_abroad_position !=0 && working_abroad.working_abroad_position != null">
                                                    <span v-for="position in positions" :key="'position'+position.id">
                                                        <span v-if="position.id == working_abroad.working_abroad_position">{{position.position_name}}</span>
                                                    </span>
                                                </span>
                                                <span v-else>-</span>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr v-if="index != (experience_qualification.working_abroad_experiences.length-1)">
                                    <!-- <span v-for="country in country_list" :key="'country'+country.id">
                                        <span v-if="country.id == working_abroad.working_abroad_country">{{country.country_name}}</span>
                                    </span>・
                                    <span>{{working_abroad.working_abroad_period}}</span>・
                                    <span v-for="position in positions" :key="'position'+position.id">
                                        <span v-if="position.id == working_abroad.working_abroad_position">{{position.position_name}}</span>
                                    </span> -->
                                </div>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
                        <dt class="detail-head">就労ビザ</dt>
                        <dd class="detail-data">
                            <p v-if="experience_qualification.work_visa.status == 1">
                                <span v-if="experience_qualification.work_visa.country == '' || experience_qualification.work_visa.country == null">
                                    有り
                                </span>
                                <span v-else>
                                    <span v-for="country in country_list" :key="'country'+country.id">
                                        <span v-if="country.id == experience_qualification.work_visa.country">{{country.country_name}}</span>
                                    </span>
                                </span>                                
                            </p>
                            <p v-else>無し</p>
                        </dd>
                        <dt class="detail-head">語学レベル</dt>
                        <dd class="detail-data">
                            <div v-for="foreign_language_level in experience_qualification.foreign_language_level_experiences" :key="foreign_language_level.id">
                                <span v-if="foreign_language_level.foreign_language != '' && foreign_language_level.foreign_language != null">
                                    <span v-for="language in languages" :key="'language'+language.id">
                                        <span v-if="language.id == foreign_language_level.foreign_language">{{language.language_name}}</span>
                                    </span>
                                    <span v-if="foreign_language_level.language_level != null">・{{foreign_language_level.language_level}}</span>
                                </span>
                                <span v-else>未入力</span>
                            </div>
                        </dd>
                        <dt class="detail-head">TOEICスコア</dt>
                        <dd class="detail-data">{{experience_qualification.other_qualifications.TOEIC_score ? experience_qualification.other_qualifications.TOEIC_score : '未入力'}}</dd>
                        <dt class="detail-head">その他語学関連資料</dt>
                        <dd class="detail-data">{{experience_qualification.other_qualifications.language_qualifications ? experience_qualification.other_qualifications.language_qualifications : '未入力'}}</dd>
                        <dt class="detail-head">その他資格</dt>
                        <dd class="detail-data">{{experience_qualification.other_qualifications.qualifications ? experience_qualification.other_qualifications.qualifications : '未入力'}}</dd>
                    </dl>
                </div>
            </div>
            <div class="row tab-content qualification-content mb-3 m-0" v-if="expQualificationEdit">
                <div class="head-wrap col-12">
                    <!-- <h3 class="text-left main-header header" >経歴 <span class="delete-btn" @click="careerEdit = !careerEdit"> <span class="glyphicon glyphicon-remove"></span>{{$t('common.close')}} </span></h3> -->
                    <div class="tit-box tit-box-edit">
                        <h3 class="profile-edit-tit">経歴業種・資格</h3>
                        <p class="profile-edit-txt" @click="editBox('expQualificationEdit','close')"><i class="fa fa-times pr-1"></i>{{$t('common.close')}}</p>
                    </div>

                    <!-- Experienced job type -->
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.experience_industry_occupation')}}</h6>
                        <div class="col-md-12 school-box" v-for="(experience_job,index) in experience_qualification.experience_jobs" :key="experience_job.id">
                            <p class="delete-btn" @click="deleteExpQualification(index, experience_job.industry_history_id)" v-if="experience_qualification.experience_jobs.length > 1">
                                <span class="glyphicon glyphicon-remove"></span>
                            </p>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experience_year')}}</label>
                                <div class="col-md-3 p-0">
                                    <div class="select-wrap">
                                        <input type="text" v-model="experience_job.experience_year" placeholder="経験年数を入力" @paste="(e)=> { e.preventDefault(); return false}" @input="filterInput(index,$event)" @keydown="filterInput(index,$event)" class="form-control">
                                    </div>
                                    <!-- <div class="input-group" v-if="experience_job.experience_year.$dirty">
                                        <div class="error" v-if="!experience_job.experience_year.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_year') }) }}</div> -->
                                        <!-- <div class="error" v-if="!experience_job.experience_year.maxLength">maxlength Error</div> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experience_industry')}}</label>
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select class="form-control" v-model="experience_job.experience_industry">
                                            <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_industry') }) }}</option>
                                            <option v-for="industry in industry_list" :key="industry.id" :value="industry.id">{{industry.industry_name}}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    <!-- <div class="input-group" v-if="experience_job.experience_industry.$dirty">
                                        <div class="error" v-if="!experience_job.experience_industry.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_industry') }) }}</div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experience_occupation')}}</label>
                                <div class="col-md-8 p-0 mb-2">
                                    <div class="select-wrap">
                                        <select class="form-control" v-model="experience_job.experience_occupation">
                                            <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_occupation') }) }}</option>
                                            <option v-for="occupation in exp_occupations" :key="occupation.id" :value="occupation.id">{{occupation.occupation_name}}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    <!-- <div class="input-group" v-if="experience_job.experience_occupation.$dirty">
                                        <div class="error" v-if="!experience_job.experience_occupation.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_occupation') }) }}</div>
                                    </div> -->
                                </div>
                                <label for="">ポジション</label>
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select class="form-control" v-model="experience_job.experience_position">
                                            <option :value="null" >ポジションを選択</option>
                                            <option v-for="position in exp_positions" :key="position.id" :value="position.id">{{position.position_name}}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    <!-- <div class="input-group" v-if="experience_job.experience_position.$dirty">
                                        <div class="error" v-if="!experience_job.experience_position.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_position') }) }}</div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addExpQualification()">+&nbsp;{{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <!-- Study aboard Experience -->
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.experience_study_abroad')}}</h6>
                        <div class="col-md-12 school-box" v-for="(study_abroad_experience, index) in experience_qualification.study_abroad_experiences" :key="study_abroad_experience.id">
                            <p class="delete-btn" @click="deleteStudyAbroad(index, study_abroad_experience.study_abroad_id)" v-if="experience_qualification.study_abroad_experiences.length > 1">
                                <span class="glyphicon glyphicon-remove"></span>
                            </p>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 mb-2-res">
                                        <label for="">{{$t('jobseekerprofile.experience_country')}}</label>
                                        <div class="select-wrap">
                                       
                                            <select class="form-control" v-model="study_abroad_experience.study_abroad_country">
                                                <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_country') }) }}</option>
                                                <option v-for="country in country_list" :key="country.id" :value="country.id">{{country.country_name}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="study_abroad_experience.study_abroad_country.$dirty">
                                            <div class="error" v-if="!study_abroad_experience.study_abroad_country.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_country') }) }}</div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">{{$t('jobseekerprofile.experience_period')}}</label>
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="study_abroad_experience.study_abroad_period">
                                                <option :value="null" >期間を選択</option>
                                                <option v-for="abroad_period in study_abroad_period" :key="abroad_period.id" :value="abroad_period">{{abroad_period}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="study_abroad_experience.study_abroad_period.$dirty">
                                            <div class="error" v-if="!study_abroad_experience.study_abroad_period.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_period') }) }}</div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experience_purpose')}}</label>
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select class="form-control" v-model="study_abroad_experience.study_abroad_purpose">
                                            <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_purpose') }) }}</option>
                                            <option v-for="purpose in purpose_study_abroad" :key="purpose.id" :value="purpose">{{purpose}}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    <!-- <div class="input-group" v-if="study_abroad_experience.study_abroad_purpose.$dirty">
                                        <div class="error" v-if="!study_abroad_experience.study_abroad_purpose.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_purpose') }) }}</div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addStudyAbroadExp()">+&nbsp;{{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <!-- Experience working overseas -->
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.experience_working_abroad')}}</h6>
                        <div class="col-md-12 school-box" v-for="(working_abroad_experience, index) in experience_qualification .working_abroad_experiences" :key="working_abroad_experience.id">
                            <p class="delete-btn" @click="deleteWorkingAbroad(index, working_abroad_experience.working_abroad_id)" v-if="experience_qualification.working_abroad_experiences.length > 1">
                                <span class="glyphicon glyphicon-remove"></span>
                            </p>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 mb-2-res">
                                        <label for="">{{$t('jobseekerprofile.experience_country')}}</label>
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="working_abroad_experience.working_abroad_country">
                                                <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_country') }) }} </option>
                                                <option v-for="country in country_list" :key="country.id" :value="country.id">{{country.country_name}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="working_abroad_experience.working_abroad_country.$dirty">
                                            <div class="error" v-if="!working_abroad_experience.working_abroad_country.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_country') }) }}</div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">{{$t('jobseekerprofile.experience_period')}}</label>
                                        <div class="select-wrap">
                                            <select class="form-control" v-model="working_abroad_experience.working_abroad_period">
                                                <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_period') }) }}</option>
                                                <option v-for="overseas_period in overseas_working_period" :key="overseas_period.id" :value="overseas_period">{{overseas_period}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="working_abroad_experience.working_abroad_period.$dirty">
                                            <div class="error" v-if="!working_abroad_experience.working_abroad_period.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_period') }) }}</div>
                                        </div> -->
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="">{{$t('jobseekerprofile.experience_working_position')}}</label>
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select class="form-control" v-model="working_abroad_experience.working_abroad_position"> 
                                            <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_working_position') }) }}</option>
                                            <option v-for="position in exp_positions" :key="position.id" :value="position.id">{{position.position_name}}</option>
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    <!-- <div class="input-group" v-if="working_abroad_experience.working_abroad_position.$dirty">
                                        <div class="error" v-if="!working_abroad_experience.working_abroad_position.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_working_position') }) }}</div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addWorkingAbroadExp()">+&nbsp;{{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <!-- work visa -->
                    <div class="popup-databox">
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.work_visa')}}</label>
                            <div class="d-flex">
                                <p class="custom-radio-group mr-3">
                                    <input type="radio" id="yes" name="radio-group" v-model="experience_qualification.work_visa.status" value="1" checked class="custion-radio" @change="changeVisaStatus(1)" />
                                    <label for="yes" class="custom-radio-lable">{{$t('jobseekerprofile.yes')}}</label>
                                </p>
                                <p class="custom-radio-group">
                                    <input type="radio" id="none" name="radio-group" v-model="experience_qualification.work_visa.status" value="0" class="custion-radio" @change="changeVisaStatus(0)" />
                                    <label for="none" class="custom-radio-lable">{{$t('jobseekerprofile.none')}}</label>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.experience_country')}}</label>
                            <div class="col-md-4 p-0">
                                <div class="select-wrap">                                  
                                    <select class="form-control" v-model="experience_qualification.work_visa.country" :disabled='experience_qualification.work_visa.status == 0'>
                                        <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_country') }) }} </option>
                                        <option v-for="country in country_list" :key="country.id" :value="country.id">{{country.country_name}} </option>
                                    </select>
                                    <!-- <span class="sort-desc r-5">&#9662;</span> -->
                                </div>
                                <!-- <div class="input-group" v-if="experience_qualification .work_visa.country.$error">
                                    <div class="error" v-if="!experience_qualification.work_visa.country.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_country') }) }}</div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Foreign Language Level -->
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.experience_foreign_language_level')}}</h6>
                        <div class="col-md-12 school-box" v-for="(foreign_language_level_experience, index) in experience_qualification .foreign_language_level_experiences" :key="foreign_language_level_experience.id">
                            <p class="delete-btn" @click="deleteforeignLanguageLevel(index, foreign_language_level_experience.foreign_language_id)" v-if="experience_qualification.foreign_language_level_experiences.length > 1">
                                <span class="glyphicon glyphicon-remove"></span>
                            </p>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 mb-2-res">
                                        <label for="">{{$t('jobseekerprofile.experience_foreign_language')}}</label>
                                        <div class="select-wrap">
                                 
                                            <select class="form-control" v-model="foreign_language_level_experience.foreign_language">
                                                <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_foreign_language') }) }}</option>
                                                <option v-for="language in languages" :key="language.id" :value="language.id">{{language.language_name}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="foreign_language_level_experience.foreign_language.$dirty">
                                            <div class="error" v-if="!foreign_language_level_experience.foreign_language.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_foreign_language') }) }}</div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-4">
                                     
                                        <label for="">{{$t('jobseekerprofile.experience_language_level')}}</label>
                                        <div class="select-wrap">
                                    
                                            <select class="form-control" v-model="foreign_language_level_experience.language_level">
                                                <option :value="null" >{{ $t('jobseekerprofile.select_field', { field: $t('jobseekerprofile.experience_language_level') }) }}</option>
                                                <option v-for="lang_lvl in language_level" :key="lang_lvl.id" :value="lang_lvl">{{lang_lvl}}</option>
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                        <!-- <div class="input-group" v-if="foreign_language_level_experience.language_level.$dirty">
                                            <div class="error" v-if="!foreign_language_level_experience.language_level.required">{{ $t('jobseekerprofile.required_field', { field: $t('jobseekerprofile.experience_language_level') }) }}</div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-4">
                            <span class="btn add-btn" @click="addforeignLanguageLevel()">+&nbsp;{{$t('jobseekerprofile.add')}}</span>
                        </p>
                    </div>

                    <!-- qualification -->
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.qualifications')}}</h6>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.toeic_score')}}</label>
                            <div class="col-md-4 p-0">
                                <input type="text" placeholder="スコアを入力" v-model="experience_qualification.other_qualifications.TOEIC_score" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.other_language_qualification')}}</label>
                            <div class="col-md-8 p-0">
                                <textarea name="" class="form-control" v-model="experience_qualification.other_qualifications.language_qualifications"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.other_qualifications')}}</label>
                            <div class="col-md-8 p-0">
                                <textarea name="" class="form-control" v-model="experience_qualification.other_qualifications.qualifications"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="w-100 text-center mt-3">
                    <span class="btn appendbtn btn-large border-style" @click="saveExpQualification()">{{$t('jobseekerprofile.save')}}</span>
                </p>
            </div>
            <!-- End exp-qualification -->

            <!-- desired-condition -->
            <div class="row tab-content condition-content m-0" id="desiredConditionEdit" v-if="!desiredConditionEdit && showDetails">
                <div class="col-12">
                    <div class="tit-box">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.desiredconditions')}} </h3>
                        <p class="profile-edit-txt" @click="editBox('desiredConditionEdit','open')"><span class="icon icon-edit align-text-bottom align-text-bottom"></span>{{$t('common.edit')}}</p>
                    </div>
                    <dl class="detail-list clearfix">
                        <dt class="detail-head">{{$t('jobseekerprofile.changejobs')}}</dt>
                        <dd class="detail-data" v-if="desired_condition.job_change_reason != null && desired_condition.job_change_reason != ''">{{desired_condition.job_change_reason}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.activitystatus')}}</dt>
                        <dd class="detail-data" v-if="desired_condition.job_search_activity != '' && desired_condition.job_search_activity != null">{{desired_condition.job_search_activity}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.translocation')}}</dt>
                        <dd class="detail-data" v-if="desired_condition.main_fact_when_change != null && desired_condition.main_fact_when_change != ''">{{desired_condition.main_fact_when_change}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.timetochagejobs')}}</dt>
                        <dd class="detail-data" v-if="desired_condition.desired_change_period != null && desired_condition.desired_change_period != ''">{{desired_condition.desired_change_period}}</dd>
                        <dd class="detail-data" v-else>未入力</dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.preferredworkplace')}}</dt>
                        <dd class="detail-data"> 
                            <table class="history-edit-tbl">
                                <tr>
                                    <td>第一希望</td>
                                    <td>
                                        <span v-if="desired_condition.desired_locations_1 != null">{{desired_condition.desired_locations_1}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第二希望</td>
                                    <td>
                                        <span v-if="desired_condition.desired_locations_2 != null">{{desired_condition.desired_locations_2}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>第三希望</td>
                                    <td>
                                        <span v-if="desired_condition.desired_locations_3 != null">{{desired_condition.desired_locations_3}}</span>
                                        <span v-else>未入力</span>
                                    </td>
                                </tr>
                            </table>                          
                           
                        </dd>
                        <!-- <dd class="detail-data" v-else>未入力</dd> -->
                        <dt class="detail-head">{{$t('jobseekerprofile.preferredindustry')}}</dt>

                        <dd class="detail-data">
                            <span v-if="desired_condition.industry_name">
                                <span v-for="industry in desired_condition.industry_name" v-bind:key="industry.id">{{industry.industry_name}}<br></span>
                            </span>
                             <span  v-if="desired_condition.industry_name.length == 0 && desired_condition.desired_industry_status == 0">未入力</span>
                            <span v-if="desired_condition.desired_industry_status == 1">こだわらない </span>
                        </dd>

                        <dt class="detail-head">{{$t('jobseekerprofile.desiredoccupation')}}</dt>
                        <dd class="detail-data">
                            <span v-if="desired_condition.occupation_name">
                                <span v-for="occupation in desired_condition.occupation_name" v-bind:key="occupation.id">{{occupation.occupation_name}} <br></span>
                            </span>
                             <span  v-if="desired_condition.occupation_name.length == 0 && desired_condition.desired_occupation_status == 0">未入力</span>
                            <span v-if="desired_condition.desired_occupation_status == 1">こだわらない </span>
                        </dd>
                        <dt class="detail-head">{{$t('jobseekerprofile.desiredsalary')}}</dt>
                      <!-- 万 円 -->
                        <dd class="detail-data" v-if="!desired_condition.desired_min_annual_income && !desired_condition.desired_max_annual_income"> 未入力 </dd>
                        <dd class="detail-data" v-if="desired_condition.desired_min_annual_income && !desired_condition.desired_max_annual_income">{{ desired_condition.desired_min_annual_income }} {{desired_condition.desired_currency}}以上 </dd>
                        <dd class="detail-data" v-if="!desired_condition.desired_min_annual_income && desired_condition.desired_max_annual_income">{{ desired_condition.desired_max_annual_income }} {{desired_condition.desired_currency}} </dd>
                        <dd class="detail-data" v-if="desired_condition.desired_min_annual_income && desired_condition.desired_max_annual_income">{{desired_condition.desired_min_annual_income }} ~ {{ desired_condition.desired_max_annual_income}} {{desired_condition.desired_currency}}</dd>
                    </dl>
                </div>
            </div> 
            <div class="row tab-content condition-content m-0" v-if="desiredConditionEdit">
                <div class="col-12">
                    <div class="tit-box tit-box-edit">
                        <h3 class="profile-edit-tit">{{$t('jobseekerprofile.desiredconditions')}} </h3>
                        <p class="profile-edit-txt" @click="editBox('desiredConditionEdit','close')"><i class="fa fa-times pr-1"></i>{{$t('common.close')}}</p>
                    </div>
                    
                    <div class="popup-databox">
                        
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.changejobs')}}</label>
                            <div class="col-md-8 p-0">
                                <div class="select-wrap">
                                    <select id="" class="form-control" v-model="desired_condition.job_change_reason">
                                        <option :value="null" >{{$t('jobseekerprofile.selectjobmotivation')}}</option>
                                        <option v-for="moti in moitivation" :key="moti.id" :value="moti.id">{{ moti.id }}</option>
                                    </select>
                           
                                    <!-- <span class="sort-desc">&#9662;</span> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.activitystatus')}}</label>
                            <div class="col-md-8 p-0">
                                <div class="select-wrap">
                                    <select id="" class="form-control" v-model="desired_condition.job_search_activity">
                                        <option :value="null">{{$t("jobseekerprofile.selectactivitystatus")}}</option>
                                        <option v-for="status in activity_status" :key="status.id" :value="status.id">{{ status.id }}</option>   
                                    </select>
                                    <!-- <span class="sort-desc">&#9662;</span> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.translocation')}}</label>
                            <div class="col-md-8 p-0">
                                <div class="select-wrap">
                                    <select id="" class="form-control" v-model="desired_condition.main_fact_when_change">                                        
                                        <option :value="null">{{$t('jobseekerprofile.selecttranslocation')}}</option>
                                        <option v-for="jobs in changing_jobs" :key="jobs.id" :value="jobs.id">{{ jobs.id }}</option>  
                                    </select>
                                    <!-- <span class="sort-desc">&#9662;</span> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{$t('jobseekerprofile.timetochagejobs')}}</label>
                            <div class="col-md-8 p-0">
                                <div class="select-wrap">
                                    <select id="" class="form-control" v-model="desired_condition.desired_change_period">
                                        <option :value="null">{{$t('jobseekerprofile.selectchangejobs')}}</option>
                                        <option v-for="date in date_list" :key="date.id" :value="date">{{ date }}</option>  
                                    </select>
                                    <!-- <span class="sort-desc">&#9662;</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-databox">
                        <h6 class="font-weight-bold">{{$t('jobseekerprofile.preferredworkplace2')}}</h6>
                        <div class="col-md-12 school-box">
                            <div class="form-group">
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select id="" class="form-control" v-model="desired_condition.desired_location_1" @change="chooseLocation()">
                                            <option :value="null">{{$t('jobseekerprofile.select1stchoice')}}</option>
                                            <option v-for="city in desired_city_list" :key="city.id" :value="city.id">{{ city.country_name }}</option>  
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>   
                                    <span class="error" v-if="desired_errors.location_error">{{desired_errors.location_error}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select id="" class="form-control" v-model="desired_condition.desired_location_2" @change="chooseLocation()">
                                            <option :value="null">{{$t('jobseekerprofile.select2ndchoice')}}</option>
                                            <option v-for="city in desired_city_list" :key="city.id" :value="city.id">{{ city.country_name }}</option>  
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-md-8 p-0">
                                    <div class="select-wrap">
                                        <select id="" class="form-control" v-model="desired_condition.desired_location_3" @change="chooseLocation()">
                                            <option :value="null">{{$t('jobseekerprofile.Select3rdchoice')}}</option>
                                            <option v-for="city in desired_city_list" :key="city.id" :value="city.id">{{ city.country_name }}</option>  
                                        </select>
                                        <!-- <span class="sort-desc">&#9662;</span> -->
                                    </div>
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="popup-databox">
                        <h6 class="font-weight-bold form-group">{{$t('jobseekerprofile.preferredindustry')}}</h6>                            
                        <div class="form-group mb-0">        
                            <input type="checkbox" id="非公開0" class="custom-control-input custom-checkbox" @change="getData('industry')" v-model="desired_condition.desired_industry_status" />
                             <label for="非公開0" class="custom-control-label custom-checkbox-label"> {{$t('jobseekerprofile.notconsidering')}}</label>
                             <div class="error col-12" v-if="desired_errors.industry_error">
                                        {{desired_errors.industry_error}}
                            </div>
                            <div v-if="!desired_condition.desired_industry_status">   
                                <div  class="col-md-12 school-box mt-4" v-for="(industry,indx) in industries" :key="indx">
                                    <span v-if="industries.length != 1" class="close-btn" title="削除" @click="removeIndustry(indx)"><span class="glyphicon glyphicon-remove"></span></span>
                                    
                                        <div class="col-md-8 p-0">
                                            <div class="select-wrap">
                                                <select id=""  v-model="industry.id" @change="getData('industry')" class="form-control">
                                                    <option value="0">{{$t('jobseekerprofile.selectindustry')}}</option>
                                                    <option v-for="(indu,index) in industry_list" :key="index" :value="indu.id">{{ indu.industry_name }}</option>  
                                                </select>
                                                <!-- <span class="sort-desc">&#9662;</span> -->
                                            </div>
                                        </div>                                    
                                </div>

                                <p class="text-center mt-4">
                                    <span class="btn add-btn" @click="addIndustry">+ {{$t('jobseekerprofile.add')}}</span>
                                </p>
                            </div>
                        </div>  
                    </div>
                    <div class="popup-databox">
                        <h6 class="font-weight-bold form-group">{{$t('jobseekerprofile.desiredoccupation')}}</h6>
                        <div class="form-group mb-0">
                            <input type="checkbox" id="非公開1" class="custom-control-input custom-checkbox" @change="getData('occupation')" v-model="desired_condition.desired_occupation_status" />
                            <label for="非公開1" class="custom-control-label custom-checkbox-label">{{$t('jobseekerprofile.notconsidering')}} </label>
                        </div>
                        <div class="error col-12" v-if="desired_errors.occupation_error">
                                    {{desired_errors.occupation_error}}
                        </div>
                        <div v-if="!desired_condition.desired_occupation_status">
                            <div class="col-md-12 school-box mt-4" v-for="(occupation,index) in occupations" :key="index">
                                <span v-if="occupations.length != 1" class="close-btn" title="削除" @click="removeOccupation(index)"><span class="glyphicon glyphicon-remove"></span></span>
                                <div class="form-group mb-0">
                                    <div class="col-md-8 p-0">
                                        <div class="select-wrap">
                                            <select id="" class="form-control" v-model="occupation.id" @change="getData('occupation')">
                                                <option value="0">{{$t('jobseekerprofile.selectjobcategory')}}</option>
                                                <option v-for="(occu,indx) in occupation_list" :key="indx" :value="occu.id">{{ occu.occupation_name }}</option>  
                                            </select>
                                            <!-- <span class="sort-desc">&#9662;</span> -->
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <p class="text-center mt-4">
                                <span class="btn add-btn" @click="addOccupation">+ {{$t('jobseekerprofile.add')}}</span>
                            </p>
                            
                        </div>

                        
                    </div>
                    <div class="popup-databox">
                        <h6 class="font-weight-bold mb-3">{{$t('jobseekerprofile.desiredsalary')}}</h6>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-xl-2 pl-5">{{$t('jobseekerprofile.minannulaincome')}}</label>
                            <div class="col-sm-6 col-xl-4">
                                <input type="text"  :placeholder="[[$t('jobseekerprofile.enteramount') ]]" class="form-control" v-model="desired_condition.desired_min_annual_income " />  
                                <!-- <span class="invalid-feedback">
                                    <span  v-if="$v.desired_condition.desired_min_annual_income.$error">{{$t('jobseekerprofile.salaryreq')}}</span>
                                </span> -->
                            </div>
                        
                        </div>
                          

                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-xl-2 pl-5">{{$t('jobseekerprofile.maxannualincome')}}</label>
                            <div class="col-sm-6 col-xl-4">
                                <input type="text" class="form-control" :placeholder="[[$t('jobseekerprofile.enteramount') ]]" v-model="desired_condition.desired_max_annual_income" /> 
                            </div>
                            <!-- <div class="col-md-2" style="margin-top:5px;">
                                万
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-xl-2 pl-5"></label>
                            <div class="col-sm-6 col-xl-4">
                                <div class="select-wrap">
                                    <v-select v-model="desired_condition.desired_currency"
                                                    :options="iso_list" 
                                                    label="iso_list"
                                                    class="form-control"
                                                    :placeholder="$t('jobseekerprofile.selectcurrency')"
                                                    :reduce="iso_list=>iso_list" 
                                                    aria-autocomplete="on">
                                                    <span slot="no-options" @click="$refs.select.open = false">
                                                    検索条件に当てはまるデータはありません
                                                    </span>
                                    </v-select>
                                    <!-- <select id="" class="form-control" v-model="desired_condition.desired_currency">
                                        <option :value="null">{{$t('jobseekerprofile.selectcurrency')}}</option>
                                        <option v-for="curr in currency" :key="curr.id" :value="curr.id">{{ curr.id }}</option>  
                                    </select> -->
                                    <!-- <span class="sort-desc">&#9662;</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="w-100 text-center mt-3">
                    <span class="btn appendbtn btn-large border-style" @click="saveDesiredCondition()">{{$t('common.save')}}</span>
                </p>
            </div>
            <!-- End desired-condition -->
        </form>
    </div>
</template>
<script>
    import { required, requiredIf, maxLength, numeric, helpers, email } from "vuelidate/lib/validators";
    import { matchYoutubeUrl } from "../../partials/common";
    import Vuelidate from "vuelidate";

    const alphaNumAndValidator = (ph_number) => {
    if (typeof ph_number === 'undefined' || ph_number === null || ph_number === '') {
    return true;
        }
    return (/^[\+\d]?(?:[\+\][\d-.\s()]*)$/m).test(ph_number);
    }

    const isFurigana = (value) => {
        let allow = true;
        let charArray = value.split("");
        for (let i = 0; i < charArray.length; i++) {
            let code = charArray[i].charCodeAt();
            if (!(code > 12448 && code < 12543)) {
                allow = false;
                break;
            }
        }
        return !helpers.req(value) || allow;
    };
    const isTrueImage = (value) => {
        if (!value) {
            return true;
        }
        let file = value;
        return file.type ? file.type.startsWith("image") : true;
    };
    function buildFormData(formData, data, parentKey) {
        if (data && typeof data === "object" && !(data instanceof Date) && !(data instanceof File)) {
            Object.keys(data).forEach((key) => {
                buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
            });
        } else {
            const value = data == null ? "" : data;
            formData.append(parentKey, value);
        }
    }
    export default {
        metaInfo: {
            title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
        },
        data() {
            return {
                yearArr: (new Date().getFullYear()) - 1919,
                yearArrJob: (new Date().getFullYear()) - 1920,
                showHide:true,
                checkDOB:'',
                file_names: [],
                defaultImageUrl: "",
                showDetails: true,
                selfIntroEdit: false,
                basicInfoEdit: false,
                careerEdit: false,
                expQualificationEdit: false,
                desiredConditionEdit: false,
                facImageUrl: "",
                selfIntro: {
                    // jobseeker_nick_name:'',
                    related_images: [],
                    delete_related_images: [],
                    delete_fac_image: false,
                },
                selfIntroDetails: {},
                currentImage: "",
                imageError: "",
                employment_types: [],
                iso_list:[],
                positions: [],
                carrers: [    
                    {
                        last_annual_income : "",
                        num_of_experienced_companies : "",
                        last_currency : "",
                    }
                ],
                educations: [
                    {
                        id: null,
                        jobseeker_id: null,
                        school_name: "",
                        subject: "",
                        degree: null,
                        from_year: null,
                        from_month: null,
                        to_year: null,
                        to_month: null,
                        education_status: null,
                      
                    },
                ],
                experiences: [
                    {
                        id: null,
                        jobseeker_id: null,
                        position_id: 0,
                        employment_type_id: 0,
                        job_location: "",
                        main_duty: "",
                        from_year: "年",
                        from_month: "月",
                        to_year: "年", 
                        to_month: "月",
                        current: "",
                        private_status: 0
                    },
                ],
                //basicInfo
                basicInfo: {
                    jobseeker_number:'',
                    dob:'',
                    country_name:0,
                    city_name:0,
                    final_education :"",
                    current_situation :"",

                },
                countries: [],
                city_list: [],
                languages: [],
                disabled: 0,
                finaleducation: [{ id: "大学（学士)"}, { id: "短期大学" }, { id: "大学院（修士）" }, { id: "大学院（博士）" }, { id: "専門学校" }, { id: "高校" }, { id: "高等専門学校" }, { id: "中学校" }, { id: "その他" }],
                currentposition: [{ id: "会社員" }, { id: "大学生/大学院生/専門学校" }, { id: "語学留学生" }, { id: "主婦" }, { id: "経営者/自営業" }, { id: "無職" }, { id: "定年退職" }, { id: "その他" }],
                currency : [{ id: "円"},{ id: "元"},{ id: "USドル"},{ id: "バーツ"}],
                //basicInfo

                // (start) experience qualification
                ids_to_del_exp_quali : [],
                ids_to_del_study_abroad : [],
                ids_to_del_working_abroad : [],
                ids_to_del_language_levels : [],
                industry_list : [],
                country_list : [],
                exp_occupations : [],
                exp_positions : [],
                experience_qualification : {
                    experience_jobs: [],
                    study_abroad_experiences: [],
                    working_abroad_experiences: [],
                    //overseas_working_experiences: [],
                    foreign_language_level_experiences: [],
                    //qualifications: [],
                    work_visa : {
                        status : '',
                        country : '',
                    },
                    other_qualifications : {
                        TOEIC_score : '',
                        language_qualifications : '',
                        qualifications : '',
                    },
                },
                study_abroad_period: ['半年未満', '半年～1年', '1年～2年', '2年～3年', '4年以上'],
                purpose_study_abroad: ['語学留学', '大学', 'MBA', 'MBA以外の修士号', 'その他'],
                overseas_working_period: ['1年未満', '1年～2年', '2年～3年', '4年以上'],
                language_level: [ '挨拶程度', '日常会話', 'ビジネスレベル', 'ネイティブレベル'],
                // (end) experience qualification

                desired_industries: [],
                desired_occupations: [],
                // images: [],
                activeImage: 4,

                //desired_condition
                moitivation:[{id:"積極的に多くの企業と会いたい"},{id:"いいところがあれば会いたい"},{id:"まだ積極的には考えていない"}],
                activity_status:[{id:"特に何もしていない"},{id:"情報収集（求人サイト・求人雑誌）"},{id:"情報収集（人材会社等に登録）"},{id:"企業にエントリー済み"},{id:"一次面接・筆記試験予定がある"},{id:"最終面接予定がある"},{id:"既に内定をもらっている"}],
                changing_jobs:[{id:"特になし"},{id:"勤務地域（国、都市）"},{id:"経験を活かせる業界、職種で働きたい"},{id:"未経験・異なる業界、職種に挑戦したい"},{id:"有名企業、大手企業で働きたい"},{id:"安定性、将来性がある企業で働きたい"},{id:"新規拠点・事業の立上げに関わりたい"},{id:"給与・待遇面を改善したい"},{id:"駐在案件の海外勤務を希望"}],
                currency:[{id:'円'},{id:'元'},{id:'USドル'},{id:'バーツ'}],
                // currency:[],
                desired_city_list:[],
                industry_list:[],
                occupation_list:[],
                date_list:[],
                industries:[{
                    id:0,
                    jobseeker_id:null,
                }],
                occupations:[{
                    id:0,
                    jobseeker_id:null,
                }],
                desired_condition:{
                    occupation_name:'',
                    industry_name:'',
                    desired_min_annual_income:null,
                    desired_location_1:null
                },
                desired_errors:{
                    industry_error:'',
                    occupation_error:'',
                    location_error:'',
                    location_error_status:false
                },
              
                experience_errors:'',
                exp_year_errors:'',
                exp_month_errors:'',
                 //desired_condition
                showModal : false,
                imgUrl: '',
                loading:'',

               
           };
        },
        validations: {
            selfIntro: {
                video: { matchYoutubeUrl },
                face_image: { isTrueImage },
                // jobseeker_nick_name:{required}
            },
            basicInfo: {
                phone: {
                    required,
                    alphaNumAndValidator,
                },
                jobseeker_furigana_name: {
                    required,
                    isFurigana,
                },
                jobseeker_name: {
                    required,
                },
                email: {
                    required,
                    email,
                },
            },

            // (start) experience qualification
            // experience_qualification : {
            //     experience_jobs: {
            //         $each: {
            //             experience_year: { required, numeric, maxLength: maxLength(80) },
            //             experience_industry: { required },
            //             experience_occupation: { required },
            //             experience_position: { required },
            //         }
            //     },
            //     study_abroad_experiences: {
            //         $each: {
            //             study_abroad_country: { required },
            //             study_abroad_period: { required },
            //             study_abroad_purpose: { required },
            //         }
            //     },
            //     working_abroad_experiences: {
            //         $each: {
            //             working_abroad_country: { required },
            //             working_abroad_period: { required },
            //             working_abroad_position: { required },
            //         }
            //     },
            //     work_visa : {
            //         country : {
            //             required : requiredIf(function(){
            //                 return this.experience_qualification.work_visa.status == 1 ? true : false;
            //             })
            //         },
            //     },
            //     foreign_language_level_experiences: {
            //         $each: {
            //             foreign_language: { required },
            //             language_level: { required },
            //         }
            //     },
            // },
            // (end) experience qualification

            // desired_condition:{
            //     desired_min_annual_income: {required},
                
            // },

            study_abroad_experiences: {
                    $each: {
                        study_abroad_country: { required },
                        study_abroad_period: { required },
                        study_abroad_purpose: { required },
                    }
                },

            // carrers:{
            //     num_of_experienced_companies:{required}
            // },

            // educations :{
            //     $each:{
            //         school_name:{required},
            //         subject:{required},
            //         from_year:{required},
            //         to_year:{required},
            //         from_month:{required},
            //         to_month:{required},
            //         education_status:{required},
            //     }
              
            // }
        },
        created() {
            // this.$loading.show();
            //this.$store.dispatch('user_image');
            this.selfIntro.face_image_private_status = 0;
            this.getSelfIntroDetails();
            this.getCarrerRequiredList();
            this.getBasicInfoDetails();
            this.getCarrerDetails();
            this.getDesiredCondition();
            this.getJobIndustryExpDetails();
        },
        computed: {
            years() {
                const year = new Date().getFullYear();
                return Array.from({ length: year - 1900 }, (value, index) => year - index);
            },
            months() {
                return ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
            },
            isDisabled() {
                return this.experience_qualification.work_visa.status == 0 ? (true, this.experience_qualification.work_visa.country = '') : false;
            },
        },
        methods: {
            numberOnlyInput(evt){
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
                
                if(charCode == 8 || charCode == 35 || charCode == 36 || charCode == 37 || charCode == 39 || charCode == 46 || (charCode > 47 && charCode < 58) || (charCode > 95 && charCode < 106)){
                    return true;
                }
                else if(isNaN(evt.key) && charCode != 8){
                    evt.preventDefault();
                } 
                else{
                    evt.preventDefault();
                }
            },
            filterInput(index,evt){             
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
              
                if(charCode == 8 || charCode == 190 || charCode == 35 || charCode == 36 || charCode == 37 || charCode == 39 || charCode == 46 || (charCode > 47 && charCode < 58) || (charCode > 95 && charCode < 106) || charCode == 110){
                    return true;
                }
                else if(isNaN(evt.key) && charCode != 8){
                    evt.preventDefault();
                } 
                else{
                    evt.preventDefault();
                }
            },
            chooseLocation(){            
            
                if(this.desired_condition.desired_location_1 != null || this.desired_condition.desired_location_2 != null || this.desired_condition.desired_location_3 != null){
                
                   this.desired_errors.location_error_status = true;
                   this.desired_errors.location_error = '';
                   
                }
                else if(this.desired_condition.desired_location_1 == null && this.desired_condition.desired_location_2 == null && this.desired_condition.desired_location_3 == null){
                 
                   this.desired_errors.location_error_status = false;
                  
                }
            },

            changeVisaStatus(status){
                if(status == 0)
                    this.experience_qualification.work_visa.country = null;
            },

            getData(status)
            {
                if(status == 'industry'){
                    this.desired_errors.industry_error = '';
                }
                else{
                     this.desired_errors.occupation_error = '';
                }
            },
            checkGender(type) {
                this.basicInfo.gender = type;
            },
            checkPrivateStatus(type,index,status) {
             
                if(type == 0){
                     this.experiences[index].private_status = status;
                }
                if(type == 1){
                    if(status == 2 ){
                        this.experiences[index].private_status = status;
                    } else{
                        this.experiences[index].private_status = 0;
                    }
                }
                if(type == 2){
                     if(status == 1 ){
                        this.experiences[index].private_status = status;
                    }else{
                        this.experiences[index].private_status = 0;
                    }
                }
            },

            getCarrerRequiredList() {
                console.log("hi")
                this.$api.get("jobseeker/required-list").then((response) => {
                    console.log("hello",response.data)
                    this.positions = response.data.data.positions;
                    this.employment_types = response.data.data.employment_types;
                    this.iso_list = response.data.data.iso_list;
                });
            },

            yearChange(index,istrue)
            {
                if(istrue){
                    this.experiences[index].to_year = '年';
                    this.experiences[index].to_month = '月';

                }
            
            },  
            change(type,value,index)
            {
               
                if(type == 'year')
                {
                    if(value == '年'){
                          this.experiences[index].to_year = '年';
                    }
                   
                }else{
                     if(value == '月'){
                        this.experiences[index].to_month = '月';
                    }
                    
                }

            },

            
             //su sandy
            getSelfIntroDetails() {
                let loader = this.$loading.show();
               
                this.$api.get("/default-image").then((r) => {
                    this.defaultImageUrl = r.data.data;
                });

                this.$api.get("/jobseeker/profile/selfintro").then((r) => {
                    if(this.loading != ''){
                        this.loading.hide();
                    }
                    var lang = r.data.data.language;
            
                    this.file_names = r.data.data.hashedFile; //hashed for related_images

                    this.selfIntro = r.data.data.selfIntro; //rebind selfintro data
                  
                    this.selfIntro.delete_related_images = []; //clear deleted images

                    this.currentImage = this.selfIntro.face_image_url;

                    this.selfIntroDetails = r.data.data.selfIntroDetails; //to show selfintro details

                    if(this.selfIntroDetails.occupation_name != null) {
                        this.selfIntroDetails.occupation_name = this.selfIntroDetails.occupation_name.split(',');
                    }
                    else{
                        this.selfIntroDetails.occupation_name = [];
                    }

                    lang.filter(lan=>{
                        if(lan.language_level){
                            var split_arr = lan.language_level.split(',');
                            this.selfIntroDetails.language_level = split_arr;
                        }   
                    });

                    this.activeImage = 4;                   
                   
                });
               
                loader.hide();
            },

            //su sandy
            activateImage(type, imageIndex) {
            
                if (type == "related") {
                    this.activeImage = imageIndex;
                    if(imageIndex > this.selfIntro.related_images.length || imageIndex == this.selfIntro.related_images.length){       
                           return false;
                          //this.currentImage = this.defaultImageUrl; 
                    }else{    
                        if(this.selfIntro.related_images[imageIndex].file_url.includes('images/default.png')){
                            return false;
                        }
                        else{
                            this.currentImage = this.selfIntro.related_images[imageIndex].file_url;
                        }
                    }
                }else {
                    this.activeImage = imageIndex;
                    this.currentImage = this.selfIntro.face_image_url;
                }
            },

            //su sandy
            changeFaceImage(e) {
                this.$v.selfIntro.face_image.$touch();
                if(this.$v.selfIntro.face_image.$error) {
                    return;
                }
                const files = e.target.files || e.dataTransfer.files;
                const file = files[0];
                const ext = file.name.split(".").pop().toLowerCase(); 

                if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    
                    //  if(file.size/1024/1024 <= 3){
                        this.selfIntro.face_image = file;
                        this.selfIntro.face_image_url = URL.createObjectURL(file);
                        this.selfIntro.delete_fac_image = false;
                    //  }
                    //  else{
                    //      this.$alertService.showWarningDialog(null,　this.$t('alertMessage.imageSizeWarning'),this.$t('common.close')).then(r => { });
                    //  }
                    
                } else {
                                        
                    this.$alertService.showWarningDialog(null, this.$t('alertMessage.imageTypeWarning'),this.$t('common.close')).then(r => { });
                   
                }
                e.target.value = '';
            },

            //su sandy
            deleteFacImage() {
                // this.$alertService.showConfirmDialog(null, "Are you sure to delete logo?").then((r) => {
                //     if (r.value) {
                        this.selfIntro.face_image = "";
                        this.selfIntro.face_image_url = this.defaultImageUrl;
                        this.selfIntro.delete_fac_image = true;
                //     }
                // });
            },

            //su sandy 
            changeRelatedImages(e) { 
                const files = e.target.files || e.dataTransfer.files;
                if (files.length + this.selfIntro.related_images.length > 4) {
                    this.$alertService.showWarningDialog(null,　this.$t('alertMessage.imageMaximunWarning'),this.$t('common.close')).then(r => { });
                    return;
                }

                let taken = this.selfIntro.related_images.map((x) => {
                    return x.url.split(".")[0];
                });
                let availables = this.file_names.filter((x) => !taken.includes(x));

                const vm = this;
                Array.from(files).forEach((file, i) => {

                const ext = file.name.split(".").pop().toLowerCase(); 

                if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    // if(file.size/1024/1024 <= 3){
                        let filename = availables[i];
                        let extension = file.type.split("/").pop();
                        let entry = {
                            file: file,
                            url: `${filename}.${extension}`,
                            file_url: URL.createObjectURL(file),
                            file_type: "photo",
                            user_type: "jobseeker",
                        };
                        vm.selfIntro.related_images.splice(i, 0, entry);
                        let deleteFlagIndex = vm.selfIntro.delete_related_images.indexOf(filename);
                        if (deleteFlagIndex == -1) vm.selfIntro.delete_related_images.splice(deleteFlagIndex, 1);
                    // }
                    // else{
                    //     this.$alertService.showWarningDialog(null,　this.$t('alertMessage.imageSizeWarning'),this.$t('common.close')).then(r => { });
                    // }
                }
                else{
                    this.$alertService.showWarningDialog(null, this.$t('alertMessage.imageTypeWarning'),this.$t('common.close')).then(r => { });
                }            
              });
              e.target.value = '';
            },

            //su sandy 
            deleteRelatedImage(index) {
                // this.$alertService.showConfirmDialog(null, "Delete image?").then((r) => {
                //     if (r.value) {
                        let uploadedFile = this.selfIntro.related_images.splice(index, 1);
                        let filename = uploadedFile[0].url.slice(0, uploadedFile[0].url.indexOf("."));

                        if (this.selfIntro.delete_related_images.indexOf(filename) == -1) this.selfIntro.delete_related_images.push(filename);
                //     }
                // });
            },

            // Edit Button Click
            editBox(boxName, action) {
                
                this.showDetails = !this.showDetails;
                this[boxName] = !this[boxName];
                if(action == 'close'){
                    this.loading = this.$loading.show();
                }
                if(boxName == 'basicInfoEdit'){
                    this.getBasicInfoDetails();   
                }
                if(boxName == 'selfIntroEdit'){
                    this.getSelfIntroDetails();   
                }
                if(boxName == 'careerEdit'){
                    this.getCarrerDetails();
                }
                if(boxName == 'expQualificationEdit'){
                    this.getCarrerRequiredList();
                   this.getJobIndustryExpDetails();
                   
                }
                if(boxName == 'desiredConditionEdit'){
                    // this.getDesiredCondition();
                    this.getSelfIntroDetails();
                }
               
                if (action == "open") {
                    if(boxName == 'desiredConditionEdit'){
                        if(!this.date_list.includes(this.desired_condition.desired_change_period)){
                            this.desired_condition.desired_change_period = null;
                        }
                    }
                    this.$emit('menuShowHide',this.showHide = false)
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                } else {
                    if(boxName == 'desiredConditionEdit'){
                        this.getDesiredCondition();
                    }
                    this.$emit('menuShowHide',this.showHide = true)
                    if(boxName == 'expQualificationEdit'){
                        this.getJobIndustryExpDetails();
                        // call getData func back to clear empty array in data collection
                    }
                    this.$nextTick(() => {
                        var ele = this.$el.querySelector("#" + boxName);
                        window.scrollTo(0, ele.offsetTop);
                    });
                }
                
            },

            // Education
            addEducation() {
                this.educations.push({
                    id: null,
                    jobseeker_id: null,
                    school_name: "",
                    subject: "",
                    degree: null,
                    from_year: null,
                    from_month: null,
                    to_year: null,
                    to_month: null,
                    education_status: null,
                 
                    
                });
            },
            deleteEducation(indx) {
                this.educations.splice(indx, 1);
            },

            // Experience
            addExperience() {
                this.experiences.push({
                    id: null,
                    jobseeker_id: null,
                    position_id: 0,
                    employment_type_id: 0,
                    job_location: "",
                    main_duty: "",
                    from_year: "年",
                    from_month: "月",
                    to_year: "年",
                    to_month: "月",
                    current: 0,
                    private_status: 0,
                });
               
            },
           
            deleteExperience(indx) {
                this.experiences.splice(indx, 1);
            },

            //(start) ExpQualification by zayar_phone_naing
            addExpQualification() {
                this.experience_qualification.experience_jobs.push({
                    experience_year: null,
                    experience_industry: null,
                    experience_occupation: null,
                    experience_position: null,
                });
            },

            addStudyAbroadExp() {
                this.experience_qualification.study_abroad_experiences.push({
                    study_abroad_country: null,
                    study_abroad_period: null,
                    study_abroad_purpose: null,
                });
            },

            addWorkingAbroadExp() {
                this.experience_qualification.working_abroad_experiences.push({
                    working_abroad_country: null,
                    working_abroad_period: null,
                    working_abroad_position: null,
                });
            },

            addforeignLanguageLevel(){
                this.experience_qualification.foreign_language_level_experiences.push({
                    foreign_language: null,
                    language_level: null
                });
            },
            
            getJobIndustryExpDetails(){
                this.$api.post("/jobseeker/profile/experiences-qualifications")
                .then((res) => {
                    if(this.loading != ''){
                        this.loading.hide();
                    }
                    let response            = res.data.data;
                    this.industry_list      = response.industries;
                    this.country_list       = response.countries;
                    this.exp_occupations    = response.occupations;
                    this.exp_positions      = response.positions;
                    let jobseeker_detail    = response.jobseeker_detail;
                    let industry_histories  = response.industry_histories;
                    let education_overseas  = response.education_overseas;
                    let working_overseas    = response.working_overseas;
                    let languages_levels    = response.languages_levels;
                    
                    // experience job type
                    this.experience_qualification.experience_jobs = [];
                    if(industry_histories.length > 0){
                        for(const industry_history of industry_histories){
                            this.experience_qualification.experience_jobs.push({
                                industry_history_id :   industry_history.id,
                                experience_year:        industry_history.experience_year,
                                experience_industry:    industry_history.industry_id,
                                experience_occupation:  industry_history.occupation_keyword_id,
                                experience_position:    industry_history.position_id,
                            });
                        }
                    }else {
                        this.experience_qualification.experience_jobs.push({
                            industry_history_id: null,
                            experience_year: null,
                            experience_industry: null,
                            experience_occupation: null,
                            experience_position: null,
                        });
                    }

                    // study abroad experience
                    this.experience_qualification.study_abroad_experiences = [];
                    if(education_overseas.length > 0){
                        for(const education_oversea of education_overseas){
                            this.experience_qualification.study_abroad_experiences.push({
                                study_abroad_id :       education_oversea.id,
                                study_abroad_country:   education_oversea.country_id,
                                study_abroad_period:    education_oversea.period,
                                study_abroad_purpose:   education_oversea.purpose
                            });
                        }
                    }else {
                        this.experience_qualification.study_abroad_experiences.push({
                            study_abroad_id : null,
                            study_abroad_country: null,
                            study_abroad_period: null,
                            study_abroad_purpose: null
                        });
                    }

                   

                    // working at overseas/abroad experience
                    this.experience_qualification.working_abroad_experiences = [];
                    if(working_overseas.length > 0){
                        for(const working_oversea of working_overseas){
                            this.experience_qualification.working_abroad_experiences.push({
                                working_abroad_id :      working_oversea.id,
                                working_abroad_country:  working_oversea.country_id,
                                working_abroad_position: working_oversea.position_id,
                                working_abroad_period:   working_oversea.period
                            });
                        }
                    }else {
                        this.experience_qualification.working_abroad_experiences.push({
                            working_abroad_id : null,
                            working_abroad_country: null,
                            working_abroad_position: null,
                            working_abroad_period: null
                        });
                    }

                    // work visa
                    this.experience_qualification.work_visa.status   = jobseeker_detail.visa_status ?? '0';
                    this.experience_qualification.work_visa.country  = jobseeker_detail.visa_country ?? null;

                    // other qualifications
                    this.experience_qualification.other_qualifications.TOEIC_score              = jobseeker_detail.toeic_score;
                    this.experience_qualification.other_qualifications.language_qualifications  = jobseeker_detail.other_language_certificate;
                    this.experience_qualification.other_qualifications.qualifications           = jobseeker_detail.other_certificate;

                    // foreign language level
                    this.experience_qualification.foreign_language_level_experiences = [];
                    if(languages_levels.length > 0){
                   
                        for(const languages_level of languages_levels){
                            this.experience_qualification.foreign_language_level_experiences.push({
                                foreign_language_id :   languages_level.id,
                                foreign_language:       languages_level.language_id,
                                language_level:         languages_level.language_level,
                            });
                        }
                    }else {
                      
                        this.experience_qualification.foreign_language_level_experiences.push({
                            foreign_language_id : null,
                            foreign_language: null,
                            language_level: null,
                        });
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
            },

            deleteExpQualification(index, industry_history_id) {
                if( typeof(industry_history_id) !== 'undefined' ){
                    this.ids_to_del_exp_quali.push({industry_history_id});
                }
                this.experience_qualification.experience_jobs.splice(index, 1);
            },

            deleteStudyAbroad(index, study_abroad_id) {
                if( typeof(study_abroad_id) !== 'undefined' ){
                    this.ids_to_del_study_abroad.push({study_abroad_id});
                }
                this.experience_qualification.study_abroad_experiences.splice(index, 1);
            },

            deleteWorkingAbroad(index, working_abroad_id) {
                if( typeof(working_abroad_id) !== 'undefined' ){
                    this.ids_to_del_working_abroad.push({working_abroad_id});
                }
                this.experience_qualification.working_abroad_experiences.splice(index, 1);
            },

            deleteforeignLanguageLevel(index, foreign_language_id) {
                if( typeof(foreign_language_id) !== 'undefined' ){
                    this.ids_to_del_language_levels.push({foreign_language_id});
                }
                this.experience_qualification.foreign_language_level_experiences.splice(index, 1);
            },

            saveExpQualification(){
                // let exp_jobs = this.experience_qualification.experience_jobs.map((x) => {
                //     if(x.experience_year != null && x.experience_year != ''){
                //         var fix_decimal = parseFloat(x.experience_year).toFixed(6);
                //         x.experience_year = fix_decimal.slice(0,-1);
                //     }
                    
                //     return x;
                // });
                // console.log("exp job",exp_jobs)
                // console.log("exp job old",this.experience_qualification.experience_jobs)
                // this.experience_qualification .$touch();
                // if (this.experience_qualification .$invalid) return;
                let loader = this.$loading.show();
                let request_data = {
                    experienced_jobs:                       this.experience_qualification.experience_jobs,
                    delete_experience_jobs:                 this.ids_to_del_exp_quali,
                    study_abroad_experiences:               this.experience_qualification.study_abroad_experiences,
                    delete_study_abroad:                    this.ids_to_del_study_abroad,
                    working_abroad_experiences:             this.experience_qualification.working_abroad_experiences,
                    delete_working_abroad:                  this.ids_to_del_working_abroad,
                    work_visa:                              this.experience_qualification.work_visa,
                    foreign_language_experiences:           this.experience_qualification.foreign_language_level_experiences,
                    delete_foreign_languages_experiences:   this.ids_to_del_language_levels,
                    other_qualifications:                   this.experience_qualification.other_qualifications,
                };
                console.log("reqdata", request_data);
                this.$api
                    .post("/jobseeker/profile/experiences-qualifications/update", request_data)
                    .then((response) => {
                        loader.hide();
                        this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));
                        this.editBox('expQualificationEdit','close');
                    })
                    .catch((errors) => {
                        loader.hide();
                    });
            },
            //(end) ExpQualification by zayar_phone_naing

            //Industry (sus andy)
            addIndustry(){
                this.industries.push({
                    id:0,
                    jobseeker_id:null,
                })
            },
          
            removeIndustry(index)
            {
               this.industries.splice(index,1);
            },
            //occupation


            //occupation (su sandy)
            addOccupation(){
                 this.occupations.push({
                    id:0,
                    jobseeker_id:null,
                })
            },
          
            removeOccupation(index)
            {
                this.occupations.splice(index,1);
            },
            //occupation 

            //su sandy
            saveSelfIntro() {
                this.$v.selfIntro.$touch();
                if (this.$v.selfIntro.$invalid) {
                    return;
                }
                let data = new FormData();
                buildFormData(data, this.selfIntro);
                let loading = this.$loading.show();
                this.$api.post("/jobseeker/profile/selfintro", data).then((r) => {
                    this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));
                    this.editBox("selfIntroEdit", "close");
                    this.getSelfIntroDetails();
                    // this.$store.dispatch('user_image');
                    
                }).catch((e) => {
                    console.log(e);
                }).finally(() => {
                    loading.hide();
                });
            },

            // Zinko
            saveBasicInfo() {
                this.$v.basicInfo.$touch();
                if (this.$v.basicInfo.$invalid) {
                    return;
                }
                let data = new FormData();
                buildFormData(data, this.basicInfo);
                let loading = this.$loading.show();
                this.$api.post("/jobseeker/profile/basicinfo", data).then((r) => {
                    this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));
                    
                    this.editBox("basicInfoEdit", "close");
                    this.getBasicInfoDetails();
                        
                }).catch((e) => {
                    console.log(e);
                }).finally(() => {
                    this.$store.dispatch('user_image');
                    loading.hide();
                });
            },
            
            getBasicInfoDetails() {
                this.$api.get("jobseeker/profile/basicinfo").then((response) => {
                    if(this.loading != '')
                    {
                        this.loading.hide();
                    }
                    // this.loading.hide();
                    this.basicInfo = response.data.data.profile;
                    if( this.basicInfo.only_country == 1){
                      
                        this.basicInfo.city_name = null;
                    }
                

                    this.city_list = response.data.data.cities;
                   
                //     if(this.basicInfo.city_name == '日本'){
                //         this.basicInfo.city_name = null;
                //     }
                 
                //    this.city_list = response.data.data.cities.filter(function(e){
                //           return e.city_name != '日本';
                //    });
                   
                    this.languages = response.data.data.languages;
                    this.countries = response.data.data.countries;
                    // if(this.basicInfo.dob == '' ){
                    //     this.basicInfo.dobyears = null;
                    //     this.basicInfo.dobmonth = null;
                    //     this.basicInfo.dobday = null;
                    // }
                    // else{
                    //     const dob = new Date(this.basicInfo.dob);
                    //     this.basicInfo.dobyears = dob.getFullYear() + ' 年';
                    //     this.basicInfo.dobmonth = dob.getMonth() + 1 + ' 月';
                    //     this.basicInfo.dobday = dob.getDate() + ' 日';
                    // }
                });
            },
              
            

            getCity() {
                this.basicInfo.city_name = 0;
                this.$api.get("/jobseeker/city-list/" + this.basicInfo.country_name).then((res) => {
                     this.city_list = res.data;
                    // this.city_list = res.data.filter(function (e) {
                    //     return e.city_name != '日本';
                    // });
                    
                });
            },

            getCarrerDetails(){
                this.$api.get("jobseeker/profile/carrerinfo").then((response) => {
                        if(this.loading != ''){
                            this.loading.hide();
                        }
                        this.educations = response.data.data.educations;
                      
                        this.experiences = response.data.data.experiences;
                        
                       
                        if(this.educations.length > 0){
                            
                            this.educations.forEach(element => {
                             
                                if(element.from_year){
                                    element.from_year = element.from_year + 1920+ " 年";
                                }else{
                                    element.from_year = null;
                                }

                                if(element.from_month || element.from_month == 0){
                                    element.from_month = element.from_month + 12+ " 月";
                                }else{
                                    element.from_month = null;
                                }

                                if(element.to_year){
                                    element.to_year = element.to_year + 1920+ " 年";
                                }else{
                                     element.to_year = null ;
                                }

                                if(element.to_month || element.to_month == 0){
                                    element.to_month = element.to_month + 12+ " 月";
                                }else{
                                    element.to_month =  null;
                                }   
                                
                            });
                        }else{
                           this.educations.push({
                                id: null,
                                jobseeker_id: null,
                                school_name: "",
                                subject: "",
                                degree: null,
                                from_year: null,
                                from_month: null,
                                to_year: null,
                                to_month: null,
                                education_status: null,
                              
                            });
                        }
                        
                        if(this.experiences.length > 0){ 

                            this.experiences.forEach(element => {
                               

                                if(element.position_id == null){
                                    element.position_id == 0;
                                }

                                if(element.employment_type_id == null){
                                    element.employment_type_id == 0;
                                }

                                if(element.from_year){
                                    element.from_year = element.from_year + 1920+ " 年";
                                }else{
                                     element.from_year = '年';
                                }

                                if(element.from_month  || element.from_month == 0){
                                    element.from_month = element.from_month + 12+ " 月";
                                }else{
                                     element.from_month = '月';
                                }

                                if(element.to_year){
                                    element.to_year = element.to_year + 1920+ " 年";
                                }else{
                                     element.to_year = '年';
                                }

                                if(element.to_month || element.to_month == 0){
                                    element.to_month = element.to_month + 12+ " 月";
                                }else{
                                    element.to_month = '月';

                                }
                            });
                        }
                        else{
                            this.experiences.push({
                                id: null,
                                jobseeker_id: null,
                                position_id: 0,
                                employment_type_id: 0,
                                job_location: "",
                                main_duty: "",
                                from_year: "年",
                                from_month: "月",
                                to_year: "年",
                                to_month: "月",
                                current: 0,
                                private_status: 0,
                            });
                          
                        }
                         
                        this.carrers = response.data.data.carrers;
                      
                        
                    });
            },
            
            //Zin ko

            // Thuzar
            saveCarrer() {

                    let current = this.experiences.filter(x=>x.current == 1 && (x.from_year == '年' || x.from_month == '月'));
                    if(current.length > 0){
                        this.exp_year_errors = "errors";
                        this.exp_month_errors = "errors";
                    }
                    else{
                        this.exp_year_errors = '';
                        this.exp_month_errors = '';

                    }
                
                    for(var i =this.experiences.length-1;i>=0;i--){
                        if(this.experiences.length != 1){
                            if((this.experiences[i].job_location == null || this.experiences[i].job_location == '') && this.experiences[i].from_year == '年' && this.experiences[i].from_month == '月' && 
                                this.experiences[i].to_year == '年' && this.experiences[i].to_month == '月' && this.experiences[i].position_id == "0" && this.experiences[i].employment_type_id == "0" && 
                                (this.experiences[i].main_duty == null || this.experiences[i].main_duty == '' && this.experiences[i].private_status == 0 ) && (this.experiences[i].current == false || this.experiences[i].current == 0)
                                && this.exp_year_errors == '' && this.exp_month_errors == '')  {
                                this.experiences.splice(i,1);
                               
                            }
                        }
                     
                    }

                
                    for(var i =this.educations.length-1;i>=0;i--){
                        if(this.educations.length != 1) {
                            if((this.educations[i].school_name == null || this.educations[i].school_name == '' ) && (this.educations[i].degree == null || this.educations[i].degree == '' )  && (this.educations[i].subject == null || this.educations[i].subject == '' ) && this.educations[i].from_year == null && this.educations[i].to_year == null && this.educations[i].from_month == null && this.educations[i].to_month == null && this.educations[i].education_status == null ){
                                this.educations.splice(i,1);
                            }
                        }
                    }

                    if(this.exp_year_errors == '' && this.exp_month_errors == ''){
                        let loader = this.$loading.show();
                    
                        let jsonData = {
                            educations: this.educations,
                            experiences: this.experiences,
                            carrers: this.carrers,
                        };
                        console.log(jsonData)

                        this.$api.post("/jobseeker/profile/carrer", jsonData).then((response) => {
                            this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));
                            this.editBox("careerEdit", "close");
                            this.getCarrerDetails(); 
                            loader.hide();
                        }).catch((errors) => {
                            loader.hide();
                        });

                    }

                    
            },
            // Thuzar


            //Su Sandy
            getDesiredCondition(){
              
                 this.$api.get("jobseeker/profile/desired-condition").then((response) => {
                     if(this.loading != ''){
                        this.loading.hide();
                     }
                     this.desired_city_list = response.data.city_list;
                     this.industry_list = response.data.industry_list;
                     this.occupation_list = response.data.occupation_list;
                     this.date_list = response.data.date_list;
                     this.desired_condition = response.data.desired_condition;
                     
                     this.chooseLocation();

                    // if(!this.date_list.includes(this.desired_condition.desired_change_period)){
                    //     this.desired_condition.desired_change_period = null;
                    // }

                    if(response.data.industries.length)
                    {
                        this.industries = response.data.industries;
                    }
                    else{
                        this.industries=[{id:0,jobseeker_id:null}];
                    }

                    if(response.data.occupations.length)
                    {
                        this.occupations = response.data.occupations;
                    }
                    else{
                        this.occupations=[{id:0,jobseeker_id:null}];
                    }
                 })
            },


            saveDesiredCondition()
            {

                let jsonData = {
                    desired_condition: this.desired_condition,
                    industries: this.industries,
                    occupations: this.occupations,
                };

                //delete industry null 
                if(this.desired_condition.desired_industry_status) {
                    jsonData.industries = [];
                }else{
                    var indexArray = [];
                    this.industries.forEach(function(value, index) {
                        if (value.id != 0) {
                            indexArray.push(index);
                        }
                    })
                
                }

                //delete occupation null 
                if(this.desired_condition.desired_occupation_status){
                    jsonData.occupations = [];
                }
                else{
                    var indexArray = [];
                    this.occupations.forEach(function(value, index) {
                        if (value.id != 0) {
                            indexArray.push(index);
                        }
                    })
                }

              
                let loader = this.$loading.show();
                this.$api.post("jobseeker/profile/desired-condition",jsonData).then((response) => {                    
                    this.editBox("desiredConditionEdit", "close");
                    this.getDesiredCondition();
                    this.getSelfIntroDetails();
                    loader.hide();
                    this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));
                })
                .catch((errors) => {
                    loader.hide();
                });
            },
            // Su Sandy
            imagePopup(url) {
                if(url.includes('images/default.png') || url.includes('images/no_public.png')){
                    return false;
                }
                else{
                    this.showModal =  true;
                    this.imgUrl = url;
                }
                
            },
        },
    };
</script>
<style lang="scss" scoped>
.detail-head {
	width: 34%;
}

.detail-data div {
    white-space: unset!important;
}

.detail-data {
	width: 63%;
}
.profile-body .tab-content{
    box-shadow: none ;
}
    .form-control {
        border: 1px solid #919191;
        border-radius: 0;
    }
    /* modal-dialog */
    .popup-databox {
        border: 1px solid #c4c4c4;
        border-radius: 8px;
        padding: 15px 30px;
        margin-bottom: 20px;
    }
    .popup-databox .form-group {
        margin-bottom: 1.5rem;
    }
    .school-box,
    .experience-box {
        margin-bottom: 10px;
        padding: 30px;
        background: #f0f0f0;
        border-radius: 3px;
        border: 1px solid #c4c4c4;
    }
    .add-btn {
        padding: 0.7rem 2rem;
        background: #f0f0f0;
        color: #222;
        border: 1px solid #c4c4c4;
        border-radius: 50px;
        font-size: 14px;
    }
    .close-btn {
        position: absolute;
        top: -17px;
        right: -15px;
        width: 35px;
        height: 35px;
        font-size: 16px;
        text-align: center;
        background: #fff;
        border-radius: 50%;
        color: #919191;
        border: 1px solid;
        line-height: 35px;
        vertical-align: middle;
        cursor: pointer;
        
    }
    .check-txt {
        display: block;
        width: 100%;
        margin-bottom: 5px;
    }
    /* intro edit */
    .upload-content {
        display: flex;
        position: relative;
        padding: 20px 0;
        background: #fff;
        color: #333;
        justify-content: center;
        align-items: center;
    }
    .upload-content::after {
        content: "";
        position: absolute;
        background: linear-gradient(to right, #b5c4cc 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#b5c4cc 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(to right, #b5c4cc 50%, rgba(255, 255, 255, 0) 0%),
            linear-gradient(#b5c4cc 50%, rgba(255, 255, 255, 0) 0%);
        background-position: top, right, bottom, left;
        background-repeat: repeat-x, repeat-y;
        background-size: 25px 4px, 4px 25px;
        top: -2px;
        bottom: -2px;
        left: -2px;
        right: -2px;
        z-index: 1;
    }
    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        z-index: 2;
    }

    .upload-btn {
        border: 1px solid #b5c4cc;
        background-color: #fff;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: bold;
        color: #333;
        box-shadow: none;
    }

    .upload-file {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 45px;
    }
    .profile-edit-img {
        width: 100%;
    }
    .intro-tit {
        margin-bottom: 15px;
        font-size: 15px;
        color: #333;
    }
    .custom-radio-lable {
        font-weight: normal;
    }
    .custion-radio:checked + .custom-radio-lable:before {
        border: 1px solid #ddd;
    }
    .custion-radio:not(:checked) + .custom-radio-lable:before {
        border: 1px solid #ddd;
    }
    /*
.custion-radio:checked + .custom-radio-lable:after {
        background: #6085A3;
}
*/
    .upload-content .icon {
        font-size: 50px;
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
         font-size: 14px;
    }
    .about-box {
        min-height: 100px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px 20px;
        line-height: 2;
        font-size: 14px;
        border: 1px solid #ddd;
        margin: 15px 0 0px 0;
    }
    .intro-tit {
        margin-bottom: 20px;
        border-bottom: none;
    }
    // .movie-row {
    //     padding-bottom: 100px;
    // }
    
    .movie-link {
        width: 100%;
        height: 100%;
    }
    .detail-list {
        margin: 50px 0;
    }
    .intro-row{
        margin-bottom: 50px;
    }
    /*
    .detail-head,
    .detail-data {
        padding: 15px 0 5px 0;
        border-top: 1px solid #ddd;
        line-height: 2;
    }
    .detail-head {
        display: flex;
        float: left;
        width: 40%;
        margin-right: 6%;
        align-items: flex-end;
        font-weight: normal;
    }
    .detail-data {
        float: left;
        width: 54%;
        background-color: #fff;
    }*/
    .private {
        margin: 0 20px 2px auto;
        padding: 0 10px;
        background: #0071b4;
        border-radius: 2px;
        color: #fff;
    }
    .view-permission {
        margin: 0 20px 2px auto;
        padding: 2px 10px 2px 10px;
        background: #CC7694;
        border-radius: 2px;
        color: #fff;
        display: inline-block;
    }
    .explation-note {
        margin: 0 20px;
        padding: 15px 20px;
        border: 1px solid #ababab;
        border-radius: 10px;
        color: #666666;
        line-height: 1.8;
    }
    .explation-note .private {
        padding: 3px 10px;
        display: inline-block;
    }
    textarea.form-control {
        height: 200px;
        text-align: left;
    }
    .popup-databox .private {
        padding: 1px 10px 2px 10px;
        vertical-align: middle;
        font-weight: normal;
    }
    
    .introduction-content .popup-databox .form-group {
        margin-bottom: 2rem;
    }
    .gender-choice {
        position: relative;
        display: block;
        padding: 0.5rem 0.1rem;
        border-radius: 0.25rem;
        border: 1px solid #707070;
        text-align: center;
        background: #f0f0f0;
    }
    .gender-choice.disable {
        color: #d5d5d5;
        background: #fff;
    }
    .gender-choice .check-icon {
        padding-right: 10px;
    }
/*
    .select-wrap {
        position: relative;
        border: 1px solid #919191;
    }
    .select-wrap .form-control {
        border: none;
        -webkit-appearance: none;
    }
    .sort-desc {
    position: absolute;
    top: 0;
    right: 18px;
    font-size: 24px;
    color: #C4C4C4;
    }*/
    .r-5 {
        right: 5px !important;
    }
/*wmo for carousel slider */
.card-carousel {
    user-select: none;
    position: relative;
}
.progressbar {
    display: block;
    width: 100%;
    height: 5px;
    position: absolute;
    background-color: rgba(221, 221, 221, 0.25);
    z-index: 1;
}
.progressbar > div {
    background-color: rgba(255, 255, 255, 0.52);
    height: 100%;
}
.thumbnails {
    display: block;
    justify-content: space-evenly;
    flex-direction: row;
}
.thumbnail-image {   
    align-items: flex-start;
    cursor: pointer;    
    width: 100%;
    height: 82px;
    display: flex;
    overflow: hidden;
    margin: 0px 0px 10px 0px;
   /* border: 1px solid #eee;*/
    box-shadow: none;
}
.thumbnail-image > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 250ms;
}
.thumbnail-image:hover > img, 
.thumbnail-image.active > img {
    opacity: 0.6;
    box-shadow: 2px 2px 6px 1px rgba(0,0,0, 0.5);
}
.cursor-pointer{
    cursor: pointer;
}
.card-img {
    position: relative;
    margin-bottom: 15px;
}
.card-img > img {
    display: block;
    margin: 0 auto;
}
.actions {
    font-size: 1.5em;
    height: 40px;
    position: absolute;
    top: 50%;
    margin-top: -20px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #585858;
}
.actions > span {
    cursor: pointer;
    transition: all 250ms;
    font-size: 45px;
}
.actions > span.prev {
    margin-left: 5px;
}
.actions > span.next {
    margin-right: 5px;
}
.actions > span:hover {
    color: #eee;
}
.list-enter-active, .list-leave-active {
  transition: opacity 0.25s ease-out;
}

.list-enter,
.list-leave-to {
    opacity: 0;
}
.face-img-block{
    min-height: 240px;
    margin-bottom: 10px;
    img{
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: contain;      
    }
}
.face-image-wrapper{    
    position: relative;
    display: inline-block;
    .delete-btn {
        right: -5px;
    }
}
.face-image{
    height: 82px;
    display: block;
    img{
        width: 100%;
        height: 100%;       
        object-fit: contain;  
    }
}
.img-wrapper {
    max-height: 266px;
    
}
.img-wrapper img {
    height: 266px;
}
.delete-btn{
    position: absolute;
    top: -10px;
    right: -5px;
    width: 35px;
    height: 35px;
    font-size: 16px;
    text-align: center;
    background: #fff;
    border-radius: 50%;
    color: #919191;
    border: 1px solid;   
    vertical-align: middle;   
    cursor: pointer;
    line-height: 35px;
    cursor: pointer;
}

.check-item{
    position: relative;
    width: 130px;
    list-style: none;
    padding: 10px 20px;
    margin: 0 10px 10px 0;
    display: inline-block;
    color: #999;
    border: 1px solid #9b9898;
    margin-right: 10px;
    background: #eee;
    border-radius: 5px;
}
.check-item .fa{
    margin-right: 5px;
}
.fa-check.disabled{
    opacity: 0.2;
}
.show-info{    
    border: 1px solid #d2d2d2;
    width: 100%;
    display: inline-block;
    line-height: 1.5;
    padding: 6px;
    margin-top: 10px;
    background: #fff;
    color: #333;
    text-align: center;
}

.same-check .check-private{  
    margin: 0px 10px 0px 20px;
    max-height: 28px;
    top: -0.15rem;
    left: 0rem;
    width: 1.4rem;
    height: 1.4rem;
    border: #b5b5b5 solid 2px;
    border-radius: 0px;
    padding: 0px;
}
.same-check .fa-check:before {
    content: "";
    background-color: transparent;
    content: "\2714";
    color: #2f2d2c;
    font-size: 17px;
    padding-top: 0px;
    padding-left: 2px;
    border-color: #b5b5b5;
}
.check-private{
    position: relative;
    max-width: 28px;
    list-style: none;
    padding: 3px 15px 0px 6px;
    display: inline-block;
    color: #3276e0;
    border: 1px solid #9b9898;
    margin: -3px 10px 0px 10px;
    max-height: 28px;
}
.info{
    margin-bottom: 15px;
    background: #fff3f3;
    padding: 2px 5px;
    color: #ff7a00;
}
.info-choose{
    display:inline-block;
    color:#666666;
    font-size:13px;
    margin-top:5px;
}
.status{
    width: 100px;
    height: 35px;
    background-color: #eee;
    margin: 0px 0px 10px 0px;
    padding: 10px 8px 8px 8px;
    border-radius: 3px;

}
.history-edit div {
    white-space: unset;
}
.history-edit-tbl td {
    padding: 5px 0;
    vertical-align: top;
}
.history-edit-tbl td:first-child {
    display:block;
    width: 260px;
    color: #666;
}
.kara-txt{
    line-height:35px;
}
@media (max-width: 1024px) { 
    .img-modal-wrapper .img-modal-block {
        width: 80%;
    }
    .movie-row{
        padding-bottom: 0px;
    }
    .thumbnails{
        padding-left: 0px;
    }
    .detail-head {
        flex-wrap: wrap;
        float: none;
    }
}
@media (max-width: 768px) { 
    .detail-head {
        flex-wrap: wrap;
        float: none;
    }
    .view-permission {
        display: inline-block;
        float: none !important;
        margin: 0 20px 2px 0;
    }
    .history-edit-tbl td:first-child {
        width: 190px;
    }
    .same-check .fa-check:before {
        font-size: 14px;
    }
}
@media (max-width: 480px) { 
    .p-0-res{
        padding-left: 0px;
        padding-right: 0px;
    }
    .pl-5{
        padding-left: 15px !important;
    }
    .kara-txt{
        margin-left: 5px;
    }
    .tab-content {
        padding: 40px 0;
    }
    .profile-edit-tit {
        font-size: 18px;
    }
    .profile-edit-txt {       
        font-size: 12px;
        padding:2px 8px !important;
    }
    .profile-edit-wrap{
        margin-bottom: 20px;
    }
    .icon-edit {
        margin-right: 2px;
        font-size: 30px;
    }
    .delete-btn{
        right:0px;
    }
    .upload-content{
        flex-wrap: wrap;
    }
    .tit-box {
        margin-bottom: 0;
    }
    .popup-databox{
        padding: 10px 15px 10px 15px;
    }
    .school-box, .experience-box{
        padding: 15px;
    }
    .face-img-block {
        height: 153px;
        min-height: 200px;
    }
    .face-image{
        height: 60px;
    }
    .faceimg-wrap {
        width: 40%;
        margin-left: auto;
    }
    .face-image-wrapres{
        width: 40%;
        margin-left: auto;
    }
    .img-wrapper-res{
        margin-bottom: 20px;
    }
    .thumbnail-image {
        height: 60px;
    }
    .explation-note {
        margin: 0;
        padding: 10px;
    }
    .about-box {
        padding: 10px;
    }
    .detail-head {
        width: 42%;
    }
    .detail-data {
        width: 52%;
    }
    .view-permission {
        width: 100%;
        padding: 0 3px 2px 3px;
        margin: 0 0 2px 0;
        font-size: 12px;
    }
    .explation-note .view-permission {
        width: auto;
        font-size: 14px;
    }
    .history-edit-tbl td {
        display: block;
        width: 100%;
        margin: 5px 0;
        padding: 5px;        
    }
    .history-edit-tbl td:first-child {
        width: 200px;
        background: #f1eeee;
    }
    .img-wrapper img{
        height: 206px;
    }
    .private{
        margin:0 0px 2px auto;
    }
    .show-info{
        font-size:12px;
    }
    .popup-databox{
        margin: 15px 0px;
    }
    .mb-2-res{
        margin-bottom: .5rem!important
    }
}
@media (max-width: 380px) {
    .profile-edit-tit{
        font-size: 15px;
    }
     .history-edit-tbl td:first-child {
        width:100%;
    }
    
}
@media (max-width:360px){
    .date-360{
       .date-col{
        flex: 0 0 100% !important;
        max-width: 100% !important;
       }       
    }
    .p-0-res{
        padding:15px 15px 0px 15px;
    }
    .kara-txt{
        width:100%;
        text-align:center;
    }
}
@media (max-width: 320px) {
    .profile-edit-tit{
        font-size: 13px;
    }
    .profile-edit-txt {
        top: -8px;
        font-size: 12px;
    }
    .icon-edit {
        margin-right: 2px;
        font-size: 20px;
    }
    .view-permission {
        font-size: 10px;
    }
    .private{
        margin:0px;
        padding: 0px 2px;
    }
}
</style>
