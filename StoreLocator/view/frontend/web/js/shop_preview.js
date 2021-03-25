
require(['jquery', 'underscore'], function($) {
    let splittedHref = window.location.href.split('/');
    let shopName = splittedHref[splittedHref.length - 1];
    $.ajax({
        url: '/rest/V2/shop/getShopByUrlKey/' + shopName,
        method: 'GET'
    }).error(function (response) {
        console.log(response);
    }).success(function(response) {
        let compiledShop = _.template($('#shopInfoScript').html()) ({
            data: response
        });
        $('#shopInfo').html(compiledShop);
        const position = {
            lat: response.latitude,
            lng: response.longitude
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 20,
            center: position
        });
        const marker = new google.maps.Marker({
            position: position,
            map: map,
        });
        $( ".shopDescription" ).append(response.description);
    })
});
