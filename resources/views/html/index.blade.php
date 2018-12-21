@extends('html.master')

@section('content')

<div id="map" style="height: 480px;"></div>

<table>
    <thead>
        <tr>
            <th>事業所名</th>
            <th>種別</th>
            <th>住所</th>
            <th>電話番号</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td>{{ $data->facility_name }}</td>
                {{-- <td>{{ $data->category->category_name }}</td> --}}
                <td>{{ $data->city_name }}{{ $data->address }}</td>
                <td>{{ $data->telphone }}</td>
                <td><a href="{{ action('FacilityController@show', $data->id) }}">詳細</a></td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>
    var map;
    var marker = [];
    var data = [
        @foreach ($datas as $data)
            { name: '{{ $data->facility_name }}', lat: {{ $data->latitude }}, lng: {{ $data->longitude }} },
        @endforeach
    ];


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

        for (var i = 0; i < data.length; i++) {
            markerLatLng = {lat: data[i]['lat'], lng: data[i]['lng']};
            marker[i] = new google.maps.Marker({
                position: markerLatLng,
                map: map
            });
        }
    }

</script>

{{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&lang=ja&callback=initMap"></script> --}}

@stop
