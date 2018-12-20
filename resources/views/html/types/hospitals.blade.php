<table class="content-map-table table is-fullwidth is-bordered">
    <tbody>

        @foreach ($m_questions as $m_question)
            @php
                $attributes = $facility->answers->where('question_cd', $m_question->question_cd);
            @endphp

            @if ($attributes->isNotEmpty())
            <tr>
                <th scope="row">{{$m_question->question_content}}</th>
                <td colspan="3" data-th="">
                    <ul class="content-map-ul">
                        @foreach ($attributes as $attribute)
                        <li>
                            {{$attribute->M_answer_cd->answer_content}}
                        </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif

        @endforeach

    </tbody>
</table>
