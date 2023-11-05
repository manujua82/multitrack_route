import { Controller } from 'stimulus';
import { useDebounce } from "stimulus-use";


export default class extends Controller {

    selectedDates = null;

    static values = {
        refreshUrl:  String,
    }

    static targets = [
        'search',
        'dateRange',
        'orderList'
    ]

    static debounces = ['onSearchOrder'];
    
    connect() {
        useDebounce(this);
    }

    async fetchOrdersByFilters(){
        const params = new URLSearchParams({
            query: this.searchTarget.value,
            selectedDates: this.selectedDates,
            preview: 1

        });
        const response = await fetch(`${this.refreshUrlValue}?${params.toString()}`);
        return await response.text()
    }

    async onSearchOrder(event) {
        event.preventDefault();
        console.log("onSearchOrder");
        this.orderListTarget.innerHTML = await this.fetchOrdersByFilters();
    }

    onDateRange(event) {
        event.preventDefault();
        const _flatpickr = event.srcElement._flatpickr;
        if (!_flatpickr.isOpen) {
            this.selectedDates = [
                _flatpickr.selectedDates[0]?.toISOString(),
                _flatpickr.selectedDates[1]?.toISOString(),
            ]
        }
    }
}