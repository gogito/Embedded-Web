<!DOCTYPE html>
<html>

<head>
    <title>Quick Start - Leaflet</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1.0" />

    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin="">
    </script>

    <style>
        .text-labels {
            font-size: 8pt;
            width: 500pt;
            font-weight: 300;
            color: black;
        }
    </style>


<body style="padding: 0; margin: 0">
    <div id="mapid" style="width: 100%; height: 40vh"></div>
    <script>
        var popupArray = [];
        var markerArray = [];
        var ORS_API_KEY =
            "5b3ce3597851110001cf624848475da112574d1eb33f2348aff842e8";
        var CUR_LOCATION;
        var mymap = L.map("mapid", {
            zoomControl: false,
            attributionControl: false,
        }).setView([10.77057, 106.672547], 17);

        L.tileLayer(
            "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw", {
                maxZoom: 25,
                attribution: "Map data &copy; OpenStreetMap contributors, ",
                id: "mapbox/streets-v11",
            }
        ).addTo(mymap);
        var routingLayer = L.layerGroup().addTo(mymap);
        var additonalLayer = new L.FeatureGroup();
        var parkingLayer = new L.FeatureGroup();
        var routingLine;



        mymap.addLayer(additonalLayer);
        mymap.addLayer(parkingLayer);

        var HoangSaLatLng = [16.32, 111.367];
        var HoangSa = L.marker(HoangSaLatLng, {
            icon: L.divIcon({
                className: "text-labels",
                html: "Hoang Sa",
            }),
            zIndexOffset: 1000,
        }).addTo(mymap);
        additonalLayer.addLayer(HoangSa);

        var TruongSaLatLng = [8.3830, 111.5555];
        var TruongSa = L.marker(TruongSaLatLng, {
            icon: L.divIcon({
                className: "text-labels",
                html: "Truong Sa",
            }),
            zIndexOffset: 1000,
        }).addTo(mymap);
        additonalLayer.addLayer(TruongSa);

        mymap.on("zoomend", function() {
            if (mymap.getZoom() <= 3) {
                mymap.removeLayer(additonalLayer);
            } else {
                mymap.addLayer(additonalLayer);
            }
            if (mymap.getZoom() <= 10) {
                mymap.removeLayer(parkingLayer);
            } else {
                mymap.addLayer(parkingLayer);
            }

        });

        var fullLots = L.icon({
                iconUrl: "img/fullSlots.png",
                iconSize: [38, 38]
            }),
            normalLots = L.icon({
                iconUrl: "img/normalSlots.png",
                iconSize: [38, 38]
            }),
            emptyLots = L.icon({
                iconUrl: "img/emptySlots.png",
                iconSize: [38, 38]
            }),
            carLoc = L.icon({
                iconUrl: "img/car.png",
                iconSize: [38, 38]
            });
        // var curCarLocation = L.marker([10.7715454, 106.6577752], {
        //     icon: carLoc,
        // });
        // parkingLayer.addLayer(curCarLocation);

        function markLocation(lat, long, iconType, id) {
            switch (iconType) {
                case 1:
                    markerArray[id] = L.marker([lat, long], {
                        icon: fullLots
                    }).on(
                        "click",
                        function(e) {
                            showDetail(id);
                        }
                    );

                    parkingLayer.addLayer(markerArray[id]);
                    popupArray[id] = L.popup();
                    popupArray[id]
                        .setLatLng(markerArray[id].getLatLng())
                        .setContent("<p>Loading</p>")

                    markerArray[id].bindPopup(popupArray[id]);
                    break;
                case 2:
                    markerArray[id] = L.marker([lat, long], {
                        icon: emptyLots
                    }).on(
                        "click",
                        function(e) {
                            showDetail(id);
                        }
                    );
                    parkingLayer.addLayer(markerArray[id]);
                    popupArray[id] = L.popup();
                    popupArray[id]
                        .setLatLng(markerArray[id].getLatLng())
                        .setContent("<p>Loading</p>")

                    markerArray[id].bindPopup(popupArray[id]);
                    break;
                case 3:
                    markerArray[id] = L.marker([lat, long], {
                        icon: emptyLots
                    }).on(
                        "click",
                        function(e) {
                            showDetail(id);
                        }
                    );
                    parkingLayer.addLayer(markerArray[id]);
                    popupArray[id] = L.popup();
                    popupArray[id]
                        .setLatLng(markerArray[id].getLatLng())
                        .setContent("<p>Loading</p>")

                    markerArray[id].bindPopup(popupArray[id]);
                    break;
                case 4:
                    var newLatLng = new L.LatLng(lat, long);
                    curCarLocation.setLatLng(newLatLng);
                    break;
                default:
                    L.marker([lat, long], {
                        icon: carLoc
                    }).addTo(mymap);
            }
        }

        function showDetail(id) {
            fetch("http://bkparking.ddns.net:3002/parkinglots/" + id)
                .then((response) => response.json())
                .then((data) => {
                    updateDetailPopup(id, data.name, Math.round(data.status*100)+"% free");
                });
        }

        function updateDetailPopup(id, name, status) {
            console.log(popupArray[id]);
            popupArray[id].setContent("<p>" + name + "<br/>" + status + "</p>");
        }

        fetch("http://bkparking.ddns.net:3002/parkinglots")
                .then((response) => response.json())
                .then((data) => {
                    // console.log(data[0]);
                    for (var i = 0; i < data.length; i++){
                        console.log(data[i]);
                        markLocation(data[i].coordinate.longitude, data[i].coordinate.latitude, 1, data[i]._id);
                    }
                });
    </script>
</body>

</html>