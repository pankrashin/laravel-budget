<x-guest-layout>
    <div class="text-center">

        <!-- Main Heading -->
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">
            Welcome to Laravel Budget
        </h1>
        <br>
    

        <!-- Subheading / Tagline -->
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            A simple and intuitive application to help you manage your personal finances.
        </p>
        <br>
        <!-- Feature List (Optional but recommended) -->
        <div class="mt-8 text-left inline-block">
            <ul class="list-disc list-inside space-y-2 text-gray-600" style="list-style: inside">
                <li>Track multiple accounts in different currencies.</li>
                <li>Calculate your total net worth automatically.</li>
                <li>Installable as a Progressive Web App (PWA).</li>
            </ul>
        </div>

        <br>
        <br>
        

        <!-- Call-to-Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            {{-- Link the button to the login route --}}
            <a href="{{ route('login') }}">
                <x-secondary-button class="px-8 py-3 text-lg">
                    {{ __('Log In') }}
                </x-secondary-button>
            </a>

            
            {{-- Link the button to the register route --}}
            <a href="{{ route('register') }}" style="margin-left: 12px">
                <x-primary-button class="px-8 py-3 text-lg">
                    {{ __('Register') }}
                </x-primary-button>
            </a>
        </div>

        <br>

        <!-- Footer Note -->
        <div class="mt-12">
            <p class="text-sm text-gray-400">
                Learning project by Daniil Pankrashin
            </p>
            <br>
            
            <p class="text-sm text-gray-400">
                For demonstration purposes there is a user daniil@pankrashin.com with password "password"
            </p>
        </div>
    </div>
</x-guest-layout>