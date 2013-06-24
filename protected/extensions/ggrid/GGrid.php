<?php
Yii::import('CWidget');
class GGrid extends CWidget{
    public $columns=array();
    public $cssFile;
    public $model;
    public $field_names=array();
    /**
     * arreglo que especifica las opciones para slickgrid
     * por default su valor es:
     * 'grid_options'=>array(
     *           'multiSelect'=>'false',
     *           'enableColumnReorder'=>'false',
     *           'editable'=>'false',
     *           'enableCellNavigation'=>'false',
     *           'asyncEditorLoading'=>'false',
     *           'forceFitColumns'=>'true',
     *           'topPanelHeight'=>'20',
     *       );
     * @var array
     */
    public $grid_options;
    /**
     * Especifique los filtros que estaran disponibles para la busqueda
     * la estructura de este arreglo debe ser la siguiente:
     * 
     * 'filters'=>array(
     *  'primary_attibute'=>array('attribute'=>'nombre','label'=>'Buscar usuario','filter'=>'String',),
     *  'advanced_filter_attributes'=>array(
     *      array('attribute'=>'usuario','label'=>'Nombre de usuario','filter'=>'string'),
     *      array('attribute'=>'email','label'=>'Correo electronico','filter'=>'string'),
     *  ),
     * )
     * donde primary attribute se refiere al filtro de busqueda por default
     * esta compuesto por un arreglo de tres llaves, 
     *  - attribute es el nombre del campo en la BD, debe existir dentro de field_names
     *  - label es la etiqueta con la que se mostrara el filtro
     *  - filter no es obligatorio, pero es para seleccionar el tipo de filtro, por default sera string
     *    lo ideal seria tener varios tipos de filtro, pero no los diseño yo y solo vienen por string, y por slide que es una barrita
     * 
     * advanced_filter_attributes es un conjunto de arreglos como el de primary_attribute pero estos estaran ocultos por default
     * y apareceran al seleccionar el link de busqueda avanzada
     * 
     * @var array
     * NOTA: esto aparentemente podria no ser necesario se puede obtener desde el modelo apartir de la funcion search
     */
    public $filters;
    /**
     * espeifica el largo del grid
     * @var string
     */
    public $grid_height;
    /**
     * especifica las caracteristicas del boton de agregar
     * 'add_button'=>array(
     *      'label'=>'Nuevo',
     *      'icon'=>'images/icon.png',
     *      'add_function'=>'function add(id){
     *         location="localhost/index/$modeloController/create";
     *      }',
     * )
     * @var array
     */
    public $buttons;
    public $title;
    private $jsDir;
    private $cssDir;
    private $script;
    
    
    /**
     * Creates the references for the files needed
     */
    protected function registerClientScript() {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR
                    . 'js' . DIRECTORY_SEPARATOR . 'slickgrid';
        
        $this->jsDir = Yii::app()->getAssetManager()->publish($file);
        
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR
                    . 'css';
        
        $this->cssDir = Yii::app()->getAssetManager()->publish($file);
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');
         
        if($this->cssFile!==null){
            $cs->registerCssFile($this->cssFile);
        }
        $cs->registerCssFile($this->cssDir.'/slick.grid.css');
        $cs->registerCssFile($this->cssDir.'/slick.pager.css');
        $cs->registerCssFile('css/erp-gisep/jquery-ui-1.10.3.custom.min.css');
        
        $cs->registerScriptFile($this->jsDir.'/lib/jquery.event.drag-2.2.js');
        
        $cs->registerScriptFile($this->jsDir.'/slick.core.js');
        $cs->registerScriptFile($this->jsDir.'/slick.formatters.js');
        $cs->registerScriptFile($this->jsDir.'/slick.editors.js');
        $cs->registerScriptFile($this->jsDir.'/plugins/slick.rowselectionmodel.js');
        $cs->registerScriptFile($this->jsDir.'/slick.grid.js');
        $cs->registerScriptFile($this->jsDir.'/slick.dataview.js');
        $cs->registerScriptFile($this->jsDir.'/controls/slick.pager.js');
        $cs->registerScriptFile($this->jsDir.'/controls/slick.columnpicker.js');
        $cs->registerScript("slick_grid_script",  $this->script);
    }
    
