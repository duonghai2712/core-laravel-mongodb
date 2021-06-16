/**
 * Created by sakura on 20/03/2016.
 */

function CHECKBOX_RADIO_UI() {
    jQuery('input:not(.crm_not_checkbox):checkbox, input:not(.crm_not_radio):radio').uniform({
        radioClass: 'choice',
    });
}


$.fn.resizable = function (options) {
    let minHeight = options && options.minHeight || 60;
    if (minHeight === 60) {
        this.css("height", "auto");
    } else {
        this.css("height", 60);
    }
    this.css("overflow", "hidden");
    this.css("resize", "none");

    this.each(function () {
        if (this.scrollHeight > minHeight) {
            this.style.height = (this.scrollHeight) + 'px';
        }
    });

    $(this).on('input', function () {
        console.log(this.scrollHeight)
        if (this.scrollHeight > minHeight) {
            $(this).height(0).height(this.scrollHeight);
        }
    });

};


function TOOLTIP_UI() {
    $('[data-popup="tooltip"]').tooltip();
}

function SELECT2_UI() {
    $('[data-select="select2"],.select2').select2();
}
if($('[data-toggle="tooltip"]').length){
    $('[data-toggle="tooltip"]').tooltip();
}
let dr_week = [
    "CN",
    "T2",
    "T3",
    "T4",
    "T5",
    "T6",
    "T7"
];
let dr_month = [
    "Tháng 1",
    "Tháng 2",
    "Tháng 3",
    "Tháng 4",
    "Tháng 5",
    "Tháng 6",
    "Tháng 7",
    "Tháng 8",
    "Tháng 9",
    "Tháng 10",
    "Tháng 11",
    "Tháng 12"
];
(function (c) {
    c.fn.serializeJSON = function () {
        var e, d;
        e = {};
        d = this.serializeArray();
        c.each(d, function (h, f) {
            var g, k, j;
            g = f.name;
            k = f.value;
            j = c.map(g.split('['), function (i) {
                var l;
                l = i[i.length - 1];
                return l === ']' ? i.substring(0, i.length - 1) : i;
            });
            if (j[0] === '') {
                j.shift();
            }
            c.deepSet(e, j, k);
        });
        return e;
    };
    var a = function (d) {
        return d === Object(d);
    };
    var b = function (d) {
        return /^[0-9]+$/.test(String(d));
    };
    c.deepSet = function (d, l, i) {
        var j, h, f, g, k, e;
        if (!l || l.length === 0) {
            throw new Error('ArgumentError: keys param expected to be an array with least one key');
        }
        j = l[0];
        if (l.length == 1) {
            if (j === '') {
                d.push(i);
            } else {
                d[j] = i;
            }
        } else {
            h = l[1];
            if (j === '') {
                k = d.length - 1;
                e = d[d.length - 1];
                if (a(e) && !e[h]) {
                    j = k;
                } else {
                    d.push({});
                    j = k + 1;
                }
            }
            if (d[j] === undefined) {
                if (h === '' || b(h)) {
                    d[j] = [];
                } else {
                    d[j] = {};
                }
            }
            f = l.slice(1);
            c.deepSet(d[j], f, i);
        }
    };
})(jQuery);

function _convertToAlias(Text) {
    return Text.toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
}

function _AUTOCOMPLETE_INIT(inputElement, sourceRemote) {
    $(inputElement)
        .autocomplete({
            minLength: 3,
            source: sourceRemote,
            focus: function (event, ui) {
                $(inputElement).val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $(inputElement).val(ui.item.label);
                return false;
            },
        })
        .autocomplete('instance')._renderItem = function (ul, item) {
        return $('<li>')
            .append('<a>' + item.label + '</a>')
            .appendTo(ul);
    };
}

var _EDITOR = false;

function _EDITOR_INIT(element, height, _tool_bar_small,link_upload) {
    if(!link_upload && typeof public_link === 'function'){
        link_upload = public_link('base-table/saveFile');
    }
    tinymce.init({
        selector: element,
        //toolbar1: 'addMediaAdvance | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image  preview media forecolor backcolor code fullscreen',
        toolbar:
            typeof _tool_bar_small !== 'undefined' && _tool_bar_small == true
                ? 'styleselect | bold italic alignleft aligncenter alignright alignjustify bullist numlist link unlink code fullscreen'
                : 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image  preview media forecolor backcolor code fullscreen',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            editor.addButton('addMediaAdvance', {
                text: 'Thêm media',
                icon: false,
                onclick: function () {
                    return MNG_MEDIA.openUploadForm('insertImageToEditor', '');
                    //editor.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
                },
            });
            _EDITOR = editor;
        },
        relative_urls:false,
        entity_encoding: 'raw',
        menubar: false, //không hiển thị menu bar,
        fix_list_elements: true,
        force_p_newlines: true,
        allow_conditional_comments: false, //Không chấp nhận comment html
        height: typeof height !== 'undefined' ? height : 300,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'template paste textcolor colorpicker textpattern imagetools fullscreen',
        ],
        image_caption: true,
        image_advtab: true,
        image_description: true,
        image_title: true,
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', link_upload);
            xhr.setRequestHeader('X-CSRF-TOKEN', jQuery('meta[name=_token]').attr('content'));

            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);
                if (!json || !json.msg || typeof json.msg.link_full !== 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                // success(json.location);
                success(json.msg.link_full);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    });
}

function _GET_SCRIPT(link) {
    var resource = document.createElement('script');
    resource.src = link;
    var script = document.getElementsByTagName('script')[0];
    script.parentNode.insertBefore(resource, script);
}

function _SHOW_FORM_REMOTE(remote_link, target, multiform,callback) {
    if (target === undefined || target == '') {
        target = 'myModal';
    }
    if (multiform != undefined && multiform!==false) {
        target = target + remote_link.replace(/[^\w\s]/gi, '');
    } else {
        jQuery('.modal-backdrop').remove();
    }
    jQuery('#' + target).remove();
    jQuery('body').append(
        '<div class="modal fade" id="' +
        target +
        '" tabindex="-1" role="dialog" ' +
        'aria-labelledby="' +
        target +
        'Label" aria-hidden="true">' +
        '<div class="mmbd"></div></div>',
    );
    var modal = jQuery('#' + target),
        modalBody = jQuery('#' + target + ' .mmbd');
    modal
        .on('show.bs.modal', function () {

            modalBody.load(remote_link,
                function (response, status, xhr) {
                    try {
                        var responseAsObject = $.parseJSON(response);
                        $(this).html(`
<div class="modal-dialog modal-large">
    <div class="modal-content">
        <div class="modal-header bg-danger">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title">
      ${responseAsObject.msg}
            </h3>
        </div>
       <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng lại</button>
        </div>
    </div>
</div>`);
                    } catch (e) {
                        // $(this).html(response);
                    }
                    if(typeof callback=='function')
                    {
                        callback();
                    }
                }
            );
        })
        .modal({backdrop: 'static'});
    return false;
}

