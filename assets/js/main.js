$(document).ready(function() {
   $(".itemSelect").on("click", function() {
      var id = $(this).data('id');
      uniqueItem(id);
   });

   function uniqueItem(element)
   {
      if($("input[data-select=" + element + "]").length == 0) {
         $(".listOfItems").append('<input name="activityItems[]" value="' + element + '" type="hidden" data-select="' + element + '">');
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

   $("body").on("click", ".second-context", function() {
      if($("input[data-select]").length == 0) {
         return false;
      }
      $(".listOfItems").submit();
      return true;
   });
});