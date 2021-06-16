function strip_tags(item)
{
    if(item){
        return item.replace(/<\/?[^>]+(>|$)/g, "");
    }
    return item;
}
function getCurrentProjectID() {
    return typeof CRM_CURRENT_PROJECT_ID=='undefined'? m.parseQueryString(location.search).project_id:CRM_CURRENT_PROJECT_ID;
}
function getCurrentTable() {
    return typeof CRM_CURRENT_TABLE=='undefined'? m.parseQueryString(location.search).table:CRM_CURRENT_TABLE;
}

function getConvertedTable() {
    return m.parseQueryString(location.search).converted;
}
function getFromTable() {
    return m.parseQueryString(location.search).from;
}

function public_link(link) {

    return  BASE_URL+ (link[0]!=='/' ? '/':'' ) +
        link+ ''+(
            link.indexOf('project_id=')===-1?
                ((link.indexOf('?')===-1?'?':'&')+'project_id='+getCurrentProjectID())
                :''
        );
}

function web_push_link(link) {

    return  BASE_URL + (link[0]!=='/' ? '/':'' ) +
        link+ ''+(
            link.indexOf('project_id=')===-1?
                ((link.indexOf('?')===-1?'?':'&')+'project_id='+getCurrentProjectID())
                :''
        );
}

var MNG_PROJECT = {
    showSwitch() {
        return _SHOW_FORM_REMOTE(public_link('project/show-switch?popup=true'));
    }
};

function _UPLOAD(link, file_element_selector, callback_success, callback_err) {
    var $fileElementSelector = jQuery(file_element_selector);
    var data = new FormData();
    data.append('file', $fileElementSelector.prop('files')[0]);
    data.append('_token', jQuery('meta[name=_token]').attr("content"));

    $.ajax({
        type: 'POST',
        processData: false, // important
        contentType: false, // important
        data: data,
        url: link,
        dataType: 'json',
        success: function (jsonData) {
            return eval(callback_success(jsonData))

        },
        error: function (string) {
            return eval(callback_err(string))
        }
    });

}

var ZAMBA_CRM = {
    getListCity(callBack) {
        _POST(public_link('public-api/location/get-all-city'), [], callBack);
    }, getLocationSub(parent_code, callBack) {
        _POST(public_link('public-api/location/get-sub-location?parent_key=' + parent_code), [], callBack);
    },
    /**
     * Sử dụng điều hướng phần tỉnh thành quận huyện
     * tham khảo phần input khách hàng
     * @param parent_code
     * @param select_element
     * @param string_null
     * @returns {*}
     * @private
     */
    _changeCity(parent_code, select_element, string_null, _code) {
        jQuery(select_element).attr('readonly', true);
        if (select_element == '#location-district') {
            jQuery('#location-town').html('<option value="">Chọn xã phường</option>').select2();
        }
        return ZAMBA_CRM.getLocationSub(parent_code, function (json) {
            let html = '<option value="">' + string_null + '</option>';
            if (typeof json.data !== 'undefined') {
                for (let i in json.data) {
                    let location = json.data[i];
                    let selected = '';
                    if (_code && _code == location.code) {
                        selected = ' selected';
                    }
                    if (typeof location.name !== 'undefined') {
                        html += '<option value="' + location.code + '" ' + selected + '>' + location.name + '</option>';
                    }
                }
            }
                jQuery(select_element).attr('readonly', false).html(html).select2();
        });
    },
    _changeCity_td(dom, select_element, string_null, _code) {
        let parent_code = $(dom).val();
        let parent = $(dom).parents('._ELEMENT')
        let select = parent.find(`[data-id=${select_element}]`);
        jQuery(select).attr('readonly', true);
        window.aaaa = $(parent);
        jQuery(select).html('<option value="">Chọn xã phường</option>').select2();
        return ZAMBA_CRM.getLocationSub(parent_code, function (json) {
            let html = '<option value="">' + string_null + '</option>';
            if (typeof json.data !== 'undefined') {
                for (let i in json.data) {
                    let location = json.data[i];
                    let selected = '';
                    if (_code && _code == location.code) {
                        selected = ' selected';
                    }
                    if (typeof location.name !== 'undefined') {
                        html += '<option value="' + location.code + '" ' + selected + '>' + location.name + '</option>';
                    }
                }
            }
            jQuery(select).attr('readonly', false).html(html).select2();
        });
    }
};

