$(document).ready(function () {


    $('#login-form').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var url = $(this).data('url');
        var btn_id = $(document.activeElement).attr('id');
        $.ajax({
            url: url,
            method: 'post',
            data: form.serializeArray(),
            beforeSend: loader('show', '#' + btn_id),
            success: function (data) {
                loader('hide', '#' + btn_id);
                if (data.status === '0') {
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            }, error: function () {
                alert(data.message);
            }
        });
    });



    $('#register-form').submit(function (e) {
        e.preventDefault();

        var form = $(this);
        var url = $(this).data('url');

        var btn_id = $(document.activeElement).attr('id');
        $.ajax({
            url: url,
            method: 'post',
            data: form.serializeArray(),
            beforeSend: loader('show', '#' + btn_id),
            success: function (data) {
                loader('hide', '#' + btn_id);
                if (data.status === '0') {
                    alert(data.message);

                } else {
                    alert(data.message);

                }
            }, error: function () {
                alert(data.message);

            }
        });

    })


    $(".fancybox").fancybox();

    tabInit('accounting');

    var swiper = new Swiper('.vip-slider-container', {
        slidesPerView: 5,
        spaceBetween: 30,
        pagination: {
            el: '.vip-slider-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.vip-slider-button-next',
            prevEl: '.vip-slider-button-prev',
        },  breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    });

    new Swiper('.top-cat-slider-container', {
        slidesPerView: 5,
        spaceBetween: 0,
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            }
        }
    });





});

/* Button Loader */
function loader(type, id) {
    if (type === 'show') {
        $(id).addClass("load");
    } else {
        $(id).removeClass("load");
    }

};

function tabInit(tabIndex) {
    if (typeof tabIndex === "undefined" || tabIndex === null) {
        tabIndex = '[data-tabindex]';
        $(tabIndex).each(function () {
            if (tabIndex != '[data-tabindex]') {
                tabIndex = '[data-tabindex=' + $(this).attr('data-tabindex') + ']';
            }
            $(tabIndex).find('> .tab-content > [data-tabc]').hide();
            var hasTrueTab = false;
            $(tabIndex).find('> .tab-title > [data-tab]').each(function () {
                progressively.init();
                var i;
                if ($(this).hasClass('active')) {
                    i = $(this).attr('data-tab');
                    $(tabIndex).find('.tab-content > [data-tabc="' + i + '"]').addClass('active');
                    $(tabIndex).find('.tab-content > [data-tabc="' + i + '"]').show();
                    hasTrueTab = true;
                }
                if (hasTrueTab !== true) {
                    $(tabIndex).find('.tab-content > [data-tabc="' + i + '"]').show();
                    $(tabIndex).find('.tab-title > [data-tab="' + i + '"]').addClass('active');
                }
            });
            $(tabIndex).find(' > .tab-title > [data-tab]').click(function () {
                progressively.init();
                if (!$(this).hasClass('active')) {
                    var t = $(this).attr('data-tab');
                    var currentTabIndex = $(this).parents('[data-tabindex]');
                    var oldActiveTab = currentTabIndex.find('> .tab-title >  [data-tab].active').attr('data-tab');
                    currentTabIndex.find('> .tab-title > [data-tab=' + oldActiveTab + ']').removeClass('active');
                    currentTabIndex.find('> .tab-content > [data-tabc=' + oldActiveTab + ']').removeClass('active');
                    currentTabIndex.find('> .tab-content > [data-tabc=' + oldActiveTab + ']').hide();
                    $(this).addClass('active');
                    currentTabIndex.find('> .tab-content > [data-tabc="' + t + '"]').show();
                }
            });
        });
    } else {
        tabIndex = '[data-tabindex=' + tabIndex + ']';
        $(tabIndex).find('> .tab-content > [data-tabc]').hide();
        $(tabIndex).find('> .tab-title > [data-tab]').each(function () {
            // progressively.init();
            var i;
            if ($(this).hasClass('active')) {
                i = $(this).attr('data-tab');
                $(tabIndex).find('> .tab-content > [data-tabc="' + i + '"]').addClass('active');
                $(tabIndex).find('> .tab-content > [data-tabc="' + i + '"]').show();
            }
        });
        $(tabIndex).find('> .tab-title > [data-tab]').click(function () {
            // progressively.init();
            if (!$(this).hasClass('active')) {
                var t = $(this).attr('data-tab');
                var currentTabIndex = $(this).parent().parent();
                var oldActiveTab = currentTabIndex.find('> .tab-title >  [data-tab].active').attr('data-tab');
                currentTabIndex.find('> .tab-title > [data-tab=' + oldActiveTab + ']').removeClass('active');
                currentTabIndex.find('> .tab-content > [data-tabc=' + oldActiveTab + ']').removeClass('active');
                currentTabIndex.find('> .tab-content > [data-tabc=' + oldActiveTab + ']').hide();
                $(this).addClass('active');
                currentTabIndex.find('> .tab-content > [data-tabc="' + t + '"]').show();
            }
        });
    }
    $('body').on('click', '[data-tabindex-current]', function () {
        var tabIndexCurent = $(this).attr('data-tabindex-current');
        var tabCurent = $(this).attr('data-tab-current');
        $('[data-tabindex="' + tabIndexCurent + '"] > .tab-title > [data-tab]').removeClass('active');
        $('[data-tabindex="' + tabIndexCurent + '"] > .tab-content > [data-tabc]').hide();
        $('[data-tabindex="' + tabIndexCurent + '"] > .tab-title > [data-tab="' + tabCurent + '"]').addClass('active');
        $('[data-tabindex="' + tabIndexCurent + '"] > .tab-content > [data-tabc="' + tabCurent + '"]').fadeIn();
    });
}