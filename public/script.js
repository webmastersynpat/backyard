/** Slidebar functions */
function openSlidebar($slidebar, callback) {
    var slidebarWidth = $slidebar.width();

    $slidebar.show();
    $slidebar.addClass('sb-active');
    if ($slidebar.hasClass('sb-left')) {
		
        $slidebar.css('left', '0px');
    } else {
        $slidebar.css('right', '0px');
		
    }

    if (!$slidebar.hasClass('ui-resizable')) {
        $slidebar.resizable({
            handles: 'e, w',
            resize: function(event, ui) {
                // console.log($(ui).hasClass('sb-right'));
                if ($(ui).hasClass('sb-right')) {
                    // console.log($(ui).width());
                    // $(ui).css('right', '-' + $(ui).width() + 'px')
                    $(ui).css({
                        left: 'auto',
                        right: 0
                    });
                }
            }
        });
    }

    if (typeof callback === 'function') {
        callback();
    }

    return false;
}

function closeSlidebar($slidebar, callback) {
    var slidebarWidth = $slidebar.width();

    $slidebar.removeClass('sb-active');
    $slidebar.removeClass('is-open');
    if ($slidebar.hasClass('sb-left')) {
        $slidebar.css('left', -slidebarWidth + 'px');
    } else {
        $slidebar.css('right', -slidebarWidth + 'px');
    }

    if ($slidebar.hasClass('ui-resizable')) {
        $slidebar.resizable('destroy');
    }

    if (typeof callback === 'function') {
        callback();
    }

    return false;
}

function changeSlidebarSide($button, callback) {
    var $slidebar = $button.parent().parent();

    if ($slidebar.hasClass('sb-left')) {
        $slidebar.css('left', 'auto').css('right', 0);
        $slidebar.removeClass('sb-left').addClass('sb-right');
    } else {
        $slidebar.css('right', 'auto').css('left', 0);
        $slidebar.removeClass('sb-right').addClass('sb-left');
    }

    // if($slidebar.hasClass('ui-resizable')) {
    // 	$slidebar.resizable('destroy');
    // 	$slidebar.resizable({
    // 		handles: 'e, w'
    // 	});
    // }

    return false;
}



function getSectorsPage() {
    jQuery("#open_manage_sector").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_manage_sector"), function() {
        closeSlideBarLeftSector()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#open_manage_sector").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, c) {
    //         $(this).css("transform", "translate(-350px)")
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_manage_sector")),

    slidebarOpenCallback(jQuery("#open_manage_sector")), jQuery("#open_manage_sector_list").html('<iframe id="manageSectorIframe" src="' + __baseUrl + 'general/manage_sectors" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_manage_sector").addClass("is-open"), open_sector_searchResize())
}
$(window).on("resize", function() {
    open_sector_searchResize()
});

function closeSlideBarLeftSector() {
    jQuery("#open_manage_sector_list").html("");
    // jQuery("#open_manage_sector").removeClass("is-open");
    // jQuery("#open_manage_sector").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(700px)")
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_manage_sector"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function open_sector_searchResize() {
    var d = $("#manageSectorIframe"),
        e = $(window).height() - 40;
    d.length && (d.height(e), document.getElementById("manageSectorIframe").contentWindow.resizeDataTable && document.getElementById("manageSectorIframe").contentWindow.resizeDataTable(e - 100))
}

function open_advanced_search() {
    jQuery("#advanced_search").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#advanced_search"), function() {
        closeAdvancedSearch()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#advanced_search").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(350px)")
    //     },
    //     duration: "slow"
    // }, "linear"),
    openSlidebar(jQuery("#advanced_search")),

    slidebarOpenCallback(jQuery("#advanced_search")), _h = jQuery(window).height(), jQuery("#advanced_search").addClass("is-open"), open_advanced_searchResize())
}
$(window).on("resize", function() {
    open_advanced_searchResize()
});

function open_advanced_searchResize() {
    $("#advanced_search_form").height($(window).height() - 150)
}

function closeAdvancedSearch() {
    jQuery("#advanced_search_form").get(0).reset();
    jQuery("#emailListSearch").html("");
    // jQuery("#advanced_search").removeClass("is-open");
    // jQuery("#advanced_search").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(-700px)")
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#advanced_search")),

    $(".modal-backdrop-drive:eq(0)").remove()
}
$(window).load(function() {
    setTimeout(function() {
        $("#loading").fadeOut(400, "linear")
    }, 300)
});
pane = ""; /*var message="Function Disabled!";function clickIE4(){if(2==event.button){return alert(message),!1}}function clickNS4(c){if(document.layers||document.getElementById&&!document.all){if(2==c.which||3==c.which){return alert(message),!1}}}document.layers?(document.captureEvents(Event.MOUSEDOWN),document.onmousedown=clickNS4):document.all&&!document.getElementById&&(document.onmousedown=clickIE4);document.oncontextmenu=new Function("return false");*/
function changeDriveMode(c) {
    c.hasClass("nm") ? (c.removeClass("nm").addClass("wm"), c.parent().parent().css("width", "1500px")) : (c.removeClass("wm").addClass("nm"), c.parent().parent().css("width", "1020px"))
}
// function changeSlidebarSide(b){
// 	var c=b.parent().parent();

