<template>
	<div class="user-main">
		<h2 class="user-head-tit recruiter-primarylight-color">{{ $t('recruiter-profile.profile-setting') }}
		    <p class="btn primary-btn btn-sm float-right recruiter-profile-edit" @click="$router.push({ name: 'RecruiterProfileSetting' })"> <i class="fa fa-times pr-1"></i>{{ $t('common.close') }}</p>
        </h2>
		<div class="list-group-item d-flex align-items-center tab-content">
			<div class="col-12 p-0 profile-edit">
				<form @submit.prevent="updateProfile">
					<div id="information">
                        <div class="my-3 inner-wrapper">
							<div class="form-group">
                                <label><span class="align-label">企業の登記地</span></label>
                                <input type="text" class="form-control" readonly :value="recruiterForm.registered_place_type == 'oversea' ? '海外企業' : '国内企業'">
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.recruiter-id') }}</span></label>
                                <input type="text" class="form-control" readonly :value="recruiterForm.recruiter_number">
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.recruiter-name') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <input type="text" :class="['form-control',$v.recruiterForm.recruiter_name.$error?'is-invalid':'']" v-model.trim="$v.recruiterForm.recruiter_name.$model">
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.recruiterForm.recruiter_name.required">{{ $t('recruiter-profile.recruiter-name-is-required') }}</div>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label>{{ $t('recruiter-profile.recruiter-nickname') }}</label>
                                <input type="text" class="form-control" v-model.trim="recruiterForm.recruiter_nick_name">
                            </div> -->
                        </div>

                        <div class="my-3 inner-wrapper">
                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.establishment-date') }}</label>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
										 <input class="form-control mb-0" placeholder="" type="text" v-model="recruiterForm.establishment_date">
										<!-- check
										 <input minlength="4" maxlength="4" class="form-control mb-0" placeholder="年" type="text" v-model="recruiterForm.establishment_year" v-on:keypress="isYear()">
										 <label class="lab-right">年</label> check -->
                                        <!-- <select class="form-control w-100" v-model="recruiterForm.establishment_year">
                                            <option disabled value=""></option>
                                            <option v-for="year in 100" :key="year">{{ 1920 + year }}</option>
                                        </select> -->
                                    </div>
                                 
										<!-- check<input  minlength="1" maxlength="2" class="form-control mb-0" placeholder="月" type="text" v-model="recruiterForm.establishment_month" v-on:keypress="isMonth()">
                                        <label class="lab-right">月</label>check -->
										<!-- <select class="form-control w-100" v-model="recruiterForm.establishment_month">
                                            <option disabled value=""></option>
                                            <option v-for="month in 12" :key="month">{{ month }}</option>
                                        </select> -->
                                
                                </div>					
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.representative-name') }}</label>
                                <input type="text" class="form-control" v-model.trim="recruiterForm.representative_name">
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.num-of-employees') }}</label>
                                <input type="text" class='form-control'  v-model.trim="recruiterForm.num_of_employees">
                                <!-- <div class="invalid-feedback"> 
                                    <div class="error" v-if="!$v.recruiterForm.num_of_employees.numeric">{{ $t('recruiter-profile.number-only') }}</div>
                                </div> -->
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.business-description') }}</label>
                                <input type="text" class="form-control" v-model.trim="recruiterForm.business_description">
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.address') }}</label>
                                <textarea name="" id="" class="form-control" rows="5" v-model.trim="recruiterForm.address"></textarea>
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.phone1') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <input type="text" :class="['form-control',$v.recruiterForm.phone1.$error?'is-invalid':'']"  v-model.trim="$v.recruiterForm.phone1.$model">
                                <div class="invalid-feedback"> 
                                    <div class="error" v-if="!$v.recruiterForm.phone1.required">{{ $t('recruiter-profile.phone-is-required') }}</div>
                                    <div class="error" v-else-if="!$v.recruiterForm.phone1.alphaNumAndValidator">{{ $t('recruiter-profile.phone-is-number-only') }}</div>
                                    <!-- <div class="error" v-else-if="!$v.recruiterForm.phone1.minLength || !$v.recruiterForm.phone1.maxLength">{{ $t('recruiter-profile.phone-is-within-range') }}</div> -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.phone2') }}</label>
                                <input type="text" class="form-control" :class="['form-control',$v.recruiterForm.phone2.$error?'is-invalid':'']"  v-model.trim="$v.recruiterForm.phone2.$model">
                                <div class="invalid-feedback"> 								
                                    <div class="error" v-if="!$v.recruiterForm.phone2.alphaNumAndValidator">{{ $t('recruiter-profile.phone-is-number-only') }}</div>
                                    <!-- <div class="error" v-else-if="!$v.recruiterForm.phone2.minLength || !$v.recruiterForm.phone2.maxLength">{{ $t('recruiter-profile.phone-is-within-range') }}</div> -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ $t('recruiter-profile.incharge-email') }}</label>
                                <input type="text" class="form-control" :value="recruiterForm.email" readonly>
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.website') }}</span> 
								<!-- <span class="required">{{ $t('common.required') }}</span> -->
								</label>
                                <input type="text" :class="['form-control',$v.recruiterForm.website.$error?'is-invalid':'']" placeholder="http://www.example.com" v-model.trim="$v.recruiterForm.website.$model">
                                <div class="invalid-feedback">
                                    <!-- <div class="error" v-if="!$v.recruiterForm.website.required && $v.recruiterForm.website.$error">{{ $t('recruiter-profile.website-is-required') }}</div> -->
                                    <div class="error" v-if="!$v.recruiterForm.website.url">{{ $t('recruiter-profile.website-is-invalid') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="my-3 inner-wrapper">
                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.incharge-name') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <input type="text" :class="['form-control',$v.recruiterForm.incharge_name.$error?'is-invalid':'']" v-model.trim="$v.recruiterForm.incharge_name.$model">
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.recruiterForm.incharge_name.required">{{ $t('recruiter-profile.incharge-name-is-required') }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.incharge-name-furigana') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <input type="text" :class="['form-control',$v.recruiterForm.incharge_name_furigana.$error?'is-invalid':'']" v-model.trim="$v.recruiterForm.incharge_name_furigana.$model">
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.recruiterForm.incharge_name_furigana.required">{{ $t('recruiter-profile.incharge-name-furigana-is-required') }}</div>
                                    <div class="error" v-if="!$v.recruiterForm.incharge_name_furigana.isFurigana">{{ $t('recruiter-profile.incharge-name-furigana-is-invalid') }}</div>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label>{{ $t('recruiter-profile.account-id') }}</label>
                                <input type="text" class="form-control" v-model.trim="recruiterForm.account_id">
                            </div> -->

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.question') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <select :class="['form-control mb-1 qa-select',$v.recruiterForm.question.$error?'is-invalid':'']" v-model="questionType" @change="onQuestionTypeChange">
                                    <option v-for="(question, key) in $configs.questions" :value="question" :key="key">{{ question }}</option>
                                </select>
                                <input type="text" :class="['form-control',$v.recruiterForm.question.$error?'is-invalid':'']" v-model.trim="$v.recruiterForm.question.$model"
                                    v-show="questionType == $configs.questions.other">
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.recruiterForm.question.required">{{ $t('recruiter-profile.question-is-required') }}</div>
                                    <div class="error" v-else-if="!$v.recruiterForm.question.maxLength">{{ $t('recruiter-profile.question-exceed-length') }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><span class="align-label">{{ $t('recruiter-profile.answer') }}</span> <span class="required">{{ $t('common.required') }}</span></label>
                                <input type="text" :class="['form-control',$v.recruiterForm.answer.$error?'is-invalid':'']" v-model.trim="$v.recruiterForm.answer.$model">
                                <div class="invalid-feedback">
                                    <div class="error" v-if="!$v.recruiterForm.answer.required">{{ $t('recruiter-profile.answer-is-required') }}</div>
                                </div>
                            </div>
                        </div>
					</div>

					<div class="my-3 inner-wrapper" id="logo">
						<div class="form-group">
							<label class="mb-3">{{ $t('recruiter-profile.company-logo') }}</label><br/>
							<label >{{ $t('recruiter-profile.logo-and-incharge-text') }}</label>
							<div :class="['upload-content', $v.recruiterForm.logo.$error ? 'is-invalid':'']"
								@drop.prevent="onLogoChange" @dragover.prevent>
								<p><span class="icon icon-upload"><span class="path1"></span><span class="path2"></span></span></p>
								<label class="pl-1 pr-1 pl-sm-3 pr-sm-3 mb-0">{{ $t('recruiter-profile.drag-and-drop') }}</label>
								<div class="upload-btn-wrapper">
								<button type="button" class="btn upload-btn mx-3" 
									@click="$refs.logoInput.click()">{{ $t('recruiter-profile.choose-file') }}
								</button>
								</div>
								
							</div>
							<input type="file" @change="onLogoChange" accept="image/x-png,image/gif,image/jpeg" class="hidden upload-file hide" ref="logoInput">
							<div class="invalid-feedback">
                                <div class="error" v-if="!$v.recruiterForm.logo.isTrueImage">{{ $t('recruiter-profile.image-is-invalid') }}</div>
                            </div>
						</div>
						<div class="form-group">							
                                <div class="position-relative img-block">
                                    <img :src="recruiterForm.logo_url" class="mb-1 img-fluid img-contain" alt="Recruiter Logo" />                                    
                                    <button type="button" v-show="recruiterForm.logo_url != defaultImageUrl" @click="deleteLogo" class="close-btn" title="削除">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </div>	
						</div>						
					</div>

					<div class="my-3 p-3 border" id="incharge-photo">
						<div class="form-group">
							<label class="mb-3">{{ $t('recruiter-profile.incharge-photo') }}</label><br/>
							<label>{{ $t('recruiter-profile.logo-and-incharge-text') }}</label>
							<div :class="['upload-content', $v.recruiterForm.incharge_photo.$error ? 'is-invalid':'']"
								@drop.prevent="onInchargePhotoChange" @dragover.prevent>
								<p><span class="icon icon-upload"><span class="path1"></span><span class="path2"></span></span></p>
								<label class="pl-1 pr-1 pl-sm-3 pr-sm-3 mb-0">{{ $t('recruiter-profile.drag-and-drop') }}</label>
								<div class="upload-btn-wrapper">
								<button type="button" class="btn upload-btn mx-3" 
									@click="$refs.inchargePhotoInput.click()">{{ $t('recruiter-profile.choose-file') }}</button>
								</div>
								
							</div>
							<div class="invalid-feedback">
                                <div class="error" v-if="!$v.recruiterForm.incharge_photo.isTrueImage">{{ $t('recruiter-profile.image-is-invalid') }}</div>
                            </div>
							<input type="file" accept="image/x-png,image/gif,image/jpeg" class="hidden upload-file hide" @change="onInchargePhotoChange" ref="inchargePhotoInput">
						</div>
						<div class="form-group">
							<div class="col-5 p-0 border-0">
								<div class="position-relative scout-box-image">
                                    <img :src="recruiterForm.incharge_photo_url" class="img-fluid mb-1 img-contain" alt="Recruiter Incharge Photo" />                                   
                                    <button type="button" v-show="recruiterForm.incharge_photo_url != defaultImageUrl" @click="deleteInchargePhoto" class="close-btn">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                                    </div>
							</div>
						</div>
					</div>

					<div class="my-3 p-3 border" id="related-photo">
						<div class="form-group">
							<label class="mb-3">{{ $t('recruiter-profile.related-images') }}</label><br/>
							<label>{{ $t('recruiter-profile.related-text') }}</label>
							<div :class="['upload-content', $v.temp_related_image.$error ? 'is-invalid':'']"
								 @drop.prevent="onRelatedImagesChange" @dragover.prevent>
								<p><span class="icon icon-upload"><span class="path1"></span><span class="path2"></span></span></p>
								<label class="pl-1 pr-1 pl-sm-3 pr-sm-3 mb-0">{{ $t('recruiter-profile.drag-and-drop') }}</label>
								<div class="upload-btn-wrapper">
								<button type="button" class="btn upload-btn mx-3" 
									@click="$refs.relatedImagesInput.click()">{{ $t('recruiter-profile.choose-file') }}</button>
								</div>								
							</div>
							<!-- <div class="invalid-feedback">
                                <div class="error" v-if="!$v.temp_related_image.isTrueImage">{{ $t('recruiter-profile.image-is-invalid') }}</div>
                            </div> -->
							<input type="file" class="hidden upload-file hide" accept="image/x-png,image/gif,image/jpeg" multiple @change="onRelatedImagesChange" ref="relatedImagesInput">
						</div>
						<div class="row m-0">
							<div class="col-12 col-sm-6 mb-s-3">
                                <div class="row">							
                                    <div class="col-6 related-block" v-if="showRelatedImage(0)">                               
                                        <img :src="recruiterForm.related_images[0].file_url" class="img-contain" alt="Recruiter related image 1" />                                    
                                        <button type="button" @click="deleteRelatedImage(0)" class="close-btn"><span class="glyphicon glyphicon-remove"></span></button>                                								
                                    </div>

                                    <div class="col-6 related-block" v-if="showRelatedImage(1)">                            
                                        <img :src="recruiterForm.related_images[1].file_url" class="img-contain" alt="Recruiter related image 2" />
                                        <button type="button" @click="deleteRelatedImage(1)" class="close-btn"><span class="glyphicon glyphicon-remove"></span></button>                            
                                    </div>
                                </div>
							</div>
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <div class="col-6 related-block" v-if="showRelatedImage(2)">                               
                                        <img :src="recruiterForm.related_images[2].file_url" class="img-contain" alt="Recruiter related image 3" />
                                        <button type="button" @click="deleteRelatedImage(2)" class="close-btn"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>  
                                    <div class="col-6 related-block" v-if="showRelatedImage(3)">                                
                                        <img :src="recruiterForm.related_images[3].file_url" class="img-contain" alt="Recruiter related image 4" />
                                        <button type="button" @click="deleteRelatedImage(3)" class="close-btn"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>                               
                                </div>
                            </div>
						</div>
					</div>

					<div class="my-3 p-3 border" id="youtube-link">
						<div class="form-group">
							<label>{{ $t('recruiter-profile.video') }}</label>
							<div class="row">
								<label class="col-sm-12 col-form-label m-0">{{ $t('recruiter-profile.youtube-url') }}</label>
								<div class="col-sm-12">
									<input type="text" :class="['form-control',$v.recruiterForm.video.$error?'is-invalid':'']"  v-model.trim="$v.recruiterForm.video.$model">
									<div class="invalid-feedback">
										<div class="error" v-if="!$v.recruiterForm.video.matchYoutubeUrl">{{ $t('recruiter-profile.youtube-url-is-invalid') }}</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="my-3 p-3 border" id="pr">
						<div class="form-group">
							<label>{{ $t('recruiter-profile.company-pr') }}</label>
							<textarea class="form-control" rows="10" v-model="recruiterForm.company_pr"></textarea>
						</div>
					</div>

					<div class="text-center">
						<button type="submit" class="btn recruiter-primary-color btn-large border-style">{{ $t('recruiter-profile.save') }}</button>
					</div>
					
				</form>
			</div>

			<!-- <div class="col-4">
				<div class="legends" v-if="showNavigationPane">
					<h4 class="text-primary">{{ $t('recruiter-profile.page-index') }}</h4>
					<div class="list-group">
						<a class="list-group-item list-group-item-action text-primary" href="#information" v-scroll-to="{ el: '#information' }">
							{{ $t('recruiter-profile.basic-info') }}
						</a>
						<a class="list-group-item list-group-item-action text-primary" href="#logo" v-scroll-to="{ el: '#logo' }">
							{{ $t('recruiter-profile.company-logo') }}
						</a>
						<a class="list-group-item list-group-item-action text-primary" href="#incharge-photo" v-scroll-to="{ el: '#incharge-photo' }">
							{{ $t('recruiter-profile.incharge-photo-index') }}
						</a>
						<a class="list-group-item list-group-item-action text-primary" href="#related-photo" v-scroll-to="{ el: '#related-photo' }">
							{{ $t('recruiter-profile.related-images') }}
						</a>
						<a class="list-group-item list-group-item-action text-primary" href="#youtube-link" v-scroll-to="{ el: '#youtube-link' }">
							{{ $t('recruiter-profile.video') }}
						</a>
						<a class="list-group-item list-group-item-action text-primary" href="#pr" v-scroll-to="{ el: '#pr' }">
							{{ $t('recruiter-profile.company-pr') }}
						</a>
					</div>
				</div>
			</div> -->

		</div>
	</div>	
</template>
<script>
import { required,between,withParams,maxLength,email,helpers,url,sameAs,numeric } from 'vuelidate/lib/validators';
import { isFurigana, matchYoutubeUrl } from '../../partials/common';

const alphaNumAndValidator = (ph_number) => {
  if (typeof ph_number === 'undefined' || ph_number === null || ph_number === '') {
    return true;
   }
 	return (/^[\+\d]?(?:[\+\][\d-.\s()]*)$/m).test(ph_number);
}

const isTrueImage = (value) => {
	if (!value || typeof value === 'string') {
    	return true;
	}
	let file = value;
  	return file.type.startsWith('image');
}

function buildFormData(formData, data, parentKey) {	
	if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
		Object.keys(data).forEach(key => {
			buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
		});
	} else {
		const value = data == null ? '' : data;
		formData.append(parentKey, value);
	}
}

export default {
    metaInfo: {
        title: 'BORDERLESS WORKING（ボーダレスワーキング）海外就職支援',
    }, 
	data() {
		return {
			showNavigationPane: true,	
			defaultImageUrl: '',
			questionType: '',		
			recruiterForm: {
				registered_place_type:'',
				recruiter_name: '',
				num_of_employees: '',
				incharge_name_furigana: '',
				video: '',
				related_images: [],
				delete_related_images: [],
				delete_logo: false,
				delete_incharge_photo: false,
			},
			temp_related_image: null,			
		}
	},
	methods: {
	  isYear: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) ) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
      isMonth: function(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      var res = String.fromCharCode(charCode);
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) ) {
        evt.preventDefault();
      } 
      else
      {
        var getvalue = event.target.value + event.key;
        if(getvalue > 0 && getvalue <= 12)
        {
            return true;
        }
        else
        {
            evt.preventDefault();
        }
      }
},
		showRelatedImage(index) {
			return this.recruiterForm.related_images[index];			
		},
		deleteRelatedImage(index) {
			// this.$alertService.showConfirmDialog(null, "Delete image?")
			// .then(r => {
			// 	if (r.value) {
					let uploadedFile = this.recruiterForm.related_images.splice(index, 1);					
					let filename = uploadedFile[0].url.slice(0, uploadedFile[0].url.indexOf('.'));
					if (this.recruiterForm.delete_related_images.indexOf(filename) == -1)
						this.recruiterForm.delete_related_images.push(filename);
				// }
			// })
		},
		deleteLogo() {
			// this.$alertService.showConfirmDialog(null, "Are you sure to delete logo?")
			// .then(r => {
			// 	if (r.value) {
					this.recruiterForm.logo = '';
					this.recruiterForm.logo_url = this.defaultImageUrl;
					this.recruiterForm.delete_logo = true;
			// 	}
			// });
			
		},
		deleteInchargePhoto() {
			// this.$alertService.showConfirmDialog(null, "Are you sure to delete incharge person photo?")
			// .then(r => {
			// 	if (r.value) {
					this.recruiterForm.incharge_photo = '';
					this.recruiterForm.incharge_photo_url = this.defaultImageUrl;
					this.recruiterForm.delete_incharge_photo = true;
			// 	}
			// });
		},
		onQuestionTypeChange() {
			if (this.questionType == this.$configs.questions.other) {
				this.recruiterForm.question = '';
			} else { 
				this.recruiterForm.question = this.questionType;
			}
		},
		onLogoChange(e) {			
			const files =  e.target.files || e.dataTransfer.files;
			const file = files[0];
			const ext = file.name.split(".").pop().toLowerCase(); 
			if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    
                this.recruiterForm.logo = file;
                this.$v.recruiterForm.logo.$touch();
                if (this.$v.recruiterForm.logo.$error) {				
                    return;
                }			
                this.recruiterForm.logo_url = URL.createObjectURL(file);
                this.recruiterForm.delete_logo = false;
                
            } else {
                                    
                this.$alertService.showWarningDialog(null, this.$t('alertMessage.imageTypeWarning'),this.$t('common.close')).then(r => { });
                
            }
            e.target.value = '';
			
		},
		onInchargePhotoChange(e) {
			
			const files =  e.target.files || e.dataTransfer.files;
			const file = files[0];
			const ext = file.name.split(".").pop().toLowerCase(); 
			if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                this.recruiterForm.incharge_photo = file;
                this.$v.recruiterForm.incharge_photo.$touch();
                if (this.$v.recruiterForm.incharge_photo.$error) {				
                    return;
                }			
                this.recruiterForm.incharge_photo_url = URL.createObjectURL(file);
                this.recruiterForm.delete_incharge_photo = false;
                    
            } else {
                                    
                this.$alertService.showWarningDialog(null, this.$t('alertMessage.imageTypeWarning'),this.$t('common.close')).then(r => { });
                
            }
            e.target.value = '';
			
		},
		onRelatedImagesChange(e) {
		
			const files =  e.target.files || e.dataTransfer.files;
			if ((files.length + this.recruiterForm.related_images.length) > 4) {
				this.$alertService.showErrorDialog(null, this.$t('recruiter-profile.max_file'));
				return;				
			}
			// Validation of files
			// for (let index = 0; index < files.length; index++) {
			// 	this.temp_related_image = files[index];
			// 	this.$v.temp_related_image.$touch();
			// 	if (this.$v.temp_related_image.$error) 
			// 		return;
			// }
			let file_names = [ 'photo_1', 'photo_2', 'photo_3', 'photo_4' ];
			let taken = this.recruiterForm.related_images.map(x => { return x.url.slice(6, x.url.indexOf('.')); });
			let availables = file_names.filter(x => !taken.includes(x));
			const vm = this;
			Array.from(files).forEach((file, i) => {
                const ext = file.name.split(".").pop().toLowerCase(); 
				if (ext == "png" || ext == "jpg" || ext == "jpeg") {
					let relateImagesIndex = file_names.indexOf(availables.shift());
					let filename = `${vm.recruiterForm.recruiter_number}_${file_names[relateImagesIndex]}`;
					let extension = file.type.split('/').pop();
					let entry = {
						file: file,
						url: `${filename}.${extension}`,
						file_url: URL.createObjectURL(file),
						file_type: 'photo',
						user_type: 'recruiter',
					}				
					vm.recruiterForm.related_images.splice(relateImagesIndex, 0, entry);
					let deleteFlagIndex = vm.recruiterForm.delete_related_images.indexOf(filename);
					if (deleteFlagIndex == -1) {
						vm.recruiterForm.delete_related_images.splice(deleteFlagIndex, 1);
					}
						
                } else {
                                        
                    this.$alertService.showWarningDialog(null, this.$t('alertMessage.imageTypeWarning'),this.$t('common.close')).then(r => { });
                   
                }
				
            });	
            e.target.value = '';		
		},
		updateProfile() {
			this.$v.recruiterForm.$touch();
			if (this.$v.recruiterForm.$invalid) {				
                return;
			}
			let data = new FormData();
			buildFormData(data, this.recruiterForm);
			let loading = this.$loading.show();
			this.$api.post('/recruiter/profile', data)
			.then(() => { 
            this.$store.dispatch('user_image');	
            this.$alertService.showSuccessDialog(null, this.$t('alertMessage.updateSuccess'), this.$t('common.close'));	
			this.$router.push({ name: 'RecruiterProfileSetting' }) 
			})
			.catch((e) => { console.log(e) })
			.finally(() => { 
				this.$store.dispatch('user_image');	 
				loading.hide() 
				});
		}
	},
	created() {
		let loading = this.$loading.show();
		this.$api.get('/recruiter/profile')
		.then(r => {
			console.log(r);
			this.recruiterForm = r.data.data;
			console.log(this.recruiterForm);
			// --split establishment_date into year and month
			const establishment_date = new Date(this.recruiterForm.establishment_date);
			this.recruiterForm.establishment_year = establishment_date.getFullYear();
			this.recruiterForm.establishment_month = establishment_date.getMonth() + 1;
			this.recruiterForm.delete_related_images = [];

			// --map question type
			if (Object.values(this.$configs.questions).includes(this.recruiterForm.question)) {
				this.questionType = this.recruiterForm.question;
			} else {
				this.questionType = this.$configs.questions.other;
			}
		})
		.catch((e) => {			
			console.log(e);
			// this.$alertService.showErrorDialog(null, 'エラーが発生しました。');
		})
		.finally(() => loading.hide());

		this.$api.get('/default-image')
		.then(r => { this.defaultImageUrl = r.data.data; });
	},
	validations: {
		recruiterForm: {
			recruiter_name: { required },
			// num_of_employees: { numeric },
			phone1: { required, alphaNumAndValidator },
			phone2: { alphaNumAndValidator },			
			website: {  url },
			incharge_name: { required },
			incharge_name_furigana: { required, isFurigana },
			question: { required, maxLength: maxLength(54) },
			answer: { required },
			video: { matchYoutubeUrl },
			logo: { isTrueImage },
			incharge_photo: { isTrueImage },
		},
		temp_related_image: { isTrueImage },
	}
}
</script>
<style lang="scss"scoped>
.lab-right{
	float: right;
	margin-right: 14px;
	margin-top: -27px;	
}
.legends  {	
	position: -webkit-sticky; /* Safari */
  	position: sticky;
	top: 30px;
}
.form-control {
	border-radius: 0px;
}
.recruiter-profile-edit{
    top: 8px;
    padding: 8px 20px;
    border-radius: 50px;
    color: #919191;
    position: absolute;
    border: 1px solid;
    right: 40px;
    z-index: 1;
    background: #fff;
}
.profile-edit .form-control{
    width: 70%;    
}
/* intro edit */
.upload-content {
   	position: relative;
    display: flex;
    width: 70%;
    padding: 20px 0;
    background: #fff;
    color: #333;
    justify-content: center;
    align-items: center;
}
.upload-content::after {
    content: "";
    position: absolute;
    background: linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(to right, #B5C4CC 50%, rgba(255, 255, 255, 0) 0%), linear-gradient(#B5C4CC 50%, rgba(255, 255, 255, 0) 0%);
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
  border: 1px solid #B5C4CC;
  background-color: #fff;
  padding: 8px 20px;
  border-radius: 8px;
  font-weight: bold;
  color: #333; 
  box-shadow: none;
}

.upload-file {
  /*position: absolute;
  left: 0;
  top: 0;*/
  opacity: 0;
  /*width: 100%;
  height: 45px;*/
}
.base-align-btn{
    align-self: flex-start;
    margin-top: auto;
}
.align-label{
    display: inline-block;
    width: 185px;
}
.upload-content .icon {
    font-size: 50px;
}
.close-btn {
    position: absolute;
    top: -17px;
    right: 0px;
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
//images
 .scout-box-image {      
    display: flex;
    justify-content: center;
    align-items: center;
    width: 180px;
    height: 240px;
    padding: 10px;
    border: 1px solid #ddd;
.img-contain{
    width: 100%;
    height: 100%;       
    object-fit: contain;      
    object-position: center;
}
}
.d-table-spacing{
    display: table!important;
    border-spacing: 10px;
}
.profile-edit .form-control.qa-select {
	padding: .3rem .75rem .375rem .75rem;
}
@media (max-width: 820px){
.profile-edit .form-control{
    width: 85%;
}
.upload-content{
    width: 85%;
}
}
@media (max-width: 640px){
.profile-edit .form-control{
    width: 100%;
}
.profile-edit .form-control.qa-select {
	overflow: hidden;
}
.upload-content{
    width: 100%;
}
.upload-btn{
    padding: 5px 10px;
}
}
@media (max-width: 480px){
.user-head-tit{
	padding: 15px;
	font-size:17px
}
.recruiter-profile-edit{
	top: 12px;
	width: 80px;
	min-width: auto;
	padding: 3px 0;
	right: 15px;
}
.tab-content{
	padding: 0 15px 15px 15px;
}
.upload-content{
    width: 100%;
    flex-wrap: wrap;
	label{
		margin-bottom: .5rem !important;
		line-height: 1.2;
		text-align: center;
	}
	p{
		display: block;
		width: 100%;
		margin-bottom: 0.5rem;
		text-align: center;
	}
}
.mb-s-3{
	margin-bottom: 1rem!important;
}
.form-group label{
	width: 100%;
}
}
</style>