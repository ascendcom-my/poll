<x-poll::layout>
    <x-slot name="header">Debug Poll</x-slot>

    <div class="flex justify-center pt-8">
        <div class="container">
            <x-vendor-bigmom-poll-widget:main :questions="$questions"></x-vendor-bigmom-poll-widget:main>
        </div>
    </div>
</x-poll::layout>