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
                        {{ __("Welcome $userInfo->name!") }}
                    @endif
                </div>

                <div class="p-10 text-gray-900">
                    <form id="historicalChangesForm">
                        @csrf
                        <div style="display: flex;">
                            <div style="flex: 1;">
                                <label for="currencyCode">Currency Code:</label>
                                <select id="currencyCode" name="currencyCode">
                                    <option value="">Select Currency Code</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label for="startDate">Start Date:</label>
                                <input type="date" id="startDate" name="startDate" value="{{ old('startDate') }}">
                            </div>
                            <div style="flex: 1;">
                                <label for="endDate">End Date:</label>
                                <input type="date" id="endDate" name="endDate" value="{{ old('endDate') }}">
                            </div>
                        </div><br><br>
                    </form>
                    <label for="historicalChangesResult"><b>Currency Changes:</b></label>
                    <div id="historicalChangesResult">
                        @if($historicalChanges)
                            @foreach($historicalChanges as $change)
                                <p>Currency Code: {{ $change->currency->code }}, Rate: {{ $change->rate }}, Change Date: {{ $change->created_at }}</p>
                            @endforeach
                        @endif
                    </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Function to format date to Y-m-d H:i:s format
            function formatDate(date) {
                return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2) + ' 00:00:00';
            }

            // Function to fetch historical changes
            function fetchHistoricalChanges() {
                var startDate = $('#startDate').val() ? formatDate(new Date($('#startDate').val())) : '';
                var endDate = $('#endDate').val() ? formatDate(new Date($('#endDate').val())) : '';
                var formData = $('#historicalChangesForm').serialize();
                formData += '&startDate=' + startDate + '&endDate=' + endDate;

                $.ajax({
                    type: 'POST',
                    url: '/historical-changes',
                    data: formData,
                    success: function (data) {
                        $('#historicalChangesResult').empty(); // Clear previous results
                        data.forEach(function (change) {
                            $('#historicalChangesResult').append('<p>Currency Code: ' + change.currency.code + ', Rate: ' + change.rate + ', Change Date: ' + change.created_at + '</p>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Fetch historical changes on form submission
            $('#historicalChangesForm').submit(function (event) {
                event.preventDefault(); // Prevent default form submission
                fetchHistoricalChanges();
            });

            // Fetch historical changes when currency or date is changed
            $('#currencyCode, #startDate, #endDate').change(function () {
                fetchHistoricalChanges();
            });
        });
    </script>

</x-app-layout>
