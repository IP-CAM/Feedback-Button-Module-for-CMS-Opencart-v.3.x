$(document).ready(function () {

    /**
     * BUTTON POSITION BLOCK
     */
    if ($('#button-position option').filter(':selected').val() !== 'custom-settings') {
        $(".custom-position-section").hide();
    }

    $("#button-position").change(function () {
        if ($(this).val() === 'custom-settings') {
            $(".custom-position-section").show();
        } else {
            $(".custom-position-section").hide();
        }
    });

    /**
     * TEXT IMAGE BLOCK
     */
    if ($("#show-image").is(":checked")) {
        $(".button-image-section").show();
        $(".button-text-section").hide();
    } else {
        $(".button-image-section").hide();
        $(".button-text-section").show();
    }

    $('#show-image').change(function() {
        if(this.checked) {
            $(".button-image-section").show();
            $(".button-text-section").hide();
        } else {
            $(".button-image-section").hide();
            $(".button-text-section").show();
        }
    });
});