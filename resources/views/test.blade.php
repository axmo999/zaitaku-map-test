<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>gmapsサンプル</title>
    <style>
        @charset "utf-8";
        #map {
            height: 400px;
            background: #58B;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEW6xdNF_MXF3nEkXUhHXyqyk5k4ksoCQ"></script>
    <script src="{{ asset('/js/gmaps.js') }}"></script>
    <script>
        window.onload = function(){
            map = new GMaps({
                div: "#map",
                lat: 34.408882,
 	            lng: 133.204869,
                zoom: 5,
            });
            @foreach($datas as $data)
            map.addMarker({
                lat: {{$data->latitude}},
                lng: {{$data->longitude}},
                title: '{{$data->facility_name}}',
            });
            @endforeach
        };
    </script>
</head>
<body>
    <div id="map"></div>
</body>
</html>
