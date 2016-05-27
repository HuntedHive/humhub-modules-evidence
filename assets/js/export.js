$(document).ready(function() {
    $(".buttonSaveExport").on("click", function() {
        if($("input[data-select]").length == 0) {
            $("#selectContributions").modal('show');
            return false;
        }

        var url = $("#formSaveExport").attr('action');
        var data = $("#formSaveExport").serialize();
        var requestData = {'exportData': data, 'html':$(".contentListOfItems").html()};
        $.ajax({
            url: url,
            data: requestData,
            type: 'POST',
            success: function(data) {
                var response = JSON.parse(data);
                if(response.flag) {
                    console.log(response.redirect);
                    window.location.href = response.redirect;
                } else {
                    $("#formSaveExport .displayErrors").empty();
                    $("#formSaveExport .displayErrors").append("<p>"+response.error.name+"</p>");
                }
            }
        });

        return false;
    });

    $(".loadExport").on("click", function() {
        var exportId = $(".loadExportSelect").val();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            data: {'exportId': exportId, "CSRF": "b1b12323333qweqw"},
            type: 'POST',
            success: function(data) {
                var response = JSON.parse(data);
                if(response.flag) {
                    console.log(response.redirect);
                    window.location.href = response.redirect;
                } else {
                    $("#exportLoad .displayErrors").empty();
                    $("#exportLoad .displayErrors").append("<p>"+response.error.name+"</p>");
                }
            }
        });
    });
});