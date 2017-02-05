$.notifyDefaults({
  type: 'pastel-danger',
    allow_dismiss: false,
    delay: 2000,
    z_index: 9999,
    offset: {
      y: 180,
    },
    placement: {
      from: "bottom",
      align: "center"
    },
    animate: {
      enter: 'animated shake',
      exit: 'animated zoomOut'
    }
});

function toast(message, type) {
  if(type == 'info') {
    $.notify({
      message: message,
      target: '_self',
    }, {
      type: 'pastel-success',
      allow_dismiss: false,
      delay: 2000,
      z_index: 9999,
      offset: {
        y: 180,
      },
      animate: {
        enter: 'animated fadeIn',
        exit: 'animated fadeOut'
    }});
  } else {
    $.notify(message);
  }
}

function showConfirmDialog(message, okFn, cancelFn) {
  BootstrapDialog.show({
    title: '',
    cssClass: 'dialog-no-title',
    message: '<p class="text-center">' + message + '</p>',
    buttons: [{
      id: 'btn-cancel',
      label: '取消',
      cssClass: 'btn btn-default',
      autospin: false,
      action: function(dialogRef){
          if(cancelFn != null) {
            cancelFn();
          }
          dialogRef.close();
      }
    },
    {
      id: 'btn-ok',
      label: '确定',
      cssClass: 'btn btn-danger', 
      autospin: false,
      action: function(dialogRef){
          okFn();
          dialogRef.close();
      }
    }]
  });
}