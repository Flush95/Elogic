

function buildMap(result) {
    const position = { lat: result.latitude, lng: result.longitude };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 20,
        center: position,
    });
    const marker = new google.maps.Marker({
        position: position,
        map: map,
    });
    openMarkerDescription(result, marker);
}

function initMap() {
    require(['jquery'], function($) {
        $.ajax({
            url: '/rest/V2/shop/getShopById/1',
            dataType: 'json'
        }).error(function (result) {
            console.log('error ' + result);
        }).success(function (result) {
            buildMap(result);
        });
    });
}

function openMarkerDescription(result, marker) {
    let content = document.createElement('div');
    let urlKey = result.url_key;
    content.innerHTML =
        "<h1><strong>" + result.shop_name + "</strong></h1>" +
        "<p>City: " + result.shop_city + "</p>" +
        "<p>ZIP: " + result.shop_zip + "</p>" +
        "<p>Address: " + result.shop_address + "</p>" +
        "<p>State: " + result.shop_state + "</p>" +
        "<p>Description: " + result.description + "</p>" +
        "<p><a href='shops/" + urlKey + "'>See More</a></p>";
    let infowindow = new google.maps.InfoWindow({
        content: content
    });
    infowindow.open(map, marker);
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
}

require(['jquery'], function($) {

    $('#searchShop').on('keypress',function(e) {
        if(e.which === 13) {
            this.form.submit();
        }
    });

    $('a.stretched-link').on('click', function () {
        $(".card").removeClass("bg-primary text-white");
        let elem = document.getElementById($(this).attr('id'));
        elem.classList.add("bg-primary");
        elem.classList.add("text-white");

        let id = $(this).attr('id');
        $.ajax({
            url: '/rest/V2/shop/getShopById/' + id,
            dataType: 'json'
        }).error(function(result) {
            console.log('error');
        }).success(function(result) {
            buildMap(result);
        });
    });
});
