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
        if(is_logged_in() && check_auth('edit_vars_lang'))
        {
            $this->set_tpl('admin/edit_vars_lang.html');
            $this->add_css('forms.css');
            $this->set_title(Nw::$lang['admin']['edit_vars_lang']);
            $this->set_filAriane(array(
                Nw::$lang['admin']['fa_admin']          => array('admin.html'),
                Nw::$lang['admin']['edit_vars_lang']    => array('')
            ));
            
            //Récupération de tous les fichiers de langue
            $list_files = array();
            $list_vars = array();
            $dir = opendir(PATH_ROOT.'lang/'.Nw::$site_lang);
            while($file = readdir($dir))
            {
                if(strpos($file, '.php'))
                {
                    Nw::$tpl->setBlock('files', array(
                        'FILENAME' => $file,
                    ));
                    $list_files[] = $file;
                }
            }
            closedir($dir);
            
            //Si on a envoyé un fichier particulier à voir
            if(!empty($_GET['file']) && !empty($_GET['lang']))
            {
                if(in_array($_GET['file'], $list_files) && 
                    array_key_exists($_GET['lang'], Nw::$lang['common']['languages']))
                {
                    $this->load_lang_file(str_replace('.php', '', $_GET['file']), $_GET['lang']);
                    $list_vars = Nw::$lang[str_replace('.php', '', $_GET['file'])];
                    foreach($list_vars as $key => $value)
                    {
                        Nw::$tpl->setBlock('vars', array(
                            'KEY' => $key, 
                            'VALUE' => is_array($value) ? array_map('htmlspecialchars', $value) : htmlspecialchars($value),
                        ));
                    }
                    Nw::$tpl->set('TEXT_LEGEND', sprintf(Nw::$lang['admin']['edit_vars_file'], $_GET['file']));
                    Nw::$tpl->set('DISPLAY_FORM', true);
                    Nw::$tpl->set('FILE', $_GET['file']);
                    Nw::$tpl->set('LANG', $_GET['lang']);
                }
                else
                    Nw::$tpl->set('DISPLAY_FORM', false);
            }
            
            //Si on veut éditer un fichier
            if(isset($_POST['edit_vars']) && !empty($_GET['file']) && !empty($_GET['lang']))
            {
                $f = PATH_ROOT.'lang/'.$_GET['lang'].'/'.$_GET['file'];
                $content = file_get_contents($f);
                foreach($list_vars as $key => $value)
                {
                    if(isset($_POST[$key]) && !empty($_POST[$key]))
                    {
                        $value = str_replace('\'', '\\\'', $_POST[$key]);
                        $content = preg_replace('`\''.$key.'\'(\s*)=>(\s*)\'(.*)\',`sU', 
                            '\''.$key.'\'$1=>$2\''.$value.'\',', $content);
                    }
                    else
                        redir(sprintf(Nw::$lang['admin']['error_var_empty'], $key), false, 'admin-1.html?file='.$_GET['file'].'&lang='.$_GET['lang']);
                }
                //echo htmlspecialchars($content);
                file_put_contents($f, $content);
                redir(sprintf(Nw::$lang['admin']['redir_vars_lang'], $key), true, 'admin-1.html?file='.$_GET['file'].'&lang='.$_GET['lang']);
            }
        }
        else
            redir(Nw::$lang['admin']['error_cant_edit_vars'], false, Nw::$site_url);
    }
}


/*  *EOF*   */
