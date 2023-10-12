import { Controller } from 'stimulus';
import MapUtils from '../mapUtils';

export default class extends Controller {

    static targets = [
        'content',
        'mapObj',
    ];

    static values = {
        url:  String,
        addRouteUrl: String,
        removeRouteUrl: String,
        routeSelectedId: String
    }

    routeSelectedId = null;

    connect() {
        console.log(`connected`);
        this.routeSelectedId = this.routeSelectedIdValue;
        this.mapUtils = new MapUtils(this.mapObjTarget);
        this.mapUtils.init(0, 0);

        this.setupReSizer();
        this.setupLeftVerticalPanel();
        this.setupRightVerticalPanel();
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
        
        // const responseHtml = await response.text();
        
        // var newElement = this.htmlToElement(responseHtml);
        // console.log(newElement);
        // const leftPanelElement = newElement.querySelector('#leftPanel')
        // console.log(leftPanelElement);
        console.log(this.mapObjTarget.innerHTML);
        target.innerHTML =  await response.text();

        this.dispatch('success');
    }

    async refreshContent({ detail: { routeId }} ) {
        this.routeSelectedId = routeId;
        this.fetchDashboard(routeId);
    }

    async addOrderToRoute( { detail: { items }} ) {
        var orderIds = this.getRouteOrdersIds(items);
        const endpointUri = this.getEndpointUri(this.addRouteUrlValue, orderIds);
        const response =  await fetch(endpointUri);
        
        this.contentTarget.innerHTML =  await response.text();
    }

    async removeOrderToRoute({ detail: { items }} ) {
        var orderIds = this.getRouteOrdersIds(items);
        const endpointUri = this.getEndpointUri(this.removeRouteUrlValue, orderIds);
        const response =  await fetch(endpointUri);
        this.contentTarget.innerHTML =  await response.text();
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

    setupReSizer() {
        const leftPanel = document.querySelector("#left-panel");
        const rightPanel = document.querySelector("#right-panel");
        const gutter = document.querySelector("#horizontal-gutter");

        gutter.addEventListener('mousedown',reSizer);

        function reSizer(e) {
            let prevX = e.x;
            const leftPanelBoundingRect = leftPanel.getBoundingClientRect();
    
            window.addEventListener('mousemove',mousemove);
            window.addEventListener('mouseup',mouseup);
            
            function mousemove(e) {
                let newX = prevX - e.x;
                leftPanel.style.width = leftPanelBoundingRect.width - newX + "px";
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

        const routeAddressGutter = document.querySelector("#route-address-gutter");
        const addressOrderGutter = document.querySelector('#address-order-gutter');
        
        routeAddressGutter.addEventListener('mousedown',reRouteAddressSizer);   
        addressOrderGutter.addEventListener('mousedown',reAddressOrderSizer);   


        function reRouteAddressSizer(e) {
            let prevRouteY = e.y;

            const routePanelBoundingRect = routePanel.getBoundingClientRect();
            window.addEventListener('mousemove',mouseAddressMove);
            window.addEventListener('mouseup',mouseAddressUp);

            function mouseAddressMove(e) {
                let newY = prevRouteY - e.y;
                routePanel.style.height = routePanelBoundingRect.height - newY + "px";
            }
        
            function mouseAddressUp() {
                window.removeEventListener('mousemove', mouseAddressMove);
                window.removeEventListener('mouseup',mouseAddressUp);
            }
        }

        function reAddressOrderSizer(e) {
            let prevY = e.y;

            const routeAddressPanelBoundingRect = routeAddressPanel.getBoundingClientRect();
            window.addEventListener('mousemove',mouseRouteAddressMove);
            window.addEventListener('mouseup',mouseRouteAddressUp);

            function mouseRouteAddressMove(e) {
                let newY = prevY - e.y;
                routeAddressPanel.style.height = routeAddressPanelBoundingRect.height - newY + "px";
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
            const topPanelBoundingRect = topPanel.getBoundingClientRect();

            window.addEventListener('mousemove',mouseVerticalMove);
            window.addEventListener('mouseup',mouseVerticalUp);

            function mouseVerticalMove(e) {
                let newY = prevY - e.y;
                topPanel.style.height = topPanelBoundingRect.height - newY + "px";
            }
        
            function mouseVerticalUp() {
                window.removeEventListener('mousemove', mouseVerticalMove);
                window.removeEventListener('mouseup',mouseVerticalUp);
            }
        }
        
    }
}
