
export default class MapUtils {

    mapObj = null;
    map = null;
    markers = [];

    constructor(element) {
        this.mapObj = element;
    }

    validateCoordinate (coordinate) {
        const coordinateAsNumber = Number(coordinate);
        if (isNaN(coordinateAsNumber)) {
            return 0;
        }
        return coordinateAsNumber;
    }

    async init(latitude, longitude) {
        if (typeof(google) != "undefined"){
            
            const { Map } = await google.maps.importLibrary("maps");

            const position = {
                lat: this.validateCoordinate(latitude), 
                lng: this.validateCoordinate(longitude)
            }
            
            this.map = new Map(this.mapObj, {
                center: position,
                zoom: 12,
            });

            this.markers.push(new google.maps.Marker({
                map: this.map,
                position: position,
            }));
        }
    }

    async getGeocodingByAddress(address, key) {
        const urlBase = 'https://maps.googleapis.com/maps/api/geocode/json';
        const params = new URLSearchParams({
            address: address,
            key: key,
        });
        const response =  await fetch(`${urlBase}?${params.toString()}`);
        const geoResponse =  await response.json();
        return geoResponse.results[0];    
    }
};