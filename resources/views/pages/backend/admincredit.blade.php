
@extends('layouts.app')

@section('content')
    <h1>About Us</h1>
    <p>We are a company that specializes in providing quality products and services to our customers.</p>
@endsection
<html>
    <head>
        <title>Account Report</title>
    </head>
    <body>
        <h1>Account Report</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->user_id }}</td>
                        <td>{{ $account->balance }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
