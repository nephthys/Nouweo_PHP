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

function add_ban_ip($ip, $id_modo, $duree, $motif, $motif_admin)
{
    inc_lib('bbcode/parse');
    $motif_admin = parse(insertBD(trim($motif_admin)));
    $motif = insertBD(trim($motif_admin));
    
    Nw::$DB->query("INSERT INTO ".Nw::$prefix_table."ban_ip(ban_ip, ban_id_modo,
        ban_date, ban_date_end, ban_is_end, ban_motif, ban_motif_admin)
        VALUES(".ip2long($ip).", ".intval($id_modo).", NOW(),
        NOW() + ".intval($duree)." DAY, 0, '".$motif."', '".$motif_admin."'");
}
