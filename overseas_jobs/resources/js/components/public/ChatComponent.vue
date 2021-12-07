<template>
    <div id="boxchat">
        <div ref="draggableContainer" :class="`box-chat ${isToggled == true ? 'chat_show' : 'chat_hide' }`">  
                <div class="main-chat">
                    <div class="col-md-4 tab-left float-left">
						<div class="sp chat-head">
							<div class="close" @click="closeChatBox()"><i title="Close" class="fa fa-times pr-1" style="color: #333;"></i></div>
							<div class="chat-tit"><p>チャット先を選択してください</p></div>																		
						</div>
						<ul class="list-user-search">
							<li class="has-input">
								<div class="search-container">
									<span class="fa fa-search"></span>
									<input @keydown.enter="getUsers" v-model="filter_text" type="text" class="filter-input" placeholder="相手を検索" />
								</div>
							</li>
							<li v-if="userListSearching" class="user-list-searching">
								<img width="25" src="/images/loading.gif" alt="loading">
							</li>
						</ul>
						<transition-group name="list-user" class="list-user" :class="currentUser.role_id == 2 ? 'list-rec-user' : 'list-job-user'" ref="chattables" tag="ul">
                            <li v-for="item in jobs" v-bind:key="item.management_number" @click="getMessage(item)" :class="[getActiveClass(item)]">
                                <div class="status">
                                    <div v-if="online.includes(item.user_id)" >
                                    <i class="fa fa-circle text-success"></i>
                                    </div>
                                    <div v-else>
                                    <i class="fa fa-circle" style="color:#e1e1e1"></i>
                                    </div>									
                                </div>
                                <div class="name">
									<!-- Recruiter display -->
									<p v-if="currentUser.role_id == 2">
										<span class="d_name sp-dname">{{item.jobseeker_name}}</span>
										<span class="d_name pc-dname">{{item.jobseeker_name}}</span>
										<span style="font-size:11px;margin-right:-7px;" class="text-secondary pull-right">{{ item.last_chat_time|last_time(timezone) }}</span></p>
                                    <p v-if="currentUser.role_id == 2" class="txt-ellipsis-1">{{item.title}}</p>
									<!-- Jobseeker display -->
									<p v-if="currentUser.role_id == 3">
										<span class="d_name sp-dname">{{item.recruiter_name}}</span>
										<span class="d_name pc-dname">{{item.recruiter_name}}</span>
										<span style="font-size:11px;margin-right:-7px;" class="text-secondary pull-right">{{ item.last_chat_time|last_time(timezone) }}</span></p>
                                    <p v-if="currentUser.role_id == 3" class="txt-ellipsis-1">{{item.title}}</p>
                                </div>                               
                                <div class="unread" v-if="item.unread > 0">
                                    <span v-if="item.unread <= 5">{{item.unread}}</span>
                                    <span class="plus" v-else>5+</span>
                                </div>                              
                            </li>
						</transition-group>
                    </div>
					<div @show="showChattingBox" class="sp">
						<transition name="slideRight">
							<div class="chat-panel" v-if="showChattingBox">
								<div :class="['col-sm-12 tab-right float-right',showChattingBox ? 'mobile-chat-right-pane':'']">
									<div class="header-chat d-flex" :class="currentUser.role_id == 2 ? 'recruiter-primary-color recruiter-header-border' : 'job-primary-color job-header-border'">									
										<span class="w-4" @click="hideChattingBox()"><i class="fa fa-arrow-left" title="Close" style="color:#fff;"></i></span>																																	
										<div class="name w-90">										
											<p>{{number}}</p>
											<span class="txt-ellipsis-1">{{title}}</span>
											<span class="txt-ellipsis-1" style="font-size:12px;">{{showName}}</span>
										</div>
										<div class="close w-6" @click="closeChatBox()">
											<i class="fa fa-times-circle-o" title="Close"></i>
										</div>
									</div>
									<div class="content-chat content-chat-sp" ref="scrollChat" id="chat-scroll-container-mobile" v-chat-scroll="{always: false, smooth: true}"  @v-chat-scroll-top-reached="scrollTop(channel,current_page)">
										<div class="chat-history">
											<div v-if="loading" class="loading d-flex justify-content-center">
												<div class="spinner-border text-info">
													<span class="sr-only"></span>
												</div>
											</div>
											<ul class="chat-msg">
												
												<li v-for="message in messages" :key="message.id">
													
													<div :class="`message ${ isSender(message) ? 'my-message float-right ' + getPrimaryColorLight() : 'other-message float-left'}`">
														<div v-if="!isSender(message)" :class="['name',getPrimaryColorText()]">
														
															<strong>{{ senderName(message) }}</strong>
														</div>
														<!-- <pre>{{ message.message }}</pre> -->
														<pre v-if="message.message_type == 'text'">{{ message.message }}</pre>
														<div v-if="message.message_type == 'file'" :class="`file-message d-flex flex-column align-items-center ${message.expired ? 'file-expired' : ''}`">
															<div>
																<i :class="`fa fa-file-text-o mr-2 ${isSender(message)?'text-white':''}`"></i>{{ message.message|trim_file_message }}
															</div>
															<div class="btn-download" @click="downloadFile(message)" v-if="!message.expired">
																<i class="fa fa-download"></i>&nbsp;ダウンロード
															</div>
														</div>
														<!-- Message delete button -->
														<p class="message-delete" @click="deleteMessage(message)" v-if="false">
															<i class="fa fa-times"></i>
														</p>
													</div>
													<!-- Message Time -->
													<div :class="['time',isSender(message) ? 'float-right text-right' : '']">
														<span v-if="isSender(message) && message.status">
															<i :class="`fa fa-check ${getPrimaryColorText()}`"></i>
														</span>
														<span>{{ message.created_at|xdate(timezone) }}</span>
														<div v-if="message.expired">
															<span>このファイルのダウンロード期間が過ぎました</span>
														</div>
													</div>
													
												</li>
												<div class="clearfix"></div>
											</ul>
										</div>
									</div>
									<div :class="`footer-chat ${getFooterChatColor()}`">
										<!-- <div v-if="typing" class="typing"><img width="20" src="/images/loading.gif" alt="loading"></div>
										
										<div class="d-flex w-100" style="padding-top: 20px;align-self: flex-end;align-items: center;">
										<div id="chrom-txtarea">
										<textarea :rows="messageLines" @keyup="userTyping" @keydown.alt.enter="sendMessage" @keydown.ctrl.enter="sendMessage" v-model="message_payload.message" placeholder="メッセージを入力" 
											@input="calcTextareaHeight" :disabled="!message_payload.scoutid_or_applyid">
										</textarea>										
										</div>
										<i class="fa fa-paper-plane-o" @click="sendMessage"></i>
										</div> -->

										<div v-if="typing" class="typing"><img width="50" src="/images/loading.gif" alt="loading"></div>
										<div class="d-flex" style="padding-top:10px;align-self: flex-end;align-items: center; width: 100%;" v-if="!readOnly">
											<!-- File Input -->
											<i class="btn-attachment fa fa-paperclip fa-rotate-90" @click="$refs.fileInput.click()" v-if="message_payload.scoutid_or_applyid"></i>
											<input type="file" @change="onFileChange" class="d-none" ref="fileInput">
											<!-- Text Input -->
											<div id="chrom-txtarea" v-if="message_payload.message_type == 'text'">
												<textarea :rows="messageLines" @keyup="userTyping" @keydown.alt.enter="sendMessage" @keydown.ctrl.enter="sendMessage" v-model="message_payload.message" placeholder="メッセージを入力" 
													@input="calcTextareaHeight" :disabled="!message_payload.scoutid_or_applyid" ref="txtMessage">
												</textarea>
											</div>
											<div id="chrom-txtarea" v-else-if="message_payload.message_type == 'file'">
												<div :class="`file-upload-preview badge ${getPrimaryColor()} text-white py-2`" class="attach-name" style="max-width: 190px;">
													<p class="file-name">{{ message_payload.message }}</p>
													<p class="file-delete" @click="deletePreviewFile">
														<!-- <i class="fa fa-times"></i> -->
														<span class="fa fa-times-circle-o"></span>
													</p>
												</div>
											</div>
											
											<i class="btn-send fa fa-paper-plane-o" @click="sendMessage" title=""></i>
										</div>
									</div>
								</div>
							</div>
						</transition>
					</div>
					<div class="col-md-8 tab-right float-right pc">
							<div class="header-chat" :class="currentUser.role_id == 2 ? 'recruiter-primary-color' : 'job-primary-color'">
								<div class="name" @mousedown.self="dragMouseDown">
									<span>{{number}}</span>
									<span class="txt-ellipsis-1">{{title}}</span>
									<span class="txt-ellipsis-1" style="font-size:12px;">{{showName}}</span>
								</div>
								<div class="draggable-filler" @mousedown.self="dragMouseDown"></div>
								<div class="close" @click="closeChatBox()">
									<i class="fa fa-times-circle-o" title="Close"></i>
								</div>
							</div>
							<div :class="['content-chat',readOnly?'read-only':'']" ref="scrollChat" id="chat-scroll-container" v-chat-scroll="{always: false, smooth: true}"  @v-chat-scroll-top-reached="scrollTop(channel,current_page)">
								<div class="chat-history">
									<div v-if="loading" class="loading d-flex justify-content-center">
										<div class="spinner-border text-info">
											<span class="sr-only"></span>
										</div>
									</div>
									<ul>
										<li v-for="message in messages" :key="message.id">
											
											<div :class="`message ${ isSender(message) ? 'my-message float-right ' + getPrimaryColor() : 'other-message float-left'}`">
												<div v-if="!isSender(message)" :class="['name',getPrimaryColorText()]">
													<strong>{{ senderName(message) }}</strong>
												</div>
												<pre v-if="message.message_type == 'text'">{{ message.message }} </pre>
												<div v-if="message.message_type == 'file'" :class="`file-message d-flex flex-column align-items-center ${message.expired ? 'file-expired' : ''}`">
													<div>
														<i :class="`fa fa-file-text-o mr-2 ${isSender(message)?'text-white':''}`"></i>{{ message.message|trim_file_message }}
													</div>
													<div class="btn-download" @click="downloadFile(message)" v-if="!message.expired">
														<i class="fa fa-download"></i>&nbsp;ダウンロード
													</div>
												</div>
												<!-- Message delete button -->
												<p class="message-delete" @click="deleteMessage(message)" v-if="false">
													<i class="fa fa-times"></i>
												</p>
											</div>
											<!-- Message Time -->
											<div :class="['time',isSender(message) ? 'float-right text-right' : '']">
												<span v-if="isSender(message) && message.status">
													<i :class="`fa fa-check ${getPrimaryColorText()}`"></i>
												</span>
												<span>{{ message.created_at|xdate(timezone) }} ----- {{timezone}}</span>
												<div v-if="message.expired">
													<span>このファイルのダウンロード期間が過ぎました</span>
												</div>
											</div>
										</li>
										<div class="clearfix"></div>
									</ul>
								</div>
							</div>
							<div :class="`footer-chat ${getFooterChatColor()}`">
								<!-- <div v-if="typing" class="typing"><img width="28" src="/images/loading.gif" alt="loading"></div>
								
								<div class="d-flex" style="padding-top: 20px;align-self: flex-end;align-items: center;">
								<div id="chrom-txtarea">
								<textarea :rows="messageLines" @keyup="userTyping" @keydown.alt.enter="sendMessage" @keydown.ctrl.enter="sendMessage" v-model="message_payload.message" placeholder="メッセージを入力" 
									@input="calcTextareaHeight" :disabled="!message_payload.scoutid_or_applyid">
								</textarea>								
								</div>
								<i class="fa fa-paper-plane-o" @click="sendMessage"></i>
								</div> -->

								<div v-if="typing" class="typing"><img width="50" src="/images/loading.gif" alt="loading"></div>
								<div class="d-flex" style="padding-top:10px;align-self: flex-end;align-items: center;" v-if="!readOnly">
									<!-- File Input -->
									<i class="btn-attachment fa fa-paperclip fa-rotate-90" @click="$refs.fileInput.click()" v-if="message_payload.scoutid_or_applyid"></i>
									<input type="file" @change="onFileChange" class="d-none" ref="fileInput">
									<!-- Text Input -->
									<div id="chrom-txtarea" v-if="message_payload.message_type == 'text'">
										<textarea :rows="messageLines" @keyup="userTyping" @keydown.alt.enter="sendMessage" @keydown.ctrl.enter="sendMessage" v-model="message_payload.message" placeholder="メッセージを入力" 
											@input="calcTextareaHeight" :disabled="!message_payload.scoutid_or_applyid" ref="txtMessage">
										</textarea>
									</div>
									<div id="chrom-txtarea" v-else-if="message_payload.message_type == 'file'">
										<div :class="`file-upload-preview badge ${getPrimaryColor()} text-white py-2`" class="attach-name">
											<p class="file-name">{{ message_payload.message }}</p>
											<p class="file-delete" @click="deletePreviewFile">
												<!-- <i class="fa fa-times"></i> -->
												<span class="fa fa-times-circle-o"></span>
											</p>
										</div>
									</div>
									
									<i class="btn-send fa fa-paper-plane-o" @click="sendMessage" title=""></i>
								</div>
							</div>
						</div>
            </div>             
        </div>
        <div class="btn-chat" :class="currentUser.role_id == 2 ? 'recruiter-primary-color' : 'job-primary-color'" @dblclick="resetBox">
			<div class="sp">
				<div @click="openChatBox()">
					<i class="fa fa-comments-o"></i>
					<div class="count-all" v-if="count_all > 0">
						<span v-if="count_all <= 5">{{count_all}}</span>
						<span class="plus" v-else>5+</span>
					</div>    
				</div>
			</div>
			<div class="pc">
				<div @click="isToggled = !isToggled">
					<i class="fa fa-comments-o"></i>
					<div class="count-all" v-if="count_all > 0">
						<span v-if="count_all <= 5">{{count_all}}</span>
						<span class="plus" v-else>5+</span>
					</div>    
            	</div>
			</div>           
        </div>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import Vue from 'vue';

