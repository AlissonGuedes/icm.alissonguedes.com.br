function resizeble() {

    var index = $('#index');
    var height = $(window).outerHeight();

    index.css({
        'height': height + 'px',
    });

}

function resizeBody() {

    var alturaBody = $('body').height();

    var alturaTotal = alturaBody - 420;

    setTimeout(() => {
        $('.dataTables_wrapper.no-footer .dataTables_scrollBody').css({
            'height': alturaTotal + 'px',
            'min-height': alturaTotal + 'px',
            'max-height': alturaTotal + 'px',
        });
    }, 0);

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

function quillEditor() {

    // Editor básico com apenas formatação de fontes [Bold, Underline, Italic e Strike]
    if ($('.editor--basic').length > 0) {
        $('.editor--basic').each(function() {
            new Quill(this, {
                bounds: this,
                modules: {
                    formula: !0,
                    syntax: !0,
                    toolbar: [
                        [{ font: [] }, { size: [] }],
                        [{ align: [] }],
                        ["bold", "italic", "underline", "strike"],
                        ["link", "image", "video"],
                    ]
                },
                placeholder: $(this).attr('data-placeholder'),
                theme: "snow"
            });
        });
    }

    // Editor completo
    if ($('.editor--full').length > 0) {
        $('.editor--full').each(function() {
            new Quill(this, {
                bounds: this,
                modules: {
                    formula: !0,
                    syntax: !0,
                    toolbar: [
                        [{ font: [] }, { size: [] }],
                        ["bold", "italic", "underline", "strike"],
                        [{ color: [] }, { background: [] }],
                        [{ script: "super" }, { script: "sub" }],
                        [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                        [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
                        ["direction", { align: [] }],
                        ["link", "image", "video", "formula"],
                        ["clean"]
                    ]
                },
                placeholder: $(this).attr('data-placeholder'),
                theme: "snow"
            });
        });
    }

}

$(window).on('resize', function(e) {

    resizeBody();

})