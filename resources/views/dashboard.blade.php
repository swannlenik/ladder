<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="w-full mt-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Access Rights') }}</h2>
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        @foreach($accessRights as $routeName => $type)
                            <div class="pt-2 w-full border text-center leading-8 {{ $type === 1 ? 'bg-green-200' : 'bg-blue-200' }}">
                                {{ $routeName }} - {{ $type === 1 ? 'RW' : 'RO' }}
                            </div>
                        @endforeach
                    </div>

                    <div class="w-full mt-6" x-data={showLegend:false}>
                        <a x-on:click.prevent="showLegend=!showLegend" class="no-underline">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Legend') }} ({{ __('Click for details') }})
                        </h2>
                        </a>
                        <div class="grid grid-cols-2 gap-4" x-show="showLegend">
                            <div class="bg-green-200 p-2">Page / Route I have <b>Read & Write</b> access</div>
                            <div class="bg-blue-200 p-2">Page / Route I have <b>Read Only</b> access</div>
                        </div>
                    </div>

                    <div class="w-full mt-6" x-data={showDetail:false}>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            <a x-on:click.prevent="showDetail=!showDetail" class="no-underline">
                                {{ __('Access Rights detail') }} ({{ __('Click for details') }})
                            </a>
                        </h2>
                        <div class="grid grid-cols-2 gap-4" x-show="showDetail">
                            @foreach($accessRights as $routeName => $type)
                                <div class="p-2 border">
                                    <i><b>{{ $routeName }}:</b></i> {{ $routes[strtoupper(str_replace('.', '_', $routeName))] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
