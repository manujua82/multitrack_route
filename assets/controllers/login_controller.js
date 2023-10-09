import { Controller } from 'stimulus';
import $ from 'jquery';

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
            if (!user) {
                $("#username").css('border-color', '#ed2000')
                e.preventDefault();
            } else {
                $("#username").css('border-color', '#cbd0dd')
            }
            if (!pass) {
                $("#password").css('border-color', '#ed2000')
                e.preventDefault();
            } else {
                $("#password").css('border-color', '#cbd0dd')
            }

            if (!user || !pass) {
                $('#alert-warning').html('Los campos marcados en rojo son requeridos').fadeIn()
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