$(document).ready(function() {
   $("body").on("click", ".itemSelect", function() {
      var id = $(this).data('id');
      uniqueItem(id);
   });
   var listItems = [];
   function uniqueItem(element)
   {
      if($("input[data-select=" + element + "]").length == 0) {
         $(".listOfItems").append('<input class="activityObjects" name="activityItems[]" value="' + element + '" type="hidden" data-select="' + element + '">');
      } else {
         issetItemAndRemove(element);
      }
   }

   function issetItemAndRemove(element)
   {
      if($("input[data-select=" + element + "]").length == 1) {
         $("input[data-select=" + element + "]").remove();
      }
   }

   $("body").on("click", ".pager ul.yiiPager li", function() {
      setTimeout(function() {
         $.each($(".activityObjects"), function (index) {
            var idActivity = $(this).data('select');
            var objectActivity = $(".itemSelect[data-id=" + idActivity + "]");
            if (!objectActivity.is(":checked")) {
               objectActivity.prop("checked", true);
            }
         });
      }, 500);
   })

   $("body").on("click", ".second-context", function() {
      if($("input[data-select]").length == 0) {
         return false;
      }
      $(".listOfItems").submit();
      return true;
   });
   var html;
   $("body").on("click", ".htmlToWord" ,function() {
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

   $("body").on("click", ".htmlPreview" ,function() {
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


   $('input[name="daterange"]').daterangepicker({
      timePicker: false,
      locale: {
         format: 'YYYY/MM/DD'
      }
   });
});