function _SHOW_FORM_REMOTE_HTML(remote_html, target, multiform) {
    if (target === undefined || target == '') {
        target = 'myModal';
    }
    if (multiform != undefined&&multiform!==false) {
        target = target + remote_html.replace(/[^\w\s]/gi, '');
    } else {
        jQuery('.modal-backdrop').remove();
    }
    jQuery('#' + target).remove();
    jQuery('body').append(
        '<div class="modal fade" id="' +
        target +
        '" tabindex="-1" role="dialog" ' +
        'aria-labelledby="' +
        target +
        'Label" aria-hidden="true">' +
        '<div class="mmbd"></div></div>',
    );
    var modal = jQuery('#' + target),
        modalBody = jQuery('#' + target + ' .mmbd');
    modal
        .on('show.bs.modal', function () {
            try {
                $(this).html(remote_html)
            } catch (e) {
                console.log(e);
            }
        })
        .modal({backdrop: 'static'});
    return false;
}

function _SHOW_REMOTE_HTML(remote_link, options) {
    jQuery('#remote_html').remove();
    jQuery('body').append('<div id="remote_html" class=""></div>');
    let remote_html = jQuery('#remote_html');
    remote_html.load(remote_link, function (response, status, xhr) {
        var ct = xhr.getResponseHeader("content-type") || "";
        if (ct.indexOf('json') > -1) {
            // handle json here
            if (response) {
                let obj = JSON.parse(response);
                console.log(obj)
                if (obj.status != 1) {
                    alert(obj.msg)
                    remote_html.hide()
                }
            }
        } else {
            remote_html.hide().fadeIn(300);
        }

    });
}

var STATUS_JSON_DONE = 1;
var ALL_POST_RESULT = [];

var PRIVATE_PREFIX = '/private/';

function _POST(url, data, callback, cache, type, retry) {
    if (cache != undefined) {
        if (ALL_POST_RESULT[cache] != undefined) {
            return callback(ALL_POST_RESULT[cache]);
        }
    }
    var _token = jQuery('meta[name=_token]').attr('content');
    if (_token) {
        var _data = {name: '_token', value: _token};
        data.push(_data);

    }
    console.log('data', data);
    if (type == undefined) {
        type = 'json';
    }
    jQuery.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: type,
        success: function (data) {
            MNG_POST.running = 0;

            if (cache != undefined && cache != null) {
                ALL_POST_RESULT[cache] = data;
            }
            typeof callback === "function" && eval(callback(data))
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (typeof retry === "function") {
                eval(retry(data));
            } else {
                MNG_POST.running = 0;
                if(xhr.status ===401){
                    alert(xhr.responseJSON.msg ? xhr.responseJSON.msg : '')
                }else{
                    alert(thrownError);

                }
            }


        },
    });
    return true;
}

var App = {
    DOMAIN: document.location.origin,
    API: document.location.origin + '/api',
};

