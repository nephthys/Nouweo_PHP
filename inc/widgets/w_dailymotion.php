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

class w_dailymotion extends widget_base {
    
    private $width = 480;
    private $height = 381;
    
    private $non_optionnal_vars = array('id_video');
    
    public function render()
    {
        if(!parent::checkArgs($this->non_optionnal_vars))
            return '';
        
        $this->checkSize();
        
        Nw::$tpl->set(array(
                            strtoupper(__CLASS__).'_WIDTH'  => $this->width,
                            strtoupper(__CLASS__).'_HEIGHT' => $this->height,
                            strtoupper(__CLASS__).'_ID' => $this->args['id_video'],
                        )
                    );
        
        return Nw::$tpl->pparse('widgets/'.__CLASS__.'.html');
    }
    
    private function checkSize()
    {
        $this->width = (!empty($this->args['width']) AND is_numeric($this->args['width']) AND $this->args['width'] > 0) ? (int) $this->args['width'] : $this->width;
        $this->height = (!empty($this->args['height']) AND is_numeric($this->args['height']) AND $this->args['height'] > 0) ? (int) $this->args['height'] : $this->height;
    }
}
