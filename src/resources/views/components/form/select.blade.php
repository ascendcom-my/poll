<select {{ $attributes->merge(['class' => 'px-2 py-1 border border-gray-300 w-full']) }}>
    {{ $slot }}
</select>