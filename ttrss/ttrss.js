ttrss = function(user,pass,path) {
  if (pass != '') {
    $.ajax({
      'url': '/rss/public.php?op=logout',
      'type':'GET',
      'success': function(output, status, xhr) {
        $.ajax({
          'url': '/rss/public.php',
          'data': { 'login': user, 'password': pass, 'op': 'login', 'profile0': 0 },
          'type':'POST',
          'complete': function(output, status, xhr) {
            $('#rss_frame').prop('src','/rss/');
          }
        });
      }
    });
  } else {
    $('#rss_frame').prop('src',path);
  }
  return false;
};

ttrss_logout = function(path) {
  $.ajax({
    'url': path + '/public.php?op=logout',
    'type':'GET'
  });
};
