/* Easy Elements Preloader */
( function( $ ) {
    'use strict';

    $( function() {

        var $preloader = $( '.easyel-preloader' );

        if ( ! $preloader.length ) {
            return;
        }

        var data        = ( typeof window.easyelPreloaderData !== 'undefined' ) ? window.easyelPreloaderData : {};
        var minTime     = parseInt( data.minTime, 10 );
        var fadeoutTime = parseInt( data.fadeoutTime, 10 );

        if ( isNaN( minTime ) ) {
            minTime = 500;
        }
        if ( isNaN( fadeoutTime ) ) {
            fadeoutTime = 600;
        }

        var startTime = Date.now();

        function easyelPreloaderHide() {

            var elapsed = Date.now() - startTime;
            var delay   = Math.max( 0, minTime - elapsed );

            setTimeout( function() {
                $preloader.addClass( 'easyel-preloader-hidden' );

                setTimeout( function() {
                    $preloader.remove();
                }, fadeoutTime );
            }, delay );
        }

        if ( document.readyState === 'complete' ) {
            easyelPreloaderHide();
        } else {
            $( window ).on( 'load', easyelPreloaderHide );
        }

        // Safety: never let preloader stay forever
        setTimeout( function() {
            if ( $preloader.length && ! $preloader.hasClass( 'easyel-preloader-hidden' ) ) {
                easyelPreloaderHide();
            }
        }, 15000 );

    } );

} )( jQuery );
