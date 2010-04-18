/***************************/  
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro  
//@website: www.yensdesign.com  
//@email: yensamg@gmail.com  
//@license: Feel free to use it, but keep this credits please!  
/***************************/  
  
$(document).ready(function(){  
    $(".menu > li").click(function(e){  
        switch(e.target.id){  
            case "FeaturesSpec":  
                //change status & style menu  
                $("#FeaturesSpec").addClass("active");  
                $("#CustomerReviews").removeClass("active");  
                $("#ExpertAdvice").removeClass("active");  
                //display selected division, hide others  
                $("div.FeaturesSpec").fadeIn();  
                $("div.CustomerReviews").css("display", "none");  
                $("div.ExpertAdvice").css("display", "none");  
            break;  
            case "CustomerReviews":  
                //change status & style menu  
                $("#FeaturesSpec").removeClass("active");  
                $("#CustomerReviews").addClass("active");  
                $("#ExpertAdvice").removeClass("active");  
                //display selected division, hide others  
                $("div.CustomerReviews").fadeIn();  
                $("div.FeaturesSpec").css("display", "none");  
                $("div.ExpertAdvice").css("display", "none");  
            break;  
            case "ExpertAdvice":  
                //change status & style menu  
                $("#FeaturesSpec").removeClass("active");  
                $("#CustomerReviews").removeClass("active");  
                $("#ExpertAdvice").addClass("active");  
                //display selected division, hide others  
                $("div.ExpertAdvice").fadeIn();  
                $("div.FeaturesSpec").css("display", "none");  
                $("div.CustomerReviews").css("display", "none");  
            break;  
        }  
        //alert(e.target.id);  
        return false;  
    });  
});  