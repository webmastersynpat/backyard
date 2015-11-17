// var _panelMarginBottom = 20;
var _panelMarginBottom = 4;

var _inputStringFieldsWidthInterval = null;
var _inputStringFieldsWidthIntervalPeriod = 700;

var _rowWidthInterval = null;
var _rowWidthIntervalPeriod = 300;

function windowResize() {
    $('body').css('height', window.InnerHeight + 'px');
    $('.dashboard-box, .dashboard-box-1').each(function() {
        $(this).css('height', (window.innerHeight - $(this).offset().top - _panelMarginBottom) + 'px')
    });
    $('.dashboard-box-2').each(function() {
        $(this).css('height',(window.innerHeight - $(this).offset().top) + 'px')
    });

    // Notofications dropdown
    $('#notifications-btn .slimScrollDiv, #notifications-btn .scrollable-content').height($(window).height() - 200);

    // Dashboard messages panel
    if($('#dashboard-page #message-detail').length) {
        $('#dashboard-page #message-detail').outerHeight($('#contentPart').outerHeight() - 22);
        $('#dashboard-page #messages-list > div').outerHeight($('#contentPart').outerHeight() - 24);
    }

    // Lead name on topbar
    $('.topbar-lead-name').width($(window).width() - $('#header-logo').width() - $('#header-nav-left').width() - $('#header-nav-right').width() - 90);

    rowWidth();
    inputStringFieldsWidth();
}

window.onresize = function() {
    windowResize();

    // Body scrollable
    /*checkBodyScrollable();*/
}

window.onload = function() {
    $('body').css('height', window.InnerHeight + 'px');
    $('.dashboard-box, .dashboard-box-1').each(function() {
        $(this).css('overflow-x' ,'hidden');
        $(this).css('overflow-y' ,'auto');
        $(this).css('height' , (window.innerHeight - $(this).offset().top - _panelMarginBottom) + 'px')
    });
    $('.dashboard-box-2').each(function() {
        $(this).css('overflow-x', 'hidden');
        $(this).css('overflow-y', 'auto');
        $(this).css('height', (window.innerHeight - $(this).offset().top) + 'px')
    });

    windowResize();
}

function inputStringFieldsWidth() {
    $('.input-string-group').each(function(index, obj) {
        var $this = $(this),
            $label = $this.find('label'),
            $input = $this.find('.form-control');

        if(!$input.is(':focus')) {
            if($label.length) {
                $input.width($this.width() - $label.width() - 20);
            }
            else {
                $input.width($this.width() - 20);
            }

            if($input.width() <= 15) {
                $input.addClass('nopadding').css('text-align', 'right');
            }
            else {
                $input.removeClass('nopadding').css('text-align', 'left');
            }
        }
    });
}

function rowWidth() {
   

    $('.row.row-width').each(function(i, row) {
        var $row = $(row),
            nofixColsCount = $row.find('>div:not(.col-width)').length,
            fixColsWidth = 0;

        $row.find('>div.col-width').each(function(j, col) {
            var $col = $(col);
            fixColsWidth += $col.outerWidth();
        });

        if(nofixColsCount) {
            // $row.find('>div:not(.col-width)').outerWidth(($row.width() - fixColsWidth) / nofixColsCount - 2);
            $row.find('>div:not(.col-width)').each(function(j, col) {
                var $col = $(col),
                    colPercent = 100,
                    colClasses = $col.attr('class').split(' ');

                colClasses = colClasses.filter(function(x) {return x.indexOf('col-') !== -1 });
                if(colClasses.length) {
                    colClasses = colClasses[0].split('-')[2];

                    switch(colClasses) {
                        case '1':
                            colPercent = 8.3333;
                            break;
                        case '2':
                            colPercent = 16.6667;
                            break;
                        case '3':
                            colPercent = 25;
                            break;
                        case '4':
                            colPercent = 33.3333;
                            break;
                        case '5':
                            colPercent = 41.6667;
                            break;
                        case '6':
                            colPercent = 50;
                            break;
                        case '7':
                            colPercent = 58.3333;
                            break;
                        case '8':
                            colPercent = 66.6667;
                            break;
                        case '9':
                            colPercent = 75;
                            break;
                        case '10':
                            colPercent = 83.3333;
                            break;
                        case '11':
                            colPercent = 91.6667;
                            break;
                        case '12':
                            colPercent = 100;
                            break;
                        case '1x5':
                            colPercent = 20;
                            break;
                        default:
                            colPercent = 100;
                            break;
                    }

                    $col.outerWidth(($row.width() - fixColsWidth) * colPercent / 100 - 1);
                }
            });
        }
    });
}

