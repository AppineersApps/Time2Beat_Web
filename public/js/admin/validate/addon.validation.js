$(function() {
    $.validator.addMethod( "notEqualTo", function( value, element, param ) {
        return this.optional(element) || !$.validator.methods.equalTo.call( this, value, element, param );
    }, "Please enter a different value, values must not be the same." );
    $.validator.addMethod("nowhitespace", function(value, element) {
        return this.optional(element) || /^\S+$/i.test(value);
    }, "No white space please");
    
    $.validator.addMethod("alpha_with_spaces", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/.test(value);
    }, "Please enter valid characters data.(space allowed)");
    $.validator.addMethod("alpha_without_spaces", function(value, element) {
        return this.optional(element) || /^[a-zA-Z]+$/.test(value);
    }, "Please enter valid characters only.");

    $.validator.addMethod("alpha_numeric_with_spaces", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z ]+$/.test(value);
    }, "Please enter alpha numeric characters (space allowed).");
    $.validator.addMethod("alpha_numeric_without_spaces", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z]+$/.test(value);
    }, "Please enter alpha numeric characters only.");

    $.validator.addMethod("alpha_without_special_chars", function(value, element) {
        return this.optional(element) || /^[a-zA-Z _-]+$/.test(value);
    }, "Please enter valid characters without special characters.");
    $.validator.addMethod("alpha_numeric_without_special_chars", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z _-]+$/.test(value);
    }, "Please enter alpha numeric characters without special character.");
    
    $.validator.addMethod("phone_number", function(value, element) {
        return this.optional(element) || /^([(]{1}[0-9]{3}[)]{1}[.| |-]{0,1}|^[0-9]{3}[.|-| ]?)?[0-9]{3}(.|-| )?[0-9]{4}$/.test(value);
    }, "Please enter valid phone numer.");
    $.validator.addMethod("zip_code", function(value, element) {
        return this.optional(element) || /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/.test(value);
    }, "Please enter valid zip code.");
    $.validator.addMethod("credit_card", function(value, element) {
        return this.optional(element) || /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35d{3})d{11})$/.test(value);
    }, "Please enter valid credit card number.");
    
    $.validator.addMethod("maxWords", function(value, element, params) {
        return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length <= params;
    }, $.validator.format("Please enter {0} words or less."));
    $.validator.addMethod("minWords", function(value, element, params) {
        return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params;
    }, $.validator.format("Please enter at least {0} words."));
    $.validator.addMethod("rangeWords", function(value, element, params) {
        var valueStripped = stripHtml(value),
            regex = /\b\w+\b/g;
        return this.optional(element) || valueStripped.match(regex).length >= params[0] && valueStripped.match(regex).length <= params[1];
    }, $.validator.format("Please enter between {0} and {1} words."));

    $.validator.addMethod("ip_address", function(value, element) {
        return this.optional(element) || /^(1?d{1,2}|2([0-4]d|5[0-5]))(.(1?d{1,2}|2([0-4]d|5[0-5]))){3}$/.test(value);
    }, "Please enter valid ip address.");
    $.validator.addMethod("ipv4", function(value, element) {
        return this.optional(element) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value);
    }, "Please enter a valid IP v4 address.");
    $.validator.addMethod("ipv6", function(value, element) {
        return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
    }, "Please enter a valid IP v6 address.");
});