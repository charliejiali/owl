/**
 * Created by ewen on 2016/5/12.
 */
var win = {};

$(document).ready(function () {
    onResize();
    $(window).bind('resize', onResize);

    function onResize() {
        win.w = $(window).width();
        win.h = $(window).height();
    }

    /*selectbox(document.getElementById('demo-select-img'), {
     optionHtml: '<dd class="ui-selectbox-option {{className}}" data-option="{{index}}" tabindex="-1"><img src="{{icon}}" alt="icon" style="width:16px;height:16px;vertical-align:middle" /> {{textContent}}</dd>'
     });*/

    $('.form-eval select').each(function (i, e) {
        selectbox(this);
    });


    $(".result-list").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });

    var program_id='';
    get_media_list();

    $('#select_platform').on('change', function () {
        get_media_list();
    });
    $('#select_property').on('change', function () {
        get_media_list();
    });
    $('#select_time').on('change', function () {
        get_media_list();
    });
    $('#program_name').on('keypress',function(e){
        if(e.keyCode==13){
            get_media_list();
        }
    });
    // 点击剧目效果
    $("#resultList").on("click", '.list-item-img', function (e) {
        var id = $(this).attr('data-id');
        window.location.href='/detail/view?name='+id;
    });

    function get_media_list() {
        var input = {};
        input["program_name"]=$('#program_name').val();
        input["platform_name"] = $('#select_platform option:selected').val();
        input["property_name"]= $('#select_property option:selected').val();
        input["time"] = $('#select_time option:selected').val();

        $.get('/detail/get-list',input, function (json) {
            var box = $('#list');
            box.empty();
            if (!$.isEmptyObject(json)) {
                set_yes_result();

                var data = json;
                for (var i in data) {
                    var d = data[i];
                    var img_url =  d.src;

                    if (img_url != '') {
                        box.append('<div class="pure-u-1-5">'
                            + '  <div class="list-item-img" data-id="' + d.program_name + '">'
                            + '      <div class="box-img-mask">'
                            + '          <div class="box-img" style="background-image:url(' + img_url + ') "><p>' + d.program_name + '</p></div>'
                            + '      </div>'
                            + '      <p class="title">《' + d.program_name + '》</p>'
                            + '  </div>'
                            + '</div>'
                        );
                    } else {
                        box.append('<div class="pure-u-1-5">'
                            + '  <div class="list-item-img" data-id="' + d.program_name + '">'
                            + '      <div class="box-img-mask"><div class="box-img no-img">'
                            + (__BDP_C.strLen(d.program_name) > 13 ? '<p class="min">《' + d.program_name + '》</p>' : '<p>《' + d.program_name + '》</p>')
                            + '      </div></div>'
                            + '      <p class="title">《' + d.program_name + '》</p>'
                            + '  </div>'
                            + '</div>'
                        );
                    }
                }

                if (program_id != '') {
                    $('#resultList .list-item-img[data-id="' + program_id + '"]').trigger('click');
                    program_id = '';
                }
            }
        }, 'json');
    }
    function set_no_result() {
        $('#resultList').hide();
        $('#no-result').show();
    }
    function set_yes_result() {
        $('#resultList').show();
        $('#no-result').hide();
    }
});
