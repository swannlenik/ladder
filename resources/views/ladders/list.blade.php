<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ladders') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="border-collapse border border-slate-400 w-full">
                        <thead class="bg-slate-100">
                        <tr>
                            <th class="border border-slate-300 p-2">{{ __('Name') }}</th>
                            <th class="border border-slate-300 p-2">{{ __('Date') }}</th>
                            <th class="border border-slate-300 p-2 text-center">{{ __('Singles / Doubles') }}</th>
                            <th class="border border-slate-300 p-2 text-center">{{ __('Actions') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($ladders as $ladder)
                            <tr>
                                <td class="border border-slate-300 p-2">
                                    <a href="{{ route('view.ladder', ['ladderID' => $ladder->id]) }}">{{ $ladder->name }}</a>
                                </td>
                                <td class="border border-slate-300 p-2">{{ $ladder->getDateToString() }}</td>
                                <td class="border border-slate-300 p-2 text-center">{{ $ladder->isSingle ? 'Singles' : 'Doubles' }}</td>
                                <td class="border border-slate-300 p-2 text-center">
                                    <a href="{{ route('view.ladder', ['ladderID' => $ladder->id]) }}" class="btn-gray mx-2">{{ __('View') }}</a>
                                    @if (isset($accessRights['duplicate.ladder']) && $accessRights['delete.ladder'] === 'RW')
                                    <a href="{{ route('duplicate.ladder', ['ladderID' => $ladder->id]) }}" class="btn-green mx-2">{{ __('Duplicate') }}</a>
                                    @endif
                                    @if (isset($accessRights['delete.ladder']) && $accessRights['duplicate.ladder'] === 'RW')
                                        <a href="{{ route('delete.ladder', ['ladderID' => $ladder->id]) }}" class="btn-red mx-2">{{ __('Delete') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if (isset($accessRights['create.ladder']) && $accessRights['create.ladder'] === 'RW')
                            <tr>
                                <form method="POST" action="{{ route('create.ladder') }}" class="table-row">
                                    @csrf
                                    <td class="border border-slate-300 p-2">
                                        <input type="text" name="ladder-name" placeholder="Ladder name"
                                               class="w-full p-2 rounded-lg"
                                               value=""/>
                                    </td>
                                    <td class="border border-slate-300 p-2">
                                        <input type="text" name="ladder-date" placeholder="Ladder date"
                                               class="w-half p-2 rounded-lg"
                                               value="{{ date('Y-m-d') }}"/>
                                    </td>
                                    <td class="border border-slate-300 p-2 text-center">
                                        <input type="checkbox" name="ladder-is-single" placeholder=""
                                               checked="checked"/>
                                    </td>
                                    <td class="border border-slate-300 text-center" colspan="2">
                                        <input type="submit" name="ladder-submit" class="btn-green" value="{{ __('Create new Ladder') }}"/>
                                    </td>
                                </form>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </div>
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
