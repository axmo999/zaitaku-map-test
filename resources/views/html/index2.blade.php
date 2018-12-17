@extends('html.master')

@section('content')

<div class="container">

    <div id="map" style="height: 480px;"></div>

    <div id="app">
        <v-client-table :columns="columns" :data="data" :options="options">
            <a slot="詳細" slot-scope="props" :href="`${props.row.詳細}`">
                詳細
            </a>
        </v-client-table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-tables-2@1.4.70/compiled/index.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-tables-2@1.4.70/dist/vue-tables-2.min.js"></script>


<script>
    Vue.use(VueTables.ClientTable);
    new Vue({
        el: "#app",
        data: {
            columns: [
            '事業所名',
            '種別',
            '住所',
            '電話番号',
            '詳細',
            ],
            data: getData(),
            options: {
                filterByColumn: true,
/*                     headings: {
                    hospitalName: '医院名',
                    HostName: 'PC名',
                    ServerDiv: '主従区分'
                }, */
                sortable: [
                    '事業所名', '種別' ,'住所'
                ],
                texts: {
                    filterPlaceholder: '検索する'
                }
            }
        }
    });

    function getData() {
		return [
            @foreach ($datas as $data)
            {
                '事業所名': '{{ $data->facility_name }}',
                '種別': '',
                '住所': '{{ $data->city_name }}{{ $data->address }}',
                '電話番号': '{{ $data->telphone }}',
                '詳細': '{{ action('FacilityController@show', $data->id) }}'
            },
            @endforeach
        ];
    }
</script>

@stop
