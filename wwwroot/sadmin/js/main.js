$(document).ready(function() {
  initSearch();
});

function initSearch() {
  var $search = $('#search');
  $search.focus(function(e) {
    $search.val('');
  });
  $search.keyup(function(e) {
    var query = $search.val();
    $.post('/sadmin/Search/search', {query:query}, function(data) {
      $('#body').html(data);
    });
  });
}
