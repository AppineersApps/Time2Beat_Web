/* jQuery validation plug-in 1.7 | http://bassistance.de/jquery-plugins/jquery-plugin-validation/  | $Id: jquery.validate.js 6403 2009-06-17 14:27:16Z joern.zaefferer */
(function($) {

    $.extend($.fn, {
        validate: function( options ) {

            // if nothing is selected, return nothing; can't chain anyway
            if (!this.length) {
                options && options.debug && window.console && console.warn( "nothing selected, can't validate, returning nothing" );
                return;
            }

            // check if a validator for this form was already created
            var validator = $.data(this[0], 'validator');
            if ( validator ) {
                return validator;
            }

            validator = new $.validator( options, this[0] );
            $.data(this[0], 'validator', validator);

            if ( validator.settings.onsubmit ) {

                // allow suppresing validation by adding a cancel class to the submit button
                this.find("input, button").filter(".cancel").click(function() {
                    validator.cancelSubmit = true;
                });

                // when a submitHandler is used, capture the submitting button
                if (validator.settings.submitHandler) {
                    this.find("input, button").filter(":submit").click(function() {
                        validator.submitButton = this;
                    });
                }

                // validate the form on submit
                this.submit( function( event ) {

                    if ( validator.settings.debug )
                        // prevent form submit to be able to see console output
                        event.preventDefault();

                    function handle() {
                        if ( validator.settings.submitHandler ) {
                            if (validator.submitButton) {
                                // insert a hidden input as a replacement for the missing submit button
                                var hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val(validator.submitButton.value).appendTo(validator.currentForm);
                            }
                            validator.settings.submitHandler.call( validator, validator.currentForm );

                            if (validator.submitButton) {
                                // and clean up afterwards; thanks to no-block-scope, hidden can be referenced
                                hidden.remove();
                            }
                            return false;
                        }
                        return true;
                    }

                    // prevent submit for invalid forms or custom submit handlers
                    if ( validator.cancelSubmit ) {
                        validator.cancelSubmit = false;
                        return handle();
                    }
                    if ( validator.form() ) {

                        if ( validator.pendingRequest ) {
                            validator.formSubmitted = true;
                            return false;
                        }
                        return handle();
                    } else {
                        validator.focusInvalid();
                        return false;
                    }
                });
            }

            return validator;
        },
        // http://docs.jquery.com/Plugins/Validation/valid
        valid: function() {
            if ( $(this[0]).is('form')) {
                return this.validate().form();
            } else {

                var valid = true;
                var validator = $(this[0].form).validate();
                this.each(function() {
                    valid &= validator.element(this);
                });
                return valid;
            }
        },
        // attributes: space seperated list of attributes to retrieve and remove
        removeAttrs: function(attributes) {
            var result = {},
            $element = this;
            $.each(attributes.split(/\s/), function(index, value) {
                result[value] = $element.attr(value);
                $element.removeAttr(value);
            });
            return result;
        },
        
        // http://docs.jquery.com/Plugins/Validation/rules
        rules: function(command, argument) {
            var element = this[0];

            if (command) {
                var settings = $.data(element.form, 'validator').settings;
                var staticRules = settings.rules;
                var existingRules = $.validator.staticRules(element);
                switch(command) {
                    case "add":
                        $.extend(existingRules, $.validator.normalizeRule(argument));
                        staticRules[element.name] = existingRules;
                        if (argument.messages)
                            settings.messages[element.name] = $.extend( settings.messages[element.name], argument.messages );
                        break;
                    case "remove":
                        if (!argument) {
                            delete staticRules[element.name];
                            return existingRules;
                        }
                        var filtered = {};
                        $.each(argument.split(/\s/), function(index, method) {
                            filtered[method] = existingRules[method];
                            delete existingRules[method];
                        });
                        return filtered;
                }
            }

            var data = $.validator.normalizeRules(
                $.extend(
                {},
                    $.validator.metadataRules(element),
                    $.validator.classRules(element),
                    $.validator.attributeRules(element),
                    $.validator.staticRules(element)
                    ), element);

            // make sure required is at front
            if (data.required) {
                var param = data.required;
                delete data.required;
                data = $.extend({
                    required: param
                }, data);
            }

            return data;
        }
    });

    // Custom selectors
    $.extend($.expr[":"], {
        // http://docs.jquery.com/Plugins/Validation/blank
        blank: function(a) {
            return !$.trim("" + a.value);
        },
        // http://docs.jquery.com/Plugins/Validation/filled
        filled: function(a) {
            return !!$.trim("" + a.value);
        },
        // http://docs.jquery.com/Plugins/Validation/unchecked
        unchecked: function(a) {
            return !a.checked;
        }
    });

    // constructor for validator
    $.validator = function( options, form ) {
        this.settings = $.extend( true, {}, $.validator.defaults, options );
        this.currentForm = form;
        this.init();
    };

    $.validator.format = function(source, params) {

        if ( arguments.length == 1 )
            return function() {
                var args = $.makeArray(arguments);
                args.unshift(source);
                return $.validator.format.apply( this, args );
            };
        if ( arguments.length > 2 && params.constructor != Array  ) {
            params = $.makeArray(arguments).slice(1);
        }
        if ( params.constructor != Array ) {
            params = [ params ];
        }
        $.each(params, function(i, n) {
            source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
        });
        return source;
    };

    $.extend($.validator, {

        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "err",
            validClass: "valid",
            errorElement: "div",
            focusInvalid: true,
            errorContainer: $( [] ),
            errorLabelContainer: $( [] ),
            onsubmit: true,
            ignore: [],
            ignoreTitle: false,
            onfocusin: function(element) {
                this.lastActive = element;

                // hide error label and remove error class on focus if enabled
                if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
                    this.settings.unhighlight && this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
                    this.errorsFor(element).hide();
                }
            },
            onfocusout: function(element) {
                if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
                    this.element(element);
                }
            },
            onkeyup: function(element) {
                if ( element.name in this.submitted || element == this.lastElement ) {
                    this.element(element);
                }
            },
            onclick: function(element) {
                // click on selects, radiobuttons and checkboxes
                if ( element.name in this.submitted )
                    this.element(element);
                // or option elements, check parent select in that case
                else if (element.parentNode.name in this.submitted)
                    this.element(element.parentNode);
            },
            highlight: function( element, errorClass, validClass ) {
                $(element).addClass(errorClass).removeClass(validClass);
                if($(element).closest(".form-right-div").hasClass("frm-controls-merge")){
                    if($(element).closest(".frm-controls-merge").parent().find(".frm-labels-merge").length){
                        var ind = $(element).closest(".frm-merge-ctrl-block").index();
                        $(element).closest(".frm-controls-merge").parent().find(".frm-labels-merge").find(".form-label:eq("+ind+")").addClass(errorClass).removeClass(validClass);
                    } else {
                        $(element).closest(".form-right-div").parent().find(".form-label").addClass(errorClass).removeClass(validClass);
                    }
                } else {
                    $(element).closest(".form-right-div").parent().find(".form-label").addClass(errorClass).removeClass(validClass);
                }
            },
            unhighlight: function( element, errorClass, validClass ) {
                $(element).removeClass(errorClass).addClass(validClass);
                if($(element).closest(".form-right-div").hasClass("frm-controls-merge")){
                    if($(element).closest(".frm-controls-merge").parent().find(".frm-labels-merge").length){
                        var ind = $(element).closest(".frm-merge-ctrl-block").index();
                        $(element).closest(".frm-controls-merge").parent().find(".frm-labels-merge").find(".form-label:eq("+ind+")").removeClass(errorClass).addClass(validClass);
                    } else {
                        $(element).closest(".form-right-div").parent().find(".form-label").removeClass(errorClass).addClass(validClass);
                    }
                } else {
                    $(element).closest(".form-right-div").parent().find(".form-label").removeClass(errorClass).addClass(validClass);
                }
            }
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/setDefaults
        setDefaults: function(settings) {
            $.extend( $.validator.defaults, settings );
        },

        messages: {
            required: "This field is required.",
            firstname: "Please enter a valid firstname.",
            lastname: "Please enter a valid lastname.",
            namewithdot:"Please enter a valid name",
            phonenumber:"Please enter a valid phone number.",
            cellnumber:"Please enter a valid cell number(minimum 10 digits).",
            username:"Please enter a valid user number.",
            fullname:"Please enter a valid full name.",
            notStartWithSpace:"Please enter a value which not started from space.",
            nonzeronumber:"Please enter a number greater than zero",
            code:"Please enter a valid code.",
            password:"Please enter a valid password.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            nospace:"No space please and don't leave it empty",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            maxdate: $.validator.format("Please enter date smaller or equal to {0}."),
            mindate: $.validator.format("Please enter date greater or equal to {0}."),
            number: "Please enter a valid number.",
            positive_number:"Please enter a valid Positive Number.",
            phone:"Please enter a valid Phone Number",
            digits: "Please enter only digits.",
            nondecimal: "Please enter only digital number.",
            decimals: "Number has wrong format.",
            specialcharacters:"Please enter valid name",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            accept: "Please upload a image with valid extension.",
            maxlength: $.validator.format("Please enter no more than {0} characters."),
            minlength: $.validator.format("Please enter at least {0} characters."),
            rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
            range: $.validator.format("Please enter a value between {0} and {1}."),
            max: $.validator.format("Please enter a value less than or equal to {0}."),
            min: $.validator.format("Please enter a value greater than or equal to {0}."),
            alphaspace: "No space please and don't leave it empty.",
            alphaOnly: "Alphabets are allowed only.",
            countryTitle: "Please enter valid name.",
            newsTitle: "Please enter valid title.",
            alphaCode:"Alphabets are allowed only.",
            zipCode:"Please enter valid zipcode.",
            faxCode:"Please enter valid fax number.",
            tollFreeCode:"Please enter valid toll free number.",
            url_host:"Please enter a valid URL.",
            price:"Please enter a valid Price.",
            latlong:"Please enter valid latitude/longitude",
            categoryname:"Please enter valid category name"
        },

        autoCreateRanges: false,

        prototype: {

            init: function() {
                this.labelContainer = $(this.settings.errorLabelContainer);
                this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
                this.containers = $(this.settings.errorContainer).add( this.settings.errorLabelContainer );
                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();

                var groups = (this.groups = {});
                $.each(this.settings.groups, function(key, value) {
                    $.each(value.split(/\s/), function(index, name) {
                        groups[name] = key;
                    });
                });
                var rules = this.settings.rules;
                $.each(rules, function(key, value) {
                    rules[key] = $.validator.normalizeRule(value);
                });

                function delegate(event) {
                    var validator = $.data(this[0].form, "validator"),
                    eventType = "on" + event.type.replace(/^validate/, "");
                    validator.settings[eventType] && validator.settings[eventType].call(validator, this[0] );
                }
                $(this.currentForm)
                .validateDelegate(":text, :password, :file, select, textarea", "focusin focusout keyup", delegate)
                .validateDelegate(":radio, :checkbox, select, option", "click", delegate);

                if (this.settings.invalidHandler)
                    $(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler);
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/form
            form: function() {
                this.checkForm();
                $.extend(this.submitted, this.errorMap);
                this.invalid = $.extend({}, this.errorMap);
                if (!this.valid())
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                this.showErrors();
                return this.valid();
            },

            checkForm: function() {
                this.prepareForm();
                /*for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                    this.check( elements[i] );
                }*/
                for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                    if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
                        for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                            this.check( this.findByName( elements[i].name )[cnt] );
                        }
                    } else {
                        this.check( elements[i] );
                    }
                }
                return this.valid();
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/element
            element: function( element ) {
                element = this.clean( element );
                this.lastElement = element;
                this.prepareElement( element );
                this.currentElements = $(element);
                var result = this.check( element );
                if ( result ) {
                    delete this.invalid[element.name];
                } else {
                    this.invalid[element.name] = true;
                }
                if ( !this.numberOfInvalids() ) {
                    // Hide error containers on last error
                    this.toHide = this.toHide.add( this.containers );
                }
                this.showErrors();
                return result;
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/showErrors
            showErrors: function(errors) {
                if(errors) {
                    // add items to error list and map
                    $.extend( this.errorMap, errors );
                    this.errorList = [];
                    for ( var name in errors ) {
                        this.errorList.push({
                            message: errors[name],
                            element: this.findByName(name)[0]
                        });
                    }
                    // remove items from success list
                    this.successList = $.grep( this.successList, function(element) {
                        return !(element.name in errors);
                    });
                }
                this.settings.showErrors
                ? this.settings.showErrors.call( this, this.errorMap, this.errorList )
                : this.defaultShowErrors();
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/resetForm
            resetForm: function() {
                if ( $.fn.resetForm )
                    $( this.currentForm ).resetForm();
                this.submitted = {};
                this.prepareForm();
                this.hideErrors();
                this.elements().removeClass( this.settings.errorClass );
            },

            numberOfInvalids: function() {
                return this.objectLength(this.invalid);
            },

            objectLength: function( obj ) {
                var count = 0;
                for ( var i in obj )
                    count++;
                return count;
            },

            hideErrors: function() {
                this.addWrapper( this.toHide ).hide();
            },

            valid: function() {
                return this.size() == 0;
            },

            size: function() {
                return this.errorList.length;
            },

            focusInvalid: function() {
                if( this.settings.focusInvalid ) {
                    try {
                        $(this.findLastActive() || this.errorList.length && this.errorList[0].element || [])
                        .filter(":visible")
                        .focus()
                        // manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
                        .trigger("focusin");
                    } catch(e) {
                    // ignore IE throwing errors when focusing hidden elements
                    }
                }
            },

            findLastActive: function() {
                var lastActive = this.lastActive;
                return lastActive && $.grep(this.errorList, function(n) {
                    return n.element.name == lastActive.name;
                }).length == 1 && lastActive;
            },

            elements: function() {
                var validator = this,
                rulesCache = {};

                // select all valid inputs inside the form (no submit or reset buttons)
                // workaround $Query([]).add until http://dev.jquery.com/ticket/2114 is solved
                return $([]).add(this.currentForm.elements)
                .filter(":input")
                .not(":submit, :reset, :image, [disabled]")
                .not( this.settings.ignore )
                .filter(function() {
                    !this.name && validator.settings.debug && window.console && console.error( "%o has no name assigned", this);

                    // select only the first element for each name, and only those with rules specified
                    if ( this.name in rulesCache || !validator.objectLength($(this).rules()) )
                        return false;

                    rulesCache[this.name] = true;
                    return true;
                });
            },

            clean: function( selector ) {
                return $( selector )[0];
            },

            errors: function() {
                return $( this.settings.errorElement + "." + this.settings.errorClass, this.errorContext );
            },

            reset: function() {
                this.successList = [];
                this.errorList = [];
                this.errorMap = {};
                this.toShow = $([]);
                this.toHide = $([]);
                this.currentElements = $([]);
            },

            prepareForm: function() {
                this.reset();
                this.toHide = this.errors().add( this.containers );
            },

            prepareElement: function( element ) {
                this.reset();
                this.toHide = this.errorsFor(element);
            },

            check: function( element ) {
                element = this.clean( element );

                // if radio/checkbox, validate first element in group instead
                if (this.checkable(element)) {
                    element = this.findByName( element.name )[0];
                }

                var rules = $(element).rules();
                var dependencyMismatch = false;
                for( method in rules ) {
                    var rule = {
                        method: method,
                        parameters: rules[method]
                    };
                    try {
                        var result = $.validator.methods[method].call( this, element.value.replace(/\r/g, ""), element, rule.parameters );

                        // if a method indicates that the field is optional and therefore valid,
                        // don't mark it as valid when there are no other rules
                        if ( result == "dependency-mismatch" ) {
                            dependencyMismatch = true;
                            continue;
                        }
                        dependencyMismatch = false;

                        if ( result == "pending" ) {
                            this.toHide = this.toHide.not( this.errorsFor(element) );
                            return;
                        }

                        if( !result ) {
                            this.formatAndAdd( element, rule );
                            return false;
                        }
                    } catch(e) {
                        this.settings.debug && window.console && console.log("exception occured when checking element " + element.id
                            + ", check the '" + rule.method + "' method", e);
                        throw e;
                    }
                }
                if (dependencyMismatch)
                    return;
                if ( this.objectLength(rules) )
                    this.successList.push(element);
                return true;
            },

            // return the custom message for the given element and validation method
            // specified in the element's "messages" metadata
            customMetaMessage: function(element, method) {
                if (!$.metadata)
                    return;

                var meta = this.settings.meta
                ? $(element).metadata()[this.settings.meta]
                : $(element).metadata();

                return meta && meta.messages && meta.messages[method];
            },

            // return the custom message for the given element name and validation method
            customMessage: function( name, method ) {
                var m = this.settings.messages[name];
                return m && (m.constructor == String
                    ? m
                    : m[method]);
            },

            // return the first defined argument, allowing empty strings
            findDefined: function() {
                for(var i = 0; i < arguments.length; i++) {
                    if (arguments[i] !== undefined)
                        return arguments[i];
                }
                return undefined;
            },

            defaultMessage: function( element, method) {
                return this.findDefined(
                    this.customMessage( element.name, method ),
                    this.customMetaMessage( element, method ),
                    // title is never undefined, so handle empty string as undefined
                    !this.settings.ignoreTitle && element.title || undefined,
                    $.validator.messages[method],
                    "<strong>Warning: No message defined for " + element.name + "</strong>"
                    );
            },

            formatAndAdd: function( element, rule ) {
                var message = this.defaultMessage( element, rule.method ),
                theregex = /\$?\{(\d+)\}/g;
                if ( typeof message == "function" ) {
                    message = message.call(this, rule.parameters, element);
                } else if (theregex.test(message)) {
                    message = jQuery.format(message.replace(theregex, '{$1}'), rule.parameters);
                }
                this.errorList.push({
                    message: message,
                    element: element
                });

                this.errorMap[element.name] = message;
                this.submitted[element.name] = message;
            },

            addWrapper: function(toToggle) {
                if ( this.settings.wrapper )
                    toToggle = toToggle.add( toToggle.parent( this.settings.wrapper ) );
                return toToggle;
            },

            defaultShowErrors: function() {
                for ( var i = 0; this.errorList[i]; i++ ) {
                    var error = this.errorList[i];
                    this.settings.highlight && this.settings.highlight.call( this, error.element, this.settings.errorClass, this.settings.validClass );
                    this.showLabel( error.element, error.message );
                }
                if( this.errorList.length ) {
                    this.toShow = this.toShow.add( this.containers );
                }
                if (this.settings.success) {
                    for ( var i = 0; this.successList[i]; i++ ) {
                        this.showLabel( this.successList[i] );
                    }
                }
                if (this.settings.unhighlight) {
                    for ( var i = 0, elements = this.validElements(); elements[i]; i++ ) {
                        this.settings.unhighlight.call( this, elements[i], this.settings.errorClass, this.settings.validClass );
                    }
                }
                this.toHide = this.toHide.not( this.toShow );
                this.hideErrors();
                this.addWrapper( this.toShow ).show();
            },

            validElements: function() {
                return this.currentElements.not(this.invalidElements());
            },

            invalidElements: function() {
                return $(this.errorList).map(function() {
                    return this.element;
                });
            },

            showLabel: function(element, message) {
                var label = this.errorsFor( element );
                if ( label.length ) {
                    // refresh error/success class
                    label.removeClass().addClass( this.settings.errorClass );
                    // check if we have a generated label, replace the message then
                    label.attr("generated") && label.html(message);
                } else {
                    // create label
                    label = $("<" + this.settings.errorElement + "/>")
                    .attr({
                        "for":  this.idOrName(element),
                        generated: true
                    })
                    .addClass(this.settings.errorClass)
                    .html(message || "");
                    if ( this.settings.wrapper ) {
                        // make sure the element is visible, even in IE
                        // actually showing the wrapped element is handled elsewhere
                        label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
                    }
                    var setpos = "";
                    if (element.type== 'radio' || element.type== 'checkbox'){
                        setpos = $(element).parent().after(label);
                    } else {
                        setpos = label.insertAfter(element);
                    }
                    if ( !this.labelContainer.append(label).length )
                        this.settings.errorPlacement
                        ? this.settings.errorPlacement(label, $(element) )
                        : setpos;

                }
                if ( !message && this.settings.success ) {
                    label.text("");
                    typeof this.settings.success == "string"
                    ? label.addClass( this.settings.success )
                    : this.settings.success( label );
                }
                var lft = ($(element).position().left - $(element).parent().position().left);
                if((lft < 0))
                {
                    if($(element).offset().left >0)
                        lft = $(element).offset().left-30;
                    else
                        lft = 0;
                }
                label.css('padding-left',lft+'px');
                this.toShow = this.toShow.add(label);
            },

            errorsFor: function(element) {
                var name = this.idOrName(element);
                return this.errors().filter(function() {
                    return $(this).attr('for') == name;
                });
            },

            idOrName: function(element) {
                return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
            },

            checkable: function( element ) {
                return /radio|checkbox/i.test(element.type);
            },

            findByName: function( name ) {
                // select by name and filter by form for performance over form.find("[name=...]")
                var form = this.currentForm;
                return $(document.getElementsByName(name)).map(function(index, element) {
                    return element.form == form && element.name == name && element  || null;
                });
            },

            getLength: function(value, element) {
                switch( element.nodeName.toLowerCase() ) {
                    case 'select':
                        return $("option:selected", element).length;
                    case 'input':
                        if( this.checkable( element) )
                            return this.findByName(element.name).filter(':checked').length;
                }
                return value.length;
            },

            depend: function(param, element) {
                return this.dependTypes[typeof param]
                ? this.dependTypes[typeof param](param, element)
                : true;
            },

            dependTypes: {
                "boolean": function(param, element) {
                    return param;
                },
                "string": function(param, element) {
                    return !!$(param, element.form).length;
                },
                "function": function(param, element) {
                    return param(element);
                }
            },

            optional: function(element) {
                return !$.validator.methods.required.call(this, $.trim(element.value), element) && "dependency-mismatch";
            },

            startRequest: function(element) {
                if (!this.pending[element.name]) {
                    this.pendingRequest++;
                    this.pending[element.name] = true;
                }
            },

            stopRequest: function(element, valid) {
                this.pendingRequest--;
                // sometimes synchronization fails, make sure pendingRequest is never < 0
                if (this.pendingRequest < 0)
                    this.pendingRequest = 0;
                delete this.pending[element.name];
                if ( valid && this.pendingRequest == 0 && this.formSubmitted && this.form() ) {
                    $(this.currentForm).submit();
                    this.formSubmitted = false;
                } else if (!valid && this.pendingRequest == 0 && this.formSubmitted) {
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                    this.formSubmitted = false;
                }
            },

            previousValue: function(element) {
                return $.data(element, "previousValue") || $.data(element, "previousValue", {
                    old: null,
                    valid: true,
                    message: this.defaultMessage( element, "remote" )
                });
            }

        },

        classRuleSettings: {
            required: {
                required: true
            },
            firstname: {
                firstname: true
            },
            lastname: {
                lastname: true
            },
            specialcharacters:{
                specialcharacters:true
            },
            namewithdot:{
                namewithdot:true
            },
            phonenumber:{
                phonenumber:true
            },
            cellnumber:{
                cellnumber:true
            },
            username:{
                username:true
            },
            fullname:{
                fullname:true
            },
            notStartWithSpace:{
                notStartWithSpace:true
            },
            nonzeronumber:{
                nonzeronumber:true
            },
            code:{
                code:true
            },
            password:{
                password:true
            },
            email: {
                email: true
            },
            nospace: {
                nospace: true
            },
            url: {
                url: true
            },
            date: {
                date: true
            },
            dateISO: {
                dateISO: true
            },
            maxdate: {
                maxdate: true
            },
            mindate: {
                mindate: true
            },
            dateDE: {
                dateDE: true
            },
            number: {
                number: true
            },
            numberDE: {
                numberDE: true
            },
            digits: {
                digits: true
            },
            nondecimal: {
                nondecimal:true  
            },
            alphaspace: {
                digits: true
            },
            alphaOnly:{
                alphaOnly:true
            },
            countryTitle:{
                countryTitle:true
            },
            newsTitle:{
                newsTitle:true  
            },
            alphaCode:{
                alphaCode:true
            },
            zipCode:{
                zipCode:true
            },
            faxCode:{
                faxCode:true
            },
            tollFreeCode:{
                tollFreeCode:true
            },
            url_host:{
                url_host : true  
            },
            decimals: {
                decimals: true
            },
            creditcard: {
                creditcard: true
            },
            price: {
                price: true
            },
            latlong:{
                latlong:true
            },
            categoryname:{
                categoryname:true
            }
        },

        addClassRules: function(className, rules) {
            className.constructor == String ?
            this.classRuleSettings[className] = rules :
            $.extend(this.classRuleSettings, className);
        },

        classRules: function(element) {
            var rules = {};
            var classes = $(element).attr('class');
            classes && $.each(classes.split(' '), function() {
                if (this in $.validator.classRuleSettings) {
                    $.extend(rules, $.validator.classRuleSettings[this]);
                }
            });
            return rules;
        },

        attributeRules: function(element) {
            var rules = {};
            var $element = $(element);

            for (method in $.validator.methods) {
                var value = $element.attr(method);
                if (value) {
                    rules[method] = value;
                }
            }

            // maxlength may be returned as -1, 2147483647 (IE) and 524288 (safari) for text inputs
            if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
                delete rules.maxlength;
            }

            return rules;
        },

        metadataRules: function(element) {
            if (!$.metadata) return {};

            var meta = $.data(element.form, 'validator').settings.meta;
            return meta ?
            $(element).metadata()[meta] :
            $(element).metadata();
        },

        staticRules: function(element) {
            var rules = {};
            var validator = $.data(element.form, 'validator');
            if (validator.settings.rules) {
                rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
            }
            return rules;
        },

        normalizeRules: function(rules, element) {
            // handle dependency check
            $.each(rules, function(prop, val) {
                // ignore rule when param is explicitly false, eg. required:false
                if (val === false) {
                    delete rules[prop];
                    return;
                }
                if (val.param || val.depends) {
                    var keepRule = true;
                    switch (typeof val.depends) {
                        case "string":
                            keepRule = !!$(val.depends, element.form).length;
                            break;
                        case "function":
                            keepRule = val.depends.call(element, element);
                            break;
                    }
                    if (keepRule) {
                        rules[prop] = val.param !== undefined ? val.param : true;
                    } else {
                        delete rules[prop];
                    }
                }
            });

            // evaluate parameters
            $.each(rules, function(rule, parameter) {
                rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter;
            });

            // clean number parameters
            $.each(['minlength', 'maxlength', 'min', 'max'], function() {
                if (rules[this]) {
                    rules[this] = Number(rules[this]);
                }
            });
            $.each(['rangelength', 'range'], function() {
                if (rules[this]) {
                    rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
                }
            });

            if ($.validator.autoCreateRanges) {
                // auto-create ranges
                if (rules.min && rules.max) {
                    rules.range = [rules.min, rules.max];
                    delete rules.min;
                    delete rules.max;
                }
                if (rules.minlength && rules.maxlength) {
                    rules.rangelength = [rules.minlength, rules.maxlength];
                    delete rules.minlength;
                    delete rules.maxlength;
                }
            }

            // To support custom messages in metadata ignore rule methods titled "messages"
            if (rules.messages) {
                delete rules.messages;
            }

            return rules;
        },

        // Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
        normalizeRule: function(data) {
            if( typeof data == "string" ) {
                var transformed = {};
                $.each(data.split(/\s/), function() {
                    transformed[this] = true;
                });
                data = transformed;
            }
            return data;
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/addMethod
        addMethod: function(name, method, message) {
            $.validator.methods[name] = method;
            $.validator.messages[name] = message != undefined ? message : $.validator.messages[name];
            if (method.length < 3) {
                $.validator.addClassRules(name, $.validator.normalizeRule(name));
            }
        },

        methods: {

            // http://docs.jquery.com/Plugins/Validation/Methods/required
            required: function(value, element, param) {
                // check if dependency is met
                if ( !this.depend(param, element) )
                    return "dependency-mismatch";
                switch( element.nodeName.toLowerCase() ) {
                    case 'select':
                        // could be an array for select-multiple or a string, both are fine this way
                        var val = $(element).val();
                        /* ADDED LAST CONDITION BY SNEHASIS */
                        return val && val.length > 0 && val != '';
                    case 'input':
                        if ( this.checkable(element) )
                            return this.getLength(value, element) > 0;
                    default:
                        return $.trim(value).length > 0;
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/remote
            remote: function(value, element, param) {
                // alert(value,element,param);
                if ( this.optional(element) )
                    return "dependency-mismatch";

                var previous = this.previousValue(element);
                if (!this.settings.messages[element.name] )
                    this.settings.messages[element.name] = {};
                previous.originalMessage = this.settings.messages[element.name].remote;
                this.settings.messages[element.name].remote = previous.message;

                param = typeof param == "string" && {
                    url:param
                } || param;

                if ( previous.old !== value ) {
                    previous.old = value;
                    var validator = this;
                    this.startRequest(element);
                    var data = {};
                    data[element.name] = value;
                    //alert(param+data);
                    $.ajax($.extend(true, {
                        url: param,
                        mode: "abort",
                        port: "validate" + element.name,
                        dataType: "json",
                        data: data,
                        success: function(response) {
                            validator.settings.messages[element.name].remote = previous.originalMessage;
                            var valid = response === true;
                            if ( valid ) {
                                var submitted = validator.formSubmitted;
                                validator.prepareElement(element);
                                validator.formSubmitted = submitted;
                                validator.successList.push(element);
                                validator.showErrors();
                            } else {
                                var errors = {};
                                var message = (previous.message = response || validator.defaultMessage( element, "remote" ));
                                errors[element.name] = $.isFunction(message) ? message(value) : message;
                                validator.showErrors(errors);
                            }
                            previous.valid = valid;
                            validator.stopRequest(element, valid);
                        }
                    }, param));
                    return "pending";
                } else if( this.pending[element.name] ) {
                    return "pending";
                }
                return previous.valid;
            },
            firstname: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||value.match(/^[a-zA-Z ]+$/);
                } else {
                    return false;
                }
            },
            lastname: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[a-zA-Z' ]+$/);
                } else {
                    return false;
                }
            },
            namewithdot: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[a-zA-Z.\' ]+$/);
                } else {
                    return false;
                }
            },
            specialcharacters: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[^!@#$%<>+=*`~"^\\?:;|/]+$/);
                } else {
                    return false;
                }
            },
            phonenumber: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9\+)(\[\]{}-]+$/);
            },
            cellnumber: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9]+$/) && (value).length >= 10;
            },
            username: function(value, element) {
                if((value).length >= 5) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[a-zA-Z0-9]+$/);
                } else {
                    return false;
                }
            },
            fullname: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[a-zA-Z\' ]+$/);
                } else {
                    return false;
                }
            },
            notStartWithSpace: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^([\S])+.*$/);
                } else {
                    return false;
                }
            },
            nonzeronumber: function(value, element) {
                if(value > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^\+?[0-9]*\.?[0-9]+$/);
                } else {
                    return false;
                }
            },
            code: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[a-zA-Z]+$/);
                } else {
                    return false;
                }
            },
            password: function(value, element) {
                if((value).length >= 5) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[^ ]+$/);
                } else {
                    return false;
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/minlength
            minlength: function(value, element, param) {
                return this.optional(element) || this.getLength($.trim(value), element) >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/maxlength
            maxlength: function(value, element, param) {
                return this.optional(element) || this.getLength($.trim(value), element) <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/rangelength
            rangelength: function(value, element, param) {
                var length = this.getLength($.trim(value), element);
                return this.optional(element) || ( length >= param[0] && length <= param[1] );
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/min
            min: function( value, element, param ) {
                return this.optional(element) || value >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/max
            max: function( value, element, param ) {
                return this.optional(element) || value <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/range
            range: function( value, element, param ) {
                return this.optional(element) || ( value >= param[0] && value <= param[1] );
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/email
            email: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                //return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
                // return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( value );
                // HB Customization
                return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.[a-zA-Z]{2}(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( value );
            },
            nospace: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                return value.indexOf(" ") < 0 && value != "";
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/url
            //            url: function(value, element) {
            //                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
            //                return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
            //            },


            /*url: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
                //return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
                //                return this.optional(element) || /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi.test(value);
                return this.optional(element) ||
                value.match(/^(http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$/) ||
                value.match(/^(((ht|f)tp(s?))\:\/\/)?(www.|[a-zA-Z].)[a-zA-Z0-9\-\.]+[a-zA-Z0-9/]+$/);
            },*/

            url: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
                //return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
                //return this.optional(element) || /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi.test(value);
                return this.optional( element ) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test( value );
            },


            // http://docs.jquery.com/Plugins/Validation/Methods/date
            date: function(value, element) {
                return this.optional(element) || !/Invalid|NaN/.test(new Date(value));
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/dateISO
            dateISO: function(value, element) {
                return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);
            },

            // check max limit of date
            maxdate: function(value, element, param)
            {
                var val = value.toString();
                var vl = val.split('-');
                var vdt = vl[2]+'/'+vl[1]+'/'+vl[0];
                vl[0] = parseInt(vl[0],10);
                vl[1] = parseInt(vl[1],10);
                vl[2] = parseInt(vl[2],10);
                vl[1] = vl[1] - 1;
                var vdate = new Date(vl[0],vl[1],vl[2]);

                // var tday = new Date();
                var mval = $('#'+param).val().toString();
                var mvl = mval.split('-');
                var mvdt = mvl[2]+'/'+mvl[1]+'/'+mvl[0];
                mvl[0] = parseInt(mvl[0],10);
                mvl[1] = parseInt(mvl[1],10);
                mvl[2] = parseInt(mvl[2],10);
                mvl[1] = mvl[1] - 1;
                var mdate = new Date(mvl[0],mvl[1],mvl[2]);

                // alert(vdate.getTime()+'>'+mdate.getTime());
                if(vdate.getTime() < mdate.getTime()) {
                    return true;
                } else {
                    return false;
                }
            },

            // check min limit of date
            mindate: function(value, element, param)
            {
                if($.trim(value)=='' || $.trim($('#'+param).val())=='') {
                    return true;
                }

                var val = value.toString();
                var vl = val.split('-');
                var vdt = vl[2]+'/'+vl[1]+'/'+vl[0];
                vl[0] = parseInt(vl[0],10);
                vl[1] = parseInt(vl[1],10);
                vl[2] = parseInt(vl[2],10);
                vl[1] = vl[1] - 1;
                var vdate = new Date(vl[0],vl[1],vl[2]);

                // var tday = new Date();
                var mval = $('#'+param).val().toString();
                var mvl = mval.split('-');
                var mvdt = mvl[2]+'/'+mvl[1]+'/'+mvl[0];
                mvl[0] = parseInt(mvl[0],10);
                mvl[1] = parseInt(mvl[1],10);
                mvl[2] = parseInt(mvl[2],10);
                mvl[1] = mvl[1] - 1;
                var mdate = new Date(mvl[0],mvl[1],mvl[2]);

                // alert(vdate.getTime()+'>'+mdate.getTime());
                if(vdate.getTime() > mdate.getTime()) {
                    return true;
                } else {
                    return false;
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/number
            number: function(value, element) {
                return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
            },
            positive_number:function(value){
                return Number(value) > 0;
            },
            phone: function(value, element) {
                return this.optional(element) || /^[0-9\+)(-]+$/.test(value);
            //return this.optional(element) || /^\d{10,15}$/.test(value);
            },


            // http://docs.jquery.com/Plugins/Validation/Methods/digits
            digits: function(value, element) {
                return this.optional(element) || /^\d+$/.test(value);
            },
            nondecimal: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9]+$/);
            },
            alphaspace: function(value, element) {
                // contributed by Chittaranjan Nayak:
                return this.optional(element) || /^[A-Za-z0-9][A-Za-z0-9\.\-_]*$/.test(value);
            },


            alphaOnly: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[a-zA-Z ]+$/);
            },
            countryTitle: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[^0-9!@#$%_<>+=*`~"^\\?:;|/]+$/);
            },
            newsTitle: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[^-!@#$%_<>+=*`~"^\\?:;|/]+$/);
            },
            alphaCode: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[a-zA-Z]+$/);
            },

            zipCode: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9a-zA-Z ]+$/) && (value).length >= 3 && (value).length <= 10;
            },
            faxCode: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9\+)(\[\]{}-]+$/);
            },
            tollFreeCode: function(value, element) {
                return this.optional(element) || value === "NA" ||
                value.match(/^[0-9\+)(-]+$/);
            },
            url_host: function(value, element) {
                if($.trim(value)!=''){
                    url = site_url+'tools/settings/get_host';
                    $.getJSON(url,{
                        validate_url: ""+value+""
                    }, function(data) {
                        response = data.response;
                        return response;
                    });                
                }else{
                    return true;
                }
            },


            // added from outside (http://igorgladkov.com/resources/demos/jquery.validator/demos/decimal_validation.html)
            decimals: function(value, element) {
                return this.optional(element) || /^[0-9\.,]+$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/creditcard
            // based on http://en.wikipedia.org/wiki/Luhn
            creditcard: function(value, element) {
                if ( this.optional(element) )
                    return "dependency-mismatch";
                // accept only digits and dashes
                if (/[^0-9-]+/.test(value))
                    return false;
                var nCheck = 0,
                nDigit = 0,
                bEven = false;

                value = value.replace(/\D/g, "");

                for (var n = value.length - 1; n >= 0; n--) {
                    var cDigit = value.charAt(n);
                    var nDigit = parseInt(cDigit, 10);
                    if (bEven) {
                        if ((nDigit *= 2) > 9)
                            nDigit -= 9;
                    }
                    nCheck += nDigit;
                    bEven = !bEven;
                }

                return (nCheck % 10) == 0;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/accept
            accept: function(value, element, param) {
                param = typeof param == "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
                return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/equalTo
            equalTo: function(value, element, param) {
                // bind to the blur event of the target in order to revalidate whenever the target field is updated
                // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
                var target = $(param).unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
                    $(element).valid();
                });
                return value == target.val();
            },
            price:function(value){
                if(Number(value) > 0) {
                    return  value.match(/^[0-9]+.[0-9]+$/)||value.match(/^[0-9]+$/);
                }
                
            },
            latlong: function(value, element) {
                return this.optional(element) || /^[0-9\.,-]+$/.test(value);
            },
            categoryname: function(value, element) {
                if((value).length > 0) {
                    return this.optional(element) || value === "NA" ||
                    value.match(/^[&a-zA-Z' ]+$/ );
                } else {
                    return false;
                }
            }

        }
    });

    // deprecated, use $.validator.format instead
    $.format = $.validator.format;

})(jQuery);

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()
;
(function($) {
    var ajax = $.ajax;
    var pendingRequests = {};
    $.ajax = function(settings) {
        // create settings for compatibility with ajaxSetup
        settings = $.extend(settings, $.extend({}, $.ajaxSettings, settings));
        var port = settings.port;
        if (settings.mode == "abort") {
            if ( pendingRequests[port] ) {
                pendingRequests[port].abort();
            }
            return (pendingRequests[port] = ajax.apply(this, arguments));
        }
        return ajax.apply(this, arguments);
    };
})(jQuery);

