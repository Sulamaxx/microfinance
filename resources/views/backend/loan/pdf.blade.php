<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Loan Calculation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .tableLoan {
            width: 100%;
            border-collapse: collapse;
        }

        .tableLoan th,
        .tableLoan td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: right;
        }

        .tableLoan th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h3 class="text-center mb-3">PEOPLES LANKA - Loan Calculation</h3>

    <table style="width: 100%;" class="border">
        <tr>
            <td style="width: 48%;">
                <!-- First Column (First Table) -->
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Customer Name:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $customer_name }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Approved Amount:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace(floor($apply_amount)) }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Interest Rate:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $interest_rate }}%</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Interest Type:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $interest_type }}</h6>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 48%; padding-left: 20px;">
                <!-- Second Column (Second Table) -->
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Repayment:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $term_period }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Terms:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $term }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">First Payment Date:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ $first_payment_date }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;">
                            <h6 class="text-start fw-bold"></h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end"></h6>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h3 class="text-center mb-3 mt-3">Result</h3>
    <table style="width: 100%;" class="border">
        <tr>
            <td style="width: 48%;">
                <!-- First Column (First Table) -->
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Total Loan Amount:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace(floor($payable_amount)) }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Total Interest:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace($table_data_total[0]['interest_total']) }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Installment:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace($table_data[0]['principle_amount']) }}</h6>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 48%; padding-left: 20px;">
                <!-- Second Column (Second Table) -->
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Capital:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace($table_data[0]['capital']) }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <h6 class="text-start fw-bold">Interest:</h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end">{{ decimalPlace($table_data[0]['interest']) }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;">
                            <h6 class="text-start fw-bold"></h6>
                        </td>
                        <td style="padding: 5px;">
                            <h6 class="text-end"></h6>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="table table-bordered mt-4 tableLoan">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Installment</th>
                <th>Capital</th>
                <th>Interest</th>
                <th>Paid</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data as $td)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d/m/Y', strtotime($td['date'])) }}</td>
                    <td class="text-right">{{ decimalPlace($td['principle_amount']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['capital']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['interest']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['amount_to_pay']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['balance']) }}</td>
                </tr>
            @endforeach

            @foreach ($table_data_total as $td)
                <tr class="fw-bold">
                    <td>Total</td>
                    <td></td>
                    <td class="text-right">{{ decimalPlace($td['principle_amount_total']) }}</td>
                    {{-- <td class="text-right">{{ decimalPlace(floor($td['capital_total'])) }}</td>
                                            <td class="text-right">{{ decimalPlace(floor($td['interest_total'])) }}</td>
                                            <td class="text-right">{{ decimalPlace(floor($td['amount_to_pay_total'])) }}
                                            </td> --}}
                    <td class="text-right">{{ decimalPlace($td['capital_total']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['interest_total']) }}</td>
                    <td class="text-right">{{ decimalPlace($td['amount_to_pay_total']) }}
                    </td>
                    <td class="text-right">{{ decimalPlace(0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