function buildFormData(formData, data, parentKey) {	
	console.log('formdata',parentKey)
	if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
		Object.keys(data).forEach(key => {
			console.log('key',key)
			buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
		});
	} else {
		const value = data == null ? '' : data;
		formData.append(parentKey, value);
	}
}

function utcToTimezone(value, timezone, showSecond = true) {
	if (!value || !timezone) return value;
	let n = (typeof value === "string" ? new Date(value) : value);
	const utc = Date.UTC(n.getFullYear(),n.getMonth(),n.getDate(),n.getHours(),n.getMinutes(),n.getSeconds(),0);
	let v = new Date(new Date(utc).toLocaleString("en-US", {timeZone: timezone}));
	let m = v.getMonth() + 1;
	m = m < 10 ? '0'+m : m.toString();
	let d = v.getDate();
	d = d < 10 ? '0'+d : d.toString();
	let h = v.getHours();
	h = h < 10 ? '0'+h : h.toString();
	let min = v.getMinutes();
	min = min < 10 ? '0'+min : min.toString();
	let s = v.getSeconds();
	s = s < 10 ? '0'+s : s.toString();
	return showSecond ? `${v.getFullYear()}-${m}-${d} ${h}:${min}:${s}` : `${v.getFullYear()}-${m}-${d} ${h}:${min}`;
}

