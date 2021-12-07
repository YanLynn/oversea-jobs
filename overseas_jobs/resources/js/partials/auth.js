import  api   from '../api/apiBasePath';

export function registerUser(credentials){
    return new Promise((res,rej)=>{
        api.post('/auth/register', credentials)
        .then(response => {
            res(response.data);
        })
        .catch(err => {
            rej('An error occured.. try again later.')
        })
    })
}

export function registerRecruiter(credentials){
    return new Promise((res,rej)=>{
        api.post('/auth/registerRecruiter', credentials)
        .then(response => {
            res(response.data);
        })
        .catch(err => {
            rej('An error occured.. try again later.')
        })
    })
}

export function jobseeker_register(credentials){
    return new Promise((res,rej)=>{
        api.post('/auth/jobseeker_register', credentials)
        .then(response => {
            res(response.data);
        })
        .catch(err => {
            rej('An error occured.. try again later.')
        })
    })

}

export function login(credentials){
    return new Promise((res,rej)=>{
        return api.post('/auth/login', credentials)
        .then(response => {            
            res(response.data);
        })
        .catch(err => {
        
            rej('メールアドレスまたはパスワードが正しくありません')
        })
    })
}

export function recruiterLogin(credentials){
    return new Promise((res,rej)=>{
        return api.post('/auth/recruiter/login', credentials)
        .then(response => {            
            res(response.data);
        })
        .catch(err => {
            rej(err)
          
        })
    })
}


export function jobseekerLogin(credentials){
    return new Promise((res,rej)=>{
        return api.post('/auth/jobseeker/login', credentials)
        .then(response => {            
            res(response.data);
        })
        .catch(err => {
            rej(err)
            // rej('You have no authorized.')
        })
    })
}



 
export function getLoggedinUser(){
    const userStr = localStorage.getItem('user');

    if(!userStr){
        return null
    }

    return JSON.parse(userStr);
}