function _getFileIcon(file) {
    try {
        if (file == 'application/pdf') {
            file = 'application/.pdf'
        }
        let ext = file.split('.').pop();
        let icon = '';
        switch (ext) {
            case 'document':
            case 'docx':
            case 'doc': {
                icon = 'icon-file-word text-primary';
                break;
            }
            case 'txt': {
                icon = 'icon-file-text text-success-800';
                break;
            }
            case 'png':
            case 'jpg':
            case 'jpeg': {
                icon = 'icon-file-picture2 text-success-800';
                break;
            }
            case 'mp3':
            case 'mp4': {
                icon = 'icon-file-music text-success-800';
                break;
            }
            case 'xls':
            case 'xlsb':
            case 'spreadsheet':
            case 'sheet':
            case 'csv':
            case 'xlsx': {
                icon = 'icon-file-excel text-success-800';
                break;
            }
            case 'pdf': {
                icon = 'icon-file-pdf text-warning';
                break;
            }
            default: {
                icon = 'icon-files-empty';
                break;
            }
        }
        return "<i class='" + icon + "'></i>";
    } catch (e) {
        return "<i class='icon-files-empty'></i>";

    }
}

var MNG_UPDATE_INFO = {
    updateProfile: function (link, formElementId, add_more) {
        var $form = jQuery(formElementId);
        var formdata = $form.serializeArray();
        var callBack = function (json) {
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
                    $form.reset()
                }
            } catch (e) {

            }
            try {
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload()
                }
            } catch (e) {

            }
        };
        _POST(link, formdata, callBack);
        return false;
    },
}

function CHANGE_FIELD(o) {
    $(o).parents('td').find('.select2-lien-ket').addClass('hidden');

    if ($(o).val() == 'lien-ket-tai-khoan') {
        $(o).parents('td').find('.select2-lien-ket').removeClass('hidden');
    }
    $('.js-select-all .js-select-customer option').removeAttr('disabled');
    let _Choosen = [];
    $('.js-select-all .js-select-customer option:selected').each(function (e) {
        if ($(this).val() && $(this).val() != 0) {
            _Choosen.push($(this).val());
        }
    });
    $('.js-select-all .js-select-customer').each(function (e) {
        let $t = $(this);
        let $val = $t.find(':selected').val();
        for (let i = 0; i < _Choosen.length; i++) {
            if ($val === _Choosen[i]) {
                $t.find('option[value="' + _Choosen[i] + '"]').removeAttr('disabled');
            } else {
                $t.find('option[value="' + _Choosen[i] + '"]').attr('disabled', 'disabled');
            }
        }
    });
    $('.js-select-all .js-select-customer option[data-multiple=1]').removeAttr('disabled');
    $('.js-select-all .js-select-customer option[data-disabled=1]').attr('disabled','disabled');

    $.ajax({
        url: window.location.href + '&field_curent=' + $(o).val() + '&row=' + $(o).closest("tr").index(),
        type: 'get',
        dataType: 'json',
        success: function (json) {

        }
    })
}

function validateFields(t) {
    event.preventDefault();
    let _Choosen = [];
    $('.js-select-all .js-select-customer').each(function (e) {
        if ($(this).val() && $(this).val() != 0) {
            _Choosen.push($(this).val());
        }
    });
    if (_Choosen.length == 0 || _Choosen.length != $('.js-select-all .js-select-customer').length) {
        alert('Chưa chọn trường dữ liệu tương ứng!');
    } else {
        location.href = $(t).attr('href') + '&fields=' + _Choosen.join('+');
    }
}

