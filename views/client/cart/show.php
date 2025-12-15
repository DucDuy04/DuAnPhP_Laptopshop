<?php

/**
 * Cart View
 * Tương đương client/cart/show.jsp
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Giỏ hàng của bạn</h1>

        <?php if ($isCartEmpty): ?>
            <div class="text-center py-5">
                <i class="fa fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h4>Giỏ hàng trống</h4>
                <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="<?= url('/products') ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else:  ?>
            <form action="<?= url('/cart/update') ?>" method="POST" id="cart-update-form">
                <?= Csrf::field() ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Đơn giá</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Thành tiền</th>
                                <th scope="col">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartDetails as $i => $item): ?>
                                <tr>
                                    <td>
                                        <img src="<?= url('/public/image/product/' . ($item['image'] ?? 'default.png')) ?>"
                                            class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    </td>
                                    <td class="align-middle">
                                        <a href="<?= url('/product/' . $item['product_id']) ?>"><?= e($item['name']) ?></a>
                                    </td>
                                    <td class="align-middle"><?= formatMoney($item['price']) ?></td>
                                    <td class="align-middle" style="width: 160px;">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-secondary btn-decrease" data-index="<?= $i ?>">-</button>
                                            <input type="hidden" name="cartDetails[<?= $i ?>][id]" value="<?= $item['id'] ?>">
                                            <input type="number" class="form-control text-center cart-quantity" name="cartDetails[<?= $i ?>][quantity]" id="cart-qty-<?= $i ?>" value="<?= $item['quantity'] ?>" min="1">
                                            <button type="button" class="btn btn-outline-secondary btn-increase" data-index="<?= $i ?>">+</button>
                                        </div>
                                    </td>
                                    <td class="align-middle item-subtotal" data-item-id="<?= $item['id'] ?>"><?= formatMoney($item['price'] * $item['quantity']) ?></td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-cart"
                                            data-url="<?= url('/delete-cart-product/' . $item['id']) ?>"
                                            data-item-id="<?= $item['id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5>Tổng tiền:</h5>
                                    <h5 class="text-primary" id="cart-total-price"><?= formatMoney($totalPrice) ?></h5>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="<?= url('/checkout') ?>" class="btn btn-primary">
                                        <i class="fa fa-credit-card me-2"></i>Thanh toán
                                    </a>
                                    <a href="<?= url('/products') ?>" class="btn btn-outline-secondary">
                                        Tiếp tục mua sắm
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script>
    // Auto-update cart via AJAX (debounced)
    (function() {
        var form = document.getElementById('cart-update-form');
        if (!form) return;

        var timer = null;
        var delay = 600; // ms

        function formatCurrency(amount) {
            try {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            } catch (e) {
                return amount.toString();
            }
        }

        function scheduleUpdate() {
            if (timer) clearTimeout(timer);
            timer = setTimeout(sendUpdate, delay);
        }

        function sendUpdate() {
            var fd = new FormData(form);
            // Read CSRF token from form field and set header
            var token = fd.get('_csrf_token') || '';

            fetch('<?= url('/cart/update') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: fd
            }).then(function(res) {
                return res.json();
            }).then(function(json) {
                if (!json || !json.success) return;
                // Update subtotals
                if (json.items) {
                    Object.keys(json.items).forEach(function(id) {
                        var data = json.items[id];
                        var td = document.querySelector('.item-subtotal[data-item-id="' + id + '"]');
                        if (td) td.textContent = formatCurrency(parseFloat(data.subtotal));
                    });
                }
                // Update total
                var totalEl = document.getElementById('cart-total-price');
                if (totalEl && typeof json.totalPrice !== 'undefined') {
                    totalEl.textContent = formatCurrency(parseFloat(json.totalPrice));
                }
            }).catch(function(err) {
                console.error('Cart update failed', err);
            });
        }

        // Handle +/- buttons and input changes
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-decrease')) {
                var idx = e.target.getAttribute('data-index');
                var input = document.getElementById('cart-qty-' + idx);
                if (!input) return;
                var val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
                scheduleUpdate();
            }
            if (e.target.matches('.btn-increase')) {
                var idx = e.target.getAttribute('data-index');
                var input = document.getElementById('cart-qty-' + idx);
                if (!input) return;
                var val = parseInt(input.value) || 1;
                input.value = val + 1;
                scheduleUpdate();
            }
        });

        // Input change (typing) - debounce
        var qtyInputs = document.querySelectorAll('.cart-quantity');
        qtyInputs.forEach(function(inp) {
            inp.addEventListener('input', function() {
                // ensure minimum
                var v = parseInt(inp.value) || 1;
                if (v < 1) inp.value = 1;
                scheduleUpdate();
            });
        });

        // Handle delete buttons (AJAX) to avoid nested forms
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-delete-cart');
            if (!btn) return;

            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

            var url = btn.getAttribute('data-url');
            // read CSRF token from the outer form
            var tokenInput = form.querySelector('input[name="_csrf_token"]');
            var token = tokenInput ? tokenInput.value : '';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    },
                    body: new URLSearchParams({
                        '_csrf_token': token
                    })
                }).then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    if (json && json.success) {
                        // remove the row from DOM and reload to refresh totals
                        var tr = btn.closest('tr');
                        if (tr) tr.remove();
                        // reload to update totals and cart count
                        location.reload();
                    } else {
                        alert((json && json.message) || 'Xóa thất bại');
                    }
                }).catch(function(err) {
                    console.error('Delete failed', err);
                    alert('Xóa thất bại');
                });
        });
    })();
</script>