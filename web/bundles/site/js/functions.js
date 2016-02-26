 
var $ = jQuery.noConflict();

$.fn.inlineStyle = function (prop) {
    return this.prop("style")[$.camelCase(prop)];
};

$.fn.doOnce = function( func ) {
    this.length && func.apply( this );
    return this;
}

$.extend($.infinitescroll.prototype,{
    _setup_portfolioinfiniteitemsloader: function infscr_setup_portfolioinfiniteitemsloader() {
        var opts = this.options,
            instance = this;
        // Bind nextSelector link to retrieve
        $(opts.nextSelector).click(function(e) {
            if (e.which == 1 && !e.metaKey && !e.shiftKey) {
                e.preventDefault();
                instance.retrieve();
            }
        });
        // Define loadingStart to never hide pager
        instance.options.loading.start = function (opts) {
            opts.loading.msg
                .appendTo(opts.loading.selector)
                .show(opts.loading.speed, function () {
                    instance.beginAjax(opts);
                });
        }
    },
    _showdonemsg_portfolioinfiniteitemsloader: function infscr_showdonemsg_portfolioinfiniteitemsloader () {
        var opts = this.options,
            instance = this;
        //Do all the usual stuff
        opts.loading.msg
            .find('img')
            .hide()
            .parent()
            .find('div').html(opts.loading.finishedMsg).animate({ opacity: 1 }, 2000, function () {
                $(this).parent().fadeOut('normal');
            });
        //And also hide the navSelector
        $(opts.navSelector).fadeOut('normal');
        // user provided callback when done
        opts.errorCallback.call($(opts.contentSelector)[0],'done');
    }
});

var SEMICOLON = SEMICOLON || {};