var MNG_SEO = {
    settings: {
        URL_ACTION: App.DOMAIN + PRIVATE_PREFIX + 'seo/',
    },
    MNG_LANDING_PAGE: {
        save: function (formElementId, script) {
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                if (json.status != 1) {
                    alert(json.msg);
                    if (json.key != undefined) {
                        jQuery(json.key).focus();
                    }
                } else {
                    if (json.data != undefined) {
                        if (script == undefined && json.data.link_edit != undefined) {
                            window.location.href = json.data.link_edit;
                        } else {
                            alert(json.msg);
                            window.location.href = json.data.link_add;
                        }
                    }
                }
            };
            _POST(MNG_SEO.settings.URL_ACTION + 'landing-page/save', formdata, callBack);
            return false;
        },
        InputForm_show: function (id, page) {
            if (id === undefined) {
                id = 0;
            }
            var linkremoteform = MNG_SEO.settings.URL_ACTION + 'landing-page/' + page + '?id=' + id;
            return _SHOW_FORM_REMOTE(linkremoteform);
        },
        InputForm_save: function (formElementId) {
            //Không dùng nữa
            var formdata = jQuery('#' + formElementId).serializeArray();
            var txtLink = jQuery('.txtLink').val();
            var callBack = function (json) {
                if (json.status == 0) {
                    if (json.msg) {
                        jQuery.each(json.msg, function (key, value) {
                            show_notify('Lỗi', 'warning', 'icon-warning2', value);
                        });
                    }
                } else {
                    show_notify(json.msg, 'success', 'icon-checkmark3', '');
                    if (json.id) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        if (!confirm('Ban có muốn tiếp tục thực hiện thao tác?')) {
                            window.location.reload();
                        } else {
                            jQuery('#' + formElementId)[0].reset();
                        }
                    }
                }
            };
            if (txtLink !== undefined && !isUrlValid(txtLink)) {
                if (!confirm('Link của bạn không đúng định dạng bạn vẫn muốn tiếp tục?')) {
                    return false;
                }
            }
            return _POST(MNG_SEO.settings.URL_ACTION + 'landing-page/save', formdata, callBack);
        },
        deleteHandle: function (formElementId) {
            var formdata = jQuery('#' + formElementId).serializeArray();
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'landing-page/deleteHandle', formdata, callBack);
        },
        multiRemove: function (flag) {
            var ids = getCheckBoxVal('landingPageTbl', 'landingPageCbx');
            if (ids == '') {
                alert('Bạn chưa chọn landing page nào!');
                return false;
            }
            var msg = 'Bạn có chắc chắn muốn xóa';
            if (flag) {
                msg += ' hoàn toàn (dữ liệu sẽ không thể khôi phục)';
            }
            if (!confirm(msg + ' ?')) {
                return false;
            }
            var data = [{name: 'ids', value: ids}, {name: 'flag', value: flag}];
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'landing-page/multiRemove', data, callBack);
        },
    },
    MNG_DOMAIN: {
        InputForm_show: function (id, page) {
            if (id === undefined) {
                id = 0;
            }
            var linkremoteform = MNG_SEO.settings.URL_ACTION + 'domain/' + page + '?id=' + id;
            return _SHOW_FORM_REMOTE(linkremoteform);
        },
        InputForm_save: function (formElementId) {
            var formdata = jQuery('#' + formElementId).serializeArray();
            var txtLink = jQuery('.txtLink').val();
            var callBack = function (json) {
                if (json.status == 0) {
                    if (json.msg) {
                        var first = true;
                        jQuery.each(json.msg, function (key, value) {
                            show_notify('Lỗi', 'warning', 'icon-warning2', value);
                            if (first) {
                                validateInput(key, true);
                            } else {
                                validateInput(key, false);
                            }
                        });
                    }
                } else {
                    if (json.msg) {
                        show_notify(json.msg, 'success', 'icon-checkmark3', '');
                        if (json.id) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        } else {
                            if (!confirm('Ban có muốn tiếp tục thực hiện thao tác?')) {
                                window.location.reload();
                            } else {
                                jQuery('#' + formElementId)[0].reset();
                            }
                        }
                    }
                }
            };
            if (txtLink !== undefined && !isUrlValid(txtLink) && txtLink != '') {
                if (!confirm('Link của bạn không đúng định dạng bạn vẫn muốn tiếp tục?')) {
                    return false;
                }
            }
            return _POST(MNG_SEO.settings.URL_ACTION + 'domain/save', formdata, callBack);
        },
        deleteHandle: function (formElementId) {
            var formdata = jQuery('#' + formElementId).serializeArray();
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'domain/deleteHandle', formdata, callBack);
        },
        //deleteConfirm : function (table) {
        //    var ids = getCheckBoxVal(table, 'domainCbx');
        //    if (ids == '') {
        //        alert('Bạn chưa chọn domain nào!');
        //        return false;
        //    }
        //    var linkremoteform = MNG_SEO.settings.URL_ACTION + 'domain/deleteConfirm';
        //    return _SHOW_FORM_REMOTE(linkremoteform);
        //},
        multiRemove: function (flag) {
            var ids = getCheckBoxVal('domainTbl', 'domainCbx');
            if (ids == '') {
                alert('Bạn chưa chọn domain nào!');
                return false;
            }
            var msg = 'Bạn có chắc chắn muốn xóa';
            if (flag) {
                msg += ' hoàn toàn (dữ liệu sẽ không thể khôi phục)';
            }
            if (!confirm(msg + ' ?')) {
                return false;
            }
            var data = [{name: 'ids', value: ids}, {name: 'flag', value: flag}];
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'domain/multiRemove', data, callBack);
        },
    },

    MNG_BACKLINK: {
        InputForm_show: function (id) {
            if (id === undefined) {
                id = 0;
            }
            var linkremoteform = MNG_SEO.settings.URL_ACTION + 'backlink/input?id=' + id;
            return _SHOW_FORM_REMOTE(linkremoteform);
        },
        DeleteForm_show: function (id) {
            if (id !== undefined && id > 0) {
                var linkremoteform = MNG_SEO.settings.URL_ACTION + 'backlink/show?id=' + id;
                return _SHOW_FORM_REMOTE(linkremoteform);
            }
        },
        InputForm_save: function (formElementId) {
            jQuery('#btn-submit').attr('disabled', true);
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                jQuery('#btn-submit').attr('disabled', false);
                if (json.status == 1) {
                    jQuery.each(json.msg, function (k, v) {
                        show_notify('Lỗi', 'warning', 'icon-warning2', v);
                    });
                } else {
                    show_notify('Đã lưu', 'success', 'icon-checkmark3', '');
                    if (confirm(json.msg)) {
                        window.location.reload();
                    }
                    if (json.data.id == 0) {
                        jQuery(formElementId)[0].reset();
                    }
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'backlink/save', formdata, callBack);
        },
        syncToRemote: function (postId) {
            var callBack = function (json) {
                alert(json.msg);
                if (json.status == STATUS_JSON_DONE) {
                    window.location.reload();
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'backlink/post_remote?id=' + postId, [], callBack);
        },
        DeleteForm_save: function (formElementId) {
            jQuery('#btn-submit').attr('disabled', true);
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                jQuery('#btn-submit').attr('disabled', false);
                if (json.status == 1) {
                    jQuery.each(json.msg, function (k, v) {
                        show_notify('Lỗi', 'warning', 'icon-warning2', v);
                    });
                } else {
                    show_notify('Đã xóa', 'success', 'icon-checkmark3', '');
                    window.location.reload();
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'backlink/delete', formdata, callBack);
        },
        multiRemove: function (flag) {
            var ids = getCheckBoxVal('backLinkTbl', 'backLinkCbx');
            if (ids == '') {
                alert('Bạn chưa chọn back link nào!');
                return false;
            }
            var msg = 'Bạn có chắc chắn muốn xóa';
            if (flag) {
                msg += ' hoàn toàn (dữ liệu sẽ không thể khôi phục)';
            }
            if (!confirm(msg + ' ?')) {
                return false;
            }
            var data = [{name: 'ids', value: ids}, {name: 'flag', value: flag}];
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'backlink/multiRemove', data, callBack);
        },
    },
    POST_BACKLINK: {
        save: function (formElementId, script) {
            var $btn = jQuery('.js-post-btn');
            $btn.hide();
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                if (json.status != 1) {
                    $btn.show();
                    alert(json.msg);
                    if (json.key != undefined) {
                        jQuery(json.key).focus();
                    }
                } else {
                    if (json.data != undefined) {
                        if (script == undefined && json.data.link_edit != undefined) {
                            window.location.href = json.data.link_edit;
                        } else {
                            alert(json.msg);
                            window.location.href = json.data.link_add;
                        }
                    }
                }
            };
            _POST(MNG_SEO.settings.URL_ACTION + 'backlink/post-input-save', formdata, callBack);
            return false;
        },
    },

    MNG_KEYWORD: {
        InputForm_show: function (id) {
            if (id === undefined) {
                id = 0;
            }
            var linkremoteform = MNG_SEO.settings.URL_ACTION + 'keyword/input?id=' + id;
            return _SHOW_FORM_REMOTE(linkremoteform);
        },
        DeleteForm_show: function (id) {
            if (id !== undefined && id > 0) {
                var linkremoteform = MNG_SEO.settings.URL_ACTION + 'keyword/show?id=' + id;
                return _SHOW_FORM_REMOTE(linkremoteform);
            }
        },
        InputForm_save: function (formElementId) {
            jQuery('#btn-submit').attr('disabled', true);
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                jQuery('#btn-submit').attr('disabled', false);
                if (json.status == 1) {
                    jQuery.each(json.msg, function (k, v) {
                        show_notify('Lỗi', 'warning', 'icon-warning2', v);
                    });
                } else {
                    show_notify('Đã lưu', 'success', 'icon-checkmark3', '');
                    if (confirm(json.msg)) {
                        window.location.reload();
                    }
                    if (json.data.id == 0) {
                        jQuery(formElementId)[0].reset();
                    }
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'keyword/save', formdata, callBack);
        },
        DeleteForm_save: function (formElementId) {
            jQuery('#btn-submit').attr('disabled', true);
            var formdata = jQuery(formElementId).serializeArray();
            var callBack = function (json) {
                jQuery('#btn-submit').attr('disabled', false);
                if (json.status == 1) {
                    jQuery.each(json.msg, function (k, v) {
                        show_notify('Lỗi', 'warning', 'icon-warning2', v);
                    });
                } else {
                    show_notify('Xóa thành công', 'success', 'icon-checkmark3', '');
                    window.location.reload();
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'keyword/delete', formdata, callBack);
        },
        multiRemove: function (flag) {
            var ids = getCheckBoxVal('keywordTbl', 'keywordCbx');
            if (ids == '') {
                alert('Bạn chưa chọn từ khóa nào!');
                return false;
            }
            var msg = 'Bạn có chắc chắn muốn xóa';
            if (flag) {
                msg += ' hoàn toàn (dữ liệu sẽ không thể khôi phục)';
            }
            if (!confirm(msg + ' ?')) {
                return false;
            }
            var data = [{name: 'ids', value: ids}, {name: 'flag', value: flag}];
            var callBack = function (json) {
                if (json.status == 1) {
                    alert(json.msg);
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            };
            return _POST(MNG_SEO.settings.URL_ACTION + 'keyword/multiRemove', data, callBack);
        },
    },
};

function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(
        url,
    );
}

