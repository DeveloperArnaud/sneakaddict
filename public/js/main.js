
$('#modal-inscription').click(function(e) {


    $('#exampleModalLong').fadeOut("slow");


});

$('#cartShop-btn').hover(function(e) {
    $('#modal-cart').show();

});

$('body').click(function(e) {
    $('#modal-cart').hide();

});