$(document).ready(function() {
	//demo
   
    _inputStringFieldsWidthInterval = setInterval(inputStringFieldsWidth, _inputStringFieldsWidthIntervalPeriod);
    $('#close-sidebar').on('click', function() {
        clearInterval(_inputStringFieldsWidthInterval);
        $('.input-string-group .form-control').width(15);
        _inputStringFieldsWidthInterval = setInterval(inputStringFieldsWidth, _inputStringFieldsWidthIntervalPeriod);
    });

    _rowWidthInterval = setInterval(rowWidth, _rowWidthIntervalPeriod);
    $('#close-sidebar').on('click', function() {
        clearInterval(_rowWidthInterval);
        // $('.input-string-group .form-control').width(15);
        _rowWidthInterval = setInterval(rowWidth, _rowWidthIntervalPeriod);
    });

    body_sizer();

    $("div[id='#fixed-sidebar']").on('click', function() {

        if ($(this).hasClass("switch-on")) {
            var windowHeight = $(window).height();
            var headerHeight = $('#page-header').height();
            var contentHeight = windowHeight - headerHeight;

            $('#page-sidebar').css('height', contentHeight);
            $('.scroll-sidebar').css('height', contentHeight);
			/*
            $('.scroll-sidebar').slimscroll({
                height: '100%',
                color: 'rgba(155, 164, 169, 0.68)',
                size: '6px'
            });
			*/
            var headerBg = $('#page-header').attr('class');
            $('#header-logo').addClass(headerBg);

        } else {
            var windowHeight = $(document).height();
            var headerHeight = $('#page-header').height();
            var contentHeight = windowHeight - headerHeight;

            $('#page-sidebar').css('height', contentHeight);
            $('.scroll-sidebar').css('height', contentHeight);
			/*
            $(".scroll-sidebar").slimScroll({
                destroy: true
            });
			*/
            $('#header-logo').removeClass('bg-gradient-9');

        }

    });

    // Modal
    // $('.modal').on('shown.bs.modal', function () {
    //     $('body').css('overflow-y', 'hidden');
    // });
    // $('.modal').on('hidden.bs.modal', function () {
    //     // $('body').css('overflow-y', 'auto');
    //     $('body').css('overflow-y', 'hidden');
    // });

});

$(window).on('resize', function() {
    body_sizer();
});

function body_sizer() {

    if ($('body').hasClass('fixed-sidebar')) {

        var windowHeight = $(window).height();
        var headerHeight = $('#page-header').height();
        var contentHeight = windowHeight - headerHeight;

//        $('#page-sidebar').css('height', contentHeight);
  //      $('.scroll-sidebar').css('height', contentHeight);
//        $('#page-content').css('min-height', contentHeight);

    } else {

        var windowHeight = $(document).height();
        var headerHeight = $('#page-header').height();
        var contentHeight = windowHeight - headerHeight;

//        $('#page-sidebar').css('height', contentHeight);
  //      $('.scroll-sidebar').css('height', contentHeight);
       // $('#page-content').css('min-height', contentHeight);

    }

};

function pageTransitions() {

    var transitions = ['.pt-page-moveFromLeft', 'pt-page-moveFromRight', 'pt-page-moveFromTop', 'pt-page-moveFromBottom', 'pt-page-fade', 'pt-page-moveFromLeftFade', 'pt-page-moveFromRightFade', 'pt-page-moveFromTopFade', 'pt-page-moveFromBottomFade', 'pt-page-scaleUp', 'pt-page-scaleUpCenter', 'pt-page-flipInLeft', 'pt-page-flipInRight', 'pt-page-flipInBottom', 'pt-page-flipInTop', 'pt-page-rotatePullRight', 'pt-page-rotatePullLeft', 'pt-page-rotatePullTop', 'pt-page-rotatePullBottom', 'pt-page-rotateUnfoldLeft', 'pt-page-rotateUnfoldRight', 'pt-page-rotateUnfoldTop', 'pt-page-rotateUnfoldBottom'];
    for (var i in transitions) {
        var transition_name = transitions[i];
        if ($('.add-transition').hasClass(transition_name)) {

            $('.add-transition').addClass(transition_name + '-init page-transition');

            setTimeout(function() {
                $('.add-transition').removeClass(transition_name + ' ' + transition_name + '-init page-transition');
            }, 1200);
            return;
        }
    }

};

$(document).ready(function() {

    pageTransitions();

    /* Sidebar menu */
    $(function() {

        $('#sidebar-menu').superclick({
            animation: {
                height: 'show'
            },
            animationOut: {
                height: 'hide'
            }
        });

    });

    /* Colapse sidebar */
    $(function() {

        $('#close-sidebar').click(function() {
            $('body').toggleClass('closed-sidebar');
            $('.glyph-icon', this).toggleClass('icon-angle-right').toggleClass('icon-angle-left');
        });

    });

    /* Sidebar scroll */



});