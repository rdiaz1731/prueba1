<?php
Yii::import("zii.widgets.CMenu");
class GMenu extends CMenu{
    /**
     * Creates the references for the files needed
     */
    public $cssFile;
    private $is_group;
    private $jsDir;
    private $cssDir;
    protected function registerClientScript() {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR
                    . 'js';
        
        $this->jsDir = Yii::app()->getAssetManager()->publish($file);
        
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR
                    . 'css';
        $this->cssDir = Yii::app()->getAssetManager()->publish($file);
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile($this->cssDir.'/gmenu-default.css');
        $cs->registerScriptFile($this->jsDir.'/gmenu.js');
        if($this->cssFile!==null){
            $cs->registerCssFile($this->cssFile);
        }
    }
    
    public function init() {
        $this->is_group=true;
        if($this->htmlOptions==null || !isset($this->htmlOptions['class']) || !preg_match('/(^menu | menu | menu$)/', $this->htmlOptions['class'])){
            if(empty($this->htmlOptions['class']))
                $this->htmlOptions['class']="menu";
            else
                $this->htmlOptions['class'].=" menu";
        }
        /*if($this->itemCssClass==null || !isset($this->itemCssClass) || !preg_match('/(^menu | menu | menu$)/', $this->firstItemCssClass)){
            if(empty($this->itemCssClass))
                $this->itemCssClass="grupo";
            else
                $this->itemCssClass.=" grupo";
        }*/
        $this->registerClientScript();
        parent::init();
    }
    
    public function run() {
        echo CHtml::openTag("div",array("class"=>"gmenu"));
            parent::run();
            echo CHtml::openTag("div",array("id"=>"gmenu-panel"));
                echo CHtml::openTag("div",array("id"=>"gmenu-overlay"));
                echo CHtml::closeTag("div");
            echo CHtml::closeTag("div");
        echo CHtml::closeTag("div");
    }
    /**
    * Recursively renders the menu items.
    * @param array $items the menu items to be rendered recursively
    */
    protected function renderMenuRecursive($items)
    {       
            $len=count($items);
            for($i=0;$i<$len;$i++)
            {
                if($this->is_group){
                    if(!isset($items[$i]['itemOptions']['class']))
                        $items[$i]['itemOptions']['class']="";
                    if(!preg_match('/(^grupo | grupo | grupo$)/', $items[$i]['itemOptions']['class'])){
                        if(empty($items[$i]['itemOptions']['class']))
                            $items[$i]['itemOptions']['class'].='grupo';
                        else
                            $items[$i]['itemOptions']['class'].=' grupo';
                    }
                }
            }
            $this->is_group=false;
            parent::renderMenuRecursive($items);
    }
    
}
?>
