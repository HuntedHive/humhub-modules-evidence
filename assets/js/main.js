$(document).ready(function() {
   $("body").on("click", ".itemSelect", function() {
      var id = $(this).data('id');
      uniqueItem(id, $(this));
   });
   var listItems = [];
   function uniqueItem(element, currentElement)
   {
      var dataType = currentElement.data('type');
      var dataId = currentElement.data('id');
      var objectActivity = $("input[data-select="+dataId+"][data-type="+dataType+"]");

      if(objectActivity.length == 0) {
         $(".contentListOfItems").append('<input class="activityObjects" name="activityItems['+currentElement.data('type')+'][]" value="' + element + '" type="hidden" data-type="'+currentElement.data('type')+'" data-select="' + element + '">');
      } else {
         issetItemAndRemove(objectActivity);
      }
   }

   function issetItemAndRemove(objectActivity)
   {
      if(objectActivity.length == 1) {
         objectActivity.remove();
      }
   }

   $(document).on("click", ".pagination li", function() { // check list on pagination
      setTimeout(function() {
         $.each($(".activityObjects"), function (index) {
            var dataType = $(this).data('type');
            var dataId = $(this).data('select');
            var objectActivity = $("input[data-id="+dataId+"][data-type="+dataType+"]");
            if (!objectActivity.is(":checked")) {
                  objectActivity.prop("checked", true);
            }
         });
      }, 500);
   });

   setTimeout(function() {
      $.each($(".activityObjects"), function (index) {
         var dataType = $(this).data('type');
         var dataId = $(this).data('select');
         var objectActivity = $("input[data-id="+dataId+"][data-type="+dataType+"]");
         if (!objectActivity.is(":checked")) {
            objectActivity.prop("checked", true);
         }
      });
   }, 500);


   var html;
   $("body").on("click", ".htmlToWord" ,function() { //second step convert html to docx
      html='';
      $.each($(".check-item"), function(index) {
         if($(this).is(":checked")) {
            var parentElement = $(this).parents(".block-item").clone();
            parentElement.find(".check-item").remove();
            html += parentElement.html();
         }
      });

      $.ajax({
         type: 'POST',
         url: url,
         data: {'html':html, 'CSRF_TOKEN':token},
         success: function(data) {
            console.log(data);
         }
      });
   });

   $("body").on("click", ".htmlPreview" ,function() { //second step preview html to docx
      html='';
      $.each($(".check-item"), function(index) {
         if($(this).is(":checked")) {
            var parentElement = $(this).parents(".block-item").clone();
            parentElement.find(".check-item").remove();
            html += parentElement.html();
         }
      });
      $(".previewModal .modal-body").empty();
      $(".previewModal .modal-body").append(html);
      $(".previewModal").modal('show');
   });


    function updatepicker(start, end, label) {
        $('input[name="date_from"]').val(start.format('YYYY-MM-DD'));
        $('input[name="date_to"]').val(end.format('YYYY-MM-DD'));
    }

    $('input[name="daterange"]').daterangepicker({ // set datapicker on first step
        locale: {
            format: 'DD MMMM YY',
            separator: ' to '
        },
        "autoApply": true,
        "linkedCalendars": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },

    }, updatepicker);
    updatepicker($('input[name="daterange"]').data('daterangepicker').startDate, $('input[name="daterange"]').data('daterangepicker').endDate, '');
});