// provides cross-browser focusin and focusout events
// IE has native support, in other browsers, use event caputuring (neither bubbles)

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jquery-object for event.target
;
(function($) {
    // only implement if not provided by jQuery core (since 1.4)
    // TODO verify if jQuery 1.4's implementation is compatible with older jQuery special-event APIs
    if (!jQuery.event.special.focusin && !jQuery.event.special.focusout && document.addEventListener) {
        $.each({
            focus: 'focusin',
            blur: 'focusout'
        }, function( original, fix ){
            $.event.special[fix] = {
                setup:function() {
                    this.addEventListener( original, handler, true );
                },
                teardown:function() {
                    this.removeEventListener( original, handler, true );
                },
                handler: function(e) {
                    arguments[0] = $.event.fix(e);
                    arguments[0].type = fix;
                    return $.event.handle.apply(this, arguments);
                }
            };
            function handler(e) {
                e = $.event.fix(e);
                e.type = fix;
                return $.event.handle.call(this, e);
            }
        });
    };
    $.extend($.fn, {
        validateDelegate: function(delegate, type, handler) {

            return this.bind(type, function(event) {
                var target = $(event.target);
                if (target.is(delegate)) {
                    return handler.apply(target, arguments);
                }
            });
        }
    });
})(jQuery);
