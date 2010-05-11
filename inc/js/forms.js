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
    $('#get_apercu_final').click(function(){
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');
        var content = $("#contenu").val();
        
        $.post("ajax.php?s=common&act=prev", {content: content}, 
            function(data){
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                });
                $('#apercu_final').html(data);
            }
        );
        return false;
    });
});
