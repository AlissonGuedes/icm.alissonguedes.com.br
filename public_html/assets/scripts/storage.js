'use strict';

var db = localStorage;

var Storage = {

    set: (key, val) => {
        return db.setItem(key, val);
    },

    get: (key) => {
        return db.getItem(key);
    },

    del: (key) => {
        return db.removeItem(key);
    },

    isset: (key) => {

        if (typeof key === 'undefined')
            return db = Boolean;

        if (Storage.get(key) === null)
            return false;

        return true;

    },

    removeSession: (key) => {
        Storage.del(key);
    },

    checkSession: () => {

        var $return;

        if (Storage.isset('token')) {

            $return = true

            Http.get(BASE_URL + 'api/token', 'json', (response) => {

                if (response.token === Storage.get('token')) {

                    if (window.location.href.split('/').pop() == 'login') {
                        Request.refreshUrl('dashboard');
                    }

                } else {

                    Storage.removeSession('token');
                    Http.goTo(BASE_URL + 'login');

                }

            });

        } else {
            $return = false;
        }

        return $return;

    }

}