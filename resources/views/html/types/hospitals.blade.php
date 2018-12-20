<table class="table table-bordered">
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
                            <li>
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
</table>
