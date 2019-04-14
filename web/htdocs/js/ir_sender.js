
$(function () {
    //エンターキー押下時に送信ボタン押下実行
    $("#ir_filename").keypress(function (e) {
        if (e.which == 13) {
            $("#send_button").click();
        }
    });

    //エンターキー押下時に送信ボタン押下実行
    $("#ir_command").keypress(function (e) {
        if (e.which == 13) {
            $("#send_button").click();
        }
    });

    //送信ボタン押下時
    $('#send_button').click(function () {
        irFile = $("#ir_filename option:selected").text();
        irCommand = $("#ir_command option:selected").text();

        space = " "

        //パラメータが空の場合終了
        if (irFile == "" || irFile === null) {
            console.log(irFile + ' is not a empty string, or it is a null');
            return;
        }

        //パラメータが空の場合終了
        if (irCommand == "" || irCommand === null) {
            console.log(irCommand + ' is not a empty string, or it is a null');
            return;
        }

        //コマンド実行
        executeSendIrCommand(irFile, irCommand)

    });
});

//  赤外線送信コマンドを実行する
function executeSendIrCommand(irFile, irCommand) {

    $.ajax({
        url: './api/remotecontrol.php',
        type: 'GET',
        data: {
            filename: irFile,
            command: irCommand
        }
    })
        // Ajaxリクエストが成功した場合
        .done((data) => {

            $('#logtable').append(
                '<tr><td>' + getNow() + '</td>' +
                '<td>' + data.filename + '</td>' +
                '<td>' + data.sendcommand + '</td>' +
                '<td>' + data.commandstatus + '</td></tr>');
        })
        // Ajaxリクエストが失敗した場合
        .fail((data) => {
            $('#result').html(data);
            console.log(data);
        })
}

//現在時刻を取得する（yyyy/mm/dd hh:mm:ss）
function getNow() {
    var now = new Date();

    var year = now.getFullYear();
    var mon = ('0' + (now.getMonth() + 1)).slice(-2)
    var day = ('0' + now.getDate()).slice(-2);
    var hour = ('0' + now.getHours()).slice(-2);
    var min = ('0' + now.getMinutes()).slice(-2);
    var sec = ('0' + now.getSeconds()).slice(-2);

    //出力用
    return year + "/" + mon + "/" + day + " " + hour + ":" + min + ":" + sec;
}