<x-layout>
    <div class="bg-white rounded-log shadow-md w-full md:max-w-xl mx-auto mt-12 px-8 py-12">
        <h2 class="text-4xl text-center font-bold mb-4">
            Login
        </h2>
        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf
            <x-inputs.text id="email" name="email" placeholder="Email address" type="email" />
            <x-inputs.text id="password" name="password" type="password" placeholder="Password" />

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none">
                Register
            </button>

            <p class="mt-4 text-gray-500">
                Don't have an account?
                <a class="text-blue-900" href="{{ route('register') }}" >Register</a>
            </p>
        </form>
    </div>
</x-layout>