@extends('html.master')

@section('content')

<style>
    .table-hidden { display:none }
</style>

<div class="row">
    <div class="col-md-8 p-3">
        <div id="map" style="height: 480px;"></div>
    </div>

    <div class="col-md-4 p-3">
        <div id="search-accordion">
            <h3>施設を絞り込む</h3>
            <div>
                <fieldset>
                    <legend>オプション</legend>
                    {{ Form::checkbox('home_care', 1, false ,["id" => 'home_care']) }}
                    {{ Form::label('home_care', '在宅可能') }}
                </fieldset>

                <fieldset>
                    <legend>種別選択</legend>
                    @foreach ($facility_types as $facility_type)
                        {{ Form::checkbox('facility_types[]', $facility_type->id, false ,["id" => $facility_type->facility_type_name]) }}
                        {{ Form::label($facility_type->facility_type_name) }}
                    @endforeach
                </fieldset>
            </div>
            <h3>市町村を絞り込む</h3>
            <div>
                @foreach ($city_names as $city_name)
                    @if ($city_name->city_name)
                        {{ Form::checkbox('city_names[]', $city_name->city_name, false ,["id" => $city_name->city_name]) }}
                        {{ Form::label($city_name->city_name) }}
                    @endif
                @endforeach
            </div>
            <h3>受け入れ可能患者で絞り込む</h3>
            <div>
                @foreach ($acceptable_patients as $acceptable_patient)
                    {{ Form::checkbox('acceptable_patients[]', $acceptable_patient->answer_cd, false ,["id" => $acceptable_patient->answer_content]) }}
                    {{ Form::label($acceptable_patient->answer_content) }}
                @endforeach
            </div>
            <h3>対応可能市町村で絞り込む</h3>
            <div>
                @foreach ($acceptable_cities as $acceptable_city)
                    {{ Form::checkbox('acceptable_cities[]', $acceptable_city->answer_cd, false ,["id" => $acceptable_city->answer_content]) }}
                    {{ Form::label($acceptable_city->answer_content) }}
                @endforeach
            </div>
        </div>
    </div>


</div>


{{-- <div id="search-tabs">
    <ul>
        <li><a href="#search-tabs-1">種別選択</a></li>
        <li><a href="#search-tabs-2">市町村選択</a></li>
    </ul>
    <div id="search-tabs-1">
        @foreach ($facility_types as $facility_type)
        {{ Form::checkbox('facility_types[]', $facility_type->id, false ,["id" => $facility_type->facility_type_name]) }}
        {{ Form::label($facility_type->facility_type_name) }}
        @endforeach
    </div>
    <div id="search-tabs-2">
        @foreach ($city_names as $city_name)
        @if ($city_name->city_name)
        {{ Form::checkbox('city_names[]', $city_name->city_name, false ,["id" => $city_name->city_name]) }}
        {{ Form::label($city_name->city_name) }}
        @endif
        @endforeach
    </div>
</div> --}}



<div>
    <h3><span id="total_count"></span>件、表示しています。</h3>
</div>

<table id="dataTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(function(){
        //$( "#search-tabs" ).tabs();
        $( "#search-accordion" ).accordion({active: false, collapsible: true, heightStyle: "fill"});
        //$( "input:checkbox" ).checkboxradio();
    });
</script>

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

    var _table = $('#dataTable').DataTable({
        //dom: "lfrtip",
        //destroy: true,
        "processing": true,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "pageLength": 10,
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
            { data: null, render: function(data, type, full, meta){ return '<a href="./facility/' + full.id + '">' + full.facility_name + '</a>' ; } },
            { data: "facility_type.facility_type_name" },
            { data: null, render: function(data, type, full, meta){ return full.city_name + full.address ; } },
            { data: "telphone" }
        ],
        language: { url: "{{ asset('/js/japanese.json') }}" },
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

            $('#total_count').text(_table.page.info().recordsDisplay);

        }
    });

    function search() {

        _parameters = {}

        // 種別取得
        var facility_type_id = $('input[name="facility_types[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

        var city_names = $('input[name="city_names[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

        var home_care = $('input[name="home_care"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

        var acceptable_cities = $('input[name="acceptable_cities[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

        var acceptable_patients = $('input[name="acceptable_patients[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

        if( Object.keys(facility_type_id).length ) {
            _parameters.facility_type_id = facility_type_id;
        };

        if( Object.keys(city_names).length ) {
            _parameters.city_names = city_names;
        };

        if( Object.keys(home_care).length ) {
            _parameters.home_care = home_care;
        };

        if( Object.keys(acceptable_cities).length ) {
            _parameters.acceptable_cities = acceptable_cities;
        };

        if( Object.keys(acceptable_patients).length ) {
            _parameters.acceptable_patients = acceptable_patients;
        };

        //console.log(_parameters);

        _table.ajax.reload();

    }

    $('input:checkbox').change(function() {
        search();
    });



    // $("button#search").click(function() {
    //     // 多重送信を防ぐため通信完了までボタンをdisableにする
    //     var button = $(this);
    //     button.attr("disabled", true);

    //     _parameters = {}

    //     // 種別取得
    //     var facility_type_id = $('input[name="facility_types[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

    //     var city_names = $('input[name="city_names[]"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

    //     var home_care = $('input[name="home_care"]:checkbox:checked').map(function (){ return $(this).val(); }).get();

    //     //console.log(city_names);

    //     if( Object.keys(facility_type_id).length ) {
    //         _parameters.facility_type_id = facility_type_id;
    //     };

    //     if( Object.keys(city_names).length ) {
    //         _parameters.city_names = city_names;
    //     };

    //     if( Object.keys(home_care).length ) {
    //         _parameters.home_care = home_care;
    //     };

    //     //console.log(_parameters);

    //     _table.ajax.reload();

    //     button.attr("disabled", false); // ボタンを再び enableにする
    // });

</script>



@endsection