function _validateSubmit(emails) {
    let _Choosen = [];
    let _ChoosenIndex = [];
    $('.js-select-all .js-select-customer[name="fields_in_crm[]"]').each(function (e) {
        if ($(this).val() && $(this).val() != 0) {
            _Choosen.push($(this).val());
            _ChoosenIndex.push(e);
        }
    });
    if(_Choosen.indexOf('emails') === -1 && emails){
        //check bắt buộc chọn trường email - nếu có
        event.preventDefault();
        alert('Bạn phải chọn trường email - Thông tin cơ bản!');
        return false;
    }else if (_Choosen.length == 0) {
        //bắt buộc gán chọn 1 trường dữ liệu
        event.preventDefault();
        alert('Chưa chọn trường dữ liệu tương ứng!');
        return false;
    } else if ($('.customer_check_list:checked').length == 0) {
        //bắt buộc check trùng 1 trường dữ liệu
        event.preventDefault();
        alert('Cần chọn trường check trùng');
        return false;
    }
    //check xem điểm check trùng đã gán dữ liệu vào chưa
    let $check_duplicate = false;
    $('.customer_check_list').each(function (e) {
        if($(this).is(':checked') && _ChoosenIndex.indexOf(e) === -1){
            $check_duplicate = true;
        }
    });
    // if($check_duplicate){
    //     event.preventDefault();
    //     alert('Chưa chọn trường dữ liệu tương ứng với vị trí check trùng!');
    //     return false;
    // };
    //show loading and move step 3 - review and import
    $('.js_import_loading > i').remove();
    $('.js_import_loading').append(' <i class="icon-spinner3 spinner"></i>');
    return true;
}

