$(document).ready(function() {
    $('.js-carrier-autocomplete').each(function() {
        var autocompleteUrl = $(this).data('autocomplete-url');
        
        $(this).autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.carriers);
                    });
                },
                displayKey: 'code',
                debounce: 500
            }
        ]);
    });
});
