$(function() {
    //currencyFmatter
    $.extend($.fn.fmatter, {
        currencyFmatter: function(cellvalue, options, rowObject) {
            return "$ " + cellvalue;
        }
    });
    $.extend($.fn.fmatter.currencyFmatter, {
        unformat: function(cellvalue, options, cell) {
            return cellvalue.replace("$", "");
        }
    });
});