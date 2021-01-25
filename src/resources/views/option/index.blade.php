<x-bigmom-auth::layout.main>
    @push('script')
    <script src="{{ asset('vendor/poll/js/option.js') }}" defer></script>
    @endpush

    <x-slot name="header">Option list ({{ $question->title }})</x-slot>
    <x-slot name="headerRightSide">
        <x-bigmom-auth::button.link.blue href="{{ route('bigmom-poll.question.getIndex') }}">Back</x-bigmom-auth::button.link.blue>
    </x-slot>

    <x-bigmom-auth::card class="pt-8">
        <table class="w-full table-auto border-separate mb-2">
            <thead>
                <tr>
                    <x-bigmom-auth::table.th>Text</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th class="hidden md:table-cell">Token</x-bigmom-auth::table.th>
                    <x-bigmom-auth::table.th>Votes</x-bigmom-auth::table.th>
                </tr>
            </thead>
            <tbody>
                @foreach ($question->options as $option)
                <tr>
                    <x-bigmom-auth::table.td class="flex items-center">
                        {{ $option->text }}
                        @if ($loop->last && $question->allow_abstain)
                        <strong class="font-bold">(Abstain)</strong>
                        @endif
                        <button class="btn-update ml-4" data-micromodal-trigger="modal-update" data-route="{{ route('bigmom-poll.option.postUpdate', $option) }}" data-text="{{ $option->text }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Change name</title><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                    </x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td class="hidden md:table-cell">{{ $option->token }}</x-bigmom-auth::table.td>
                    <x-bigmom-auth::table.td>{{ $option->cache_count }}</x-bigmom-auth::table.td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-bigmom-auth::card>

    @push('modal')
    <div id="modal-update" aria-hidden="true" class="modal fixed top-0 left-0 w-screen h-screen flex justify-center items-center">
        <div tabindex="-1" data-micromodal-close class="w-full h-full bg-gray-600 opacity-50 absolute top-0 left-0"></div>
        <div role="dialog" aria-model="true" aria-labelledby="modal-update-title" class="relative bg-white container max-h-3/4 z-30 rounded-lg px-8 py-8 overflow-auto">
            <header class="py-2">
                <h2 id="modal-update-title" class="font-bold text-2xl text-blue-900">
                    Update Question
                </h2>
            </header>
            <div id="modal-update-content" class="mb-4">
                <form method="POST" action="" id="update-form">
                    @csrf
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::form.label for="update-text">Text</x-bigmom-auth::form.label>
                        <x-bigmom-auth::form.input id="update-text" type="text" name="text" required></x-bigmom-auth::form.input>
                    </x-bigmom-auth::form.section>
                    <x-bigmom-auth::form.section>
                        <x-bigmom-auth::button.yellow type="submit">Update</x-bigmom-auth::button.yellow>
                    </x-bigmom-auth::form.section>
                </form>
            </div>
        </div>
    </div>
    @endpush
</x-bigmom-auth::layout.main>