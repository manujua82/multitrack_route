import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['routeTable'];

    static values = {
        routeSelectedId: String
    }

    routeSelectedId = null;

    routeClickHandler = function(table, route) { 
        return function () {
            var cell = route.getElementsByTagName("td")[1];
            
            if(this.routeSelectedId !=  cell.innerHTML.trim()) {
                this.routeSelectedId = cell.innerHTML.trim();
                this.dispatch('routeClicked', {
                    detail: { 
                        routeId: this.routeSelectedId
                    }
                });
            }
        }
    }
    
    addClickEventToRouteList() {
        var routes = this.routeTableTarget.getElementsByTagName('tr');
        for (var i = 0; i < routes.length; i++) {
            var currentRow = this.routeTableTarget.rows[i];
            currentRow.onclick = this.routeClickHandler(this.routeTableTarget, currentRow).bind(this);
        }
    }

    connect() {
        this.routeSelectedId = this.routeSelectedIdValue;
        this.addClickEventToRouteList();
    }
}
