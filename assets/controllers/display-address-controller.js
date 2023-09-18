import { Controller } from  'stimulus';

export default class extends Controller {

    static targets = [
        'street', 
        'city',
        'state',
        'postalCode',
        'country',
        'fullAddress',
        'mapObj',
        'latitude',
        'longitude',
    ];

    connect() {
        const key = '';
        const addressValue = `${this.streetTarget.value}, ${this.cityTarget.value}, ${this.stateTarget.value} ${this.postalCodeTarget.value}, ${this.countryTarget.value}`;
        const mapUri = this.getMapUrl(key, addressValue);
        this.mapObjTarget.innerHTML = this.getMapHtml(mapUri);
    }

    async onLocationClick() {
        // console.log(`onLocationClick`);
        const addressValue = `${this.streetTarget.value}, ${this.cityTarget.value}, ${this.stateTarget.value} ${this.postalCodeTarget.value}, ${this.countryTarget.value}`;
        const key = '';
        this.getGeocodingByAddress(addressValue, key);
    }

    getMapUrl(key, addressValue) {
        const urlBase = 'https://www.google.com/maps/embed/v1/place';
        const params = new URLSearchParams({
            q: addressValue,
            key: key,
        });
        return `${urlBase}?${params.toString()}`;
    }

    getMapHtml(mapUri) {
        return `
            <iframe
                width="600"
                height="450"
                style="border:0"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src=${mapUri}>
            </iframe>`;
    }

    async getGeocodingByAddress(address, key) {
        const urlBase = 'https://maps.googleapis.com/maps/api/geocode/json';
        const params = new URLSearchParams({
            address: address,
            key: key,
        });
        const response =  await fetch(`${urlBase}?${params.toString()}`);
        const geoResponse =  await response.json();
        const geoResult = geoResponse.results[0];    

        if (geoResult && geoResult.formatted_address) {
            this.fullAddressTarget.value = geoResult.formatted_address;
            const mapUri = this.getMapUrl(key, geoResult.formatted_address);
            
            console.log(`mapUri: ${mapUri}`);

            this.mapObjTarget.innerHTML = this.getMapHtml(mapUri);

            this.latitudeTarget.value = geoResult.geometry.location.lat;
            this.longitudeTarget.value = geoResult.geometry.location.lng;
        }
    }

}