// 	if(c.hasClass("sb-left")){
// 		c.removeClass("sb-left");
// 		c.addClass("sb-right");
// 		c.css({marginLeft:"0",marginRight:"-350px"});
// 		c.css("transform","translate(-350px)")
// 	}
// 	else {
// 		c.removeClass("sb-right");
// 		c.addClass("sb-left");
// 		c.css({marginLeft:"-350px",marginRight:"0"});
// 		c.css("transform","translate(350px)")
// 	}
// }

setTimeout(function() {
    jQuery.ajax({
        type: "POST",
        url: __baseUrl + "opportunity/c_l",
        data: {
            s: "123@#sPQWnd*&TRYE"
        },
        cache: !1,
        success: function(c) {
            0 == jQuery("#web_lead").find("span").length ? jQuery("#web_lead").find("i").before('<span class="bs-badge badge-absolute bg-red" style="top:-8px;left:-10px;padding:0 7px 0 5px">' + c + "</span>") : jQuery("#web_lead").find("span").html(c)
        }
    })
}, 15000);
var idleTime = 0;
$(document).ready(function() {
    setInterval(timerIncrement, 60000);
    $(this).mousemove(function(c) {
        idleTime = 0;
        jQuery("#open_files_gd").hasClass("is-open") || 0 != jQuery("input[name='checkbox-example-2221']").prop("checked") || (chDa = 1, 0 == jQuery("#timmerPopup").is(":visible") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !0), jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on"), runLogin()))
    });
    $(this).keypress(function(c) {
        idleTime = 0;
        jQuery("#open_files_gd").hasClass("is-open") || 0 != jQuery("input[name='checkbox-example-2221']").prop("checked") || (chDa = 1, 0 == jQuery("#timmerPopup").is(":visible") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !0), jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on"), runLogin()))
    })
});
_PAN = _tL = 0;

function timerIncrement() {
    0 == _tL && jQuery.ajax({
        url: __baseUrl + "dashboard/check_login",
        type: "POST",
        data: {
            d: chDa
        },
        cache: !1,
        success: function(c) {
            _tL = 1;
            0 == jQuery("input[name='checkbox-example-2221']").prop("checked") && (chDa = 1, 0 == jQuery("#timmerPopup").is(":visible") && (chDa = 1, jQuery("input[name='checkbox-example-2221']").prop("checked", !0), jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on"), runLogin()))
        }
    });
    jQuery("input[name='checkbox-example-2221']").is(":checked") && (idleTime += 1, 20 < idleTime && jQuery("input[name='checkbox-example-2221']").prop("checked") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !1), chDa = 0, jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-on").addClass("bootstrap-switch-off"), runLogin(), jQuery("#timmerPopup").modal("show"), jQuery(document).ready(function() {
        jQuery("#timmerPopup").on("hidden.bs.modal", function() {
            jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on")
        })
    })));
    jQuery("#open_files_gd").hasClass("is-open") && 0 == _PAN && (chDa = 0, _PAN = 1, jQuery("input[name='checkbox-example-2221']").prop("checked", !1), jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-on").addClass("bootstrap-switch-off"), runLogin())
}

function slidebarOpenCallback(c) {
    var d = $("body").data("modalzindex");
    d += 1;
    $("body").data("modalzindex", d);
    c.css("z-index", d)
}

