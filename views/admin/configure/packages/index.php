<?php
theme::header_start('Packages','Package manager');
Plugin_search_sort::field();
//theme::header_button('Customize Load Order',$controller_path.'/load-order','sort-amount-asc');
o::view_event($controller_path,'header.buttons');
theme::header_end();

/* display errors */
if ($errors) {
	echo '<div class="alert alert-danger" role="alert">';
	echo '<b>We have a problem!</b><br>';
	echo $errors.'<br>';
	echo 'This needs to be fixed in order for packages to be dynamically loaded.';
	echo '</div>';
}

theme::table_start(['Name','Type'=>'text-center','Description','Version'=>'text-center','Actions'=>'text-center'],['tbody_class'=>'searchable','class'=>'sortable'],$records);

//kd($records);

foreach ($records as $name=>$record) {
	/* Name */
	theme::table_start_tr();
	echo '<span style="';
	echo ($record['is_active']) ? 'font-weight: 700">' : '">';
	o::e($record['name']);
	echo '</span>';

	/* type */
	theme::table_row('text-center');
	echo '<a href="'.$controller_path.'/search/'.$record['type'].'">';
	echo '<span class="label label-'.$type_map[$record['type']].'">'.$record['type'].'</span>';
	echo '</a>';

	/* Description */
	theme::table_row();
	o::e($record['description']);
	echo ' <a href="'.$controller_path.'/details/'.$record['url_name'].'"><i class="fa fa-info-circle"></i></a> ';

	/* Version */
	theme::table_row('text-center');
	/* show upgrade version and up arrow? */
	switch ($record['version_display']) {
	case 0:
		echo '<span class="label label-primary">'.$record['composer_version'].'</span> ';
	break;
	case 2:
		/* version in db matches migration version */
		echo '<span class="label label-primary">'.$record['migration_version'].'</span> ';
	break;
	case 3: /* greater than */
		echo '<span class="label label-info"> <i class="fa fa-arrow-up"></i>'.$record['composer_version'].'</span>&nbsp;';
		echo '<span class="label label-primary">'.$record['migration_version'].'</span> ';
	break;
	case 4: /* less than */
		echo '<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> '.$record['composer_version'].'</span>&nbsp;';
		echo '<span class="label label-primary">'.$record['migration_version'].'</span> ';
	break;
	default:
		echo '<span class="label label-default">'.$record['composer_version'].'</span> ';
	}

	/* Actions */
	theme::table_row('text-center');
	echo '<a href="'.$this->controller_path.'/install/'.$record['url_name'].'" class="btn btn-xs btn-default">install</a> ';

	theme::table_end_tr();
}

theme::table_end();

theme::return_to_top();