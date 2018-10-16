if (typeof $ === 'function') {
    $(function () {
        var customAlert = {
            defaultConfig: {
                showConfirm: true,
                showCancel: false,
                confirmText: '确认',
                cancelText: '取消'
            },
            html: '<div id="customAlertDiv" class="us-modal modal fade in" style="display: block;" aria-hidden="false">' +
           '<div class="modal-header"><a id="closeBtn" class="close" >×</a> <h3 id="Alert_title"><i class="fa fa-warning" style="color:#efc20f;"></i></h3> </div>' +
           '<div class="modal-body ptb20"><h4 class="centerblock" align="center"></h4></div>'+
           '<div class="modal-footer">'+
             '<a id="Alert_confirm" class="btn btn-success" ></a>' +
             '<a id="Alert_cancel" class="btn btn-gray" ></a>' +
           '</div>'+
           '</div>',
            alerthtml: '<div  id="customAlertDiv" class="titpop-backdrop">' +
                        '<div class="titpop-box">'+
                           '<a class="titpop-btn-close">×</a>'+
                           '<p class="titpop-hh"></p>'+
                           '<a class="titpop-btn-checked" >确定</a>'+
                         '</div>'+
                        '</div>',

            overlay: '<div id="myAlertloading" class="modal-backdrop fade in"></div>',
            open: function (title, message, callback, o) {
                var opts = {}, that = this;
                $.extend(opts, that.defaultConfig, o);
                $('body').append(that.html).append(that.overlay);
                title && $('#Alert_title').html(title).show(),
                message && $('.centerblock').text(message).show();
                var confirmBtn = $('#Alert_confirm'), cancelBtn = $('#Alert_cancel'), closeBtn = $('#closeBtn');
                opts.showConfirm && confirmBtn.text(opts.confirmText).show(),
                opts.showCancel && cancelBtn.text(opts.cancelText).show();
                closeBtn.unbind('click').bind('click', function () {
                    that.close();
                });
                confirmBtn.unbind('click').bind('click', function () {
                    that.close();
                    typeof callback === 'function' && callback(true);
                });
                cancelBtn.unbind('click').bind('click', function () {
                    that.close();
                    typeof callback === 'function' && callback(false);
                });
            },
            alertopen: function (message, alerttype) {
                var thatalert = this;
                $('body').append(thatalert.alerthtml);
                var contentStr = alerttype == "" ? message : '<img id="alertImage"/>' + message;
                message && $('.titpop-hh').html(contentStr).show();
                alerttype != "" && $("#alertImage").attr('src', '/tit-' + alerttype + '.png');
                var confirmBtn = $('.titpop-btn-checked'),closeBtn = $('.titpop-btn-close');
                confirmBtn.text("确认").show();
                closeBtn.unbind('click').bind('click', function () {
                    thatalert.close();
                });
                confirmBtn.unbind('click').bind('click', function () {
                    thatalert.close();
                });
            },
            close: function () {
                $("#customAlertDiv").remove();
                $("#myAlertloading").remove();
               
            }
        };
        window.alert = function (message, alerttype) {
            alerttype = typeof (alerttype) == "undefined" ? "" : alerttype;
            customAlert.alertopen(message, alerttype);
        };
        var _confirm = window.confirm;
        window.confirm = function (title, message, callback, opts) {
            opts = $.extend({showCancel: true }, opts);
            if (typeof callback === 'function') {
                customAlert.open(title, message, callback, opts);
            } else {
                return _confirm(title);
            }
        }
    });
}