zIndex = 10;
$(function () {
    $('#note-new').click(function () {
        $('body')
            .append('\
            <div class="sticky-note-pre ui-widget-content">\
                <div class="handle">&nbsp;<div class="close">&times;</div></div>\
                <div contenteditable class="contents">awesome</div>\
            </div>\
         ')
            .find('.sticky-note-pre')
            //.position where we want it
            .end()
        //.do something else to $('#notes')
        ;
        $('.sticky-note-pre')
            .draggable({
                handle: '.handle'
            })
            .resizable({
                resize: function () {
                    var n = $(this);
                    n.find('.contents').css({
                        width: n.width() - 40,
                        height: n.height() - 60
                    });
                }
            })
            .bind('click hover focus mousedown', function () {
                $(this)
                    .css('zIndex', zIndex++)
                    .find('.ui-icon')
                    .css('zIndex', zIndex++)
                    .end()
                ;
            })
            .find('.close')
            .click(function () {
                $(this).parents('.sticky-note').remove();
            })
            .end()
            .dblclick(function () {
                $(this).remove();
            })
            .addClass('sticky-note')
            .removeClass('sticky-note-pre')
        ;
    });

    $('#save').click(function () {
        var notes = [], i, note;
        $('.sticky-note').each(function () {
            notes.push($(this).find('.contents').html());
        });
        //do something with notes
        console.log(notes);
    });

    /*TOGGLE MENU DROPDOWN HEADER*/
    $('.dropdown-menu-parent').click(function (event) {
        event.preventDefault();
        return false;
    })
    /*END TOGGLE MENU DROPDOWN HEADER*/

    LOAD_DROPDOWN_MENU_HEADER();
    //CALL DATE RANGER
    if($('.daterange-basic-customer').length){
        DATERANGE_BASIC('.daterange-basic-customer');
    }
});
/*
* Get data all project & service from api mybizfly
* Append content menu dropdown header
* */
function LOAD_DROPDOWN_MENU_HEADER() {
    CRM_CONFIG = typeof CRM_CONFIG !== "undefined" ? CRM_CONFIG : {};
    if(CRM_CONFIG.is_auth2fa)
    {
        return  false;
    }
    if(typeof CRM_CURRENT_PROJECT_ID === "undefined" ){
        return false;
    }
    let API = ADMIN_PREFIX + 'project/get-project-list';
    let COL_LIST_SERVICE = $('#COL_LIST_SERVICE');//column list service
    let COL_LIST_PROJECT = $('#COL_LIST_PROJECT');//column list project
    $.ajax({
        url: API,
        data: '',
        type: "GET",
        dataType: 'json',
        success: function (json) {
            if (json.status == 1 && json.data.length) {
                let DATA = json.data;
                if(DATA.length < 5 ){
                    $('#pr_search').addClass('hide');
                }
                let LIST_PROJECT_HTML = '';
                for(let i = 0; i < DATA.length; i++){
                    let active = '';
                    if(typeof CRM_CURRENT_PROJECT_ID !== "undefined" && CRM_CURRENT_PROJECT_ID === DATA[i]._id){
                        //lấy danh sách dịch vụ nếu đã chọn dự án
                        active = ' active ';
                        LOAD_SERVICE_MENU_HEADER(DATA[i].url);
                    }
                    let description = DATA[i].description && DATA[i].description !== "undefined" ? strip_tags( DATA[i].description) : '';
                    LIST_PROJECT_HTML += DATA[i].name && DATA[i].name !== "undefined" ?
                        '<li data-name="'+strip_tags(DATA[i].name)+'" data-description="'+strip_tags(DATA[i].description)+'" class="dropdown-submenu ' + active + '"\n' +
                        '                                                onclick=\'LOAD_SERVICE_MENU_HEADER("' + DATA[i].url + '",this)\'>\n' +
                        '                                                <a href="javascript:void(0)">\n' +
                        '                                                    ' + strip_tags( DATA[i].name) + '\n' +
                        '                                                    <span class="display-block text-muted">\n' +
                        '                                                    ' + description + '\n' +
                        '                                                </span>\n' +
                        '                                                </a>\n' +
                        '                                            </li>' : '';
                }
                COL_LIST_PROJECT.find('.LIST_PROJECT').html(LIST_PROJECT_HTML).removeClass('hide');//append & show list project
                COL_LIST_PROJECT.find('.ICON_LOADING').addClass('hide');//hide loading
                if(typeof CRM_CURRENT_PROJECT_ID === "undefined"){
                    //chưa chọn dự án nào
                    COL_LIST_SERVICE.find('.LIST_SERVICE').removeClass('hide');//append & show list service
                    COL_LIST_SERVICE.find('.ICON_LOADING').addClass('hide');//hide loading
                }
            }else if(json.status == 1 && json.data.length == 0){
                COL_LIST_PROJECT.find('.LIST_PROJECT').html('<li class="crm_no_result">Chưa có dự án nào</li>').removeClass('hide');//append & show list project
                COL_LIST_PROJECT.find('.ICON_LOADING').addClass('hide');//hide loading
                COL_LIST_SERVICE.find('.LIST_SERVICE').html('<li class="crm_no_result">Chưa có dịch vụ nào</li>').removeClass('hide');//append & show list servce
                COL_LIST_SERVICE.find('.ICON_LOADING').addClass('hide');//show loading
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
        }
    });
}
/*
* Search realive
* */
function showProject() {
    // Declare variables
    var input, filter, ul, li, a, i, txtValue, count = 0;
    input = document.getElementById('pr_search');
    filter = input.value.toUpperCase();
    ul = document.getElementById("list_pr_search");
    li = ul.getElementsByTagName('li');
  
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("a")[0];
      if(!a){
          $('#notification').html('Không có dự án nào');
      }
      txtValue = typeof a.childNodes !== 'undefined' && a.childNodes && a.childNodes[0] && a.childNodes[0].textContent ? a.childNodes[0].textContent : a.childNodes && typeof a.childNodes[0] !== 'undefined' &&  a.childNodes[0] && a.childNodes[0].innerText ? a.childNodes[0].innerText : '';
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        li[i].style.display = "";
        count++;
      } else {
        li[i].style.display = "none";
      }
    }
    if(count==0){
        $('#notification').removeClass('hidden');
		$('#notification').html('Không có dự án trùng với tên bạn vừa tìm');
    }else{
        $('#notification').addClass('hidden');
    }
  }