function closeSlideBarRightList() {
    jQuery("#open_list").html("");
    // jQuery("#open_all_list").removeClass("is-open");
    // jQuery("#open_all_list").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(700px)")
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_all_list"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function open_all_list() {
    jQuery("#open_all_list").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_all_list"), function() {
        closeSlideBarRightList()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#open_all_list").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_all_list").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(350px)"), $(this).css({
    //                 marginLeft: "-350px",
    //                 marginRight: "0"
    //             }), alert('sdsdsds')
    //         } else {
    //             $(this).css("transform", "translate(-350px)"), $(this).css({
    //                 marginLeft: "0",
    //                 marginRight: "-350px"
    //             }, alert('dsd'))
    //         }
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_all_list")),


    slidebarOpenCallback(jQuery("#open_all_list")), jQuery("#open_list").html('<iframe id="allListIframe" src="' + __baseUrl + 'opportunity/all_list" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_all_list").addClass("is-open"), open_all_listResize())
}
$(window).on("resize", function() {
    var c = $("#allListIframe");
    c.length && c.is(":visible") && open_all_listResize()
});

function open_all_listResize() {
    var d = $("#allListIframe"),
        e = $(window).height() - 100;
    d.length && d.height(e)
}

function open_contact_list() {
    jQuery("#open_contact_gd").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_contact_gd"), function() {
        closeSlideBarLeftContact()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#open_contact_gd").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_contact_gd").hasClass("sb-left")) {
    //             $(this).css({
    //                 marginLeft: "-350px",
    //                 marginRight: "0"
    //             }), $(this).css("transform", "translate(350px)")
    //         } else {
    //             $(this).css({
    //                 marginLeft: "0",
    //                 marginRight: "-350px"
    //             }), $(this).css("transform", "translate(-350px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_contact_gd")),


    slidebarOpenCallback(jQuery("#open_contact_gd")), jQuery.ajax({
        type: "POST",
        url: __baseUrl + "dashboard/search_contact",
        cache: !1,
        success: function(c) {
            availableTags = jQuery.parseJSON(c)
        }
    }), _h = jQuery(window).height(), jQuery("#open_contact_list").html('<iframe id="contactFormIframe" src="' + __baseUrl + 'opportunity/contact" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_contact_gd").addClass("is-open"), open_contact_listResize())
}

function openCContact() {
    jQuery("#open_ccompany_gd").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_ccompany_gd"), function() {
        closeSlideBarLeftCCompany()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#open_ccompany_gd").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(-350px)")
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_ccompany_gd")),

    slidebarOpenCallback(jQuery("#open_ccompany_gd")), _h = jQuery(window).height(), jQuery("#open_ccompany_list").html('<iframe id="companyFormIframe" src="' + __baseUrl + 'opportunity/companies" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_ccompany_gd").addClass("is-open"), open_ccompany_listResize())
}

function closeSlideBarLeftCCompany() {
    jQuery("#open_ccompany_list").html("");
    // jQuery("#open_ccompany_gd").removeClass("is-open");
    // jQuery("#open_ccompany_gd").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         $(this).css("transform", "translate(700px)")
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_ccompany_gd"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function open_sales_list(b) {
    jQuery("#open_sales_gd").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_sales_gd"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), 

    // jQuery("#open_sales_gd").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_sales_gd").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(350px)")
    //         } else {
    //             $(this).css("transform", "translate(-350px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_sales_gd")),

    slidebarOpenCallback(jQuery("#open_sales_gd")), jQuery("#open_sales_list").html('<iframe id="salesFormIframe" src="' + __baseUrl + "opportunity/sales_contact/" + leadGlobal + "/" + b + '" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_sales_gd").addClass("is-open"), open_sales_listResize())
}
$(window).on("resize", function() {
    var c = $("#contactFormIframe");
    c.length && c.is(":visible") && open_contact_listResize();
    c = $("#salesFormIframe");
    c.length && c.is(":visible") && open_sales_listResize()
});

function open_ccompany_listResize() {
    var d = $("#companyFormIframe"),
        e = $(window).height() - 80;
    d.length && (d.height(e), document.getElementById("companyFormIframe").contentWindow.resizeDataTable && document.getElementById("companyFormIframe").contentWindow.resizeDataTable(e - 100))
}

function open_contact_listResize() {
    var d = $("#contactFormIframe"),
        e = $(window).height() - 80;
    d.length && (d.height(e), document.getElementById("contactFormIframe").contentWindow.resizeDataTable && document.getElementById("contactFormIframe").contentWindow.resizeDataTable(e - 100))
}

function open_sales_listResize() {
    var d = $("#salesFormIframe"),
        e = $(window).height() - 80;
    d.length && (d.height(e), document.getElementById("salesFormIframe").contentWindow.resizeDataTable && document.getElementById("salesFormIframe").contentWindow.resizeDataTable(e - 100))
}

function closeSlideBarLeftContact() {
    jQuery("#open_contact_list").html("");
    // jQuery("#open_contact_gd").removeClass("is-open");
    // jQuery("#open_contact_gd").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_contact_gd").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(-700px)")
    //         } else {
    //             $(this).css("transform", "translate(700px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_contact_gd"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function closeSlideBarLeftSales() {
    jQuery("#open_sales_list").html("");
    // jQuery("#open_sales_gd").removeClass("is-open");
    // jQuery("#open_sales_gd").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_sales_gd").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(-700px)")
    //         } else {
    //             $(this).css("transform", "translate(700px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_sales_gd"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function closeSlideBarLucid() {
    jQuery("#scrapLucid").html("");
    // jQuery("#scrapLucidData").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#scrapLucidData").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(-700px)")
    //         } else {
    //             $(this).css("transform", "translate(700px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#scrapLucidData"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function closeSlideBarLeftMessage() {
    // jQuery("#gmail_message_modal").removeClass("sb-active").animate(
    // 	{
    // 		textIndent:0
    // 	},
    // 	{
    // 		step:function(d,e) {
    // 		if(jQuery("#gmail_message_modal").hasClass("sb-left")) {
    // 			$(this).css("transform","translate(-700px)")
    // 		}
    // 		else {
    // 			$(this).css("transform","translate(700px)")
    // 		}
    // 	},
    // 	duration:"slow"
    // },"linear");
    closeSlidebar(jQuery("#gmail_message_modal"));

    jQuery("body").attr("onselectstart", "return false");

    document.oncontextmenu = new Function("return false");
    $(".modal-backdrop-drive:eq(0)").remove();
    jQuery("#eventCid").val(0);
    jQuery("#eventPid").val(0);

    // jQuery("#gmail_message_modal")
    // 	.removeClass('sb-right')
    // 	.addClass('sb-left')
    // 	.css({
    // 		marginRight:'0px',
    // 		marginLeft:'-350px',
    // 		transform:'translate(-700px)'
    // 	})
}

function closeSlideBarGoogle() {
    jQuery("#scrapGooglePatent").html("");
    // jQuery("#scrapGoogleData").removeClass("sb-active").animate(
    // 	{
    // 		textIndent:0
    // 	},
    // 	{
    // 		step:function(d,e){
    // 			if(jQuery("#scrapGoogleData").hasClass("sb-left")){
    // 				$(this).css("transform","translate(-700px)")
    // 			}
    // 			else {
    // 				$(this).css("transform","translate(700px)")
    // 			}
    // 		},
    // 		duration:"slow"
    // 	},"linear");	
    closeSlidebar(jQuery("#scrapGoogleData"));

    $(".modal-backdrop-drive:eq(0)").remove()
}

function openExcelSheet() {
    jQuery("#excelData").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#excelData"), function() {
        closeSlideBarLeftGoogle()
    }) : (jQuery("input[name='checkbox-example-2221']").prop("checked") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !1), chDa = 0, jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-on").addClass("bootstrap-switch-off"), runLogin()), $("body").append('<div class="modal-backdrop modal-backdrop-excel"></div>'), jQuery("#excelData").find(".pad15A").html('<div class="loading-spinner" id="loading_spinner_heading_excel_scrap" style="display:none;"><img src="public/images/ajax-loader.gif" alt=""></div><div id="excelSheet"></div>'),

        jQuery("#loading_spinner_heading_excel_scrap").css("display", "block"),
        // jQuery("#excelData").addClass("sb-active").animate({
        //     textIndent: 0
        // }, {
        //     step: function(d, e) {
        //         if (jQuery("#excelData").hasClass("sb-left")) {
        //             $(this).css("transform", "translate(350px)")
        //         } else {
        //             $(this).css("transform", "translate(-350px)")
        //         }
        //     },
        //     duration: "slow"
        // }, "linear");
        openSlidebar(jQuery("#excelData")),

        slidebarOpenCallback(jQuery("#excelData")),
        jQuery("#loading_spinner_heading_excel_scrap").css("display", "none"), jQuery("#excelSheet").html("<iframe src='" + snapGlobal + "' width='100%' height='800px;'></iframe>"),
        jQuery("#excelData").addClass("is-open"))
}

function closeSlideBarLeftGoogle() {
    0 == jQuery("input[name='checkbox-example-2221']").prop("checked") && (chDa = 1, 0 == jQuery("#timmerPopup").is(":visible") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !0),
        jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on"),
        runLogin()));

    // jQuery("#excelData").addClass("sb-active").animate(
    // 	{textIndent:0},
    // 	{step:function(d,e){
    // 		if(jQuery("#excelData").hasClass("sb-left")){
    // 			$(this).css("transform","translate(-700px)")
    // 		}
    // 		else {
    // 			$(this).css("transform","translate(700px)")
    // 		}
    // 	},
    // 	duration:"slow"
    // },"linear");
    // jQuery("#excelData").removeClass("sb-active").removeClass("is-open");
    closeSlidebar(jQuery("#excelData"));

    jQuery(".modal-backdrop-excel").remove()
}

