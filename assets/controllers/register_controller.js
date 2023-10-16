import { Controller } from 'stimulus';
import $ from 'jquery';

export default class extends Controller { 
    static values = {
        errorEmailMessage: String,
        errorPassFirstMessage: String,
        errorPassSecondMessage: String,
        errorAgreeTermsMessage: String,
        errorPassEqualMessage: String
    }
    connect() { 
        const errorEmailMessage = this.errorEmailMessageValue;
        const errorPassFirstMessage = this.errorPassFirstMessageValue;
        const errorPassSecondMessage = this.errorPassSecondMessageValue;
        const errorAgreeTermsMessage = this.errorAgreeTermsMessageValue;
        const errorPassEqualMessage = this.errorPassEqualMessageValue;

        $('form').on('submit', function (e) {
            let valid = true;
            const email = $("#registration_form_email");
            const passFirst = $("#registration_form_plainPassword_first");
            const passSecond = $("#registration_form_plainPassword_second");
            const termCond = $("#registration_form_agreeTerms");

            if (!validate(email, errorEmailMessage)) valid = false;
            if (!validate(passFirst, errorPassFirstMessage)) valid = false;
            if (!validate(passSecond, errorPassSecondMessage)) valid = false;
            if (!validate(termCond, errorAgreeTermsMessage, 'checkbox')) valid = false;

            if (passFirst.val() !== passSecond.val()) {
                let msjError = passFirst.parent().children('.invalid-feedback');
                passFirst.css('border-color', '#ed2000');
                msjError.html(errorPassEqualMessage);
                msjError.fadeIn();
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        })

        function validate(element, text, type='text') {
            let msjError = element.parent().children('.invalid-feedback');
            let valid = true;
            if (type === 'text') {
                if (!element.val()) valid = false
            }else if (type === 'checkbox') {
                if (!element.is(':checked')) valid = false
            }

            if (!valid) {
                element.css('border-color', '#ed2000');
                msjError.html(text);
                msjError.fadeIn();
            } else {
                element.css('border-color', '#cbd0dd');
                msjError.fadeOut();
            }
            return valid;
        }
    }
}