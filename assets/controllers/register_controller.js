import { Controller } from 'stimulus';

export default class extends Controller { 
    connect() { 
        $('form').on('submit', function (e) { 
            let valid = true;
            const email = $("#registration_form_email");
            const passFirst = $("#registration_form_plainPassword_first");
            const passSecond = $("#registration_form_plainPassword_second");

            if (!validate(email, "El correo es obligatorio")) valid = false;
            if (!validate(passFirst, "La clave es requerida")) valid = false;
            if (!validate(passSecond, "Debe repetir la clave")) valid = false;

            if (!valid) {
                e.preventDefault();
            }
        })

        function validate(element, text) {
            let msjError = element.parent().children('.invalid-feedback');
            if (!element.val()) {
                element.css('border-color', '#ed2000');
                msjError.html(text);
                msjError.fadeIn();
                return false;
            } else {
                element.css('border-color', '#cbd0dd');
                msjError.fadeOut();
                return true;
            }
        }
    }
}