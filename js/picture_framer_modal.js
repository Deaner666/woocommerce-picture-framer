jQuery(document).ready( function() {

  jQuery( "#picture-frame-selector-modal" ).dialog({
    modal: true,
    autoOpen: false,
    position: { my: "center", at: "center", of: window },
    width: 800,
    height: 500,
    draggable: false,
    resizable: false,
    show: 175,
    hide: 175,
    buttons: [
      { text: "Ok",
        click: function() {
          
          jQuery( this ).dialog( "close" );
        }
      },
      { text: "Cancel", click: function() { jQuery( this ).dialog( "close" ); } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#picture-frame-selector-modal" ).dialog( "open" );
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
    });
  });

});