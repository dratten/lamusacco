@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('transactions.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by member name">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="loan">Loan</option>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="repayment">Repayment</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="float-end">
                    <button onclick="openPopup('loan')" class="btn btn-success" style="margin-right: 10px">Loan</button>
                    <button onclick="openPopup('deposit')" class="btn btn-primary" style="margin-right: 10px">Deposit</button>
                    <button onclick="openPopup('withdrawal')" class="btn btn-warning" style="margin-right: 10px">Withdraw</button>
                    <button onclick="openPopup('repayment')" class="btn btn-info" style="margin-right: 10px">Repayment</button>
                    <button class="btn btn-danger" id="send-arrears-reminders-btn">Send Reminders</button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="send-reminders-modal" tabindex="-1" role="dialog" aria-labelledby="send-reminders-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="send-reminders-modal-label">Send Loan Due Reminders</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to send loan due reminders?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn cancel-btn btn-danger" data-dismiss="modal">Cancel</button>
                        <a href="{{ route('send.reminders') }}" class="btn btn-success">Confirm</a>
                    </div>
                </div>
            </div>
        </div>

        
        <table class="table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Transacted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->member->name }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->transacted_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Popup Form -->
    <div class="modal fade" id="popup-form" tabindex="-1" role="dialog" aria-labelledby="popup-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup-title">Transaction Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="transaction-form" action="{{ route('transactions.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" id="transaction-type">
                        <div class="mb-3">
                            <label for="member-name" class="form-label">Member Name</label>
                            <select name="member_id" id="member-id" class="form-control" required>
                                <option></option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" data-member-name="{{ $member->name }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="transacted-at" class="form-label">Transacted At</label>
                            <input type="datetime-local" name="transacted_at" id="transacted-at" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function openPopup(type) {
            let title = getTitle(type);
            let form = $('#transaction-form');
            let transactionType = $('#transaction-type');

            $('#popup-title').text(title);
            form.trigger('reset');
            transactionType.val(type);
            
            $('#popup-form').modal('show');
        }

        function getTitle(type) {
            switch (type) {
                case 'loan':
                    return 'Loan';
                case 'deposit':
                    return 'Deposit';
                case 'withdrawal':
                    return 'Withdrawal';
                case 'repayment':
                    return 'Repayment';
                default:
                    return 'Transaction';
            }
        }
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('#send-arrears-reminders-btn').click(function(e) {
                e.preventDefault();
                $('#send-reminders-modal').modal('show');
            });

            $('#send-reminders-modal').on('hidden.bs.modal', function () {
                // Reset any form values or perform additional actions after modal is closed
            });

            $('#send-reminders-modal').on('click', '.cancel-btn', function() {
                $('#send-reminders-modal').modal('hide');
            });

            $('#send-reminders-modal').on('click', '.close', function() {
                $('#send-reminders-modal').modal('hide');
            });
        });
    </script>

@endsection
