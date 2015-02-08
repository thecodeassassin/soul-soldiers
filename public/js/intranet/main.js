$(function() {

//    var scoreCount = $('.scoreCount');
//
//    if (scoreCount[0]) {
//
//        $('form.scoreAdd').submit(function(e){
//            e.preventDefault();
//            ajaxLoad(true);
////
////            var thisScoreCount = $(this).parent().siblings('.badge'),
////                scoreCountEl = $(this).find('.scoreCount');
//
//            $.ajax({
//                url: $(this).attr('action'),
//                method: 'POST',
//                data: $(this).serialize(),
//                success: function(newScore) {
////                    thisScoreCount.html(newScore);
////
////                    scoreCountEl.val(0);
//                    location.reload();
//                },
//                complete: function() {
//                    ajaxLoad(false);
//                }
//            });
//
//        });

//    }

    $('.generate-players').click(function(){
       var count = prompt('Hoeveel spelers moeten er gegeneerd worden?', 4),
           tournamentId = $(this).attr('data-tournament-id');
       if (count > 0) {
           document.location.href =  '/admin/tournaments/generate-players/' + tournamentId + '/' + count;
       }

    });

    $('.matchLink').click(function(e) {
        e.preventDefault();

        var tournamentId = $(this).attr('data-tournament-id');
        $.magnificPopup.open({
            type:'image',
            mainClass: 'mfp-fade',
            items: {src: '/static/image/'+tournamentId+'.png?ts='+new Date().getTime()},
            gallery: {
                enabled: true
            }
        });

    });

    $('.action-btn').click(function() {
        ajaxLoad(true);
       $(this).attr('disabled', 'disabled');
    });

    $('#isTeamTournament').click(function() {
        var teamSize = $('#teamSize');
       if (!$(this).prop('checked')){
           teamSize.val('');
       } else {
           if (teamSize.val() == '') {
               teamSize.val(2);
           }
       }
     });

    $("#startDate").mask("9999-99-99 99:99:99");
    activateToolTips();
});


