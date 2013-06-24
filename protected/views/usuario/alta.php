<?php
/* @var $this UsuarioController */
/* @var $model Usuario */

$this->breadcrumbs=array(
	'Usuarios'=>array('index'),
	'Alta Usuario',
);
?>

<h1>Alta Usuario</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>