function isScrolledIntoView(el) {
   // Source: https://stackoverflow.com/questions/46651373/disable-materialize-css-modal-footer-button
   var rect = el.getBoundingClientRect();
   var elemTop = rect.top;
   var elemBottom = rect.bottom;

   // Only completely visible elements return true:
   // var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
   // Partially visible elements return true:
   var isVisible = elemTop < window.innerHeight && elemBottom >= 0;
   return isVisible;
}

$("a[href='#top']").click(function () {
   $("html, body").animate({ scrollTop: 0 }, "fast");
   return false;
});

$(window).scroll(function () {
   if (!isScrolledIntoView(document.getElementsByClassName('footer-copyright')[0]) && $(this).scrollTop() > 300) {
      $(".scroll-to-top").removeClass("scale-out");
   } else {
      $(".scroll-to-top").addClass("scale-out");
   }
});