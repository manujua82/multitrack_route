import { Controller } from 'stimulus';
import {Sortable, MultiDrag} from 'sortablejs';
import MapUtils from '../mapUtils';

export default class extends Controller {

    static targets = [
        'content',
        'mapObj',
        'routeOrders', 
        'unscheduleOrders'
    ];

    static values = {
        url:  String,
        addRouteUrl: String,
        removeRouteUrl: String,
        routeSelectedId: String
    }

    routeSelectedId = null;

    connect() {
        try {
            Sortable?.mount(new MultiDrag())
        } catch (error) {
            
        }

        this.routeSelectedId = this.routeSelectedIdValue;
        this.mapUtils = new MapUtils(this.mapObjTarget);
        this.mapUtils.init(0, 0);

        this.setupReSizer();
        this.setupLeftVerticalPanel();
        this.setupRightVerticalPanel();
        this.makeRouteOrdersSortable();
        this.makeUnscheduleOrdersSortable();
    }

    async initMap(lat, lng) {
        this.mapUtils.init(lat, lng);
    }

    async fetchDashboard(currentRouteId = null) {
        const target = this.hasContentTarget ? this.contentTarget : this.element;
        
        var url = this.urlValue;
        if (currentRouteId != null) {
            var params = new URLSearchParams({
                currentRouteId: currentRouteId,
            });
            url = `${url}?${params.toString()}`;
        }
       
        const response = await fetch(url);
        target.innerHTML =  await response.text();

        this.refreshScriptsBehaviors();
        this.dispatch('success');
    }

    async refreshContent({ detail: { routeId }} ) {
        this.routeSelectedId = routeId;
        this.fetchDashboard(routeId);
    }

    async addOrderToRoute(items) {
        var orderIds = this.getRouteOrdersIds(items);
        const endpointUri = this.getEndpointUri(this.addRouteUrlValue, orderIds);
        const response =  await fetch(endpointUri);
        
        this.contentTarget.innerHTML =  await response.text();
        this.refreshScriptsBehaviors();
    }

    async removeOrderToRoute(items) {
        var orderIds = this.getRouteOrdersIds(items);
        const endpointUri = this.getEndpointUri(this.removeRouteUrlValue, orderIds);
        const response =  await fetch(endpointUri);
        this.contentTarget.innerHTML =  await response.text();

        this.refreshScriptsBehaviors();
    }

    getEndpointUri (baseUrl, orderIds) {
        const params = new URLSearchParams({
            routeId: this.routeSelectedId,
            orderIds: orderIds,
        });
        return `${baseUrl}?${params.toString()}`
    }

    getRouteOrdersIds(items) {
        var orderIds = [];
        for (var i = 0; i < items.length; i++) {
            var rowOrderId = items[i].getElementsByTagName("td")[0];
            orderIds.push(rowOrderId.innerHTML.trim());
        }
        return orderIds;
    }

    // Refresh Script Behaviors
    refreshScriptsBehaviors() {
        this.setupLeftVerticalPanel();
        this.makeRouteOrdersSortable();
    }
    

    // Sortable
    getSelectedItemsFormSortable(evt) {
        var items = []
        if (evt.items.length == 0) {
            items.push(evt.item);
            return items;
        }
        return  evt.items;
    }

    makeRouteOrdersSortable() {
        Sortable.create(this.routeOrdersTarget, {
            sort: false,  // sorting inside list
            multiDrag: true,
            selectedClass: "sortable-selected",
            group: {
                name: 'routeOrders',
                put: function (to) {
                    return true
                }
            },
            // Element is dropped into the list from another list
            onAdd: function (evt) {
                const items = this.getSelectedItemsFormSortable(evt);
                this.addOrderToRoute(items);
            }.bind(this),

            onRemove: function(evt){
                const items = this.getSelectedItemsFormSortable(evt);
                this.removeOrderToRoute(items);
            }.bind(this),

            animation: 100
        });
    }

