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
      { text: "Ok", click: function() { jQuery( this ).dialog( "close" ); } },
      { text: "Cancel", click: function() { jQuery( this ).dialog( "close" ); } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#picture-frame-selector-modal" ).dialog( "open" );
  });

  jQuery( "a.thumbnail-click" ).each( function() {
    jQuery(this).click( function() {
      var frame_id = "frame_" + this.id;
      jQuery( frame_id ).display = "block";
    });
  });

});