<div class="bg-gray-200 rounded-md w-full py-4 px-4 shadow-lg">
    <input type="hidden" name="csrf-token" content="{{ csrf_token() }}">
    <input id="bigmom-poll-react-data" type="hidden"
        data-questions="{{ implode(',', $questions) }}"
        data-voteRoute="{{ route('poll.vote.castVote') }}"
        data-questionRoute="{{ route('poll.question.getJsonQuestions') }}" />
    <div id="bigmom_poll_react_div"></div>
    <script src="{{ asset('vendor/poll/js/vote.js') }}" defer></script>
</div>