(function($){

    // USE STRICT
    "use strict";

    SEMICOLON.initialize = {

        init: function(){

            SEMICOLON.initialize.responsiveClasses();
            SEMICOLON.initialize.imagePreload( '.portfolio-item:not(:has(.fslider)) img' );
            SEMICOLON.initialize.stickyElements();
            SEMICOLON.initialize.goToTop();
            SEMICOLON.initialize.fullScreen();
            SEMICOLON.initialize.verticalMiddle();
            SEMICOLON.initialize.lightbox();
            SEMICOLON.initialize.resizeVideos();
            SEMICOLON.initialize.imageFade();
            SEMICOLON.initialize.pageTransition();
            SEMICOLON.initialize.dataStyles();
            SEMICOLON.initialize.dataResponsiveHeights();

            $('.fslider').addClass('preloader2');

        },

        responsiveClasses: function(){
            var jRes = jRespond([
                {
                    label: 'smallest',
                    enter: 0,
                    exit: 479
                },{
                    label: 'handheld',
                    enter: 480,
                    exit: 767
                },{
                    label: 'tablet',
                    enter: 768,
                    exit: 991
                },{
                    label: 'laptop',
                    enter: 992,
                    exit: 1199
                },{
                    label: 'desktop',
                    enter: 1200,
                    exit: 10000
                }
            ]);
            jRes.addFunc([
                {
                    breakpoint: 'desktop',
                    enter: function() { $body.addClass('device-lg'); },
                    exit: function() { $body.removeClass('device-lg'); }
                },{
                    breakpoint: 'laptop',
                    enter: function() { $body.addClass('device-md'); },
                    exit: function() { $body.removeClass('device-md'); }
                },{
                    breakpoint: 'tablet',
                    enter: function() { $body.addClass('device-sm'); },
                    exit: function() { $body.removeClass('device-sm'); }
                },{
                    breakpoint: 'handheld',
                    enter: function() { $body.addClass('device-xs'); },
                    exit: function() { $body.removeClass('device-xs'); }
                },{
                    breakpoint: 'smallest',
                    enter: function() { $body.addClass('device-xxs'); },
                    exit: function() { $body.removeClass('device-xxs'); }
                }
            ]);
        },

        imagePreload: function(selector, parameters){
            var params = {
                delay: 250,
                transition: 400,
                easing: 'linear'
            };
            $.extend(params, parameters);

            $(selector).each(function() {
                var image = $(this);
                image.css({visibility:'hidden', opacity: 0, display:'block'});
                image.wrap('<span class="preloader" />');
                image.one("load", function(evt) {
                    $(this).delay(params.delay).css({visibility:'visible'}).animate({opacity: 1}, params.transition, params.easing, function() {
                        $(this).unwrap('<span class="preloader" />');
                    });
                }).each(function() {
                    if(this.complete) $(this).trigger("load");
                });
            });
        },

        verticalMiddle: function(){
            if( $verticalMiddleEl.length > 0 ) {
                $verticalMiddleEl.each( function(){
                    var element = $(this),
                        verticalMiddleH = element.outerHeight();

                    if( element.parents('#slider').length > 0 ) {
                        if( $header.hasClass('transparent-header') && ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) ) {
                            verticalMiddleH = verticalMiddleH - 70;
                            if( $slider.next('#header').length > 0 ) { verticalMiddleH = verticalMiddleH + 100; }
                        }
                    }

                    if( $body.hasClass('device-xs') || $body.hasClass('device-xxs') ) {
                        if( element.parents('.full-screen').length && !element.parents('.force-full-screen').length ){
                            element.css({ position: 'relative', top: '0', width: 'auto', marginTop: '0', padding: '60px 0' }).addClass('clearfix');
                        } else {
                            element.css({ position: 'absolute', top: '50%', width: '100%', marginTop: -(verticalMiddleH/2)+'px' });
                        }
                    } else {
                        element.css({ position: 'absolute', top: '50%', width: '100%', marginTop: -(verticalMiddleH/2)+'px' });
                    }
                });
            }
        },

        stickyElements: function(){
            if( $siStickyEl.length > 0 ) {
                var siStickyH = $siStickyEl.outerHeight();
                $siStickyEl.css({ marginTop: -(siStickyH/2)+'px' });
            }

            if( $dotsMenuEl.length > 0 ) {
                var opmdStickyH = $dotsMenuEl.outerHeight();
                $dotsMenuEl.css({ marginTop: -(opmdStickyH/2)+'px' });
            }
        },

        goToTop: function(){
            $goToTopEl.click(function() {
                $('body,html').stop(true).animate({scrollTop:0},400);
                return false;
            });
        },

        goToTopScroll: function(){
            if($window.scrollTop() > 450) {
                $goToTopEl.fadeIn();
            } else {
                $goToTopEl.fadeOut();
            }
        },

        fullScreen: function(){
            if( $fullScreenEl.length > 0 ) {
                $fullScreenEl.each( function(){
                    var element = $(this),
                        scrHeight = $window.height();
                    if( element.attr('id') == 'slider' ) {
                        var sliderHeightOff = $slider.offset().top;
                        scrHeight = scrHeight - sliderHeightOff;
                        if( $('#slider.with-header').next('#header:not(.transparent-header)').length > 0 && ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) ) {
                            var headerHeightOff = $header.outerHeight();
                            scrHeight = scrHeight - headerHeightOff;
                        }
                    }
                    if( element.parents('.full-screen').length > 0 ) { scrHeight = element.parents('.full-screen').height(); }

                    if( $body.hasClass('device-xs') || $body.hasClass('device-xxs') ) {
                        if( !element.hasClass('force-full-screen') ){ scrHeight = 'auto'; }
                    }

                    element.css('height', scrHeight);
                    if( element.attr('id') == 'slider' && !element.hasClass('canvas-slider-grid') ) { if( element.has('.swiper-slide') ) { element.find('.swiper-slide').css('height', scrHeight); } }
                });
            }
        },

        maxHeight: function(){
            if( $commonHeightEl.length > 0 ) {
                $commonHeightEl.each( function(){
                    var parentEl = $(this),
                        maxHeight = 0;
                    parentEl.children('[class^=col-]').each(function() {
                        var element = $(this).children('div');
                        if (element.outerHeight() > maxHeight)
                            maxHeight = element.outerHeight();
                    });

                    parentEl.children('[class^=col-]').each(function() {
                        $(this).height(maxHeight);
                    });
                });
            }
        },

        testimonialsGrid: function(){
            if( $testimonialsGridEl.length > 0 ) {
                if( $body.hasClass('device-sm') || $body.hasClass('device-md') || $body.hasClass('device-lg') ) {
                    var maxHeight = 0;
                    $testimonialsGridEl.each( function(){
                        $(this).find("li > .testimonial").each(function(){
                           if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
                        });
                        $(this).find("li").height(maxHeight);
                        maxHeight = 0;
                    });
                } else {
                    $testimonialsGridEl.find("li").css({ 'height': 'auto' });
                }
            }
        },

        lightbox: function(){
            if( $lightboxImageEl.length > 0 ) {
                $lightboxImageEl.magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    closeBtnInside: false,
                    fixedContentPos: true,
                    mainClass: 'mfp-no-margins mfp-fade', // class to remove default margin from left and right side
                    image: {
                        verticalFit: true
                    }
                });
            }

            if( $lightboxGalleryEl.length > 0 ) {
                $lightboxGalleryEl.each(function() {
                    var element = $(this);

                    if( element.find('a[data-lightbox="gallery-item"]').parent('.clone').hasClass('clone') ) {
                        element.find('a[data-lightbox="gallery-item"]').parent('.clone').find('a[data-lightbox="gallery-item"]').attr('data-lightbox','');
                    }

                    element.magnificPopup({
                        delegate: 'a[data-lightbox="gallery-item"]',
                        type: 'image',
                        closeOnContentClick: true,
                        closeBtnInside: false,
                        fixedContentPos: true,
                        mainClass: 'mfp-no-margins mfp-fade', // class to remove default margin from left and right side
                        image: {
                            verticalFit: true
                        },
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                        }
                    });
                });
            }

            if( $lightboxIframeEl.length > 0 ) {
                $lightboxIframeEl.magnificPopup({
                    disableOn: 600,
                    type: 'iframe',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }

            if( $lightboxAjaxEl.length > 0 ) {
                $lightboxAjaxEl.magnificPopup({
                    type: 'ajax',
                    closeBtnInside: false,
                    callbacks: {
                        ajaxContentAdded: function(mfpResponse) {
                            SEMICOLON.widget.loadFlexSlider();
                            SEMICOLON.initialize.resizeVideos();
                            SEMICOLON.widget.masonryThumbs();
                        }
                    }
                });
            }
        },

        resizeVideos: function(){
            if ( $().fitVids ) {
                $("#content,#footer,#slider:not(.revslider-wrap),.landing-offer-media,.portfolio-ajax-modal").fitVids({
                    customSelector: "iframe[src^='http://www.dailymotion.com/embed']",
                    ignore: '.no-fv'
                });
            }
        },

        imageFade: function(){
            $('.image_fade').hover( function(){
                $(this).filter(':not(:animated)').animate({opacity: 0.8}, 400);
            }, function() {
                $(this).animate({opacity: 1}, 400);
            });
        },

        pageTransition: function(){
            $wrapper.animsition({
                inClass : 'fade-in',
                outClass : 'fade-out',
                inDuration : 1500,
                outDuration : 800,
                linkElement : '#primary-menu ul li a:not([target="_blank"]):not([href^=#])',
                loading : true,
                loadingParentElement : 'body',
                loadingClass : 'css3-spinner',
                unSupportCss : [
                                 'animation-duration',
                                 '-webkit-animation-duration',
                                 '-o-animation-duration'
                               ],
                overlay : false,
                overlayClass : 'animsition-overlay-slide',
                overlayParentElement : 'body'
            });
        },

        topScrollOffset: function() {
            var topOffsetScroll = 0;

            if( ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) && !SEMICOLON.isMobile.any() ) {
                if( $header.hasClass('sticky-header') ) {
                    if( $pagemenu.hasClass('dots-menu') ) { topOffsetScroll = 100; } else { topOffsetScroll = 144; }
                } else {
                    if( $pagemenu.hasClass('dots-menu') ) { topOffsetScroll = 140; } else { topOffsetScroll = 184; }
                }

                if( !$pagemenu.length ) {
                    if( $header.hasClass('sticky-header') ) { topOffsetScroll = 100; } else { topOffsetScroll = 140; }
                }
            } else {
                topOffsetScroll = 40;
            }

            return topOffsetScroll;
        },

        defineColumns: function( element ){
            var column = 4;

            if( element.hasClass('portfolio-full') ) {
                if( element.hasClass('portfolio-3') ) column = 3;
                else if( element.hasClass('portfolio-5') ) column = 5;
                else if( element.hasClass('portfolio-6') ) column = 6;
                else column = 4;

                if( $body.hasClass('device-sm') && ( column == 4 || column == 5 || column == 6 ) ) {
                    column = 3;
                } else if( $body.hasClass('device-xs') && ( column == 3 || column == 4 || column == 5 || column == 6 ) ) {
                    column = 2;
                } else if( $body.hasClass('device-xxs') ) {
                    column = 1;
                }
            } else if( element.hasClass('masonry-thumbs') ) {
                if( element.hasClass('col-2') ) column = 2;
                else if( element.hasClass('col-3') ) column = 3;
                else if( element.hasClass('col-5') ) column = 5;
                else if( element.hasClass('col-6') ) column = 6;
                else column = 4;
            }

            return column;
        },

        setFullColumnWidth: function( element ){

            if( element.hasClass('portfolio-full') ) {
                var columns = SEMICOLON.initialize.defineColumns( element );
                var containerWidth = element.width();
                if( containerWidth == ( Math.floor(containerWidth/columns) * columns ) ) { containerWidth = containerWidth - 1; }
                var postWidth = Math.floor(containerWidth/columns);
                if( $body.hasClass('device-xxs') ) { var deviceSmallest = 1; } else { var deviceSmallest = 0; }
                element.find(".portfolio-item").each(function(index){
                    if( deviceSmallest == 0 && $(this).hasClass('wide') ) { var elementSize = ( postWidth*2 ); } else { var elementSize = postWidth; }
                    $(this).css({"width":elementSize+"px"});
                });
            } else if( element.hasClass('masonry-thumbs') ) {
                var columns = SEMICOLON.initialize.defineColumns( element ),
                    containerWidth = element.innerWidth(),
                    windowWidth = $window.width();
                if( containerWidth == windowWidth ){
                    containerWidth = windowWidth*1.004;
                    element.css({ 'width': containerWidth+'px' });
                }
                var postWidth = (containerWidth/columns);

                postWidth = Math.floor(postWidth);

                if( ( postWidth * columns ) >= containerWidth ) { element.css({ 'margin-right': '-1px' }); }

                element.children('a').css({"width":postWidth+"px"});

                var bigImageNumber = element.attr('data-big');
                if( bigImageNumber ) { bigImageNumber = Number(bigImageNumber) - 1; }
                var firstElementWidth = element.find('a:eq(0)').outerWidth();

                element.isotope({
                    masonry: {
                        columnWidth: firstElementWidth
                    }
                });

                if( $.isNumeric( bigImageNumber ) ) {
                    var t = setTimeout( function(){
                        element.find('a:eq('+bigImageNumber+')').css({ width: firstElementWidth*2 + 'px' });
                        element.isotope('layout');
                    }, 1000 );
                }
            }

        },

        aspectResizer: function(){
            $('.aspect-resizer').each( function(){
                var element = $(this),
                    elementW = element.inlineStyle('width'),
                    elementH = element.inlineStyle('height'),
                    elementPW = element.parent().innerWidth();
            });
        },

        dataStyles: function(){
            $('[data-style-xs]').each( function(){
                var element = $(this),
                    elementStyleXs = element.attr('data-style-xs');

                if( $body.hasClass('device-xs') || $body.hasClass('device-xxs') ) {
                    if( elementStyleXs != '' ) { element.attr( 'style', elementStyleXs ); }
                }
            });

            $('[data-style-sm]').each( function(){
                var element = $(this),
                    elementStyleSm = element.attr('data-style-sm');

                if( $body.hasClass('device-sm') ) {
                    if( elementStyleSm != '' ) { element.attr( 'style', elementStyleSm ); }
                }
            });

            $('[data-style-md]').each( function(){
                var element = $(this),
                    elementStyleMd = element.attr('data-style-md');

                if( $body.hasClass('device-md') ) {
                    if( elementStyleMd != '' ) { element.attr( 'style', elementStyleMd ); }
                }
            });

            $('[data-style-lg]').each( function(){
                var element = $(this),
                    elementStyleLg = element.attr('data-style-lg');

                if( $body.hasClass('device-lg') ) {
                    if( elementStyleLg != '' ) { element.attr( 'style', elementStyleLg ); }
                }
            });
        },

        dataResponsiveHeights: function(){
            $('[data-height-xxs]').each( function(){
                var element = $(this),
                    elementHeightXxs = element.attr('data-height-xxs');

                if( $body.hasClass('device-xxs') ) {
                    if( elementHeightXxs != '' ) { element.css( 'height', elementHeightXxs ); }
                }
            });

            $('[data-height-xs]').each( function(){
                var element = $(this),
                    elementHeightXs = element.attr('data-height-xs');

                if( $body.hasClass('device-xs') ) {
                    if( elementHeightXs != '' ) { element.css( 'height', elementHeightXs ); }
                }
            });

            $('[data-height-sm]').each( function(){
                var element = $(this),
                    elementHeightSm = element.attr('data-height-sm');

                if( $body.hasClass('device-sm') ) {
                    if( elementHeightSm != '' ) { element.css( 'height', elementHeightSm ); }
                }
            });

            $('[data-height-md]').each( function(){
                var element = $(this),
                    elementHeightMd = element.attr('data-height-md');

                if( $body.hasClass('device-md') ) {
                    if( elementHeightMd != '' ) { element.css( 'height', elementHeightMd ); }
                }
            });

            $('[data-height-lg]').each( function(){
                var element = $(this),
                    elementHeightLg = element.attr('data-height-lg');

                if( $body.hasClass('device-lg') ) {
                    if( elementHeightLg != '' ) { element.css( 'height', elementHeightLg ); }
                }
            });
        }

    };

    SEMICOLON.header = {

        init: function(){

            SEMICOLON.header.superfish();
            SEMICOLON.header.menufunctions();
            SEMICOLON.header.fullWidthMenu();
            SEMICOLON.header.stickyMenu();
            SEMICOLON.header.sideHeader();
            SEMICOLON.header.onePageScroll();
            SEMICOLON.header.onepageScroller();
            SEMICOLON.header.darkLogo();
            SEMICOLON.header.topsearch();
            SEMICOLON.header.topcart();
            SEMICOLON.header.topsocial();

        },

        superfish: function(){

            if ( $().superfish ) {
                $('#primary-menu ul ul, #primary-menu ul .mega-menu-content').css('display', 'block');
                SEMICOLON.header.menuInvert();

                $('body:not(.side-header) #primary-menu > ul, body:not(.side-header) #primary-menu > div > ul,.top-links > ul').superfish({
                    popUpSelector: 'ul,.mega-menu-content,.top-link-section',
                    delay: 250,
                    speed: 350,
                    animation: {opacity:'show'},
                    animationOut:  {opacity:'hide'},
                    cssArrows: false
                });

                $('body.side-header #primary-menu > ul').superfish({
                    popUpSelector: 'ul',
                    delay: 250,
                    speed: 350,
                    animation: {opacity:'show',height:'show'},
                    animationOut:  {opacity:'hide',height:'hide'},
                    cssArrows: false
                });
            }

        },

        menuInvert: function() {

            $('#primary-menu .mega-menu-content, #primary-menu ul ul').each( function( index, element ){
                var $menuChildElement = $(element);
                var windowWidth = $window.width();
                var menuChildOffset = $menuChildElement.offset();
                var menuChildWidth = $menuChildElement.width();
                var menuChildLeft = menuChildOffset.left;

                if(windowWidth - (menuChildWidth + menuChildLeft) < 0) {
                    $menuChildElement.addClass('menu-pos-invert');
                }
            });

        },

        menufunctions: function(){

            $( '#primary-menu ul li:has(ul)' ).addClass('sub-menu');
            $( '.top-links ul li:has(ul) > a' ).append( ' <i class="icon-angle-down"></i>' );
            $( '.top-links > ul' ).addClass( 'clearfix' );

            if( $body.hasClass('device-lg') || $body.hasClass('device-md') ) {
                $('#primary-menu.sub-title > ul > li,#primary-menu.sub-title > div > ul > li').hover(function() {
                    $(this).prev().css({ backgroundImage : 'none' });
                }, function() {
                    $(this).prev().css({ backgroundImage : 'url("images/icons/menu-divider.png")' });
                });

                $('#primary-menu.sub-title').children('ul').children('.current').prev().css({ backgroundImage : 'none' });
                $('#primary-menu.sub-title').children('div').children('ul').children('.current').prev().css({ backgroundImage : 'none' });
            }

        },

        fullWidthMenu: function(){
            if( $body.hasClass('stretched') ) {
                if( $header.find('.container-fullwidth').length > 0 ) { $('.mega-menu .mega-menu-content').css({ 'width': $wrapper.width() - 120 }); }
                if( $header.hasClass('full-header') ) { $('.mega-menu .mega-menu-content').css({ 'width': $wrapper.width() - 60 }); }
            } else {
                if( $header.find('.container-fullwidth').length > 0 ) { $('.mega-menu .mega-menu-content').css({ 'width': $wrapper.width() - 120 }); }
                if( $header.hasClass('full-header') ) { $('.mega-menu .mega-menu-content').css({ 'width': $wrapper.width() - 80 }); }
            }
        },

        stickyMenu: function( headerOffset ){
            if( ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) && !SEMICOLON.isMobile.any() ) {
                if ($window.scrollTop() > headerOffset) {
                    $('body:not(.side-header) #header:not(.no-sticky)').addClass('sticky-header');
                    $('#page-menu:not(.dots-menu,.no-sticky)').addClass('sticky-page-menu');
                    if( !$headerWrap.hasClass('force-not-dark') ) { $headerWrap.removeClass('not-dark'); }
                    SEMICOLON.header.stickyMenuClass();
                } else {
                    SEMICOLON.header.removeStickyness();
                }
            } else {
                SEMICOLON.header.removeStickyness();
            }
        },

        removeStickyness: function(){
            if( $header.hasClass('sticky-header') ){
                $('body:not(.side-header) #header:not(.no-sticky)').removeClass('sticky-header');
                $('#page-menu:not(.dots-menu,.no-sticky)').removeClass('sticky-page-menu');
                $header.removeClass().addClass(oldHeaderClasses);
                $headerWrap.removeClass().addClass(oldHeaderWrapClasses);
                if( !$headerWrap.hasClass('force-not-dark') ) { $headerWrap.removeClass('not-dark'); }
                SEMICOLON.slider.swiperSliderMenu();
                SEMICOLON.slider.revolutionSliderMenu();
            }
        },

        sideHeader: function(){
            $("#header-trigger").click(function(){
                $('body.open-header').toggleClass("side-header-open");
                return false;
            });
        },

        onePageScroll: function(){
            $(".one-page-menu a[data-href]").click(function(){
                var divScrollToAnchor = $(this).attr('data-href');

                if( $( divScrollToAnchor ).length > 0 ) {
                    var topOffsetScroll = SEMICOLON.initialize.topScrollOffset();

                    if( $(".one-page-menu").hasClass('no-offset') ) { topOffsetScroll = 0; }

                    $( '.one-page-menu li' ).removeClass('current');
                    $( '.one-page-menu li a[data-href="' + divScrollToAnchor + '"]' ).parent('li').addClass('current');

                    $('html,body').stop(true).animate({
                        'scrollTop': $( divScrollToAnchor ).offset().top - topOffsetScroll
                    }, 1000, 'easeOutQuad');
                }

                return false;
            });
        },

        onepageScroller: function(){
            $( '.one-page-menu li' ).removeClass('current');
            $( '.one-page-menu li a[data-href="#' + SEMICOLON.header.onePageCurrentSection() + '"]' ).parent('li').addClass('current');
        },

        onePageCurrentSection: function(){
            var currentOnePageSection = 'home';

            $(".page-section").each(function(index) {
                var h = $(this).offset().top;
                var y = $window.scrollTop();

                if( y + 150 >= h && y < h + $(this).height() && $(this).attr('id') != currentOnePageSection ) {
                    currentOnePageSection = $(this).attr('id');
                }
            });

            return currentOnePageSection;
        },

        darkLogo: function(){
            if( ( $header.hasClass('dark') || $body.hasClass('dark') ) && !$headerWrap.hasClass('not-dark') ) {
                if( defaultDarkLogo ){ defaultLogo.find('img').attr('src', defaultDarkLogo); }
                if( retinaDarkLogo ){ retinaLogo.find('img').attr('src', retinaDarkLogo); }
            } else {
                if( defaultLogoImg ){ defaultLogo.find('img').attr('src', defaultLogoImg); }
                if( retinaLogoImg ){ retinaLogo.find('img').attr('src', retinaLogoImg); }
            }
        },

        stickyMenuClass: function(){
            if( stickyMenuClasses ) { var newClassesArray = stickyMenuClasses.split(/ +/); } else { var newClassesArray = ''; }
            var noOfNewClasses = newClassesArray.length;

            if( noOfNewClasses > 0 ) {
                var i = 0;
                for( i=0; i<noOfNewClasses; i++ ) {
                    if( newClassesArray[i] == 'not-dark' ) {
                        $headerWrap.addClass('not-dark');
                    } else if( newClassesArray[i] == 'dark' ) {
                        $headerWrap.removeClass('not-dark force-not-dark');
                    } else if( !$header.hasClass( newClassesArray[i] ) ) {
                        $header.addClass( newClassesArray[i] );
                    }
                }
            }

        },

        topsocial: function(){

            var windowWidth = $window.width();

            if( windowWidth > 991 ) {

                $("#top-social li").show();
                $("#top-social li a").css({width: 40});

                $("#top-social li a").hover(function() {
                    if( $(this).has('.ts-text').length ) {
                        var tsTextWidth = $(this).find('.ts-text').outerWidth() + 52;
                        $(this).css({width: tsTextWidth});
                    }
                }, function() {
                    $(this).css({width: 40});
                });

            } else {

                $("#top-social li").show();
                $("#top-social li a").css({width: 40});

                $("#top-social li a").each(function() {
                    var topIconTitle = $(this).find('.ts-text').text();
                    $(this).attr('title', topIconTitle);
                });

                $("#top-social li a").hover(function() {
                    $(this).css({width: 40});
                }, function() {
                    $(this).css({width: 40});
                });

                if( windowWidth < 479 ) {
                    $("#top-social li").hide();
                    $("#top-social li").slice(0, 8).show();
                }

            }

        },

        topsearch: function(){

            $(document).on('click', function(event) {
                if (!$(event.target).closest('#top-search').length) { $body.toggleClass('top-search-open', false); }
                if (!$(event.target).closest('#top-cart').length) { $topCart.toggleClass('top-cart-open', false); }
                if (!$(event.target).closest('#page-menu').length) { $pagemenu.toggleClass('pagemenu-active', false); }
            });

            $("#top-search-trigger").click(function(e){
                $body.toggleClass('top-search-open');
                $topCart.toggleClass('top-cart-open', false);
                $( '#primary-menu > ul, #primary-menu > div > ul' ).toggleClass("show", false);
                $pagemenu.toggleClass('pagemenu-active', false);
                if ($body.hasClass('top-search-open')){
                    $topSearch.find('input').focus();
                }
                e.stopPropagation();
                e.preventDefault();
            });

        },

        topcart: function(){

            $("#top-cart-trigger").click(function(e){
                $pagemenu.toggleClass('pagemenu-active', false);
                $topCart.toggleClass('top-cart-open');
                e.stopPropagation();
                e.preventDefault();
            });

        }

    };

    SEMICOLON.slider = {

        init: function() {

            SEMICOLON.slider.sliderParallax();
            SEMICOLON.slider.sliderElementsFade();
            SEMICOLON.slider.captionPosition();

        },

        sliderParallaxOffset: function(){
            var sliderParallaxOffsetTop = 0;
            var headerHeight = $header.outerHeight();
            if( $('body').hasClass('side-header') ) { headerHeight = 0; }
            if( $pageTitle.length > 0 ) {
                var pageTitleHeight = $pageTitle.outerHeight();
                sliderParallaxOffsetTop = pageTitleHeight + headerHeight;
            } else {
                sliderParallaxOffsetTop = headerHeight;
            }

            if( $slider.next('#header').length > 0 ) { sliderParallaxOffsetTop = 0; }

            return sliderParallaxOffsetTop;
        },

        sliderParallax: function(){
            if( ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) && !SEMICOLON.isMobile.any() ) {
                var parallaxOffsetTop = SEMICOLON.slider.sliderParallaxOffset();
                if ($window.scrollTop() > parallaxOffsetTop) {
                    $('.slider-parallax').css({ 'transform' : 'translateY('+ (($window.scrollTop()-parallaxOffsetTop) / 1.5 ) +'px)' });
                    $('.slider-parallax .slider-caption,.ei-title').css({ 'transform' : 'translateY(-'+ (($window.scrollTop()-parallaxOffsetTop) / 7 ) +'px)' });
                } else {
                    $('.slider-parallax,.slider-parallax .slider-caption,.ei-title').css({ 'transform' : 'translateY(0)' });
                }
            } else {
                $('.slider-parallax,.slider-parallax .slider-caption,.ei-title').css({ 'transform' : 'translateY(0)' });
            }
        },

        sliderElementsFade: function(){
            if( ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) && !SEMICOLON.isMobile.any() ) {
                var parallaxOffsetTop = SEMICOLON.slider.sliderParallaxOffset();
                if( $slider.length > 0 ) {
                    if( $header.hasClass('transparent-header') || $('body').hasClass('side-header') ) { var tHeaderOffset = 100; } else { var tHeaderOffset = 0; }
                    $('.slider-parallax').find('#slider-arrow-left,#slider-arrow-right,.vertical-middle:not(.no-fade),.slider-caption,.ei-title,.camera_prev,.camera_next').css({'opacity':( ( 100 + ( $slider.offset().top + parallaxOffsetTop + tHeaderOffset ) - $window.scrollTop() ) ) /90});
                }
            } else {
                $('.slider-parallax').find('#slider-arrow-left,#slider-arrow-right,.vertical-middle:not(.no-fade),.slider-caption,.ei-title,.camera_prev,.camera_next').css({'opacity': 1});
            }
        },

        captionPosition: function(){
            $('#slider .slider-caption').each(function(){
                var scapHeight = $(this).outerHeight();
                var scapSliderHeight = $('#slider').outerHeight();
                if( $(this).parents('#slider').prev('#header').hasClass('transparent-header') && ( $body.hasClass('device-lg') || $body.hasClass('device-md') ) ) {
                    if( $(this).parents('#slider').prev('#header').hasClass('floating-header') ) {
                        $(this).css({ top: ( scapSliderHeight + 160 - scapHeight ) / 2 + 'px' });
                    } else {
                        $(this).css({ top: ( scapSliderHeight + 100 - scapHeight ) / 2 + 'px' });
                    }
                } else {
                    $(this).css({ top: ( scapSliderHeight - scapHeight ) / 2 + 'px' });
                }
            });
        },

        swiperSliderMenu: function(){
            if( $body.hasClass('device-lg') || $body.hasClass('device-md') ) {
                var activeSlide = $('#slider').find('.swiper-slide.swiper-slide-visible');
                SEMICOLON.slider.headerSchemeChanger(activeSlide);
            }
        },

        revolutionSliderMenu: function(){
            if( $body.hasClass('device-lg') || $body.hasClass('device-md') ) {
                var activeSlide = $('#slider').find('.current-sr-slide-visible');
                SEMICOLON.slider.headerSchemeChanger(activeSlide);
            }
        },

        headerSchemeChanger: function( activeSlide ){
            if( activeSlide.length > 0 ) {
                if( activeSlide.hasClass('dark') ){
                    $('#header.transparent-header:not(.sticky-header,.semi-transparent)').addClass('dark');
                    $('#header.transparent-header.sticky-header,#header.transparent-header.semi-transparent.sticky-header').removeClass('dark');
                    $headerWrap.removeClass('not-dark');
                } else {
                    if( $body.hasClass('dark') ) {
                        activeSlide.addClass('not-dark');
                        $('#header.transparent-header:not(.semi-transparent)').removeClass('dark');
                        $('#header.transparent-header:not(.sticky-header,.semi-transparent)').find('#header-wrap').addClass('not-dark');
                    } else {
                        $('#header.transparent-header:not(.semi-transparent)').removeClass('dark');
                        $headerWrap.removeClass('not-dark');
                    }
                }
                SEMICOLON.header.darkLogo();
            }
        },

        owlCaptionInit: function(){
            $('.owl-carousel').each( function(){
                if( $(this).find('.owl-dot').length > 0 ) {
                    $(this).find('.owl-controls').addClass('with-carousel-dots');
                }
            });
        }

    };

    SEMICOLON.portfolio = {

        init: function(){

            SEMICOLON.portfolio.ajaxload();

        },

        portfolioDescMargin: function(){
            $('.portfolio-overlay').each(function() {
                if( $(this).find('.portfolio-desc').length > 0 ) {
                    var portfolioOverlayHeight = $(this).outerHeight();
                    var portfolioOverlayDescHeight = $(this).find('.portfolio-desc').outerHeight();
                    if( $(this).find('a.left-icon').length > 0 || $(this).find('a.right-icon').length > 0 || $(this).find('a.center-icon').length > 0 ) {
                        var portfolioOverlayIconHeight = 40 + 20;
                    } else {
                        var portfolioOverlayIconHeight = 0;
                    }
                    var portfolioOverlayMiddleAlign = ( portfolioOverlayHeight - portfolioOverlayDescHeight - portfolioOverlayIconHeight ) / 2
                    $(this).find('.portfolio-desc').css({ 'margin-top': portfolioOverlayMiddleAlign });
                }
            });
        },

        arrange: function(){
            SEMICOLON.initialize.setFullColumnWidth( $portfolio );
            $('#portfolio.portfolio-full').isotope('layout');
        },

        ajaxload: function(){
            $('.portfolio-ajax .portfolio-item a.center-icon').click( function(e) {
                var portPostId = $(this).parents('.portfolio-item').attr('id');
                if( !$(this).parents('.portfolio-item').hasClass('portfolio-active') ) {
                    SEMICOLON.portfolio.loadItem(portPostId, prevPostPortId);
                }
                e.preventDefault();
            });
        },

        newNextPrev: function( portPostId ){
            var portNext = SEMICOLON.portfolio.getNextItem(portPostId);
            var portPrev = SEMICOLON.portfolio.getPrevItem(portPostId);
            $('#next-portfolio').attr('data-id', portNext);
            $('#prev-portfolio').attr('data-id', portPrev);
        },

        loadItem: function( portPostId, prevPostPortId, getIt ){
            if(!getIt) { getIt = false; }
            var portNext = SEMICOLON.portfolio.getNextItem(portPostId);
            var portPrev = SEMICOLON.portfolio.getPrevItem(portPostId);
            if(getIt == false) {
                SEMICOLON.portfolio.closeItem();
                $portfolioAjaxLoader.fadeIn();
                var portfolioDataLoader = $('#' + portPostId).attr('data-loader');
                $portfolioDetailsContainer.load(portfolioDataLoader, { portid: portPostId, portnext: portNext, portprev: portPrev },
                function(){
                    SEMICOLON.portfolio.initializeAjax(portPostId);
                    SEMICOLON.portfolio.openItem();
                    $portfolioItems.removeClass('portfolio-active');
                    $('#' + portPostId).addClass('portfolio-active');
                });
            }
        },

        closeItem: function(){
            if( $portfolioDetails && $portfolioDetails.height() > 32 ) {
                $portfolioAjaxLoader.fadeIn();
                $portfolioDetails.find('#portfolio-ajax-single').fadeOut('600', function(){
                    $(this).remove();
                });
                $portfolioDetails.removeClass('portfolio-ajax-opened');
            }
        },

        openItem: function(){
            var noOfImages = $portfolioDetails.find('img').length;
            var noLoaded = 0;

            if( noOfImages > 0 ) {
                $portfolioDetails.find('img').on('load', function(){
                    noLoaded++;
                    var topOffsetScroll = SEMICOLON.initialize.topScrollOffset();
                    if(noOfImages === noLoaded) {
                        $portfolioDetailsContainer.css({ 'display': 'block' });
                        $portfolioDetails.addClass('portfolio-ajax-opened');
                        $portfolioAjaxLoader.fadeOut();
                        var t=setTimeout(function(){
                            SEMICOLON.widget.loadFlexSlider();
                            SEMICOLON.initialize.lightbox();
                            SEMICOLON.initialize.resizeVideos();
                            SEMICOLON.widget.masonryThumbs();
                            $('html,body').stop(true).animate({
                                'scrollTop': $portfolioDetails.offset().top - topOffsetScroll
                            }, 900, 'easeOutQuad');
                        },500);
                    }
                });
            } else {
                var topOffsetScroll = SEMICOLON.initialize.topScrollOffset();
                $portfolioDetailsContainer.css({ 'display': 'block' });
                $portfolioDetails.addClass('portfolio-ajax-opened');
                $portfolioAjaxLoader.fadeOut();
                var t=setTimeout(function(){
                    SEMICOLON.widget.loadFlexSlider();
                    SEMICOLON.initialize.lightbox();
                    SEMICOLON.initialize.resizeVideos();
                    SEMICOLON.widget.masonryThumbs();
                    $('html,body').stop(true).animate({
                        'scrollTop': $portfolioDetails.offset().top - topOffsetScroll
                    }, 900, 'easeOutQuad');
                },500);
            }
        },

        getNextItem: function( portPostId ){
            var portNext = '';
            var hasNext = $('#' + portPostId).next();
            if(hasNext.length != 0) {
                portNext = hasNext.attr('id');
            }
            return portNext;
        },

        getPrevItem: function( portPostId ){
            var portPrev = '';
            var hasPrev = $('#' + portPostId).prev();
            if(hasPrev.length != 0) {
                portPrev = hasPrev.attr('id');
            }
            return portPrev;
        },

        initializeAjax: function( portPostId ){
            prevPostPortId = $('#' + portPostId);

            $('#next-portfolio, #prev-portfolio').click( function() {
                var portPostId = $(this).attr('data-id');
                $portfolioItems.removeClass('portfolio-active');
                $('#' + portPostId).addClass('portfolio-active');
                SEMICOLON.portfolio.loadItem(portPostId,prevPostPortId);
                return false;
            });

            $('#close-portfolio').click( function() {
                $portfolioDetailsContainer.fadeOut('600', function(){
                    $portfolioDetails.find('#portfolio-ajax-single').remove();
                });
                $portfolioDetails.removeClass('portfolio-ajax-opened');
                $portfolioItems.removeClass('portfolio-active');
                return false;
            });
        }

    };

    SEMICOLON.widget = {

        init: function(){

            SEMICOLON.widget.parallax();
            SEMICOLON.widget.animations();
            SEMICOLON.widget.toggles();
            SEMICOLON.widget.accordions();
            SEMICOLON.widget.counter();
            SEMICOLON.widget.roundedSkill();
            SEMICOLON.widget.progress();
            SEMICOLON.widget.flickrFeed();
            SEMICOLON.widget.instagramPhotos( '36286274.b9e559e.4824cbc1d0c94c23827dc4a2267a9f6b', 'b9e559ec7c284375bf41e9a9fb72ae01' );
            SEMICOLON.widget.dribbbleShots();
            SEMICOLON.widget.textRotater();
            SEMICOLON.widget.linkScroll();
            SEMICOLON.widget.extras();

        },

        parallax: function(){
            if( !SEMICOLON.isMobile.any() ){
                $.stellar({
                    horizontalScrolling: false,
                    verticalOffset: 150,
                    responsive: true
                });
            } else {
                $('.parallax').addClass('mobile-parallax');
            }
        },

        animations: function(){
            if( $body.hasClass('device-lg') || $body.hasClass('device-md') || $body.hasClass('device-sm') ){
                $('[data-animate]').each(function(){
                    var element = $(this),
                        animationDelay = element.attr('data-delay'),
                        animationDelayTime = 0;

                    if( animationDelay ) { animationDelayTime = Number( animationDelay ) + 500; } else { animationDelayTime = 500; }

                    if( !element.hasClass('animated') ) {
                        element.addClass('not-animated');
                        var elementAnimation = element.attr('data-animate');
                        element.appear(function () {
                            setTimeout(function() {
                                element.removeClass('not-animated').addClass( elementAnimation + ' animated');
                            }, animationDelayTime);
                        },{accX: 0, accY: -120},'easeInCubic');
                    }
                });
            }
        },

        loadFlexSlider: function(){
            $('.fslider .flexslider').each(function() {
                var $flexsSlider = $(this);
                var flexsAnimation = $flexsSlider.parent('.fslider').attr('data-animation');
                var flexsEasing = $flexsSlider.parent('.fslider').attr('data-easing');
                var flexsDirection = $flexsSlider.parent('.fslider').attr('data-direction');
                var flexsSlideshow = $flexsSlider.parent('.fslider').attr('data-slideshow');
                var flexsPause = $flexsSlider.parent('.fslider').attr('data-pause');
                var flexsSpeed = $flexsSlider.parent('.fslider').attr('data-speed');
                var flexsVideo = $flexsSlider.parent('.fslider').attr('data-video');
                var flexsPagi = $flexsSlider.parent('.fslider').attr('data-pagi');
                var flexsArrows = $flexsSlider.parent('.fslider').attr('data-arrows');
                var flexsThumbs = $flexsSlider.parent('.fslider').attr('data-thumbs');
                var flexsSheight = true;
                var flexsUseCSS = false;

                if( !flexsAnimation ) { flexsAnimation = 'slide'; }
                if( !flexsEasing || flexsEasing == 'swing' ) {
                    flexsEasing = 'swing';
                    flexsUseCSS = true;
                }
                if( !flexsDirection ) { flexsDirection = 'horizontal'; }
                if( !flexsSlideshow ) { flexsSlideshow = true; } else { flexsSlideshow = false; }
                if( !flexsPause ) { flexsPause = 5000; }
                if( !flexsSpeed ) { flexsSpeed = 600; }
                if( !flexsVideo ) { flexsVideo = false; }
                if( flexsDirection == 'vertical' ) { flexsSheight = false; }
                if( flexsPagi == 'false' ) { flexsPagi = false; } else { flexsPagi = true; }
                if( flexsThumbs == 'true' ) { flexsPagi = 'thumbnails'; } else { flexsPagi = flexsPagi; }
                if( flexsArrows == 'false' ) { flexsArrows = false; } else { flexsArrows = true; }

                $flexsSlider.flexslider({
                    selector: ".slider-wrap > .slide",
                    animation: flexsAnimation,
                    easing: flexsEasing,
                    direction: flexsDirection,
                    slideshow: flexsSlideshow,
                    slideshowSpeed: Number(flexsPause),
                    animationSpeed: Number(flexsSpeed),
                    pauseOnHover: true,
                    video: flexsVideo,
                    controlNav: flexsPagi,
                    directionNav: flexsArrows,
                    smoothHeight: flexsSheight,
                    useCSS: flexsUseCSS,
                    start: function(slider){
                        SEMICOLON.widget.animations();
                        SEMICOLON.initialize.verticalMiddle();
                        slider.parent().removeClass('preloader2');
                        var t = setTimeout( function(){ $('#portfolio.portfolio-masonry,#portfolio.portfolio-full,#posts.post-masonry').isotope('layout'); }, 1200 );
                        SEMICOLON.initialize.lightbox();
                        $('.flex-prev').html('<i class="icon-angle-left"></i>');
                        $('.flex-next').html('<i class="icon-angle-right"></i>');
                        SEMICOLON.portfolio.portfolioDescMargin();
                    },
                    after: function(){
                        if( $portfolio.has('portfolio-full') ) {
                            $('#portfolio.portfolio-full').isotope('layout');
                            SEMICOLON.portfolio.portfolioDescMargin();
                        }
                    }
                });
            });
        },

        html5Video: function(){
            $('.portfolio-single-image:has(video),.entry-image.parallax:has(video),#page-title:has(video),#slider:not(.revoslider-wrap):has(video),.section:has(video)').each(function(){
                var outerContainerWidth = $(this).outerWidth();
                var outerContainerHeight = $(this).outerHeight();
                var innerVideoWidth = $(this).find('video').outerWidth();
                var innerVideoHeight = $(this).find('video').outerHeight();
                if( innerVideoHeight < outerContainerHeight ) {
                    var videoAspectRatio = innerVideoWidth/innerVideoHeight;
                    var newVideoWidth = outerContainerHeight * videoAspectRatio;
                    var innerVideoPosition = (newVideoWidth - outerContainerWidth) / 2;
                    $(this).find('video').css({ 'width': newVideoWidth+'px', 'height': outerContainerHeight+'px', 'left': -innerVideoPosition+'px' });
                } else {
                    var innerVideoPosition = (innerVideoHeight - outerContainerHeight) / 2;
                    $(this).find('video').css({ 'width': innerVideoWidth+'px', 'height': innerVideoHeight+'px', 'top': -innerVideoPosition+'px' });
                }
            });
        },

        toggles: function(){
            $(".togglec").hide();
            $(".togglet").click(function(){
                $(this).toggleClass("toggleta").next(".togglec").slideToggle(300);
                return true;
            });
        },

        accordions: function(){
            $('.accordion').each( function(){
                var $accElement = $(this);
                var accElementState = $accElement.attr('data-state');

                $accElement.find('.acc_content').hide();

                if( accElementState != 'closed' ) {
                    $accElement.find('.acctitle:first').addClass('acctitlec').next().show();
                }

                $accElement.find('.acctitle').click(function(){
                    if( $(this).next().is(':hidden') ) {
                        $accElement.find('.acctitle').removeClass('acctitlec').next().slideUp("normal");
                        $(this).toggleClass('acctitlec').next().slideDown("normal");
                    }
                    return false;
                });
            });
        },

        counter: function(){
            $('.counter:not(.counter-instant)').each(function(){
                var $counterElement = $(this);
                var counterElementComma = $(this).find('span').attr('data-comma');
                if( !counterElementComma ) { counterElementComma = false; } else { counterElementComma = true; }
                if( $body.hasClass('device-lg') || $body.hasClass('device-md') ){
                    $counterElement.appear( function(){
                        SEMICOLON.widget.runCounter( $counterElement, counterElementComma );
                    },{accX: 0, accY: -120},'easeInCubic');
                } else {
                    SEMICOLON.widget.runCounter( $counterElement, counterElementComma );
                }
            });
        },

        runCounter: function( counterElement,counterElementComma ){
            if( counterElementComma == true ) {
                counterElement.find('span').countTo({
                    formatter: function (value, options) {
                        value = value.toFixed(options.decimals);
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return value;
                    }
                });
            } else {
                counterElement.find('span').countTo();
            }
        },

        roundedSkill: function(){
            $('.rounded-skill').each(function() {

                var element = $(this);

                var roundSkillSize = element.attr('data-size');
                var roundSkillAnimate = element.attr('data-animate');
                var roundSkillWidth = element.attr('data-width');
                var roundSkillColor = element.attr('data-color');
                var roundSkillTrackColor = element.attr('data-trackcolor');

                if( !roundSkillSize ) { roundSkillSize = 140; }
                if( !roundSkillAnimate ) { roundSkillAnimate = 2000; }
                if( !roundSkillWidth ) { roundSkillWidth = 8; }
                if( !roundSkillColor ) { roundSkillColor = '#0093BF'; }
                if( !roundSkillTrackColor ) { roundSkillTrackColor = 'rgba(0,0,0,0.04)'; }

                var properties = {roundSkillSize:roundSkillSize, roundSkillAnimate:roundSkillAnimate, roundSkillWidth:roundSkillWidth, roundSkillColor:roundSkillColor, roundSkillTrackColor:roundSkillTrackColor};

                if( $body.hasClass('device-lg') || $body.hasClass('device-md') ){
                    element.css({'width':roundSkillSize+'px','height':roundSkillSize+'px'}).animate({opacity: '0'}, 10);
                    element.appear( function(){
                        if (!element.hasClass('skills-animated')) {
                            element.css({opacity: '1'});
                            SEMICOLON.widget.runRoundedSkills( element, properties );
                            element.addClass('skills-animated');
                        }
                    },{accX: 0, accY: -120},'easeInCubic');
                } else {
                    SEMICOLON.widget.runRoundedSkills( element, properties );
                }
            });
        },

        runRoundedSkills: function( element, properties ){
            element.easyPieChart({
                size: Number(properties.roundSkillSize),
                animate: Number(properties.roundSkillAnimate),
                scaleColor: false,
                trackColor: properties.roundSkillTrackColor,
                lineWidth: Number(properties.roundSkillWidth),
                lineCap: 'square',
                barColor: properties.roundSkillColor
            });
        },

        progress: function(){
            $('.progress').each(function(){

                var element = $(this),
                    skillsBar = element.parent('li'),
                    skillValue = skillsBar.attr('data-percent');

                if( $body.hasClass('device-lg') || $body.hasClass('device-md') ){
                    element.appear( function(){
                        if (!skillsBar.hasClass('skills-animated')) {
                            element.find('.counter-instant span').countTo();
                            skillsBar.find('.progress').css({width: skillValue + "%"}).addClass('skills-animated');
                        }
                    },{accX: 0, accY: -120},'easeInCubic');
                } else {
                    element.find('.counter-instant span').countTo();
                    skillsBar.find('.progress').css({width: skillValue + "%"});
                }
            });
        },

        flickrFeed: function(){
            $('.flickr-feed').each(function() {
                var flickrWrap = $(this),
                    flickrFeedID = flickrWrap.attr('data-id'),
                    flickrFeedCount = flickrWrap.attr('data-count'),
                    flickrFeedType = flickrWrap.attr('data-type'),
                    flickrFeedTypeGet = 'photos_public.gne';

                if( flickrFeedType == 'group' ) { flickrFeedTypeGet = 'groups_pool.gne'; }
                if( !flickrFeedCount ) { flickrFeedCount = 9; }

                flickrWrap.jflickrfeed({
                    feedapi: flickrFeedTypeGet,
                    limit: Number(flickrFeedCount),
                    qstrings: {
                        id: flickrFeedID
                    },
                    itemTemplate: '<a href="{{image_b}}" title="{{title}}" data-lightbox="gallery-item">' +
                                        '<img src="{{image_s}}" alt="{{title}}" />' +
                                  '</a>'
                }, function(data) {
                    SEMICOLON.initialize.lightbox();
                });
            });
        },

        instagramPhotos: function( c_accessToken, c_clientID ){
            $.fn.spectragram.accessData = {
                accessToken: c_accessToken,
                clientID: c_clientID
            };

            $('.instagram-photos').each(function() {
                var instaGramWrap = $(this),
                    instaGramUsername = instaGramWrap.attr('data-user'),
                    instaGramTag = instaGramWrap.attr('data-tag'),
                    instaGramCount = instaGramWrap.attr('data-count'),
                    instaGramType = instaGramWrap.attr('data-type');

                if( !instaGramCount ) { instaGramCount = 9; }

                if( instaGramType == 'tag' ) {
                    instaGramWrap.spectragram('getRecentTagged',{
                        query: instaGramTag,
                        max: Number( instaGramCount ),
                        size: 'medium',
                        wrapEachWith: ' '
                    });
                } else if( instaGramType == 'user' ) {
                    instaGramWrap.spectragram('getUserFeed',{
                        query: instaGramUsername,
                        max: Number( instaGramCount ),
                        size: 'medium',
                        wrapEachWith: ' '
                    });
                } else {
                    instaGramWrap.spectragram('getPopular',{
                        max: Number( instaGramCount ),
                        size: 'medium',
                        wrapEachWith: ' '
                    });
                }
            });
        },

        dribbbleShots: function(){
            $('.dribbble-shots').each(function() {
                var dribbbleWrap = $(this),
                    dribbbleUsername = dribbbleWrap.attr('data-user'),
                    dribbbleCount = dribbbleWrap.attr('data-count'),
                    dribbbleList = dribbbleWrap.attr('data-list'),
                    dribbbleType = dribbbleWrap.attr('data-type');

                if( !dribbbleCount ) { dribbbleCount = 9; }

                if( dribbbleType == 'follows' ) {
                    $.jribbble.getShotsThatPlayerFollows( dribbbleUsername , function (followedShots) {
                        var html = [];
                        $.each(followedShots.shots, function (i, shot) {
                            html.push('<a href="' + shot.url + '" target="_blank">');
                            html.push('<img src="' + shot.image_teaser_url + '" ');
                            html.push('alt="' + shot.title + '"></a>');
                        });
                        dribbbleWrap.html(html.join(''));
                    }, {page: 1, per_page: Number(dribbbleCount)});
                } else if( dribbbleType == 'user' ) {
                    $.jribbble.getShotsByPlayerId( dribbbleUsername , function (playerShots) {
                        var html = [];
                        $.each(playerShots.shots, function (i, shot) {
                            html.push('<a href="' + shot.url + '" target="_blank">');
                            html.push('<img src="' + shot.image_teaser_url + '" ');
                            html.push('alt="' + shot.title + '"></a>');
                        });
                        dribbbleWrap.html(html.join(''));
                    }, {page: 1, per_page: Number(dribbbleCount)});
                } else if( dribbbleType == 'list' ) {
                    $.jribbble.getShotsByList( dribbbleList , function (listDetails) {
                        var html = [];
                        $.each(listDetails.shots, function (i, shot) {
                            html.push('<a href="' + shot.url + '" target="_blank">');
                            html.push('<img src="' + shot.image_teaser_url + '" ');
                            html.push('alt="' + shot.title + '"></a>');
                        });
                        dribbbleWrap.html(html.join(''));
                    }, {page: 1, per_page: Number(dribbbleCount)});
                }
            });
        },

        masonryThumbs: function(){
            $('.masonry-thumbs').each( function(){
                var masonryItemContainer = $(this);
                SEMICOLON.widget.masonryThumbsArrange( masonryItemContainer );
            });
        },

        masonryThumbsArrange: function( element ){
            SEMICOLON.initialize.setFullColumnWidth( element );
            element.isotope('layout');
        },

        notifications: function( element ){
            toastr.clear();
            var notifyElement = $(element);
            var notifyPosition = notifyElement.attr('data-notify-position');
            var notifyType = notifyElement.attr('data-notify-type');
            var notifyMsg = notifyElement.attr('data-notify-msg');
            var notifyCloseButton = notifyElement.attr('data-notify-close');

            if( !notifyPosition ) { notifyPosition = 'toast-top-right'; } else { notifyPosition = 'toast-' + notifyElement.attr('data-notify-position'); }
            if( !notifyMsg ) { notifyMsg = 'Please set a message!'; }
            if( notifyCloseButton == 'true' ) { notifyCloseButton = true; } else { notifyCloseButton = false; }

            toastr.options.positionClass = notifyPosition;
            toastr.options.closeButton = notifyCloseButton;
            toastr.options.closeHtml = '<button><i class="icon-remove"></i></button>';

            if( notifyType == 'warning' ) {
                toastr.warning(notifyMsg);
            } else if( notifyType == 'error' ) {
                toastr.error(notifyMsg);
            } else if( notifyType == 'success' ) {
                toastr.success(notifyMsg);
            } else {
                toastr.info(notifyMsg);
            }

            return false;
        },

        textRotater: function(){
            $('.text-rotater').each(function(){
                var trRotate = $(this).attr('data-rotate');
                var trSpeed = $(this).attr('data-speed');
                var trSeparator = $(this).attr('data-separator');

                if( !trRotate ) { trRotate = "fade"; }
                if( !trSpeed ) { trSpeed = 1200; }
                if( !trSeparator ) { trSeparator = ","; }

                var tRotater = $(this).find('.t-rotate');

                tRotater.textrotator({
                    animation: trRotate,
                    separator: trSeparator,
                    speed: Number(trSpeed)
                });
            });
        },

        linkScroll: function(){
            $("a[data-scrollto]").click(function(){
                var divScrollToAnchor = $(this).attr('data-scrollto');
                var topOffsetScroll = SEMICOLON.initialize.topScrollOffset();

                $('html,body').stop(true).animate({
                    'scrollTop': $( divScrollToAnchor ).offset().top - topOffsetScroll
                }, 750, 'easeOutQuad');

                return false;
            });
        },

        extras: function(){
            $('[data-toggle="tooltip"]').tooltip();
            $('#primary-menu-trigger').click(function() {
                $( '#primary-menu > ul, #primary-menu > div > ul' ).toggleClass("show");
                return false;
            });
            $('#page-submenu-trigger').click(function() {
                $body.toggleClass('top-search-open', false);
                $pagemenu.toggleClass("pagemenu-active");
                return false;
            });
            $pagemenu.find('nav').click(function(e){
                $body.toggleClass('top-search-open', false);
                $topCart.toggleClass('top-cart-open', false);
            });
            if( SEMICOLON.isMobile.any() ){
                $body.addClass('device-touch');
            }
            // var el = {
            //     darkLogo : $("<img>", {src: defaultDarkLogo}),
            //     darkRetinaLogo : $("<img>", {src: retinaDarkLogo})
            // };
            // el.darkLogo.prependTo("body");
            // el.darkRetinaLogo.prependTo("body");
            // el.darkLogo.css({'position':'absolute','z-index':'-100'});
            // el.darkRetinaLogo.css({'position':'absolute','z-index':'-100'});
        }

    };

    SEMICOLON.isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (SEMICOLON.isMobile.Android() || SEMICOLON.isMobile.BlackBerry() || SEMICOLON.isMobile.iOS() || SEMICOLON.isMobile.Opera() || SEMICOLON.isMobile.Windows());
        }
    };

    SEMICOLON.documentOnResize = {

        init: function(){

            var t = setTimeout( function(){
                SEMICOLON.header.topsocial();
                SEMICOLON.header.fullWidthMenu();
                SEMICOLON.initialize.fullScreen();
                SEMICOLON.initialize.verticalMiddle();
                SEMICOLON.initialize.maxHeight();
                SEMICOLON.initialize.testimonialsGrid();
                SEMICOLON.slider.captionPosition();
                SEMICOLON.portfolio.arrange();
                SEMICOLON.widget.html5Video();
                SEMICOLON.widget.masonryThumbs();
                SEMICOLON.initialize.dataStyles();
                SEMICOLON.initialize.dataResponsiveHeights();
            }, 500 );

        }

    };

    SEMICOLON.documentOnReady = {

        init: function(){
            SEMICOLON.initialize.init();
            SEMICOLON.header.init();
            if( $slider.length > 0 ) { SEMICOLON.slider.init(); }
            if( $portfolio.length > 0 ) { SEMICOLON.portfolio.init(); }
            SEMICOLON.widget.init();
            SEMICOLON.documentOnReady.windowscroll();
        },

        windowscroll: function(){

            var headerOffset = $header.offset().top;
            var headerWrapOffset = $headerWrap.offset().top;

            $window.scroll( function(){

                SEMICOLON.initialize.goToTopScroll();
                $('body.open-header.close-header-on-scroll').removeClass("side-header-open");
                SEMICOLON.header.stickyMenu( headerWrapOffset );
                SEMICOLON.header.darkLogo();
                SEMICOLON.slider.sliderParallax();
                SEMICOLON.slider.sliderElementsFade();

            });
            $window.scrolled(function() {
                SEMICOLON.header.onepageScroller();
            });
        }

    };

    SEMICOLON.documentOnLoad = {

        init: function(){
            SEMICOLON.slider.captionPosition();
            SEMICOLON.slider.swiperSliderMenu();
            SEMICOLON.slider.revolutionSliderMenu();
            SEMICOLON.initialize.maxHeight();
            SEMICOLON.initialize.testimonialsGrid();
            SEMICOLON.initialize.verticalMiddle();
            SEMICOLON.portfolio.portfolioDescMargin();
            SEMICOLON.portfolio.arrange();
            SEMICOLON.widget.loadFlexSlider();
            SEMICOLON.widget.html5Video();
            SEMICOLON.widget.masonryThumbs();
            SEMICOLON.slider.owlCaptionInit();
        }

    };

    var $window = $(window),
        $body = $('body'),
        $wrapper = $('#wrapper'),
        $header = $('#header'),
        $headerWrap = $('#header-wrap'),
        oldHeaderClasses = $header.attr('class'),
        oldHeaderWrapClasses = $headerWrap.attr('class'),
        stickyMenuClasses = $header.attr('data-sticky-class'),
        defaultLogo = $('#logo').find('.standard-logo'),
        retinaLogo = $('#logo').find('.retina-logo'),
        defaultLogoImg = defaultLogo.find('img').attr('src'),
        retinaLogoImg = retinaLogo.find('img').attr('src'),
        defaultDarkLogo = defaultLogo.attr('data-dark-logo'),
        retinaDarkLogo = retinaLogo.attr('data-dark-logo'),
        $pagemenu = $('#page-menu'),
        $portfolio = $('#portfolio'),
        $slider = $('#slider'),
        $pageTitle = $('#page-title'),
        $portfolioItems = $('.portfolio-ajax').find('.portfolio-item'),
        $portfolioDetails = $('#portfolio-ajax-wrap'),
        $portfolioDetailsContainer = $('#portfolio-ajax-container'),
        $portfolioAjaxLoader = $('#portfolio-ajax-loader'),
        prevPostPortId = '',
        $topSearch = $('#top-search'),
        $topCart = $('#top-cart'),
        $verticalMiddleEl = $('.vertical-middle'),
        $siStickyEl = $('.si-sticky'),
        $dotsMenuEl = $('.dots-menu'),
        $goToTopEl = $('#gotoTop'),
        $fullScreenEl = $('.full-screen'),
        $commonHeightEl = $('.common-height'),
        $testimonialsGridEl = $('.testimonials-grid'),
        $lightboxImageEl = $('[data-lightbox="image"]'),
        $lightboxGalleryEl = $('[data-lightbox="gallery"]'),
        $lightboxIframeEl = $('[data-lightbox="iframe"]'),
        $lightboxAjaxEl = $('[data-lightbox="ajax"]');

    $(document).ready( SEMICOLON.documentOnReady.init );
    $window.load( SEMICOLON.documentOnLoad.init );
    $window.on( 'resize', SEMICOLON.documentOnResize.init );

})(jQuery);