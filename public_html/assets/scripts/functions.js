function resizeble() {

    var index = $('#index');
    var height = $(window).outerHeight();

    index.css({
        'height': height + 'px',
    });

}

function resizeBody() {

    // var alturaBody = $('body').height();

    // var alturaTotal = alturaBody - 420;

    // setTimeout(() => {
    //     $('.dataTables_wrapper.no-footer .dataTables_scrollBody').css({
    //         'height': alturaTotal + 'px',
    //         'min-height': alturaTotal + 'px',
    //         'max-height': alturaTotal + 'px',
    //     });
    // }, 0);

}

function animate(component, animation, callback) {

    var object;
    var animations = ["animated", "bounce", "flash", "pulse", "rubberBand", "shake", "swing", "tada", "wobble", "jello", "heartBeat", "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp", "bounceOut", "bounceOutDown", "bounceOutLeft", "bounceOutRight", "bounceOutUp", "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig", "fadeOut", "fadeOutDown", "fadeOutDownBig", "fadeOutLeft", "fadeOutLeftBig", "fadeOutRight", "fadeOutRightBig", "fadeOutUp", "fadeOutUpBig", "flip", "flipInX", "flipInY", "flipOutX", "flipOutY", "lightSpeedIn", "lightSpeedOut", "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight", "rotateOut", "rotateOutDownLeft", "rotateOutDownRight", "rotateOutUpLeft", "rotateOutUpRight", "slideInUp", "slideInDown", "slideInLeft", "slideInRight", "slideOutUp", "slideOutDown", "slideOutLeft", "slideOutRight↵	", "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp", "zoomOut", "zoomOutDown", "zoomOutLeft", "zoomOutRight", "zoomOutUp", "hinge", "jackInTheBox", "rollIn", "rollOut"]

    $(component).removeClass(animations).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
        $(this).removeClass(animations);

        if (typeof callback === 'function')
            callback($(this));
    });

};

function editor() {

    // // Editor sem barra de ferramentas
    // $('.editor--hide_toolbar').each(function(e){

    //     tinymce.init({
    //         selector: '.' + $(this).attr('class').replace(/\s/g, '.'),
    //         height: typeof $(this).data('height') !== 'undefined' ? $(this).data('height') : 250,
    //         plugins: [],
    //         toolbar: false,
    //         menubar: false,
    //         inline: false,
    //         placeholder: typeof $(this).attr('placeholder') !== 'undefined' ? $(this).attr('placeholder') : null,
    //         content_css: typeof $(this).data('style') !== 'undefined' ? $(this).data('style') : BASE_PATH + 'styles/style.css',
    //     });

    // });

    // // Editor básico
    $('.basic--editor').each(function() {

        var editor = new Quill(this, {
            placeholder: typeof $(this).attr('placeholder') !== 'undefined' ? $(this).attr('placeholder') : null,
            theme: 'snow'
        });

    });


    // // Editor completo
    // $('.full--editor').each(function(){
    //     tinymce.init({
    //         selector: '.' + $(this).attr('class').replace(/\s/g, '.'),
    //         height: typeof $(this).data('height') !== 'undefined' ? $(this).data('height') : 250,
    //         menubar: true,
    //         plugins: [
    //             'quickbars advlist autolink link image lists charmap print preview hr anchor pagebreak',
    //             'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
    //             'table emoticons template paste help'
    //         ],
    //         toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
    //             'bullist numlist outdent indent | link | print preview media fullpage | ' +
    //             'forecolor backcolor emoticons | help',
    //         menu: {
    //             favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    //         },
    //         menubar: 'favs file edit view insert format tools table help',
    //         content_css: typeof $(this).data('style') !== 'undefined' ? $(this).data('style') : BASE_PATH + 'styles/style.css',
    //         placeholder: typeof $(this).attr('placeholder') !== 'undefined' ? $(this).attr('placeholder') : null
    //     });
    // });

}

$(window).on('resize', function(e) {

    resizeBody();

});