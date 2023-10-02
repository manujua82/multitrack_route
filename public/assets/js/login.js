$(document).ready(function() {
    const passSaved = localStorage.getItem("pass_remember");
    $("#password").val(passSaved);
    
    $('form').on('submit', function(e){
        const pass = $("#password").val();
        const remember = $("#remember_me").is(':checked');
        console.log('remember', remember)
        if(remember){
            localStorage.setItem("pass_remember",pass);
        }else{
            localStorage.setItem("pass_remember", "");
        }
    });
});