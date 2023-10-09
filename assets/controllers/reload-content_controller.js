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
        this.routeSelectedId = this.routeSelectedIdValue;
        this.mapUtils = new MapUtils(this.mapObjTarget);
        this.mapUtils.init(0, 0);
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
}
