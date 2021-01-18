<div class="w-full max-w-7xl mx-auto">
    @if (session()->has('success'))
    <div class="w-full bg-green-500 bg-opacity-50 text-green-900 px-4 py-2 rounded my-4">
        {{ session()->get('success') }}
    </div>
    @endif
    @if (isset($errors) && $errors->any())
    <div class="w-full bg-red-500 bg-opacity-50 text-red-900 px-8 py-2 rounded my-4">
        <ul class="list-disc">
        @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
        </ul>
    </div>
    @endif
</div>