/*
* Show all service from current project
* */
function LOAD_SERVICE_MENU_HEADER(url,t) {
    let COL_LIST_PROJECT = $('#COL_LIST_PROJECT');//column list project
    let COL_LIST_SERVICE = $('#COL_LIST_SERVICE');//column list service
    COL_LIST_SERVICE.find('.ICON_LOADING').removeClass('hide');//show loading
    COL_LIST_SERVICE.find('.LIST_SERVICE').addClass('hide');//hide list service
    COL_LIST_SERVICE.find('.s_bottom').addClass('hide');//hide bottom
    if(t){
        //xóa và active menu dự án khi click dự án để lấy list dịch vụ
        let _t = $(t);
        $('[data-id="project-name"]').html(_t.data('name'))
        $('[data-id="project-description"]').html(_t.data('description'))
        COL_LIST_PROJECT.find('.dropdown-submenu').removeClass('active');
        _t.addClass('active');
    }

    $.ajax({
        url: url,
        data: '',
        type: "GET",
        dataType: 'json',
        success: function (json) {
            if(json.data){
                let data = json.data;
                let append = '';
                for(let i = 0; i < data.length; i++){
                    let _inner = data[i];
                    let new_icon ='';
                    if(typeof _inner.is_new!=="undefined"){
                        new_icon = ' <span style="    border-radius: 5px;\n' +
                            '    padding: 0 5px;\n' +
                            '    font-size: 11px;" class="bag bg-danger">New</span>';
                    }
                    append += '<li class="media">\n' +
                        '                                                <div class="media-left media-middle">\n' +
                        '                                                    <a href="'+_inner.link+'" onclick="location.href=this.href">\n' +
                        '                                                        <img src="' + _inner.thumb + '"/>\n' +
                        '                                                    </a>\n' +
                        '                                                </div>\n' +
                        '                                                <div class="media-body">\n' +
                        '                                                    <div class="media-heading">\n' +
                        '                                                        <a  href="' +  _inner.link + '"  class="letter-icon-title" onclick="location.href=this.href">' + _inner.name +new_icon+ '</a>\n' +
                        '                                                    </div>\n' +
                        '\n' +
                        '                                                    <div class="text-size-small '+ (typeof _inner.class !== "undefined" ? _inner.class : 'text-green-400') +'">' + _inner.status + '</div>\n' +
                        '                                                </div>\n' +
                        '\n' +
                        '                                                <div class="media-right media-middle">\n' +
                        '                                                    <ul class="icons-list">\n' +
                        '                                                        <li>\n' +
                        '                                                            <a href="javacript:void(0)" data-link="' +  _inner.link + '" onclick="choose_service(this)"></a>\n' +
                        '                                                        </li>\n' +
                        '                                                    </ul>\n' +
                        '                                                </div>\n' +
                        '                                            </li>';
                }
                COL_LIST_SERVICE.find('.LIST_SERVICE').html(append).removeClass('hide');//append & show list servce
                COL_LIST_SERVICE.find('.s_bottom').removeClass('hide');
                COL_LIST_SERVICE.find('.ICON_LOADING').addClass('hide');//show loading
            }else if(json.status == 1 && json.data.length == 0){
                COL_LIST_SERVICE.find('.LIST_SERVICE').html('<li class="crm_no_result">Chưa có dự án nào</li>').removeClass('hide');//append & show list project
                COL_LIST_SERVICE.find('.s_bottom').removeClass('hide');
                COL_LIST_SERVICE.find('.ICON_LOADING').addClass('hide');//hide loading
            }
            if(typeof json.objProject !== 'undefined'){
                let objProject = json.objProject;
                $('.OBJ_URL_HOME').attr('href',objProject.url.home);
                $('.OBJ_URL_SETTING').attr('href',objProject.url.setting);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}


function choose_service(t){
    let link = $(t).data('link');
    return window.location.href = link;
}

/*CLICK CHECK ALL*/
$("#checkAll").click(function () {
    CRM_CHECK_ALL(this);
});

function CRM_CHECK_ALL(t) {
    $('input:checkbox.js-check-box-list').not(t).prop('checked', t.checked).uniform({});
}

/*END CLICK CHECK ALL*/

/*CLICK AUTO COPY*/
function CRM_COPY(element) {
    if(!element){
        element = 'link_copy';
    }
    /* Get the text field */
    var copyText = document.getElementById(element);

    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Sao chép thành công.");
}

/*END CLICK AUTO COPY*/

function CONFIRM_REMOVE_FILTER(url) {
    if (!url) {
        return false;
    }
    if (!confirm('Bạn có chắc chắn muốn thực hiện thao tác này?')) {
        return false;
    }
    $.ajax({
        url: url,
        data: 'remove=1',
        type: "GET",
        dataType: 'json',
        success: function (json) {
            if (json.status == 1) {
                alert(json.msg);
                $('#id_' + json.data['_id']).remove();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}

function set_scrollbar() {
    //set width,height, hiển thị thanh sroll
    let filterw = $('.table-crm-filter');
    let responsivexw = $('.table-responsivex');
    if (filterw.length && responsivexw.length) {
        if (responsivexw.height() > filterw.height()) {
            //$('.scrollbar_out').remove();//xóa scroll bên ngoài
            $('.scrollbar_in').remove();//xóa scroll bên ngoài
        } else {
            $('.scrollbar_in').remove();//xóa scroll bên trong
        }
        if (filterw.width() <= responsivexw.width()) {
            $('.scroll_bottom_bar').addClass('hide');//ẩn toàn bộ scroll nếu độ rộng content vượt quá element cha
        } else {
            $('.scroll_bottom_bar').removeClass('hide').css({width: responsivexw.width()});//hiện scroll
            $('.scroll_bottom_bar>div').css({width: filterw.width()});//set width content scroll
        }
    }
}

$(function () {
    set_scrollbar();
    $(window).resize(function () {
        set_scrollbar();
    });

    $('.scroll_bottom_bar').scroll(function (e) {
        //kéo scroll
        $('.scroll_bottom_bar_x').scrollLeft($('.scroll_bottom_bar').scrollLeft());
    })

    $(document).keydown(function (e) {
        if (e.keyCode == 27) {
            CLOSE_QUICKVIEW_RECORD();//đóng quickview record nếu ấn esc
        }
    });
    $('[data-action=crm-close-modal]').click(function () {
        CLOSE_QUICKVIEW_RECORD();//đóng quickview record nếu click vào vùng clode
    })
    $(window).scroll(function () {
        if($(this).scrollTop() > 15){
            $('.navbar-fixed-top').addClass('navbar-fixed-top-scroll')
        }else{
            $('.navbar-fixed-top').removeClass('navbar-fixed-top-scroll')
        }
    });
});

function QUICKVIEW_RECORD(url, title) {
    //xem nhanh bản ghi
    if (typeof title === "undefined") {
        title = ' Thông tin';
    }
    $('.crm_overlay,.crm_overlay_content').addClass('CRM_SHOW');
    $('#__QUICK_VIEW_TITLE').html(title);
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            if (data) {
                $('.overlay_info').html($(".table-responsive", data));
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        },
    });
}

function FADE_IN_SIDEBAR_RIGHT(url, title) {
    //xem nhanh bản ghi
    if (typeof title === "undefined") {
        title = ' Thông tin';
    }
    $('.crm_overlay,.crm_overlay_content').addClass('CRM_SHOW');
    $('#__QUICK_VIEW_TITLE').html(title);
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            if (data) {
                $('.crm_overlay_content .panel-heading').hide();
                $('.overlay_info').html('FORM TICKET SHOW Ở ĐÂY').css({'line-height':'90vh',height:'100vh'}).addClass('text-center');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        },
    });
}

function QUICKVIEW_EDIT(url, _this, fields, title) {
    //xem nhanh bản ghi
    if (typeof title === "undefined") {
        title = ' Thông tin';
    }
    $('.crm_overlay,.crm_overlay_content').addClass('CRM_SHOW');
    $('#__QUICK_VIEW_TITLE').html(title);
    $.ajax({
        url: url,
        type: 'GET',
        data: 'fields=' + fields,
        dataType: 'html',
        success: function (data) {
            if (data) {
                $('.overlay_info').html($(".TABLE_CELL_CONTENT", data));
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        },
    });
}

function CLOSE_QUICKVIEW_RECORD() {
    $('.crm_overlay,.crm_overlay_content').removeClass('CRM_SHOW');
    let content_quickview = '<div class="panel-body">\n' +
        '                    <div class="row">\n' +
        '                        <div class="fake_line1"></div>\n' +
        '                        <div class="fake_line2"></div>\n' +
        '                        <div class="fake_line3"></div>\n' +
        '                    </div>\n' +
        '                </div>';
    $('.overlay_info').html(content_quickview);
}

function TOGGLE_FILTER() {
    $('#toggle-filter').toggleClass('toggle-filter');
    $('.btn-filter-customer i').toggleClass('icon-arrow-right13');
    IO_COOKIE.toggleCookie('show-filter-customer', 7)
    setTimeout(function () {
        set_scrollbar();
    }, 500);
}

function send_mail_by_campaign(url, token, campaign_id) {
    _SHOW_FORM_REMOTE(url + '&campaign_id=' + campaign_id + '&token=' + token)
}
function send_sms_by_campaign(url, token, campaign_id) {
    _SHOW_FORM_REMOTE(url + '&campaign_id=' + campaign_id + '&token=' + token)
}

function jsDateRanger(format) {
    let $this = $('.JS_DATE_RANGER');
    jsThisDateRanger($this,format);

}
function jsThisDateRanger($this,format) {
    if(!format){
        format = 'DD/MM/YYYY';
    }
    $this.daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        autoUpdateInput: false,
        singleDatePicker: false,
        showDropdowns: true,
        locale: {
            format: format,
            daysOfWeek: dr_week,
            monthNames: dr_month,
        },
        autoEnd: true,
    });
    $this.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(format) + ' - ' + picker.endDate.format(format));
    });
    $this.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
}