function getCheckBoxVal(table, cbName) {
    var ids = '';
    var first = true;
    jQuery('#' + table + ' tbody input[name=' + cbName + ']:checked').each(function () {
        if (first) {
            ids += jQuery(this).val();
            first = false;
        } else {
            ids += ',' + jQuery(this).val();
        }
    });
    return ids;
}

function validate_link(obj) {
    var val = obj.val();
    var parent = obj.parent();
    var html = '';
    if (val === undefined || val == '') {
        return false;
    }
    parent.removeClass('has-warning has-success has-error');
    parent.find('.form-control-feedback').remove();
    if (!isUrlValid(val)) {
        show_notify('Lưu ý', 'warning', 'icon-warning2', 'Link của bạn chưa đúng định dạng');
        parent.addClass('has-warning ');
        html = '<div class="form-control-feedback right10"><i class="icon-notification2"></i></div>';
    } else {
        parent.addClass('has-success');
        html = '<div class="form-control-feedback right10"><i class="icon-checkmark-circle"></i></div>';
    }
    obj.after(html);
}

function validateInput(inputName, focus) {
    var input = jQuery('input[name=' + inputName + ']');
    if (input === undefined) {
        return false;
    }
    var parent = input.parent(); //div parent
    parent.removeClass('has-warning has-success has-error').addClass('has-error');
    parent.find('.form-control-feedback').remove();
    input.after('<div class="form-control-feedback right10"><i class="icon-cancel-circle2"></i></div>');
    if (focus) {
        input.focus();
    }
}

function removeError(form) {
    jQuery('#' + form + ' input').change(function () {
        jQuery(this)
            .parent()
            .removeClass('has-warning has-success has-error')
            .find('.form-control-feedback')
            .remove();
    });
}

jQuery.fn.selectText = function () {
    var doc = document;
    var element = this[0];
    console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};

var MNG_CATE = {
    URL_ACTION: PRIVATE_PREFIX + 'cate/',
    save: function (formElementId, addnew) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            if (json.status != 1) {
                alert(json.msg);
                if (json.key != undefined) {
                    jQuery(json.key).focus();
                }
            } else {
                if (json.data != undefined) {
                    if (addnew == undefined && json.data.link != undefined) {
                        window.location.href = json.data.link;
                    } else {
                        alert(json.msg);
                        window.location.href = MNG_CATE.URL_ACTION + 'input';
                    }
                }
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },

    updateStatus: function (cate, status) {
        if (!confirm('Danh mục này sẽ bị xóa, bạn có chắc chắn muốn thực hiện hành động này?')) {
            return false;
        }
        var formdata = [{name: 'cate', value: cate}];
        formdata.push({name: 'status', value: status});
        var callBack = function (json) {
            alert(json.msg);
        };
        _POST(this.URL_ACTION + '_updateStatus', formdata, callBack);
    },
    getCateSelectOptionByObject: function () {
        var objectId = jQuery('#obj-object').val();
        var $cateElement = jQuery('#obj-parent');
        var callBack = function (json) {
            if (json.data.html != undefined) {
                $cateElement.html(json.data.html).select2();
            }
            $cateElement.attr('disabled', false);
        };
        _POST(this.URL_ACTION + '_getCateSelectOptionByObject?object=' + objectId, [], callBack);
        return false;
    },
    getCateCheckBoxByObject: function (object) {
        if (typeof object === 'undefined') {
            object = '#obj-type';
        }
        var objectId = jQuery(object).val();
        var $cateElement = jQuery('#inputCateCheckBoxRegion');
        var callBack = function (json) {
            if (json.data.html != undefined) {
                $cateElement.html(json.data.html);
                jQuery('input:checkbox, input:radio').uniform();
            }
        };
        _POST(this.URL_ACTION + '_getCateCheckBoxByObject?object=' + objectId, [], callBack);
        return false;
    },
};

