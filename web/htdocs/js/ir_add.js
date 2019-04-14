
$(function () {
    //送信ボタン押下時
    $('#add_button').click(function () {
        // irFile = $("#ir_filename option:selected").text();
        // irCommand = $("#ir_command option:selected").text();
        irFile = "hoge";
        irCommand = "cmd:power";

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
        executeAddIrCommand(irFile, irCommand)

    });
});

//  シェルコマンドを実行する
function executeAddIrCommand(irFile, irCommand) {
    $.ajax({
        url: './addremotecode.php',
        type: 'GET',
        data: {
            filename: irFile,
            sendcommand: irCommand
        }
    })
        // Ajaxリクエストが成功した場合
        .done((data) => {
            $('#addlog').html(data.commandstatus);
        })
        // Ajaxリクエストが失敗した場合
        .fail((data) => {
            $('#addlog').html(data.commandstatus);
            console.log(data);
        })
        .always((data) => {
        });
}
