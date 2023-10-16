import { Controller } from 'stimulus';

export default class extends Controller { 

    toggle_password(item) {
        const element = item.target;
        const fields = element.parentElement.getElementsByTagName('input');
        for (let i = 0; i < fields.length; i++) {
            let x = fields[i];
            if (x.type === "password") {
                element.classList.remove("uil-eye")
                element.classList.add("uil-eye-slash")
                x.type = "text";
            } else if (x.type === "text") {
                element.classList.remove("uil-eye-slash")
                element.classList.add("uil-eye")
                x.type = "password";
            }
        }
    }
}