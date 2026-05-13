(function($){
    $(document).on('click', '.eel-offcanvas-toggle', function(e){
        e.stopPropagation();
        var target = $(this).data('target');
        var $offcanvas = $(target);
        if (!$offcanvas.data('initialized')) {
            $offcanvas.data('initialized', true);
            if (!$offcanvas.hasClass('modern')) {
                if ($offcanvas.hasClass('eel-offcanvas-left')) {
                    $offcanvas.css('transform', 'translateX(-100%)');
                } else {
                    $offcanvas.css('transform', 'translateX(100%)');
                }
            } else {
                $offcanvas.css({'visibility':'hidden','opacity':'0'});
            }
        }

        // toggle active
        if ($offcanvas.hasClass('active')) {
            $offcanvas.removeClass('active');
            $('body').removeClass('eel-offcanvas-active');
            
            if (!$offcanvas.hasClass('modern')) {
                if ($offcanvas.hasClass('eel-offcanvas-left')) {
                    $offcanvas.css('transform', 'translateX(-100%)');
                } else {
                    $offcanvas.css('transform', 'translateX(100%)');
                }
            } else {
                $offcanvas.css({'visibility':'hidden','opacity':'0'});
            }
        } else {
            $('.eel-offcanvas').each(function(){
                var $other = $(this);
                if ($other[0] !== $offcanvas[0]) {
                    $other.removeClass('active');
                    if (!$other.hasClass('modern')) {
                        if ($other.hasClass('eel-offcanvas-left')) {
                            $other.css('transform','translateX(-100%)');
                        } else {
                            $other.css('transform','translateX(100%)');
                        }
                    } else {
                        $other.css({'visibility':'hidden','opacity':'0'});
                    }
                }
            });

            $offcanvas.addClass('active');
            $('body').addClass('eel-offcanvas-active');

            if (!$offcanvas.hasClass('modern')) {
                $offcanvas.css('transform','translateX(0)');
            } else {
                $offcanvas.css({'visibility':'visible','opacity':'1'});
            }
        }
    });

    $(document).on('click', function(){
        var $active = $('.eel-offcanvas.active');
        if ($active.length) {
            $active.removeClass('active');
            $('body').removeClass('eel-offcanvas-active');

            if (!$active.hasClass('modern')) {
                if ($active.hasClass('eel-offcanvas-left')) {
                    $active.css('transform','translateX(-100%)');
                } else {
                    $active.css('transform','translateX(100%)');
                }
            } else {
                $active.css({'visibility':'hidden','opacity':'0'});
            }
        }
    });

    $(document).on('click', '.eel-offcanvas', function(e){
        e.stopPropagation();
    });

})(jQuery);
