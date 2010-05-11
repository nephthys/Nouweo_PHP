<?php
function track_externs($type)
{
	$id_membre_tracker 	= (is_logged_in()) ? intval(Nw::$dn_mbr['u_id']) : 0;
	$ip_membre_tracker  = get_ip();
	$nb_clics_tracker 	= 1;
	$referer			= (isset($_SERVER['HTTP_REFERER'])) ? explode('/', $_SERVER['HTTP_REFERER']) : array();
	$referer_domain		= (count($referer) > 0) ? $referer[2] : '';
	
	$clause_where 		= (is_logged_in()) ? 't_id_membre = '.intval(Nw::$dn_mbr['u_id']) : 't_ip = \''.$ip_membre_tracker.'\'';
	
	$query = Nw::$DB->query('SELECT COUNT(*) as count, t_nb_clics, r_referer
	FROM '.Nw::$prefix_table.'extern_tracker
	WHERE '.$clause_where.' AND t_type = \''.insertBD($type).'\' GROUP BY t_id') OR Nw::$DB->trigger(__LINE__, __FILE__);
	$dn = $query->fetch_assoc();
	
	if ($dn['count'] > 0)
	{
		$nb_clics_tracker = $dn['t_nb_clics']+1;
	}
	
	Nw::$DB->query('REPLACE INTO '.Nw::$prefix_table.'extern_tracker (t_id_membre, t_type, t_date, t_ip, t_nb_clics, r_referer)
		VALUES ('.$id_membre_tracker.', \''.insertBD($type).'\', NOW(), \''.$ip_membre_tracker.'\', '.$nb_clics_tracker.', \''.insertBD($referer_domain).'\')')
	OR Nw::$DB->trigger(__LINE__, __FILE__);
}