//export danh sách bản ghi
function exportExcelRecord() {
    $('#modal-result-filter').modal();
}

//export danh sách bản ghi
function exportExcelRecord_choose(current_url) {
    let _CHOOSE = $('input[name=option]:checked').val();
    current_url = current_url.indexOf('?') > -1 ? current_url + '&': current_url + '?';
    let _URL_ACTION_CALL_BACK = current_url + 'export_excel=1';
    if (_CHOOSE !== 'all') {
        let id_list = $('.customer_check_list').get().filter(item => item.checked && $.type(item.value) === 'string' && item.value.trim().length > 0).map(item => item.value).join(',');
        _URL_ACTION_CALL_BACK = current_url + 'id_list=' + id_list + '&export_excel=1'
    }
    if (!_CHOOSE) {
        alert('Chọn bản ghi để tiếp tục');
        return false;
    } else if (_CHOOSE === 'choose' && parseInt($('.count_customer_checked').text()) == 0) {
        alert('Chọn bản ghi để tiếp tục');
        return false;
    } else if (_CHOOSE === 'all' && parseInt($('input[name=number_row]').val()) == 0) {
        alert('Chọn bản ghi để tiếp tục');
        return false;
    }
    location.href = _URL_ACTION_CALL_BACK;
}

function export_data_from_table(id_table, file_name, type, fn, dl) {
    var elt = document.getElementById(id_table || 'data-table');
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS",raw:true});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || (file_name + '.' + (type || 'xlsx')));
}
function stringToASCII(str) {
    try {
        return str.toLowerCase().replace(/[àáảãạâầấẩẫậăằắẳẵặ]/g, 'a')
            .replace(/[àáảãạâầấẩẫậăắằẳẵặ]/g, 'a')
            .replace(/[èéẻẽẹêềếểễệ]/g, 'e')
            .replace(/[èéẻẽẹêềếểễệ]/g, 'e')
            .replace(/[đ]/g, 'd')
            .replace(/[đ]/g, 'd')
            .replace(/[ìíỉĩị]/g, 'i')
            .replace(/[ìíỉĩị]/g, 'i')
            .replace(/[òóỏõọôồốổỗộơờớởỡợ]/g, 'o')
            .replace(/[òóỏõọôồốổỗộơờớởỡợ]/g, 'o')
            .replace(/[ùúủũụưừứửữự]/g, 'u')
            .replace(/[ùúủũụưừứửữự]/g, 'u')
            .replace(/[ỳýỷỹỵ]/g, 'y').trim()
            .replace(/[ỳýỷỹỵ]/g, 'y').trim()


    } catch(e) {
        return ''
    }
}


