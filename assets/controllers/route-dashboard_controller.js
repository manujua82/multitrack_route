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

            console.log(`routeClickHandler`);
            console.log(this.routeSelectedId);
            console.log(cell.innerHTML.trim());
            
            if(this.routeSelectedId !=  cell.innerHTML.trim()) {
                this.routeSelectedId = cell.innerHTML.trim();
                console.log(this.routeSelectedId);
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
        this.routeSelectedId = this.routeSelectedIdValue;
        this.addClickEventToRouteList();
    }
}
