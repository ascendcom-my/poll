<x-bigmom-auth::layout.main>
    <x-slot name="header">Debug Poll</x-slot>
    <x-slot name="headerRightSide">
        <x-bigmom-auth::button.link.blue href="{{ route('bigmom-auth.getHome') }}">Home</x-bigmom-auth::button.link.blue>
    </x-slot>

    <div class="flex justify-center pt-8">
        <div class="container">
            <x-vendor-bigmom-poll-widget:main :questions="$questions"></x-vendor-bigmom-poll-widget:main>
        </div>
    </div>
</x-bigmom-auth::layout.main>