import { Controller } from "stimulus";
import { useDebounce } from "stimulus-use";

export default class extends Controller {

    static values = {
        refreshUrl:  String,
    }

    static targets = [
        'search',
        'customerList',
        'customerFilterForm'
    ];

    static debounces = ['onSearchCustomers'];

    connect() {
        useDebounce(this);
    }

   async onSearchCustomers(event) {
        console.log(`onSearchCustomers`);
        const params = new URLSearchParams({
            query: this.searchTarget.value,
            preview: 1
        });

        const response = await fetch(`${this.refreshUrlValue}?${params.toString()}`);
        this.customerListTarget.innerHTML = await response.text();
    }
}