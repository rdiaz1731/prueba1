<?php
/* @var $this UsuarioController */
/* @var $model Usuario */

$this->breadcrumbs=array(
	'Usuarios'=>array('index'),
	$model->id_usuario,
);

?>

<h1>Ver Usuario #<?php echo $model->id_usuario; ?></h1>

<?php 
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'id_usuario',
			'nombre',
			'email',
			'usuario',
			'password',
		),
	)); 
?>
