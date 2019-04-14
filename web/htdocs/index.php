<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>IR Console</title>

        <!-- Script -->
        <script src="./js/jquery-3.4.0.min.js"></script>
        <script src="./js/ir_sender.js"></script>
        <script src="./js/ir_add.js"></script>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body>
        <div id="content" style="padding:10px;" class="table-responsive">
            <h1>IR Console</h1>

            <h3>Send</h3>
            <div class="row">
                <div class="col-sm-4">
                    <div class="">
                        <label class="control-label col-xs-2"><b>IR FileName:</b></label>
                        <div class="col-xs-5">
                            <select id="ir_filename" class="form-control input-sm" maxlength="20">
                                <option value="">-</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="">
                        <label class="control-label col-xs-2"><b>IR Command:</b></label>
                        <div class="col-xs-5">
                            <select id="ir_command" class="form-control input-sm" maxlength="20"></select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="">
                        <label class="control-label col-xs-2"><b>Control:</b></label>
                        <div class="col-xs-5">
                            <button id="send_button" class="btn btn-success btn-sm" value="">execute</button>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <h3>Log</h3>
            <table class="table table-striped table-sm table-hover table-responsive table-bordered" id='logtable'>
                <thead class="thead-light table-hover">
                    <tr>
                        <th align="left"><b>Date</b></th>
                        <th align="left"><b>FileName</b></th>
                        <th align="left"><b>Command</b></th>
                        <th align="left"><b>Result</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="display: none;">
                        <td><div id="sendedfilename""></div></td>
                        <td><div id="sendedcommand""></div></td>
                        <td><div id="result""></div></td>
                    </tr>
                </tbody>
            </table>
            <br>
        </div>
    </body>
    <script>

        //赤外線ファイル名をコンボボックスへ設定する
        function putRemoteFileNames() {
            $.ajax({
                url: './api/loadremotefiles.php',
                type: 'GET',
            })
            // Ajaxリクエストが成功した場合
            .done((data) => {
                
                //シリアライズされたファイル名をリスト化する。
                var fileNameList = data.split(',').sort();

                //赤外線ファイル名のコンボボックスに項目を設定する。
                fileNameList.forEach(function(fileName){
                    $('#ir_filename').append('<option value="">'+ fileName +'</option>');
                });
            })
            // Ajaxリクエストが失敗した場合
            .fail((data) => {
                console.log(data);
                $('#ir_filename').append('<option value="">fail</option>');
            })
        }


        //赤外線コマンド名をコンボボックスへ設定する
        function putRemoteFileCommandNames(irFile) {
            if(irFile == '-'){
                $('#ir_command').children().remove();
                return;
            }

            $.ajax({
                url: './api/loadcommandnames.php',
                type: 'GET',
                data: {
                    filename: irFile
                }
            })
            // Ajaxリクエストが成功である場合
            .done((data) => {
                console.log(data);
                //シリアライズされたファイル名をリスト化する。
                var fileNameList = data.split(',').sort();

                //赤外線コマンド名のコンボボックスに設定された項目を全消去する。
                $('#ir_command').children().remove();

                //赤外線コマンド名のコンボボックスに項目を設定する。
                fileNameList.forEach(function(fileName){
                    $('#ir_command').append('<option value="">'+ fileName +'</option>');
                });
            })
            // Ajaxリクエストが失敗である場合
            .fail((data) => {
                console.log(data);
                $('#ir_command').append('<option value="">fail</option>');
            })
        }

        //ページ展開時のコールバック
        $(document).ready( function(){
            putRemoteFileNames();
            $("#ir_filename").change(function(){
                irFile = $("#ir_filename option:selected").text();
                putRemoteFileCommandNames(irFile);
            });
        });
    </script>
</html>