    makeUnscheduleOrdersSortable() {
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

    // Resize panels functions 
    setupReSizer() {
        const leftPanel = document.querySelector("#left-panel");
        const rightPanel = document.querySelector("#right-panel");
        const gutter = document.querySelector("#horizontal-gutter");

        gutter.addEventListener('mousedown',reSizer);

        function reSizer(e) {
            let prevX = e.x;
            const leftPanelBoundingRect = leftPanel.getBoundingClientRect();
            const rightPanelBoundingRect = rightPanel.getBoundingClientRect();
    
            window.addEventListener('mousemove',mousemove);
            window.addEventListener('mouseup',mouseup);
            
            function mousemove(e) {
                let newX = prevX - e.x;
                leftPanel.style.width = leftPanelBoundingRect.width - newX + "px";
                rightPanel.style.width = rightPanelBoundingRect.width + newX + "px";
                
            }
        
            function mouseup() {
                window.removeEventListener('mousemove', mousemove);
                window.removeEventListener('mouseup',mouseup);
            }
        }
    }

    setupLeftVerticalPanel() {
        const routePanel = document.querySelector("#route-panel");
        const routeAddressPanel = document.querySelector("#route-address-panel");
        const routeOrderPanel = document.querySelector("#route-order-panel");

        const routeAddressGutter = document.querySelector("#route-address-gutter");
        const addressOrderGutter = document.querySelector('#address-order-gutter');
        
        routeAddressGutter.addEventListener('mousedown',reRouteAddressSizer);   
        addressOrderGutter.addEventListener('mousedown',reAddressOrderSizer);   

        function reRouteAddressSizer(e) {
            let prevRouteY = e.y;

            const routePanelBoundingRect = routePanel.getBoundingClientRect();
            const addressPanelBoundingRect = routeAddressPanel.getBoundingClientRect();
            
            window.addEventListener('mousemove',mouseAddressMove);
            window.addEventListener('mouseup',mouseAddressUp);

            function mouseAddressMove(e) {
                let newY = prevRouteY - e.y;
                
                if (!(addressPanelBoundingRect.height + newY <= 50  || routePanelBoundingRect.height - newY <=50 )){
                    routePanel.style.height = routePanelBoundingRect.height - newY + "px";
                    routeAddressPanel.style.height = addressPanelBoundingRect.height + newY + "px";
                }
               
            }
        
            function mouseAddressUp() {
                window.removeEventListener('mousemove', mouseAddressMove);
                window.removeEventListener('mouseup',mouseAddressUp);
            }
        }

        function reAddressOrderSizer(e) {
            let prevY = e.y;

            const routePanelBoundingRect = routePanel.getBoundingClientRect();
            const routeAddressPanelBoundingRect = routeAddressPanel.getBoundingClientRect();
            const orderPanelBoundingRect = routeOrderPanel.getBoundingClientRect();
            
            window.addEventListener('mousemove',mouseRouteAddressMove);
            window.addEventListener('mouseup',mouseRouteAddressUp);

            function mouseRouteAddressMove(e) {
                let newY = prevY - e.y;
                console.log(`
                newY: ${newY}
                routeAddressPanelBoundingRect: ${routeAddressPanelBoundingRect.height - newY}
                `);
                
                if (routeAddressPanelBoundingRect.height - newY > 50 &&  orderPanelBoundingRect.height + newY > 150 ) {
                    routeAddressPanel.style.height = routeAddressPanelBoundingRect.height - newY + "px";
                    routeOrderPanel.style.height = orderPanelBoundingRect.height + newY + "px";
                }
                
            }
        
            function mouseRouteAddressUp() {
                window.removeEventListener('mousemove', mouseRouteAddressMove);
                window.removeEventListener('mouseup',mouseRouteAddressUp);
            }
        }
    }

    setupRightVerticalPanel() {
        const topPanel = document.querySelector("#routeMap");
        const bottomPanel = document.querySelector("#unschedule-order-panel");
        const verticalGutter = document.querySelector("#right-panel-vertical-gutter");

        verticalGutter.addEventListener('mousedown',reVerticalSizer);

        function reVerticalSizer(e) {
            let prevY = e.y;
            console.log(prevY);
            const topPanelBoundingRect = topPanel.getBoundingClientRect();
            const bottomPanelBoundingRect = bottomPanel.getBoundingClientRect();

            window.addEventListener('mousemove',mouseVerticalMove);
            window.addEventListener('mouseup',mouseVerticalUp);

            function mouseVerticalMove(e) {
                let newY = prevY - e.y;
                topPanel.style.height = topPanelBoundingRect.height - newY + "px";
                bottomPanel.style.height = bottomPanelBoundingRect.height + newY + "px";
            }
        
            function mouseVerticalUp() {
                window.removeEventListener('mousemove', mouseVerticalMove);
                window.removeEventListener('mouseup',mouseVerticalUp);
            }
        }
        
    }
}
