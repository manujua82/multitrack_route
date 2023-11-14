import { Controller } from "stimulus";
import { Modal } from "bootstrap";
import $ from "jquery";

export default class extends Controller {
    static targets = ["modal", "modalBody"];

    static values = {
            formUrl: String,
            loadingText: String,
    };

    modal = null;

    async populateFormModal(method = "GET") {

        console.log(`populateFormModal`);
        this.modalBodyTarget.innerHTML = this.loadingTextValue;
        this.modal = new Modal(this.modalTarget);
        this.modal.show();
        const response = await $.ajax({
            url: this.formUrlValue,
            method: method,
        });
        console.log(response);
        this.modalBodyTarget.innerHTML = response;
    }

    async openModal(event) {
        this.populateFormModal();
    }

    async editEntity(event) {
        this.formUrlValue = event.target.dataset.value;
        this.populateFormModal("POST");
    }

    async submitForm(event) {
        event.preventDefault()
        console.log("------  HELLO  -----------");
        console.log(this.modalBodyTarget);
        const $form = $(this.modalBodyTarget).find("form");
        console.log($form);
        console.log(this.formUrlValue);
        console.log($form.prop("method"));
        try {
            await $.ajax({
                url: this.formUrlValue,
                method: $form.prop("method"),
                data: $form.serialize(),
            });

            console.log("------  HELLO 4 -----------");
            this.modal.hide();
            this.dispatch("success");
        } catch (e) {
            console.log(e.responseText);
            this.modalBodyTarget.innerHTML = e.responseText;
        }
    }

    modalHidden() {
        console.log("it was hidden!");
    }
}
