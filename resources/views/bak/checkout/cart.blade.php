<div class="card-header">
    <h5 class="modal-title">Held Transactions</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Session Code</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Hold Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="heldCartsList">
                <!-- Example Row -->
                <tr>
                    <td><span class="badge bg-warning">CS-001</span></td>
                    <td>
                        Budi<br>
                        <small class="text-muted">0812-xxxx-xxxx</small>
                    </td>
                    <td>3 items</td>
                    <td>Rp 450.000</td>
                    <td>
                        <small class="text-muted">5 mins ago</small><br>
                        <small class="text-info">Waiting for service</small>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="resumeCart('CS-001')">
                            <i class="ti ti-arrow-back"></i> Resume
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="cancelCart('CS-001')">
                            <i class="ti ti-x"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