function open_drive_files(c) {
    jQuery("#other_list_boxes").find("table>tbody").find("tr>td").removeClass("active");
    jQuery("#displayEmail").html("");
    if (jQuery("#open_files_gd").hasClass("is-open")) {
        checkModalFrontOrHide(jQuery("#open_files_gd"), function() {})
    }(jQuery("input[name='checkbox-example-2221']").prop("checked") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !1), chDa = 0, jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-on").addClass("bootstrap-switch-off"), runLogin()), $("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), jQuery("#open_files_gd").find(".pad15A").html('<div class="loading-spinner" id="loading_spinner_heading_drive" style="display:block;"><img src="public/images/ajax-loader.gif" alt=""></div><div id="open_drive_files"></div>'), jQuery("#loading_spinner_heading_excel_scrap").css("display", "block"), 

    // jQuery("#open_files_gd").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(e, f) {
    //         if (jQuery("#open_files_gd").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(350px)")
    //         } else {
    //             $(this).css("transform", "translate(-350px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear"), 
    openSlidebar(jQuery("#open_files_gd")),

    slidebarOpenCallback(jQuery("#open_files_gd")), jQuery.ajax({
        type: "POST",
        url: __baseUrl + "dashboard/validateDriveFilePermission",
        data: {
            f: c
        },
        cache: !1,
        success: function(d) {}
    }), jQuery("#loading_spinner_heading_drive").css("display", "none"), jQuery("#open_drive_files").html('<iframe src="' + c + '" width="100%" height="800px;"></iframe>'), jQuery("#open_files_gd").addClass("is-open"));
    open_drive_files_resize();
}
jQuery(window).on('resize', function() {
    open_drive_files_resize()
});

