$(function(){
    initJs($('body'));

    // Main menu js
    initMainMenu();
    
    // Default jQuery Exception
    $( document ).ajaxError(function( event, request, settings ) {
        if(typeof(settings.globalError) != 'undefined' || settings.globalError == false) {
            return;
        }
        
        // abort ajax
        if (request.statusText =='abort') {
            console.log('User abort!');
            return;
        }

        if (request.responseText) {
            alert(request.responseText);
        } else {
            console.log(request);
        }
    });

    // Top quota button
    $(document).on('click', '.top-quota-button', function(e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        console.log(url);


        var quotaPopup = new Popup({
            url: url
        });
        
        quotaPopup.load();
    });
});