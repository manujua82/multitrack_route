import { Controller } from 'stimulus';

export default class extends Controller {

    static targets = ['content', 'routeTable'];

    static values = {
        url:  String
    }

    routeList = null;

    routeClickHandler = function(table, route) {
        return function () {
            var cell = route.getElementsByTagName("td")[1];
            var routeSelectedId = cell.innerHTML;
        
            var routes = table.getElementsByTagName('tr');
            for (var i = 0; i < routes.length; i++) {

                var row = table.rows[i];
                var checkInput = row.getElementsByTagName("td")[0];
                var rowRouteId = row.getElementsByTagName("td")[1];
                
                if (typeof checkInput !== 'undefined' && typeof rowRouteId !== 'undefined'){
                    if (routeSelectedId == rowRouteId.innerHTML) {
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
        }
    }

    addClickEventToRouteList() {
        var routes = this.routeTableTarget.getElementsByTagName('tr')
        for (var i = 0; i < routes.length; i++) {
            var currentRow = this.routeTableTarget.rows[i];
            currentRow.onclick = this.routeClickHandler(this.routeTableTarget, currentRow);
        }
    }

    connect() {
        this.addClickEventToRouteList();
    }

    async refreshContent(event) {
        const target = this.hasContentTarget ? this.contentTarget : this.element;

        target.style.opacity = .5;
        const response = await fetch(this.urlValue);
        target.innerHTML = await response.text();

        target.style.opacity = 1;

        this.addClickEventToRouteList();
    }
}
