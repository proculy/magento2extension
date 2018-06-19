require([
    'jquery',
    'jquery/validate',
    'domReady!'
], function ($) {
    $('input[type="range"].progress_class').rules('add', {
        range: [0, 100]
    });

    $('input[type="range"].progress_class').val(
        function() {
            var progressVal = $('#task_progress').val();
            if (null == progressVal || "" == progressVal) {
                progressVal = 0;
                $('#task_progress').val(0);
            }

            return progressVal;
        }
    );

    $('input[type="range"].progress_class').on('change',
        function() {
            $('#task_progress').val($('input[type="range"].progress_class').val());
        }
    );
});
