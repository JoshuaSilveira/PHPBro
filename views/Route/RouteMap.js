function initMap() {
    var directionsService = new google.maps.DirectionsService();
    var distanceService = new google.maps.DistanceMatrixService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    var map = new google.maps.Map(document.getElementById('routeMap'), {
        zoom: 16,
        center: {lat: 43.73, lng: -79.61}
    });
    directionsRenderer.setMap(map);

    var ShowRoute = function() {
        CalculateAndDisplayRoute(directionsService, distanceService, directionsRenderer);
    }
    document.getElementById('showRoute').addEventListener('click', ShowRoute);

    //Only for the List page when they click on the link (route name) to show the map of their saved route
    if (document.getElementById("startAddress").value != ""
    && document.getElementById("endAddress").value != "") {
        document.getElementById('routeMap').scrollIntoView();
        CalculateAndDisplayRoute(directionsService, distanceService, directionsRenderer);
    }

}

function CalculateAndDisplayRoute(directionsService, distanceService, directionsRenderer) {
    directionsService.route(
        {
            origin: {query: document.getElementById('startAddress').value},
            destination: {query: document.getElementById('endAddress').value},
            travelMode: 'WALKING'
        },
        function(response, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                //Get the distance and duration for the route
                distanceService.getDistanceMatrix(
                    {
                        origins: [{query: document.getElementById('startAddress').value}],
                        destinations: [{query: document.getElementById('endAddress').value}],
                        travelMode: 'WALKING'
                    },
                    function(response, status) {
                        if (status == 'OK') {
                            //Get the first row (only one origin and destination pair)
                            var results = response.rows[0].elements[0];
                            document.getElementById('totalDistance').innerText = "Distance: " + results.distance.text;
                            document.getElementById('totalDuration').innerText = "Duration: " + results.duration.text;
                        }
                        else {
                            window.alert('Distance request failed due to ' + status);
                        }
                    });
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
}
