jQuery(document).ready( function() {

  jQuery( "#picture-frame-selector-modal" ).dialog({
    modal: true,
    autoOpen: false,
    position: { my: "center", at: "center", of: window },
    width: 800,
    height: 600,
    draggable: false,
    resizable: false,
    buttons: [
      { text: "Ok", click: function() { jQuery( this ).dialog( "close" ); } },
      { text: "Cancel", click: function() { jQuery( this ).dialog( "close" ); } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#picture-frame-selector-modal" ).dialog( "open" );
  });

});