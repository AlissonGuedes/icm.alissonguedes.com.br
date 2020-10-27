'use strict';

var xhr;

var Http = {

    goTo: (url, params) => {

        Http.get(url);

        Request.refreshUrl(url);

        // Redirecionar url com parâmetro.
        if (typeof params !== 'undefined') {
            Form.showMessage(params.message, params.status);
        }

    },

    get: (url, params, callback) => {

        if (params && typeof params !== 'function') {

            var array = [];

            $.ajax({
                'async': false,
                'method': (params.method || 'get'),
                'url': url,
                'dataType': (params.datatype || 'json'),
                'data': (params.data || null),
                'success': (response) => {

                    if (typeof callback === 'function')
                        return callback(response);
                    else
                        return callback = response;

                }

            });

            return callback;

        } else {

            Http.open('GET', url);

        }

    },

    post: (url, params, callback) => {

        if (params && typeof params !== 'function') {

            $.ajax({
                'async': false,
                'method': (params.method || 'post'),
                'url': url,
                'dataType': (params.datatype || 'json'),
                'data': (params.data || null),
                'success': (response) => {

                    if (typeof callback === 'function')
                        return callback(response);
                    else
                        return callback = response;
                }

            });

            return callback;

        } else {

            Http.open('POST', url);
        }

    },

    open: (type, url) => {

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = ActiveXObject('Microsoft.XMLHTTP');
        }

        xhr.open(type, url);
        Http.send(url);

        // movimenta a barra de rolagem para o topo da pÃ¡gina
        $('html,body').animate({
            scrollTop: 0
        }, {
            duration: 200
        });

    },

    send: (url) => {

        xhr.onprogress = function(e) {

        }

        xhr.onreadystatechange = function(e) {

        }

        xhr.onloadstart = function(e) {

        }

        xhr.onloadend = function(e) {

            if (xhr.readyState === 4) {
                Http.renderer(url);
            }

            // Recarrega scripts e funções de JS 
            core();

        }

        xhr.onload = function(e) {

        }

        xhr.onerror = function(e) {
            // console.error(e);
        }

        xhr.send(null);

    },

    renderer: (url) => {

        var parser = new DOMParser();
        var content = parser.parseFromString(xhr.response, 'text/html');

        if (xhr.status === 200) {

            var title = content.querySelector('title');

            if (title) {
                document.title = title.innerHTML;
            }

            $('body').html($(content).find('#body').html());

        } else {
            console.error(xhr.response);
        }

    },

}