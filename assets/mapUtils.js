
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
        if (typeof(google) != "undefined" && this.map == null ){
            
            const { Map } = await google.maps.importLibrary("maps");         
            const position = {
                lat: this.validateCoordinate(latitude), 
                lng: this.validateCoordinate(longitude)
            }
            
            this.map = new Map(this.mapObj, {
                center: position,
                zoom: 10,
                mapTypeId: "terrain",
            });

            // const encodedPolyline = "clg}Cvh`hNK}GGIKgAIOUOSAUBa@fAIp@F`KCXGXFhFLvGFjEHnBDnAZnHTnCX`B`@zAn@jBfOd^j@nB^nBNfBH`ClA|w@?fDGjI[jHBfHBlBT~`@JhO^pUJ~Hd@~YTvNC`CKdB_@xBg@hBk@rAu@nAaI`Li@fAe@jAe@bBYjBMfBCjCnBnrAFjEPbK@zBPvLVtCRpALb@@VlAtCPp@D|@Ah@SfAm@rC?f@Hd@P`@r@x@rAr@lDpAnAv@~@t@LCxAhAjC`BdChA|Bt@n@NdBTbBFfA@bBMhOkBtFg@tFYdGK|NGTQtFCFA|i@JzFIlGSzIc@h}AsCpDCvLRxGGzXc@`CGpGMhEUtEWdEIdt@q@jw@u@fEK|Xq@Z@vACbCSlB[xBm@~GgCdCk@h@IvCWlGKpFC~KB`HGnZc@|CDdCPnC\\fEp@^JbD\\|CLnFErDGTIxs@mAlCBz@Jt@Tx@h@r@l@t@dAd@tAb@pB`@vCf@jFVdETvH\\zKPT~@nwADd@Rb^AtGChCs@d^E`JNtWRvFh@fHhCnSnBnOd@zBt@pCpApDrB~E`ArBLZLNj@pAh@nBrCjLJ\\t@rA|@bAx@p@fAh@pA`@`BVtAF`EFf@GxB?zMIvDDfJIfGOvI@|AAXNx@JZLRRHV@b@M^SRUF[@[MSWKe@EiA@eDAwG]gKAaDHmEAaAK]IyFEy@?c@CS`AGtDFbFI~DS`@CHG|@E`XWrDA|BCNMBW?g@G{BE{EDuEKSNaDVyBhEiTXmCL{B@uCEcBeBe]GqDFaETgEb@{Dj@eDt@}CjAwDnDoJh@uB`@wCLwBHsEIoQUmLGeFSeC_@aCWaAsAiEoGcTmAcEw@}Ci@}AIa@{AqEuCuI{AaEyAeDkBwEu@cCFk@Y{BCiB?{@PsBPaA^_AdAkBn@y@hAcAt@i@vM{Ir@a@DYzIsEpAg@zBo@hCa@vBSdHMpRW~ASjCm@J[hFeBr@e@Vc@Ng@He@HoCL}ALo@|AcFNi@NqAz@oKLaC?iBKuCIw@?sB|NO`@BrJ|A_AzHDnFgA?e@B_@HI?SEa@FcJ@sCJI@o@_@Q[Ei@HgAAgBOgAqAN}CFJlIAXGJOpBBjCIJQvBm@xDI`BBpCB\\?^Mn@Sd@[`@QPa@PaIbC_APsAFs@@WOsTVgBJiBPiC\\kCl@{Bv@yCnAub@nQmFxBcBj@cAX}Ex@oALoDL{_@PcNTsBFcHHQLSD}v@x@q`@\\sTZm[t@{C?wCMeCWM?sF_AcD]aDMyCAqQZiMPoF@_JC}FBmBBuCR_C`@oA\\sHnCeCl@uBX{BJs]z@gdBbBcPRqFV}DTsKRaa@h@eEEeHMsDBe{AnCgGVgHZwELuDBwLCwUEuE@UEyC@SGKGcKBiLNmZt@kFPYMwGE{@Mu@[s@i@uCqDcHcJOMyFwHEAi@y@yAsDUgAS{AEgAkAoz@eAup@K_HFoDToBd@sBt@uBr@sAr@}@j@{@jA{Ab@s@nAeBr@iAd@aAv@uB^eBNiAL{B@oBq@eb@c@wWSiPIwKWsb@BsECuBJwDPeEH}IIqHe@cWi@{]MqB]qB[sAm@iBsEsK{H_Ra@wASqAK}AAuA\\sNDkEEgFE}BG}H"
            // var decodedPath = google.maps.geometry.encoding.decodePath(encodedPolyline);
            // console.log(decodedPath);

            // const flightPath = new google.maps.Polyline({
            //     path: decodedPath,
            //     geodesic: true,
            //     strokeColor: "#FF0000",
            //     strokeOpacity: 1.0,
            //     strokeWeight: 4,
            // });

            // flightPath.setMap(this.map);
        }
    }

    isReady() {
        return this.map != null;
    }

    setCenter(latitude, longitude) {
        this.map.setCenter(new google.maps.LatLng(latitude, longitude));
    }

    resetMarkets() {
        for (let i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }
        this.markers = [];
    }

    addMarker(latitude, longitude) {
        this.markers.push(new google.maps.Marker({
            map: this.map,
            position: {
                lat: this.validateCoordinate(latitude), 
                lng: this.validateCoordinate(longitude)
            },
        }));
    }

    addMarketWithLabel(label, latitude, longitude) {
        this.markers.push(new google.maps.Marker({
            map: this.map,
            label: label,
            position: {
                lat: this.validateCoordinate(latitude), 
                lng: this.validateCoordinate(longitude)
            },
        }));
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