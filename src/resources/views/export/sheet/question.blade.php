<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Is ballot</th>
            <th>Allow abstain</th>
            <th>Reveal result</th>
            <th>Min</th>
            <th>Max</th>
            <th>Group ID</th>
            <th>Start at</th>
            <th>Stop at</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($questions as $question)
        <tr>
            <td>{{ $question->title }}</td>
            <td>{{ $question->type_name }}</td>
            <td>{{ $question->is_ballot ? "true" : "false" }}</td>
            <td>{{ $question->allow_abstain ? "true" : "false" }}</td>
            <td>{{ $question->allow_reveal_result ? "true" : "false" }}</td>
            <td>{{ $question->min }}</td>
            <td>{{ $question->max }}</td>
            <td>{{ $question->group_id }}</td>
            <td>{{ $question->start_at }}</td>
            <td>{{ $question->stop_at }}</td>
            @foreach ($question->options as $option)
            <td>{{ $option->text }} ({{ $option->cache_count }})</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
            