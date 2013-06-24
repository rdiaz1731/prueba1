<?php
/* @var $this UsuarioController */
$this->breadcrumbs=array(
	'Usuarios',
);
?>

<?php	/**
	 * Esta parte no esta implementada aun pero debe haber una forma de obtener los permisos 
	 * del usuario y eliminar los botones que no son requeridos se da por hecho que index tendra un permiso
	 * espefifico por lo que un usuario sin permiso index, no podra accesar a index
	 aqui llevaria algo asi
	 if($permiso['alta'])// y asi sucesivamente*/
	$buttons['btnGridAdd']=array(
		'label'=>'Agregar usuario',
		'icon'=>'images/botones/alta.png',
		'redirect'=>'usuario/Alta',
		);
	$buttons['btnGridView']=array(
		'label'=>'Ver detalles',
		'icon'=>'images/botones/ver.png',
		'redirect'=>'usuario/Ver&id={id}',
		);
	$buttons['btnGridEdit']=array(
		'label'=>'Editar',
		'icon'=>'images/botones/editar.png',
		'redirect'=>'usuario/Editar&id={id}',
	);
	$buttons['btnGridDelete']=array(
		'label'=>'Eliminar',
		'icon'=>'images/botones/borrar.png',
		'redirect'=>'usuario/Borrar&id={id}',
		'askToRedirect'=>array(
			'message'=>'Eliminar {model}: <b>{0}</b>',
			'title'=>'Eliminar?',
			'ok_button'=>'Aceptar',   
			'cancel_button'=>'Cancelar',
		),
	);
	$this->widget('ext.ggrid.GGrid', array(
		'title'=>'Usuarios',
		'columns' => array(
			array('id'=>'id_id_usuario','name'=>'Id Usuario','field'=>'id_usuario','resizable'=>'false','sortable'=>'true','cssClass'=>'ggrid-cell-left',),
			array('id'=>'id_nombre','name'=>'Nombre','field'=>'nombre','resizable'=>'false','sortable'=>'true','cssClass'=>'ggrid-cell-left',),
			array('id'=>'id_email','name'=>'Email','field'=>'email','resizable'=>'false','sortable'=>'true','cssClass'=>'ggrid-cell-left',),
			array('id'=>'id_usuario','name'=>'Usuario','field'=>'usuario','resizable'=>'false','sortable'=>'true','cssClass'=>'ggrid-cell-left',),
			array('id'=>'id_password','name'=>'Password','field'=>'password','resizable'=>'false','sortable'=>'true','cssClass'=>'ggrid-cell-left',),
 
		),
		'cssFile'=>Yii::app()->getBaseUrl().'/css/ggrid_custom/ggrid_general.css',
		'field_names'=>array(
			'id_usuario',
			'nombre',
			'email',
			'usuario',
			'password',
		),
		'filters'=>array(
			'primary_attibute'=>array('attribute'=>'id_usuario','label'=>'Buscar {attribute}','filter'=>'string','icon'=>'images/botones/lupa.png'),
			'advanced_filter_attributes'=>array( 
				array('attribute'=>'nombre','label'=>'Buscar {attribute}','filter'=>'string','icon'=>'images/botones/lupa.png'),
				array('attribute'=>'email','label'=>'Buscar {attribute}','filter'=>'string','icon'=>'images/botones/lupa.png'),
				array('attribute'=>'usuario','label'=>'Buscar {attribute}','filter'=>'string','icon'=>'images/botones/lupa.png'),
				array('attribute'=>'password','label'=>'Buscar {attribute}','filter'=>'string','icon'=>'images/botones/lupa.png'),
			 ), 
		),
		'buttons'=>$buttons,
		'model' => 'Usuario',
		'grid_height'=>'300px',
	)); 
?>