export default {
	name: 'ChatComponent',
	filters: {
		xdate: function(value, tz) {
			console.log( 'tt',value, tz)

			if (!value || !tz) return value;

			return utcToTimezone(value, tz, false);
		},
		last_time(value, timezone) {
			if (!value) return '';
			let v = new Date(utcToTimezone(value, timezone));
			let t = new Date(new Date().toLocaleString('en-US',{ timeZone: timezone }));
			let diff = t - v;
			let diff_days = Math.ceil(diff/(1000 * 60 * 60 * 24)) - 1;
			if (t.getDate() == v.getDate() && t.getMonth() == v.getMonth() && t.getFullYear() == v.getFullYear()) {
				// --Is today
				let h = v.getHours();
				h = h < 10 ? '0'+h : h.toString();
				let m = v.getMinutes();
				m = m < 10 ? '0'+m : m.toString();
				return `${h}:${m}`;
			} else if (diff_days < 8) {
				// --Within week
				const days = [
					'日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'
				];
				const day = v.getDay();
				return days[day];
			} else {
				let m = v.getMonth() + 1;
				m = m < 10 ? '0'+m : m.toString();
				let d = v.getDate();
				d = d < 10 ? '0'+d : d.toString();
				return `${v.getFullYear()}-${m}-${d}`;
			}
		},
		trim_file_message(v) {
			if (v.length > 14) {
				return v.substring(14);
			} else {
				return v;
			}
		},
	},
    data(){
        return{
            jobs: [],
			filter_text: '',
			userListSearching: false,
            typing: false,
            isToggled: false,
            messages: [],
            online: [],
            current_page: 1,
            last_page: 1,
            channel: null,
            title: null,
            number: null,
            showName: null,
            role: null,
            username: null,
            loading: false,
            count_all: 0,
            positions: {
                clientX: undefined,
                clientY: undefined,
                movementX: 0,
                movementY: 0
			},
			// --Loggedin recruiter or jobseeker object
			loggedInUser: {},
			message_payload: {
				recruiter_id: 0,
				jobseeker_id: 0,
				speaker_id: 0,
				speaker_role_id: 0,
				scoutid_or_applyid: 0,
				type: '',
				message: '',
				created_at: new Date().toLocaleString('en-us', { timeZone: this.myTimeZone }),
				message_type: 'text',
				file_expired: false,
				file: null,
			},
			meta: undefined,
			showChattingBox: false,
			messageLines: 1,
			myTimeZone: null,
			readOnly: false,
        }
    },
    mounted() {
        const vm = this;
        $(window).on('popstate', function(event) {    
            $("body","html").css("overflow", "unset");
            $("body").css("position", "unset");
        });

		if (!socket.connected) {
			socket.connect()
		}
		this.role = this.currentUser.role_id;
		this.getUsers();
        this.listenForSocket();
	},
	created() {
		this.$api.get('/me')
		.then(r => {
			this.loggedInUser = r.data.data;
			console.log('loginuser',this.loggedInUser)
			this.message_payload.speaker_id = this.loggedInUser.id;
			this.message_payload.speaker_role_id = this.currentUser.role_id;
			this.myTimeZone = r.data.data.extra.timezone;
			console.log('me',new Date().toLocaleString('en-us', { timeZone: this.myTimeZone }))
		})
	},
    methods: {
        listenForSocket() {
            socket.on("channel-chat", (data) => {
				if (this.isReceiver(data)) {
					this.messages.push(data);
					this.markAsRead();
					this.scrollToBottomMessage();
				}
				// --Reorder user list to show latest chat on top
				this.jobs.forEach(x => {
					if (x.scoutid_or_applyid == data.scoutid_or_applyid && 
						x.type == data.type) {
							x.last_chat_time = data.last_chat_time;
					}
				})
				this.reorderUserList();
            });
            socket.on("count-message", (data) => {
				this.jobs.forEach(job => {
					if (job.scoutid_or_applyid == data.scoutid_or_applyid && 
						job.type == data.type){
						job.unread++;
					}
					this.count_all += job.unread;
				});
				this.sumUnRead();
            });
            socket.on("usertyping", (data) => {
				if (this.isReceiver(data)) {
					if (!this.typing) this.typing = true;
				}
				setTimeout(() => {
					this.typing = false;
				}, 3000);
            });
            socket.on("usernames", (data) => {
				this.online = data;
            });
			socket.on("message-removed",data => {
				const i = this.messages.findIndex(x => x.id == data.id);
				if (i > -1) {
					// --Remove message if chat window open
					this.messages.splice(i, 1);
				} else {
					// --else update unread count
					let requireReorder = false;
					this.jobs.forEach(job => {
						if (job.scoutid_or_applyid == data.scoutid_or_applyid && job.type == data.type) {
							// --Update unread count
							const c = data.unread_receivers.filter(x => x.receiver_role_id == this.currentUser.role_id);
							if (c.length > 0 && job.unread > 0) {
								job.unread -= 1;
							}
							// --Update last chat time
							if (data.last_chat_time == job.last_chat_time) {
								job.last_chat_time = data.second_last_chat_time;
								requireReorder = true;
							}
						}
					});
					if (requireReorder) this.reorderUserList();
					this.sumUnRead();
				}
			});
			socket.on("message-been-read", data => {
				if (this.message_payload.scoutid_or_applyid == data.scoutid_or_applyid && this.message_payload.type == data.type) {
					
					this.messages.forEach((message, index) => {
						
						if (data.message_ids.includes(message.id)) {
							this.messages[index].status = 1;
							this.messages[index].read_time = data.read_time_utc;
							this.scrollToBottomMessage();
						}
					})
				}
			});
			socket.on("status-changed", data => {
				this.getUsers();
				if (data.scoutid_or_applyid == this.message_payload.scoutid_or_applyid && data.type == this.message_payload.type) {
					// --Read only when status is payment_confirmed 
					let ro_status = [
						this.$configs.scouts.payment_confirmed,
						this.$configs.job_apply.payment_confirmed,
						this.$configs.scouts.declined,
						this.$configs.job_apply.declined,
					];
					if (ro_status.includes(data.status)) {
						this.readOnly = true;
						this.scrollToBottomMessage();
					} else {
						this.readOnly = false;
					}

					let hidden_status = [
						this.$configs.scouts.declined,
						this.$configs.job_apply.declined,
					]
					if (hidden_status.includes(data.status)) {
						this.resetChatMessage(data);
					}
				}
			});
		},
		isSender(message_payload) {
			console.log('message_payload',message_payload)
			return (message_payload.speaker_role_id == this.currentUser.role_id);
		},
		senderName(message_payload) {
			console.log('message_payload',message_payload)
			switch (message_payload.speaker_role_id)
			{
				case 1:
					return "運営管理";

				case 2:
					return this.meta.recruiter_name;

				case 3:
					// --Todo: jobseeker name should be nickname depending on status
					return this.meta.jobseeker_name;
			}
		},
		isReceiver(message_payload) {
			console.log('isRece_payload',message_payload)
			let allow = false;
			if (message_payload.speaker_role_id != this.currentUser.role_id) {
					if (this.currentUser.role_id == 1) {
						allow = this.message_payload.scoutid_or_applyid == message_payload.scoutid_or_applyid && 
							this.message_payload.type == message_payload.type;
					} else if (this.currentUser.role_id == 2) {
						allow = this.loggedInUser.id == message_payload.recruiter_id && 
							this.message_payload.scoutid_or_applyid == message_payload.scoutid_or_applyid && 
							this.message_payload.type == message_payload.type;
					} else if (this.currentUser.role_id == 3) {
						allow = this.loggedInUser.id == message_payload.jobseeker_id && 
							this.message_payload.scoutid_or_applyid == message_payload.scoutid_or_applyid && 
							this.message_payload.type == message_payload.type;
					}
			} else {
				allow = false;
			}
			return allow;
		},
		getActiveClass(item) {
			if (this.message_payload.type == item.type && this.message_payload.scoutid_or_applyid == item.scoutid_or_applyid) {
				if (this.message_payload.speaker_role_id == 2) {
					return 'recruiter-primarylight-color active-chattable'
					;
				} else if (this.message_payload.speaker_role_id == 3) {
					return 'jobseeker-primarylight-color active-chattable';
				} else {
					return '';
				}
			} else {
				return '';
			}
		},
		getPrimaryColor() {
			if (this.message_payload.speaker_role_id == 2) {
				return 'recruiter-primary-color';
			} else if (this.message_payload.speaker_role_id == 3) {
				return 'job-primary-color';
			} else {
				return '';
			}
		},
		getPrimaryColorLight() {
			if (this.message_payload.speaker_role_id == 2) {
				return 'recruiter-primarylight-color';
			} else if (this.message_payload.speaker_role_id == 3) {
				return 'jobseeker-primarylight-color';
			} else {
				return '';
			}
		},
		getPrimaryColorText() {
			if (this.message_payload.speaker_role_id == 2) {
				return 'recruiter-primary-color-txt';
			} else if (this.message_payload.speaker_role_id == 3) {
				return 'job-primary-color-txt';
			} else {
				return '';
			}
		},
		getFooterChatColor() {
			if (this.message_payload.speaker_role_id == 2) {
				return 'recruiter-primary-footer-chat';
			} else if (this.message_payload.speaker_role_id == 3) {
				return 'jobseeker-primary-footer-chat';
			} else {
				return '';
			}
		},
		scrollUserIntoView() {
			this.$nextTick(() => {
				var element = (document.getElementsByClassName('active-chattable'))[0];
				element.parentNode.scrollTop = element.offsetTop - 30;
			})	
		},
        resetBox(){
            this.$refs.draggableContainer.style.top = 'unset';
            this.$refs.draggableContainer.style.left = 'unset';
		},
		loadAllUsers() {
			if (this.filter_text.length == 0){
				this.getUsers();
			}
		},
        getUsers(){
			let role_id = this.currentUser.role_id;
            let filter_text = encodeURIComponent(this.filter_text);
			this.userListSearching = true;
			this.jobs = [];
			Promise.all([
				// --scout chattables
				this.$api.get(`/chattables/${role_id}/scout?q=${filter_text}`)
				.then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response)),
				// --jobapply chattables
				this.$api.get(`/chattables/${role_id}/job-apply?q=${filter_text}`)
				.then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response))
			])
			.then((r) => {
                // console.log("getUsers",r)
				this.userListSearching = false;
				this.jobs = r[0].concat(r[1]);
				// --order by created_at of scouts/apply DESC
				this.jobs.sort((a,b) => {
					return new Date(b.o_created_at) - new Date(a.o_created_at);
				})

				if (filter_text.length == 0) {
					socket.emit('join', this.currentUser.id);
					this.sumUnRead();
				}
				this.reorderUserList();
			})
			.catch(() => {
				this.userListSearching = false;
			})
        },     
		reorderUserList() {
			this.jobs.sort((a,b) => {
				let dateA = new Date(a.last_chat_time);
				let dateB = new Date(b.last_chat_time);
				return dateB - dateA;
			});
		},    
        getMessage(model){			
			console.log('model',model);
			// --Read only when status is payment_confirmed 

			// kyan
			let ro_status = [
				this.$configs.scouts.payment_confirmed,
				this.$configs.job_apply.payment_confirmed,
			];
			//kyan
			this.readOnly = ro_status.includes(model.status);

			this.message_payload.recruiter_id = model.recruiter_id;
			this.message_payload.jobseeker_id = model.jobseeker_id;
			this.message_payload.scoutid_or_applyid = model.scoutid_or_applyid;
			this.message_payload.type = model.type;
			this.deletePreviewFile();
			this.showChattingBox = true;

			Promise.all([
				// --messages
				this.$api.get(`/messages/${this.message_payload.type}/${this.message_payload.scoutid_or_applyid}`)
				.then(r => Promise.resolve(r.data)).catch(error => Promise.reject(error.response)),
				// --metadata
				this.$api.get(`/messages/meta/${this.message_payload.type}/${this.message_payload.scoutid_or_applyid}`)
				.then(r => Promise.resolve(r.data.data)).catch(error => Promise.reject(error.response))
			])
			.then(r => {
				// --messages response
				this.messages = r[0].data.reverse();
				console.log('messagessss',r[0].data.reverse());
				this.current_page = r[0].current_page;
				this.last_page = r[0].last_page;
                // console.log("messages",r);

				// --metadata response
				let meta = r[1];
				console.log('meta',meta)
				this.username = this.currentUser.role_id == 2 ? meta.jobseeker.jobseeker_name : meta.recruiter.recruiter_name ;
				this.meta = {
					'jobseeker_name': meta.jobseeker.jobseeker_name,
					'recruiter_name': meta.recruiter.recruiter_name,
					'jobseeker_nickname': meta.jobseeker.jobseeker_nick_name,
					'recruiter_nickname': meta.recruiter.recruiter_nick_name,
					'jobseeker_user_id': meta.jobseeker.user_id,
					'recruiter_user_id': meta.recruiter.user_id,
					'admin_user_id': meta.admin.id,
					'admin_email': meta.admin.email,
					'jobseeker_email': meta.jobseeker.email,
					'recruiter_email': meta.recruiter.email,
				};
                this.title = meta.job.title;
                this.number = meta.job.management_number;
				
				// --Bind name based on speaker role
				switch (this.message_payload.speaker_role_id) {
					case 2:
						this.showName = meta.jobseeker.jobseeker_name;
						break;
					case 3:
						this.showName = meta.recruiter.recruiter_name;
						break;
				}

				this.loading = false;

				this.markAsRead();
				this.resetLine();
				this.scrollToBottomMessage();
			})
			.catch(r => {
				console.log(r);
			})

            // this.$refs.scrollChat.scrollTop = this.$refs.scrollChat.scrollHeight ;   //--NO longer works
        },
        unreadMessage(){
			this.jobs.forEach(job => {
				if(job.scoutid_or_applyid == this.message_payload.scoutid_or_applyid && 
					job.type == this.message_payload.type){
					job.unread = 0;
				}
			});
            this.sumUnRead();
        },
        sumUnRead(){
            let sum = 0;
			this.jobs.forEach(job => {
				sum += job.unread
			});
            this.count_all = sum;
        },
        markAsRead() {
			let opposite_id = this.currentUser.role_id == 2 ? this.messages[0].jobseeker_id : this.messages[0].recruiter_id;
            this.$api.post(`/messages/read/${this.message_payload.type}/${this.message_payload.scoutid_or_applyid}/${this.currentUser.role_id}/${opposite_id}`)
            .then(res => {
                const message_ids = res.data.data.message_ids;
				const read_time = res.data.data.read_time;
				const read_time_utc = res.data.data.read_time_utc;
                this.unreadMessage();
				if (message_ids.length > 0) {
					// --Broadcast message read event
					socket.emit("message-read",{
						scoutid_or_applyid: this.message_payload.scoutid_or_applyid,
						type: this.message_payload.type,
						message_ids: message_ids,
						read_time: read_time,
						read_time_utc: read_time_utc,
					});
				}
            })
            .catch(err => {
                console.log(err);
			});
        },
        userTyping(){
            socket.emit('typing', {
				speaker_role_id: this.message_payload.speaker_role_id,
				jobseeker_id: this.message_payload.jobseeker_id,
				recruiter_id: this.message_payload.recruiter_id,
				scoutid_or_applyid: this.message_payload.scoutid_or_applyid,
				type: this.message_payload.type,
			});
        },
        sendMessage(e){
            e.preventDefault();
			if (this.message_payload.message == '' ) { return }

			let data = new FormData();
			buildFormData(data, this.message_payload);
			console.log('aaa',this.message_payload);
			// --Save message using API
			this.$api.post('/messages', data)
			.then(r => {
				const message = r.data.data;
                const broadcast = {
					id: message.id,
					recruiter_id: this.message_payload.recruiter_id,
					jobseeker_id: this.message_payload.jobseeker_id,
					speaker_id: this.message_payload.speaker_id,
					speaker_role_id: this.message_payload.speaker_role_id,
					scoutid_or_applyid: this.message_payload.scoutid_or_applyid,
					type: this.message_payload.type,
					message: message.message,
					message_type: this.message_payload.message_type,
					created_at: message.created_at_utc,
					last_chat_time: message.created_at_utc,
					status: 0,
					read_time: null,
				};
				this.messages.push(broadcast);
				console.log('messagePush', this.message)
				// --Broadcast the message
				socket.emit("chat-message",broadcast);

				this.message_payload.message_type = 'text';
				this.message_payload.message = '';
				this.message_payload.file = null;

				// --Reorder user list to show latest chat on top
				this.jobs.forEach(x => {
					if (x.scoutid_or_applyid == this.message_payload.scoutid_or_applyid && 
						x.type == this.message_payload.type) {
							x.last_chat_time = message.created_at_utc;
					}
				})
				this.reorderUserList();

				// --Send offline users notification email
				let offlines = [];
				if (this.message_payload.speaker_role_id == 1) {
					if (!this.online.includes(this.meta.jobseeker_user_id)) {
						offlines.push({
							message_id: message.id,
							from: '運営管理者',
							to: this.meta.jobseeker_email,
							to_name: this.meta.jobseeker_name,
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 3,
						})
					}
					if (!this.online.includes(this.meta.recruiter_user_id)) {
						offlines.push({
							message_id: message.id,
							from: '運営管理者',
							to: this.meta.recruiter_email,
							to_name: this.meta.recruiter_name,
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 2,
						})
					}
				}
				else if (this.message_payload.speaker_role_id == 2) {
					if (!this.online.includes(this.meta.jobseeker_user_id)) {
						offlines.push({
							message_id: message.id,
							from: this.meta.recruiter_name,
							to: this.meta.jobseeker_email,
							to_name: this.meta.jobseeker_name,
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 3,
						})
					}
					if (!this.online.includes(this.meta.admin_user_id)) {
						offlines.push({
							message_id: message.id,
							from: this.meta.recruiter_name,
							to: this.meta.admin_email,
							to_name: '運営管理者',
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 1,
						})
					}
				}
				else if (this.message_payload.speaker_role_id == 3) {
					if (!this.online.includes(this.meta.recruiter_user_id)) {
						offlines.push({
							message_id: message.id,
							from: this.meta.jobseeker_name,
							to: this.meta.recruiter_email,
							to_name: this.meta.recruiter_name,
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 2,
						})
					}
					if (!this.online.includes(this.meta.admin_user_id)) {
						offlines.push({
							message_id: message.id,
							from: this.meta.jobseeker_name,
							to: this.meta.admin_email,
							to_name: '運営管理者',
							date: Date.now(),
							number: this.number,
							title: this.title,
							jobseeker_email: this.meta.jobseeker_email,
							recruiter_email: this.meta.recruiter_email,
							receiver_role_id: 1,
						})
					}
				}
				offlines.forEach(x => {
					this.sendMail(x);
				})
				this.resetLine();
			})
			.catch((error) => {
				console.log(error);
			})
        },
        scrollTop(channel,page) {
			if (this.current_page > this.last_page) { return }
			
            this.$api.get(`/messages/${this.message_payload.type}/${this.message_payload.scoutid_or_applyid}?page=${page+1}`)
            .then(res => {
                // console.log("scroll top msg",res)
				const r = res.data;
                this.messages = r.data.reverse().concat(this.messages);
                this.current_page = r.current_page;
				this.last_page = r.last_page;
				// --leave some space for move scroll
				this.$nextTick(() => {
					let is_mobile = window.innerWidth < 767;
					let id = is_mobile ? 'chat-scroll-container-mobile' : 'chat-scroll-container';
					if (this.current_page <= this.last_page){
						document.getElementById(id).scrollTop = document.getElementById(id).offsetTop + 300;
					}
				});
            })
            .catch(err => {
                console.log(err);
			});
            // this.$refs.scrollChat.scrollTop = 50 ; //--No longer works
		},
		// --This method is called by parent component
        resetChatMessage(payload){
			if (payload.scoutid_or_applyid == this.message_payload.scoutid_or_applyid && 
				payload.type == this.message_payload.type) {
				this.message_payload.scoutid_or_applyid = 0;
				this.meta = {
					'jobseeker_name': '',
					'recruiter_name': '',
					'jobseeker_nickname': '',
					'recruiter_nickname': '',
					'jobseeker_user_id': 0,
					'recruiter_user_id': 0,
					'recruiter_email': '',
					'jobseeker_email': '',
				};
                this.title = '';
                this.number = '';
                this.showName = '';
				this.messages = [];
			}
		},
		async onFileChange(e) {
			const files =  e.target.files || e.dataTransfer.files;
			const file = files[0];
			const ext = file.name.split(".").pop().toLowerCase();
			// --Check for extension
			const allowed_ext = [
				'html','htm','css','js',
				'jpeg','jpg','png','gif','tiff','bmp',
				'avi','wmv','mpg','mov','swf','mp4','mp3',
				'pdf','txt','docx','xlsx','pptx','zip','csv','doc','xls','ppt','txt',
				'xml','rar','gz','flv','pps','xlr','odt','mkv','tar',
				'log','dat',
			];
			if (!allowed_ext.includes(ext)) {
				await Vue.swal({
					allowOutsideClick: false,
					icon: "warning",
					width: 350,
					title: null,
					html: 'ファイル形式を確認してください。<br> ※アップロードできるファイル形式：<br> html、htm、css、js、jpeg、jpg、png、gif、tiff、bmp、avi、wmv、mpg、mov、swf、mp4、mp3、pdf、txt、docx、xlsx、pptx、zip、csv、doc、xls、ppt、txt、xml、rar、gz、flv、pps、xlr、odt、mkv、tar、log、dat',
					confirmButtonText: '閉じる',
					confirmButtonColor: "#ff5733",
					customClass: {
						confirmButton: 'border-style',
					},
				});
				// await this.$alertService.showWarningDialog(null,'ファイル形式を確認してください。\n ※アップロードできるファイル形式：\n html、htm、css、js、jpeg、jpg、png、gif、tiff、bmp、avi、wmv、mpg、mov、swf、mp4、mp3、pdf、txt、docx、xlsx、pptx、zip、csv、doc、xls、ppt、txt、xml、rar、gz、flv、pps、xlr、odt、mkv、tar、log、dat',this.$t('common.close'));
				e.target.value = '';
				return;
			}
			// --Check for filesize
			let size = file.size/1024/1024; // --size in MB
			if (size > 51) {
				await this.$alertService.showWarningDialog(null,'添付できるファイルは50MBまでです。',this.$t('common.close'));
				e.target.value = '';
				return;
			}
			
			this.message_payload.file = file;
			this.message_payload.message = file.name;
			this.message_payload.message_type = 'file';
			e.target.value = '';
		},
		deletePreviewFile() {
			this.message_payload.file = null;
			this.message_payload.message_type = 'text';
			this.message_payload.message = '';
		},
		downloadFile(message) {
			if (message.id && message.message_type == 'file' && !message.expired) {
				this.$api.post(`/messages/file/${message.id}`, {}, { responseType: "arraybuffer" })
				.then((r) => {
					const type = r.headers["content-type"];
					const blob = new Blob([r.data], { type: type, encoding: "UTF-8" });
					const filename = this.$options.filters.trim_file_message(message.message);
					const objectUrl = window.URL.createObjectURL(blob);
					// window.open(objectUrl);
					const link = document.createElement("a");
					link.href = objectUrl;
					link.download = filename;
					link.click();
					setTimeout(function () {
						// For Firefox it is necessary to delay revoking the ObjectURL
						window.URL.revokeObjectURL(objectUrl);
					}, 100);
				})
				.catch(err => {
					this.$alertService.showErrorDialog(null,'ファイルが存在しません。');
				});
			}
		},
		async deleteMessage(message) {
			if (!message.id) return;
			const confirm = await this.$alertService.showConfirmDialog(null,this.$t('このメッセージを本当に削除しますか？削除後は元に戻すことはできません。'),this.$t('alertMessage.yes'),this.$t('alertMessage.no'));
			if (confirm.value) {
				// --Delete message API
				this.$api.delete(`/messages/${message.id}`)
				.then(r => {
					const removed_message = r.data.data;
					// --Remove from messages array
					let i = this.messages.findIndex(x => x.id == message.id);
					if (i > -1) {
						this.messages.splice(i,1);
					}
					// --Broadcast deleted message event
					socket.emit("remove-message",removed_message);

					// --Reorder user list to show latest chat on top
					let requireReorder = false;
					this.jobs.forEach(job => {
						if (job.scoutid_or_applyid == removed_message.scoutid_or_applyid && job.type == removed_message.type) {
							// --Update last chat time
							if (removed_message.last_chat_time == job.last_chat_time) {
								job.last_chat_time = removed_message.second_last_chat_time;
								requireReorder = true;
							}
						}
					});
					if (requireReorder) this.reorderUserList();
				})
				.catch(e => {
					console.log(e.response);
				});
			}
		},
		scrollToBottomMessage: function() {
			// --scroll to bottom message
			this.$nextTick(() => {
				let isMobile = window.innerWidth < 767;
				let id = isMobile ? 'chat-scroll-container-mobile' : 'chat-scroll-container';
				document.getElementById(id).scrollTop = document.getElementById(id).scrollHeight;
			});
		},
        dragMouseDown: function (event) {
            this.positions.clientX = event.clientX
            this.positions.clientY = event.clientY
            document.onmousemove = this.elementDrag
            document.onmouseup = this.closeDragElement
        },
        elementDrag: function (event) {
            event.preventDefault()
            this.positions.movementX = this.positions.clientX - event.clientX
            this.positions.movementY = this.positions.clientY - event.clientY
            this.positions.clientX = event.clientX
            this.positions.clientY = event.clientY
            this.$refs.draggableContainer.style.top = (this.$refs.draggableContainer.offsetTop - this.positions.movementY) + 'px'
            this.$refs.draggableContainer.style.left = (this.$refs.draggableContainer.offsetLeft - this.positions.movementX) + 'px'
        },
        closeDragElement () {
            document.onmouseup = null
            document.onmousemove = null
		},
		openChatBox(e){			
			this.isToggled = !this.isToggled;
			$("body","html").css("overflow", "hidden");
			$("body").css("position", "fixed");
			console.log('openchat')
			
		},
		closeChatBox() {
			this.messages = [];
			this.title = '';
			this.number = '';
			this.showName = '';
			this.isToggled = !this.isToggled;
			this.message_payload.recruiter_id = 0;
			this.message_payload.jobseeker_id = 0;
            this.message_payload.scoutid_or_applyid = 0;
            this.hideChattingBox();
			$("body","html").css("overflow", "unset");
			$("body").css("position", "unset");
		},
		sendMail(receiver){
			this.$api.post('/messages/send-mail', receiver)
			.then(() => {})
			.catch((e) => {
				console.log(e);
			})
		},
		hideChattingBox(){
			this.messages = [];
			this.resetLine();
			this.showChattingBox = false;
		},		
		addLine() {
			this.messageLines++;
			this.message_payload.message = this.message_payload.message + "\r\n";
		},
		resetLine() {
			this.messageLines = 1;
			this.message_payload.message = '';
		},
		calcTextareaHeight() {
			let lineBreaks = (this.message_payload.message.match(/\n/g)||[]).length;
			if (lineBreaks > 0) { 
                this.messageLines = lineBreaks + 1;
				if(lineBreaks >3 ){
					$("#chrom-txtarea textarea").animate({ scrollTop: $(document).height()-$(window).height() });
					console.log('aa')
				}
            } else if (lineBreaks == 0) {
                this.messageLines = 1;
            }
		}
	},
	computed: {
		...mapGetters(["currentUser"]),
		timezone() {
			return this.loggedInUser.extra.timezone ? this.loggedInUser.extra.timezone : 'Asia/Tokyo';
		}
	},
	watch: {
		filter_text() {
			this.loadAllUsers();
		}
	}
}
</script>
<style lang="scss" scoped>
@import './resources/sass/variables.scss';
.sp{
	display:none;
}
.sp-dname {
	display: none!important;
}
.pc{
	display:flex;
	flex-direction: column;
}
.pc-dname{
	display:inline-block!important;
	flex-direction: column;
}
.jobseeker-primary-footer-chat {
	input {
		border: 1px solid color(primary-jobseeker) !important;
	}
	textarea {
        border: 1px solid color(primary-jobseeker) !important;
    }
	i {
		color: color(primary-jobseeker) !important;
		&:hover {
			color: #FFF !important;
			background-color: color(primary-jobseeker) !important;
		}
	}
	#chrom-txtarea{
		border: 1px solid color(primary-jobseeker) !important;
	}
}

