<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Manager') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data={showUsers:{{ isset($user) ? 'false' : 'true' }}}>
                <div class="w-full p-2">
                    <a x-on:click.prevent="showUsers=!showUsers" class="no-underline w-full">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Users') }}
                            <i x-show="showUsers">({{ __('Click to collapse') }})</i>
                            <i x-show="!showUsers">({{ __('Click to expand') }})</i>
                        </h2>
                    </a>
                </div>

                <div x-show="showUsers" class="grid grid-cols-6 gap-4">
                    @foreach ($users as $u)
                        <div class="w-full text-center">
                            <a href="{{ route('view.users', ['userID' => $u->id]) }}">
                                {{ $u->name }} ({{ ucwords(strtolower(str_replace(['ROLE_', '_'], ['', ' '], $u->role))) }})
                            </a>
                        </div>
                    @endforeach
                </div>

                @if (isset($user))
                    <div class="w-full p-2 mt-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('User') }} {{ $user->name }}
                        </h2>

                        <div class="w-full p-2">
                            <form method="POST" action="{{ route('update.user') }}">
                                @csrf
                                <input type="hidden" name="user-id" value="{{ $user->id }}"/>

                                <div class="flex flex-wrap">
                                    <div class="w-2/12 pr-4 text-right shrink-0">{{ __('Role') }}</div>
                                    <div class="w-10/12 shrink-0">
                                        <select name="user-role">
                                            @foreach (array_keys($roles) as $role)
                                                <option value="{{ $role }}" {{ $role === $user->role ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-2/12 pr-4 text-right shrink-0 mt-2">{{ __('Password Reset') }}</div>
                                    <div class="w-10/12 shrink-0 mt-2">
                                        <input type="password" name="user-password-1" value="" placeholder="{{ __('Password') }}"/>
                                    </div>

                                    <div class="w-2/12 pr-4 text-right mt-2">{{ __('Password Confirmation') }}</div>
                                    <div class="w-10/12 mt-2">
                                        <input type="password" name="user-password-2" value="" placeholder="{{ __('Confirm Password') }}"/>
                                    </div>

                                    <div class="w-full text-center mt-2">
                                        <input type="submit" name="user-submit" value="{{ __('Save Modifications') }}" class="btn-green" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <x-slot name="footer">
        @foreach($links as $link)
            <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                {{ $link['name'] }}
            </a>
        @endforeach
    </x-slot>
</x-app-layout>
