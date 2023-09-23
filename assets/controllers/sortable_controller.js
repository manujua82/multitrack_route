import { Controller } from 'stimulus';
import {Sortable, MultiDrag} from 'sortablejs';

export default class extends Controller {

    static targets = ['routeOrders', 'unscheduleOrders'];

    connect() {

        try {
            Sortable?.mount(new MultiDrag())
        } catch (error) {
            
        }
        
        Sortable.create(this.routeOrdersTarget, {
            sort: false,  // sorting inside list
            // multiDrag: true,
            selectedClass: "sortable-selected",
            group: {
                name: 'routeOrders',
                put: function (to) {
                    return true
                }
            },
            // Element is dropped into the list from another list
            onAdd: function (evt) {
                var items = []
                if (evt.items.length == 0) {
                    items.push(evt.item);
                } else {
                    items = evt.items;
                }
                this.dispatch('addedOrder', { detail: { 
                    items: items
                }})
            }.bind(this),
            animation: 100
        });

        Sortable.create(this.unscheduleOrdersTarget, {
            sort: false,
            multiDrag: true,
            selectedClass: "sortable-selected",
            group: {
                name: 'unscheduleOrders',
                put: 'routeOrders',
                pull: function (to, from) {
                    return true
                }
            },
            animation: 100
        });
    }
}
