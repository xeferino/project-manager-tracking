  'use strict';
  $(window).on('load', function() {
      //Welcome Message (not for login page)
      $(window).on('load', function() {
          //Welcome Message (not for login page)
          function notify(message, type, time) {
              $.growl({
                  message: message
              }, {
                  type: type,
                  allow_dismiss: false,
                  label: 'Cancel',
                  placement: {
                      from: 'top',
                      align: 'right'
                  },
                  delay: time,
                  animate: {
                      enter: 'animated fadeInRight',
                      exit: 'animated fadeOutRight'
                  },
                  offset: {
                      x: 30,
                      y: 30
                  }
              });
          };
      });
  });