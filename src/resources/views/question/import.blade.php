<x-poll::layout>
    @push('script')
    <script src="{{ asset('vendor/poll/js/import.js') }}" defer></script>
    @endpush

    <x-slot name="header">Import/Export questions</x-slot>
    <x-slot name="rightSide">
        <x-poll::button.link.blue href="{{ route('poll.question.getIndex') }}">Back</x-poll::button.link.blue>
    </x-slot>

    <x-poll::card class="pt-8">
        <form method="POST" action="{{ route('poll.question.postImport') }}" enctype="multipart/form-data">
            @csrf
            <x-poll::form.section>
                <x-poll::form.label for="file">File to import</x-poll::form.label>
                <x-poll::form.input id="file" type="file" name="file"></x-poll::form.input>
            </x-poll::form.section>
            <x-poll::form.section>
                <x-poll::form.label for="truncate">Clear all current questions and replace with this excel.</x-poll::form.label>
                <input id="truncate" type="checkbox" name="truncate" value="1" class="mx-2">
            </x-poll::form.section>
            <x-poll::form.section>
                <x-poll::button.blue type="submit">Import</x-poll::button.gray>
            </x-poll::form.section>
            <x-poll::form.section>
                <x-poll::button.link.gray href="{{ route('poll.question.downloadTemplate') }}">
                    Download example template
                </x-poll::button.link.gray>
            </x-poll::form.section>
        </form>
    </x-poll::card>
    <x-poll::card class="pt-8">
        <x-poll::button.link.gray href="{{ route('poll.question.downloadExport') }}">Download data</x-poll::button.link.gray>
    </x-poll::card>
</x-poll::layout>