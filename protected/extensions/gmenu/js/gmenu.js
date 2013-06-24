jQuery('document').ready(function($){        
    var mostrarMenu = function(e){
        $('.grupo').removeClass('selected');
            $("#gmenu-panel").show();
            $(this).addClass('selected');
            drawSubmenus($(this));
    };
    
    var drawSubmenus= function(grupo){
        if(!grupo.hasClass('grupo')){
            return;
        }
        $("#gmenu-overlay").html('');
        var items=grupo.find("> ul > li");
        if(items.length===0){
            $('#gmenu-panel').hide();
            return;
        }else{
            $('#gmenu-panel').show();
            $('#gmenu-overlay').css('background-image',"url('"+grupo.attr('image')+"')");
        }
        items.each(function(index){
            if($(this).has(":contains('ul')")){
                agrega($(this),0,$("#gmenu-overlay"));
            } 
        });
    };
    var agrega=function(li,level,item){
        text=li.html().split("<ul>")[0];
        var span=$("<span class=\"level-"+level+"\">"+text+"</span>");
        
        item.append(span);
        li.find("> ul > li").each(function(index){
           if($(this).has(":contains('ul')")){
                agrega($(this),level+1,span);
            } 
        });
    };
    
    $('.grupo').hover(mostrarMenu).on('click',mostrarMenu);
    $(document).mouseup(function (e){
        var container = $("#gmenu-panel");    
        if (!container.is(e.target) && container.has(e.target).length === 0){
            container.hide();
            $('.grupo').removeClass('selected');
        }
    });
});