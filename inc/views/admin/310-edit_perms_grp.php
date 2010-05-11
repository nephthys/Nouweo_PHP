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
        if (empty($_GET['id']) || !is_numeric($_GET['id']))
            header('Location: ./');
        
        if(is_logged_in() && check_auth('manage_groups'))
        {
            include(PATH_ROOT.'lang/'.Nw::$site_lang.'/droits.php');
            
            $this->set_tpl('admin/edit_grp_perms.html');
            $this->add_css('forms.css');
            $this->set_title(Nw::$lang['admin']['titre_accueil']);

            inc_lib('admin/get_info_grp');
            $donnees_groupe = get_info_grp($_GET['id']);
            
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['admin']['fa_admin']                  => array('admin.html'),
                Nw::$lang['admin']['fa_grp']                    => array('admin-299.html'),
                $donnees_groupe['g_nom']                        => array('admin-300-'.$_GET['id'].'.html'),
                Nw::$lang['admin']['fa_edit_perms_grp']             => array(''),
            ));
            
            // Liste des droits
            inc_lib('admin/get_xml_droits');
            $all_droit = get_xml_droits();
            $list_droits_cache = array();
            
            
            if(is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$_GET['id'].'.php')) {
                include(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$_GET['id'].'.php');
                $group_values = $group_auth['g'.$_GET['id']];
            }
            else {
                $group_values = array();
            }
            
            
            foreach($all_droit as $section => $list_droit)
            {
                Nw::$tpl->setBlock("section", array(
                    'NOM'   => Nw::$lang['droits']['section_'.$section]
                ));
                foreach($list_droit as $nom_droit => $droit)
                {
                    Nw::$tpl->setBlock("section.droit", array(
                        'TYPE'          => $droit[0],
                        'NOM'           => $nom_droit,
                        'FULLNAME'      => Nw::$lang['droits'][$nom_droit],
                        'VALEUR'        => (isset($group_values[$nom_droit])) ? $group_values[$nom_droit] : '',
                    ));
                    
                    $list_droits_cache[$nom_droit] = array($droit[0]);
                }
            }
            
            // Formulaire soumis
            if (isset($_POST['submit']))
            {
                inc_lib('admin/edit_auth_grp');
                inc_lib('admin/new_grp_auth_cache');
                
                if(is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$_GET['id'].'.php')) {
                    @unlink(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$_GET['id'].'.php');
                }
                        
                $start_cache_file = '<?php'."\r".' $group_auth[\'g'.$_GET['id'].'\'] = array( '."\r";
                        
                foreach($list_droits_cache AS $nom_droit => $donnees_droit)
                {
                    if ($donnees_droit[0] == 1) {
                        $value_droit = (isset($_POST['prm_'.$nom_droit])) ? 1 : 0;
                    } else {
                        $value_droit = '\''.intval($_POST['prm_'.$nom_droit]).'\'';
                    }
                    
                    $value_droit_cache = (isset($group_values[$nom_droit])) ? $group_values[$nom_droit] : '';
                    
                    // Édition en BDD si nécessaire.
                    if($value_droit != $value_droit_cache || !in_array($nom_droit, $group_values))
                    {
                        edit_auth_grp($_GET['id'], $nom_droit, $value_droit);
                    }
                        
                    $start_cache_file .= "\t".'\''.$nom_droit.'\' => '.$value_droit.', '."\r";
                }
                
                $start_cache_file .= "\r".');'."\r".'?>';
                
                new_grp_auth_cache($_GET['id'], $start_cache_file);
                redir(Nw::$lang['admin']['redir_modif_droits'], true, 'admin-310-'.$_GET['id'].'.html');
            }
        
            
            Nw::$tpl->set(array(
                'ID'            => $_GET['id'],
                'NOM_GRP'       => $donnees_groupe['g_nom'],
            ));
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, './');
    }
}


/*  *EOF*   */
