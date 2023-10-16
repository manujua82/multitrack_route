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

    connect() {
        this.mapUtils = new MapUtils(this.mapObjTarget);
        this.mapUtils.init(this.latitudeTarget.value,this.longitudeTarget.value);
    }

    async initMap(lat, lng) {
        this.mapUtils.init(lat, lng);
    }

    async onLocationClick() {
        const addressValue = `${this.streetTarget.value}, ${this.cityTarget.value}, ${this.stateTarget.value} ${this.postalCodeTarget.value}, ${this.countryTarget.value}`;
        const geoResult = await this.mapUtils.getGeocodingByAddress(addressValue, this.apiKeyValue);
        if (geoResult && geoResult.formatted_address) {
            this.fullAddressTarget.value = geoResult.formatted_address;
            this.latitudeTarget.value = geoResult.geometry.location.lat;
            this.longitudeTarget.value = geoResult.geometry.location.lng;

            this.mapUtils.init(this.latitudeTarget.value,this.longitudeTarget.value);
        }
    }
}