import { Controller } from "stimulus";
import { useDebounce } from "stimulus-use";

export default class extends Controller {

    static values = {
        refreshUrl:  String,
    }

    static targets = [
        'search',
        'itemsList'
    ];

    static debounces = ['onSearchItems'];

    connect() {
        useDebounce(this);
    }

    async onSearchItems(event) {
        const params = new URLSearchParams({
            query: this.searchTarget.value,
            preview: 1
        });

        const response = await fetch(`${this.refreshUrlValue}?${params.toString()}`);
        this.itemsListTarget.innerHTML = await response.text();
    }
}