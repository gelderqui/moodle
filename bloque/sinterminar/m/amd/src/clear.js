define(['jquery'], function($) {
        // // Private functions.
        // var privateFunc = function(a) {
        //     // JQuery is available via $ if I want it
        //     return a + 1;
        // };
        //
        // Add call from mod/assing/view.php
        // $PAGE->requires->js_call_amd('block_mcdpde/clear', 'init');

        // Public functions.rm
        return {
            init: function(b) {
              //set clear form
              $("form[data-region*='grading-actions-form']").append('<button type="submit" class="btn" name="clearbutton">Limpiar</button>');
              $("button[name*='clearbutton']").click(function() {
                $(".radio input[id*='advancedgrading-criteria']").removeAttr('checked');
                $("tr[role*='radiogroup'] td[id*='advancedgrading-criteria']").attr({
                    class: 'level first even',
                    'aria-checked': 'false'
                })
              })
            }
        }
    });
