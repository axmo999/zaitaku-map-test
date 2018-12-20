@extends('html.master')
@section('content')

<div class="container">

    <h1 id="post-title">{{ $facility->facility_name }}</h1>

    <div class="edit">
        <a href="{{ url('facility/'.$facility->id.'/edit') }}" class="btn btn-primary">
                {{ __('Edit') }}
            </a> @component('components.btn-del') @slot('table', 'facility') @slot('id', $facility->id) @endcomponent
    </div>

    <hr>

    {{-- 基本項目上部読み込み --}}
    {{-- @include('html.types.basic_top') --}}

    @include('html.types.hospitals')

    {{-- 基本項目下部読み込み --}}
    {{-- @include('html.types.basic_button') --}}


    <div id="map" style="height: 480px;"></div>

    <div style="margin:10px;">
        <p>※上記掲載内容に関しては、各機関からの申告に基づく内容となっております。詳細に関しては直接医療機関、事務所にお問い合わせください。</p>
        <p>※出来るだけ最新の情報提供に努めていますが、情報が古くなっている、または誤った情報が掲載されている場合は、各在宅医療・介護連携支援センターまでご連絡頂ければ幸いです。</p>
    </div>

    <div>
        <p style="font-size: 16px; text-align: right;">
            ［記事公開日］{{ $facility->created_at }} ［最終更新日］{{ $facility->updated_at }}
        </p>
    </div>

</div>



<script>
    function initMap() {

        var location = new google.maps.LatLng({{ $facility->latitude }}, {{ $facility->longitude }});

        var mapOptions = {
            zoom: 15,
            center: location,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
            scaleControl:true,
            scrollwheel: false
        };

        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: '{{$facility->facility_name}}',
            infoWindow: {
                content: '{{$facility->facility_name}}'
            }
        });
    }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&lang=ja&callback=initMap"></script>



@stop
