jQuery(document).ready( function() {

  var dialogWidth = jQuery( window ).width() * 0.8;
  var dialogHeight = jQuery( window ).height() * 0.85;

  jQuery( "#picture-frame-selector-modal" ).dialog({
    modal: true,
    autoOpen: false,
    position: { my: "center", at: "center", of: window },
    width: dialogWidth,
    height: dialogHeight,
    draggable: false,
    resizable: false,
    show: 175,
    hide: 175,
    buttons: [
      { text: "OK", click: function() { jQuery( this ).dialog( "close" ); } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#picture-frame-selector-modal" ).dialog( "open" );
    jQuery( "#picture-frame-selector-lists" ).height(dialogHeight * 0.7);
  });

  jQuery( "a.frame-thumbnail-click" ).each( function() {
    jQuery(this).click( function() {
      var frame_id = "frame_" + this.id;
      // console.log(frame_id);
      jQuery( '.overlay-frame' ).hide();
      jQuery( '#' + frame_id ).show();
      jQuery( "input[type=radio][value='" + this.title + "']" ).prop( 'checked', true );
    });
  });

  jQuery( "a.mount-thumbnail-click" ).each( function() {
    jQuery(this).click( function() {
      var mount_id = "mount_" + this.id;
      // console.log(mount_id);
      jQuery( '.overlay-mount' ).hide();
      jQuery( '#' + mount_id ).show();
      jQuery( "input[type=radio][value='" + this.title + "']" ).prop( 'checked', true );
    });
  });

});