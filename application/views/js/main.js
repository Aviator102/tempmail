particlesJS('main-wrap',
  
  {
    "particles": {
      "number": {
        "value": 60,
        "density": {
          "enable": true,
          "value_area": 800
        }
      },
      "color": {
        "value": "#ffffff"
      },
      "shape": {
        "type": "circle",
        "stroke": {
          "width": 0,
          "color": "#000000"
        },
        "polygon": {
          "nb_sides": 5
        },
        "image": {
          "src": "img/github.svg",
          "width": 100,
          "height": 100
        }
      },
      "opacity": {
        "value": 0.2,
        "random": false,
        "anim": {
          "enable": false,
          "speed": 1,
          "opacity_min": 0.1,
          "sync": false
        }
      },
      "size": {
        "value": 5,
        "random": true,
        "anim": {
          "enable": false,
          "speed": 40,
          "size_min": 0.1,
          "sync": false
        }
      },
      "line_linked": {
        "enable": true,
        "distance": 150,
        "color": "#ffffff",
        "opacity": 0.4,
        "width": 1
      },
      "move": {
        "enable": true,
        "speed": 6,
        "direction": "none",
        "random": false,
        "straight": false,
        "out_mode": "out",
        "attract": {
          "enable": false,
          "rotateX": 600,
          "rotateY": 1200
        }
      }
    },
    "interactivity": {
      "detect_on": "canvas",
      "events": {
        "onhover": {
          "enable": true,
          "mode": "grab"
        },
        "onclick": {
          "enable": true,
          "mode": "push"
        },
        "resize": true
      },
      "modes": {
        "grab": {
          "distance": 180,
          "line_linked": {
            "opacity": 1
          }
        },
        "bubble": {
          "distance": 400,
          "size": 40,
          "duration": 2,
          "opacity": 8,
          "speed": 3
        },
        "repulse": {
          "distance": 200
        },
        "push": {
          "particles_nb": 4
        },
        "remove": {
          "particles_nb": 2
        }
      }
    },
    "retina_detect": true
  }

);

$(document).ready(function() {
    'use strict';

    $(window).on("load resize ", function() {
      var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
      $('.tbl-header').css({'padding-right':scrollWidth});
    }).resize();

    var remove_true = 'remove=1';
    window.validNavigation = false;
    $(document).on("click", "li", function() {
     window.validNavigation = true;
    });

    $(window).on('beforeunload', function(){
        if(jQuery('.tbl-content').length > 0 && window.validNavigation == true) 
        {
          return true;
        }
    });

    $('.a-btn').click(function() {
        $('a').css({'cursor' : 'wait'});
        $(document.body).css({'cursor' : 'wait'});
    })

    if(jQuery('.tbl-content').length > 0) 
    {
        setInterval(function() {
          if(jQuery('.modal-backdrop:visible').length == 0) {
            $.ajax({ data: "ajax=1" }).done(function(response) {
                $(".lefted-div2").html($(response).find('.lefted-div2').html());
            });
          }
        }, 3800);

      $(window).on('unload', function(){
          $(document.body).css({'cursor' : 'wait'});
          $.ajax({ url: '../remove/?'+remove_true });
      });
    }
})