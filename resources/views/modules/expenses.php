<?php
// Expenses module content - this file is included in the module layout
?>
<div class="module-grid">
    <!-- New Entry Panel -->
    <section class="panel shadow-sm rounded-4 p-4 bg-white">
        <div class="panel-heading mb-3">
            <div><span class="section-kicker text-primary fw-bold small text-uppercase">New entry</span><h2 class="h5 fw-bold mb-0">Record expense</h2></div>
        </div>
        <form method="post" class="row g-3">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            
            <div class="col-md-6">
                <label class="form-label fw-medium">Category</label>
                <select name="category" class="form-select" required>
                    <option value="Electricity Bill">Electricity Bill</option>
                    <option value="Shop Rent">Shop Rent</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Supplies">Supplies</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Salary">Salary</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Amount</label>
                <input name="amount" class="form-control" type="number" min="0" step=".01" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-medium">Date</label>
                <input name="expense_date" class="form-control" type="date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-medium">Description</label>
                <input name="description" class="form-control" placeholder="Optional details">
            </div>
            <div class="col-12 pt-2">
                <button class="btn btn-primary w-100 py-2 shadow-sm fw-semibold"><i class="fa-solid fa-plus me-1"></i> Save expense record</button>
            </div>
        </form>
    </section>

    <!-- History / Recent Expenses Panel -->
    <section class="panel wide-panel shadow-sm rounded-4 p-4 bg-white">
        <div class="panel-heading mb-3">
            <div><span class="section-kicker text-primary fw-bold small text-uppercase">History</span><h2 class="h5 fw-bold mb-0">Recent expenses</h2></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Recorded by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($expenseRecords)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fs-2 d-block mb-2"></i>
                                No expense records yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($expenseRecords as $record): ?>
                            <tr>
                                <td><strong><?= e($record['category']) ?></strong></td>
                                <td><?= e($record['description'] ?? '-') ?></td>
                                <td><strong class="text-danger">৳ <?= number_format((float) $record['amount'], 2) ?></strong></td>
                                <td><?= date('d M Y', strtotime($record['expense_date'])) ?></td>
                                <td><?= e($record['full_name']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>