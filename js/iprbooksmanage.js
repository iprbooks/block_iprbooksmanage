$(document).ready(function () {
    // init
    send_request_m();

    $('.iprbooksmanage-form-control').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("iprbooksmanage-filter-apply").click();
        }
    });
});

// filter
$("#iprbooksmanage-filter-apply").click(function () {
    send_request_m();
});

// clear filter
$("#iprbooksmanage-filter-clear").click(function () {
    $(".iprmanage-filter").val("");
    send_request_m();
});

// register
$("#ipr-user-register").click(function () {
    var email = $("#user-email").val(),
        fio = $("#user-fio").val(),
        user_type = $("#user-type").val(),
        pass = $("#user-pass").val();
    register_user(email, fio, user_type, pass);
});


function send_request_m(page = 0) {
    var filter = $(".iprmanage-filter")
        .map(function () {
            return this.id + "=" + $(this).val();
        })
        .get()
        .join('&');

    $.ajax({
        url: M.cfg.wwwroot + "/blocks/iprbooksmanage/ajax.php?action=getlist&page=" + page + "&" + encodeURI(filter)
    }).done(function (data) {
        // set data
        $("#ipr-user-list").html(data.html);
        $("#ipr-user-list").scrollTop(0);

        // pagination
        $(".iprmanage-page").click(function () {
            send_request_m($(this).data('page'));
        });

        //set user block listener
        $(".ipr-user-block").click(function () {
            $(this).hide();
            block_user($(this).data("id"));
        });

        //set user unblock listener
        $(".ipr-user-unblock").click(function () {
            $(this).hide();
            unblock_user($(this).data("id"));
        });
    });
}

function block_user(id) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/iprbooksmanage/ajax.php?action=block_user&user_id=" + id
    }).done(function (data) {
        send_request_m();
    });
}

function unblock_user(id) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/iprbooksmanage/ajax.php?action=unblock_user&user_id=" + id
    }).done(function (data) {
        send_request_m();
    });
}

function register_user(email, fio, type, pass) {
    $.ajax({
        url: M.cfg.wwwroot + "/blocks/iprbooksmanage/ajax.php?action=register_user"
            + "&email=" + email
            + "&fio=" + fio
            + "&user_type=" + type
            + "&pass=" + pass
    }).done(function (data) {
        alert(data.text);
        clear_registerform();
        send_request_m();
    });
}

function clear_registerform() {
    $("#user-email").val("");
    $("#user-fio").val("");
    $("#user-type").val(1);
    $("#user-pass").val("");
}
