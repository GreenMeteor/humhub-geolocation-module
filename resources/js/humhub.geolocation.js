humhub.module('geolocation', function(module, require, $) {


    console.log('Geolocation init.');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getPosition);
    } else {
        document.getElementById("demo").innerHTML = "Geolocation is not supported by this browser.";
    }

    function getPosition(position) {

        var url = "index.php?r=geolocation/index/updatelocation&lat=" + position.coords.latitude + "&long=" + position.coords.longitude;
        $.ajax({
            url: url,
            type: 'post',

        })
            .done(function(response) {
                if (response == 1) {
                    console.log("Updated Location in DB.");
                    document.getElementById('searchform-latitude').setAttribute('value',position.coords.latitude );
                    document.getElementById('searchform-longitude').setAttribute('value',position.coords.longitude );


                    var formData = $('#group-search-form').serialize();

                    $.ajax({
                        url: "index.php?r=geolocation/index/members",
                        type: "post",
                        data: formData,
                    }).done(function() {

                        console.log("Finished ajax call to members.");

                    }).fail(function() {
                            console.log("error");
                        });
                    }


            })
            .fail(function() {
                console.log("error");
            });
    }
});