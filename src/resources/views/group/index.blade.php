<x-poll::layout>
    @push('script')
    <script src="{{ asset('/vendor/poll/js/group.js') }}" defer></script>
    @endpush

    <x-slot name="header">Manage group ({{ $group }})</x-slot>
    <x-slot name="rightSide">
        <x-poll::button.link.blue href="{{ route('poll.question.getIndex') }}">Back</x-poll::button.link.blue>
    </x-slot>
    
    <x-poll::card class="pt-8">
        <x-poll::button.yellow id="sort-save" data-route="{{ route('poll.group.postSort', $group) }}">Save sorting</x-poll::button.yellow>
        <ul id="sort-root" class="py-4 mt-8 bg-gray-200 rounded-xl" data-group="{{ $group }}">
            @foreach ($questions as $question)
            <li class="w-full" data-token="{{ $question->token }}">
            <x-poll::card class="py-2">
                <div class="flex w-full">
                    <svg class="w-6 h-6 sort-handle cursor-move mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    <span>{{ $question->title }}</span>
                </div>
            </x-poll::card>
            </li>
            @endforeach
        </ul>
    </x-poll::card>

    @push('modal')
    <div id="modal-success" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-success-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-success-title" class="font-bold text-2xl text-blue-900">
                    Success
                </h2>
            </header>
            <div id="modal-success-content">
                <h4 id="modal-success-message" class="text-lg pt-4">You're not supposed to see this ; -;</h4>
            </div>
        </div>
    </div>
    <div id="modal-error" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-error-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-error-title" class="font-bold text-2xl text-blue-900">
                    Error
                </h2>
            </header>
            <div id="modal-error-content">
                <h4 id="modal-error-message" class="text-lg pt-4">You're not supposed to see this ; -;</h4>
            </div>
        </div>
    </div>
    @endpush
</x-poll::layout>