function open_drive_files_resize() {
    jQuery('#open_drive_files iframe').height(jQuery(window).height() - 110)
}

function closeSlideBarLeftDrive() {
    0 == jQuery("input[name='checkbox-example-2221']").prop("checked") && (console.log("Left Drive Close"),
        chDa = 1, 0 == jQuery("#timmerPopup").is(":visible") && (jQuery("input[name='checkbox-example-2221']").prop("checked", !0),
            jQuery(".bootstrap-switch-wrapper").removeClass("bootstrap-switch-off").addClass("bootstrap-switch-on"), runLogin()));

    // jQuery("#open_files_gd").find("a").removeClass("wm").addClass("nm");
    // jQuery("#open_files_gd").css("width","1020px").addClass("sb-active").animate(
    // 	{
    // 		textIndent: 0
    // 	},
    // 	{
    // 		step: function(d,e) {
    // 			if(jQuery("#open_files_gd").hasClass("sb-left")) {
    // 				$(this).css("transform","translate(-700px)")
    // 			}
    // 			else {
    // 				$(this).css("transform","translate(700px)")
    // 			}
    // 		},
    // 		duration:"slow"
    // 	}, "linear");
    closeSlidebar(jQuery("#open_files_gd")),

    // jQuery("#open_files_gd").removeClass("sb-active").removeClass("is-open");
    jQuery(".modal-backdrop-drive").remove()
}

function addAttendees() {
    jQuery("#attendeesPlaceholder").find(".col-xs-12").append('<input type="text" name="email[]" class="form-control input-string" placeholder="Enter guest email adresses">')
}

function insertEvent() {
    0 < leadGlobal ? (jQuery("#lead_id").val(leadGlobal), jQuery("#btnEvent").addClass("hide"), _eventE = 0, _eventM = "", "" == jQuery("#eventSummary").val() && (_eventM = "Please enter title for event.", _eventE = 1), 0 == _eventE && "" == jQuery("#eventStartDate").val() && (_eventM = "Please enter start date for event.", _eventE = 1), 0 == _eventE && "" == jQuery("#eventStartTime").val() && (_eventM = "Please enter start time for event.", _eventE = 1), 0 == _eventE && "" == jQuery("#eventEndDate").val() && (_eventM = "Please enter end date for event.", _eventE = 1), 0 == _eventE && "" == jQuery("#eventEndTime").val() && (_eventM = "Please enter end time for event.", _eventE = 1), 0 == _eventE && "" == jQuery("#attendeeEmail").val() && (_eventM = "Please enter atleast one email address.", _eventE = 1), 0 == _eventE ? jQuery.ajax({
        type: "POST",
        url: __baseUrl + "users/insert_event",
        data: jQuery("#frm_calendar_event").serializeArray(),
        cache: !1,
        success: function(c) {
            jQuery("#btnEvent").removeClass("hide");
            threadDetail(jQuery("#all_type_list").find("tbody").find("tr.active"))
        }
    }) : alert(_eventM)) : alert("Please select lead first")
}

function open_all_invitation() {
    jQuery("#open_invitation").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_invitation"), function() {
        close_all_invitation()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), jQuery("#eventPredefinedMessage").attr("src", __baseUrl + "users/predefined_templates/2"), 

    // jQuery("#open_invitation").addClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_invitation").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(350px)")
    //         } else {
    //             $(this).css("transform", "translate(-350px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear"),
    openSlidebar(jQuery("#open_invitation")),

    slidebarOpenCallback(jQuery("#open_invitation")), jQuery("#open_invitation").addClass("is-open"))
}

