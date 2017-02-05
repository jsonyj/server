$(document).ready(function() {
  $('[data-rel=tooltip]').tooltip();
  $('[data-rel=popover]').popover({html:true});

  $.datepicker.regional['zh-CN'] = {
    clearText: '清除',
    clearStatus: '清除已选日期',
    closeText: '关闭',
    closeStatus: '不改变当前选择',
    prevText: '<上月',
    prevStatus: '显示上月',
    prevBigText: '<<',
    prevBigStatus: '显示上一年',
    nextText: '下月>',
    nextStatus: '显示下月',
    nextBigText: '>>',
    nextBigStatus: '显示下一年',
    currentText: '今天',
    currentStatus: '显示本月',
    monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
    monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
    monthStatus: '选择月份',
    yearStatus: '选择年份',
    weekHeader: '周',
    weekStatus: '年内周次',
    dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
    dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
    dayNamesMin: ['日','一','二','三','四','五','六'],
    dayStatus: '设置 DD 为一周起始',
    dateStatus: '选择 m月 d日, DD',
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    initStatus: '请选择日期',
    isRTL: false
  };

  $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

  $('.form-control-datepicker').datetimepicker({
      'format': 'yyyy-mm-dd',
      'regional': 'zh-CN',
      'language': 'zh-CN',
      'minView': 'month',
      'autoclose': true,
  });

  $('.form-control-datetimepicker').datetimepicker({
      'format': 'yyyy-mm-dd hh:ii',
      'regional': 'zh-CN',
      'language': 'zh-CN',
      'autoclose': true,
  });
});

function datepickerInit() {
    var now = new Date();
    var year = now.getFullYear();
    var startYeah = year - 5;
    var endYear = year + 5;
    $.datepicker.setDefaults({
        showOn: 'both',
        buttonImage: './image/calendar.gif',
        buttonImageOnly: true,
        showOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        speed: 'fast',
        yearRange: startYeah + ':' + endYear
    });
}

function formConfirm(type) {
    if (type == 'save') {
        return confirm('保存してよろしいですか。');
    }
    
    return false;
}

function delConfirm(uri, data) {
    if (confirm('你确定要删除吗？')) {
        $.get(uri, data, function(json){
            if (json.msg) {
                alert(json.msg);
            }
            
            if (json.status) {
                window.location.reload();
            }
        });
    }
}

function getSingleImage(file, name) {
    var html =  '<div class="single_image">' +
                   '<input type="hidden" name="' + name + '" value="' + file.file + '">' +
                   '<div><img src="' + file.full_url + '" /></div>' +
                   '<p style="text-align: center; font-weight: bolder;"><a href="javascript:void(0);" onclick="deleteImage(this, 0, \'' + file._type + '\')">［删除］</a></p>' +
                '</div>';

    return html;
}

function getImageAdd(type) {
    var html = '<div class="image_add">' +
                '<a href="?c=upload&a=image&width=480&height=150&mode=true&_type=' + type + '" class="thickbox" title="增加"><div>+</div></a>' +
                '<p style="text-align: center; font-weight: bolder; margin-top:9px;"><a href="?c=upload&a=image&width=480&height=150&mode=true&_type=' + type + '" class="thickbox" title="增加">［增加］</a></p>' +
               '</div>';
    return html;
}

function order(uri) {
    window.location.href = uri;
}

function pagingChange() {
    document.search_form.submit();
}

function saveConfirm() {
  return confirm('确认保存吗？')
}

