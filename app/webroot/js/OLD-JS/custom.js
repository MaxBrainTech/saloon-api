jQuery(document).ready(function() {

    jQuery(function() {
        jQuery("#datepicker").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0"
        });
    });

// ----------------tabs	--------------
    jQuery('.tabcontent').hide();
//jQuery('.tabcontent:first').show();
//jQuery('.tab-strip li a:first').addClass('active');

    jQuery(".tab-strip li a").click(function() {
        jQuery(".tab-strip li a").removeClass('active');
        jQuery(this).addClass('active');
        var id = jQuery(this).attr('href');
        jQuery('.tabcontent').hide();
        jQuery(id).show();
        return false;
    });

// ----------------image gallary	--------------
//    var _gaq = _gaq || [];
//    _gaq.push(['_setAccount', 'UA-2196019-1']);
//    _gaq.push(['_trackPageview']);
//
//    (function() {
//        var ga = document.createElement('script');
//        ga.type = 'text/javascript';
//        ga.async = true;
//        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
//        var s = document.getElementsByTagName('script')[0];
//        s.parentNode.insertBefore(ga, s);
//    })();

// ----------------navigation	--------------
    jQuery("nav ul li a").click(function() {
        jQuery("nav ul li a").parent().removeClass('current');
        jQuery(this).parent().addClass('current');
        return false;
    });

// ----------------prifile page add list --------------
    jQuery(".add-interest").click(function(e) {
        unique_number = Math.floor(Math.random() * 10000000000000001);
        e.preventDefault();
        var val = jQuery(".add-interest-ttl").val();
        if (val.trim() != "") {
            jQuery(".ph-list").append("<li><img src='../frontend/images/user.png'> " + val + "<input type='hidden' value='" + val + "' name='interests[" + unique_number + "][name]'><input type='hidden' value='' name='interests[" + unique_number + "][image]'></li>");
        } else {
            alert("Please add interest title");
        }
        jQuery(".add-interest-ttl").attr("value", "");

    });

    jQuery(".add-education").click(function(e) {
        unique_number = Math.floor(Math.random() * 10000000000000001);
        e.preventDefault();
        var val2 = jQuery(".add-education-ttl").val();
        if (val2.trim() != "") {
            jQuery(".education-list").append("<li>" + val2 + "<input type='hidden' value='" + val2 + "' name='education[" + unique_number + "][name]'></li>");
        } else {
            alert("Please add education title");
        }

        jQuery(".add-education-ttl").attr("value", "");

    });

    jQuery(".add-education2").click(function(e) {
        unique_number = Math.floor(Math.random() * 10000000000000001);
        e.preventDefault();
        var val3 = jQuery(".add-education-ttl2").val();
        if (val3.trim() != "") {
            jQuery(".education-list2").append("<li>" + val3 + "<input type='hidden' value='" + val3 + "' name='experience[" + unique_number + "][name]'></li>");
        } else {
            alert("Please add work title");
        }
        jQuery(".add-education-ttl2").attr("value", "");


    });




    /*form*/
    //jQuery("select, .check, .check :checkbox, input:radio, input:file").uniform();
    jQuery(".check, .check :checkbox, input:radio, input:file").uniform();

});


createPopUp = function(title, text) {
    return jQuery("<div class='dialog-popup' title='" + title + "'><p>" + text + "</p>\n\
</div>")
            .dialog({
        resizable: false,
        height: 300,
        width: 350,
        modal: true,
        top : 120,
    });
}