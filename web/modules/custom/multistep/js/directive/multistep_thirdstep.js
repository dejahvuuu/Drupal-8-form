(function ($, Drupal) {
  Drupal.behaviors.multistep = {
    attach: function (context, settings) {
      setTimeout(function () {
        window.location.href =
          window.location.protocol +
          "//" +
          window.location.hostname +
          drupalSettings.url_redirect;
      }, 4000);
    },
  };
})(jQuery, Drupal);
