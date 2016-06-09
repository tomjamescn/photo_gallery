<?php

/* @var $this yii\web\View */

?>

<style>
    .grid-item { width: 200px; }
    .center {text-align: center;}

    .spinner {
        margin: 100px auto 0;
        width: 70px;
        text-align: center;
    }

    .spinner > div {
        width: 18px;
        height: 18px;
        background-color: #333;

        border-radius: 100%;
        display: inline-block;
        -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        animation: sk-bouncedelay 1.4s infinite ease-in-out both;
    }

    .spinner .bounce1 {
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }

    .spinner .bounce2 {
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }

    @-webkit-keyframes sk-bouncedelay {
        0%, 80%, 100% { -webkit-transform: scale(0) }
        40% { -webkit-transform: scale(1.0) }
    }

    @keyframes sk-bouncedelay {
        0%, 80%, 100% {
            -webkit-transform: scale(0);
            transform: scale(0);
        } 40% {
              -webkit-transform: scale(1.0);
              transform: scale(1.0);
          }
    }

    .favorite-icon {

        position: absolute;
        right: 5px;
        top: 5px;
        font-size: 24px;
        cursor: pointer;

    }

    .favorite-icon-select {
        color: yellow;
    }


</style>

<div class="grid"></div>

<br><br>

<div id="nomore" class="row" style="display: none;">
    <div class="col-xs-12 center">
        已经到底了~
    </div>
</div>

<div id="loadMore" class="row">
    <div class="spinner" onclick="getPhotoInfoList();">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<?php $this->beginBlock('pageJs'); ?>
<script>

    function doFavorite(imageId) {
        var eleId = 'favorite_icon_'+imageId;
        console.log('doFavorite:'+imageId);
        $.ajax({
            url: '?r=site/do-favorite&imageId='+imageId,
            dataType: 'json'
        }).done(function(result){
            if(result.code == 0) {
                if($('#'+eleId).hasClass('favorite-icon-select')) {
                    $('#'+eleId).removeClass('favorite-icon-select');
                }else{
                    $('#'+eleId).addClass('favorite-icon-select');
                }
            }else{
                alert(result.msg);
            }
        });
    }

    function preloadImages(pictureUrls, callback) {
        var i, j, loaded = 0;

        for (i = 0, j = pictureUrls.length; i < j; i++) {
            (function (img, src) {
                img.onload = function () {
                    if (++loaded == pictureUrls.length && callback) {
                        callback();
                    }
                };

                // Use the following callback methods to debug
                // in case of an unexpected behavior.
                img.onerror = function () {};
                img.onabort = function () {};

                img.src = src;
            } (new Image(), pictureUrls[i]));
        }
    };

    var msnry = new Masonry( '.grid', {
        // options
        itemSelector: '.grid-item',
        columnWidth: 200
    });

    var page = 1;
    var sn = 1;
    var numPerPage = 50;
    function getPhotoInfoList() {
        $.ajax({
            url: '<?=$getPhotoInfoListUrl ?>',
            data: {
                numPerPage: numPerPage,
                page: page,
                sn: sn,
                tagId: <?=isset($tagId) ? $tagId : 0; ?>
            },
            dataType: 'json'
        }).done(function (result) {

            if(sn > result.sn || result.data.length == 0) {
                //no more
                $('#loadMore').hide();
                $('#nomore').show();
                return;
            }

            if(result.data.length < numPerPage) {
                //no more
                $('#loadMore').hide();
                $('#nomore').show();
            }

            sn++;

            page ++;

            var urlList = [];

            var linksContainer = $('.grid')
            // Add the demo images as links with thumbnails to the page:
            $.each(result.data, function (index, photo) {

                urlList[urlList.length] = photo['thumbnailUrl'];

                var favoriteCss = 'glyphicon glyphicon-heart favorite-icon ';
                if(photo['isFavorite']) {
                    favoriteCss += ' favorite-icon-select';
                }

                var favoriteHtml = '<span class="'+favoriteCss+'" aria-hidden="true" ' +
                    'id="favorite_icon_'+photo['id']+'" onclick="doFavorite('+photo['id']+');return false;" />';


                var ele = $('<div class="grid-item">').append($('<a class="swipebox"/>')
                    .append($('<img>').prop('src', photo['thumbnailUrl']))
                    .prop('href', photo['url'])
                    .prop('title', 'title')).append($(favoriteHtml));

                ele.appendTo(linksContainer);
                msnry.appended(ele);
            });

            preloadImages(urlList, function() {
                msnry.layout();
                $( '.swipebox' ).swipebox();
            });

        });
    }

    getPhotoInfoList();

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            getPhotoInfoList();
        }
    });


</script>

<?php $this->endBlock(); ?>
