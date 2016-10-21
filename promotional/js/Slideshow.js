
    $(function() {
        var theImage = $('ul#ProdSlideShow li img');
        var theWidth = theImage.width();
        //wrap into mother div
        $('ul#ProdSlideSho').wrap('<div id="mother" />');                 
        //assign height width and overflow hidden to mother
        $('#mother').css({
            width: function() {
                return theWidth;
            }, 
            height: function() {
                return theImage.height();
            }, 
            position: 'relative',
            overflow: 'hidden'      
        });
            //get total of image sizes and set as width for ul 
        var totalWidth = theImage.length * theWidth;
        $('ul').css({
            width: function(){
                return totalWidth;  
            }               
        }); 

        var ss_timer = setInterval(function(){ ss_next(); }, 3000);

        function ss_next()
        {
            var a = $(".active");
            a.removeClass('active');                                                                                                                

            if(a.hasClass('last'))
            {
                //last element -- loop
                a.parent('ul').animate({"margin-left": (0)}, 1000);
                a.siblings(":first").addClass('active');                            
            }
            else
            {
                a.parent('ul').animate({"margin-left": (-(a.index() + 1) * theWidth)}, 1000);   
                a.next().addClass('active');
            }
        }

        // Cancel slideshow and move next manually on click
        $('ul#ProdSlideShow li img').on('click', function(){               
            clearInterval(ss_timer);
            ss_next();
        });

    });
