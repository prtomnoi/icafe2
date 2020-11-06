$("#logo-team").click(function() {
    $("input[id='pic1']").click();
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#logo-team').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

$("#pic1").change(function() {
    readURL(this);
});

$('#submit-mainteam').click(function() {
    $("#form-mainteam").submit();
});


$(document).ready(function() {
    $("#form-mainteam").on("submit", function(event) {

        var fileInput =
            document.getElementById('pic1');

        var filePath = fileInput.value;

        // Allowing file type 
        var allowedExtensions =
            /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if ($('#mode').val() == 'add') {

            if (!allowedExtensions.exec(filePath)) {


                swal("Error!", "Please upload logo team!", "error");
                fileInput.value = '';
                return false;

            }
        }
        if ($('#teamname').val() == '' || $("#teamname").val() == null) {
            swal("Error!", "please input team name and try again!", "error");
            $("#teamname").focus();
            return false;
        } else if ($("#name1").val() == '' || $("#name1").val() == null) {
            swal("Error!", "Please input Firstname and try again!", "error");
            $("#name1").focus();
            return false;
        } else if ($("#surname1").val() == '' || $("#surname1").val() == null) {
            swal("Error!", "Please input Lastname and try again!", "error");
            $("#surname1").focus();
            return false;
        } else if ($("#id_card1").val() == '' || $("#id_card1").val() == null) {
            swal("Error!", "Please input ID Card Code and try again!", "error");
            $("#id_card1").focus();
            return false;
        } else if ($("#email1").val() == '' || $("#email1").val() == null) {
            swal("Error!", "Please input Email and try again!", "error");
            $("#email1").focus();
            return false;
        } else if ($("#tel1").val() == '' || $("#tel1").val() == null) {
            swal("Error!", "Please input Telephone number and try again!", "error");
            $("#tel1").focus();
            return false;
        } else if ($("#ingamename1").val() == '' || $("#ingamename1").val() == null) {
            swal("Error!", "Please input Ingame Name and try again!", "error");
            $("#ingamename1").focus();
            return false;
        } else if ($("#garena_id1").val() == '' || $("#garena_id1").val() == null) {
            swal("Error!", "Please input Garena ID and try again!", "error");
            $("#garena_id1").focus();
            return false;
        } else if ($("#uid1").val() == '' || $("#uid1").val() == null) {
            swal("Error!", "Please input UID and try again!", "error");
            $("#uid1").focus();
            return false;
        } else if ($("#facebook1").val() == '' || $("#facebook1").val() == null) {
            swal("Error!", "Please input Facebook URL and try again!", "error");
            $("#facebook1").focus();
            return false;
        } else {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "save_team.php",
                type: "post",
                data: formData,
                processData: false, //Not to process data
                contentType: false, //Not to set contentType
                success: function(data) {
                    window.location.href = 'create-team.php'
                }
            });


        }

    });
});

$('.edit-form').click(function() {
    var id = $(this).attr('data-id');

    $.ajax({
        url: 'add_member.php',
        type: 'POST',
        data: $('#add-member-form' + id).serialize(),
        success: function(data) {
            switch (id) {
                case '1':
                    $('#headname-1').html($('#name2').val() + ' ' + $('#surname2').val());
                    break;
                case '2':
                    $('#headname-2').html($('#name3').val() + ' ' + $('#surname3').val());
                    break;
                case '3':
                    $('#headname-3').html($('#name4').val() + ' ' + $('#surname4').val());
                    break;
                case '4':
                    $('#headname-4').html($('#name5').val() + ' ' + $('#surname5').val());
                    break;
            }
            swal("Success!", "Update Already!", "success");
        }
    });
});