function close_all_invitation() {
    // jQuery("#open_invitation").removeClass("is-open");
    // jQuery("#open_invitation").removeClass("sb-active").animate({
    //     textIndent: 0
    // }, {
    //     step: function(d, e) {
    //         if (jQuery("#open_invitation").hasClass("sb-left")) {
    //             $(this).css("transform", "translate(-700px)")
    //         } else {
    //             $(this).css("transform", "translate(700px)")
    //         }
    //     },
    //     duration: "slow"
    // }, "linear");
    closeSlidebar(jQuery("#open_invitation"));

    $(".modal-backdrop-drive:eq(0)").remove()
}
$(document).ready(function() {
    $(".switch-button").click(function(d) {
        d.preventDefault();
        d = $(this).attr("switch-parent");
        var e = $(this).attr("switch-target");
        $(d).slideToggle();
        $(e).slideToggle()
    });
    $(".hidden-button").hover(function() {
        $(".btn-hide", this).fadeIn("fast")
    }, function() {
        $(".btn-hide", this).fadeOut("normal")
    });
    $(".toggle-button").click(function(c) {
        c.preventDefault();
        $(".glyph-icon", this).toggleClass("icon-rotate-180");
        $(this).parents(".content-box:first").find(".content-box-wrapper").slideToggle()
    });
    $(".remove-button").click(function(e) {
        e.preventDefault();
        var g = $(this).attr("data-animation"),
            f = $(this).parents(".content-box:first");
        $(f).addClass("animated");
        $(f).addClass(g);
        window.setTimeout(function() {
            $(f).slideUp()
        }, 500);
        window.setTimeout(function() {
            $(f).removeClass(g).fadeIn()
        }, 2500)
    });
    $(function() {
        $(".infobox-close").click(function(c) {
            c.preventDefault();
            $(this).parent().fadeOut()
        })
    })
});
$(document).ready(function() {
    $(".overlay-button").click(function() {
        var e = $(this).attr("data-theme"),
            g = $(this).attr("data-opacity"),
            f = $(this).attr("data-style"),
            e = '<div id="loader-overlay" class="ui-front loader ui-widget-overlay ' + e + " opacity-" + g + '"><img src="../../assets/images/spinner/loader-' + f + '.gif" alt="" /></div>';
        $("#loader-overlay").length && $("#loader-overlay").remove();
        $("body").append(e);
        $("#loader-overlay").fadeIn("fast");
        setTimeout(function() {
            $("#loader-overlay").fadeOut("fast")
        }, 3000)
    });
    $(".refresh-button").click(function(e) {
        $(".glyph-icon", this).addClass("icon-spin");
        e.preventDefault();
        e = $(this).parents(".content-box");
        var i = $(this).attr("data-theme"),
            h = $(this).attr("data-opacity"),
            g = $(this).attr("data-style"),
            i = '<div id="refresh-overlay" class="ui-front loader ui-widget-overlay ' + i + " opacity-" + h + '"><img src="../../assets/images/spinner/loader-' + g + '.gif" alt="" /></div>';
        $("#refresh-overlay").length && $("#refresh-overlay").remove();
        $(e).append(i);
        $("#refresh-overlay").fadeIn("fast");
        setTimeout(function() {
            $("#refresh-overlay").fadeOut("fast");
            $(".glyph-icon", this).removeClass("icon-spin")
        }, 1500)
    })
});
$(function() {
    $('a[href="#"]').click(function(c) {
        c.preventDefault()
    })
});
$(function() {
    $(".todo-box li input").on("click", function() {
        $(this).parent().toggleClass("todo-done")
    })
});
$(function() {
    var c = 0;
    $(".timeline-scroll .tl-row").each(function(g, e) {
        var b = $(e);
        c += b.outerWidth() + parseInt(b.css("margin-left"), 10) + parseInt(b.css("margin-right"), 10)
    });
    $(".timeline-horizontal", this).width(c)
});
$(function() {
    $(".scrollable-slim").slimScroll({
        color: "#8da0aa",
        size: "10px",
        alwaysVisible: !0
    })
});
$(function() {
    $(".scrollable-slim-sidebar").slimScroll({
        color: "#8da0aa",
        size: "10px",
        height: "100%",
        alwaysVisible: !0
    })
});
$(function() {
    $(".scrollable-slim-box").slimScroll({
        color: "#8da0aa",
        size: "6px",
        alwaysVisible: !1
    })
});
$(function() {
    $(".tooltip-button").tooltip({
        container: "body",
        trigger: 'hover',
        delay: {
            "show": 2000, 
            "hide": 100
        }
    });
});
var _panelMarginBottom = 4,
    _inputStringFieldsWidthInterval = null,
    _inputStringFieldsWidthIntervalPeriod = 700,
    _rowWidthInterval = null,
    _rowWidthIntervalPeriod = 300;

function windowResize() {
    $("body").css("height", window.InnerHeight + "px");
    $(".dashboard-box, .dashboard-box-1").each(function() {
        $(this).css("height", window.innerHeight - $(this).offset().top - _panelMarginBottom + "px")
    });
    jQuery("#my_c_task_list").find(".dashboard-box").each(function() {
        $(this).css("height", window.innerHeight - $(this).offset().top + "px")
    });
    $(".dashboard-box-2").each(function() {
        $(this).css("height", window.innerHeight - $(this).offset().top + "px")
    });
    $("#notifications-btn .slimScrollDiv, #notifications-btn .scrollable-content").height($(window).height() - 200);
    $("#dashboard-page #message-detail").length && ($("#dashboard-page #message-detail").outerHeight($("#contentPart").outerHeight() - 22), $("#dashboard-page #messages-list > div").outerHeight($("#contentPart").outerHeight() - 24));
    rowWidth();
    inputStringFieldsWidth()
}
window.onresize = function() {
    windowResize()
};
window.onload = function() {
    $("body").css("height", window.InnerHeight + "px");
    $(".dashboard-box, .dashboard-box-1").each(function() {
        $(this).css("overflow-x", "hidden");
        $(this).css("overflow-y", "auto");
        $(this).css("height", window.innerHeight - $(this).offset().top - _panelMarginBottom + "px")
    });
    $(".dashboard-box-2").each(function() {
        $(this).css("overflow-x", "hidden");
        $(this).css("overflow-y", "auto");
        $(this).css("height", window.innerHeight - $(this).offset().top + "px")
    });
    windowResize()
};

