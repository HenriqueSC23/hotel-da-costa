(() => {
  'use strict';
  const root = document.querySelector('[data-hdc-lp-reviews]');
  if (!root) return;
  const track = root.querySelector('.hdc-lp-reviews__track');
  const carousel = root.querySelector('.hdc-lp-reviews__carousel');
  const dots = root.querySelector('.hdc-lp-reviews__dots');
  const empty = root.querySelector('.hdc-lp-reviews__empty');
  const previous = root.querySelector('[data-hdc-lp-reviews-previous]');
  const next = root.querySelector('[data-hdc-lp-reviews-next]');
  const announcement = root.querySelector('[data-hdc-lp-reviews-announcement]');
  const modal = root.querySelector('[data-hdc-lp-review-modal]');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let items = [], active = 0, initialized = false, opener = null;
  let programmatic = false, scrollTimer = 0, drag = null, suppressedClick = false;
  const element = (tag, className, text) => {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined) node.textContent = text;
    return node;
  };

  function reviewArticle(item, index, full = false) {
    const article = element('article');
    article.setAttribute('aria-label', `Avaliação de ${item.author}`);
    const header = element('header', 'hdc-lp-review__header');
    const avatar = element('span', 'hdc-lp-review__avatar');
    avatar.setAttribute('aria-hidden', 'true');
    avatar.append(element('span', '', Array.from(item.author || 'G')[0]));
    if (item.avatar) {
      const image = element('img');
      image.src = item.avatar; image.alt = ''; image.width = 40; image.height = 40;
      image.loading = 'lazy'; image.decoding = 'async'; image.referrerPolicy = 'no-referrer';
      image.addEventListener('error', () => image.remove(), { once: true });
      avatar.append(image);
    }
    const identity = element('div', 'hdc-lp-review__identity');
    const author = element('h3', 'hdc-lp-review__author', item.author);
    author.title = item.author;
    const date = element('time', 'hdc-lp-review__date', item.dateLabel);
    date.dateTime = item.date || '';
    identity.append(author, date); header.append(avatar, identity);
    const stars = element('div', 'hdc-lp-review__stars');
    stars.setAttribute('role', 'img'); stars.setAttribute('aria-label', `${item.rating} de 5 estrelas`);
    for (let star = 1; star <= 5; star++) {
      const icon = element('span', `hdc-lp-review__star${star > item.rating ? ' hdc-lp-review__star--muted' : ''}`);
      icon.setAttribute('aria-hidden', 'true'); stars.append(icon);
    }
    const body = element('div', 'hdc-lp-review__body');
    body.append(element('p', 'hdc-lp-review__text', item.comment || 'Avaliação sem comentário escrito.'));
    if (!full) {
      const more = element('button', 'hdc-lp-review__more', 'Ler mais');
      more.type = 'button'; more.hidden = true;
      more.dataset.hdcLpReviewMore = String(index);
      more.setAttribute('aria-label', `Ler avaliação completa de ${item.author}`);
      more.setAttribute('aria-haspopup', 'dialog');
      body.append(more);
    }
    article.append(header, stars, body);
    return article;
  }

  function checkExcerpts() {
    const dialogSupported = modal && typeof modal.showModal === 'function';
    // Without a dialog, leave the full review readable instead of trapping a truncated text.
    root.classList.toggle('hdc-lp-reviews--ready', Boolean(dialogSupported));
    track.style.removeProperty('--hdc-lp-reviews-header-height');
    const headerHeight = Math.max(40, ...Array.from(track.querySelectorAll('.hdc-lp-review__header'), node => node.getBoundingClientRect().height));
    track.style.setProperty('--hdc-lp-reviews-header-height', `${headerHeight}px`);
    for (const card of track.children) {
      card.classList.remove('hdc-lp-review--long');
      const text = card.querySelector('.hdc-lp-review__text');
      const more = card.querySelector('[data-hdc-lp-review-more]');
      const long = dialogSupported && text.scrollHeight > 97;
      card.classList.toggle('hdc-lp-review--long', long);
      more.hidden = !long;
    }
  }

  function updateControls(notify = false) {
    Array.from(dots.children).forEach((dot, index) => {
      dot.setAttribute('aria-current', String(index === active));
      dot.tabIndex = index === active ? 0 : -1;
    });
    previous.disabled = active === 0;
    next.disabled = active === items.length - 1;
    if (notify && items.length) announcement.textContent = `Avaliação ${active + 1} de ${items.length}, de ${items[active].author}`;
  }

  function select(index, smooth = true, notify = true) {
    if (!items.length) return;
    active = Math.max(0, Math.min(items.length - 1, index));
    const card = track.children[active];
    const left = card.offsetLeft - track.firstElementChild.offsetLeft;
    programmatic = true;
    track.scrollTo({ left: Math.min(left, track.scrollWidth - track.clientWidth), behavior: smooth && !reducedMotion.matches ? 'smooth' : 'auto' });
    updateControls(notify);
  }

  function fromScroll() {
    if (programmatic || !items.length) return;
    const maximum = track.scrollWidth - track.clientWidth;
    if (maximum > 1 && track.scrollLeft >= maximum - 2) active = items.length - 1;
    else {
      const first = track.firstElementChild.offsetLeft;
      active = Array.from(track.children).reduce((best, card, index) => Math.abs(card.offsetLeft - first - track.scrollLeft) < Math.abs(track.children[best].offsetLeft - first - track.scrollLeft) ? index : best, 0);
    }
    updateControls(true);
  }

  function closeModal() { if (modal.open) modal.close(); }

  function openReview(button) {
    const index = Number(button.dataset.hdcLpReviewMore);
    if (!items[index] || typeof modal.showModal !== 'function') return;
    opener = button;
    const full = modal.querySelector('[data-hdc-lp-review-full]');
    const article = reviewArticle(items[index], index, true);
    full.replaceChildren(...article.children);
    modal.showModal();
    document.body.classList.add('hdc-lp-review-modal-open');
    modal.scrollTop = 0;
    modal.querySelector('[data-hdc-lp-review-close]').focus({ preventScroll: true });
  }

  function bind() {
    previous.addEventListener('click', () => select(active - 1));
    next.addEventListener('click', () => select(active + 1));
    dots.addEventListener('click', event => {
      const button = event.target.closest('[data-hdc-lp-review-dot]');
      if (button) select(Number(button.dataset.hdcLpReviewDot));
    });
    dots.addEventListener('keydown', event => {
      const steps = { ArrowLeft: active - 1, ArrowRight: active + 1, Home: 0, End: items.length - 1 };
      if (!(event.key in steps)) return;
      event.preventDefault(); select(steps[event.key]); dots.children[active].focus({ preventScroll: true });
    });
    track.addEventListener('keydown', event => {
      const steps = { ArrowLeft: active - 1, ArrowRight: active + 1, Home: 0, End: items.length - 1 };
      if (!(event.key in steps)) return;
      event.preventDefault(); select(steps[event.key]);
    });
    track.addEventListener('scroll', () => { clearTimeout(scrollTimer); scrollTimer = window.setTimeout(fromScroll, 140); }, { passive: true });
    track.addEventListener('wheel', () => { programmatic = false; }, { passive: true });
    track.addEventListener('pointerdown', event => {
      programmatic = false;
      if (event.pointerType === 'touch' || event.button !== 0 || event.target.closest('button, a')) return;
      drag = { id: event.pointerId, start: event.clientX, left: track.scrollLeft, moved: false };
    });
    track.addEventListener('pointermove', event => {
      if (!drag || drag.id !== event.pointerId) return;
      const distance = event.clientX - drag.start;
      if (Math.abs(distance) > 6) {
        drag.moved = true;
        if (!track.hasPointerCapture(event.pointerId)) track.setPointerCapture(event.pointerId);
        track.classList.add('hdc-lp-reviews__track--dragging');
        track.scrollLeft = drag.left - distance;
        event.preventDefault();
      }
    });
    function endDrag(event) {
      if (!drag || drag.id !== event.pointerId) return;
      suppressedClick = drag.moved;
      drag = null;
      track.classList.remove('hdc-lp-reviews__track--dragging');
      if (track.hasPointerCapture(event.pointerId)) track.releasePointerCapture(event.pointerId);
      fromScroll();
      window.setTimeout(() => { suppressedClick = false; }, 0);
    }
    track.addEventListener('pointerup', endDrag);
    track.addEventListener('pointercancel', endDrag);
    track.addEventListener('lostpointercapture', endDrag);
    track.addEventListener('pointerleave', event => { if (drag && !drag.moved) drag = null; });
    track.addEventListener('dragstart', event => event.preventDefault());
    track.addEventListener('click', event => {
      if (suppressedClick) { event.preventDefault(); return; }
      const button = event.target.closest('[data-hdc-lp-review-more]');
      if (button) openReview(button);
    });
    modal.querySelector('[data-hdc-lp-review-close]').addEventListener('click', closeModal);
    let outsideDown = false;
    const outside = event => { const r = modal.getBoundingClientRect(); return event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom; };
    modal.addEventListener('pointerdown', event => { outsideDown = outside(event); });
    modal.addEventListener('click', event => { if (outsideDown && outside(event)) closeModal(); outsideDown = false; });
    modal.addEventListener('close', () => {
      document.body.classList.remove('hdc-lp-review-modal-open');
      if (opener && opener.isConnected) opener.focus({ preventScroll: true });
      opener = null;
    });
    // Native dialog supplies modal semantics and Escape. Keep Tab inside even on older browsers.
    modal.addEventListener('keydown', event => {
      if (event.key !== 'Tab') return;
      const focusable = Array.from(modal.querySelectorAll('button, a[href], [tabindex="0"]')).filter(node => !node.disabled && !node.hidden);
      if (!focusable.length) return;
      if (event.shiftKey && document.activeElement === focusable[0]) { event.preventDefault(); focusable.at(-1).focus(); }
      else if (!event.shiftKey && document.activeElement === focusable.at(-1)) { event.preventDefault(); focusable[0].focus(); }
    });
    if (window.ResizeObserver) new ResizeObserver(() => { checkExcerpts(); select(active, false, false); }).observe(track);
    else window.addEventListener('resize', () => { checkExcerpts(); select(active, false, false); });
    document.fonts?.ready.then(checkExcerpts);
  }

  function render(data, serverRendered = false) {
    // Trustindex owns its imported records. The seven-day expiry belongs to the Business Profile cache.
    const expiry = Object.hasOwn(data, 'expiresAt') ? (data.expiresAt ? Date.parse(data.expiresAt) : null) : (data.lastSynced ? Date.parse(data.lastSynced) + 7 * 86400000 : null);
    if (Number.isFinite(expiry) && Date.now() >= expiry) {
      data = { status: 'unavailable', items: [], googleUrl: data.googleUrl };
      serverRendered = false;
    }
    items = Array.isArray(data.items) ? data.items : [];
    if (!items.length) {
      announcement.textContent = '';
      modal.querySelector('[data-hdc-lp-review-full]').replaceChildren();
    }
    if (!serverRendered) {
      track.replaceChildren(...items.map((item, index) => {
        const card = element('li', 'hdc-lp-review');
        card.dataset.hdcLpReviewIndex = String(index);
        card.append(reviewArticle(item, index)); return card;
      }));
    }
    root.querySelectorAll('.hdc-lp-review__avatar img').forEach(image => {
      image.addEventListener('error', () => image.remove(), { once: true });
      if (image.complete && !image.naturalWidth) image.remove();
    });
    carousel.hidden = !items.length; empty.hidden = Boolean(items.length);
    empty.textContent = data.status === 'empty' ? 'Nenhuma avaliação disponível com os filtros atuais. Veja todas no Google.' : 'As avaliações estão indisponíveis no momento. Você pode consultá-las diretamente no Google.';
    // The endpoint is our server's filtered cache, not a direct Google API response.
    if (typeof data.googleUrl === 'string' && /^https:\/\/(?:[^/]+\.)?(?:google\.com(?:\.br)?|maps\.app\.goo\.gl|g\.page)\//.test(data.googleUrl)) root.querySelector('[data-hdc-lp-reviews-google]').href = data.googleUrl;
    dots.replaceChildren(...items.map((item, index) => {
      const dot = element('button', 'hdc-lp-reviews__dot');
      dot.type = 'button'; dot.dataset.hdcLpReviewDot = String(index);
      dot.setAttribute('aria-label', `Ver avaliação ${index + 1} de ${items.length}, de ${item.author}`);
      dot.setAttribute('aria-controls', 'hdc-lp-reviews-track'); return dot;
    }));
    dots.hidden = !items.length; previous.hidden = !items.length; next.hidden = !items.length;
    active = 0;
    if (!initialized) { initialized = true; bind(); }
    checkExcerpts(); updateControls();
    if (Number.isFinite(expiry) && items.length) {
      const remaining = expiry - Date.now();
      if (remaining > 0 && remaining < 7 * 86400000 + 1000) window.setTimeout(() => {
        if (modal.open) modal.close();
        render({status:'unavailable',items:[],googleUrl:data.googleUrl});
      }, remaining);
    }
  }

  const dataNode = root.querySelector('[data-hdc-lp-reviews-data]');
  if (dataNode) {
    try { render(JSON.parse(dataNode.textContent), true); dataNode.remove(); } catch { /* SSR remains readable if JSON is unavailable. */ }
  } else if (root.dataset.hdcLpReviewsEndpoint) {
    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 8000);
    fetch(root.dataset.hdcLpReviewsEndpoint, { signal: controller.signal, credentials: 'omit', cache: 'no-store' })
      .then(response => { if (!response.ok) throw Error('Cache unavailable'); return response.json(); })
      .then(data => render(data))
      .catch(() => render({ status: 'unavailable', items: [] }))
      .finally(() => clearTimeout(timeout));
  }
})();
