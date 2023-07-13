<!-- members.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('members.search') }}" method="GET">
        <input type="text" name="search" id="search-input" placeholder="Search by name" value="{{ request('search') }}">
        <select name="per_page" id="per-page-select">
            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
            <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
        </select>
        <button type="submit">Search</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Savings</th>
                <th>Arrears</th>
                <th>Monthly Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
            <tr>
                <td>{{ $member->id }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->mobile_number }}</td>
                <td>{{ $member->savings }}</td>
                <td>{{ $member->arrears }}</td>
                <td>{{ $member->monthly_payment }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="pagination">
    @if ($members->lastPage() > 1)
        <ul>
            {{-- Previous Page Link --}}
            @if ($members->currentPage() > 1)
                <li>
                    <a href="{{ $members->url($members->currentPage() - 1) }}" class="pagination-link">&laquo; Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @for ($i = 1; $i <= $members->lastPage(); $i++)
                <li class="{{ ($members->currentPage() == $i) ? 'active' : '' }}">
                    <a href="{{ $members->url($i) }}" class="pagination-link">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next Page Link --}}
            @if ($members->currentPage() < $members->lastPage())
                <li>
                    <a href="{{ $members->url($members->currentPage() + 1) }}" class="pagination-link">Next &raquo;</a>
                </li>
            @endif
        </ul>
    @endif
</div>

    <a href="#" class="add-button" id="add-member-button">Add Member</a>
</div>

<div id="add-member-popup" class="popup">
    <div class="popup-content">
        <h2 id="form-title">Add Member</h2>
        <button id="close-popup">Close</button>
        <form id="add-member-form" method="POST" action="{{ route('members.save') }}">
            @csrf
            <input type="hidden" name="member_id" id="member_id">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number" id="mobile_number" required><br>
            <label for="savings">Savings:</label>
            <input type="text" name="savings" id="savings"><br>
            <label for="arrears">Arrears:</label>
            <input type="text" name="arrears" id="arrears"><br>
            <label for="monthly_payment">Monthly Payment:</label>
            <input type="text" name="monthly_payment" id="monthly_payment"><br>
            <button type="submit" id="submit-button">Add</button>
        </form>
    </div>
</div>
@endsection

<style>
    .container {
        width: 100%;
        max-width: 1200px; /* Adjust the max-width as needed */
        margin: 0 auto;
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .add-button {
        position: fixed;
        bottom: 20px;
        left: 45%;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }

    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .popup-content h2 {
        margin-bottom: 10px;
        width: 400px; /* Adjust the width as needed */
        padding: 20px;
    }

    #close-popup {
        position: absolute; /* Add position absolute */
        top: 0;
        right: 0;
        margin-top: -10px; /* Adjust margin as needed */
        margin-left: -10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px; 
    }

    button[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    #per-page-select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        color: #333;
        font-size: 14px;
        width: 60px;
        margin-right: 20px;
    }

    #per-page-select:hover,
    #per-page-select:focus {
        outline: none;
        border-color: #007bff;
    }

    .pagination {
        display: flex;
        left: 100%;
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .pagination li {
        display: inline-block;
        margin: 0 4px;
    }

    .pagination li a {
        padding: 6px 12px;
        border: 1px solid #ccc;
        background-color: #fff;
        color: #333;
        text-decoration: none;
    }

    .pagination li.active a {
        background-color: #007bff;
        color: #fff;
    }

    .pagination li.disabled a {
        pointer-events: none;
        opacity: 0.6;
    }

</style>


