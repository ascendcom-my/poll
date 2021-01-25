<x-bigmom-auth::layout.main>
    @push('script')
    <script src="{{ asset('vendor/poll/js/import.js') }}" defer></script>
    @endpush

    <x-slot name="header">Import/Export questions</x-slot>
    <x-slot name="headerRightSide">
        <x-bigmom-auth::button.link.blue href="{{ route('bigmom-poll.question.getIndex') }}">Back</x-bigmom-auth::button.link.blue>
    </x-slot>

    <x-bigmom-auth::card class="pt-8">
        <form method="POST" action="{{ route('bigmom-poll.question.postImport') }}" enctype="multipart/form-data">
            @csrf
            <x-bigmom-auth::form.section>
                <x-bigmom-auth::form.label for="file">File to import</x-bigmom-auth::form.label>
                <x-bigmom-auth::form.input id="file" type="file" name="file"></x-bigmom-auth::form.input>
            </x-bigmom-auth::form.section>
            <x-bigmom-auth::form.section>
                <x-bigmom-auth::form.label for="truncate">Clear all current questions and replace with this excel.</x-bigmom-auth::form.label>
                <input id="truncate" type="checkbox" name="truncate" value="1" class="mx-2">
            </x-bigmom-auth::form.section>
            <x-bigmom-auth::form.section>
                <x-bigmom-auth::button.blue type="submit">Import</x-bigmom-auth::button.gray>
            </x-bigmom-auth::form.section>
            <x-bigmom-auth::form.section>
                <x-bigmom-auth::button.link.gray href="{{ route('bigmom-poll.question.downloadTemplate') }}">
                    Download example template
                </x-bigmom-auth::button.link.gray>
            </x-bigmom-auth::form.section>
        </form>
    </x-bigmom-auth::card>
    <x-bigmom-auth::card class="pt-8">
        <x-bigmom-auth::button.link.gray href="{{ route('bigmom-poll.question.downloadExport') }}">Download data</x-bigmom-auth::button.link.gray>
    </x-bigmom-auth::card>
</x-bigmom-auth::layout.main>