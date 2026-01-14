{{-- ADD BILL --}}
<div class="modal fade" id="addBillModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <form method="POST" action="{{ route('admin.khatabill.store') }}">
    @csrf
    <input type="hidden" name="khatas_id" value="{{ $khata->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Bill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
        </div>
        <div class="modal-body">

          <div class="form-group mb-2">
            <label for="bill_no">Bill No</label>
            <input type="text" name="bill_no" class="form-control form-control-rounded" placeholder="Enter bill no" required>
          </div>
          <div class="form-group mb-2">
            <label for="bill_date">Bill Data</label>
            <input type="date" name="bill_date" class="form-control form-control-rounded date-input" placeholder="Enter date" required>
          </div>
          <div class="form-group mb-2">
            <label for="amount">Bill Amount</label>
            <input type="number" name="amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
          </div>
          <div class="form-group mb-2">
            <label or="note">Note</label>
            <textarea type="text" name="note" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Save</button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- EDIT BILL --}}
<div class="modal fade" id="editBillModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <form method="POST" id="editBillForm">
    @csrf @method('PUT')
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Bill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
        </div>
        <div class="modal-body">


          <div class="form-group mb-2">
            <label for="bill_no">Bill No</label>
            <input type="text" name="bill_no" id="e_bill_no" class="form-control form-control-rounded" placeholder="Enter bill no" required>
          </div>
          <div class="form-group mb-2">
            <label for="bill_date">Bill Data</label>
            <input type="date" name="bill_date" id="e_bill_date" class="form-control form-control-rounded date-input" placeholder="Enter date" required>
          </div>
          <div class="form-group mb-2">
            <label for="amount">Bill Amount</label>
            <input type="number" name="amount" id="e_amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
          </div>
          <div class="form-group">
            <label or="note">Note</label>
            <textarea type="text" name="note" id="e_note" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- ADD EXPENSE --}}
<div class="modal fade" id="addExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <form method="POST" action="{{ route('admin.expense.store') }}">
    @csrf
    <input type="hidden" name="khatas_id" value="{{ $khata->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
        </div>
        <div class="modal-body">

          <div class="form-group mb-2">
            <label for="expense_date">Expense Data</label>
            <input type="date" name="expense_date" class="form-control form-control-rounded date-input" placeholder="Enter date" required>
          </div>
          <div class="form-group mb-2">
            <label for="amount">Amount</label>
            <input type="number" name="amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
          </div>
          <div class="form-group mb-2">
            <label for="payment_type">Payment Type</label>
            <select name="payment_type" class="form-control" required>
              <option value="cash">Cash</option>
              <option value="online">Online</option>
              <option value="cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label or="note">Note</label>
            <textarea type="text" name="note" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Save</button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- EDIT EXPENSE --}}
<div class="modal fade" id="editExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <form method="POST" id="editExpenseForm">
    @csrf @method('PUT')
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
        </div>
        <div class="modal-body">


          <div class="form-group">
            <label for="expense_date">Expense Data</label>
            <input type="date" name="expense_date" id="e_expense_date" class="form-control form-control-rounded date-input" placeholder="Enter date" required>
          </div>
          <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="e_expense_amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
          </div>
          <div class="form-group">
            <label for="payment_type">Payment Type</label>
            <select name="payment_type" class="form-control" id="e_payment_type" required>
              <option value="cash">Cash</option>
              <option value="online">Online</option>
              <option value="cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label or="note">Note</label>
            <textarea type="text" name="note" id="e_expense_note" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </form>
</div>