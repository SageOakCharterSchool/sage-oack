var lastMapping = null;
$(".protectMe").click(function() {
    $.blockUI({ message: "<h1>Please Wait!</h1>" });
 });
$(".protectMeShort").click(function() {
    $.blockUI({ message: "<h1>Please Wait!</h1>" });
    setTimeout($.unblockUI, 3000);
 });
 function open_bug_modal_feedback() {
    $('#bugModal').modal({
        keyboard: false
    })
    $('#bugModal').modal('show')
}
function closeModalAndCreateBug() {
    var myRadio = $("input[name=bug_type]");
    var myRadioValue = myRadio.filter(":checked").val();
    $.ajax({
        url: '/bug',
        type: 'POST',
        data: {
            feedback: $("#feedback").val(),
            url: $("#url").val(),
            bug_type: myRadioValue,
            reporter_email: $("#reporter_email").val(),
            _token: $("#_token").val(),
        },
        dataType: "text",
        success: function (response) {
            $("#feedback").val('');
        },
        error: function () {
            alert("error");
        }
    });
}
function closeBugNotes() {
    console.log('here2');
}
