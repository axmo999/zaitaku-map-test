@extends('html.master')

@section('content')

<button id="update" type="button">更新</button>

<table id="dataTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>事業所名</th>
            <th>種別</th>
            <th>住所</th>
            <th>電話番号</th>
            <th>詳細</th>
        </tr>
    </thead>
</table>


@stop

@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/af-2.3.2/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/af-2.3.2/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>


<script>

function loadTable(attributes) {
    // 多重送信を防ぐため通信完了までボタンをdisableにする
    var button = $(this);
    button.attr("disabled", true);

    // 通信実行
    $.ajax({
        type:"post",                // method = "POST"
        url:"http://localhost:8000/api",        // POST送信先のURL
        data:JSON.stringify(attributes),  // JSONデータ本体
        contentType: 'application/json', // リクエストの Content-Type
        dataType: "json",           // レスポンスをJSONとしてパースする
        success: function(json_data) {   // 200 OK時
            // JSON Arrayの先頭が成功フラグ、失敗の場合2番目がエラーメッセージ
            if (!json_data[0]) {    // サーバが失敗を返した場合
                alert("Transaction error. " + json_data[1]);
                return;
            }
            // 成功時処理
            $('#dataTable').DataTable({
                dom: "Bfrtip",
                destroy: true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    "url": "http://localhost:8000/api",
                    "type": "POST",
                    data:JSON.stringify(attributes),  // JSONデータ本体
                    contentType: 'application/json', // リクエストの Content-Type
                    dataType: "json",           // レスポンスをJSONとしてパースする
                    dataSrc:""
                },
                columns: [
                    { data: "facility_name" },
                    { data: "postal_code" },
                    { data: "city_name"},
                    { data: "telphone" }
                ]
            });
        },
        error: function() {         // HTTPエラー時
            alert("Server Error. Pleasy try again later.");
        },
        complete: function() {      // 成功・失敗に関わらず通信が終了した際の処理
            button.attr("disabled", false);  // ボタンを再び enableにする
        }
    });
}

$("button#update").click(function() {

});

window.onload = function() {

    //loadTable();

    var datas = {
        "city_name": "国頭郡恩納村"
    };

    $('#dataTable').DataTable({
        dom: "lfrtipB",
        destroy: true,
        "processing": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        //"serverSide": true,
        ajax: {
            "url": "http://localhost:8000/api",
            "type": "POST",
            "data": function () {
                return JSON.stringify( datas );
            },  // JSONデータ本体
            "contentType": 'application/json', // リクエストの Content-Type
            "dataType": "json",           // レスポンスをJSONとしてパースする
            "dataSrc": ""
        },
        columns: [
            { data: "facility_name" },
            { data: "facility_type_id" },
            { data: "postal_code" },
            { data: "city_name"},
            { data: "telphone" }
        ]
    });

}
</script>
@endsection
