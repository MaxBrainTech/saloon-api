

var options = {
    valueNames: [
        'name',
        'born',
        'address',
        { data: ['gender']}
    ],
    page: 12,
    pagination: true
};
var userList = new List('users', options);
var userList = new List('users1', options);
var userList = new List('users2', options);

function resetList(){
    userList.search();
    userList.filter();
    userList.update();
    $(".filter-all").prop('checked', true);
    $('.filter').prop('checked', false);
    $('.search').val('');
    //console.log('Reset Successfully!');
};
  
function updateList(){
  var values_gender = $("input[name=gender]:checked").val();
    var values_address = $("input[name=address]:checked").val();
    console.log(values_gender, values_address);

    userList.filter(function (item) {
        var genderFilter = false;
        var addressFilter = false;
        
        if(values_gender == "all")
        { 
            genderFilter = true;
        } else {
            genderFilter = item.values().gender == values_gender;
            
        }
        if(values_address == null)
        { 
            addressFilter = true;
        } else {
            addressFilter = item.values().address.indexOf(values_address) >= 0;
        }
        return addressFilter && genderFilter
    });
    userList.update();
    //console.log('Filtered: ' + values_gender);
}
                               
$(function(){
  //updateList();
  $("input[name=gender]").change(updateList);
    $('input[name=address]').change(updateList);
    
    userList.on('updated', function (list) {
        if (list.matchingItems.length > 0) {
            $('.no-result').hide()
        } else {
            $('.no-result').show()
        }
    });
});








// Start upload preview image
	$(".gambar").attr("src", "images/dummy-image.jpg");
        var $uploadCrop,
        tempFilename,
        rawImg,
        imageId;
        function readFile(input) {
            if (input.files && input.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('.upload-demo').addClass('ready');
                    $('#cropImagePop').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
            else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: 200,
                height: 240,
            },
            enforceBoundary: false,
            enableExif: true
        });
        $('#cropImagePop').on('shown.bs.modal', function(){
            // alert('Shown pop');
            $uploadCrop.croppie('bind', {
                url: rawImg
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        $('.item-img').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();
            $('#cancelCropBtn').data('id', imageId); readFile(this); });
        $('#cropImageBtn').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'base64',
                format: 'jpeg',
                size: {width: 200, height: 240}
            }).then(function (resp) {
                $('#item-img-output').attr('src', resp);
                $('#cropImagePop').modal('hide');
            });
    });
// End upload preview image





/*$('#example1').calendar();*/
$('#example1').calendar({
  type: 'date'
});
$('#example2').calendar({
  type: 'date'
});
$('#example3').calendar({
  type: 'time'
});
$('#example4').calendar({
  type: 'time'
});

/*var today = new Date();
$('#example11').calendar({
  minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() - 5),
  maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 5)
});
$('#example12').calendar({
  monthFirst: false
});
$('#example13').calendar({
  monthFirst: false,
  formatter: {
    date: function (date, settings) {
      if (!date) return '';
      var day = date.getDate();
      var month = date.getMonth() + 1;
      var year = date.getFullYear();
      return day + '/' + month + '/' + year;
    }
  }
});
$('#example14').calendar({
  inline: true
});
$('#example15').calendar();*/





