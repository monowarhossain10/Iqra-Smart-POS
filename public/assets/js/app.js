document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('is-ready');
  ensureNavigation();
  if (document.querySelector('.module-heading')) {
    const moduleStyles = document.createElement('link');
    moduleStyles.rel = 'stylesheet';
    moduleStyles.href = `${window.IQRA_APP_URL}/assets/css/modules.css`;
    document.head.appendChild(moduleStyles);
  }
  const posSearch = document.querySelector('#posProductSearch');
  const productTiles = Array.from(document.querySelectorAll('.product-tile'));
  const matchCount = document.querySelector('#productMatchCount');
  const noProductMatches = document.querySelector('#noProductMatches');
  let activeCategory = 'all';
  const filterProducts = () => {
    const query = posSearch?.value.trim().toLowerCase() || '';
    let visible = 0;
    productTiles.forEach(tile => {
      const item = JSON.parse(tile.dataset.product);
      const searchable = `${item.name} ${item.sku} ${item.barcode || ''}`.toLowerCase();
      const matches = (activeCategory === 'all' || tile.dataset.category === activeCategory) && (!query || searchable.includes(query));
      tile.hidden = !matches;
      if (matches) visible += 1;
    });
    if (matchCount) matchCount.textContent = `${visible} product${visible === 1 ? '' : 's'}`;
    if (noProductMatches) noProductMatches.hidden = visible !== 0;
  };
  document.querySelectorAll('.category-pill').forEach(button => button.addEventListener('click', () => {
    activeCategory = button.dataset.category;
    document.querySelectorAll('.category-pill').forEach(item => item.classList.toggle('active', item === button));
    filterProducts();
  }));
  posSearch?.addEventListener('input', filterProducts);

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
      (document.querySelector('#posProductSearch') || document.querySelector('#productSearch'))?.focus();
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
  const addProduct = item => {
    if (item.stock <= 0) return;
    const existing = cart.find(product => product.id === item.id);
    if (existing) existing.quantity = Math.min(existing.quantity + 1, item.stock);
    else cart.push({ ...item, quantity: 1 });
    renderCart();
  };
  productTiles.forEach(tile => tile.addEventListener('click', () => addProduct(JSON.parse(tile.dataset.product))));
  posSearch?.addEventListener('keydown', event => {
    if (event.key !== 'Enter') return;
    const query = posSearch.value.trim().toLowerCase();
    const firstMatch = productTiles.find(tile => {
      const item = JSON.parse(tile.dataset.product);
      return !tile.hidden && !tile.disabled && (item.sku.toLowerCase() === query || String(item.barcode || '').toLowerCase() === query);
    }) || productTiles.find(tile => !tile.hidden && !tile.disabled);
    if (!firstMatch) return;
    event.preventDefault();
    addProduct(JSON.parse(firstMatch.dataset.product));
    posSearch.select();
  });
  cartItems?.addEventListener('click', event => {
    const button = event.target.closest('[data-cart-action]'); if (!button) return;
    const item = cart[Number(button.dataset.cartIndex)];
    if (!item) return;
    if (button.dataset.cartAction === 'plus') item.quantity = Math.min(item.quantity + 1, Number(item.stock || item.quantity + 1)); else item.quantity -= 1;
    if (item.quantity <= 0) cart.splice(Number(button.dataset.cartIndex), 1);
    renderCart();
  });
  document.querySelector('#clearCart')?.addEventListener('click', () => { cart.splice(0); renderCart(); });
  discount?.addEventListener('input', renderCart);
  paidAmount?.addEventListener('input', renderCart);
  const paymentMethod = document.querySelector('#paymentMethod');
  document.querySelectorAll('[data-payment]').forEach(button => button.addEventListener('click', () => {
    if (paymentMethod) paymentMethod.value = button.dataset.payment;
    document.querySelectorAll('[data-payment]').forEach(item => item.classList.toggle('active', item === button));
    if (paidAmount && !paidAmount.value) paidAmount.value = (cart.reduce((sum, item) => sum + item.price * item.quantity, 0) - Math.max(0, Number(discount?.value || 0))).toFixed(2);
    renderCart();
  }));
  paymentMethod?.addEventListener('change', () => document.querySelectorAll('[data-payment]').forEach(button => button.classList.toggle('active', button.dataset.payment === paymentMethod.value)));
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
  paymentMethod?.dispatchEvent(new Event('change'));
  filterProducts();
  document.addEventListener('keydown', event => {
    if (event.key === '/' && !event.ctrlKey && !event.metaKey && !event.altKey && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) {
      event.preventDefault();
      posSearch?.focus();
    }
    if (event.key === 'F4' && !event.ctrlKey && !event.altKey) {
      const holdButton = document.querySelector('#saleForm button[value="hold"]');
      if (holdButton) { event.preventDefault(); holdButton.click(); }
    }
    if (event.ctrlKey && event.key === 'Enter') {
      const completeSale = document.querySelector('#completeSale');
      if (completeSale && cart.length) { event.preventDefault(); completeSale.click(); }
    }
    if (event.key === 'Escape' && document.activeElement === posSearch && posSearch.value) { posSearch.value = ''; filterProducts(); }
  });
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
    const price = Number(product.price ?? product.selling_price ?? 0).toFixed(2);
    const labels = Array.from({ length: quantity }, (_, index) => `<article class="price-label"><div class="label-brand">IQRA STATIONARY</div><strong>${escapeHtml(product.name)}</strong><small>SKU: ${escapeHtml(product.sku)}</small><div class="label-price">৳ ${price}</div><svg id="barcode-${index}"></svg></article>`).join('');
    const barcodeScript = Array.from({ length: quantity }, (_, index) => `JsBarcode('#barcode-${index}', ${JSON.stringify(String(barcodeValue)).replace(/</g, '\\u003c')}, {format:'CODE128', displayValue:true, fontSize:9, height:30, margin:0, width:1.25});`).join('');
    printWindow.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Product price labels</title><script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script><style>@page{size:auto;margin:5mm}*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;color:#111}.sheet{display:flex;flex-wrap:wrap;gap:3mm}.price-label{width:58mm;height:40mm;border:1px solid #111;padding:3mm;display:flex;flex-direction:column;align-items:center;text-align:center;break-inside:avoid;overflow:hidden}.label-brand{width:100%;border-bottom:1px solid #111;padding-bottom:1.5mm;font-size:7pt;font-weight:700;letter-spacing:.6pt}.price-label strong{margin:2mm 0 .5mm;font-size:10pt;line-height:1.15;max-height:23pt;overflow:hidden}.price-label small{font-size:7pt}.label-price{margin:1.5mm 0;font-size:16pt;font-weight:800;line-height:1}.price-label svg{width:45mm;height:9mm}@media print{.sheet{gap:2mm}}</style></head><body><main class="sheet">${labels}</main><script>window.onload=function(){${barcodeScript}window.print();};<\/script></body></html>`);
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
    ['salaries', 'Employee salaries', 'fa-users-gear', '?route=salaries'],
    ['income', 'Income records', 'fa-hand-holding-dollar', '?route=income'],
    ['expenses', 'Expenses', 'fa-money-bill-wave', '?route=expenses'],
    ['accounts', 'All accounts', 'fa-wallet', '?route=accounts'],
    ['vouchers', 'All Vouchers', 'fa-ticket', '?route=vouchers'],
    ['reports', 'Reports', 'fa-chart-line', '?route=reports'],
    ['users', 'User management', 'fa-users', '?route=users']
  ];
  const current = new URLSearchParams(window.location.search).get('route') || 'dashboard';
  const sidebar = document.querySelector('aside');
  if (!sidebar) return;

  const menuItems = links.map(([route, label, icon, href]) => `
    <a class="nav-link ${current === route ? 'active' : ''}" href="${href}">
      <i class="fa-solid ${icon}"></i><span class="nav-label">${label}</span>${route === 'pos' ? '<span class="nav-badge">F2</span>' : ''}
    </a>`).join('');

  sidebar.className = 'sidebar';
  sidebar.id = 'sidebar';
  sidebar.removeAttribute('style');
  sidebar.innerHTML = `
    <div class="sidebar-brand">
      <div class="brand-mark"><i class="fa-solid fa-feather-pointed"></i></div>
      <div class="brand-copy"><strong>iqra</strong><span>stationary solutions</span></div>
    </div>
    <button type="button" class="sidebar-collapse-toggle" id="sidebarCollapseToggle" aria-label="Collapse navigation" aria-expanded="true" title="Collapse navigation"><i class="fa-solid fa-angles-left"></i></button>
    <div class="sidebar-label">Navigation</div>
    <nav class="nav flex-column gap-1">${menuItems}</nav>
    <div class="sidebar-bottom">
      <div class="help-box"><i class="fa-regular fa-circle-question"></i><div class="help-copy"><strong>Need a hand?</strong><small>Use quick actions</small></div></div>
      <a class="nav-link logout-link" href="?route=logout"><i class="fa-solid fa-right-from-bracket"></i><span class="nav-label">Logout</span></a>
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
  const sidebarCollapseToggle = document.querySelector('#sidebarCollapseToggle');
  const setSidebarCollapsed = collapsed => {
    document.body.classList.toggle('sidebar-collapsed', collapsed);
    sidebarCollapseToggle?.setAttribute('aria-expanded', String(!collapsed));
    sidebarCollapseToggle?.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
    sidebarCollapseToggle?.setAttribute('title', collapsed ? 'Expand navigation' : 'Collapse navigation');
    sidebarCollapseToggle?.querySelector('i')?.classList.toggle('fa-angles-right', collapsed);
    sidebarCollapseToggle?.querySelector('i')?.classList.toggle('fa-angles-left', !collapsed);
    try { window.localStorage.setItem('iqra-sidebar-collapsed', collapsed ? '1' : '0'); } catch { /* Storage may be unavailable. */ }
  };
  try { setSidebarCollapsed(window.localStorage.getItem('iqra-sidebar-collapsed') === '1'); } catch { setSidebarCollapsed(false); }
  sidebarCollapseToggle?.addEventListener('click', () => setSidebarCollapsed(!document.body.classList.contains('sidebar-collapsed')));
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
