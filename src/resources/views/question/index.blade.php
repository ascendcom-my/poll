<x-poll::layout>
    @push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @endpush
    @push('script')
    <script src="{{ asset('vendor/poll/js/question.js') }}" defer></script>
    @endpush

    <x-slot name="header">Question List</x-slot>
    <x-slot name="rightSide">
        <form method="POST" action="{{ route('poll.postLogout') }}">
            @csrf
            <x-poll::button.red type="submit">Logout</x-poll::button.red>
        </form>
    </x-slot>

    <input type="hidden" id="required-data" data-server-time="{{ Carbon\Carbon::now() }}">
    <x-poll::card class="pt-8">
        <div class="flex justify-end my-2">
            <x-poll::button.blue class="mx-2" data-micromodal-trigger="modal-create">Create</x-poll::button.blue>
            <x-poll::button.link.gray href="{{ route('poll.question.getImport') }}">Import/Export</x-poll::button.link.gray>
        </div>
        <table class="w-full table-auto border-collapse mb-2">
            <thead>
                <tr>
                    <x-poll::table.th>Title</x-poll::table.th>
                    <x-poll::table.th>Option count</x-poll::table.th>
                    <x-poll::table.th>Group ID</x-poll::table.th>
                    <x-poll::table.th class="hidden md:table-cell">Token</x-poll::table.th>
                    <x-poll::table.th class="hidden md:table-cell">Status</x-poll::table.th>
                    <x-poll::table.th>Actions</x-poll::table.th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $question)
                <tr>
                    <x-poll::table.td>{{ $question->title }}</x-poll::table.td>
                    <x-poll::table.td class="">
                        <a href="{{ route('poll.question.option.getIndex', $question) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                            {{ $question->options()->count() }}
                        </a>
                    </x-poll::table.td>
                    <x-poll::table.td>
                        <a href="{{ route('poll.group.getIndex', ['group' => rawurlencode($question->group_id)]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                            {{ $question->group_id }}
                        </a>
                    </x-poll::table.td>
                    <x-poll::table.td class="hidden md:table-cell">{{ $question->token }}</x-poll::table.td>
                    <x-poll::table.td class="hidden md:table-cell">{{ $question->status_name }}</x-poll::table.td>
                    <x-poll::table.td>
                        <x-poll::button.yellow data-micromodal-trigger="modal-update" class="btn-update mx-auto my-1"
                            data-route="{{ route('poll.question.postUpdate', $question) }}"
                            data-title="{{ $question->title }}"
                            data-start="{{ $question->formatted_start_at }}"
                            data-stop="{{ $question->formatted_stop_at }}"
                            data-reveal="{{ $question->allow_reveal_result ? '1' : '0' }}"
                            data-group="{{ $question->group_id }}">
                                Update</x-poll::button.yellow>
                        <x-poll::button.red data-micromodal-trigger="modal-delete" class="btn-delete mx-auto" data-route="{{ route('poll.question.postDelete', $question) }}" data-title="{{ $question->title }}">
                            Delete
                        </x-poll::button.red>
                    </x-poll::table.td>
                </tr>
                @empty
                <tr>
                    <td class="pt-2">
                       No questions yet! Click on the blue "Create" button to create one.
                    </td>
                </tr> 
                @endforelse
            </tbody>
        </table>
        {{ $questions->links() }}
    </x-poll::card>

    @push('modal')
    <div id="modal-create" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-create-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-create-title" class="font-bold text-2xl text-blue-900">
                    Create Question
                </h2>
            </header>
            <div id="modal-create-content" class="mb-8">
                <form method="POST" action="{{ route('poll.question.postCreate') }}">
                    @csrf
                    <x-poll::form.section>
                        <x-poll::form.label>Current server time: {{ Carbon\Carbon::now() }}</x-poll::form.label>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-title">Title</x-poll::form.label>
                        <x-poll::form.input id="create-title" type="text" name="title" value="{{ old('title') }}" required></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-type">Type</x-poll::form.label>
                        <x-poll::form.select id="create-type" name="type" required>
                            <option>Select one</option>
                            @foreach($questionTypes as $index => $type)
                            <option value="{{ $index }}"
                                @if (old('type') === strval($index))
                                selected
                                @endif
                            >{{ $type }}</option>
                            @endforeach
                        </x-poll::form.select>
                    </x-poll::form.section>
                    <x-poll::form.section class="flex items-center">
                        <x-poll::form.label for="create-ballot">Is ballot</x-poll::form.label>
                        <input id="create-ballot" type="checkbox" name="is_ballot" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-abstain">Allow abstain</x-poll::form.label>
                        <input id="create-abstain" type="checkbox" name="allow_abstain" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-reveal">Reveal result</x-poll::form.label>
                        <input id="create-reveal" type="checkbox" name="allow_reveal_result" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-min">Min</x-poll::form.label>
                        <x-poll::form.input id="create-min" type="number" name="min" min="1" value="{{ old('min') }}" required></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-max">Max</x-poll::form.label>
                        <x-poll::form.input id="create-max" type="number" name="max" min="1" value="{{ old('max') }}" required></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-options">Options (Separate with ||)</x-poll::form.label>
                        <x-poll::form.textarea id="create-options" name="options" required>{{ old('options') }}</x-poll::form.textarea>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="create-group">Group ID (default: {{ config('poll.default-group', 'question-all') }})</x-poll::form.label>
                        <x-poll::form.input id="create-group" type="text" name="group_id" value="{{ old('group_id') }}" ></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <div class="flex items-center">
                            <x-poll::form.label for="create-start">Start at (optional)</x-poll::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="create-start">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current server time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-poll::form.input type="datetime-local" id="create-start" name="start_at" value="{{ old('start_at') }}"  class="flatpickr"></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <div class="flex items-center">
                            <x-poll::form.label for="create-stop">Stop at (optional)</x-poll::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="create-stop">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current server time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-poll::form.input type="datetime-local" id="create-stop" name="stop_at" value="{{ old('stop_at') }}" class="flatpickr"></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::button.blue type="submit">Create</x-poll::button.blue>
                    </x-poll::form.section>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-update" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-update-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-update-title" class="font-bold text-2xl text-blue-900">
                    Update Question
                </h2>
            </header>
            <div id="modal-update-content" class="mb-8">
                <form method="POST" action="" id="update-form">
                    @csrf
                    <x-poll::form.section>
                        <x-poll::form.label>Current server time: {{ Carbon\Carbon::now() }}</x-poll::form.label>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="update-title">Title</x-poll::form.label>
                        <x-poll::form.input id="update-title" type="text" name="title" required></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="update-reveal">Reveal result</x-poll::form.label>
                        <input id="update-reveal" type="checkbox" name="allow_reveal_result" value="1" class="mx-2">
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::form.label for="update-group">Group ID (default: {{ config('poll.default-group', 'question-all') }})</x-poll::form.label>
                        <x-poll::form.input id="update-group" type="text" name="group_id"></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <div class="flex items-center">
                            <x-poll::form.label for="update-start">Start at (optional)</x-poll::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="update-start">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-poll::form.input type="datetime-local" id="update-start" name="start_at" class="flatpickr"></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <div class="flex items-center">
                            <x-poll::form.label for="update-stop">Stop at (optional)</x-poll::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="update-stop">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-poll::form.input type="datetime-local" id="update-stop" name="stop_at" class="flatpickr"></x-poll::form.input>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::button.yellow type="submit">Update</x-poll::button.yellow>
                    </x-poll::form.section>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-delete" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-delete-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-delete-title" class="font-bold text-2xl text-blue-900">
                    Are you sure you want to delete this question and all its children?
                </h2>
            </header>
            <div id="modal-delete-content">
                <form id="delete-form" method="POST" action="">
                    @csrf
                    <x-poll::form.section>
                        <x-poll::form.label id="delete-title"></x-poll::form.label>
                    </x-poll::form.section>
                    <x-poll::form.section>
                        <x-poll::button.red type="submit">Delete</x-poll::button.red>
                    </x-poll::form.section>
                </form>
            </div>
        </div>
    </div>
    @endpush
</x-poll::layout>