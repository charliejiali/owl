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


    $("#detailsBox").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });

    $("#controlBox .control-label").on("click", function (e) {
        e.preventDefault();
        console.log('12312')
        if (!$(this).parent().hasClass("disabled")) {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                $(this).addClass("active");
            }
            checkControlNum();
        }
    });

    $("#controlBox .control-slider").on("mouseup", function (e) {
        // console.log($(this).val());
        checkControlNum();
    });


    $('.video-play-button').on("click", function (e) {
        console.log($(this).data("poster"), $(this).data("video"));
        var curPopup = $.magnificPopup.open({
            items: {
                // src: $('<div class="video-popup"><video src="' + $(this).data("video") + '" poster="' + $(this).data("poster") + '" width="960" preload autoplay id="videoPlayer"></video></div>'),
                src: $('<div class="video-popup"><video src="' + $(this).data("video") + '" width="960" preload autoplay id="videoPlayer"></video></div>'),
                type: 'inline'
            },
            mainClass: 'mfp-fade',
            midClick: true,
            closeBtnInside: true,
            closeOnBgClick: false,
            callbacks: {
                open: function () {
                    console.log("alertBox open");
                    var mvPlayer = new MediaElementPlayer('#videoPlayer', {
                        enablePluginDebug: false,
                        plugins: ['flash'],
                        type: '',
                        // pluginPath: 'js/mediae/',
                        // flashName: 'flashmediaelement.swf',
                        defaultVideoWidth: 960,
                        defaultVideoHeight: 540,
                        pluginWidth: 0,
                        pluginHeight: 0,
                        timerRate: 250,
                        startVolume: 1,
                        // features: ['playpause', 'progress', 'current', 'duration', 'tracks', 'volume', 'fullscreen'],
                        features: ['playpause', 'progress', 'current', 'duration'],
                        // features: [],
                        success: function (mediaElement, domObject) {
                            mediaElement.addEventListener('play', function (e) {

                            }, false);
                            mediaElement.addEventListener('pause', function (e) {
                            }, false);
                            mediaElement.addEventListener('ended', function (e) {
                                console.log('ended');
                                // $(".home-video-player").hide();
                                //mvPlayer.exitFullScreen();
                                // curPopup.close();
                                $.magnificPopup.close();
                            }, false);
                        },
                        error: function () {
                        }
                    });
                },
                close: function () {
                    console.log("alertBox close");
                },
                afterClose: function () {
                    console.log('Popup is completely closed');

                    // $('video, audio').mediaelementplayer({ shimScriptAccess: 'always' });


                    // if ($aUrl != undefined && $aUrl != "") {
                    //     if ($target != undefined) {
                    //         window.open($aUrl, $target);
                    //     } else {
                    //         location.href = $aUrl;
                    //     }
                    // }
                    // if (typeof($callback) == "function") {
                    //     $callback();
                    // }
                }
            }
        });
    });


    $('#back').on('click',function(){
        window.location.href='/detail/main';
    });
    $('a[name="media"]').on('click',function(){
        window.location.href='/detail/view?name='+name+'&platform='+$(this).attr('data-name');
    });
});
