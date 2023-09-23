import { Controller } from 'stimulus';

export default class extends Controller {

    static targets = ['content'];

    static values = {
        url:  String,
        addRouteUrl: String,
        routeSelectedId: String
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
       
        target.style.opacity = .5;
        const response = await fetch(url);
        target.innerHTML = await response.text();
        target.style.opacity = 1;
    }

    async refreshContent({ detail: { routeId }} ) {
        console.log(`refreshContent: ${routeId}`);
        this.fetchDashboard(routeId);
    }

    async addOrderToRoute( { detail: { items }} ) {
        var orderIds = [];
        for (var i = 0; i < items.length; i++) {
            var row = items[i];
            var rowOrderId = row.getElementsByTagName("td")[0];
            
            orderIds.push(rowOrderId.innerHTML.trim());
        }

        const params = new URLSearchParams({
            routeId: this.routeSelectedId,
            orderIds: orderIds,
        });
        const response =  await fetch(`${this.addRouteUrlValue}?${params.toString()}`);
        this.contentTarget.innerHTML =  await response.text();
    }
}
