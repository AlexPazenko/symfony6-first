
console.log('7777');
console.log(jQuery().jquery);




$(document).ready(function() {

    $('.userLikesVideo').show();
    $('.userDoesNotLikeVideo').show();
    $('.noActionYet').show();

    console.log('999');




    $('.toogle-likes').on('click', function(e) {
        e.preventDefault();
        console.log('111');

        var $link = $(e.currentTarget);
        var href = $link.attr('href');
        console.log(href);
        $.ajax({
            method: 'POST',
            url: href
        }).done(function(data) {
            switch (data.action)
            {
                case 'liked':
                    var number_of_likes_str =  $('.number-of-likes-' + data.id);
                    var number_of_likes = parseInt( number_of_likes_str.html().replace(/\D/g,'') ) + 1;
                    number_of_likes_str.html('(' + number_of_likes + ')');
                    $('.likes-video-id-'+data.id).show();
                    $('.dislikes-video-id-'+data.id).hide();
                    $('.video-id-'+data.id).hide();

                    break;
                case 'disliked':
                    var number_of_dislikes_str =  $('.number-of-dislikes-' + data.id);
                    var number_of_dislikes = parseInt( number_of_dislikes_str.html().replace(/\D/g,'') ) + 1;
                    number_of_dislikes_str.html('(' + number_of_dislikes + ')');
                    $('.dislikes-video-id-'+data.id).show();
                    $('.likes-video-id-'+data.id).hide();
                    $('.video-id-'+data.id).hide();

                    break;
                case 'undo liked':
                    var number_of_likes_str =  $('.number-of-likes-' + data.id);

                    var number_of_likes = parseInt( number_of_likes_str.html().replace(/\D/g,'') ) - 1;
                    number_of_likes_str.html('(' + number_of_likes + ')');
                    $('.video-id-'+data.id).show();
                    $('.dislikes-video-id-'+data.id).hide();
                    $('.likes-video-id-'+data.id).hide();

                    break;

                case 'undo disliked':
                    var number_of_dislikes_str =  $('.number-of-dislikes-' + data.id);
                    var number_of_dislikes = parseInt( number_of_dislikes_str.html().replace(/\D/g,'') ) - 1;
                    number_of_dislikes_str.html('(' + number_of_dislikes + ')');
                    $('.video-id-'+data.id).show();
                    $('.dislikes-video-id-'+data.id).hide();
                    $('.likes-video-id-'+data.id).hide();
                    break;

            }

        })
    });

});




