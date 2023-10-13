import { Controller } from 'stimulus';
import $ from 'jquery';

export default class extends Controller { 
    static values = {
        errorEmailMessage: String,
        errorPassMessage: String,
    }
    connect() { 
        const errorEmailMessage = this.errorEmailMessageValue;
        const errorPassMessage = this.errorPassMessageValue;
        const passSaved = localStorage.getItem("pass_remember");
        const userSaved = localStorage.getItem("user_remember");
        $("#password").val(passSaved);
        $("#username").val(userSaved);
        
        $('form').on('submit', function(e){
            const pass = $("#password").val();
            const passError = $("#password").parent().children('.invalid-feedback');
            const user = $("#username").val();
            const userError = $("#username").parent().children('.invalid-feedback');
            const remember = $("#remember_me").is(':checked');
            if (!user) {
                $("#username").css('border-color', '#ed2000');
                userError.html(errorEmailMessage);
                userError.fadeIn();
                e.preventDefault();
            } else {
                $("#username").css('border-color', '#cbd0dd');
            }
            if (!pass) {
                $("#password").css('border-color', '#ed2000');
                passError.html(errorPassMessage);
                passError.fadeIn();
                e.preventDefault();
            } else {
                $("#password").css('border-color', '#cbd0dd');
            }

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