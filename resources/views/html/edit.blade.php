@extends('html.master')

@section('content')

<div class="container">
    <h1 id="post-title">編集</h1>

    <form action="{{ url('facility/'.$facility->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="facility_name">施設名</label>
            <input id="facility_name" type="text" class="form-control" name="facility_name" value="{{ $facility->facility_name }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" type="text" class="form-control" name="postal_code" value="{{ $facility->postal_code }}" required>
        </div>
        <div class="form-group">
            <label for="prefecture_name">都道府県名</label>
            <input id="prefecture_name" type="text" class="form-control" name="prefecture_name" value="{{ $facility->prefecture_name }}" required>
        </div>
        <div class="form-group">
            <label for="city_name">市町村名</label>
            <input id="city_name" type="text" class="form-control" name="city_name" value="{{ $facility->city_name }}" required>
        </div>
        <div class="form-group">
            <label for="address">住所</label>
            <input id="address" type="text" class="form-control" name="address" value="{{ $facility->address }}" required>
        </div>
        <div class="form-group">
            <label for="telphone">電話番号</label>
            <input id="telphone" type="text" class="form-control" name="telphone" value="{{ $facility->telphone }}" required>
        </div>
        <div class="form-group">
            <label for="fax">FAX番号</label>
            <input id="fax" type="text" class="form-control" name="fax" value="{{ $facility->fax }}">
        </div>
        <div class="form-group">
            <label for="representative">代表者名</label>
            <input id="representative" type="text" class="form-control" name="representative" value="{{ $facility->representative }}">
        </div>
        <div class="form-group">
            <label for="homepage">ホームページ</label>
            <input id="homepage" type="text" class="form-control" name="homepage" value="{{ $facility->homepage }}">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
</div>

@stop
