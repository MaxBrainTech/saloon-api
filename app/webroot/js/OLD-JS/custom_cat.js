// JavaScript Document

$(document).ready(function() {

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


// responsive navigation js for 768px
    var wid = $(window).width();

//  navigation start

    $(".nav li").click(function(e) {
        //e.preventDefault();
        if ($(this).hasClass('active'))
        {
            $(this).removeClass('active');
            $(this).children("ul").slideUp();
        }
        else
        {
            $(".nav li").removeClass('active');
            $(this).addClass('active');
            $(".nav li ul").slideUp();
            $(this).children("ul").slideDown();
        }
        $(".container").css("min-height", "650px")
    });

    $(".nav li ul li a").click(function(e) {
        $(".nav li ul li a").removeClass('this');
        $(this).toggleClass('this');
        e.stopPropagation();
    });
//  navigation end

// user area box
    $(".user-area .box").click(function() {
        $('.info').slideToggle('fast');

    });

    $(".info a").click(function(e) {
        e.stopPropagation();

    });

// datepicker
    $(function() {
        $("#datepicker, #datepicker2").datepicker({
             changeMonth: true,
             changeYear: true,
             yearRange: "-100:+0"

        });
    });


// ----------------tabs	--------------

    $('.tabcontent').hide();
    $('.tabcontent:first').show();
    $('.tabs a:first').addClass('active');

    $(".tabs a").click(function() {
        $(".tabs a").removeClass('active');
        $(this).addClass('active');
        var id = $(this).attr('href');
        $('.tabcontent').hide();
        $(id).show();
        return false;
    });

// ----------------add more category start-------------
    var $addcat = {
        add: function() {
            $(".addcatBtn").click(function() {
                //alert("gi");
                $(this).closest('div').find('input').show();
                $(this).closest('div').find('span').show();
                $("input.addInLast").hide();
                $(this).hide();

            });
        },
        remove: function() {
            $(".addMore .cancel").click(function() {
                $(this).closest('div').find('input').hide();
                $(this).closest('div').find('a').show();
                $("input.addInLast").show();
                $(this).hide();

            });
        }
    }
    $addcat.add();
    $addcat.remove();
// ----------------add more category end-------------

    $(".advance-options-link").click(function() {
        $(".advance-options").toggle();
        $(this).toggleClass("expand");
    });

    $('.allow-backorder').click(function() {
        if ($(this).is(':checked'))
            $(".backorder-delay").show();
        else
            $(".backorder-delay").hide();
    });

    $('.allow-bidding').click(function() {
        if ($(this).is(':checked'))
            $(".floor-price").show();
        else
            $(".floor-price").hide();
    });

    $('.import input[type=file]').change(function(e) {
        //$in=$(this);
        $(".importBtn").css("opacity", "1");
    });



// ----------------create a table layout on click print code-------------
    function createTable(x, y) {
        var table = $('<table width="100%" style="height:70px;"></table>').addClass('ptable');
        for (i = 0; i < x; i++) {
            var row = $('<tr></tr>');
            table.append(row);
            for (j = 0; j < y; j++) {
                var row1 = $('<td></td>');
                row.append(row1);

            }
        }

        $(".tableView").html(table);
    }

    $('.tchoice li select').change(function() {
        var cv = $(".colSelect").val();
        var rv = $(".rowSelect").val();
        //alert(cv);
        createTable(rv, cv);
        $("#productAction .rt, .cellView .inner, .lbl").show();

    });



    $(function() {
        var a = "A Vintage";
        $(".code-title").html(a);
    });

    $("input.title-check").change(function() {
        var ch1 = $(this);
        if (!ch1.is(":checked"))
        {
            $(".code-title").hide();
        }
        else
        {
            $(".code-title").show();
        }
    });

    $(function() {
        var b = "ETXE002";
        $(".code-name").html(b);
    });

    $("input.code-check").change(function() {
        var ch2 = $(this);
        if (!ch2.is(":checked"))
        {
            $(".code-name").hide();
        }
        else
        {
            $(".code-name").show();
        }
    });




    $(".ticket").click(function() {
        if (!$(this).is(":checked"))
        {
            $(".ticket-options").css("display", "none");

        }
        else
        {
            $(".ticket-options").css("display", "block");
        }
    });

    $(".priceOption").change(function() {
        var pval = $(this).val();
        if (pval == "Paid")
        {
            $(".ticketPrice").css("display", "block");
        }
        else
        {
            $(".ticketPrice").css("display", "none");
        }
    });

    $(".eventAddress").css("display", "none");
    $(".eventType select").change(function() {
        var eval = $(this).val();
        if (eval == "Online and offline")
        {
            $(".eventAddress").css("display", "block");
        }
        else
        {
            $(".eventAddress").css("display", "none");
        }
    });

    /*-----add category----*/
    $('.sub-cat-slt').hide();
    $(".catcheckRow input").click(function() {
        if ($('.catcheck2').is(":checked")) {
            $('.sub-cat-slt').show();
        } else {
            $('.sub-cat-slt').hide();
        }
    });

    /*-----edit cal list category----*/
    $(".edit-cat-list").click(function() {


        if ($(this).hasClass('edit-ok'))
        {
            Update($(this).attr('lang'), $(this).parent().find(".cat-list-prt-ttl").children().first().val(),
                    $(this),
                    $(this).parent().find(".cat-list-prt-ttl")
                    
                    );
        }
        else
        {
            var catval = $(this).parent().find(".cat-list-prt-ttl").text();
            //alert(catval);
            $(this).parent().find(".cat-list-prt-ttl").html("<input type='text' value='" + catval + "'>");
            $(this).addClass("edit-ok");
        }



//	 $(this).parent().find(".cat-list-prt-ttl").children().blur(function (e) { 
//                var newContent = $(this).val();
//                $(this).parent().text(newContent);
//                $(".edit-cat-list").removeClass("edit-ok");
//                
//           
//        });
    });

    var callback = function() {
        console.log("hi");
    }


    $(".edit-cat-child-list").live('click',function() {
        if ($(this).hasClass('edit-ok'))
        {
            Update(
                    $(this).attr('lang'),
                    $(this).parent().find(".cat-list-child-ttl").children().first().val(),
                    $(this),
                    $(this).parent().find(".cat-list-child-ttl")
                    );
        }
        else
        {
            var catval = $(this).parent().find(".cat-list-child-ttl").text();
            $(this).parent().find(".cat-list-child-ttl").html("<input type='text' value='" + catval + "'>");
            $(this).addClass("edit-ok");
        }

//	 $(this).parent().find(".cat-list-child-ttl").children().blur(function (e) {  
//                var newContent = $(this).val();
//                $(this).parent().text(newContent);
//                $(".edit-cat-child-list").removeClass("edit-ok");
//                
//           
//        });
    });
//    $(".delete-cat").click(function() {
//        $(this).parent().remove();
//
//
//    });

    function Update(value, newContent, obj1, obj2)
    {

        var dataString = 'value=' + value + '&newContent=' + escape(newContent);

        if (newContent != '')
        {	//alert(window.location.href.split('#')[0] + "/edit");
            $.ajax({
                type: "POST",
                url: window.location.href.split('#')[0] + "/edit",
                data: dataString,
                cache: false,
                success: function(response)
                {
                    obj1.removeClass("edit-ok");
                    obj2.text(obj2.children().first().val());
                },
                error: function(response)
                {
                    alert('Error!! Could not perform update operation');
                }
            });

        }
        else
        {
            alert('Field cannot be left blank');
        }

    }
	
	$(".add_subcat").click(function() {

        if ($(this).hasClass('Add-ok'))
        {
            Addsubcat($(this).attr('lang'), $(this).parent().find(".subcat_box").children().first().val(),
                    $(this),
                    $(this).parent().find(".subcat_box"));
        }
        else
        {
            //var catval = $(this).parent().find(".subcat_box").text();
            $(this).parent().find(".subcat_box").html("<input type='text' value=''>");
            $(this).addClass("Add-ok");
        }

    });
	
	function Addsubcat(value, newContent, obj1, obj2)
    {
		
        var dataString = 'value=' + value + '&newContent=' + escape(newContent);

         if (newContent != '')
        {	
            $.ajax({
                type: "POST",
                url: window.location.href.split('#')[0] + "/addsubcat",
                data: dataString,
                cache: false,
                success: function(response)
                {
					var subcat=response.split('-');
                    obj1.removeClass("Add-ok");
                    obj2.text('');
					$('ul#'+subcat[1]).append('<li id='+subcat[0]+'Cat><span class="cat-list-child-ttl">'+subcat[2]+'</span><a href="javascript:void(0);'+subcat[0]+'Cat" class="edit-cat-child-list" lang='+subcat[0]+'></a><a href="javascript:void(0);" class="delete-cat" lang='+subcat[0]+'></a></li>');
					alert('Subcategory added successfully.');
                },
                error: function(response)
                {
                    alert('Error!! Could not perform add operation');
                }
            });

        }
        else
        {
            alert('Field cannot be left blank');
        } 

    }
	

    /*-----delete cal list category----*/
    $(".delete-cat").live('click',function() {
        if (confirm("Corresponding ads will also be deleted along with category.\nDo you want to continue?")) {
            deleteCategory($(this).attr('lang'));
        }
    });
	
    function deleteCategory(value)
    {
        var dataString = 'value=' + value;
		
        if (value != '')
        {
            $.ajax({
                type: "POST",
                url: window.location.href.split('#')[0] + "/delete",
                data: dataString,
                cache: false,
                success: function(response)
                { 
                    //alert(response);
                    location.reload();
                },
                error: function(response)
                {
                    alert('Error!! Could not perform delete operation');
                }
            });

        }

    }


    //cat list collapse
    $(".edit-cat-child-list-ul").hide();
    $(".edit-cat-list-ul > li span").click(function() {
        $(this).parent().children(".edit-cat-child-list-ul").slideToggle(300, function() {

            if ($(this).parent().hasClass('expand')) {
                $(this).parent().removeClass('expand');
            } else {
                $(this).parent().addClass('expand');
            }
        });
    });
	
	
	
	
	
    
    jQuery(".add-interest").click(function(e) {
        unique_number = Math.floor(Math.random() * 10000000000000001);
        e.preventDefault();
        var val = jQuery(".add-interest-ttl").val();
        if (val != "") {
            jQuery(".ph-list").append("<li><img src='/frontend/images/user.png'> " + val + "<input type='hidden' value='" + val + "' name='interests[" + unique_number + "][name]'><input type='hidden' value='' name='interests[" + unique_number + "][image]'></li>");
        } else {
            alert("Please add interest title");
        }
        jQuery(".add-interest-ttl").attr("value", "");

    });

    jQuery(".add-education").click(function(e) {
        unique_number = Math.floor(Math.random() * 10000000000000001);
        e.preventDefault();
        var val2 = jQuery(".add-education-ttl").val();
        if (val2 != "") {
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
        if (val3 != "") {
            jQuery(".education-list2").append("<li>" + val3 + "<input type='hidden' value='" + val3 + "' name='experience[" + unique_number + "][name]'></li>");
        } else {
            alert("Please add education title");
        }
        jQuery(".add-education-ttl2").attr("value", "");


    });

});




