<x-layout title="Register">
    <x-form title="Register an Account" description="Start tracking your Ideas today.">
        <form action="/register" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field label="Your Name" name="name" />
            <x-form.field label="Email" name="email" type="email" />
            <x-form.field label="Password" name="password" type="password" />

            <button type="submit" class="btn mt-2 h-10 w-full">Create Account</button>
        </form>
    </x-form>
</x-layout>
