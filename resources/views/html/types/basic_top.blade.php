<style>
    .column-common-top-header {border-left: solid 5px #ffaf58;}
    .column-common-buttom-header{border-left: solid 5px #eeefff;}
    .column-common-middle-header{border-left: solid 5px #80ff00;}
    p.schedule {width: 100px; margin: 0;}
</style>

<div class="container-fluid">
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">施設名</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->facility_name }}</div>
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">代表者名</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->representative }}</div>
    </div>
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">住所</div>
        <div class="col-lg p-2 border-bottom">〒{{ $facility->postal_code }} {{ $facility->prefecture_name }}{{ $facility->city_name }}{{ $facility->address }}</div>
    </div>
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">電話番号</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->telphone }}</div>
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">FAX番号</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->fax }}</div>
    </div>

    @if($facility->person)
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">窓口担当者</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->person }}</div>
    </div>
    @endif

    @if($facility->correspondence_dept)
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">窓口対応部署</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->correspondence_dept }}</div>
    </div>
    @endif

    @if($facility->correspondence_time)
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">窓口対応時間</div>
        <div class="col-lg p-2 border-bottom">{{ $facility->correspondence_time }}</div>
    </div>
    @endif

    @if($facility->homepage)
    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">ホームページ</div>
        <div class="col-lg p-2 border-bottom"><a href="{{ $facility->homepage }}" target="_blank">{{ $facility->homepage }}</a></div>
    </div>
    @endif

    <div class="row p-1">
        <div class="col-lg-2 p-2 border-bottom font-weight-bold column-common-top-header">営業時間</div>
        <div class="col-lg p-2 border-bottom">
            <div class="d-md-inline-block p-2 align-top align-middle">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_mon ? 'bg-primary' : 'bg-danger'}} bg-primary text-white font-weight-bold schedule">月曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_mon }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top align-middle">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_tue ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">火曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_tue }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_wed ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">水曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_wed }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_thu ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">木曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_thu }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_fri ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">金曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_fri }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_sat ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">土曜日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_sat }}</p>
            </div>
            <div class="d-md-inline-block p-2 align-top">
                <p class="d-inline-block d-md-block text-center align-middle p-2 {{$facility->available_time_sun ? 'bg-primary' : 'bg-danger'}} text-white font-weight-bold schedule">日・祝日</p>
                <p class="d-inline-block d-md-block text-center align-middle p-2 schedule">{{ $facility->available_time_sun }}</p>
            </div>
        </div>
    </div>

    @if($facility->open_24hours)
    <div class="row p-2">
        <div class="col-lg-2">24時間対応</div>
        <div class="col-lg">○</div>
    </div>
    @endif

</div>


