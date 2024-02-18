<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($userInfo)
                        <table>
                            <tbody>
                            <tr>
                                <td>{{ __("Welcome $userInfo->name!") }}</td>
                            </tr>
                            <tr>
                                <td>{{ __("YOUR ID:") }}</td>
                                <td>{{ $userInfo->email }}</td>
                            </tr>
                            </tbody>
                        </table>
                    @else

                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        td, th {
            padding: 8px;
        }

        tr {
            border-bottom: 1px solid #ddd;
        }

        td:first-child td {
            color: #808080FF;
        }
    </style>


</x-app-layout>
