// -----------------------------

//   js index
/* =================== */
/*  
    

    

*/
// -----------------------------


(function($) {
    "use strict";

    /*---------------------
    preloader
    --------------------- */

    $(window).on('load', function() {
        $('#preloader').fadeOut('slow', function() { $(this).remove(); });
    });

    /*------------------------------
         counter
    ------------------------------ */

    $('#fetch').on('click',function () {
        $('#status').text('LOADING PLEASE WAIT!');
        $('#fetch').hide();
        $.when(
        $.ajax({
            url: '/api/request.php',
            contentType: "application/json",
            dataType: 'json',
            data: { link : "https://en.wikipedia.org/wiki/Mobile_operating_system​"},
            success: function(result){
                if (result != "success") {
                    $('#status').text('DATA SET COMPLETED');
                } else {
                    $('#status').text('WEBSITE CRAWL COMPLETE');
                }
            }
        })
    ).then( function(){
            $.ajax({
            url: '/api/words_aggregator.php',
            contentType: "application/json",
            dataType: 'json',
            data: { type : "every10"},
            success: function(data) {
                $( '#tenth_word').append( '<p>'+data[0].word+'</p>');
                $.each( data, function( key, value ) {
                    $( '#every10').append( '<p>'+value.word+'</p>');
                });
            }
        })
        $.ajax({
            url: '/api/words_aggregator.php',
            contentType: "application/json",
            dataType: 'json',
            data: { type : "all"},
            success: function(data) {
                $( '#total').text( data.total+' total words');
                $.each( data.words, function( key, value ) {
                    $( '#wordlist').append( '<p>'+value.word+': '+value.num+' appearances</p>');
                });
            }
        })
    });


    })
}(jQuery));