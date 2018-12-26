@extends('html.master')

@section('content')

<style>
    .table-hidden { display:none }
</style>

<button id="update" type="button">検索</button>

<div id="map" style="height: 480px;"></div>

{{ Form::label('city_name', '市町村選択') }}

@foreach ($facility_types as $facility_type)
{{ Form::label($facility_type->facility_type_name) }}
{{ Form::checkbox('facility_types[]', $facility_type->id) }}
@endforeach


{{ Form::button('検索', ['id' => 'search']) }}

<table id="dataTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="table-hidden">ID</th>
            <th>事業所名</th>
            <th>種別</th>
            <th>住所</th>
            <th>電話番号</th>
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
    var map;

    function initMap() {

        var location = new google.maps.LatLng(26.3578420, 127.8405900);

        var mapOptions = {
            zoom: 10,
            center: location,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
            scaleControl:true,
            scrollwheel: false
        };

        map = new google.maps.Map(document.getElementById("map"), mapOptions);
    }

    function pinSymbol(color) {
        return {
            path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
            fillColor: color,
            fillOpacity: 1,
            strokeColor: '#000',
            strokeWeight: 2,
            scale: 1,
        };
    }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&lang=ja&callback=initMap"></script>

<script>

    var _parameters = {};
    var MarkerArray = new google.maps.MVCArray();


    _parameters = { "city_name": "国頭郡恩納村" };

    var _table = $('#dataTable').DataTable({
        dom: "lfrtipB",
        //destroy: true,
        "processing": true,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        //"serverSide": true,
        ajax: {
            "url": "{{ env('API_URL') }}",
            "type": "POST",
            "data": function () {
                return JSON.stringify( _parameters );
            },  // JSONデータ本体
            "contentType": 'application/json', // リクエストの Content-Type
            "dataType": "json",           // レスポンスをJSONとしてパースする
            "dataSrc": ""
        },
        columns: [
            { data: "id", className: 'table-hidden' },
            { data: null, render: function(data, type, full, meta){ return '<a href="./facility/' + full.id + '">' + full.facility_name + '</a>' ; } },
            { data: "facility_type.facility_type_name" },
            { data: null, render: function(data, type, full, meta){ return full.city_name + full.address ; } },
            { data: "telphone" }
        ],
        language: { url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json" },
        drawCallback: function(){
            MarkerArray.forEach(function (marker, idx) { marker.setMap(null); });
            var bounds = new google.maps.LatLngBounds();
            var marker = [];
            var infowindow = [];
            var columnDatas = this.api().rows( {page:'current'} ).data();
            if( columnDatas.any() ){
                columnDatas.each( function( value, index ) {
                    marker[index] = new google.maps.Marker({
                        map: map,
                        animation: google.maps.Animation.DROP,
                        position: {lat: value.latitude, lng: value.longitude},
                        title: value.facility_name,
                        icon: pinSymbol("#00F")
                    });
                    infowindow[index] = new google.maps.InfoWindow({
                        content: value.facility_name,
                        size: new google.maps.Size( 50, 30 )
                    });
                    MarkerArray.push(marker[index]);
                    bounds.extend(marker[index].position);
                    google.maps.event.addListener( marker[index], 'click', function() {
                        infowindow[index].open( map, marker[index] );
                    });
                    google.maps.event.trigger(marker[index],'click');
                });

                var margin = 0.004;
                if (marker.length === 1) {
                    var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + margin, bounds.getNorthEast().lng() + margin);
                    var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - margin, bounds.getNorthEast().lng() - margin);
                    bounds.extend(extendPoint1);
                    bounds.extend(extendPoint2);
                }
                map.fitBounds(bounds);
            }
        }
    });

    $("button#search").click(function() {
        // 多重送信を防ぐため通信完了までボタンをdisableにする
        var button = $(this);
        button.attr("disabled", true);

        _parameters = {}

        // 種別取得
        var facility_type_id = $('input[name="facility_types[]"]:checked').map(function (){ return $(this).val(); }).get();

        //console.log(facility_type_id);

        if( Object.keys(facility_type_id).length ) {
            _parameters.facility_type_id = facility_type_id;
        };

        //console.log(_parameters);

        _table.ajax.reload();

        button.attr("disabled", false); // ボタンを再び enableにする
    });

</script>



@endsection
