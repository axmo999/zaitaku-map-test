<div class="container">
    <div class="row">
        <div class="col-4 col-md-2">施設名</div>
        <div class="col-8 col-md-4">{{ $facility->facility_name }}</div>
        <div class="col-4 col-md-2">代表者名</div>
        <div class="col-8 col-md-4">{{ $facility->representative }}</div>
    </div>
    <div class="row">
            <div class="col-2">住所</div>
            <div class="col-10">〒{{ $facility->postal_code }} {{ $facility->prefecture_name }}{{ $facility->city_name }}{{ $facility->address }}</div>
    </div>
</div>


    <table class="table">
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
            @endif


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

