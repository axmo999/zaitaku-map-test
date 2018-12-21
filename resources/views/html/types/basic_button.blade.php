
    <table class="table">
        <tbody>
            @if($facility->foreign_language)
            <tr>
                <th scope="row">外国語対応</th>
                <td colspan="3" data-th="外国語対応">○</td>
            </tr>
            @endif

            @if($facility->related_facilities)
            <tr>
                <th scope="row">併設・関連施設</th>
                <td colspan="3" data-th="併設・関連施設">
                    {{ $facility->related_facilities }}
                </td>
            </tr>
            @endif

            @if($facility->options)
            <tr>
                <th scope="row">オプション・事業所のアピール等</th>
                <td colspan="3" data-th="オプション・事業所のアピール等">
                    {{ $facility->options }}
                </td>
            </tr>
            @endif

            @if($facility->note)
            <tr>
                <th scope="row">特記</th>
                <td colspan="3" data-th="特記">
                    {{ $facility->note }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

