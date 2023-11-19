import { Controller } from "stimulus";

export default class extends Controller {
  static targets = ["content"];

  static values = {
    url: String,
  };

  async reloadContent() {
    var url = this.urlValue;
    const response = await fetch(url);
    this.contentTarget.innerHTML = await response.text();
  }
}
