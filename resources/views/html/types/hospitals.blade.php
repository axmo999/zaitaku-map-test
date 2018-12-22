<div class="container-fluid">

    @foreach ($m_questions as $m_question)
        @php
            $attributes = $facility->answers->where('question_cd', $m_question->question_cd);
        @endphp

        @if ($attributes->isNotEmpty())
        <div class="row p-1">
            <div class="col-lg-3 p-2 border-bottom font-weight-bold column-common-middle-header">{{$m_question->question_content}}</div>
            <div class="col-lg p-2 border-bottom">
                @if ($m_question->question_type == "check")

                        @foreach ($attributes as $attribute)
                        <p class="p-2 m-1 d-inline-block text-white align-middle bg-success rounded-pill">
                            {{$attribute->M_answer_cd->answer_content}}
                        </p>
                        @endforeach
                    </ul>
                @elseif ($m_question->question_type == "text")
                    {{$attributes->first()->answer_content}}
                @elseif ($m_question->question_type == "bool")
                    @if ($attributes->first()->M_answer_cd->answer_content == "1")
                        ○
                    @endif
                @elseif ($m_question->question_type == "select")
                    {{$attributes->first()->M_answer_cd->answer_content}}
                @endif
                </div>
        </div>
        @endif

    @endforeach

</div>



{{-- <table class="table">
    <tbody>

        @foreach ($m_questions as $m_question)
            @php
                $attributes = $facility->answers->where('question_cd', $m_question->question_cd);
            @endphp

            @if ($attributes->isNotEmpty())
            <tr>
                <th scope="row">{{$m_question->question_content}}</th>
                <td colspan="3" data-th="{{$m_question->question_content}}">
                    @if ($m_question->question_type == "check")
                        <ul class="list-inline">
                            @foreach ($attributes as $attribute)
                            <li class="list-inline-item">
                                {{$attribute->M_answer_cd->answer_content}}
                            </li>
                            @endforeach
                        </ul>
                    @elseif ($m_question->question_type == "text")
                        {{$attributes->first()->answer_content}}
                    @elseif ($m_question->question_type == "bool")
                        @if ($attributes->first()->M_answer_cd->answer_content == "1")
                            ○
                        @endif
                    @elseif ($m_question->question_type == "select")
                        {{$attributes->first()->M_answer_cd->answer_content}}
                    @endif
                </td>
            </tr>
            @endif

        @endforeach

    </tbody>
</table> --}}
