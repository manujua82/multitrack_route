import { Controller } from 'stimulus';
import { useDebounce } from "stimulus-use";


export default class extends Controller {

    selectedDates = null;

    static targets = [
        'search',
        'dateRange',
    ]

    static debounces = ['onSearchOrder'];
    
    connect() {
        useDebounce(this);
    }

    async onSearchOrder(event) {
        // event.preventDefault();
        // document.getElementById("order-filter-form").submit();
    }

    onDateRange(event) {
        event.preventDefault();
        const _flatpickr = event.srcElement._flatpickr;
        if (!_flatpickr.isOpen) {
            document.getElementById("order-filter-form").submit();
        }
    }
}