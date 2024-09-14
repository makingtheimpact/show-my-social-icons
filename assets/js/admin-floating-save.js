jQuery(document).ready(function($) {
    var $form = $('#smsi-settings-form');
    var $floatingSave = $('#smsi-floating-save');
    var formChanged = false;

    $form.on('change', 'input, select, textarea', function() {
        formChanged = true;
        $floatingSave.fadeIn();
    });

    $form.on('submit', function() {
        formChanged = false;
        $floatingSave.fadeOut();
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    $floatingSave.hide();
});
