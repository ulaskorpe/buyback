function selectTown() {
    $.get("https://buyback.garantiliteknoloji.com/data/get-towns/" + $('#city_id').val() , function (data) {
        $('#town_id').html(data);

    });
    $('#district_id').html('<option>Mahalle Seçiniz</option>');
    $('#neighborhood_id').html('<option>Bölge Seçiniz</option>');
    $('#district_id').prop('disabled', true);
    $('#neighborhood_id').prop('disabled', true);
    $('#town_id').prop('disabled', false);
}

function selectDistrict( ) {

    $.get("https://buyback.garantiliteknoloji.com/data/get-districts/" + $('#town_id').val()  , function (data) {
        $('#district_id').html(data);

    });
    $('#neighborhood_id').html('<option>Bölge Seçiniz</option>');
    $('#neighborhood_id').prop('disabled', true);
    $('#district_id').prop('disabled', false);
}

function selectNeighborhood() {
    $.get("https://buyback.garantiliteknoloji.com/data/get-neighborhoods/" + $('#district_id').val()  , function (data) {
        $('#neighborhood_id').html(data);
    });
    $('#neighborhood_id').prop('disabled', false);
}

function getPostalCode() {
    $.get("https://buyback.garantiliteknoloji.com/data/get-postalcode/" + $('#neighborhood_id').val(), function (data) {
        $('#postalcode').html(data);
    });

}
