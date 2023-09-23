import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['routeTable'];

    connect() {
        console.log(`connected`);
    }
}
