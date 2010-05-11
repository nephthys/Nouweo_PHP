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
    $('a.vote_attente_plus').click(function(){
        var id_news = $(this).attr('id');
        
        if (id_news.length > 0) {
            $("#loader_user").hide();
            $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');
            $.post("ajax.php?s=news&act=vote_attente", {id: id_news, type: 'plus'}, 
                function(data){
                    $("#loader span").fadeOut("slow",function(){ $("#loader").empty(); $("#loader_user").show(); });
                    $('#output_'+id_news).fadeOut("normal",function(){ $('#output_'+id_news).fadeIn("fast",function(){$('#output_'+id_news).html(data); }); });
                }
            );
            return false;
        }
    });
    
    $('a.vote_attente_moins').click(function(){
        var id_news = $(this).attr('id');
        
        if (id_news.length > 0) {
            $("#loader_user").hide();
            $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');
            $.post("ajax.php?s=news&act=vote_attente", {id: id_news, type: 'moins'}, 
                function(data){
                    $("#loader span").fadeOut("slow",function(){ $("#loader").empty(); $("#loader_user").show(); });
                    $('#output_'+id_news).fadeOut("normal",function(){ $('#output_'+id_news).fadeIn("fast",function(){$('#output_'+id_news).html(data); }); });
                }
            );
            return false;
        }
    });
});
