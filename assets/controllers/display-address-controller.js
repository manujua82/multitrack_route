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

    static values = {
        apiKey:  String,
    }

    connect() {
        this.initMap(this.latitudeTarget.value,this.longitudeTarget.value);
    }

    validateCoordinate (coordinate) {
        console.log(typeof coordinate)
        const coordinateAsNumber = Number(coordinate);
        if (isNaN(coordinateAsNumber)) {
            return 0;
        }
        return coordinateAsNumber;
    }

    async initMap(lat, lng) {
        if (typeof(google) != "undefined"){
            const { Map } = await google.maps.importLibrary("maps");

            const position = {
                lat: this.validateCoordinate(lat), 
                lng: this.validateCoordinate(lng)
            }
            
            const map = new Map(this.mapObjTarget, {
                center: position,
                zoom: 12,
            });

            const marker = new google.maps.Marker({
                map: map,
                position: position,
              });
        }
    }

    async onLocationClick() {
        const addressValue = `${this.streetTarget.value}, ${this.cityTarget.value}, ${this.stateTarget.value} ${this.postalCodeTarget.value}, ${this.countryTarget.value}`;
        this.getGeocodingByAddress(addressValue, this.apiKeyValue);
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
            this.latitudeTarget.value = geoResult.geometry.location.lat;
            this.longitudeTarget.value = geoResult.geometry.location.lng;

            this.initMap(this.latitudeTarget.value,this.longitudeTarget.value);
        }
    }

}