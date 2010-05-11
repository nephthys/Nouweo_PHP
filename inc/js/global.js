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
    var popup_categories_ouvert = null;
    $('#more_catsub').mouseenter(function(event)
    {
        clearTimeout(popup_categories_ouvert);
        $(this).find('ul').slideDown('normal');
    })
    .mouseleave(function(event)
    {
        popup_categories_ouvert = setTimeout(function(){ $('#more_catsub').find('ul').slideUp('fast'); }, 200);
    });
            
    $('a#show_rqt').click(function()
    {
        if ($("#list_rqts").is(":hidden")) { $("#list_rqts").slideDown("normal",function(){ $("list_rqts").show(); }); }
        else { $("#list_rqts").slideUp("fast",function(){ $("list_rqts").hide(); }); }
        return false;
    });
    
    
    $("a.edit_param_widget_live").click(function() {
        var id_live = $("#w_post_id").val();
        
        if ($('#admin_edit_widget_live_'+id_live).is(":hidden")) { $('#admin_edit_widget_live_'+id_live).slideDown("normal",function(){ $('#admin_edit_widget_live_'+id_live).show(); }); }
        else { $('#admin_edit_widget_live_'+id_live).slideUp("fast",function(){ $('#admin_edit_widget_live_'+id_live).hide(); }); }
        
        return false;
    });
    
    $("a.stop_edit_widget_live").click(function() {
        var id_live = $("#w_post_id").val();
        $('#admin_edit_widget_live_'+id_live).slideUp('fast');
        return false;
    });
    
    var edit_widget_open = null;
    $('.one_widget').mouseenter(function(event)
    {
        clearTimeout(edit_widget_open);
        $(".one_widget .content_widget_without_title").addClass("add_border_widget_hover");
        
        $(".one_widget .add_opt_edit").fadeIn("slow",function(){
            $(".one_widget .add_opt_edit").show();
        });
    })
    .mouseleave(function(event)
    {
        edit_widget_open = setTimeout(function(){ $('.one_widget .add_opt_edit').hide(); }, 200);
        
        if($(".one_widget .content_widget_without_title").hasClass("add_border_widget_hover")) {
            $(".one_widget .content_widget_without_title").removeClass("add_border_widget_hover");
        }
    });
});
