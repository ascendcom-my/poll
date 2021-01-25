<x-bigmom-auth::layout.main>
    @push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @endpush
    @push('script')
    <script src="{{ asset('vendor/poll/js/question.js') }}" defer></script>
    @endpush

    <x-slot name="header">Question List</x-slot>
    <x-slot name="headerRightSide">
        <x-bigmom-auth::button.link.blue href="{{ route('bigmom-auth.getHome') }}">Home</x-bigmom-auth::button.link.blue>
    </x-slot>

    <input type="hidden" id="required-data" data-server-time="{{ Carbon\Carbon::now() }}">
    <x-bigmom-auth::card class="pt-8">
        <div class="flex justify-end my-2">
            <x-bigmom-auth::button.blue class="mx-2" data-micromodal-trigger="modal-create">Create</x-pobigmom-authll::button.blue>
            <x-bigmom-auth::button.link.gray href="{{ route('bigmom-poll.question.getImport') }}">Import/Export</x-bigmom-auth::button.link.gray>
        </div>
        <table class="w-full table-auto border-collapse mb-2">
            <thead>
                <tr>
                    <x-bigmom-auth::table.th>Title</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th>Option count</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th>Group ID</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th class="hidden md:table-cell">Token</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th class="hidden md:table-cell">Status</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th>Actions</x-bigmom-auth::table.th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $question)
                <tr>
                    <x-bigmom-auth::table.td>{{ $question->title }}</x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td class="">
                        <a href="{{ route('bigmom-poll.question.option.getIndex', $question) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                            {{ $question->options()->count() }}
                        </a>
                    </x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td>
                        <a href="{{ route('bigmom-poll.group.getIndex', ['group' => rawurlencode($question->group_id)]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                            {{ $question->group_id }}
                        </a>
                    </x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td class="hidden md:table-cell">{{ $question->token }}</x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td class="hidden md:table-cell">{{ $question->status_name }}</x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td>
                        <x-bigmom-auth::button.yellow data-micromodal-trigger="modal-update" class="btn-update mx-auto my-1"
                            data-route="{{ route('bigmom-poll.question.postUpdate', $question) }}"
                            data-title="{{ $question->title }}"
                            data-start="{{ $question->formatted_start_at }}"
                            data-stop="{{ $question->formatted_stop_at }}"
                            data-reveal="{{ $question->allow_reveal_result ? '1' : '0' }}"
                            data-group="{{ $question->group_id }}">
                                Update</x-bigmom-auth::button.yellow>
                        <x-bigmom-auth::button.red data-micromodal-trigger="modal-delete" class="btn-delete mx-auto" data-route="{{ route('bigmom-poll.question.postDelete', $question) }}" data-title="{{ $question->title }}">
                            Delete
                        </x-bigmom-auth::button.red>
                    </x-bigmom-auth::table.td>
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
    </x-bigmom-auth::card>

    @push('modal')
    <div id="modal-create" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-create-title" class="relative bg-white container z-30 rounded-lg px-8 py-8 overflow-auto" style="max-height: 75%">
            <header class="py-2">
                <h2 id="modal-create-title" class="font-bold text-2xl text-blue-900">
                    Create Question
                </h2>
            </header>
            <div id="modal-create-content" class="mb-8">
                <form method="POST" action="{{ route('bigmom-poll.question.postCreate') }}">
                    @csrf
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label>Current server time: {{ Carbon\Carbon::now() }}</x-bigmom-auth::form.label>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-title">Title</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="create-title" type="text" name="title" value="{{ old('title') }}" required></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-type">Type</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.select id="create-type" name="type" required>
                            <option>Select one</option>
                            @foreach($questionTypes as $index => $type)
                            <option value="{{ $index }}"
                                @if (old('type') === strval($index))
                                selected
                                @endif
                            >{{ $type }}</option>
                            @endforeach
                        </x-bigmom-auth::form.select>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section class="flex items-center">
                        <x-bigmom-auth::form.label for="create-ballot">Is ballot</x-bigmom-auth::form.label>
                        <input id="create-ballot" type="checkbox" name="is_ballot" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-abstain">Allow abstain</x-bigmom-auth::form.label>
                        <input id="create-abstain" type="checkbox" name="allow_abstain" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-reveal">Reveal result</x-bigmom-auth::form.label>
                        <input id="create-reveal" type="checkbox" name="allow_reveal_result" value="1" class="mx-2"
                            @if (old('is_ballot'))
                            checked
                            @endif
                        >
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-min">Min</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="create-min" type="number" name="min" min="1" value="{{ old('min') }}" required></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-max">Max</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="create-max" type="number" name="max" min="1" value="{{ old('max') }}" required></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-options">Options (Separate with ||)</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.textarea id="create-options" name="options" required>{{ old('options') }}</x-bigmom-auth::form.textarea>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="create-group">Group ID (default: {{ config('poll.default-group', 'question-all') }})</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="create-group" type="text" name="group_id" value="{{ old('group_id') }}" ></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <div class="flex items-center">
                            <x-bigmom-auth::form.label for="create-start">Start at (optional)</x-bigmom-auth::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="create-start">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current server time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-bigmom-auth::form.input type="datetime-local" id="create-start" name="start_at" value="{{ old('start_at') }}"  class="flatpickr"></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <div class="flex items-center">
                            <x-bigmom-auth::form.label for="create-stop">Stop at (optional)</x-bigmom-auth::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="create-stop">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current server time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-bigmom-auth::form.input type="datetime-local" id="create-stop" name="stop_at" value="{{ old('stop_at') }}" class="flatpickr"></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::button.blue type="submit">Create</x-bigmom-auth::button.blue>
                    </x-bigmom-auth::form.section>
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
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label>Current server time: {{ Carbon\Carbon::now() }}</x-bigmom-auth::form.label>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="update-title">Title</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="update-title" type="text" name="title" required></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="update-reveal">Reveal result</x-bigmom-auth::form.label>
                        <input id="update-reveal" type="checkbox" name="allow_reveal_result" value="1" class="mx-2">
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="update-group">Group ID (default: {{ config('poll.default-group', 'question-all') }})</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="update-group" type="text" name="group_id"></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <div class="flex items-center">
                            <x-bigmom-auth::form.label for="update-start">Start at (optional)</x-bigmom-auth::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="update-start">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-bigmom-auth::form.input type="datetime-local" id="update-start" name="start_at" class="flatpickr"></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <div class="flex items-center">
                            <x-bigmom-auth::form.label for="update-stop">Stop at (optional)</x-bigmom-auth::form.label>
                            <button type="button" class="btn-fill-in ml-4" data-target="update-stop">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Fill this field in with current time.</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                        <x-bigmom-auth::form.input type="datetime-local" id="update-stop" name="stop_at" class="flatpickr"></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::button.yellow type="submit">Update</x-bigmom-auth::button.yellow>
                    </x-bigmom-auth::form.section>
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
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label id="delete-title"></x-bigmom-auth::form.label>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::button.red type="submit">Delete</x-bigmom-auth::button.red>
                    </x-bigmom-auth::form.section>
                </form>
            </div>
        </div>
    </div>
    @endpush
</x-bigmom-auth::layout.main>