<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informace o profilu') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Aktualizujte informace o profilu vašeho účtu a e-mailovou adresu.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Jméno')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Vaše e-mailová adresa není ověřena.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Klikněte zde pro opětovné odeslání ověřovacího e-mailu.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Nový ověřovací odkaz byl odeslán na vaši e-mailovou adresu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        
        {{-- NOVÁ POLE PRO KONTAKTY (ROLE, TELEFON, OBRÁZEK) --}}

        <!-- Pozice / Role -->
        <div class="mt-4">
            <x-input-label for="position" :value="__('Pozice / Role (Zobrazí se na kontaktech)')" />
            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position', $user->position)" />
            <x-input-error class="mt-2" :messages="$errors->get('position')" />
        </div>

        <!-- Telefon -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Telefon')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
        
        <!-- Profilový obrázek -->
        <div class="mt-4">
            <x-input-label for="profile_image" :value="__('Profilový obrázek (bude zmenšen na 96x96px)')" />
            <input id="profile_image" name="profile_image" type="file" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            
            @if ($user->profile_image)
                <p class="text-sm mt-2">Aktuální náhled:</p>
                {{-- Načítáme profilovku z public/storage/profiles --}}
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profil" class="mt-2 w-24 h-24 object-cover rounded-full">
            @endif
            
            <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
        </div>
        

<!-- GitHub Link -->
<div class="mt-4">
    <x-input-label for="github_link" :value="__('GitHub URL')" />
    <x-text-input id="github_link" name="github_link" type="url" class="mt-1 block w-full" :value="old('github_link', $user->github_link)" placeholder="https://github.com/vas-profil" />
    <x-input-error class="mt-2" :messages="$errors->get('github_link')" />
</div>

<!-- LinkedIn Link -->
<div class="mt-4">
    <x-input-label for="linkedin_link" :value="__('LinkedIn URL')" />
    <x-text-input id="linkedin_link" name="linkedin_link" type="url" class="mt-1 block w-full" :value="old('linkedin_link', $user->linkedin_link)" placeholder="https://linkedin.com/in/vas-profil" />
    <x-input-error class="mt-2" :messages="$errors->get('linkedin_link')" />
</div>

<!-- Instagram Link -->
<div class="mt-4">
    <x-input-label for="instagram_link" :value="__('Instagram URL')" />
    <x-text-input id="instagram_link" name="instagram_link" type="url" class="mt-1 block w-full" :value="old('instagram_link', $user->instagram_link)" placeholder="https://instagram.com/vas-profil" />
    <x-input-error class="mt-2" :messages="$errors->get('instagram_link')" />
</div>

<!-- Portfolio Link -->
<div class="mt-4">
    <x-input-label for="portfolio_link" :value="__('Portfolio / Vlastní web')" />
    <x-text-input id="portfolio_link" name="portfolio_link" type="url" class="mt-1 block w-full" :value="old('portfolio_link', $user->portfolio_link)" placeholder="https://vasedomena.cz" />
    <x-input-error class="mt-2" :messages="$errors->get('portfolio_link')" />
</div>

<div class="mt-6 border-t pt-4 border-gray-200 dark:border-gray-700">
    <label for="show_on_contacts" class="flex items-center space-x-2 cursor-pointer">
        <input type="checkbox" 
               id="show_on_contacts" 
               name="show_on_contacts" 
               value="1" 
               {{ old('show_on_contacts', $user->show_on_contacts) ? 'checked' : '' }}
               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Zobrazit můj profil na veřejné kontaktní stránce
        </span>
    </label>
    <p class="text-xs text-gray-500 mt-1">Pokud toto pole není zaškrtnuto, váš kontakt se nezobrazí, ale zůstanete adminem.</p>
</div>

        {{-- KONEC NOVÝCH POLÍ --}}

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Uložit') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Uloženo.') }}</p>
            @endif
        </div>
    </form>
</section>