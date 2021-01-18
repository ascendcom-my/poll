<x-poll::button.colorless {{ $attributes->merge(['class' => 'bg-gray-600 text-white font-bold hover:bg-gray-800']) }}>
    {{ $slot }}
</x-poll::button.colorless>