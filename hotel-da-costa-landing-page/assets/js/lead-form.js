( function () {
  'use strict';
  const root = document.querySelector( '.hdc-lp' );
  const dialog = root && root.querySelector( '[data-hdc-lp-lead-modal]' );
  const triggers = root ? root.querySelectorAll( '[data-hdc-lp-lead-open]' ) : [];
  const configNode = document.getElementById( 'hdc-lp-lead-config' );
  if ( ! root || ! dialog || ! configNode ) { return; }
  let config;
  try { config = JSON.parse( configNode.textContent ); } catch ( error ) { return; }
  const form = dialog.querySelector( '[data-hdc-lp-lead-form]' );
  const status = dialog.querySelector( '[data-hdc-lp-lead-status]' );
  const submit = form.querySelector( '[type="submit"]' );
  const close = dialog.querySelector( '[data-hdc-lp-lead-close]' );
  const fields = {};
  [ 'hdc_lp_name', 'hdc_lp_email', 'hdc_lp_phone' ].forEach( function ( name ) { fields[ name ] = form.elements.namedItem( name ); } );
  const phone = fields.hdc_lp_phone;
  const utmFields = [ 'utm_term', 'utm_campaign', 'utm_content', 'utm_source', 'utm_medium' ];
  const ddds = '11 12 13 14 15 16 17 18 19 21 22 24 27 28 31 32 33 34 35 37 38 41 42 43 44 45 46 47 48 49 51 53 54 55 61 62 63 64 65 66 67 68 69 71 73 74 75 77 79 81 82 83 84 85 86 87 88 89 91 92 93 94 95 96 97 98 99'.split( ' ' );
  const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );
  let lastTrigger = null;
  let generation = 0;
  let pending = false;
  let controller = null;
  let closeTimer = null;
  let redirectTimer = null;
  let submission = null;
  let backdropPress = false;

  function attribution() {
    const params = new URL( window.location.href ).searchParams;
    const fromUrl = {};
    utmFields.forEach( function ( name ) { fromUrl[ name ] = params.get( name ) || ''; } );
    let values = fromUrl;
    try {
      if ( utmFields.some( function ( name ) { return params.has( name ); } ) ) {
        sessionStorage.setItem( 'hdc_lp_utm', JSON.stringify( fromUrl ) );
      } else {
        const saved = JSON.parse( sessionStorage.getItem( 'hdc_lp_utm' ) || 'null' );
        if ( saved && typeof saved === 'object' && ! Array.isArray( saved ) ) { values = saved; }
      }
    } catch ( error ) { values = fromUrl; }
    utmFields.forEach( function ( name ) { form.elements.namedItem( 'hdc_lp_' + name ).value = typeof values[ name ] === 'string' ? values[ name ] : ''; } );
  }
  attribution();

  function digits( value ) {
    const result = value.replace( /\D/g, '' );
    return result.length === 13 && result.startsWith( '55' ) ? result.slice( 2 ) : result;
  }
  function mask( value ) {
    const result = digits( value ).slice( 0, 11 );
    if ( ! result ) { return ''; }
    if ( result.length < 3 ) { return '(' + result; }
    return '(' + result.slice( 0, 2 ) + ') ' + result.slice( 2, 7 ) + ( result.length > 7 ? '-' + result.slice( 7 ) : '' );
  }
  function caretAfterDigits( value, count ) {
    if ( count <= 0 ) { return value.startsWith( '(' ) ? 1 : 0; }
    let seen = 0;
    for ( let i = 0; i < value.length; i++ ) { if ( /\d/.test( value[ i ] ) && ++seen === count ) { return i + 1; } }
    return value.length;
  }
  function applyMask() {
    const raw = phone.value;
    let before = raw.slice( 0, phone.selectionStart === null ? raw.length : phone.selectionStart ).replace( /\D/g, '' ).length;
    if ( raw.replace( /\D/g, '' ).length === 13 && raw.replace( /\D/g, '' ).startsWith( '55' ) ) { before = Math.max( 0, before - 2 ); }
    phone.value = mask( raw );
    if ( document.activeElement === phone ) { const position = caretAfterDigits( phone.value, before ); phone.setSelectionRange( position, position ); }
  }
  phone.addEventListener( 'input', applyMask );
  phone.addEventListener( 'change', applyMask );
  // Normalize the full clipboard before maxlength can truncate a +55 number.
  phone.addEventListener( 'paste', function ( event ) {
    if ( ! event.clipboardData || phone.readOnly ) { return; }
    event.preventDefault();
    const text = event.clipboardData.getData( 'text' );
    const start = phone.selectionStart === null ? phone.value.length : phone.selectionStart;
    const end = phone.selectionEnd === null ? start : phone.selectionEnd;
    const pasted = digits( text );
    const number = phone.value.slice( 0, start ) + pasted + phone.value.slice( end );
    const count = phone.value.slice( 0, start ).replace( /\D/g, '' ).length + pasted.length;
    phone.value = mask( number );
    const caret = caretAfterDigits( phone.value, Math.min( 11, count ) );
    phone.setSelectionRange( caret, caret );
    phone.dispatchEvent( new Event( 'input', { bubbles: true } ) );
  } );
  phone.addEventListener( 'beforeinput', function ( event ) {
    if ( phone.selectionStart !== phone.selectionEnd || ! [ 'deleteContentBackward', 'deleteContentForward' ].includes( event.inputType ) ) { return; }
    const position = phone.selectionStart;
    const backward = event.inputType === 'deleteContentBackward';
    const neighbor = phone.value[ backward ? position - 1 : position ];
    if ( ! neighbor || /\d/.test( neighbor ) ) { return; }
    event.preventDefault();
    const before = phone.value.slice( 0, position ).replace( /\D/g, '' ).length;
    const index = backward ? before - 1 : before;
    const number = digits( phone.value );
    if ( index < 0 || index >= number.length ) { return; }
    phone.value = mask( number.slice( 0, index ) + number.slice( index + 1 ) );
    const caret = caretAfterDigits( phone.value, backward ? Math.max( 0, before - 1 ) : before );
    phone.setSelectionRange( caret, caret );
    phone.dispatchEvent( new Event( 'input', { bubbles: true } ) );
  } );

  function fieldError( name, message ) {
    const node = form.querySelector( '[data-hdc-lp-error="' + name + '"]' );
    node.textContent = message || ''; node.hidden = ! message;
    if ( message ) { fields[ name ].setAttribute( 'aria-invalid', 'true' ); }
    else { fields[ name ].removeAttribute( 'aria-invalid' ); }
  }
  function validate( name ) {
    const value = fields[ name ].value.trim();
    if ( name === 'hdc_lp_name' && ( Array.from( value ).length < 2 || Array.from( value ).length > 150 || ! /\p{L}/u.test( value ) ) ) { return 'Informe seu nome, com pelo menos dois caracteres.'; }
    if ( name === 'hdc_lp_email' && ( ! value || value.length > 254 || fields[ name ].validity.typeMismatch || ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( value ) ) ) { return 'Informe um e-mail válido.'; }
    if ( name === 'hdc_lp_phone' && ( ! /^[1-9][0-9]9[0-9]{8}$/.test( digits( value ) ) || ! ddds.includes( digits( value ).slice( 0, 2 ) ) ) ) { return 'Informe um celular válido com DDD: (XX) XXXXX-XXXX.'; }
    return '';
  }
  Object.keys( fields ).forEach( function ( name ) {
    fields[ name ].addEventListener( 'input', function () { if ( fields[ name ].hasAttribute( 'aria-invalid' ) ) { fieldError( name, validate( name ) ); } } );
    fields[ name ].addEventListener( 'blur', function () { if ( fields[ name ].value || fields[ name ].hasAttribute( 'aria-invalid' ) ) { fieldError( name, validate( name ) ); } } );
  } );
  function announce( message, error ) { status.textContent = message; status.classList.toggle( 'hdc-lp-lead-form__status--error', Boolean( error ) ); }
  function busy( value ) { pending = value; submit.disabled = value; Object.values( fields ).forEach( function ( field ) { field.readOnly = value; } ); form.setAttribute( 'aria-busy', value ? 'true' : 'false' ); }
  function finishClose() {
    clearTimeout( closeTimer ); closeTimer = null;
    if ( dialog.open ) { dialog.close(); }
  }
  function closeModal() {
    generation++;
    clearTimeout( redirectTimer ); redirectTimer = null;
    if ( controller ) { controller.abort(); controller = null; }
    busy( false );
    dialog.classList.remove( 'hdc-lp-lead-modal--visible' );
    if ( reduced.matches ) { finishClose(); }
    else { clearTimeout( closeTimer ); closeTimer = setTimeout( finishClose, 220 ); }
  }
  function openModal( trigger ) {
    if ( typeof dialog.showModal !== 'function' ) {
      const unavailable = root.querySelector( '[data-hdc-lp-lead-unavailable]' );
      unavailable.hidden = false; unavailable.textContent = 'Atualize seu navegador para preencher o formulário e reservar via WhatsApp.';
      unavailable.scrollIntoView( { block: 'center' } ); return;
    }
    if ( dialog.open ) { return; }
    clearTimeout( closeTimer ); closeTimer = null;
    generation++; lastTrigger = trigger; busy( false ); announce( '' ); attribution(); applyMask();
    dialog.showModal(); document.body.classList.add( 'hdc-lp-lead-open' );
    // Flush the transparent initial state before fading in both panel and backdrop.
    void dialog.offsetWidth;
    dialog.classList.add( 'hdc-lp-lead-modal--visible' );
    fields.hdc_lp_name.focus( { preventScroll: true } );
  }
  triggers.forEach( function ( trigger ) { trigger.addEventListener( 'click', function () { openModal( trigger ); } ); } );
  close.addEventListener( 'click', closeModal );
  dialog.addEventListener( 'cancel', function ( event ) { event.preventDefault(); closeModal(); } );
  dialog.addEventListener( 'close', function () {
    document.body.classList.remove( 'hdc-lp-lead-open' ); dialog.classList.remove( 'hdc-lp-lead-modal--visible' );
    if ( lastTrigger && lastTrigger.isConnected ) { lastTrigger.focus( { preventScroll: true } ); }
  } );
  dialog.addEventListener( 'transitionend', function ( event ) { if ( closeTimer && event.target === dialog && event.propertyName === 'opacity' ) { finishClose(); } } );
  function outside( event ) { const rect = dialog.getBoundingClientRect(); return event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom; }
  dialog.addEventListener( 'pointerdown', function ( event ) { backdropPress = event.target === dialog && outside( event ); } );
  dialog.addEventListener( 'click', function ( event ) { if ( backdropPress && event.target === dialog && outside( event ) ) { closeModal(); } backdropPress = false; } );
  dialog.addEventListener( 'keydown', function ( event ) {
    if ( event.key !== 'Tab' ) { return; }
    const controls = Array.from( dialog.querySelectorAll( 'button, input:not([type="hidden"])' ) ).filter( function ( control ) { return ! control.disabled && control.getClientRects().length && control.tabIndex >= 0; } );
    const first = controls[ 0 ], last = controls[ controls.length - 1 ];
    if ( event.shiftKey && document.activeElement === first ) { event.preventDefault(); last.focus(); }
    else if ( ! event.shiftKey && document.activeElement === last ) { event.preventDefault(); first.focus(); }
  } );
  if ( reduced.addEventListener ) { reduced.addEventListener( 'change', function () { if ( reduced.matches && closeTimer ) { finishClose(); } } ); }

  function uuid() {
    const bytes = new Uint8Array( 16 ); window.crypto.getRandomValues( bytes );
    bytes[ 6 ] = ( bytes[ 6 ] & 15 ) | 64; bytes[ 8 ] = ( bytes[ 8 ] & 63 ) | 128;
    const hex = Array.from( bytes, function ( value ) { return value.toString( 16 ).padStart( 2, '0' ); } ).join( '' );
    return hex.slice( 0, 8 ) + '-' + hex.slice( 8, 12 ) + '-' + hex.slice( 12, 16 ) + '-' + hex.slice( 16, 20 ) + '-' + hex.slice( 20 );
  }
  function knownError( message ) { const error = new Error( message ); error.hdcMessage = true; return error; }
  async function readResponse( response ) {
    let data;
    try { data = await response.json(); } catch ( error ) { throw knownError( 'Não foi possível enviar seus dados. Tente novamente.' ); }
    if ( ! response.ok ) {
      if ( data.data && data.data.fields ) {
        Object.keys( fields ).forEach( function ( name ) { const message = data.data.fields[ name ]; if ( typeof message === 'string' ) { fieldError( name, message ); } } );
        const first = Object.values( fields ).find( function ( field ) { return field.hasAttribute( 'aria-invalid' ); } ); if ( first ) { first.focus(); }
      }
      throw knownError( typeof data.code === 'string' && data.code.startsWith( 'hdc_lp_' ) && typeof data.message === 'string' ? data.message : 'Não foi possível enviar seus dados. Tente novamente.' );
    }
    return data;
  }
  form.addEventListener( 'submit', async function ( event ) {
    event.preventDefault(); if ( pending ) { return; }
    applyMask(); attribution();
    let firstInvalid = null;
    Object.keys( fields ).forEach( function ( name ) {
      const message = validate( name ); fieldError( name, message ); if ( message && ! firstInvalid ) { firstInvalid = fields[ name ]; }
    } );
    if ( firstInvalid ) { announce( 'Confira os campos destacados.', true ); firstInvalid.focus(); return; }
    const values = Object.fromEntries( new FormData( form ).entries() );
    values.hdc_lp_name = values.hdc_lp_name.trim(); values.hdc_lp_email = values.hdc_lp_email.trim(); values.hdc_lp_phone = digits( values.hdc_lp_phone ); values.hdc_lp_page_id = config.pageId;
    const fingerprint = JSON.stringify( values );
    if ( ! submission || submission.fingerprint !== fingerprint ) { submission = { key: uuid(), fingerprint: fingerprint }; }
    values.hdc_lp_submission_key = submission.key;
    const token = generation;
    const transport = new AbortController(); controller = transport;
    let timedOut = false, successful = false;
    const timeout = setTimeout( function () { timedOut = true; transport.abort(); }, 15000 );
    busy( true ); announce( 'Enviando seus dados…' );
    try {
      const settings = await readResponse( await fetch( config.configUrl, { credentials: 'omit', cache: 'no-store', signal: transport.signal } ) );
      if ( settings.formId !== 'hdc-lp-landing-page' || settings.pageId !== Number( config.pageId ) || typeof settings.nonce !== 'string' ) { throw knownError( 'O formulário desta página está indisponível.' ); }
      values.hdc_lp_nonce = settings.nonce;
      const result = await readResponse( await fetch( config.submitUrl, { method: 'POST', credentials: 'omit', cache: 'no-store', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify( values ), signal: transport.signal } ) );
      if ( generation !== token || ! dialog.open ) { return; }
      const target = new URL( result.redirectUrl );
      if ( ! Number.isInteger( result.id ) || result.id < 1 || target.protocol !== 'https:' || ! [ 'api.whatsapp.com', 'wa.me' ].includes( target.hostname ) || target.username || target.password || target.port ) { throw knownError( 'Não foi possível confirmar o envio. Tente novamente.' ); }
      successful = true; announce( 'Dados enviados! Abrindo o WhatsApp…' );
      redirectTimer = setTimeout( function () { if ( token === generation && dialog.open ) { window.location.assign( target.href ); } }, 600 );
    } catch ( error ) {
      if ( generation === token && dialog.open ) { announce( timedOut ? 'O envio demorou mais que o esperado. Tente novamente.' : ( error.hdcMessage ? error.message : 'Não foi possível enviar seus dados. Verifique sua conexão e tente novamente.' ), true ); }
    } finally {
      clearTimeout( timeout ); if ( controller === transport ) { controller = null; }
      if ( generation === token && ! successful ) { busy( false ); }
    }
  } );
}() );