.recruiter-primary-footer-chat {
	input {
		border: 1px solid color(primary-recruiter) !important;
	}
	textarea {
        border: 1px solid color(primary-recruiter) !important;
    }
	i {
		color: color(primary-recruiter) !important;
		&:hover {
			color: #FFF !important;
			background-color: color(primary-recruiter) !important;
		}
	}
	#chrom-txtarea{
		border: 1px solid color(primary-recruiter) !important;
	}
}
/*fixed for textarea browser support*/
#chrom-txtarea {
    width: 100%;
    margin: 0 auto;
	padding: 1px;
    position: relative;
    clear: both;
    box-sizing: border-box;
    // border: 1px solid #6085a3;
    border-radius: 5px;
}
#chrom-txtarea textarea {
    width: 100%;
    font-size: 12px;
    line-height: 18px;
	background:transparent;
    border: none !important;
    overflow: auto;
    resize: none;
    display: block;
}
#chrom-txtarea:before, #chrom-txtarea:after {
    display: block;
    height: 5px;
    background-color: #FFF;
    position: absolute;
    left: 1px;
    right: 17px;
    content:'';
}
#chrom-txtarea:before {
    top: 1px;
}
#chrom-txtarea:after {
    bottom: 1px;
}
/*end fixed for textarea browser support*/
.txt-ellipsis-1 {
	display: -webkit-box;
	-webkit-line-clamp: 1;
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
	word-break: break-word;
}
input:focus{
    outline: none;
}
.chat_show{
    display: block;
    animation: bounce-in .1s;
}
.chat_hide{
    display: none;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
#boxchat {
    z-index: 1;
}
.box-chat{
    background: white;
    box-shadow: 2px 2px 5px 0px #7b7b7b;
    position: fixed;
    right: 90px;
    bottom: 20px;
    width: 600px;
    height: 460px;
    // z-index: 10001;
	z-index: 1050;
}
.list-user-move {
	transition: transform 1s;
}
.attach-name {
	width: auto; 
	position: relative;
	margin: 10px 5px !important;
}
    .main-chat{
        right: 90px;
        bottom: 20px;
        .tab-right{
            padding: 0;
            border-left: 1px solid #e2e2e2;
			max-height: 460px;
        }
        .tab-left{
            padding: 0;
            margin-top: 5px;
			.list-user-search {
				height: 45px;
			}
            .list-user{
                height: 385px;
                overflow-y: scroll;
				padding-top: 5px;
            }
        }
        .header-chat{
            width: 100%;
			// height: 70px;
            // background: #27a09e;
            padding: 5px;
			display: flex;
			min-height: 70px;
            max-height: 80px;
            h3{
                margin-top: 15px;
                font-size: 16px;
                color: #fff;
                text-align: center;
            }
            .name{
                margin-left: 15px;
                color: #fff;
				cursor: grabbing;
				display: flex;
				flex-direction: column;
				align-items: flex-start;
                p{
                    margin: 0;
                    font-size: 14px;                    
                    font-weight: bold;
                }
                span{
                    font-size: 12px;
					cursor: text;
                }
            }
            .close{
				align-self: flex-start;
                text-shadow:unset;
				cursor: pointer;
                &:hover{
                    opacity: 1;
                }
            }
			.draggable-filler {
				flex: 1;
				cursor: grabbing;
			}
        }
        .list-job-user{
            li{
               &.active{
                    background: #6085a3;
                    color: #fff;
                    
                    &:hover{
                        background: #6085a3;
                        cursor: context-menu;
                    }
                }
                &:hover{
                    cursor: pointer;
                    background: #d4cdcd;
                } 
            }
        }
        .list-rec-user{
            li{
               &.active{
                    background: #27a09e;
                    color: #fff;
                    
                    &:hover{
                        background: #27a09e;
                        cursor: context-menu;
                    }
                }
                &:hover{
                    cursor: pointer;
                    background: #d4cdcd;
                } 
            }
        }
		.list-user-search {
			.filter-input {
				width: 90%;
				border: 0;
				line-height: 60px; 
				// 
				&::placeholder {
					color: color(placeholder);
				}
			}
			li {
                display: flex;
                padding: 7px 10px;
                margin: 0 5px;
                position: relative;
                list-style-type: none;
                border-bottom: 1px solid #e2e2e2;
                transition: 0.3s;
                &:hover{
                    cursor: pointer;
                    background: #d4cdcd;
                }
				&.has-input {
					padding: 5px 3px;
					border: 0;
					height: 70px;
					position: sticky;
					top: 0px;
					z-index: 99;
					background: transparent;
					.search-container {
						display: flex;
						align-items: flex-end;
						width: 100%;
						height: 95%;
						border-bottom: 1px solid #e2e2e2;
						span {
							color: #a1a1a1;
							margin: 0px 5px;
							padding-bottom: 23px; 
						}
						input {
							flex: 1 1 auto;
						}
					}
				}
				&.user-list-searching {
					padding: 3px 0px;
					justify-content: center;
					border: 0;
				}
            }
		}
        .list-user{
			.filter-input {
				width: 100%;
				border: 0;
				// border-bottom: 1px solid#e2e2e2;
				&::placeholder {
					color: color(placeholder);
				}
			}
            li{
                display: flex;
                padding: 7px 10px;
                margin: 0 5px;
                position: relative;
                list-style-type: none;
                border-bottom: 1px solid #e2e2e2;
                transition: 0.3s;
				min-height: 43px;
                // &.active{
                //     // background: #27a09e;
                //     color: #fff;
                    
                //     &:hover{
                //         // background: #27a09e;
                //         cursor: context-menu;
                //     }
                // }
                // &:hover{
                //     cursor: pointer;
                //     background: #d4cdcd;
                // }
                .unread{
                    position: absolute;
                    top: 30px;
                    right: 0;
                    height: 15px;
                    width: 15px;
                    background: #c51223;
                    border-radius: 50%;
                    span{
                        position: absolute;
                        color: #fff;
                        font-size: 7px;
                        left: 34%;
                        top: 2px;
                    }
                    .plus{
                        left: 29%;
                    }
                }
				&.has-input {
                    // padding: 7px 3px;
                    padding: 7px 20px 7px 10px;	
					// border: 0;
					position: sticky;
					top: 0px;
					z-index: 99;
					background: #fff;
					height: 65px;
					&:hover {
						background: transparent;
					}
					.search-container {
						display: flex;
						align-items: center;
						width: 100%;
						height: 100%;
						span {
							color: #a1a1a1;
							margin: 0px 5px;
                            position: relative;
                            padding-top: 3px;
						}
						input {
							flex: 1 1 auto;
						}
					}
				}
				&.is-title {
					padding: 7px 3px;
					&:hover {
						cursor: default;
						background: transparent;
					}
				}
				&.user-list-searching {
					padding: 3px 0px;
					justify-content: center;
					border: 0;
				}
            }
            .name{
                margin-left: 5px;
				flex: 1;
                p{
                    margin: 0;
                    font-size: 12px;
					.d_name {
						max-width: 95px;
						text-overflow: ellipsis;
						overflow: hidden;
						white-space: nowrap;
						display: inline-block;
					}
                }
            }
        }
        .content-chat{
            // height: 340px;
            overflow-y: scroll;
			flex: 1 1 340px;  
			&.read-only {
				flex-basis: 400px;
			}
            .chat-history{
                .loading{
                    margin-top: 40%;
                }
                padding: 10px;
                ul{
                    li{
                        list-style-type: none;
                    }
                }
                .message{
                    clear: both;
                    padding: 5px;
                    max-width: 230px;
                    font-size: 13px;
                    word-break: break-all; 
                    overflow-wrap: break-word;    
					position: relative;               
					pre {
						min-height: 1rem;
					}
					.message-delete {
						display: none;
						position: absolute;
						top: -5px;
						right: -5px;
						width: 20px;
						height: 20px;
						border: 1px solid #919191;
						background: #FFF;
						border-radius: 50%;
						text-align: center;
						line-height: 1.2;
						vertical-align: middle;
						cursor: pointer;
					}
					&:hover > .message-delete {
						display: block;
					}
                }                   
                .my-message{
                    background: #59d0ce;
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                    border-bottom-left-radius: 10px;
					.btn-download {
						background: #EDEDED;
						margin-top: 5px;
						margin-left: -5px;
						margin-right: -5px;
						margin-bottom: -5px;
						padding: 3px 6px;
						border-bottom-left-radius: 10px;
						cursor: pointer;
						flex: 1;
						align-self: stretch;
						text-align: center;
						color: #999;
					}
                }
                .other-message{
                    background: #d0d0d0;
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                    border-bottom-right-radius: 10px;
                    .name{
                        color: #27a09e;
                        font-size: 11px;
                    }
					.btn-download {
						background: #EDEDED;
						margin-top: 5px;
						margin-left: -5px;
						margin-right: -5px;
						margin-bottom: -5px;
						padding: 3px 6px;
						border-bottom-right-radius: 10px;
						cursor: pointer;
						align-self: stretch;
						text-align: center;
						color: #999;
					}
                }
                .time{
                    clear: both;
                    margin-bottom: 15px;
                    font-size: 10px;
                    color: #888888;
                }

            }
        }
        .footer-chat{
			// display: flex;
            align-self: flex-end;
            align-items: center;
            width: 100%;
            padding: 0px 10px 0px 10px;
            margin-bottom: 15px;
            textarea{
                padding: 10px 20px;
                width: 84%;
                border-radius: 5px;
				box-sizing: border-box;
                border: 1px solid #27a09e;
				overflow-y: auto;
				max-height: 96px;
				line-height: 18px;
				resize: vertical;
            }
            i{
                // margin-left: 10px;
                font-size: 18px;
                // color: #27a09e;
                padding: 10px;
                &:hover{
                    color: #fff;
                    // background: #27a09e;
                    border-radius: 20px;
                    cursor: pointer;
                }
            }
			i.btn-attachment {
				margin-right: 7px;
			}
			i.btn-send {
				margin-left: 10px;
			}
            .typing{
                position: absolute;	
				bottom: -16px;
    			left: 50px;			          
                // bottom: 35px;
            }
			.file-upload-preview {
				margin-top: 10px;
				margin-bottom: 5px;
				margin-left: 5px;
				max-width: 250px;
				p.file-name {
					max-width: 230px;
					overflow: hidden;
					text-overflow: ellipsis;
					margin-bottom: 0px;
					line-height: 1.2;
				}
				.file-delete {
					position: absolute;
					top: -8px;
					right: -10px;
					width: 20px;
					height: 20px;
					// border: 1px solid #919191;
					background: #fff;
					border-radius: 50%;
					text-align: center;
					line-height: 1;
					font-size: 15px;
					color:#333;
					span {
						position: relative;
						cursor: pointer;
						font-size: 20px;
						line-height: 1.1;
						color:#333;
					}
				}
			}
        }
    }

