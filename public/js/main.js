
$('#modal-inscription').click(function(e) {


    $('#exampleModalLong').fadeOut("slow");


});

    var loaded = false;

    $('#cartShop-btn').hover(function(e) {

        $('#modal-cart').show();
        if(loaded) return;
        $.ajax({
            type: 'GET',
            url: '/cart_modal',
            dataType: 'json',
            success: function (res) {

                // get the ajax response data

                console.log(res);
                // update modal content here
                // you may want to format data or
                // update other modal elements here too

                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    $('.modal-body').append('<div class="media"> <img src= '+ obj.url +' class="mr-3" style =" height: 64px; width : 64px;"alt="..."> <div class="media-body"> <h5 class="mt-0"> '+ obj.titre + '</h5></div></div> <hr>');
                    break;
                }

                loaded = true;

            },
            error: function (request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
    });


$('body').click(function(e) {
    $('#modal-cart').hide();

});

async function  modal_cart() {

}