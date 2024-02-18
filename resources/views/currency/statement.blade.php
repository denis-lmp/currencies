<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Account Statement') }}
                            </h2>
                        </header>

                        <table class="table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->counter }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>

                                        @if($transaction->type == 'transfer' && $transaction->sender->email == auth()->user()->email)
                                            {{ ucfirst($transaction->type) }} to {{ $transaction->receiver->email }}
                                        @elseif($transaction->type == 'transfer' && $transaction->receiver->email == auth()->user()->email)
                                            {{ ucfirst($transaction->type) }} from {{ $transaction->sender->email }}
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($transaction->balance) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $transactions->links() }}
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
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
