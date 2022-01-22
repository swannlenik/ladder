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

                    <table class="border-collapse border border-slate-400 w-full">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="border border-slate-300 p-2">Group Name</th>
                                <th class="border border-slate-300 p-2 text-center">Link</th>
                                <th class="border border-slate-300 p-2 text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                        @if (count($groups) === 0)
                            <tr>
                                <td colspan="3" class="border border-slate-300 p-2 text-center">
                                    {{ __('No existing group in this Ladder') }}
                                </td>
                            </tr>
                        @endif

                        @foreach ($groups as $group)
                            <tr>
                                <td class="border border-slate-300 p-2 w-8/12">
                                    <a href="{{ route('view.group', ['groupID' => $group->id]) }}">{{ $group->groupName }}</a>
                                </td>
                                <td class="border border-slate-300 p-2 text-center">
                                    <a href="{{ route('view.group', ['groupID' => $group->id]) }}" class="btn-gray">View</a>
                                </td>
                                <td class="border border-slate-300 p-2 text-center">
                                    @if (isset($accessRights['delete.group']) && $accessRights['delete.group'] === 'RW')
                                        <a href="{{ route('delete.group', ['groupID' => $group->id]) }}" class="btn-red">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if (isset($accessRights['create.group']) && $accessRights['create.group'] === 'RW')
                            <tr>
                                <td class="border border-slate-300 p-2 text-center" colspan="3">
                                    <a href="{{ route('create.group', ['ladderID' => $ladder->id]) }}" class="btn-green">Create new Group</a>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
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
