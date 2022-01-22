<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ladder') }} {{ $ladder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (count($groups) === 0)
                        <div>
                            {{ __('No existing group in this Ladder') }}
                        </div>
                    @endif

                    @foreach ($groups as $group)
                        <div class="mt-6">
                        <?php $players = $playersByGroup[$group->id]; ?>
                        <?php $statistics = $statisticsByGroup[$group->id]; ?>
                        <a class="font-semibold text-xl text-gray-800 leading-tight">
                            <a href="{{ route('view.group', ['groupID' => $group->id]) }}">
                                {{ $group->groupName }}
                            </a>
                        </h2>

                        @include('groups/playerTable')
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

    <x-slot name="footer">
        <a href="{{ route('view.all.ladders') }}" class="btn-blue">
            {{ __('Back to ladders') }}
        </a>
        @foreach($links as $link)
            <a href="{{ $link['href'] }}" class="{{ $link['class'] ?? 'btn-gray' }} ml-2 mr-2">
                {{ $link['name'] }}
            </a>
        @endforeach
    </x-slot>
</x-app-layout>
