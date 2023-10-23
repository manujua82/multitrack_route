import { Controller } from  'stimulus';
import MapUtils from '../mapUtils';
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

    async connect() {        
        this.mapUtils = new MapUtils(this.mapObjTarget);
        await this.mapUtils.init(this.latitudeTarget.value,this.longitudeTarget.value);
        this.mapUtils.addMarker(this.latitudeTarget.value,this.longitudeTarget.value);
    }

    async initMap(lat, lng) {
        if (!this.mapUtils.isReady()){
            await this.mapUtils.init(this.latitudeTarget.value,this.longitudeTarget.value);
            this.mapUtils.addMarker(this.latitudeTarget.value,this.longitudeTarget.value);
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

            this.mapUtils.resetMarkets();
            this.mapUtils.setCenter(geoResult.geometry.location.lat, geoResult.geometry.location.lng);
            this.mapUtils.addMarker(geoResult.geometry.location.lat, geoResult.geometry.location.lng);
        }
    }
}