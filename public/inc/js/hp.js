$(document).ready(function() {

$(function () {
  $('#id_dest').each(function () {
    $(this).select2({
      theme: 'bootstrap4',
      placeholder: $(this).attr('placeholder'),
      allowClear: Boolean($(this).data('allow_clear')),
    });
  });
});

});
