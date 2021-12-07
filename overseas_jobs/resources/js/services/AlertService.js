import Vue from 'vue';

const successDialog = (title, message, ok = "閉じる") => {
	return Vue.swal({
		allowOutsideClick: false,		
		icon: "success",
		width: 350,
		title: title,
		text: message,
		confirmButtonText: ok,
		confirmButtonColor: "#1eb1ff",
		customClass: {
			confirmButton: 'border-style',
			cancelButton: 'cancelbtn border-style'
		},
	});
}
const infoDialog = (title, message, ok = "閉じる") => {
	return Vue.swal({
		allowOutsideClick: false,		
		icon: "info",
		width: 350,
		title: title,
		text: message,
		confirmButtonText: ok,
		confirmButtonColor: "#1eb1ff",
		customClass: {
			confirmButton: 'border-style',
			cancelButton: 'cancelbtn border-style'
		},
	});
}

const errorDialog = (title, message, ok = "閉じる") => {
	return Vue.swal({
		allowOutsideClick: false,
		icon: "error",
		width: 350,
		title: title,
		text: message,
		confirmButtonText: ok,
		confirmButtonColor: "#d62b2b",
		customClass: {
			confirmButton: 'border-style',
		},
	});
}

const confirmDialog = (title, message, ok = "Yes", cancel = "No") => {
	return Vue.swal({
		allowOutsideClick: false, 
		title: title,
		text: message,  
		icon: "question",
		width: 350,
		confirmButtonColor: "#ffb700",                       
		confirmButtonText: ok,
		cancelButtonColor: "#0062ff",                       
		cancelButtonText: cancel,
		customClass: {
			confirmButton: 'border-style',
			cancelButton: 'cancelbtn border-style'
		},
		showCloseButton: true,
		showCancelButton: true
	});
}


const warningDialog = (title, message, ok = "閉じる") => {
	return Vue.swal({
		allowOutsideClick: false,
		icon: "warning",
		width: 350,
		title: title,
		text: message,
		confirmButtonText: ok,
		confirmButtonColor: "#ff5733",
		customClass: {
			confirmButton: 'border-style',
		},
	});
}

const inputDialog = (title, ok = "OK", cancel = "Cancel") => {
	return Vue.swal({
		allowOutsideClick: false, 
		title: title,
		width: 400,
		input: 'text',		
		confirmButtonColor: "#ffb700",                       
		confirmButtonText: ok,		
		cancelButtonColor: "#0062ff",                       
		cancelButtonText: cancel,		
		customClass: {
			confirmButton: 'border-style',
			cancelButton: 'cancelbtn border-style'
		},
		showCloseButton: true,
		showCancelButton: true,
		inputValidator: (value) => {
			if (!value) {
				return 'You need to write something!'
			}
		}
	});
}

const toast = (title, message) => {
	return Vue.swal({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3000,
		icon: 'info',
		title: title,
		text: message,
	});
}

export default {
	showSuccessDialog: successDialog,
	showErrorDialog: errorDialog,
	showConfirmDialog: confirmDialog,
	showWarningDialog: warningDialog,
	showInputDialog: inputDialog,
	showToast: toast,
	showInfoDialog:infoDialog,
	
}