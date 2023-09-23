import { Controller } from 'stimulus';

export default class extends Controller {

    static targets = ['content', 'routeTable'];

    static values = {
        url:  String,
        addRouteUrl: String,
        routeSelectedId: String
    }

    routeList = null;
    routeSelectedId = null

    routeClickHandler = function(table, route) { 
        return function () {
            var cell = route.getElementsByTagName("td")[1];

            console.log(`routeClickHandler`);
            console.log(this.routeSelectedId);
            
            if(this.routeSelectedId !=  cell.innerHTML.trim()) {
                this.routeSelectedId = cell.innerHTML.trim();
                console.log(this.routeSelectedId);
                var routes = table.getElementsByTagName('tr');
                
                for (var i = 0; i < routes.length; i++) {
                    var row = table.rows[i];
                    var checkInput = row.getElementsByTagName("td")[0];
                    var rowRouteId = row.getElementsByTagName("td")[1];
                    
                    if (typeof checkInput !== 'undefined' && typeof rowRouteId !== 'undefined'){
                        if (this.routeSelectedId == rowRouteId.innerHTML) {
                            checkInput.innerHTML = `
                                <div class="form-check mb-0 fs-0" >
                                    <input class="form-check-input"type="checkbox" checked=""/>
                                </div>`;
                        } else {
                            checkInput.innerHTML = `
                                <div class="form-check mb-0 fs-0" >
                                    <input class="form-check-input"type="checkbox"/>
                                </div>`;
                        }
                    }
                }

                this.fetchDashboard(this.routeSelectedId);
            }
        }
    }

    addClickEventToRouteList() {
        var routes = this.routeTableTarget.getElementsByTagName('tr');
        console.log(`
            addClickEventToRouteList
            ${routes.length}
        `);
        for (var i = 0; i < routes.length; i++) {
            var currentRow = this.routeTableTarget.rows[i];
            currentRow.onclick = this.routeClickHandler(this.routeTableTarget, currentRow).bind(this);
        }
    }

    connect() {
        this.routeSelectedId = this.routeSelectedIdValue
        this.addClickEventToRouteList();
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

    async refreshContent(event) {
        this.fetchDashboard();
        this.addClickEventToRouteList();
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