function inputStringFieldsWidth() {
    $(".input-string-group").each(function(e, l) {
        var k = $(this),
            i = k.find("label"),
            h = k.find(".form-control");
        h.is(":focus") || (i.length ? h.width(k.width() - i.width() - 20) : h.width(k.width() - 20), 15 >= h.width() ? h.addClass("nopadding").css("text-align", "right") : h.removeClass("nopadding").css("text-align", "left"))
    })
}

function rowWidth() {
    $(".row.row-width").each(function(e, l) {
        var k = $(l),
            i = k.find(">div:not(.col-width)").length,
            h = 0;
        k.find(">div.col-width").each(function(f, m) {
            var g = $(m);
            h += g.outerWidth()
        });
        i && k.find(">div:not(.col-width)").each(function(d, n) {
            var g = $(n),
                m = 100,
                m = g.attr("class").split(" "),
                m = m.filter(function(c) {
                    return -1 !== c.indexOf("col-")
                });
            if (m.length) {
                m = m[0].split("-")[2];
                switch (m) {
                    case "1":
                        m = 8.3333;
                        break;
                    case "2":
                        m = 16.6667;
                        break;
                    case "3":
                        m = 25;
                        break;
                    case "4":
                        m = 33.3333;
                        break;
                    case "5":
                        m = 41.6667;
                        break;
                    case "6":
                        m = 50;
                        break;
                    case "7":
                        m = 58.3333;
                        break;
                    case "8":
                        m = 66.6667;
                        break;
                    case "9":
                        m = 75;
                        break;
                    case "10":
                        m = 83.3333;
                        break;
                    case "11":
                        m = 91.6667;
                        break;
                    case "12":
                        m = 100;
                        break;
                    case "1x5":
                        m = 20;
                        break;
                    default:
                        m = 100
                }
                g.outerWidth((k.width() - h) * m / 100 - 1)
            }
        })
    })
}
$(document).ready(function() {
    _inputStringFieldsWidthInterval = setInterval(inputStringFieldsWidth, _inputStringFieldsWidthIntervalPeriod);
    $("#close-sidebar").on("click", function() {
        clearInterval(_inputStringFieldsWidthInterval);
        $(".input-string-group .form-control").width(15);
        _inputStringFieldsWidthInterval = setInterval(inputStringFieldsWidth, _inputStringFieldsWidthIntervalPeriod)
    });
    _rowWidthInterval = setInterval(rowWidth, _rowWidthIntervalPeriod);
    $("#close-sidebar").on("click", function() {
        clearInterval(_rowWidthInterval);
        _rowWidthInterval = setInterval(rowWidth, _rowWidthIntervalPeriod)
    });
    body_sizer();
    $("div[id='#fixed-sidebar']").on("click", function() {
        if ($(this).hasClass("switch-on")) {
            var d = $(window).height(),
                e = $("#page-header").height(),
                d = d - e;
            $("#page-sidebar").css("height", d);
            $(".scroll-sidebar").css("height", d);
            d = $("#page-header").attr("class");
            $("#header-logo").addClass(d)
        } else {
            d = $(document).height(), e = $("#page-header").height(), d -= e, $("#page-sidebar").css("height", d), $(".scroll-sidebar").css("height", d), $("#header-logo").removeClass("bg-gradient-9")
        }
    });
    $("body").data("modalzindex", 1050);
    $(".modal").on("shown.bs.modal", function() {
        var c = $("body").data("modalzindex"),
            c = c + 1;
        $("body").data("modalzindex", c);
        $(this).css("z-index", c)
    })
});
$(window).on("resize", function() {
    body_sizer()
});

function body_sizer() {
    $("body").hasClass("fixed-sidebar") ? $(window).height() : $(document).height();
    $("#page-header").height()
}

