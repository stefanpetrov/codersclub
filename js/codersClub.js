var $updateViaAjax = false;

/**
 * Validate PHP and show error message
 */
function codersClubValidatePHP() {
    var r = (-0.5) + (Math.random() * (1000.99));
    $.getJSON("lib/codersClub.php?key=validatePHP&ajax=ajax&r=" + r, function (data) {
        $.each(data, function(key, val){
            codersClubError(key, val);
        });
    });
}

/**
 * Load data from source and save it to DB
 */
function codersClubUpdateDB() {
    var r = (-0.5) + (Math.random() * (1000.99));
    $.get("lib/codersClub.php?key=save&ajax=ajax&r=" + r);
}

/**
 * Display error message
 * @param key
 * @param message
 */
function codersClubError(key, message) {
    if (key == 'error') {
        $( "<p/>", {
            "class": "bg-danger",
            html: message
        }).prependTo( "body" );
    }
}

/**
 * Print values in html
 * @param selector
 * @param key
 * @param val
 */
function codersClubPrint(selector, data) {
    $.each(data, function(key, val) {
        if (key == 'error') {
            codersClubError(key, val);
        } else {
            $.each(val, function(key1, val1) {
                $(selector + ' .' + key1).html(val1);
            });
        };
    });
}

$(function() {
    codersClubValidatePHP();
    if ($updateViaAjax) {
        var intervalID = window.setInterval(codersClubUpdateDB, 1000);
    }

    // Init datepicker
    $( "#dateFrom" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        onClose: function( selectedDate ) {
            $( "#dateTo" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    // Init datepicker
    $( "#dateTo" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        onClose: function( selectedDate ) {
            $( "#dateFrom" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    // Override event of the trigger element
    $("a.show-current").on('click', function (event) {
        event.preventDefault();
        var r = (-0.5) + (Math.random() * (1000.99));
        $.getJSON("lib/codersClub.php?key=current&ajax=ajax&r=" + r, function (data) {
            //codersClubCurrent(data);
            codersClubPrint("#current-song", data);
            $("#current-song ul").removeClass('hidden');
        });

    });

    // Prevent submit and load data from ajax
    $("#reportForm").on('submit', function(event, selector, data){
        event.preventDefault();
        var dateFrom = $('#reportForm :input#dateFrom').val();
        var dateTo = $('#reportForm :input#dateTo').val();
        var r = (-0.5) + (Math.random() * (1000.99));
        $.getJSON("lib/codersClub.php?key=report&dateFrom=" + dateFrom + "&dateTo=" + dateTo + "&ajax=ajax&r=" + r, function (data) {
            codersClubPrint("#reports", data);
            $("#reports").removeClass('hidden');
        });
    });
});