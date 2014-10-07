/**
 *
 *  Remote modal for SSC
 *
 *  Uses bootbox and bootstrap modal
 *
 * @author Stephen Hoogendijk - Leaseweb
 *
 */

var locale = 'en',
    modalTrigger = 'a[rel="remote-modal"], [data-remote-modal]';

if (typeof __active_locale != 'undefined') {

    locale = __active_locale.toLowerCase();
}

bootbox.setDefaults({
    /**
     * @optional String
     * @default: en
     * which locale settings to use to translate the three
     * standard button labels: OK, CONFIRM, CANCEL
     */
    locale: locale,

    /**
     * @optional Boolean
     * @default: true
     * whether the dialog should be shown immediately
     */
    show: true,

    /**
     * @optional Boolean
     * @default: true
     * whether the dialog should be have a backdrop or not
     */
    backdrop: true,

    /**
     * @optional Boolean
     * @default: true
     * show a close button
     */
    closeButton: true,

    /**
     * @optional Boolean
     * @default: true
     * animate the dialog in and out (not supported in < IE 10)
     */
    animate: true,

    /**
     * @optional String
     * @default: null
     * an additional class to apply to the dialog wrapper
     */
    className: "ssc-modal"

});

var remoteModal = function(el, trigger, userOptions) {

    var obj = {

        options: {},
        el : null,

        init: function(el, options) {

            var me = this,
                defaultOptions = {
                    message: '',
                    callback: '',
                    title: '',
                    type: null
                };

            me.options = $.extend({}, defaultOptions, options);
            me.el = el;

        },

        /**
         * Load from remote
         * @param url
         * @param data Optional serialized data to
         */
        load: function(url, data) {
            var me = this,
                options = me.options,
                modalType = options.type,
                forceType = options.forceType,
                requestType = (data ? 'POST' : 'GET'),
                postData = data || null;

            ajaxLoad(true);

            $.ajax({
                type: requestType,
                url: url,
                data: postData,
                complete: function(data, status) {

                    if (typeof data.responseText != 'undefined') {
                        options.message = data.responseText;
                    }

                    // if the form contains error, update the modal, else go to the page
                    if ((($(options.message).find('.has-error').length > 0
                        || $(options.message).find('.error').length > 0)
                        || requestType == 'GET') && status == 'success' ) {

                        // force the type to alert if an error is found in the result
                        if($(options.message).find('.error').length > 0){
                            forceType = 'alert';
                        }
                        me.show(forceType);
                    } else if (status != 'success') {
                        me.show('alert');
                    }

                    // force to continue to display dialogs for a certain number of steps
                    // the step number shall not increase when the form has erros (.has-error class)
                    // type will be automatically equal to 'confirm' if a form is detected unless
                    // forceType is specified
                    else if (options.forceanother && options.forceanotherCount < options.forceanother) {
                        me.options.forceanotherCount += 1;
                        me.show(forceType);
                    }
                    else {
                        window.location.reload();
                    }

                }
            }).always(function () {
              ajaxLoad(false);
            });
        },

        /**
         *
         * @param url
         * @param data
         */
        update: function(url, data) {

            var me = this,
                updateUrl = url || me.options.updateUrl;

            if ($('.modal-body').find('.has-error').length > 0) {
                throw 'invalid form';
            }
            me.el.hide();
            this.load(updateUrl, data);
        },

        /**
         * Show the modal
         */
        show: function(forceType) {

            var me = this,
                options = me.options,
                type = forceType || options.type,
                titleObj = $(options.message).find('h4'),
                form = $(options.message).find('form'),
                beforeSubmitCallback = options.beforeSubmitCallback,
                onShowCallback = options.onShowCallback,
                title = '';

            // if we can't find the title as a child, try to find it as a sibling
            if (!titleObj.length > 0) {
                titleObj = $(options.message).siblings('h4');
            }

            if (titleObj.length > 0) {
                title = titleObj.html();
                options.title = title;
            }

            if ($(form).length) {
                me.options.callback = function(result) {

                    if (result) {

                        if (beforeSubmitCallback) {
                            if (!beforeSubmitCallback.call(this)) {
                                return false;
                            }
                        }

                        me.el.find('form').submit();
                    }
                };
                if (!forceType) {
                    type = 'confirm';
                }
            }

            // when there is a alternative show function, block the default show action
            if (typeof window[onShowCallback] != 'undefined') {
                onShowCallback = window[onShowCallback];
                options.show = false;
            }

            if (trigger) {

                if (type == 'confirm'){
                    me.el = bootbox.confirm(options);
                } else if (type == 'prompt'){
                    me.el = bootbox.prompt(options);
                } else {
                    me.el = bootbox.alert(options);
                }

                if (onShowCallback) {
                    me.el.on('shown.bs.modal', onShowCallback);
                    me.el.modal('show');
                }

                if (me.options.updateUrl) {
                    me.el.find('form').on('submit', function(e) {
                        e.preventDefault();
                        me.update(null, $(this).serialize());
                    });
                }
            }
        },

        hide: function() {
            el.hide();
        }
    };

    // call the constructor
    obj.init(el, userOptions);

    return obj;
};

