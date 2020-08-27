$(document).ready(function() {
    $('.banner-slider').slick({
        autoplay: true,
        arrow: true,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<button class="prev"><i class="fa fa-angle-left"></i></button>',
        nextArrow: '<button class="next"><i class="fa fa-angle-right"></i></button>',
    });

    $('.partner-slider').slick({
        autoplay: true,
        arrow: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    vertical: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
        dots: true,
    });

    $('.pro-watched-slider').slick({
        autoplay: true,
        arrow: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ],
        dots: false,
        prevArrow: '<button class="prev"><i class="fa fa-angle-left"></i></button>',
        nextArrow: '<button class="next"><i class="fa fa-angle-right"></i></button>',
    });

    $('.teacher-slider').slick({
        autoplay: true,
        arrow: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ],
    });

    $('.feedback-slider').slick({
        autoplay: true,
        arrow: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
    });



    $("#menu").mmenu({
        "extensions": [
            "fx-menu-zoom"
        ],
        "counters": true
    });

    $('.btn-search').click(function () {
        $('.search-abs').slideToggle(300)
    })

    $('.current-select').click(function () {
        $(this).next('.dropdown-select').slideToggle(400)
    });
    $('.dropdown-select a').click(function (e) {
        e.preventDefault();
        $(this).closest('.sidebar-dropdown').find('.current-select').html($(this).text());
        $(this).closest('.dropdown-select').slideToggle(300)
    })
    $('.course-cate-title').click(function () {
        $(this).toggleClass('active');
        $(this).next('.course-cate-list').slideToggle(300)
    })

    $('.content-box').first().find('.content-list').show();
    $('.content-title').click(function () {
        $(this).toggleClass('active');
        $(this).next('.content-list').slideToggle(200)
    })

    $(document).ready(function () {
        $(document).on("scroll", onScroll);

        //smoothscroll
        $('.title-scroll a[href^="#"]').on('click', function (e) {
            e.preventDefault();
            $(document).off("scroll");

            $('a').each(function () {
                $(this).removeClass('active');
            })
            $(this).addClass('active');

            var target = this.hash,
                menu = target;
            $target = $(target);
            $('html, body').stop().animate({
                'scrollTop': $target.offset().top+2
            }, 500, 'swing', function () {
                window.location.hash = target;
                $(document).on("scroll", onScroll);
            });
        });
    });

    function onScroll(event){
        var scrollPos = $(document).scrollTop();
        $('.title-scroll a').each(function () {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                $('.title-scroll ul li a').removeClass("active");
                currLink.addClass("active");
            }
            else{
                currLink.removeClass("active");
            }
        });
    }
    AOS.init({
        duration: 1200,
    });
    if ($('#back-to-top').length) {
        var scrollTrigger = 600, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('show');
                } else {
                    $('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('#back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }
})
