<div {{ $attributes->merge(['class' => "max-w-7xl mx-auto sm:px-6 lg:px-8"]) }}>
    <div class="bg-white shadow-xl sm:rounded-lg px-4 py-4">
        {{ $slot }}
    </div>
</div>