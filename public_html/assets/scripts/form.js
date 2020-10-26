'use strict';

var element;
var duration = 0;

var Form = {

    init: () => {

        $('body').find('form').each(function() {

            element = $(this);

            $(this).on('submit', function(e) {

                e.preventDefault();

                Form.send();

            });

            $(this).find('#btn-back').on('click', function() {

                Form.clearErrors();
                var url = $(this).data('link');
                Http.goTo(url);

            });

            element.find('[autofocus]').focus();

            // Acender botões se valores dos checkboxes forem verdadeiros
            // Ex.: botões de bloqueio
            Form.toggleButtons();
        });

        Form.image_upload();

        // Exibir formulários nas páginas e omitir as dataTables
        $('.add-button').on('click', function() {
            var url = $(this).data('link');
            Http.goTo(url);
        });

    },

    reset: () => {

        element.find('[type=reset]').click();

    },

    send: () => {

        var success = Boolean;
        var label = element.find(':button:submit').find('i').html();
        var method = element.attr('method');
        var action = element.attr('action');

        element.ajaxSubmit({

            method: method, // method
            action: action, // url

            beforeSend: (e) => {

                Form.__button__(label, true);

            },

            success: (response) => {

                try {

                    var $response = typeof response === 'string' ? JSON.parse(response) : response;

                    if (element.attr('id') === 'frm-login') {

                        Form.login($response, label);

                    } else {

                        if ($response.status === 'success') {

                            if ($response.message) {
                                Form.showMessage($response.message, null, 'Ok');
                            }

                            if (element.find('[name=_method]').val() === 'post') {
                                element.resetForm();
                                element.find('[autofocus]').focus();
                            }

                            Form.refreshPage($response.type);

                        } else {

                            Form.showErrors($response.fields);

                            success = false;

                        }

                    }

                    Form.__button__(label, false);

                } catch (error) {

                    if (Storage.checkSession()) {

                        if (window.location.href.split('/').pop() == 'login') {
                            Http.goTo('dashboard');
                        }

                    } else {
                        M.toast({ html: error });
                    }

                    Form.__button__(label, false);

                }


            },

            error: (error) => {
                Form.__button__(label, false);
            }

        });

    },

    login: (response, label) => {

        if (response.status === 200) {

            Form.__avancar__();

            element.find(':button:submit').removeClass('next');

            setTimeout(function() {
                document.getElementById('senha').focus();
                Form.__button__(label, false);
                Form.__voltar__();
            }, 270);

        } else {

            if (response.status === 'success') {

                if (response.message) {
                    Form.showMessage(response.message, null, 'Ok');
                    Form.reset();
                    duration = 500;
                }

                // CRIAR FUNÇÃO PARA EXECUTAR ATUALIZAÇÃO NA PÁGINA DE ACORDO COM A REQUISIÇÃO 
                setTimeout(function() {

                    if (response.type === 'reload') {

                        location.href = response.url;

                    } else {

                        // Criar um banco de dados local para armazenar as credenciais de acesso
                        Storage.set('token', response.data.token);
                        Http.goTo(response.url);

                    }

                }, duration);

            } else {

                Form.showErrors(response.fields)
                Form.__button__(label, false);

            }

        }

    },

    refreshPage: (type) => {

        if (type === 'back') {

            Form.reset();

        }

        if (type === 'refresh') {
            setTimeout(function() {
                location.reload();
            }, 2000);
        }

    },

    fill: (obj) => {

        Object.keys(obj).map((key) => {

            if ($('body').find('[name="' + key + '"]').length) {
                var input = document.querySelector('[name="' + key + '"]');
                Form.serialize(input, obj[key]);
            }

            if (typeof obj[key] === 'object' && obj[key]) {

                for (var rule in obj[key]) {

                    if ($('body').find('[name="' + key + '.' + rule + '"]').length) {
                        var input = document.querySelector('[name="' + key + '.' + rule + '"]');
                        Form.serialize(input, obj[key][rule]);
                    }

                }

            }

        });

        // Acender botões se valores dos checkboxes forem verdadeiros
        // Ex.: botões de bloqueio
        Form.toggleButtons();

    },

    show: (method, id) => {

        if (Storage.checkSession()) {

            element.parent('#form').show().find('.panel').addClass('loading');
            $('#list').hide();

            element.find('[name="_method"]').val(method);

            var form_tab = M.Tabs.getInstance($('.form-tabs'));
            form_tab.select('account');

            if (id) {

                var url = window.location.href + '/' + id;

                Http.get(url, 'json', (response) => {

                    Form.fill(response);
                    element.find('[autofocus]').focus();
                    element.find('.panel').removeClass('loading');

                });

            } else {
                element.find('[autofocus]').focus();
                element.parent('#form').find('.panel').removeClass('loading');
            }

        } else {

            Http.goTo('login');

        }

    },

    toggleButtons: () => {

        // Resetar valores dos campos de textos
        element.find('input,textarea').each(function() {
            if ($(this).val() != '')
                $(this).parents('.input-field').find('label').addClass('active');
        })

    },

    image_upload: () => {

        $('.redefinir_foto').click();

        // Exibir a imagem em uploads de imagens 
        $(element).find('.image_view').on('click', function(e) {

            img = $(this).parents('.media').find('img');

            $(this).next(':input:file').click();

        });

        var img;

        // Redefinir foto do perfil de usuário
        $(element).find('.redefinir_foto').on('click', function() {

            $(this).parents('.media').find('.original').show();
            $(this).parents('.media').find('.temp').parent().remove();
            $('.image_view').next(':input:file').val('');
            $(this).parents('form').find('.redefinir_foto').parent().hide();

        });

        // Alterar imagem ao selecionar uma no upload de arquivos
        $(element).find('.image_view').next(':input:file').on('change', function() {

            $(this).parents('.media').find('.original').hide();
            $(this).parents('.media').find('.temp').parent().remove();

            if ($(this).val()) {

                var src = window.URL.createObjectURL(document.querySelector('[name="imagem"]').files[0]);
                var img = $('<img/>', {
                    'src': src,
                    'class': 'materialboxed temp z-depth-4 circle',
                });

                $(this).parents('.media').find('.img-perfil').append(img);
                $(this).parents('form').find('.redefinir_foto').parent().show();
                $(img).materialbox();

            }

        });

    },

    serialize: (input, value) => {

        var nodeName = input.nodeName;
        var type = input.type;

        switch (nodeName) {

            case 'INPUT':
                switch (type) {
                    case 'text':
                    case 'hidden':
                    case 'password':
                    case 'email':
                    case 'url':

                        $(input).val(value);

                        break;

                    case 'checkbox':
                    case 'radio':

                        if ($('[name="' + input.name + '"]').val() === value || value === true) {
                            $(input).attr('checked', true);
                        } else {
                            $(input).attr('checked', false);
                        }

                        break;

                    case 'file':
                        break;

                }
                break;

            case 'TEXTAREA':

                $(input).val(value);

                break;

            case 'SELECT':
                switch (type) {
                    case 'select-one':

                        if ($('select[name="' + input.name + '"]').find('option[value="' + value + '"]').length) {
                            $('select[name="' + input.name + '"]')
                                .find('option[selected]').removeAttr('selected');
                            $('option[value="' + value + '"]').attr('selected', true);
                            $('select[name="' + input.name + '"]').formSelect()
                        }

                        break;
                    case 'select-multiple':
                        for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) { if (form.elements[i].options[j].selected) { q.push(form.elements[i].name + '=' + encodeURIComponent(form.elements[i].options[j].value)) } }
                        break
                }
                break;

        }

    },

    showErrors: (field) => {

        Form.clearErrors();
        M.Toast.dismissAll();

        Object.keys(field).forEach((item) => {

            var label = $('[name="' + item + '"]');
            var div = $('<div/>', { 'class': 'error' });
            var icon = $('<i/>', { class: 'material-icons sufix' }).html('error');

            $(label).parents('.input-field').addClass('error')
                .find('.error').remove();

            $(label).parents('.input-field').addClass('error')
                .append(div.append(icon, field[item]));

        })

        // $('input:not(:valid)')[0].focus();

    },

    clearErrors: () => {

        element.find('.input-field').removeClass('error').find('.error').remove();

    },

    showMessage: ($text, $status, $title = 'teste') => {

        Form.clearErrors();
        M.Toast.dismissAll();

        if (typeof $text !== 'object') {

            var classes = 'z-depth-2';

            M.toast({
                classes: classes + ' ' + (typeof $status !== null ? $status : ''),
                html: $text + '<button class="btn btn-floating btn-small transparent toast-action waves-effect waves-light"><i class="material-icons">close</i></button>',
                displayLength: 10000
            });

            $('.toast-action').on('click', function(e) {
                e.preventDefault();
                M.Toast.dismissAll();
            });

        }

    },

    __button__: (label, block) => {

        var spinner = '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>';

        if (block) {
            element.find(':button:submit').attr('disabled', true)
                .find('i').removeClass('material-icons').html(spinner);
        } else {
            element.find(':button:submit').attr('disabled', false)
                .find('i').addClass('material-icons').html(label);
        }

    },

    __avancar__: () => {

        Form.clearErrors();

        $('#input-login').removeClass('animated fast fadeOutLeft fadeInLeft fadeInRight fadeOutRight').addClass('animated fadeOutLeft')
            .find('[name="login"]').attr('disabled', true);
        $('#input-pass').removeClass('animated fast fadeOutLeft fadeInLeft fadeInRight fadeOutRight').addClass('animated fadeInRight').show()
            .find('[name="senha"]').attr('disabled', false);
        $('#relembrar_login').hide();
        $('#btn-back,#relembrar_senha').css('display', 'flex').attr('disabled', false);

    },

    __voltar__: () => {

        Form.clearErrors();

        $('#btn-back').on('click', function() {
            $('#input-pass').removeClass('animated fast fadeOutLeft fadeInLeft fadeInRight fadeOutRight').addClass('animated fadeOutRight')
                .find('[name="senha"]').val('').attr('disabled', true);
            $('#btn-back,#relembrar_senha').css('display', 'flex').attr('disabled', true).hide();
            $('#input-login').removeClass('animated fast fadeOutLeft fadeInLeft fadeInRight fadeOutRight').addClass('animated fadeInLeft').show()
                .find('[name="login"]').attr('disabled', false);
            $('#relembrar_login').show();
            document.getElementById('login').focus();
        })

    },

}