{{-- <table class="table">
    <tbody>
        <tr>
            <th scope="row" class="col-md-2">施設名</th>
            <td data-th="施設名" class="col-md-4">
                {{ $facility->facility_name }}
            </td>
            <th scope="row" class="col-md-2">代表者名</th>
            <td data-th="代表者名" class="col-md-4">
                {{ $facility->representative }}
            </td>
        </tr>
        <tr>
            <th scope="row">住所</th>
            <td colspan="3" data-th="住所">
                〒{{ $facility->postal_code }} {{ $facility->prefecture_name }}{{ $facility->city_name }}{{ $facility->address }}
            </td>
        </tr>
        <tr>
            <th scope="row">電話番号</th>
            <td data-th="電話番号">
                {{ $facility->telphone }}
            </td>
            <th scope="row">FAX番号</th>
            <td data-th="FAX番号">
                {{ $facility->fax }}
            </td>
        </tr>

        @if($facility->person)
        <tr>
            <th scope="row">窓口担当者</th>
            <td colspan="3" data-th="窓口担当者">
                {{ $facility->person }}
            </td>
        </tr>
        @endif

        @if($facility->correspondence_dept)
        <tr>
            <th scope="row">窓口対応部署</th>
            <td colspan="3" data-th="窓口対応部署">
                {{ $facility->correspondence_dept }}
            </td>
        </tr>
        @endif

        @if($facility->correspondence_time)
        <tr>
            <th scope="row">窓口対応時間</th>
            <td colspan="3" data-th="窓口対応時間">
                {{ $facility->correspondence_time }}
            </td>
        </tr>
        @endif

        @if($facility->homepage)
        <tr>
            <th scope="row">ホームページ</th>
            <td colspan="3" data-th="ホームページ">
                <a href="{{ $facility->homepage }}" target="_blank">{{ $facility->homepage }}</a>
            </td>
        </tr>
        @endif
        <tr>
            <th scope="row">営業時間</th>
            <td colspan="3" data-th="営業時間" class="pd-none">
                <table class="table table-bordered inner-table">
                    <thead>
                        <tr>
                            <th scope="col" class="no-bd-top no-bd-left">月曜日</th>
                            <th scope="col" class="no-bd-top">火曜日</th>
                            <th scope="col" class="no-bd-top">水曜日</th>
                            <th scope="col" class="no-bd-top">木曜日</th>
                            <th scope="col" class="no-bd-top">金曜日</th>
                            <th scope="col" class="no-bd-top">土曜日</th>
                            <th scope="col" class="no-bd-top no-bd-right">日・祝日</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-th="月曜日" class="no-bd-bottom no-bd-left">
                                {{ $facility->available_time_mon }}
                            </td>
                            <td data-th="火曜日" class="no-bd-bottom">
                                {{ $facility->available_time_tue }}
                            </td>
                            <td data-th="水曜日" class="no-bd-bottom">
                                {{ $facility->available_time_wed }}
                            </td>
                            <td data-th="木曜日" class="no-bd-bottom">
                                {{ $facility->available_time_thu }}
                            </td>
                            <td data-th="金曜日" class="no-bd-bottom">
                                {{ $facility->available_time_fri }}
                            </td>
                            <td data-th="土曜日" class="no-bd-bottom">
                                {{ $facility->available_time_sat }}
                            </td>
                            <td data-th="日・祝日" class="no-bd-bottom no-bd-right">
                                {{ $facility->available_time_sun }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        @if($facility->open_24hours)
        <tr>
            <th scope="row">24時間対応</th>
            <td colspan="3" data-th="24時間対応">○</td>
        </tr>
        @endif --}}


        {{-- @php
            $acceptances = $facility->answers->where('question_cd', 'QA001');
            $acceptances_other = $facility->answers->where('question_cd', 'QA002');
            $areas = $facility->answers->where('question_cd', 'QA003');
            $areas_other = $facility->answers->where('question_cd', 'QA004');
        @endphp

        @if($acceptances->isNotEmpty() || $acceptances_other->isNotEmpty())
        <tr>
            <th scope="row">受け入れ患者</th>
            <td colspan="3" data-th="受け入れ可">
                <ul class="content-map-ul">
                    @if ($acceptances->isNotEmpty())
                        @foreach ($acceptances as $acceptance)
                        <li>
                            {{$acceptance->M_answer_cd->answer_content}}
                        </li>
                        @endforeach
                    @endif

                    @if ($acceptances_other->isNotEmpty())
                        @foreach ($acceptances_other as $acceptance_other)
                            <li>
                                {{$acceptance_other->answer_content}}
                            </li>
                        @endforeach
                    @endif
                </ul>
            </td>
        </tr>
        @endif

        @if($areas->isNotEmpty() || $areas_other->isNotEmpty())
        <tr>
            <th scope="row">訪問、対応地域</th>
            <td colspan="3" data-th="訪問、対応地域">
                <ul class="content-map-ul">
                    @if ($areas->isNotEmpty())
                        @foreach ($areas as $area)
                            <li>
                                {{$area->M_answer_cd->answer_content}}
                            </li>
                        @endforeach
                    @endif
                    @if ($areas_other->isNotEmpty())
                        @foreach ($areas_other as $area_other)
                            <li>
                                {{$area_other->answer_content}}
                            </li>
                        @endforeach
                    @endif
                </ul>
            </td>
        </tr>
        @endif --}}


    </tbody>
</table>

