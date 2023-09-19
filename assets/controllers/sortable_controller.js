import { Controller } from 'stimulus';
import Sortable from 'sortablejs';


export default class extends Controller {

    static targets = ['routeOrders', 'unscheduleOrders'];

    connect() {
        console.log(`Sortable Connected ${this.routeOrdersTarget}`);

        Sortable.create(this.routeOrdersTarget, {
            group: {
                name: 'routeOrders',
                put: function (to) {
                    console.log(`routeOrders to ${to.el}`);
                    return true
                }
            },
            animation: 100
        });

        Sortable.create(this.unscheduleOrdersTarget, {
            group: {
                name: 'unscheduleOrders',
                put: 'routeOrders',
                pull: function (to, from) {
                    console.log(`unscheduleOrders pull: ${from.el.children}`)
                    console.log(`unscheduleOrders to ${to.el.children}`)
                    return true
                }
            },
            animation: 100
        });
    }
}
