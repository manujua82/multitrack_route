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
    this.modalBodyTarget.innerHTML = this.loadingTextValue;
    this.modal = new Modal(this.modalTarget);
    this.modal.show();
    const response = await $.ajax({
      url: this.formUrlValue,
      method: method,
    });
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
    event.preventDefault();
    const $form = $(this.modalBodyTarget).find("form");
    try {
      await $.ajax({
        url: this.formUrlValue,
        method: $form.prop("method"),
        data: $form.serialize(),
      });

      this.modal.hide();
      this.dispatch("success");
    } catch (e) {
      console.log(e.responseText);
      this.modalBodyTarget.innerHTML = e.responseText;
    }
  }

  modalHidden() {
    console.log("Hide");
  }
}
