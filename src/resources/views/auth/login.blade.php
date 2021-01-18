<x-poll::layout>
    <x-slot name="header">Poll Login</x-slot>

    <x-poll::card class="pt-8">
        <form action="{{ route('poll.postLogin') }}" method="POST">
            @csrf
            <div class="flex items-center">
                <label for="email" class="w-28">Email</label>
                <input id="email" type="email" name="email" placeholder="Email Address" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline block my-2 w-full">
            </div>
            <div class="flex items-center">
                <label for="password" class="w-28">Password</label>
                <input type="password" name="password" placeholder="Password" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline block my-2 w-full">
            </div>
            <button type="submit" class="my-2 px-4 py-2 bg-gray-300 rounded">Log in</button>
        </form>
    </x-poll::card>
</x-poll::layout>