@extends('html.master')

@section('content')

<div class="container">
    <h1 id="post-title">新規作成</h1>

    <form action="{{ url('facility') }}" method="post">
        @csrf
        @method('POST')
        <div class="form-group">
            <label for="facility_name">施設名</label>
            <input id="facility_name" type="text" class="form-control" name="facility_name" required autofocus>
        </div>
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" type="text" class="form-control" name="postal_code" onKeyUp="AjaxZip3.zip2addr(this,'','prefecture_name','city_name','address');" required>
        </div>
        <div class="form-group">
            <label for="prefecture_name">都道府県名</label>
            <input id="prefecture_name" type="text" class="form-control" name="prefecture_name" required>
        </div>
        <div class="form-group">
            <label for="city_name">市町村名</label>
            <input id="city_name" type="text" class="form-control" name="city_name" required>
        </div>
        <div class="form-group" id="address_form">
            <label for="address">住所</label>
            <input id="address" type="text" class="form-control" name="address" v-model="addressinput" required>
        </div>

        <div id="map" style="width: auto; height: 300px;"></div>

        <div class="form-group">
            <label for="latitude">緯度</label>
            <input id="latitude" type="text" class="form-control" name="latitude">
        </div>
        <div class="form-group">
            <label for="longitude">経度</label>
            <input id="longitude" type="text" class="form-control" name="longitude">
        </div>
        <div class="form-group">
            <label for="telphone">電話番号</label>
            <input id="telphone" type="text" class="form-control" name="telphone" required>
        </div>
        <div class="form-group">
            <label for="fax">FAX番号</label>
            <input id="fax" type="text" class="form-control" name="fax">
        </div>
        <div class="form-group">
            <label for="representative">代表者名</label>
            <input id="representative" type="text" class="form-control" name="representative">
        </div>
        <div class="form-group">
            <label for="homepage">ホームページ</label>
            <input id="homepage" type="text" class="form-control" name="homepage">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
</div>



<script type="text/javascript">
// ページ読み込み完了時に実行する関数
function initMap() {

    var myLatlng = new google.maps.LatLng(26.3553024,127.803391);
    var mapOptions = {
    zoom: 15,
    center: myLatlng
    }
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

    // Place a draggable marker on the map
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable:true,
        title:"Drag me!"
    });

    // マーカーのドロップ（ドラッグ終了）時のイベント
    google.maps.event.addListener( marker, 'dragend', function(ev){
        // イベントの引数evの、プロパティ.latLngが緯度経度。
        document.getElementById('latitude').value = ev.latLng.lat();
        document.getElementById('longitude').value = ev.latLng.lng();
    });

}

// ONLOADイベントにセット
//window.onload = init();
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEW6xdNF_MXF3nEkXUhHXyqyk5k4ksoCQ&lang=ja&callback=initMap"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13"></script>

<script>
function watchAddress() {
    var address = new Vue({
        el: '#address_form',
        data: {
            addressinput: ''
        },
        watch: {
            addressinput: function(val, oldval) {
                console.log(val, oldval);
            }
        }
    });
}
</script>

@stop
