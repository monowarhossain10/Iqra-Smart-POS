document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('is-ready');
  ensureNavigation();
  if (document.querySelector('.module-heading')) {
    const moduleStyles = document.createElement('link');
    moduleStyles.rel = 'stylesheet';
    moduleStyles.href = `${window.IQRA_APP_URL}/assets/css/modules.css`;
    document.head.appendChild(moduleStyles);
  }
  document.querySelectorAll('.category-pill').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.category-pill').forEach(item => item.classList.remove('active'));
      button.classList.add('active');
      const category = button.dataset.category;
      document.querySelectorAll('.product-tile').forEach(tile => {
        tile.hidden = category !== 'all' && tile.dataset.category !== category;
      });
    });
  });

  const posSearch = document.querySelector('#posProductSearch');
  posSearch?.addEventListener('input', () => {
    const query = posSearch.value.trim().toLowerCase();
    document.querySelectorAll('.product-tile').forEach(tile => {
      tile.hidden = query.length > 0 && !tile.textContent.toLowerCase().includes(query);
    });
  });

  const search = document.querySelector('#productSearch');
  const results = document.querySelector('#productResults');
  let timer;
  search?.addEventListener('input', () => {
    clearTimeout(timer);
    const query = search.value.trim();
    if (query.length < 2) { results.classList.remove('show'); results.innerHTML = ''; return; }
    timer = setTimeout(async () => {
      try {
        const response = await fetch(`${window.IQRA_APP_URL}/?route=api/products&q=${encodeURIComponent(query)}`, { headers: { Accept: 'application/json' } });
        const payload = await response.json();
        results.innerHTML = payload.data?.length ? payload.data.map(product => `<div class="search-item"><span><strong>${escapeHtml(product.name)}</strong><small>${escapeHtml(product.sku)} · ${product.stock_quantity} in stock</small></span><strong>৳ ${Number(product.selling_price).toFixed(0)}</strong></div>`).join('') : '<div class="search-item">No products found</div>';
        results.classList.add('show');
      } catch { results.innerHTML = '<div class="search-item">Unable to search right now</div>'; results.classList.add('show'); }
    }, 250);
  });
  document.addEventListener('click', event => { if (!event.target.closest('.pos-search')) results?.classList.remove('show'); });
  document.addEventListener('keydown', event => {
    if (event.key === 'F2' || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k')) {
      event.preventDefault();
      document.querySelector('#productSearch')?.focus();
    }
  });

  const cart = [];
  const cartItems = document.querySelector('#cartItems');
  const cartInput = document.querySelector('#cartInput');
  const cartTotal = document.querySelector('#cartTotal');
  const cartSubtotal = document.querySelector('#cartSubtotal');
  const cartDiscount = document.querySelector('#cartDiscount');
  const cartCount = document.querySelector('#cartCount');
  const discount = document.querySelector('#saleDiscount');
  const paidAmount = document.querySelector('#paidAmount');
  const changeDue = document.querySelector('#changeDue');
  const changeLabel = document.querySelector('#changeLabel');
  const initialCart = cartItems ? JSON.parse(cartItems.dataset.initialCart || '[]') : [];
  initialCart.forEach(item => {
    const productId = Number(item.product_id ?? item.id ?? 0);
    const quantity = Number(item.quantity ?? 0);
    if (productId > 0 && quantity > 0) cart.push({ ...item, id: productId, quantity });
  });
  const renderCart = () => {
    if (!cartItems) return;
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const discountAmount = Math.min(subtotal, Math.max(0, Number(discount?.value || 0)));
    const total = Math.max(0, subtotal - discountAmount);
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartInput.value = JSON.stringify(cart.map(item => ({ product_id: Number(item.id ?? item.product_id), quantity: item.quantity })));
    if (cartSubtotal) cartSubtotal.textContent = `৳ ${subtotal.toFixed(2)}`;
    if (cartDiscount) cartDiscount.textContent = `৳ ${discountAmount.toFixed(2)}`;
    cartTotal.textContent = `৳ ${total.toFixed(2)}`;
    if (paidAmount && changeDue) {
      const received = Math.max(0, Number(paidAmount.value || 0));
      const difference = received - total;
      changeDue.textContent = `৳ ${Math.abs(difference).toFixed(2)}`;
      if (changeLabel) changeLabel.textContent = difference >= 0 ? 'Change to return' : 'Balance due';
      changeDue.classList.toggle('text-danger', difference < 0);
      changeDue.classList.toggle('text-success', difference >= 0);
    }
    cartItems.innerHTML = cart.length ? cart.map((item, index) => `<div class="cart-row"><span><strong>${escapeHtml(item.name)}</strong><small>${escapeHtml(item.sku)} · ৳ ${item.price.toFixed(2)}</small></span><span class="qty-control"><button type="button" data-cart-action="minus" data-cart-index="${index}">−</button><span>${item.quantity}</span><button type="button" data-cart-action="plus" data-cart-index="${index}">+</button></span><strong>৳ ${(item.price * item.quantity).toFixed(2)}</strong></div>`).join('') : '<div class="empty-state compact"><i class="fa-solid fa-basket-shopping"></i><br>Select an item to begin</div>';
  };
  document.querySelectorAll('.product-tile').forEach(tile => tile.addEventListener('click', () => {
    const item = JSON.parse(tile.dataset.product);
    if (item.stock <= 0) return;
    const existing = cart.find(product => product.id === item.id);
    if (existing) existing.quantity = Math.min(existing.quantity + 1, item.stock);
    else cart.push({ ...item, quantity: 1 });
    renderCart();
  }));
  cartItems?.addEventListener('click', event => {
    const button = event.target.closest('[data-cart-action]'); if (!button) return;
    const item = cart[Number(button.dataset.cartIndex)];
    if (button.dataset.cartAction === 'plus') item.quantity += 1; else item.quantity -= 1;
    if (item.quantity <= 0) cart.splice(Number(button.dataset.cartIndex), 1);
    renderCart();
  });
  document.querySelector('#clearCart')?.addEventListener('click', () => { cart.splice(0); renderCart(); });
  discount?.addEventListener('input', renderCart);
  paidAmount?.addEventListener('input', renderCart);
  document.querySelectorAll('.delete-held-bill').forEach(button => button.addEventListener('click', () => {
    if (!window.confirm('Delete this held bill?')) return;
    const id = document.querySelector('#deleteHeldBillId');
    const form = document.querySelector('#deleteHeldBillForm');
    if (id && form) { id.value = button.dataset.holdId; form.submit(); }
  }));
  document.querySelector('#saleForm')?.addEventListener('submit', event => {
    if (!cart.length) {
      event.preventDefault();
      cartItems?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
  renderCart();
  document.querySelectorAll('[data-filter-table]').forEach(input => input.addEventListener('input', () => {
    const target = document.querySelector(input.dataset.filterTable); const query = input.value.toLowerCase();
    target?.querySelectorAll('tbody tr').forEach(row => { row.hidden = !row.textContent.toLowerCase().includes(query); });
  }));

  document.querySelectorAll('.generate-barcode').forEach(button => button.addEventListener('click', () => {
    const target = document.querySelector(button.dataset.target);
    if (!target) return;
    const prefix = (document.querySelector('[name="sku"]')?.value || 'ITEM').replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 8);
    const suffix = `${Date.now()}`.slice(-7) + Math.floor(Math.random() * 10);
    target.value = `${prefix || 'ITEM'}${suffix}`;
    target.focus();
    target.select();
  }));

  const purchaseItems = document.querySelector('#purchaseItems');
  const addPurchaseRow = document.querySelector('#addPurchaseRow');
  const updatePurchaseTotals = () => {
    let gross = 0;
    let discounts = 0;
    purchaseItems?.querySelectorAll('.purchase-item-row').forEach(row => {
      const quantity = Number(row.querySelector('[name$="[quantity]"]')?.value || 0);
      const cost = Number(row.querySelector('[name$="[unit_cost]"]')?.value || 0);
      const discountValue = Math.max(0, Number(row.querySelector('[name$="[discount]"]')?.value || 0));
      const lineGross = quantity * cost;
      gross += lineGross;
      discounts += Math.min(lineGross, discountValue);
    });
    const total = Math.max(0, gross - discounts);
    const paid = Math.max(0, Number(document.querySelector('#purchasePaidAmount')?.value || 0));
    const setText = (selector, value) => { const element = document.querySelector(selector); if (element) element.textContent = `৳ ${value.toFixed(2)}`; };
    setText('#purchaseGrossTotal', gross);
    setText('#purchaseDiscountTotal', discounts);
    setText('#purchaseNetTotal', total);
    setText('#purchaseBalanceDue', Math.max(0, total - paid));
  };
  addPurchaseRow?.addEventListener('click', () => {
    const rows = purchaseItems?.querySelectorAll('.purchase-item-row');
    const template = rows?.[0];
    if (!purchaseItems || !template) return;
    const index = rows.length;
    const row = template.cloneNode(true);
    row.querySelectorAll('[name]').forEach(input => { input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`); if (input.tagName === 'SELECT') input.selectedIndex = 0; else input.value = input.name.endsWith('[discount]') ? '0' : ''; });
    const productSelect = row.querySelector('.purchase-product-select');
    if (productSelect) productSelect.id = `purchaseProduct${index}`;
    row.querySelector('.remove-purchase-row')?.removeAttribute('disabled');
    purchaseItems.appendChild(row);
    updatePurchaseTotals();
  });
  purchaseItems?.addEventListener('input', updatePurchaseTotals);
  purchaseItems?.addEventListener('input', event => {
    const search = event.target.closest('.purchase-product-search');
    if (!search) return;
    const select = search.closest('.purchase-item-row')?.querySelector('.purchase-product-select');
    if (!select) return;
    const query = search.value.trim().toLowerCase();
    let visibleCount = 0;
    select.querySelectorAll('option').forEach(option => {
      const matches = !query || option.textContent.toLowerCase().includes(query);
      option.hidden = !matches;
      if (matches) visibleCount += 1;
    });
    const firstVisible = Array.from(select.options).find(option => !option.hidden);
    if (query && firstVisible && visibleCount === 1) select.value = firstVisible.value;
  });
  purchaseItems?.addEventListener('click', event => {
    const removeButton = event.target.closest('.remove-purchase-row');
    if (!removeButton || purchaseItems.querySelectorAll('.purchase-item-row').length <= 1) return;
    removeButton.closest('.purchase-item-row')?.remove();
    updatePurchaseTotals();
  });
  document.querySelector('#purchasePaidAmount')?.addEventListener('input', updatePurchaseTotals);
  updatePurchaseTotals();

  document.querySelectorAll('.print-product-labels').forEach(button => button.addEventListener('click', () => {
    const product = JSON.parse(button.dataset.labelProduct);
    const quantity = Math.max(1, Math.min(100, Number(window.prompt('How many labels should be printed?', '1')) || 1));
    const printWindow = window.open('', '_blank', 'width=900,height=700');
    if (!printWindow) return;
    const barcodeValue = product.barcode || product.sku;
    const labels = Array.from({ length: quantity }, (_, index) => `<article class="label"><strong>${escapeHtml(product.name)}</strong><span class="price">৳ ${escapeHtml(product.price)}</span><svg id="barcode-${index}"></svg><small>${escapeHtml(product.sku)} · ${escapeHtml(product.supplier || window.IQRA_APP_NAME || 'Iqra Stationary Solutions')}</small></article>`).join('');
    printWindow.document.write(`<!doctype html><html><head><title>Product labels</title><script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script><style>*{box-sizing:border-box}body{margin:0;padding:18px;font-family:Arial,sans-serif}.sheet{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.label{min-height:145px;border:1px dashed #9ca3af;padding:10px;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;break-inside:avoid}.label strong{font-size:14px;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.label .price{font-size:18px;font-weight:700}.label svg{max-width:100%;height:45px}.label small{font-size:9px;color:#4b5563}@media print{body{padding:0}.label{border-color:#d1d5db}}</style></head><body><div class="sheet">${labels}</div><script>window.onload=function(){${Array.from({ length: quantity }, (_, index) => `JsBarcode('#barcode-${index}',${JSON.stringify(barcodeValue)},{format:'CODE128',displayValue:true,fontSize:10,height:42,margin:0});`).join('')}window.print();};<\/script></body></html>`);
    printWindow.document.close();
  }));
  const serviceSelect = document.querySelector('select[name="service_type_id"]');
  const serviceFee = document.querySelector('input[name="fee"]');
  serviceSelect?.addEventListener('change', () => { serviceFee.value = serviceSelect.selectedOptions[0]?.dataset.fee || 0; });
  serviceSelect?.dispatchEvent(new Event('change'));
});

function ensureNavigation() {
  const links = [
    ['dashboard', 'Overview', 'fa-grid-2', window.IQRA_APP_URL],
    ['pos', 'Point of sale', 'fa-cash-register', '?route=pos'],
    ['sales', 'Recent sales', 'fa-receipt', '?route=sales'],
    ['inventory', 'Inventory', 'fa-boxes-stacked', '?route=inventory'],
    ['purchases', 'Purchases', 'fa-truck-field', '?route=purchases'],
    ['services', 'Service billing', 'fa-print', '?route=services'],
    ['returns', 'Product returns', 'fa-arrow-rotate-left', '?route=returns'],
    ['mfs', 'MFS ledger', 'fa-mobile-screen-button', '?route=mfs'],
    ['utility', 'Utility bills', 'fa-receipt', '?route=utility'],
    ['reports', 'Reports', 'fa-chart-line', '?route=reports'],
    ['salaries', 'Employee salaries', 'fa-users-gear', '?route=salaries'],
    ['users', 'User management', 'fa-users', '?route=users']
  ];
  const current = new URLSearchParams(window.location.search).get('route') || 'dashboard';
  const sidebar = document.querySelector('aside');
  if (!sidebar) return;

  const menuItems = links.map(([route, label, icon, href]) => `
    <a class="nav-link ${current === route ? 'active' : ''}" href="${href}">
      <i class="fa-solid ${icon}"></i>${label}${route === 'pos' ? '<span class="nav-badge">F2</span>' : ''}
    </a>`).join('');

  sidebar.className = 'sidebar';
  sidebar.id = 'sidebar';
  sidebar.removeAttribute('style');
  sidebar.innerHTML = `
    <div class="sidebar-brand">
      <div class="brand-mark"><i class="fa-solid fa-feather-pointed"></i></div>
      <div><strong>iqra</strong><span>stationary solutions</span></div>
    </div>
    <div class="sidebar-label">Navigation</div>
    <nav class="nav flex-column gap-1">${menuItems}</nav>
    <div class="sidebar-bottom">
      <div class="help-box"><i class="fa-regular fa-circle-question"></i><div><strong>Need a hand?</strong><small>Use quick actions</small></div></div>
      <a class="nav-link logout-link" href="?route=logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </div>`;

  document.querySelectorAll('main').forEach(main => {
    main.classList.add('main-content');
    main.classList.remove('standalone-module');
    main.style.marginLeft = '';
  });

  const header = document.querySelector('main header');
  if (header && !header.querySelector('#menuToggle')) {
    const toggle = document.createElement('button');
    toggle.className = 'mobile-menu';
    toggle.id = 'menuToggle';
    toggle.type = 'button';
    toggle.setAttribute('aria-label', 'Open navigation');
    toggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
    header.querySelector(':scope > div')?.prepend(toggle);
  }

  const backdrop = document.createElement('button');
  backdrop.className = 'sidebar-backdrop';
  backdrop.type = 'button';
  backdrop.setAttribute('aria-label', 'Close navigation');
  document.body.appendChild(backdrop);

  const menuToggle = document.querySelector('#menuToggle');
  const closeMenu = () => {
    sidebar.classList.remove('open');
    backdrop.classList.remove('visible');
    document.body.classList.remove('menu-open');
  };
  menuToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    backdrop.classList.toggle('visible');
    document.body.classList.toggle('menu-open');
  });
  backdrop.addEventListener('click', closeMenu);
  sidebar.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
}

function escapeHtml(value) {
  return String(value).replace(/[&<>'"]/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[character]));
}