.btn-chat{
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 20px;
    right: 20px;
    border-radius: 50%;
    border: 1px solid #eaeaea;
    // background: #27a09e;
    // z-index: 9998;
    i{
        font-size: 40px;
        padding: 10px;
        color:#fff;
    }
    .count-all{
        position: absolute;
        background: #c51223;
        right: 0px;
        top: -5px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        span{
            font-size: 11px;
            color: #fff;
            position: absolute;
            left: 34%;
            top: 2px;
        }
        .plus{
            left: 20%;
        }
    }
}
.active .unread{
    display: none;
}
.recruiter-header-border{
	border: 1px solid #098e84;
}
.job-header-border{
	border: 1px solid #507594;
}
/* transistion */
.slideRight-enter-active,
.slideRight-leave-active
{
	transform: translateX(0);
	transition: transform 0.2s ease;
}

.slideRight-enter,
.slideRight-leave-to {
	transform: translateX(100%);
	transition: all 150ms ease-in 0s;
}
/* transistion */

@media screen and (max-width:767px){
	.d_name {
		max-width: 485px!important;
	}
	.pc{
		display:none;
	}
	.pc-dname{
		display:none!important;
	}
	.sp{
		display:block;
	}
	.sp-dname{
		display: inline-block!important;		
	}
	.w-90{
		width: 90%;
	}
	.w-6{
		width: 6%;
	}
	.w-4{
		width: 4%;
	}
	.chat-head{
		margin:5px 0px 10px 0px;
	}
	.chat-tit{
		margin-left: 15px;		
		p{
			margin: 0;
			font-size: 16px;                    
			color: #333;
		}
	}
	.box-chat{
		width: 100%;
		height: 100%;
		bottom: 0;
		left: 0;
		right: 0;
    	border-radius: 0;
		box-shadow: none;
	}
	.main-chat{		
		display: block;
		position: fixed;		
		width: 100%;
		height: 100%;		
		// overflow-y: auto;
		// overflow-x: hidden;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		// .footer-chat{
		// 	height: unset;
		// 	margin-bottom: 0px;
		// }
		.mobile-chat-right-pane {
			display: flex;
			flex-direction: column;
			max-height: calc(100% - 70px);
		}
		.content-chat{
			// height: auto;
			// height: calc(100% - 140px); // --header + footer height ~= 140px
			// overflow-y:auto;
			flex: 1;
			.chat-history {
				// height: 70vh;
				overflow-x: hidden;
				overflow-y: auto;
				transform: rotate(180deg);
				// direction:rtl;
				text-align:left;
			}
			.chat-msg {
				overflow: hidden;
				transform: rotate(180deg);
			}
		}	
	}

	.main-chat .footer-chat input{
		width: 87%;
	}
	.main-chat .header-chat{
		height: 70px;
		margin-top: 0px;
		padding: 7px 10px;
		// border-top-left-radius: 0px;
		// border-top-right-radius: 0px;
	}
	.main-chat .tab-right{
		border-left:none;
		height: 100%;
	}
	// .main-chat .footer-chat i{
	// 	margin-left: 0px;
	// }
	.list-user{
		height: 88vh !important;
		overflow: unset !important;
	}
	.main-chat .list-user li.has-input{
		position: relative;
		padding: 7px 5px;
		height: 50px;
	}
	.chat-panel {
		position:fixed;
		width: 100%;
		height: 100%;
		bottom: 0;
		left: 0px;
		right: 0px;   
		background: white;
		z-index: 9999;   
		border-radius: 0px;  
    }
	.main-chat .tab-left{
		height:90vh;
		margin: 0px;
		overflow-y: scroll;
	}
	.main-chat .footer-chat{
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
        padding: 0px 10px;
        // border-top: 1px solid #ddd;
		align-items: center;
        textarea{
			margin-right: 10px;
            border-radius: 5px;
            width: 90%;
        }
		.typing{
			// top: 0;
			// margin-top: 0;  
			bottom: -32px;
		}
	}
}
@media screen and (max-width:480px){
	.d_name {
		max-width: 235px!important;
	}
.btn-chat i {
	font-size: 20px;
}
.btn-chat{  
	width: 40px;
	height: 40px;
	bottom: 55px;	
}
}
@media screen and (max-width:320px){
	.d_name {
		max-width: 200px!important;
	}
	.main-chat .footer-chat input{
		width: 84%;
	}
}

</style>