var MNG_MENU = {
    URL_ACTION: PRIVATE_PREFIX + 'menu/',
    saveMenu: function (formElementId) {
        var $form = jQuery(formElementId);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            alert(json.msg);
            if(json.status == 1){
                location.reload();
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },
    removeMenuItem:function (item_id) {
        if (confirm('Bạn có chắc chắn muốn thực hiện thao tác này?')) {
            $('[data-id=' + item_id + ']').remove();
            menuUpdateOutput($('#NESTABLE-MENU').data('output', $('#menu-setting-output')));
        }
    }
};

var MNG_MEDIA = {
    URL_ACTION: PRIVATE_PREFIX + 'media/',
    BUTTON_ACTION_NAME: '',
    SELECTED: [],
    TARGET_OBJECT: false,
    SELECTED_MULTI: false,
    setting: {
        //setting for form upload
        MULTI_SELECT: false,
        BUTTON_ACTION: false,
        CURENT_IMAGE: '', //đường dẫn ảnh hiện tại: khi mở form và edit ảnh      cũng có thể là cả object
    },
    /***
     * @note: kịch bản mở form upload
     * -- trong form có các thành phần sau: nút upload hình ảnh
     * -- Danh sách hình ảnh đã được upload trước đó có phân trang
     * @param action_name = Tên action khi click vào nút chọn ảnh
     * @param curent = Link của ảnh hiện tại: support cả link tương đối và link tuyệt đối
     */
    openUploadForm: function (action_name, curent, object, object_type) {
        this.BUTTON_ACTION_NAME = action_name;
        this.SELECTED = [];
        this.TARGET_OBJECT = object;
        var linkremoteform = this.URL_ACTION + '_showFormUpload?action_name=' + action_name + '&curent=' + curent;
        return _SHOW_FORM_REMOTE(linkremoteform);
    },
    openUploadFormWithConfig: function (setting) {
        console.log(setting);
        this.setting = setting;
        this.SELECTED = [];
        var linkremoteform =
            this.URL_ACTION +
            '_showFormUpload?action_name=' +
            this.setting.BUTTON_ACTION +
            '&curent=' +
            this.setting.CURENT_IMAGE;
        return _SHOW_FORM_REMOTE(linkremoteform);
    },
    save: function (formElementId, script) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            if (json.status != 1) {
                alert(json.msg);
                if (json.key != undefined) {
                    jQuery(json.key).focus();
                }
            } else {
                if (json.data != undefined) {
                    if (addnew == undefined && json.data.link != undefined) {
                        window.location.href = json.data.link;
                    } else {
                        alert(json.msg);
                        window.location.href = MNG_CATE.URL_ACTION + 'input';
                    }
                }
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },
    uploadInit: function (options) {
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',

            browse_button: 'pickfiles', // you can pass in id...
            //container: document.getElementById('upload-container-fake'),
            url: this.URL_ACTION + '_doUpload',
            filters: {
                max_file_size: '20mb',
                mime_types: [{title: 'Chọn file bất kỳ', extensions: '*'}],
            },
            multipart_params: {},
            init: {
                PostInit: function () {
                },
                FilesAdded: function (up, files) {
                    jQuery(options.loading_element).show();
                    uploader.start();
                },
                FileUploaded: function (up, file, response) {
                    uploader.removeFile(file);
                    var response = JSON.parse(response.response);
                    jQuery(options.loading_element).hide();
                    jQuery(options.input_element).val(response.data.relative_link);
                    jQuery(options.link_element)
                        .attr('href', response.data.full_size_link)
                        .show();
                },
                UploadComplete: function (up, files) {
                },
            },
        });
        uploader.init();
        uploader.bind('FilesAdded', function (up, files) {
        });
    },
};

var MNG_BOOK = {
    URL_ACTION: PRIVATE_PREFIX + 'book/',
    /***
     * @note: Thêm 1 tag vào trong inline hidden của html để post lên sơ vơ
     * @param sourceElement: id của input tag : nơi nhập từ khóa cách nhau bởi dấu phẩy
     * @param targetElement: id của html hiển thị tag nhập  và các thẻ hidden khác
     */
    addTag: function (sourceElement, targetElement) {
        var $targetElement = jQuery(targetElement);
        var $sourceElement = jQuery(sourceElement);
        var sourceContent = $sourceElement.val().trim();
        if (sourceContent == '') {
            return false;
        }
        sourceContent = sourceContent.split(',');
        for (var i in sourceContent) {
            if (sourceContent[i].trim() != '') {
                var id = 'TAG-' + _convertToAlias(sourceContent[i].trim());
                if (jQuery('#' + id + '').length == 0) {
                    var tag =
                        '<li id="' +
                        id +
                        '"><input type="hidden" name="TAG[]" value="' +
                        sourceContent[i].trim() +
                        '"/>';
                    tag +=
                        '<i onclick="jQuery(\'#' +
                        id +
                        '\').remove();" class="icon-diff-removed"></i> ' +
                        sourceContent[i].trim() +
                        '</li>';
                    $targetElement.prepend(tag);
                }
            }
        }
        $sourceElement.val('');
        return false;
    },
    inputTagPress: function (event) {
        if (event.which == 13) {
            MNG_POST.addTag('#post-input-tag', '#post-list-tag');
            return false;
        }
    },
    save: function (formElementId, script) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            if (json.status != 1) {
                alert(json.msg);
                if (json.key != undefined) {
                    jQuery(json.key).focus();
                }
            } else {
                if (json.data != undefined) {
                    if (script == undefined && json.data.link_edit != undefined) {
                        window.location.href = json.data.link_edit;
                    } else {
                        alert(json.msg);
                        window.location.href = MNG_POST.URL_ACTION + 'input';
                    }
                }
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },
    deleteItem: function (link, id) {
        if (!confirm('Are your sure delete this item?')) {
            return false;
        }
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            alert(json.msg);
            if (json.status == 1) {
                jQuery('#itemRow_' + id).remove();
            } else {
            }
        };
        _POST(link, [], callBack);
        return false;
    },
};