    public function run() {
        $model=  $this->model;
        echo '<div class="ggrid-background">
            <div class="options-panel">
                <div class="ggrid-actions">
                    <div class="ggrid-title">
                        <h3>'.$this->title.'</h3>
                    </div>
                    ';
        if(isset($this->filters['primary_attibute']) && 
           in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
            $this->printFilterHTML($this->filters['primary_attibute'], true);
        }else{
            throw new CException("El campo de busqueda {$this->filters['primary_attibute']['attribute']} no existe en la tabla.");
        }
        if(is_array($this->filters['advanced_filter_attributes'])){
            echo '<div id="ggrid-filter-options"><a class="busqueda-boton"><div><img src="images/botones/buscar.png"/></div><div id="ggrid-button-label">B&uacute;squeda avanzada</div></a></div>';
        }
        echo '
                    <div class="ggrid-buttons">
                        ';
        foreach($this->buttons as $id=>$button){
            echo '<a id="'.$id.'" class="control-boton">';
            if(isset($button['icon']))
                echo '<div><img src="'.$button['icon'].'"/></div>';
            echo '<div id="ggrid-button-label">'.$button['label'].'</div></a>';
        }
        echo '</div>
            </div>';
        echo '</div>
            </div>';
        echo '<div class="ggrid-filter-background">';
        if(is_array($this->filters['advanced_filter_attributes'])){
            echo '<div class="ggrid-filter-panel">
                ';
            foreach($this->filters['advanced_filter_attributes'] as $filtro){
                if(in_array($filtro['attribute'], $this->field_names)){
                    $this->printFilterHTML($filtro); 
                }else{
                    throw new CException("El campo de busqueda {$filtro['attribute']} no existe en la tabla.");
                }
            }
            echo '</div>
                ';
        }
        echo '</div>';
        echo '
        <div class="slickgrid-container">
            
            <div class="ggrid-data-container">
                <div class="grid-header">
                  <label>'.$model::getTitle().'</label>
                </div>
                <div id="gisep-grid" style="height:'.$this->grid_height.';"></div>
                <div id="pager" style="height:20px;margin-bottom:20px;"></div>
            </div>
        </div>
        <div id="ggrid-message-dialog" style="display:none;">
        </div>
';
    }
    public function init() {
        
        if($this->grid_options===null){
            $this->grid_options=array(
                'multiSelect'=>'false',
                'enableColumnReorder'=>'false',
                'editable'=>'false',
                'enableCellNavigation'=>'false',
                'asyncEditorLoading'=>'false',
                'forceFitColumns'=>'true',
                'topPanelHeight'=>'20',
            );
        }
        
        $this->script = '
            var dataView;
            var grid;
            var data = [];
            var columns = [';
        $count= count($this->columns);
        foreach($this->columns as $column){
            if(!is_array($column))
                continue;
            $this->script .= "{ ";
            $count2=count($column);
            foreach($column as $key=>$val){
                if(strcmp($key, "editor")==0 || strcmp($key,"formatter")==0)
                    $this->script.="$key: $val";
                else
                    $this->script.="$key: \"$val\"";
                if(--$count2!=0){
                    $this->script.=", ";
                }
            }
            $this->script .= " } ";
            if(--$count!=0){
                $this->script.=", ";
            }
        }
        $this->script.=' ];

        var options = {';
        $count3 = count($this->grid_options);
        foreach($this->grid_options as $key=>$val){
                $this->script.="$key: $val";
            if(--$count3!=0){
                $this->script.=" , ";
            }
        }
        $this->script.=' };
            var sortcol = "title";
            var sortdir = 1;
            ';
        if(isset($this->filters['primary_attibute']) && 
           in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
            $this->script.= $this->getFilterJSVarName($this->filters['primary_attibute'], true, true)."\n";
        }
            
        foreach($this->filters['advanced_filter_attributes'] as $filtro){
            if(in_array($filtro['attribute'], $this->field_names)){
                $this->script.= $this->getFilterJSVarName($filtro, true)."\n";
            }
        }
        $this->script.= 'function requiredFieldValidator(value) {
              if (value == null || value == undefined || !value.length) {
                return {valid: false, msg: "This is a required field"};
              }
              else {
                return {valid: true, msg: null};
              }
            }

            function percentCompleteSort(a, b) {
              return a["percentComplete"] - b["percentComplete"];
            }

            function comparer(a, b) {
              var x = a[sortcol], y = b[sortcol];
              return (x == y ? 0 : (x > y ? 1 : -1));
            }
            function myFilter(item, args) {';
              
                if(isset($this->filters['primary_attibute']) && 
                   in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
                    $this->script.= $this->getFilterFilterOptions($this->filters['primary_attibute'], true)."\n";
                }
            
                foreach($this->filters['advanced_filter_attributes'] as $filtro){
                    if(in_array($filtro['attribute'], $this->field_names)){
                        $this->script.= $this->getFilterFilterOptions($filtro)."\n";
                    }
                }
                $this->script.='return true;
              }
        jQuery("document").ready(function ($) {
        
          data=[';
        $model = $this->model;
        $data = $model::model()->findAll();
        $count4 = count($data);
        foreach ($data as $item){
            $this->script.=' { ';
              // prepare the data
            foreach($this->field_names as $attribute){
                $this->script.="{$attribute} : '{$item->$attribute}', ";
              }
              $this->script.= "id : 'row_id_".$item->getPrimaryKey()."'";
              $this->script.=' } ';
              if(--$count4!=0){
                $this->script.=', '; 
              }
        }
        $this->script.='];

          dataView = new Slick.Data.DataView({ inlineFilters: true });
          grid = new Slick.Grid("#gisep-grid", dataView, columns, options);
          grid.setSelectionModel(new Slick.RowSelectionModel());

          var pager = new Slick.Controls.Pager(dataView, grid, $("#pager"));
          
          $(".grid-header .ui-icon")
                .addClass("ui-state-default ui-corner-all")
                .mouseover(function (e) {
                  $(e.target).addClass("ui-state-hover")
                })
                .mouseout(function (e) {
                  $(e.target).removeClass("ui-state-hover")
                });
          grid.onCellChange.subscribe(function (e, args) {
            dataView.updateItem(args.item.id, args.item);
          });

          grid.onAddNewRow.subscribe(function (e, args) {
            var item = {"num": data.length, "id": "new_" + (Math.round(Math.random() * 10000)), "title": "New task", "duration": "1 day", "percentComplete": 0, "start": "01/01/2009", "finish": "01/01/2009", "effortDriven": false};
            $.extend(item, args.item);
            dataView.addItem(item);
          });

          //Codigo agregado por Luismi
          //Marca la fila como seleccionada
          grid.onClick.subscribe(function(e){
                e.preventDefault();
                var cell   = grid.getCellFromEvent(e);  // get the cell
                var row    = cell.row;  // get the row\'s index (this value will change on filter/sort)
                rows=[];
                rows.push(row);
                grid.setSelectedRows(rows);
                e.preventDefault();
          });

          grid.onSort.subscribe(function (e, args) {
            sortdir = args.sortAsc ? 1 : -1;
            sortcol = args.sortCol.field;

            if ($.browser.msie && $.browser.version <= 8) {
              // using temporary Object.prototype.toString override
              // more limited and does lexicographic sort only by default, but can be much faster

              var percentCompleteValueFn = function () {
                var val = this["percentComplete"];
                if (val < 10) {
                  return "00" + val;
                } else if (val < 100) {
                  return "0" + val;
                } else {
                  return val;
                }
              };

              // use numeric sort of % and lexicographic for everything else
              dataView.fastSort((sortcol == "percentComplete") ? percentCompleteValueFn : sortcol, args.sortAsc);
            } else {
              // using native sort with comparer
              // preferred method but can be very slow in IE with huge datasets
              dataView.sort(comparer, args.sortAsc);
            }
          });

          // wire up model events to drive the grid
          dataView.onRowCountChanged.subscribe(function (e, args) {
            grid.updateRowCount();
            grid.render();
          });

          dataView.onRowsChanged.subscribe(function (e, args) {
            grid.invalidateRows(args.rows);
            grid.render();
          });

          var h_runfilters = null;
          $("#ggrid-filter-options").on("click",function(){
              $(".ggrid-filter-panel").slideToggle();
          });
            ';
                foreach($this->buttons as $id=>$button){
                   $this->script.=' 
                  $("#'.$id.'").click(function(e){
                      e.preventDefault();
                      ';
                      if(!empty($this->buttons['not_found_message'])){
                          $notFound=$this->buttons['not_found_message'];
                      }else{
                          $notFound="Primero seleccione un registro";
                      }
                      if(strpos($button['redirect'],'{id}') !== false){
                        $this->script.='var row    = grid.getSelectedRows();  // get the row\'s index (this value will change on filter/sort)
                        if(row==""){
                            $("#ggrid-message-dialog")
                              .html("'.$notFound.'")
                              .dialog("option", {
                              title: "Alerta",
                              buttons: [ 
                              { text: "Aceptar", click: function() { $( this ).dialog( "close" ); } }
                                  ]})
                              .dialog("open");
                            return;
                        }
                        var item   = dataView.getItem(row);  // get the row\'s item (see: object, data)
                        var msc    = item[grid.getColumns()[0].field];  // get the value of the first cell
                        ';
                      }
                      if(!empty($button['askToRedirect'])){
                          $askToRedirect=$button['askToRedirect'];
                          $title="Mensaje";
                          $okButton="Ok";
                          $cancelButton = "Cancel";
                          if(!empty($askToRedirect['title']))
                              $title=$askToRedirect['title'];
                          if(!empty($askToRedirect['ok_button']))
                              $okButton=$askToRedirect['ok_button'];
                          if(!empty($askToRedirect['cancel_button']))
                              $cancelButton=$askToRedirect['cancel_button'];
                          $message=  str_replace("{model}", strtolower($model), $askToRedirect['message']);
                          $columns = count($this->columns);
                          for($i=0;$i<$columns;$i++){
                            $message=  str_replace('{'.$i.'}', '"+item[grid.getColumns()['.$i.'].field]+"', $message);
                          }
                          $this->script.='$("#ggrid-message-dialog")
                              .html("'.$message.'")
                              .dialog("option", {
                              title: "'.$title.'",
                              buttons: [ 
                              { text: "'.$okButton.'", click: function() { location= "?r='.str_replace("{id}", '"+(item.id+"").replace("row_id_","")+"', $button['redirect']).'"; } },
                              { text: "'.$cancelButton.'", click: function() { $( this ).dialog( "close" ); } }
                                  ]})
                              .dialog("open");';
                      }else{
                      //alert("Row Index:"+row+", RowID:"+item.id+", Cell Value:"+msc);
                        $this->script.='location= "?r='.str_replace("{id}", '"+(item.id+"").replace("row_id_","")+"', $button['redirect']).'";';
                      }
                      $this->script.=' 
                  });
                  ';
                }
            
                if(isset($this->filters['primary_attibute']) && 
                   in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
                    $this->script.= $this->getFilterControls($this->filters['primary_attibute'], true)."\n";
                }
                foreach($this->filters['advanced_filter_attributes'] as $filtro){
                    if(in_array($filtro['attribute'], $this->field_names)){
                        $this->script.= $this->getFilterControls($filtro)."\n";
                    }
                }
            $this->script.='
            function updateFilter() {
              dataView.setFilterArgs({';
                $comma=1;
                if(isset($this->filters['primary_attibute']) && 
                   in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
                    $this->script.= $this->getFilterJSVarName($this->filters['primary_attibute'],false, true).":".$this->getFilterJSVarName($this->filters['primary_attibute'],false, true);
                    $comma=0;
                }
                foreach($this->filters['advanced_filter_attributes'] as $filtro){
                    if($comma==0)
                         $this->script.= ',';
                    else{
                        $comma=0;
                    }
                    if(in_array($filtro['attribute'], $this->field_names)){
                        $this->script.= $this->getFilterJSVarName($filtro, false).":".$this->getFilterJSVarName($filtro, false);
                    }
                }
            $this->script.='
              });
              dataView.refresh();
            }

            // initialize the model after all the events have been hooked up
            dataView.beginUpdate();
            dataView.setItems(data);
            dataView.setFilterArgs({';
                if($comma!=1)
                    $comma=1;
                if(isset($this->filters['primary_attibute']) && 
                   in_array($this->filters['primary_attibute']['attribute'], $this->field_names)){
                    $this->script.= $this->getFilterJSVarName($this->filters['primary_attibute'],false, true).":".$this->getFilterJSVarName($this->filters['primary_attibute'],false, true);
                    $comma=0;
                }
                foreach($this->filters['advanced_filter_attributes'] as $filtro){
                    if($comma==0)
                         $this->script.= ',';
                    else{
                        $comma=0;
                    }
                    if(in_array($filtro['attribute'], $this->field_names)){
                        $this->script.= $this->getFilterJSVarName($filtro, false).":".$this->getFilterJSVarName($filtro, false);
                    }
                }
            $this->script.='});
            dataView.setFilter(myFilter);
            dataView.endUpdate();

            // if you don\'t want the items that are not visible (due to being filtered out
            // or being on a different page) to stay selected, pass "false" to the second arg
            dataView.syncGridSelection(grid, true);

            $("#gridContainer").resizable();
            $("#ggrid-message-dialog").dialog({
                modal:true,
                autoOpen:false,
                title:"Mensaje"
            });
        });';
        $this->registerClientScript();
    }
    
    private function printFilterHTML($filter,$primary=false){
        if($primary)
            echo '<div class="ggrid-primary-filter">
                ';
        else
            echo '<div class="ggrid-filter-item">
                ';
        echo '
            <label>'.str_replace("{attribute}", strtolower($filter['attribute']), $filter['label']).'</label>
                ';
        if(!isset($filter['filter']) || empty($filter['filter']) || strcmp($filter['filter'],'string')==0)
                    echo '<input type=text id="txt_'.$filter['attribute'].'" name="ggrid-Filter[]">';
        else if(strcmp($filter['filter'],'slider')){
            echo '<div id="slide_'.$filter['attribute'].'"></div>';
        }
        if(isset($filter['icon']))
            echo '<img src="'.$filter['icon'].'"/>';
        echo '</div>
            ';
    }
    private function getFilterJSVarName($filter,$definition,$primary=false){
        if($definition){
            $def='var ';
        }else{
            $def='';
        }
        if($primary){
            $type="Primary";
        }else{
            $type="";
        }
        $end='';
        if(!isset($filter['filter']) || 
               empty($filter['filter']) || 
               strcmp($filter['filter'],'string')==0){
                if($definition)
                    $end=' = ""';
              return $def.$filter['attribute'].$type.'String'.$end;  
              
            }else if(strcmp($filter['filter'],'slider')){
                if($definition)
                    $end=' = 0;';
              return $def.$filter['attribute'].$type.'Slider'.$end;
            }
    }
    private function getFilterFilterOptions($filter,$primary=false){
        if(!isset($filter['filter']) || empty($filter['filter']) || strcmp($filter['filter'],'string')==0)
            return 'if (args.'.$this->getFilterJSVarName($filter, false, $primary).' 
                != "" && item["'.$filter['attribute'].'"].toLowerCase().indexOf(args.'.$this->getFilterJSVarName($filter, false, $primary).'.toLowerCase()) == -1) {
            return false;
          }';
        else if(strcmp($filter['filter'],'slider')){
            return 'if (item["'.$filter['attribute'].'"] < args.'.$this->getFilterJSVarName($filter, false, $primary).') {
            return false;
          }';
        }
    }
    
    private function getFilterControls($filter,$primary=false){
        if(!isset($filter['filter']) || empty($filter['filter']) || strcmp($filter['filter'],'string')==0)
            return '$("#txt_'.$filter['attribute'].'").keyup(function (e) {
              Slick.GlobalEditorLock.cancelCurrentEdit();

              // clear on Esc
              if (e.which == 27) {
                this.value = "";
              }

              '.$this->getFilterJSVarName($filter, false, $primary).' = this.value;
              updateFilter();
            });';
        else if(strcmp($filter['filter'],'slider')){
            return '$("#slide_'.$filter['attribute'].'").slider({
            "range": "min",
            "slide": function (event, ui) {
              Slick.GlobalEditorLock.cancelCurrentEdit();

              if ('.$this->getFilterJSVarName($filter, false, $primary).' != ui.value) {
                window.clearTimeout(h_runfilters);
                h_runfilters = window.setTimeout(updateFilter, 10);
                '.$this->getFilterJSVarName($filter, false, $primary).' = ui.value;
              }
            }
          });';
        }
    }
}
?>
