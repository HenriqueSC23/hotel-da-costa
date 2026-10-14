( function () {
  'use strict';
  const root = document.querySelector( '.hdc-lp-submissions-admin' );
  if ( ! root ) { return; }
  const period = root.querySelector( '[data-hdc-lp-period]' );
  const dates = root.querySelectorAll( '[data-hdc-lp-date]' );
  const syncDates = function () { dates.forEach( function ( label ) { label.hidden = period.value !== 'custom'; } ); };
  if ( period ) { period.addEventListener( 'change', syncDates ); syncDates(); }
  const all = root.querySelector( '[data-hdc-lp-select-all]' );
  const boxes = Array.from( root.querySelectorAll( 'input[name="hdc_lp_ids[]"][form="hdc-lp-submissions-bulk"]' ) );
  if ( all ) {
    all.addEventListener( 'change', function () { boxes.forEach( function ( box ) { box.checked = all.checked; } ); } );
    boxes.forEach( function ( box ) {
      box.addEventListener( 'change', function () {
        const selected = boxes.filter( function ( item ) { return item.checked; } ).length;
        all.checked = selected > 0 && selected === boxes.length;
        all.indeterminate = selected > 0 && selected < boxes.length;
      } );
    } );
  }
}() );