var MNG_POST = {
    URL_ACTION: PRIVATE_PREFIX + 'post/',
    /***
     * @note: Thêm 1 tag vào trong inline hidden của html để post lên sơ vơ
     * @param sourceElement: id của input tag : nơi nhập từ khóa cách nhau bởi dấu phẩy
     * @param targetElement: id của html hiển thị tag nhập  và các thẻ hidden khác
     */
    addTag: function (sourceElement, targetElement) {
        var $targetElement = jQuery(targetElement);
        var $sourceElement = jQuery(sourceElement);
        var sourceContent = $sourceElement.val().trim();
        if (sourceContent == '') {
            return false;
        }
        sourceContent = sourceContent.split(';');
        for (var i in sourceContent) {
            if (sourceContent[i].trim() != '') {
                var id = 'TAG-' + _convertToAlias(sourceContent[i].trim());
                if (jQuery('#' + id + '').length == 0) {
                    var tag =
                        '<li id="' +
                        id +
                        '"><input type="hidden" name="CATE[]" value="' +
                        sourceContent[i].trim() +
                        '"/>';
                    tag +=
                        '<i onclick="jQuery(\'#' +
                        id +
                        '\').remove();" class="icon-diff-removed"></i> ' +
                        sourceContent[i].trim() +
                        '</li>';
                    $targetElement.prepend(tag);
                }
            }
        }
        $sourceElement.val('');
        return false;
    },
    inputTagPress: function (event) {
        if (event.which == 13) {
            MNG_POST.addTag('#post-input-tag', '#post-list-tag');
            return false;
        }
    },
    save: function (formElementId, script) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            if (json.status != 1) {
                alert(json.msg);
                if (json.key != undefined) {
                    jQuery(json.key).focus();
                }
            } else {
                if (json.data != undefined) {
                    if (script == undefined && json.data.link_edit != undefined) {
                        window.location.href = json.data.link_edit;
                    } else {
                        alert(json.msg);
                        window.location.href = MNG_POST.URL_ACTION + 'input';
                    }
                }
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },
    running: 0,
    stop_queue: 0,
    update: function (link, formElementId, add_more, options) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formData = $form.serializeArray();
        if (MNG_POST.running === 1) {
            return false;
        }
        MNG_POST.running = 1;
        var callBack = function (json) {
            MNG_POST.running = 0;
            if (json.msg !== '') {
                alert(json.msg);
            }
            if (typeof add_more !== 'undefined') {
                window.location.href = link;
            } else {
                try {
                    if (typeof json.data.link !== 'undefined') {
                        window.location.href = json.data.link;
                    }
                } catch (e) {
                }
            }
            /**
             * reset form
             */
            try {
                if (typeof json.data.reset !== 'undefined') {
                    $form.reset();
                }
            } catch (e) {
            }
            try {
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload();
                }
                if (typeof json.data.redirect !== 'undefined') {
                    window.location.href = json.data.redirect;
                }
            } catch (e) {
            }
        };
        //Bổ sung thêm một số options cho tool
        if (typeof options === 'object' && !Array.isArray(options) && options !== null) {
            if (typeof options.redirect_link === 'string') {
                callBack = function (json) {
                    if (typeof json !== 'undefined') {
                        if (json.status === 1) {
                            window.location.href = options.redirect_link;
                        } else {
                            alert(json.msg);
                        }
                    } else {
                        alert('Lỗi kết nối');
                    }
                };
            } else if (typeof options.callback === 'function') {
                callBack = options.callback;
            }
        }

        _POST(link, formData, callBack);
        return false;
    },
    /**
     *
     * Dùng cho case cần xử lý nhiều dữ liệu và phải chia trang để xử lý
     * @param link
     * @param formElementId
     * @param process_element_loading
     * @param formdata
     * @returns {*}
     */
    updateQueue(link, formElementId, process_element_loading, formdata) {
        if (typeof formdata === 'undefined') {
            var $form = jQuery(formElementId);
            formdata = $form.serializeArray();
        }
        var callBack = function (json) {
            try {
                if (typeof json.data.text !== 'undefined') {
                    jQuery(process_element_loading).html(json.data.text);
                }
            } catch (e) {
            }
            try {
                if (typeof json.data.alert !== 'undefined') {
                    alert(json.data.alert);
                    jQuery('#QueueDone').show();
                    jQuery('#loaddingProcess,#importBtn').hide();
                }
            } catch (e) {
            }
            try {
                if (typeof json.msg !== 'undefined' && json.status === 0) {
                    alert(json.msg);
                    $(document)
                        .find('[data-dismiss="modal"]')
                        .trigger('click');
                }
            } catch (e) {
            }
            try {
                if (typeof json.data.next !== 'undefined') {
                    MNG_POST.updateQueue(json.data.next, formElementId, process_element_loading, formdata);
                }
            } catch (e) {
            }
        };
        _POST(link, formdata, callBack);
        return false;
    },
    deleteItem: function (link, id, o) {
        if (
            !confirm('Đối tượng bị khóa sẽ không thể khôi phục lại được!\nBạn có chắc chắn muốn khóa đối tượng này?')
        ) {
            return false;
        }
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            alert(json.msg);

            if (json.status == 1) {
                if (id) {
                    $('#itemRow_' + id).remove();

                } else {

                    $(o).parents('.item-row').remove();

                }
            }
            try {
                if(typeof json.data.link !=='undefined'){
                    window.location.href = json.data.link ;
                }
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload();
                }
            } catch (e) {
            }
        };
        _POST(link, [], callBack);
        return false;
    },
    disableAcc: function (link, id, o) {
        let a = confirm('Bạn có chắc chắn muốn khóa tài khoản này?');
        if (!a) return false;
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            alert(json.msg);

            if (json.status == 1) {
                if (id) {
                    $('#itemRow_' + id).remove();

                } else {

                    $(o).parents('.item-row').remove();

                }
            }
            try {
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload();
                }
            } catch (e) {
            }
        };
        _POST(link, [], callBack);
        return false;
    },
    enableAcc: function (link, id, o) {
        let a = confirm('Bạn có muốn mở khóa cho tài khoản này không?');
        if (!a) return false;
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            alert(json.msg);

            if (json.status == 1) {
                if (id) {
                    $('#itemRow_' + id).remove();

                } else {

                    $(o).parents('.item-row').remove();

                }
            }
            try {
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload();
                }
            } catch (e) {
            }
        };
        _POST(link, [], callBack);
        return false;
    },
    sendNotification: function (link, formElementId) {
        var $form = jQuery(formElementId);
        //$form.find("button[type='submit']").attr('disabled', true);
        var formdata = $form.serializeJSON();
        console.log(formdata);
        var callBack = function (json) {
            alert(json.msg);
        };
        _POST_JSON_BODY(link, formdata, callBack);
        return false;
    },
    _GET_PAGE_AJAX: function (link, formElementId, page = 1, container, paging) {
        var $form = jQuery(formElementId);
        var formdata = $form.serializeArray();

        if (MNG_POST.running === 1) {
            return false;
        }
        MNG_POST.running = 1;
        var callBack = function (html) {
            MNG_POST.running = 0;

            var d = $(html)
                .find(container)
                .html();
            $(container).html(d);
            $('.styled').uniform({
                radioClass: 'choice'
            });
            $("#checkAll").click(function () {
                $('input:checkbox.js-check-box-list').not(this).prop('checked', this.checked).uniform({});
                let countChecked = $('input:checkbox.js-check-box-list:checked').length;
                jQuery('.count_customer_checked').html(countChecked + ' khách hàng')
            });
            $(paging + ' .page-item').each(function () {
                $(this).click(function () {
                    let a = $(this).find('a');
                    if (a.length) {
                        h = a.attr('href');
                        if (h) {
                            let m = h.match(/page=(\d+)/g);
                            link = link.replace(/&page=(\d+)/g, '');
                            if (m[0]) {
                                let m1 = m[0].match(/\d+/g);
                                if (m1[0]) {
                                    var d = new Date();
                                    var t = d.getTime();
                                    MNG_POST._GET_PAGE_AJAX(
                                        link + '&page=' + m1[0],
                                        formElementId,
                                        m1[0],
                                        container,
                                        paging,
                                    );
                                }
                            }
                        }
                    }
                    return false;
                });
            });

            $('html, body').animate(
                {
                    scrollTop: $(container).offset().top,
                },
                200,
            );
        };

        _POST(link, formdata, callBack, undefined, 'html');
    },
    processQueue(link, data, _callback, error_callbkack) {
        MNG_POST.stop_queue = 0;
        var _token = jQuery('meta[name=_token]').attr('content');
        if (_token) {
            var _data = {name: '_token', value: _token};
            data.push(_data);

        }

        $.ajax({
            method: 'POST',
            url: link,
            dataType: "json",
            data: data,
            success: function (response) {
                console.log(response);
                try {
                    eval(_callback(response));
                } catch (e) {
                    if (typeof error_callbkack !== "function") {
                        error_callbkack = function () {

                        }
                    }
                    eval(error_callbkack(response));
                    console.log(e)
                }
                try {
                    if (MNG_POST.stop_queue !== 1 && typeof response.next_page_url !== 'undefined' && response.next_page_url) {
                        MNG_POST.processQueue(response.next_page_url, data, _callback);
                    } else {

                    }
                } catch (e) {
                }
            },
            error: function (error) {
                console.log(error)
            }
        });
        return true;
    },

};

