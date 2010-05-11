/*
 *  Copyright (C) 2009 Nouweo
 *  
 *  Nouweo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$(function(){
    $('#ajx_fav_news a').click(function(){
        var id_news = $(this).attr('id');
        $("#loader_user").hide();
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

        $.post("ajax.php?s=news&act=fav", {id: id_news}, 
            function(data){
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                    $("#loader_user").show();
                });
                $('#ajx_fav_news a#'+id_news).html(data);
            }
        );
        return false;
    });
    
    $('#ajx_more_fav_news a').click(function(){
        var id_news = $(this).attr('id');
        $("#loader_user").hide();
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

        $.post("ajax.php?s=news&act=fav&with_txt=1", {id: id_news}, 
            function(data){
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                    $("#loader_user").show();
                });
                //alert(id_news);
                $('#ajx_more_fav_news a#'+id_news).html(data);
            }
        );
        return false;
    });
    
    $('.ajx_vote_news a').click(function(){
        var id_news = $(this).attr('id');
        $("#loader_user").hide();
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

        $.post("ajax.php?s=news&act=vote", {id: id_news}, 
            function(data){
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                    $("#loader_user").show();
                });
                $('.ajx_vote_news #'+id_news).html(data);
            }
        );
        return false;
    });
    
    $('.ajx_vote_cmts a').click(function(){
        var id_cmt = $(this).attr('id');
        $("#loader_user").hide();
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

        $.post("ajax.php?s=news&act=vote_cmt", {id: id_cmt}, 
            function(data){
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                    $("#loader_user").show();
                });
                
                if(data >= vote_cmt_pertinent && !$('#content_'+id_cmt).hasClass("cmt_pertinent")){
                    $('#post_'+id_cmt).before('<div class="post_tag_pertinent">'+post_tag_pertinent+'</div>');
                    $('#content_'+id_cmt).addClass("cmt_pertinent");
                }
                
                $('.ajx_vote_cmts #'+id_cmt).html('<img src="themes/'+design_default+'/images/plussun.png" alt="" /> <span class="voted">+'+data+'</span>');
            }
        );
        return false;
    });
    
    
    $('.ajx_vote_attente a.bouton_voteattente').click(function(){
        var id_news = $(this).attr('id');
    
        if (id_news.length > 0)
        {
            $("#loader_user").hide();
            $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

            $.post("ajax.php?s=news&act=vote&target=attente", {id: id_news}, 
                function(data){
                    $("#loader span").fadeOut("slow",function(){
                        $("#loader").empty();
                        $("#loader_user").show();
                    });
                    $('.vote_news #'+id_news).fadeOut("normal",function(){
                        $('.vote_news #'+id_news).fadeIn("fast",function(){
                            $('.vote_news #output_'+id_news).html(data);
                        });
                    });
                }
            );
            return false;
        }
    });
    
    $('#open_hidden_form_tag').click(function()
    {
        if ($("#hidden_form_tag").is(":hidden")) {
        $("#hidden_form_tag").slideDown("normal");
            
            $('#hidden_form_tag #submit').click(function(){
                $("#loader_user").hide();
                $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');
                var new_tag = $("#hidden_form_tag #add_tag").val();
                var id_news = $('#open_hidden_form_tag span').attr('id');
                
                $.post("ajax.php?s=news&act=tags", {tag: new_tag, id: id_news}, 
                    function(data){
                        $("#loader span").fadeOut("slow",function(){
                            $("#loader").empty();
                            $("#loader_user").show();
                        });
                        $('#insert_tags_before').before(data);
                        $("#hidden_form_tag #add_tag").empty();
                    }
                );
            });
        }
        else {
            $("#hidden_form_tag").slideUp("fast");
        }
        return false;
    });
    
    // changer links when clicked
    $("a.mod_size_text").click(function(){
        var $mainText = $('#content_resize_news');
        var currentSize = $mainText.css('font-size');
        var num = parseFloat(currentSize, 10);
        var unit = currentSize.slice(-2);

        if (this.id == 'textLarge'){
        num = num + 2;
        } else if (this.id == 'textSmall'){
        num = num - 2;
        }
        // jQuery lets us set the font Size value of the mainText div
        $mainText.css('font-size', num + unit);
        return false;
    });
});


function search_completion(inputString) {
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $("#suggestions").hide();
    } else {
        $("#loader_user").hide();
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');
        
        $.post("ajax.php?s=common&act=search", {tag: ""+inputString+""}, function(data){
            if(data.length > 0) {
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                    $("#loader_user").show();
                });
                $("#suggestions").show();
                $("#autoSuggestionsList").html(data);
            }
        });
    }
}

function search_after(thisValue) {
    $("#s").val(thisValue);
    setTimeout("$('#autoSuggestionsList ul').fadeOut('fast',function(){$('#suggestions').hide();});", 100);
    return false;
}

var id_champs = 0;

function addSrcField(id_champs) {
    ++id_champs;
    $("#others_src").append("<div style=\"padding: 2px 0 4px 0;\" id=\"new_src_"+id_champs+"\"><div style=\"float: left; margin-right: 12px;\">"
        +   "<input name=\"sources[]\" type=\"text\" size=\"35\" /></div>"
        +   "<input name=\"sources_nom[]\" type=\"text\" size=\"15\" /> <a href=\"#\" onclick=\"removeSrcField('#new_src_"+id_champs+"'); return false;\"><img src=\"themes/1/images/icon_del.png\" alt=\"\" /></a></div>"
        +   "<div class=\"clearer\"></div>");
    return false;
}

function removeSrcField(id) {
    $(id).remove();
    return false;
}