function createDropZone(id, name, acceptedFiles, maxFilesize, maxFiles, existFileUrl, deleteFileUrl) {

  var myDropzone = new Dropzone(id , {
    paramName: name, // The name that will be used to transfer the file
    maxFilesize: maxFilesize, // MB
    acceptedFiles: acceptedFiles,
    addRemoveLinks : true,
    dictDefaultMessage :
    '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> 点击</span> 上传  <br /> <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>',

    dictResponseError: '系统错误，请稍后再试。',
    
    //change the previewTemplate to use Bootstrap progress bars
    previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>",

    'dictRemoveFile': '删除',

    'dictRemoveFileConfirmation': '确定删除文件吗？',

    maxFiles: maxFiles,
    init: function() {
        this.hiddenFileInput.removeAttribute('multiple');

        if(maxFiles == 1) {
          this.on("maxfilesexceeded", function(file) {
                this.removeAllFiles();
                this.addFile(file);
          });
        }

        var thisDropzone = this;
        $.get(existFileUrl, function(data) {
            $.each(data, function(key, value){
                var mockFile = { id: value.id, name: value.name, size: value.size, accepted: true };
                thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                thisDropzone.options.thumbnail.call(thisDropzone, mockFile, value.url);
                thisDropzone.files.push(mockFile);

                $('div.progress.progress-small.progress-striped.active').remove();
            });
        }, 'json');

        this.on("success", function(file, response) {
          file.id = response.id; 
        });

        this.on("removedfile", function(file) {
          if (!file.id) { return; }
          $.get(deleteFileUrl, {id:file.id}, function(resp) {
          }); 
        });
      }
    });
}

function openMap(inputId, keyword) {

  var id = 'dialog-map-' + s4();
  var currentPoint = null;

  var html = $('<div id="' + id + '" style="width:770px; height:480px;"></div>');

  BootstrapDialog.show({
      title: '请拖动地图，单击设置坐标（<span id="dialog-map-coordinate">' + $(inputId).val() + '</span>）：',
      size: BootstrapDialog.SIZE_WIDE,
      message: html,
      onshown: function(dialogRef) {

        var titleContainer = $('#dialog-map-coordinate');

        var map = new BMap.Map(id); // 创建地图实例
        map.enableScrollWheelZoom();
        map.addControl(new BMap.NavigationControl());
        map.centerAndZoom(new BMap.Point(104.114129, 37.550339), 4);

        var marker = new BMap.Marker(new BMap.Point(104.114129, 37.550339), {
          enableMassClear: false,
          raiseOnDrag: true
        });
        marker.enableDragging(); // 标记可拖拽
        map.addOverlay(marker);

        marker.addEventListener("dragend", function(e){
          currentPoint = e.point;
          titleContainer.html(currentPoint.lng + ', ' + currentPoint.lat);
        });

        map.addEventListener("click", function(e){
          marker.setPosition(e.point);

          currentPoint = e.point;
          titleContainer.html(currentPoint.lng + ', ' + currentPoint.lat);
        });

        if($(inputId).val() == null || $(inputId).val().length == 0) {

          var myGeo = new BMap.Geocoder();
          // 将地址解析结果显示在地图上，并调整地图视野
          myGeo.getPoint(keyword, function(point){
            if (point) {
                map.centerAndZoom(point, 16);
                marker.setPosition(point);

                currentPoint = point;
                titleContainer.html(' ' + currentPoint.lng + ', ' + currentPoint.lat);
            }
          }, "中国");
        } else {
          var array = $(inputId).val().split(',');
          currentPoint = new BMap.Point(array[0], array[1]);

          map.centerAndZoom(currentPoint, 16);
          marker.setPosition(currentPoint);

          titleContainer.html(currentPoint.lng + ', ' + currentPoint.lat);
        }

      },
      buttons: [{
        label: '确定',
        cssClass: 'btn-primary',
        action: function(dialogItself) {
          $(inputId).val($('#dialog-map-coordinate').html());
          dialogItself.close();
        }
      }, {
        label: '取消',
        action: function(dialogItself){
          dialogItself.close();
        }
      }]
  });
}

function s4() {
   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
}

function selectProvince(province) {
  $.get('?c=user&a=selectProvince', {'province': province}, function(html){
    $("#city").html('<option value="">请选择市</option>' + html);
    $("#district").html('<option value="">请选择区</option>');
  });
}

function selectCity(province, city) {
  $.get('?c=user&a=selectCity', {'province': province, 'city': city}, function(html){
    $("#district").html('<option value="">请选择区</option>' + html);
  });
}

function linkConfirm(msg, lnk) {
  if(confirm(msg)) {
    location.href = lnk;
  }
}