var MNG_MEMBER = {
    URL_ACTION: PRIVATE_PREFIX + 'member/',

    save: function (formElementId, script) {
        var $form = jQuery(formElementId);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
            // $form.find("button[type='submit']").attr('disabled', true);
            alert(json.msg);
            if (json.status != 1) {
                if (json.key != undefined) {
                    jQuery(json.key).focus();
                }
            } else {
                if (json.data != undefined) {
                    if (script == undefined && json.data.link_edit != undefined) {
                        window.location.href = json.data.link_edit;
                    } else {
                        window.location.href = MNG_MEMBER.URL_ACTION + 'input';
                    }
                }
            }
        };
        _POST(this.URL_ACTION + '_save', formdata, callBack);
        return false;
    },
    delete: function (obj, token, from) {
        if (!confirm('Bạn có chắc chắn muốn xóa thành viên: "' + obj.account + '"')) {
            return false;
        }
        var callBack = function (json) {
            alert(json.msg);
            if (json.status == 1) {
                jQuery('#row_' + obj.id).remove();
            }

            if (from == 'edit') {
                window.location.href = PRIVATE_PREFIX + 'member/input';
            }
        };
        var formdata = [];
        formdata.push({name: 'obj', value: JSON.stringify(obj)});
        formdata.push({name: 'token', value: token});
        _POST(this.URL_ACTION + '_delete', formdata, callBack);
    },
};

function _getJsonFromUrl() {
    var query = location.search.substr(1);
    var result = {};
    query.split('&').forEach(function (part) {
        var item = part.split('=');
        if (typeof item[1] !== 'undefined') {
            result[item[0]] = decodeURIComponent(item[1]);
        }
    });
    return result;
}

var IO_COOKIE = {
    getCookie(cname) {
        var name = cname + '=';
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return null;
    },
    setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        var expires = 'expires=' + d.toUTCString();
        document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
    },
    toggleCookie(cname, exdays) {
        var cookie = IO_COOKIE.getCookie(cname);
        if (cookie !== null) {
            IO_COOKIE.setCookie(cname, false, -999999);
        } else {
            IO_COOKIE.setCookie(cname, true, exdays);
        }
    },
};

(function ($) {
    $.fn.simpleMoneyFormat = function () {
        this.each(function (index, el) {
            var elType = null; // input or other
            var value = null;
            // get value
            if ($(el).is('input') || $(el).is('textarea')) {
                value = $(el).val().replace(/\./g, '');
                elType = 'input';
            } else {
                value = $(el).text().replace(/\./g, '');
                elType = 'other';
            }
            // if value changes
            $(el).on('paste keyup', function () {
                value = $(el).val().replace(/\./g, '');
                formatElement(el, elType, value); // format element
            });
            formatElement(el, elType, value); // format element
        });

        function formatElement(el, elType, value) {
            var result = '';
            var valueArray = value.split('');
            var resultArray = [];
            var counter = 0;
            var temp = '';
            for (var i = valueArray.length - 1; i >= 0; i--) {
                temp += valueArray[i];
                counter++
                if (counter == 3) {
                    resultArray.push(temp);
                    counter = 0;
                    temp = '';
                }
            }
            ;
            if (counter > 0) {
                resultArray.push(temp);
            }
            for (var i = resultArray.length - 1; i >= 0; i--) {
                var resTemp = resultArray[i].split('');
                for (var j = resTemp.length - 1; j >= 0; j--) {
                    result += resTemp[j];
                }
                ;
                if (i > 0) {
                    result += '.'
                }
            }
            ;
            if (elType == 'input') {
                $(el).val(result);
            } else {
                $(el).empty().text(result);
            }
        }
    };
}(jQuery));

function escapeHtml(text) {
    'use strict';
    return text.replace(/[\"&<>]/g, function (a) {
        return {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}[a];
    });
}

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    )
}

function convertDate(inputFormat) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }

    var d = new Date(inputFormat);
    return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/');
}

function convertDateTime(inputFormat, makeFormat = false) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }

    var d = new Date(inputFormat);
    if(makeFormat === true){
        return `${[pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/')} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    }else{
        return `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())} ${[pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/')}`;
    }

}

