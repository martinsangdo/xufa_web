/**
 * author: Martin SangDo
 */
//get & show post detail
function get_article_detail(){
    var site_type = $('#site_type').val();
    var $content_container = $('#article_detail_container');

    if (site_type == 'wp'){
        if (common.isEmpty($('#content_detail', $content_container).text())){
            //try to get from original source
            $('#loading_img', $content_container).removeClass('hidden');
            var url = $('#site_api_uri').val() + 'posts/'+$('#original_post_id').val();
            common.ajaxRawGet(url, function(resp){
                if (resp.content && resp.content.rendered){
                    $content_container.html(resp.content.rendered);
                    $('a', $content_container).attr('target', '_blank');
                    $('img', $content_container).css('max-width', '100%').css('height', 'auto');
                    $('#loading_img', $content_container).remove();
                }
            }, function(err){});
        }
    } else if (site_type == 'rss'){
        $content_container.html($('#post_excerpt').html());
        $content_container.append('<br/><a href="'+$('#original_url').val()+'">Go detail >></a>');
        $('a', $content_container).attr('target', '_blank');
        $('#loading_img').remove();
    }
}
//get & show related posts
function get_extra_posts(){
    var params = {
        post_id: $('#post_id').val(),
        extra_ids: $('#extra_ids').val()
    }
    common.ajaxPost(API_URI.GET_EXTRA_POSTS, params, function(lists){
        // console.log(lists);
        show_extra_posts(lists['random_posts'], $('#random_posts_container'));
        // show_extra_posts(lists['recent_posts'], $('#recent_posts_container'));
    });
}
//show related posts
function show_extra_posts(list, $container){
    var len = list.length;
    if (len == 0){
        return;
    }
    var $item_tmpl = $('#post_tmpl');      //item template
    var $item;
    for (var i=0; i<len; i++){
        $item = $item_tmpl.clone(false);
        $('.thumb_url', $item).attr('src', list[i]['thumb_url']).
            css('cursor', 'pointer').attr('onclick', 'common.redirect("/news/'+list[i]['slug']+'");');
        $('.title', $item).html(list[i]['title']);
        $('a', $item).attr('href', '/news/'+list[i]['slug']);
        $container.append($item.removeClass('hidden').removeAttr('id'));
    }
    //detect error img
    // setTimeout(function(){
    //     $('img', $container).each(function() {
    //         if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
    //             // image was broken, replace with your new image
    //             this.src = '/public/unity_assets/img/missing_img.png';
    //         }
    //     });
    // }, 2000);
}
//show message in form
function show_mess(str){
    $('#message').text(str).removeClass('hidden');
}
//insert new comment
function add_new_comment(){
    if (submitting){
        return;
    }
    var name = $.trim($('#txt_name').val());
    var email = $.trim($('#txt_email').val());
    var content = $.trim($('#txt_content').val());
    if (common.isEmpty(name)){
        show_mess('You must type your name');
        return;
    } else if (common.isEmpty(email) || !common.isValidEmail(email)){
        show_mess('You must type valid email');
        return;
    } else if (common.isEmpty(content) || content.length < 10){
        show_mess('You must type the content with minimum length 10 characters');
        return;
    }
    var params = {
        post_id: $('#post_id').val(),
        name: name,
        email: email,
        content: content
    };
    submitting = true;
    common.ajaxPost(API_URI.ADD_NEW_COMMENT, params, function(resp){
        if (resp.result){
            //success, add to comment list
            show_mess('Your comment is saved!');
            //should not append to UI yet because of pagination
            // append_new_comment({
            //     username: name,
            //     time: common.format_date(new Date()),
            //     content: content
            // });
            $('#txt_name').val('');
            $('#txt_email').val('');
            $('#txt_content').val('');
            $('#comment_num').text(parseInt($('#comment_num').text()) + 1);
        } else {
            //failed to insert
            show_mess('Something wrong, please try again later!');
        }
        submitting = false;
    });
}
//append new comment to UI
function append_new_comment(detail){
    var $item = $('#comment_tmpl').clone(false);
    $('.username', $item).text(detail.username);
    $('.date', $item).text(detail.time);
    $('.content', $item).text(detail.content);
    $('#comment_list').append($item.removeClass('hidden').removeAttr('id'));
}
//load next page of comments
function load_more_comment(){
    if (submitting){
        return;
    }
    var offset = $('.comment_detail', $('#comment_list')).length;
    var params = {
        offset: offset,
        limit: CONST.DEFAULT_PAGE_LEN,
        post_id: $('#post_id').val()
    };
    submitting = true;
    common.ajaxPost(API_URI.GET_COMMENT_PAGING, params, function(resp){
        if (resp.list) {
            var len = resp.list.length;
            for (var i=0; i<len; i++){
                append_new_comment(resp.list[i]);
            }
            //
            $('#comment_num').text(resp.total);
            if (len >= CONST.DEFAULT_PAGE_LEN){
                //may have more comments
                $('#btn_load_more_comment').removeClass('hidden');
            } else {
                //no more comments
                $('#btn_load_more_comment').addClass('hidden');
            }
        } else {
            //no more comments
            $('#btn_load_more_comment').addClass('hidden');
        }
        submitting = false;
    });
}
//
function window_onload(){
    get_article_detail();
    // get_extra_posts();
    // load_more_comment();
    //replace broken images
    // setTimeout(function() {
    //     $('img').each(function () {
    //         if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
    //             // image was broken, replace with your new image
    //             this.src = '/public/unity_assets/img/missing_img.png';
    //         }
    //     });
    // }, 3000);
}

window.onload = window_onload;