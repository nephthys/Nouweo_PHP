<?php
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

class Page extends Core
{
    protected function main()
    {
        if(is_logged_in() && check_auth('manage_groups'))
        {       
            // Edition d'un groupe
            if (!empty($_GET['id']) && is_numeric($_GET['id']))
            {
                // On cherche les infos du groupe
                inc_lib('admin/get_info_grp');
                $donnees_groupe = get_info_grp($_GET['id']);
                
                $form_id            = $_GET['id'];
                $form_name          = $donnees_groupe['g_nom'];
                $form_title         = $donnees_groupe['g_titre'];
                $form_icone         = $donnees_groupe['g_icone'];
                $form_color         = $donnees_groupe['g_couleur'];
                
                // Fil ariane
                $this->set_filAriane(array(
                    Nw::$lang['admin']['fa_admin']                  => array('admin.html'),
                    Nw::$lang['admin']['fa_grp']                    => array('admin-299.html'),
                    $donnees_groupe['g_nom']                        => array('admin-300-'.$_GET['id'].'.html'),
                    Nw::$lang['admin']['fa_edit_grp']               => array(''),
                ));
            }
            // Création d'un groupe
            else
            {
                $form_id            = 0;
                $form_name          = '';
                $form_title         = '';
                $form_icone             = 0;
                $form_color         = 0;
                
                // Fil ariane
                $this->set_filAriane(array(
                    Nw::$lang['admin']['fa_admin']                  => array('admin.html'),
                    Nw::$lang['admin']['fa_grp']                    => array('admin-299.html'),
                    Nw::$lang['admin']['fa_new_grp']                => array(''),
                ));
            }
            
            $this->set_tpl('admin/edit_grp.html');
            $this->add_css('forms.css');
            $this->set_title(Nw::$lang['admin']['titre_accueil']);
            
            // Formulaire soumis
            if (isset($_POST['submit']))
            {
                $array_post = array(    
                    'nom'               => $_POST['nom'],
                    'titre'             => $_POST['titre'],
                    'icone'             => $_POST['icone'],
                    'couleur'           => (isset($_POST['couleur'])) ? 1 : 0,
                );
                
                // Les champs titre & contenu ne sont pas vides
                if (!multi_empty(trim($_POST['nom'])))
                {
                    // Edition d'un groupe
                    if (!empty($_GET['id']) && is_numeric($_GET['id']))
                    {
                        inc_lib('admin/edit_grp');
                        edit_grp($_GET['id']);
                        redir(Nw::$lang['admin']['confirm_edit_grp'], true, 'admin-300-'.$_GET['id'].'.html');
                    }
                    // Création d'un nouveau groupe
                    else
                    {
                        inc_lib('admin/add_grp');
                        $id_new_grp = add_grp();
                        redir(Nw::$lang['admin']['confirm_new_grp'], true, 'admin-310-'.$id_new_grp.'.html');
                    }
                }
                else
                    display_form($array_post, Nw::$lang['admin']['nom_grp_obligatoire']); return;
            }
            

            // On affiche le template
            display_form(array( 
                    'id'                => $form_id,
                    'nom'               => $form_name,
                    'titre'             => $form_title,
                    'icone'             => $form_icone,
                    'couleur'           => $form_color,
                )
            );
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, './');
    }
}


/*  *EOF*   */
