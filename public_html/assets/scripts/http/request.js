'use strict';

var links = '[href], [data-href], .link';

var Request = {

    addEvent: () => {

        $('body').find(links).on('click', function(e) {

            var link = $(this).data('href') || $(this).attr('href');
            var target = $(this).attr('target') || false;

            e.preventDefault();

            if (Request.isLink(link) && !target) {

                if (window.location.origin + link !== window.location.href) {
                    Http.goTo(link);
                }

            } else {

                if (target && target != '_self') {
                    window.open(link, target);
                } else
                if (target && target == '_self') {
                    window.location.href = link;
                }

            }

        });

    },

    refreshUrl: (url) => {

        if (BASE_URL + url !== window.location.href)
            window.history.pushState('', '', url);

    },

    isLink: (href) => {

        var URL = typeof href.split(BASE_URL)[1] === 'undefined' ? href : href.split(BASE_URL)[1];
        var isAnchor = /^[jJ]ava[sS]cript(\:[a-z]+)?|#([a-z]?)+$/i.test(URL);

        return href !== '' && !isAnchor && typeof URL !== 'undefined';

    },

    menu: () => {

        var URL = typeof window.location.href.split(BASE_URL)[1] !== 'undefined' ? window.location.href.split(BASE_URL)[1] : 'dashboard';

        $('#slide-out').removeClass('active').find('.active').removeClass('active');

        if ($('aside').hasClass('nav-expanded'))
            $('#slide-out li').find('a[href="' + BASE_URL + URL.split('/')[0] + '"').addClass('active')
            .parents().addClass('active').show();

    }

}