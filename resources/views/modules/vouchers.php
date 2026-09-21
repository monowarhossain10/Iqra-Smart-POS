<section class="panel shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="panel-heading d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="section-kicker text-primary fw-bold small text-uppercase">Transaction Records</span>
            <h2 class="h5 fw-bold mb-0">All Vouchers</h2>
        </div>
        <span class="badge bg-light text-dark border"><?= number_format(count($allVouchers ?? [])) ?> total vouchers</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Voucher Number</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Created By</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allVouchers ?? [] as $voucher): ?>
                    <tr>
                        <td><strong><?= e($voucher['voucher_number']) ?></strong></td>
                        <td>
                            <span class="badge bg-<?= match($voucher['voucher_type']) {
                                'sale' => 'success',
                                'purchase' => 'info',
                                'service' => 'warning',
                                'mfs' => 'primary',
                                'utility' => 'danger',
                                'return' => 'secondary',
                                'salary' => 'dark',
                                'income' => 'success',
                                'expense' => 'danger',
                                default => 'light'
                            } ?>-subtle text-<?= match($voucher['voucher_type']) {
                                'sale' => 'success',
                                'purchase' => 'info',
                                'service' => 'warning',
                                'mfs' => 'primary',
                                'utility' => 'danger',
                                'return' => 'secondary',
                                'salary' => 'dark',
                                'income' => 'success',
                                'expense' => 'danger',
                                default => 'dark'
                            } ?> px-2 py-1">
                                <?= e(ucfirst($voucher['voucher_type'])) ?>
                            </span>
                        </td>
                        <td><strong>৳ <?= number_format((float)$voucher['amount'], 2) ?></strong></td>
                        <td><?= e($voucher['full_name']) ?></td>
                        <td class="small text-muted"><?= date('d M Y, h:i A', strtotime($voucher['created_at'])) ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="?route=voucher&id=<?= (int)$voucher['id'] ?>" title="View voucher">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($allVouchers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fs-2 d-block mb-2"></i>
                            No vouchers found in the system.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>