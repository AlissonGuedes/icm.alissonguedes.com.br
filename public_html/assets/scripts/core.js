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

    $('select').formSelect();
    Request.menu();
    Request.addEvent();
    Storage.checkSession();
    Form.init();

    resizeble();
    DataTable();
    buttonActions();
    Materializecss();
    editor();

    $('[data-tooltip]').tooltip();
    $('.materialboxed').materialbox();

    $(window).on('resize', function() {
        resizeble();
    });

    if ($('.sidebar').length > 0)
        new PerfectScrollbar(".sidebar");

    // Isotope
    // init Isotope
    var $grid = $('#produtos').isotope({
        itemSelector: '.produtos',
        layoutMode: 'fitRows',
    });

    $('.modal').modal();

    // bind filter button click
    $('#filters').on('click', '[data-filter]', function() {
        var filterValue = $(this).attr('data-filter');
        // use filterFn if matches value
        $grid.isotope({
            filter: filterValue
        });
    });

    // botão para exibir o forumlário da página de e-mail
    // $('[data-trigger-on]').each(function() {

    //     $(this).bind('click', function() {

    //         var trigger = $(this).data('trigger-on');

    //         animate($(trigger).show(), 'slideInRight faster', function(e) {
    //             e.removeClass('animated slideInLeft faster')
    //         });
    //         animate('.dataTables_wrapper', 'slideOutLeft faster', function(e) {
    //             e.hide().removeClass('animated slideInLeft faster');
    //         });

    //         $(this).parents('.panel').find('#form_mail_actions').css('display', 'flex')
    //             .find('button').attr('disabled', false)

    //         $(this).parents('.panel').find('.show-buttons, .panel-header .input-field').hide();

    //     });

    // });

    // botão voltar para esconder o formulário da página de e-mail
    $('.btn-back').each(function() {

        $(this).bind('click', function() {

            var trigger = $(this).data('trigger-off');
            var modal = $('.modal');

            $('.modal').modal({
                dismissible: false,
                inDuration: 150,
                outDuration: 200,
                outDuration: 200,
                startingTop: '33%',
                endingTop: '33%',
                onOpenEnd: function(el) {
                    modal.find('button').bind('click', function() {

                        var confirm = $(this).data('confirm');

                        console.log(confirm)

                        if (confirm) {

                            $(this).parents('.panel').find('#form_mail_actions').css('display', 'none')
                                .find('button').attr('disabled', true);

                            animate($('.dataTables_wrapper').show(), 'slideInLeft faster', function(e) {
                                e.removeClass('animated slideInLeft faster');
                            });

                            animate($(trigger), 'slideOutRight faster', function(e) {
                                e.hide().removeClass('animated slideOutRight faster');
                            });

                            Form.reset();

                            $(this).parents('.panel').find('.show-buttons, .panel-header .input-field').show();
                            modal.modal('close');
                        }

                    });
                },
                onCloseStart: function(el) {}
            });

            modal.modal('open');

        });

    });

    if (0 < $("#sidebar-list").length) new PerfectScrollbar("#sidebar-list", { theme: "dark" });
    if (0 < $(".scroller").length) new PerfectScrollbar(".scroller", { theme: "dark" });

    $("#contact-sidenav").sidenav({
        edge: "left",
        onOpenStart: function() {
            $("#sidebar-list").addClass("sidebar-show")
        },
        onCloseEnd: function() {
            $("#sidebar-list").removeClass("sidebar-show")
        }
    });

    // $(".sidenav-trigger").on("click",function(){$(window).width()<960&&($(".sidenav").sidenav("close"),$(".app-sidebar").sidenav("close"))}),$(window).on("resize",function(){resizetable(),899<$(window).width()&&$("#contact-sidenav").removeClass("sidenav"),$(window).width()<900&&$("#contact-sidenav").addClass("sidenav")}),resizetable(),$(window).width()<900&&($(".sidebar-left.sidebar-fixed").removeClass("animate fadeUp animation-fast"),$(".sidebar-left.sidebar-fixed .sidebar").removeClass("animate fadeUp"));
    $(function() {
        $("#bt_menu, #bt_interesse, #bt_x").click(function(e) {
            el = $(this).data('element');
            $(el).toggle();
        });
    });
}