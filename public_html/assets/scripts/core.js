window.onload = () => {

    window.addEventListener('popstate', function() {
        var href = this.location.href;
        if (Request.isLink(href)) {
            Http.get(href);
        }
    }, true);

    core();

}

function core() {

    $(document).ready(function() {

        Request.menu();
        Request.addEvent();
        Storage.checkSession();
        Form.init();

        resizeble();
        DataTable();
        buttonActions();
        Materializecss();
        quillEditor();

        $('[data-tooltip]').tooltip();
        $('.materialboxed').materialbox();

        if (0 < $(".scroller").length) new PerfectScrollbar(".scroller", { theme: "dark" });

        $(window).on('resize', function() {
            resizeble();
        });

        $('.tags').each(function() {

            var self = $(this);
            var data = [];
            var tag = [];
            var tags = typeof self.data('value') ? self.data('value').split(',') : null;

            for (var i in tags) {

                var elem = '<input type="hidden" value="' + tags[i] + '" name="' + self.data('name') + '">';

                data.push({
                    tag: tags[i],
                    image: null
                });

                // $(this).append(elem);

            }

            $(this).chips({
                'limit': typeof $(this).data('limit') ? $(this).data('limit') : Infinity,
                'placeholder': typeof $(this).attr('placeholder') ? $(this).attr('placeholder') : 'Add Tag',
                'secondaryPlaceholder': '+Tag',
                'data': data,
                'onChipAdd': (value, element) => {
                    // console.log('teste');
                    // $(element).append($('<input/>', {
                    //     'type': 'hidden',
                    //     'value': $(element).html().split('<i')[0],
                    //     'name': self.find('input').attr('name')
                    // }));
                }

            });

        });

        $('.bt_selecionar').on('click', function() {
            $('select[name="horario"]').val($(this).attr('id')).trigger('change').formSelect();
            var horarios = $('#horarios');

            horarios.modal('close');
        });

    });

}