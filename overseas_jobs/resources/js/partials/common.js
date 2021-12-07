import { helpers } from 'vuelidate/lib/validators';

export const isFurigana = (value) => {
    let allow = true;
    let charArray = value.split('');
    for (let i=0; i<charArray.length; i++){
        let code = charArray[i].charCodeAt();        
        if (!(code > 12448 && code < 12543)) {            
            allow = false;
            break;           
        }
    }
    return !helpers.req(value) || allow;
}

export const matchYoutubeUrl = (value) => {
	if (!value) {
    	return true;
	}
	let allow;
    let p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    if (value.match(p)) {
        allow = value.match(p)[1];
    } else {
		allow = false;
	}	
	return !helpers.req(value) || allow;
}

