define(['jquery'], function($) {
        // $PAGE->requires->js_call_amd('block_mcdpde/params', 'init');

        // Public functions.rm
        return {
            init: function(params) {
              //add input hidden fields to download form
              $.each(params.split("&"),function(index,value){
                var splitValue = value.split("=");
                // console.log(splitValue[1]);

                $('<input>').attr({
                  type: 'hidden',
                  name: splitValue[0],
                  value: splitValue[1]
                }).appendTo("form[class*=dataformatselector]");
              });
              // add array values to links
              var pagingLinks = $(".page-item");
              $(pagingLinks).find("a").each(function(index, value) {
                var linkHref=$(this).attr("href");
                $(this).attr("href",linkHref+params);
              });
           },

           tables: function() {
             var codeCell = $("thead th:nth-child(1)").html();
             var nameCell = $("thead th:nth-child(2)").html();
             $("thead th:nth-child(1)").html('<div class="codeName"><div class="codeCell">'+
                                                codeCell+
                                              '</div>'+
                                              '<div class="nameCell">'+
                                                 nameCell+
                                               '</div></div>');
              $("thead th:nth-child(2)").remove();
              // count columns
              var colCount = 0;
              $('tr:nth-child(1) td').each(function () {
                  if ($(this).attr('colspan')) {
                      colCount += +$(this).attr('colspan');
                  } else {
                      colCount++;
                  }
                });

                // fix all headers
              for (var i = 2; i< colCount; i++) {
                  $(".c"+i).each(function() {
                      $(this).css('max-width','50px');
                      $(this).css('min-width','50px');
                  });
              };

              var names = $("tbody td:nth-child(2)");
              var codes = $("tbody td:nth-child(1)");
              $(codes).each(function (index) {
                $(this).html('<div class="codeNameTD"><div class="codeCell">'+
                              $(this).html()+
                              '</div>'+
                              '<div class="nameCell">'+
                              names[index].innerHTML +
                              '</div></div>');

              });
              $("tbody td:nth-child(2)").remove();

              $('tbody').scroll(function(e) { //detect a scroll event on the tbody
                $('thead').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
                $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
                $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
              });

           },

           boardTable: function() {
              // count columns
              var colCount = 0;
              $('tr:nth-child(1) td').each(function () {
                  if ($(this).attr('colspan')) {
                      colCount += +$(this).attr('colspan');
                  } else {
                      colCount++;
                  }
                });

                // fix all headers
              for (var i = 1; i< colCount; i++) {
                  $(".c"+i).each(function() {
                      $(this).css('max-width','50px');
                      $(this).css('min-width','50px');
                  });
              };


              $('tbody').scroll(function(e) { //detect a scroll event on the tbody
                $('thead').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
                $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
                $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
              });

           },

           popboardTable: function() {
             var codeCell = $("thead th:nth-child(1)").html();
             var nameCell = $("thead th:nth-child(2)").html();
             $("thead th:nth-child(1)").html('<div class="codeName"><div class="codeCell">'+
                                                codeCell+
                                              '</div>'+
                                              '<div class="nameCell">'+
                                                 nameCell+
                                               '</div></div>');
              $("thead th:nth-child(2)").remove();
              // count columns
              var colCount = 0;
              $('tr:nth-child(1) td').each(function () {
                  if ($(this).attr('colspan')) {
                      colCount += +$(this).attr('colspan');
                  } else {
                      colCount++;
                  }
                });

                // fix all headers
              for (var i = 2; i< colCount; i++) {
                  $(".c"+i).each(function() {
                      $(this).css('max-width','88px');
                      $(this).css('min-width','88px');
                  });
              };

              var names = $("tbody td:nth-child(2)");
              var codes = $("tbody td:nth-child(1)");
              $(codes).each(function (index) {
                $(this).html('<div class="codeNameTD"><div class="codeCell">'+
                              $(this).html()+
                              '</div>'+
                              '<div class="nameCell">'+
                              names[index].innerHTML +
                              '</div></div>');

              });
              $("tbody td:nth-child(2)").remove();

              $('tbody').scroll(function(e) { //detect a scroll event on the tbody
                $('thead').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
                $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
                $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
              });
           }


        }
    });
