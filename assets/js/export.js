$(document).ready(function() {
    $(".buttonSaveExport").on("click", function() {
        if($("input[data-select]").length == 0) {
            $("#selectContributions").modal('show');
            return false;
        }

        var url = $("#formSaveExport").attr('action');
        var data = $("#formSaveExport").serializeArray();
        $.ajax({
            url: url,
            data: {'step1' : $(".listOfItems").html()},
            type: 'POST',
            success: function(data) {
                console.log(data);return false;
                window.location.href = url;
            }
        });

        return false;
    });
});