var webApp=function(){var a=function(){var a=$("#year-copy"),e=new Date;a.html(2013===e.getFullYear()?"2013":"2013-"+e.getFullYear().toString().substr(2,2));var i=$("#page-content");i.css("min-height",$(window).height()-($("header").height()+$("footer").outerHeight())+"px"),$(window).resize(function(){i.css("min-height",$(window).height()-($("header").height()+$("footer").outerHeight())+"px")}),$("header").hasClass("navbar-fixed-top")?$("#page-container").addClass("header-fixed-top"):$("header").hasClass("navbar-fixed-bottom")&&$("#page-container").addClass("header-fixed-bottom"),$("thead input:checkbox").click(function(){var a=$(this).prop("checked"),e=$(this).closest("table");$("tbody input:checkbox",e).each(function(){$(this).prop("checked",a)})}),$('[data-toggle="tabs"] a').click(function(a){a.preventDefault(),$(this).tab("show")}),$('[data-toggle="gallery-options"] > li').mouseover(function(){$(this).find(".thumbnails-options").show()}).mouseout(function(){$(this).find(".thumbnails-options").hide()}),$(".scrollable").slimScroll({height:"100px",size:"3px",touchScrollStep:100}),$(".scrollable-tile").slimScroll({height:"130px",size:"3px",touchScrollStep:100}),$(".scrollable-tile-2x").slimScroll({height:"330px",size:"3px",touchScrollStep:100}),$('[data-toggle="tooltip"]').tooltip({container:"body",animation:!1}),$('[data-toggle="popover"]').popover({container:"body",animation:!1}),$(".select-chosen").chosen(),$(".select-select2").select2(),$(".input-switch").bootstrapSwitch(),$("textarea.textarea-elastic").elastic(),$("textarea.textarea-editor").wysihtml5(),$(".input-colorpicker").colorpicker(),$(".input-timepicker").timepicker(),$(".input-datepicker").datepicker(),$(".input-daterangepicker").daterangepicker(),$("input, textarea").placeholder()},e=function(){$(".loading-on").click(function(){var a=$("#loading");a.fadeIn(250),$("#widgets > li > a > .badge").addClass("display-none"),setTimeout(function(){a.fadeOut(250),$("#rss-widget > a > .badge").removeClass("display-none").html("5"),$("#twitter-widget > a > .badge").removeClass("display-none").html("4")},1e3)});var a=["Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","CÃ´te d'Ivoire","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Democratic Republic of the Congo","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Faeroe Islands","Falkland Islands","Fiji","Finland","Former Yugoslav Republic of Macedonia","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Heard Island and McDonald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","North Korea","Northern Marianas","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn Islands","Poland","Portugal","Puerto Rico","Qatar","RÃ©union","Romania","Russia","Rwanda","SÃ£o TomÃ© and PrÃ­ncipe","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and the Grenadines","Samoa","San Marino","Saudi Arabia","Senegal","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and the South Sandwich Islands","South Korea","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","The Bahamas","The Gambia","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","US Virgin Islands","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Wallis and Futuna","Western Sahara","Yemen","Yugoslavia","Zambia","Zimbabwe"];$(".example-typeahead").typeahead({items:5,source:a});var e=$("#example-advanced-daterangepicker"),i=$("#example-advanced-daterangepicker span");e.daterangepicker({ranges:{Today:["today","today"],Yesterday:["yesterday","yesterday"],"Last 7 Days":[Date.today().add({days:-6}),"today"],"Last 30 Days":[Date.today().add({days:-29}),"today"],"This Month":[Date.today().moveToFirstDayOfMonth(),Date.today().moveToLastDayOfMonth()],"Last Month":[Date.today().moveToFirstDayOfMonth().add({months:-1}),Date.today().moveToFirstDayOfMonth().add({days:-1})]}},function(a,e){i.html(a.toString("MMMM d, yy")+" - "+e.toString("MMMM d, yy"))}),i.html(Date.today().toString("MMMM d, yy")+" - "+Date.today().toString("MMMM d, yy"))},i=function(){var a=$("#primary-nav > ul > li > a");a.filter(function(){return $(this).next().is("ul")}).each(function(a,e){$(e).append("<span>"+$(e).next("ul").children().length+"</span>")}),a.click(function(){var a=$(this);return a.next("ul").length>0?(a.parent().hasClass("active")!==!0&&(a.hasClass("open")?a.removeClass("open").next().slideUp(250):($("#primary-nav li > a.open").removeClass("open").next().slideUp(250),a.addClass("open").next().slideDown(250))),!1):!0})},t=function(){var a=$("#to-top");$(window).scroll(function(){$(this).scrollTop()>150?a.fadeIn(150):a.fadeOut(150)}),a.click(function(){return $("html, body").animate({scrollTop:0},300),!1})},n=function(){var a=$("#topt-fixed-header-top"),e=$("#topt-fixed-header-bottom"),i=$("#topt-fixed-layout"),t=$("header");$(".btn-theme-options").click(function(){return $(this).toggleClass("open"),$("#theme-options-content").slideToggle(200),!1}),a.on("switch-change",function(a,i){i.value===!0?(e.bootstrapSwitch("setState",!1),t.addClass("navbar-fixed-top"),$("#page-container").addClass("header-fixed-top")):(t.removeClass("navbar-fixed-top"),$("#page-container").removeClass("header-fixed-top"))}),e.on("switch-change",function(e,i){i.value===!0?(a.bootstrapSwitch("setState",!1),t.addClass("navbar-fixed-bottom"),$("#page-container").addClass("header-fixed-bottom")):(t.removeClass("navbar-fixed-bottom"),$("#page-container").removeClass("header-fixed-bottom"))}),i.on("switch-change",function(a,e){e.value===!0?$("body").addClass("fixed"):$("body").removeClass("fixed")});var n,o=$(".theme-colors"),s=$("#theme-link");s.length&&(n=s.attr("href"),$("li",o).removeClass("active"),$('a[data-theme="'+n+'"]',o).parent("li").addClass("active")),$("a",o).click(function(){n=$(this).data("theme"),$("li",o).removeClass("active"),$(this).parent("li").addClass("active"),"default"===n?s.length&&(s.remove(),s=$("#theme-link")):s.length?s.attr("href",n):($('link[href="css/main.1-6-1.css"]').after('<link id="theme-link" rel="stylesheet" href="'+n+'">'),s=$("#theme-link"))})},o=function(){$.extend(!0,$.fn.dataTable.defaults,{sDom:"<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>",sPaginationType:"bootstrap",oLanguage:{sLengthMenu:"_MENU_",sSearch:'<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span>_INPUT_</div>',sInfo:"<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",oPaginate:{sPrevious:"",sNext:""}}}),$.extend($.fn.dataTableExt.oStdClasses,{sWrapper:"dataTables_wrapper form-inline"}),$.fn.dataTableExt.oApi.fnPagingInfo=function(a){return{iStart:a._iDisplayStart,iEnd:a.fnDisplayEnd(),iLength:a._iDisplayLength,iTotal:a.fnRecordsTotal(),iFilteredTotal:a.fnRecordsDisplay(),iPage:Math.ceil(a._iDisplayStart/a._iDisplayLength),iTotalPages:Math.ceil(a.fnRecordsDisplay()/a._iDisplayLength)}},$.extend($.fn.dataTableExt.oPagination,{bootstrap:{fnInit:function(a,e,i){var t=a.oLanguage.oPaginate,n=function(e){e.preventDefault(),a.oApi._fnPageChange(a,e.data.action)&&i(a)};$(e).append('<ul class="pagination pagination-sm remove-margin"><li class="prev disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i> '+t.sPrevious+'</a></li><li class="next disabled"><a href="javascript:void(0)">'+t.sNext+' <i class="fa fa-chevron-right"></i></a></li></ul>');var o=$("a",e);$(o[0]).bind("click.DT",{action:"previous"},n),$(o[1]).bind("click.DT",{action:"next"},n)},fnUpdate:function(a,e){var i,t,n,o,s,r=5,l=a.oInstance.fnPagingInfo(),d=a.aanFeatures.p,c=Math.floor(r/2);for(l.iTotalPages<r?(o=1,s=l.iTotalPages):l.iPage<=c?(o=1,s=r):l.iPage>=l.iTotalPages-c?(o=l.iTotalPages-r+1,s=l.iTotalPages):(o=l.iPage-c+1,s=o+r-1),i=0,iLen=d.length;iLen>i;i++){for($("li:gt(0)",d[i]).filter(":not(:last)").remove(),t=o;s>=t;t++)n=t===l.iPage+1?'class="active"':"",$("<li "+n+'><a href="javascript:void(0)">'+t+"</a></li>").insertBefore($("li:last",d[i])[0]).bind("click",function(i){i.preventDefault(),a._iDisplayStart=(parseInt($("a",this).text(),10)-1)*l.iLength,e(a)});0===l.iPage?$("li:first",d[i]).addClass("disabled"):$("li:first",d[i]).removeClass("disabled"),l.iPage===l.iTotalPages-1||0===l.iTotalPages?$("li:last",d[i]).addClass("disabled"):$("li:last",d[i]).removeClass("disabled")}}}})};return{init:function(){a(),e(),i(),t(),n(),o()}}}();$(function(){webApp.init()});