var HelperJS = {
    getId : function (value){
      if(typeof value ==='string'){
          return value
      }else if(typeof value ==='object' && value.$oid){
          return value.$oid
      }
      return ''
    },
    getNumber: function (value) {
        value = value.toString();
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(value))
            value = value.replace(pattern, "$1.$2");
        return value;
    },
    getStrValue: function (value) {

        if (typeof value === 'string') {
            return value.trim();
        }

        if (typeof value === 'object' && value !== null && typeof value.label === 'string') {
            return value.label.trim();
        }
        if (typeof value === 'object' && value !== null && typeof value.value === 'string') {
            return value.value.trim();
        }
        if (typeof value !== 'undefined' && Array.isArray(value) && value[0]) {
            return HelperJS.getStrValue(value[0]);
        }
        return "";
    },
    _formatTime: function(date){
        var aaaa = date.getFullYear();
        var gg = date.getDate();
        var mm = (date.getMonth() + 1);

        if (gg < 10)
            gg = "0" + gg;

        if (mm < 10)
            mm = "0" + mm;

        var cur_day = gg + "/" + mm + "/" + aaaa;

            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();

            if (hours < 10)
                hours = "0" + hours;

            if (minutes < 10)
                minutes = "0" + minutes;

            if (seconds < 10)
                seconds = "0" + seconds;

            return hours + ":" + minutes + ":" + seconds + " " + cur_day;
            //H:i:s d/m/Y

    },
    _formatTimeLimit: function(date){
        var aaaa = date.getFullYear().toString().substr(-2);
        var gg = date.getDate();
        var mm = (date.getMonth() + 1);

        if (gg < 10)
            gg = "0" + gg;

        if (mm < 10)
            mm = "0" + mm;

        var cur_day = gg + "/" + mm + "/" + aaaa;

        var hours = date.getHours();
        var minutes = date.getMinutes();

        if (hours < 10)
            hours = "0" + hours;

        if (minutes < 10)
            minutes = "0" + minutes;

        return hours + ":" + minutes + " " + cur_day;
        //H:i d/m/Y

    },
    _formatDate: function(date){
        var aaaa = date.getFullYear();
        var gg = date.getDate();
        var mm = (date.getMonth() + 1);
        if (gg < 10)
            gg = "0" + gg;
        if (mm < 10)
            mm = "0" + mm;
        return gg + "/" + mm + "/" + aaaa;

    },
    _formatDateNoYear: function(date){
        var gg = date.getDate();
        var mm = (date.getMonth() + 1);
        if (gg < 10)
            gg = "0" + gg;
        if (mm < 10)
            mm = "0" + mm;
        return gg + "/" + mm;
    },

    getDateStr: function (value, params = {showTime: true}, param = {showTime: false}) {
        if (typeof value === 'string' || typeof value === 'number') {
            if(value == 0){
                return false;
            }
            let _value = new Date(value);
            if (String(_value) === 'Invalid Date') {
                return false;
            } else {
                if(params.showTime && param.showTime) return HelperJS._formatTimeLimit(_value);
                if (params.showTime) return HelperJS._formatTime(_value);
                return HelperJS._formatDate(_value);
            }

        } else {
            value = typeof value === 'object' && value !== null ? value : {};
            let timestamp = value.$date
                ? value.$date.$numberLong
                    ? Number(value.$date.$numberLong)
                    : false
                : false;
            if (timestamp && timestamp != 0) {
                let _timestamp = new Date(timestamp);
                if(params.showTime && param.showTime) return HelperJS._formatTimeLimit(_timestamp);
                if (params.showTime) return HelperJS._formatTime(_timestamp);
                return HelperJS._formatDate(_timestamp);

            } else {
                return false;
            }

        }
    },
    getTimestamp: function (value) {
        if (typeof value === 'string' || typeof value === 'number') {
            let _value = new Date(value);
            if (String(_value) === 'Invalid Date') {
                return false;
            } else {
                return Number(_value);
            }

        } else {

            value = typeof value === 'object' && value !== null ? value : {};
            let timestamp = value.$date
                ? value.$date.$numberLong
                    ? Number(value.$date.$numberLong)
                    : false
                : false;
            if (timestamp) {
                return timestamp;
            } else {
                return false;
            }

        }

    },
    getArray: function (data) {
        if (Array.isArray(data)) {
            return data
        } else {
            return [];
        }
    },
    showDetailCallLog: function (row) {
        let link = public_link(
            `customer/edit-phone-call-log?project_id=${row.project_id}&id=${row._id}`,
        );
        _SHOW_FORM_REMOTE(link);
    },
    isURL(str) {
        return isUrlValid(str)
    }
};

function get_config_from_storage(config_name, project_id, table) {
    if (!config_name) {
        config_name = 'config';
    }
    let key = config_name + '_' + table + '_' + project_id;
    let config = localStorage.getItem(key);
    if (config) {

        config = JSON.parse(config);
    }
    if (!Array.isArray(config)) {
        config = [];
    }
    return config;
}

function set_config_to_storage(config_name, project_id, table, config) {
    if (!config_name) {
        config_name = 'config';
    }
    let key = config_name + '_' + table + '_' + project_id;
    localStorage.setItem(key, JSON.stringify(config));

}

var postDataCustomer = {obj: {},  selectedField: {target: "lists", label: "Danh sách", key: "lists", type: "array-object", type_view: "list", pos: 0}, table:'data_customer', choose:'', updateType: "append" , listId:''};
var queueDataId = [];
function update_with_list_id_assign_list(listId, callback) {
    if (!isLoading) {
        current = 0;
    }

    postDataCustomer.listId = listId;
    let formdata = m.parseQueryString($('#obj-change-value').serialize());

    let url = "/base-table/update_many_save?project_id="+project_id;
    m.request({
        url,
        withCredentials: true,
        data: postDataCustomer,
        method: 'post'
    }).then(response => {
        current = current + listId.length;
        if (response.status === 1) {
            typeof callback === 'function' && callback(response)
        } else {
            alert(response.msg)
        }

    }).catch(err => {
        update_with_list_id_assign_list(listId, callback)
    });

}

update_many_record_list_get_data = (url = "") => {
    isLoading = true;
    m.redraw();
    m.request({
        url,
        method: 'get'
    }).then(response => {
        if (Array.isArray(response.data)) {
            let listId = response.data.map(({_id}) => customer_id);
            queueDataId.push(listId);
            queueState = `Đang lấy dữ liệu bản ghi (${queueDataId.reduce((acc, cur) => {
                return acc + cur.length
            }, 0)}) `;

            if (typeof response.next_page_url === 'string' && response.next_page_url.length > 0) {
                update_many_record_list_get_data(response.next_page_url);
            } else {
                _update_many_record_assign_list()
            }
        }

    }).catch(err => {
        update_many_record_list_get_data(url)
    })


};

function copyText(text){
    /* Get the text field */
    let tempId = "js-copy" + Date.now();
    $('body').append(`<textarea type="text" style="width: 0;height: 0;"  id="${tempId}">`);
    $('#' + tempId).val(text);
    var copyText = document.getElementById(tempId);

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");
    /* Alert the copied text */
    alert("Copied the text: " + copyText.value);
    $('#'+tempId).remove()

}

var AlertBox = {
    success(msg) {
        Swal.fire(
            "Thành Công",
            msg,
            "success"
        );
    },
    error(msg) {
        Swal.fire(
            "Thất bại",
            msg,
            "error"
        );
    },
};


var Toastr = {
    success(msg) {
        toastr.clear();
        NioApp.Toast(msg, 'success',  {position: 'top-left'});
    },
    error(msg) {
        toastr.clear();
        NioApp.Toast(msg, 'error',  {position: 'top-left'});
    },
};

var save_sale_interaction = (text,id,collection_key)=>{
    if(typeof m !=="undefined"){
        m.request({url: public_link('/base-table/save_log_sale_interaction'),method:"get", data:{text,id,collection_key}})
    }
}
