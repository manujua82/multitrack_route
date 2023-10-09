import { Controller } from 'stimulus';

export default class extends Controller { 
    connect() { 
        const passSaved = localStorage.getItem("pass_remember");
        const userSaved = localStorage.getItem("user_remember");
        $("#password").val(passSaved);
        $("#username").val(userSaved);
        
        $('form').on('submit', function(e){
            const pass = $("#password").val();
            const user = $("#username").val();
            const remember = $("#remember_me").is(':checked');
            console.log('remember', remember)
            if(remember){
                localStorage.setItem("pass_remember",pass);
                localStorage.setItem("user_remember",user);
            }else{
                localStorage.setItem("pass_remember", "");
                localStorage.setItem("user_remember", "");
            }
        });
    }
}