import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        var collectionHolder =   $('#address_list');
        collectionHolder.data('index', collectionHolder.find('.panel').length);

        var _addRemoveButton = this.addRemoveButton;
        collectionHolder.find('.panel').each(function () {
            console.log('Here');
            _addRemoveButton($(this));
        });
    }

    onNewItemClick(e) {
        e.preventDefault();
        this.addNewForm();
    }

    addNewForm() {
        var collectionHolder =   $('#address_list');
        var prototype = collectionHolder.data('prototype');
        var index = collectionHolder.data('index');
        var newForm = prototype;
    
        newForm = newForm.replace(/__name__/g, index);
        collectionHolder.data('index', index+1);
    
        var panel = $('<tr class="panel"></tr>');
        
        panel.append(newForm);
    
        // append the remove button to the new panel
        this.addRemoveButton(panel);
        $('#tbody').append(panel);
    
        this.addAutocomplete(index);
    };

    addRemoveButton (panel) {
        var removeButton = $(`<a class="phoenix-danger me-2" href="#" data-original-title="Edit" data-toggle="tooltip"><i class="fa-solid fa-trash me-2"></i></a> `);
        var panelFooter = $('<td class="align-middle white-space-nowrap text-end pe-0"></td>').append(removeButton);
        
        removeButton.click(function (e) {
            e.preventDefault();
            $(e.target).parents('.panel').slideUp(100, function () {
                $(this).remove();
            })
        });
    
        panel.append(panelFooter);
    }

    addAutocomplete(index) {
        const addressSearchBoxId = `customer_addresses_${index}_fullAddress`;
        const latitudeBoxId = `customer_addresses_${index}_latitude`;
        const longitudeBoxId = `customer_addresses_${index}_longitude`;
        const streetBoxId = `customer_addresses_${index}_street`;
        const cityBoxId = `customer_addresses_${index}_city`;
        const stateBoxId = `customer_addresses_${index}_state`;
        const postalcodeBoxId = `customer_addresses_${index}_postalcode`;
        const countryBoxId = `customer_addresses_${index}_country`;
            
        if (typeof(google) != "undefined"){
            const addressInput = document.getElementById(addressSearchBoxId);        
    
            const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                fields: ["formatted_address", "geometry", "name", 'address_components'],
                strictBounds: false,
            });
    
            autocomplete.addListener("place_changed", () => {
                const latitudeInput = document.getElementById(latitudeBoxId);
                const longitudeInput = document.getElementById(longitudeBoxId);

                const streetInput = document.getElementById(streetBoxId);
                const cityInput = document.getElementById(cityBoxId);
                const stateInput = document.getElementById(stateBoxId);
                const postalcodeInput = document.getElementById(postalcodeBoxId);
                const countryInput = document.getElementById(countryBoxId);
    
                const place = autocomplete.getPlace();
    
                latitudeInput.value = place.geometry.location.lat();
                longitudeInput.value = place.geometry.location.lng();

                const streetNumber = place.address_components.find((element) => {
                    return element.types.includes("street_number")
                });

                const streetName = place.address_components.find((element) => {
                    return element.types.includes("route")
                });

                const city = place.address_components.find((element) => {
                    return element.types.includes("locality")
                });

                const state = place.address_components.find((element) => {
                    return element.types.includes("administrative_area_level_1")
                });

                const country = place.address_components.find((element) => {
                    return element.types.includes("country")
                });

                const postalCode = place.address_components.find((element) => {
                    return element.types.includes("postal_code")
                });

                streetInput.value = `${streetNumber.short_name} ${streetName.short_name}`;
                cityInput.value = city.short_name;
                stateInput.value = state.short_name;
                postalcodeInput.value = postalCode.short_name;
                countryInput.value = country.short_name;
            });
            
        }
    }
}