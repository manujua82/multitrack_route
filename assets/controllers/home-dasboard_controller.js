import { Controller } from 'stimulus';


export default class extends Controller {

    // static targets = ['routeOrders', 'unscheduleOrders'];

    connect() {
        console.log('dashboard-connect');
    }
}