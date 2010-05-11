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

class w_live extends widget_base
{
    private $width = 0;
    private $height = 0;
    private $size = '';
    private $non_optionnal_vars = array('id_live');
    
    public function render()
    {
        if(!parent::checkArgs($this->non_optionnal_vars))
            return '';
            
        inc_lib('widgets/get_list_live_messages');
        
        $module = (isset($_GET['p'])) ? $_GET['p'] : 'news';
        $page_actuelle = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], $module));
        $limit_msg = (isset($this->args['limit'])) ? intval($this->args['limit']) : 0;
        $big_widget = false;
        
        if (!is_file(PATH_ROOT.Nw::$assets['dir_cache'].'widgets/data/'.Nw::$site_lang.'.w_live.'.intval($this->args['id_live']).'.php'))
        {
            return Nw::$lang['widgets']['widget_dont_exist'];
        }
        else
        {
            include_once(PATH_ROOT.Nw::$assets['dir_cache'].'widgets/data/'.Nw::$site_lang.'.w_live.'.intval($this->args['id_live']).'.php');
            $donnees_widget = $dn_widget[$this->args['id_live']];
            
            if(isset($this->args['size']) && $this->args['size'] == 'full')
                $big_widget = true;
            else
                if(!isset($this->args['limit']))
                    $limit_msg = 4;
            
            $all_posts = get_list_live_messages($this->args['id_live'], $limit_msg);
            
            foreach($all_posts AS $donnees)
            {
                Nw::$tpl->setBlock('posts', array(
                    'ID'            => $donnees['post_id'],
                    'AUTEUR_ID'     => $donnees['u_id'],
                    'AUTEUR_PSEUDO' => $donnees['u_pseudo'],
                    'AUTEUR_ALIAS'  => $donnees['u_alias'],
                    'AUTEUR_AVATAR' => $donnees['u_avatar'],
                    'DATE'          => date_sql($donnees['date'], $donnees['heures_date'], $donnees['jours_date'], true),
                    'CONTENU'       => $donnees['post_contenu'],
                ));
            }
            
            inc_lib('widgets/get_list_live_parts');
            $all_participants = get_list_live_parts($this->args['id_live']);
            $id_parts = array();
            
            foreach($all_participants AS $donnees)
            {
                Nw::$tpl->setBlock('parts', array(
                    'AUTEUR_ID'     => $donnees['u_id'],
                    'AUTEUR_PSEUDO' => $donnees['u_pseudo'],
                    'AUTEUR_ALIAS'  => $donnees['u_alias'],
                    'AUTEUR_AVATAR' => $donnees['u_avatar'],
                ));
                
                $id_parts[] = $donnees['u_id'];
            }
            
            // Modif des paramètres du widget
            if(isset($_POST['w_live_submit_edit']) && is_logged_in() && in_array(Nw::$dn_mbr['u_id'], $id_parts))
            {
                inc_lib('widgets/edit_infos_live');
                edit_infos_live($this->args['id_live']);
                redir(Nw::$lang['widgets']['w_live_edit_ok'], true, $page_actuelle);
            }
            
            Nw::$tpl->set(array(
                '_ASSETS_'                      => Nw::$assets,
                '_PAGE_ACTUELLE_'               => $page_actuelle,
                '_DESIGN_'                      => 1,
                'IS_LOGGED_IN'                  => is_logged_in(),
                'LANG'                          => Nw::$lang,
                'USER'                          => Nw::$dn_mbr,
                'PREF'                          => Nw::$pref,
                'FULL_AFFICHAGE'                => $big_widget,
                strtoupper(__CLASS__).'_SIZE'   => (isset($this->args['size'])) ? $this->args['size'] : 0,
                strtoupper(__CLASS__).'_HEIGHT' => (isset($this->args['height'])) ? $this->args['height'] : 0,
                strtoupper(__CLASS__).'_IMG'    => (isset($this->args['img'])) ? htmlentities($this->args['img']) : '',
                strtoupper(__CLASS__).'_LIMIT'  => $limit_msg,
                strtoupper(__CLASS__).'_PARTS'  => $id_parts,
                strtoupper(__CLASS__).'_TITLE'  => $donnees_widget['title'],
                strtoupper(__CLASS__).'_OPEN'   => $donnees_widget['open'],
                strtoupper(__CLASS__).'_ID'     => $this->args['id_live'],
            ));
            
            return Nw::$tpl->pparse('widgets/'. __CLASS__ .'.html');
        }
    }
}
