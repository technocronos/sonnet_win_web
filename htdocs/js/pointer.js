$(document).ready(function(){
    $('#main-contents').pointer();
});

(function($){
    var methods = {
        init : function( options ) {
            var settings = {
              size : 80,
              spd : 200,
              color : "#ccc"
            }
            settings = $.extend(settings, options);

            var circle_style = {
              "position":"absolute",
              "z-index":9999,
              "height":10,
              "width":10,
              "border":"solid 4px "+settings.color,
              "border-radius":settings.size
            }

            return this.each(function() {
                var $this = $(this),
                    data = $this.data('pointer'),
                    pointer = $(document).off("click").on("click", function(e){
                        var x = e.pageX-5;
                        var y = e.pageY-5;
                        
                        var pos = {
                            top :(-settings.size/2)+y,
                            left :(-settings.size/2)+x
                        }

                        //iscroll領域はすべてマイナスで取れて変な所で出てしまうのでその場合はアニメーションしない。
                        if(x < 0 && y < 0)
                            return false;

                        $this.append('<div class="circle"></div>');
                        $this.find(".circle:last").css(circle_style).css({
                            "top":y,
                            "left":x
                        }).animate({"height":settings.size,"width":settings.size,"left":pos.left,"top":pos.top},{duration:settings.spd,queue:false})
                        .fadeOut(settings.spd * 1.8,function(){
                            $(this).remove();
                        });
                    });

                if(!data){
                    $this.css({"position":"relative"});
                    $(this).data('pointer', {
                        target : $this,
                        pointer : pointer
                    });
                }
            });
        },
        destroy : function( ) {
            return this.each(function(){
                var $this = $(this),
                    data = $this.data('pointer');

                if(data){
                    // ネームスペースはここでおしまい
                    $(document).off("click");
                    data.pointer.remove();
                    $this.removeData('pointer');
                }
            })
        },
    };
    $.fn.pointer = function( method ) {
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist' );
        }
    };
})(jQuery); 