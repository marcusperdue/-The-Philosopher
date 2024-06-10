<?php
session_start();  

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;  
}
?>

<?php include './includes/header.php'; ?>

<main>
    <div class="content-wrapper"> 

        <!-- Find a Store Section -->
        <section class="find-store" style="margin-bottom: 50px; color: #000000; width: 100%; max-width: 1500px; background-color: #f9f9f9d2; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; padding: 50px; display: flex; flex-direction: column;">
            <div class="find-store-container">
                <h2>Find a Store</h2>
                <div id="map" class="map-container"></div>
            </div>
        </section>
    </div>
</main>



<!-- This is google maps api -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBD4iHQlZ2ktdbYfjdPs-iJFr9FAik1kK8"></script>
<script>
    function initMap() {
        var mapOptions = {
            zoom: 12,
            center: { lat: 40.712776, lng: -74.005974 }
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var stores = [
            {name: 'Downtown Store', address: '123 Main St, Anytown, USA', lat: 40.712776, lng: -74.005974},
            {name: 'Uptown Store', address: '456 Elm St, Anytown, USA', lat: 40.781324, lng: -73.973988},
            {name: 'Suburban Store', address: '789 Oak St, Anytown, USA', lat: 40.735657, lng: -74.172367}
        ];

        stores.forEach(function(store) {
            var marker = new google.maps.Marker({
                position: { lat: store.lat, lng: store.lng },
                map: map,
                title: store.name
            });

            var infoWindow = new google.maps.InfoWindow({
                content: '<h2>' + store.name + '</h2><p>' + store.address + '</p>'
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        });
    }

    window.onload = initMap;
</script>

<style>
    .map-container {
        width: 100%;
        height: 600px;  
        margin-top: 20px;
    }
</style>
<script src="./js/script.js"></script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