$(function(){
    bindclick();
});

var bindclick = function(element) {
    var modalTrigger;
    if (element != undefined){
        modalTrigger = element;
    }
    else {
        modalTrigger = window.modalTrigger;
    }
    $(modalTrigger).click(function(e) {

        e.preventDefault();

        var me = $(this),
            location = me.attr('href') || null,
            content = '',
            callback = me.attr('data-callback') || null,
            preExecute = me.attr('data-preexecute') || null,
            beforeSubmitCallback = me.attr('data-before-submit-callback') || null,
            onShowCallback = me.attr('data-onshow-callback') || null,
            forceanother = me.attr('data-force-another') || null,
            forceanotherCount = 0,
            updateUrl = me.attr('data-update-url') || null,
            hold = me.attr('data-hold') || false,
            className = me.attr('data-classname') || null,
            type = 'alert',
            forceType = me.attr('data-modal-type'),
            options,
            modalObj;

        if(preExecute){
            if (typeof window[preExecute] != 'undefined') {
                preExecute = window[preExecute];
                preExecute.call(this, me);
            }
        }

        if (callback || beforeSubmitCallback) {

            if (typeof window[callback] != 'undefined') {
                callback = window[callback];
                type = 'confirm';
            }

            if (typeof window[beforeSubmitCallback] != 'undefined') {
                beforeSubmitCallback = window[beforeSubmitCallback];
            }
        }

        if (me.attr('data-container')) {
            content = $(me.attr('data-container')).html();
        }

        options = {
            message: content,
            callback: callback,
            beforeSubmitCallback: beforeSubmitCallback,
            onShowCallback: onShowCallback,
            updateUrl: updateUrl,
            forceanother: forceanother,
            forceanotherCount: forceanotherCount,
            forceType: forceType,
            type: type,
            className: className,
            title: ''
        };

        modalObj = remoteModal(null, me, options);

        if (location && location.length > 0 && location.indexOf('/') > -1 && content == '') {
            modalObj.load(location);
        } else {
            modalObj.show(forceType || null);
        }
    });
};

var rebindClick = function(element) {
    bindclick(element);
};

/*  Shows a popup with the given configuration.
    Used when the popup template is
    built on-the-fly using JS
*/
function showPopup(template, trigger, callback) {
    var type = 'alert';

    if (typeof window[callback] != 'undefined') {
        var fn = window[callback];
        var preparedCallback = function(confirm){
            if (confirm) {
                fn();
            }
        }
        callback = preparedCallback;
        type = 'confirm';
    }

    var options = {
        message: template.html(),
        callback: callback,
        type: type
    };

    modalObj = remoteModal(null, trigger, options);
    modalObj.show();
    ajaxLoad(false);
}
