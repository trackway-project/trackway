; // semi-colon is a safety net against concatenated scripts and/or other modules which may not be closed properly.
(function ($) {
    var _defaults = {};

    /**
     * The module UIModuleWrapper creates kjss-generated UI modules and has to be included once
     * in your project for all kjss UI modules.
     *
     * This Container is a blueprint for actual UIModuleWrapper objects
     *
     * @type {{init: Function, handle: Function}}
     */
    var UIModuleWrapper = {
        /**
         * initial method to set up module
         */
        init: function () {},

        /**
         * Handles the module call
         * @param moduleName
         * @param constructor
         * @param parent
         * @param args
         * @returns {*}
         */
        handle: function (moduleName, constructor, parent, args) {
            var arg = args[0], // get first argument
                dataVal = "Module" + moduleName,
                returnVal = null,
                returnValIfEmpty = parent.each(function () {
                    var module = $.data(this, dataVal);
                    if (!module) {
                        // if not instantiated yet, do so by calling constructor
                        $.data(this, dataVal, new constructor(this, arg));
                    } else {
                        // already instantiated, lookup function
                        if (module[arg] && typeof module[arg] == "function") {
                            // argument is a function
                            var functionArgs = Array.prototype.slice.call(args, 1);
                            if (module[arg].length > functionArgs.length) {
                                jQuery.error('Method "' + arg + '" requires ' + module[arg].length + ' parameter(s), ' + functionArgs.length + ' given');
                            }
                            return module[arg].apply(module, functionArgs);
                        } else if (module.settings[arg]) {
                            // argument is a setting
                            if (args[1]) {
                                module.settings[arg] = args[1];
                            } else {
                                returnVal = module.settings[arg];
                                return false;
                            }
                        } else {
                            jQuery.error('Method or setting "' + arg + '" does not exist on jQuery.' + moduleName);
                        }
                    }
                });
            return returnVal || returnValIfEmpty;
        }
    };

    /**
     * [auto-generated code]
     * The actual module constructor
     *
     * @param options
     * @constructor
     */
    function UIModuleWrapperConstructor(options) {
        this.settings = $.extend({}, _defaults, options);
        this.init();
    }

    /**
     * [auto-generated code]
     * Avoid Module.prototype conflicts
     */
    $.extend(UIModuleWrapperConstructor.prototype, UIModuleWrapper);

    /**
     * [auto-generated code]
     * A module wrapper
     *
     * @param options
     * @returns {UIModuleWrapperConstructor}
     * @constructor
     */
    window.UIModuleWrapper = function (options) {
        return new UIModuleWrapperConstructor(options);
    };

})(jQuery, window, document);