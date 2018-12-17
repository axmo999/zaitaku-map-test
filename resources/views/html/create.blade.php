@extends('html.master')
@section('content')

<div class="container" id="form">
    <h1 id="post-title">新規作成</h1>
    @csrf @method('POST')

    <form action="{{ url('facility') }}" method="post">
        @csrf @method('POST')
        <div class="form-group">
            <label for="facility_name">施設名</label>
            <input id="facility_name" type="text" class="form-control" name="facility_name" required autofocus>
        </div>
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" type="text" class="form-control" name="postal_code" v-model="postal_code" required>
        </div>
        <div class="form-group">
            <label for="prefecture_name">都道府県名</label>
            <input id="prefecture_name" type="text" class="form-control" name="prefecture_name" v-model="prefecture_name" required>
        </div>
        <div class="form-group">
            <label for="city_name">市町村名</label>
            <input id="city_name" type="text" class="form-control" name="city_name" v-model="city_name" required>
        </div>
        <div class="form-group">
            <label for="address">住所</label>
            <input id="address" type="text" class="form-control" name="address" v-model="address" required>
        </div>

        <div class="map-check">
            <button type="button" v-on:click="mapCheck">マップ確認</button>
        </div>

        <div id="map" style="width: auto; height: 300px;"></div>

        <div class="form-group">
            <label for="latitude">緯度</label>
            <input id="latitude" type="text" class="form-control" name="latitude" v-model="latitude">
        </div>
        <div class="form-group">
            <label for="longitude">経度</label>
            <input id="longitude" type="text" class="form-control" name="longitude" v-model="longitude">
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

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&lang=ja"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13"></script>
<script src="https://yubinbango.github.io/yubinbango-core/yubinbango-core.js" charset="UTF-8"></script>

<script>
    new Vue({
        el: '#form',
        data: {
            postal_code: '',
            prefecture_name: '',
            city_name: '',
            address: '',
            latitude: '',
            longitude: ''
        },
        watch: {
            postal_code: function(postal_code){
                let _this = this;
                new YubinBango.Core(postal_code, function(addr){
                    _this.prefecture_name = addr.region
                    _this.city_name = addr.locality
                    _this.address = addr.street
                });
            }
        },
        computed: {
            fullAddress: function () { return this.postal_code + this.prefecture_name + this.city_name + this.address},
        },
        methods: {
            mapCheck: function (event) {
                let geocoder = new google.maps.Geocoder();
                let _this = this;
                geocoder.geocode( {
                    'address': this.postal_code + this.prefecture_name + this.city_name + this.address,
                    'region': 'ja'
                },
                function(results, status){
                    if(status === google.maps.GeocoderStatus.OK)
                    {
                        console.group('Success');
                        console.log(results);
                        console.log(status);
                        _this.latitude = results[0].geometry.location.lat();
                        _this.longitude = results[0].geometry.location.lng();
                        var map = new google.maps.Map(document.getElementById("map"),{
                            center: results[0].geometry.location,
                            zoom: 19
                        });
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                            draggable:true
                        });

                        google.maps.event.addListener( marker, 'dragend', function(ev){
                            _this.latitude = ev.latLng.lat();
                            _this.longitude = ev.latLng.lng();
                        });
                    }
                    else
                    {
                        console.group('Error');
                        console.log(results);
                        console.log(status);
                    }
                });
            }
        },
    });

</script>
@endsection
