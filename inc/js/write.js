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
        });        var popup_ouvert = null;    $('.over_align').mouseenter(function(event){ clearTimeout(popup_ouvert); $('#menu_over_align').slideDown('normal'); })    .mouseleave(function(event) { popup_ouvert = setTimeout(function(){ $('#menu_over_align').slideUp('fast'); }, 200); });
    
    var popup_ouvert2 = null;
    $('.over_flottant').mouseenter(function(event){ clearTimeout(popup_ouvert2); $('#menu_over_flottant').slideDown('normal'); })
    .mouseleave(function(event) { popup_ouvert2 = setTimeout(function(){ $('#menu_over_flottant').slideUp('fast'); }, 200); });
});

function tags_completion(inputString) {
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $("#autotags_suggestions").hide();
    } else {
        $('#loader').append('<span><img src="themes/'+design_default+'/images/loader_blck.gif" alt="" /> '+loader_lang+'</span>');

        $.post("ajax.php?s=news&act=auto_tags_edit", {tag: ""+inputString+""}, function(data){
            if(data.length > 0) {
                $("#loader span").fadeOut("slow",function(){
                    $("#loader").empty();
                });
                if(data != 'EMPTY') {
                    $("#autotags_suggestions").show(); 
                    $("#autotags_SuggestionsList").html(data);
                }
                return false;
            }
        });
    }
}

function tags_completion_after(thisValue) {
    $("#tags").val(thisValue);
    setTimeout("$('#autotags_SuggestionsList').fadeOut('fast',function(){$('#autotags_suggestions').hide();});", 100);
    return false;
}

function add_edit_trace(content, id_news) {
    if(content.length != 0) {
        $.post("ajax.php?s=news&act=add_edit_trace", {content: content, id_news: id_news}, function(data){
            if(data.length > 0) {
                alert(data);
                return false;
            }
        });
    }
}

function balise(id_champ, balise1, balise2) {
    //Dans le cas d'un select autre que "other"
    if (balise1 == 'couleur' || balise1=='align' || balise1=='flottant' || balise1=='taille') {
        var champ_encours=document.getElementById(balise1+'_'+id_champ);
        var selected_index=champ_encours.selectedIndex;
        if(selected_index==0) return;
        var valeur_champ=champ_encours.options[selected_index].value;
        balise2='</'+balise1+'>';
        balise1='<'+balise1+' valeur="'+valeur_champ+'">';
        champ_encours.options[0].selected=true;
    }
    //Dans le cas des select "other"
    if (balise1 == 'other') {
        var champ_encours=document.getElementById('other_'+id_champ);
        var selected_index=champ_encours.selectedIndex;
        if(selected_index==0) return;
        var valeur_champ=champ_encours.options[selected_index].value;
        if(valeur_champ.indexOf('<',2)==-1)
            balise2='';
        else
            balise2=valeur_champ.substring(valeur_champ.lastIndexOf('<'),valeur_champ.length);
        balise1=valeur_champ.substring(0,valeur_champ.indexOf('>')+1);
        champ_encours.options[0].selected=true;
    }
    if(balise1 == 'code') {
        var champ_encours=document.getElementById(balise1+'_'+id_champ);
        var selected_index=champ_encours.selectedIndex;
        if(selected_index==0) return;
        var valeur_champ=champ_encours.options[selected_index].value;
        balise2='</'+balise1+'>';
        balise1='<'+balise1+' type="'+valeur_champ+'">';
        champ_encours.options[0].selected=true;
    }
    
    var champ = document.getElementById(id_champ);
    var scroll_position = champ.scrollTop;
    champ.focus();
    if(typeof document.selection != 'undefined') {
        var range = document.selection.createRange();
        var chaine_select = range.text;
        range.text = balise1 + chaine_select + balise2;

        range = document.selection.createRange();
        if(chaine_select.length == 0) {
            range.move('character', -balise2.length);
        } else {
            range.moveStart('character', balise1.length + chaine_select.length + balise2.length);
        }
        range.select();
    } else if(typeof champ.selectionStart != 'undefined') {
        var curseur_debut = champ.selectionStart;
        var curseur_fin = champ.selectionEnd;
        var chaine_debut = champ.value.substr(0, curseur_debut);
        var chaine_fin = champ.value.substr(curseur_fin);
        var chaine_select = champ.value.substring(curseur_debut, curseur_fin);
        champ.value = chaine_debut + balise1 + chaine_select + balise2 + chaine_fin;

        var curseur_position;
        if (chaine_select.length == 0) {
            curseur_position = curseur_debut + balise1.length;
            champ.selectionStart = curseur_position;
            champ.selectionEnd = curseur_position;
        } else {
            champ.selectionStart = curseur_debut + balise1.length;
            champ.selectionEnd = curseur_debut + balise1.length + chaine_select.length;
        }
    } else {
        champ.value += balise1 + balise2;
    }
    champ.scrollTop = scroll_position;
}

function GereControle(Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
    if (Masquer=='1')
        objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
    else
        objControle.disabled=(objControleur.checked==true)?false:true;
    return true;
}

function balise_masque(id_groupe_balise, button_plus) {
    var groupe = document.getElementById(id_groupe_balise);
    var boutton = document.getElementById(button_plus);
    if(groupe.style.display == 'none') {
        groupe.style.display = 'inline';
        boutton.value = '-';
    } else {
        groupe.style.display = 'none';
        boutton.value = '+';
    }
}

function addChar(newChar, text){
    var curText = document.getElementById(text);
    curText.value += newChar;
    curText.focus();
    return false;
}

function liste(id_input, after) {
    var list = '';
    while((puce = prompt('Entrez le contenu d\'une puce : (cliquez sur Annuler pour arrêter)', '')) != null) {
        list += '<puce>'+puce+'</puce>\n';
    }
    balise(id_input, '<liste'+after+'>\n'+list, '</liste>');
}

function citation(id_input) {
    var citation = prompt('Auteur de la citation :', '');
    if(citation != '' && citation != null) {
        balise(id_input, '<citation auteur="'+citation+'">', '</citation>');
    } else {
        balise(id_input, '<citation auteur="Aucun auteur">', '</citation>');
    }
}

function lien(id_input) {
    var url = prompt('Veuillez entrer l\'adresse de votre lien :', 'http://');
    if(url != '' && url != null && url != 'http://') {
        balise(id_input, '<lien url="'+url+'">', '</lien>');
    }
}