function pageTransitions() {
    var e = ".pt-page-moveFromLeft pt-page-moveFromRight pt-page-moveFromTop pt-page-moveFromBottom pt-page-fade pt-page-moveFromLeftFade pt-page-moveFromRightFade pt-page-moveFromTopFade pt-page-moveFromBottomFade pt-page-scaleUp pt-page-scaleUpCenter pt-page-flipInLeft pt-page-flipInRight pt-page-flipInBottom pt-page-flipInTop pt-page-rotatePullRight pt-page-rotatePullLeft pt-page-rotatePullTop pt-page-rotatePullBottom pt-page-rotateUnfoldLeft pt-page-rotateUnfoldRight pt-page-rotateUnfoldTop pt-page-rotateUnfoldBottom".split(" "),
        g;
    for (g in e) {
        var f = e[g];
        if ($(".add-transition").hasClass(f)) {
            $(".add-transition").addClass(f + "-init page-transition");
            setTimeout(function() {
                $(".add-transition").removeClass(f + " " + f + "-init page-transition")
            }, 1200);
            break
        }
    }
}
$(document).ready(function() {
    pageTransitions();
    $(function() {
        $("#sidebar-menu").superclick({
            animation: {
                height: "show"
            },
            animationOut: {
                height: "hide"
            }
        })
    });
    $("#emailOpenModal").on("hidden.bs.modal", function() {
        $("#emailOpenContent").empty()
    });
    $(function() {
        $("#close-sidebar").click(function() {
            $("body").toggleClass("closed-sidebar");
            $(".glyph-icon", this).toggleClass("icon-angle-right").toggleClass("icon-angle-left")
        })
    })
});

function closeSlideBarLeftMessagePredfined() {
    closeSlidebar(jQuery("#open_prefined_message"));
    $(".modal-backdrop-drive:eq(0)").remove()
}

function getPredefinedMessages(c) {
    jQuery("#open_prefined_message").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_prefined_message"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'), openSlidebar(jQuery("#open_prefined_message")),    slidebarOpenCallback(jQuery("#open_prefined_message")), jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="' + __baseUrl + "users/predefined_templates/" + c + '" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_prefined_message").addClass("is-open"), open_prefined_listResize())
}

function open_prefined_listResize() {
    var d = $("#predefineFormIframe"),
        e = $(window).height() - 100;
    d.length && (d.height(e), document.getElementById("predefineFormIframe").contentWindow.resizeDataTable && document.getElementById("predefineFormIframe").contentWindow.resizeDataTable(e - 100))
}

function getLeadTemplates(c) {
    a = 1;
    jQuery("#open_prefined_message").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_prefined_message"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")), slidebarOpenCallback(jQuery("#open_prefined_message")), jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="' + __baseUrl + "users/lead_templates/" + leadGlobal + "/" + c + '" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_prefined_message").addClass("is-open"), open_prefined_listResize())
}
$(window).on("resize", function() {
    leadTemplatesResize()
});

function leadTemplatesResize() {
    $("#predefineFormIframe").height($(window).height() - 110);
    document.getElementById("predefineFormIframe") && document.getElementById("predefineFormIframe").contentWindow && document.getElementById("predefineFormIframe").contentWindow.resizeDataTable && document.getElementById("predefineFormIframe").contentWindow.resizeDataTable($(window).height())
}

function findEmailsFromSearchCriteria() {
    jQuery("#emailListSearch").html('<div class="loading-spinner" id="loading_spinner_heading_drive" style="display:block;"><img src="public/images/ajax-loader.gif" alt=""></div>');
    jQuery.ajax({
        url: __baseUrl + "users/search_gmail",
        type: "POST",
        data: jQuery("#advanced_search_form").serializeArray(),
        cache: !1,
        success: function(c) {
            jQuery("#emailListSearch").html(c)
        }
    })
}

function findNewThread(c) {
    "object" == typeof _globalAjax && _globalAjax.abort();
    j = 296;
    jQuery("#displayEmail").html('<iframe src="' + __baseUrl + "users/search_email/" + jQuery.trim(c) + '" scrolling="yes" width="100%" height="' + j + '">')
}

function callFromLandline(b) {
    _number = "";
    if (jQuery("#user_mobile_number").length > 0) {
        _number = jQuery("#user_mobile_number").val()
    } else {
        if (window.parent.jQuery("#user_mobile_number").length > 0) {
            _number = window.parent.jQuery("#user_mobile_number").val()
        }
    }
    jQuery.ajax({
        url: "http://backyard.synpat.com/vendor/ringcentral/ringcentral-php/demo/ringout.php?p=" + b + "&f=" + _number,
        cache: false,
        success: function() {}
    })
}

function myProfile() {
    jQuery("#open_prefined_message").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_prefined_message"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")), slidebarOpenCallback(jQuery("#open_prefined_message")), jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="' + __baseUrl + 'users/profile" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_prefined_message").addClass("is-open"), open_prefined_listResize())
}

function getAllLeadsPointing(ac, ea) {
    jQuery("#open_prefined_message").hasClass("is-open") ? checkModalFrontOrHide(jQuery("#open_prefined_message"), function() {
        closeSlideBarLeftSales()
    }) : ($("body").append('<div class="modal-backdrop modal-backdrop-drive"></div>'),openSlidebar(jQuery("#open_prefined_message")), slidebarOpenCallback(jQuery("#open_prefined_message")), jQuery("#open_prefined_message_list").html('<iframe id="predefineFormIframe" src="' + __baseUrl + 'leads/all_active_system/' + ac + '/' + ea + '" width="100%" height="100px" scrolling="yes"></iframe>'), jQuery("#open_prefined_message").addClass("is-open"), open_prefined_listResize())
}