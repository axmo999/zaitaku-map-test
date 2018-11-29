@extends('html.master')

@section('content')

<div class="container">
        <h1 id="post-title">{{ $facility->facility_name }}</h1>

        <div class="edit">
            <a href="{{ url('facility/'.$facility->id.'/edit') }}" class="btn btn-primary">
                {{ __('Edit') }}
            </a>
            @component('components.btn-del')
                @slot('table', 'facility')
                @slot('id', $facility->id)
            @endcomponent
        </div>


        <dl class="row">
            <dt class="col-md-2">{{ __('Created') }}:</dt>
            <dd class="col-md-10">
                <time itemprop="dateCreated" datetime="{{ $facility->created_at }}">
                    {{ $facility->created_at }}
                </time>
            </dd>
            <dt class="col-md-2">{{ __('Updated') }}:</dt>
            <dd class="col-md-10">
                <time itemprop="dateModified" datetime="{{ $facility->updated_at }}">
                    {{ $facility->updated_at }}
                </time>
            </dd>
        </dl>
        <hr>
        <div id="post-body">
            <p>{{ $facility->facility_name }}</p>
            <p>{{ $facility->postal_code }}</p>
            <p>{{ $facility->prefecture_name }}</p>
            <p>{{ $facility->city_name }}</p>
            <p>{{ $facility->address }}</p>
            <p>{{ $facility->latitude }}</p>
            <p>{{ $facility->longitude }}</p>
            <p>{{ $facility->telphone }}</p>
            <p>{{ $facility->fax }}</p>
            <p>{{ $facility->representative }}</p>
            <p>{{ $facility->homepage }}</p>
        </div>
</div>

@stop
