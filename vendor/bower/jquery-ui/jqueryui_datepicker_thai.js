// JavaScript Document
(function($){

	var dateBefore=null;
	var objYear=null;
	var separator='/';
	
    $.extend($.datepicker._defaults, {
		dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
		monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']		
    });

	setTimeout(function(){				
			var textYear=parseInt($(".ui-datepicker-year").text())+543;	
			$(".ui-datepicker-year").text(textYear);			
	},50);		 
 		
	$.extend($.datepicker._defaults, {
		 beforeShow : function(d,ins){
			if($(this).val()!=""){
				var arrayDate=$(this).val().split(separator);		
				arrayDate[2]=parseInt(arrayDate[2])-543;
				$(this).val(arrayDate[0]+separator+arrayDate[1]+separator+arrayDate[2]);
				dateBefore=$(this).val();
			}
			setTimeout(function(){				
				$.each($(".ui-datepicker-year"),function(j,k){
					if($(".ui-datepicker-year").eq(j).find(":selected").length>0){
						$.each($(".ui-datepicker-year:eq("+j+") option"),function(l,m){
							var textYear=parseInt($(".ui-datepicker-year:eq("+j+") option").eq(l).val())+543;
							$(".ui-datepicker-year:eq("+j+") option").eq(l).text(textYear);		
						});								
					}else{
						if($(".ui-datepicker-year").eq(j).parents(".ui-datepicker-inline").length==0){
							var textYear=parseInt($(".ui-datepicker-year").eq(j).text())+543;	
							$(".ui-datepicker-year").eq(j).text(textYear);		
						}
					}
				});			
			},50);					 
		}
	});	
	$.extend($.datepicker._defaults, {
		 onChangeMonthYear : function(){	
			 var objDatePicker=this;
			setTimeout(function(){		
				$.each($(".ui-datepicker-year"),function(j,k){
					if($(".ui-datepicker-year").eq(j).find(":selected").length>0){
						$.each($(".ui-datepicker-year:eq("+j+") option"),function(l,m){
							var textYear=parseInt($(".ui-datepicker-year:eq("+j+") option").eq(l).val())+543;
							$(".ui-datepicker-year:eq("+j+") option").eq(l).text(textYear);		
						});								
					}else{
						if($(".ui-datepicker-year").eq(j).parents(".ui-datepicker-inline").length==0){
							var textYear=parseInt($(".ui-datepicker-year").eq(j).text())+543;	
							$(".ui-datepicker-year").eq(j).text(textYear);		
						}else{
							if($(objDatePicker).find(".ui-datepicker-inline").length>0){
								var textYear=parseInt($(".ui-datepicker-year").eq(j).text())+543;	
								$(".ui-datepicker-year").eq(j).text(textYear);										
							}
						}
					}
				});								
			},50);			 
		}
	});	
	
	$.extend($.datepicker._defaults, {
		 onClose : function(){	
			if($(this).val()!="" && $(this).val()==dateBefore){			
				var arrayDate=dateBefore.split(separator);
				arrayDate[2]=parseInt(arrayDate[2])+543;
				$(this).val(arrayDate[0]+separator+arrayDate[1]+separator+arrayDate[2]);			
			}				 
		}
	});			 	
	
	$.extend($.datepicker._defaults, {
		 onSelect : function(dateText, inst){	
			dateBefore=$(this).val();
			var objDateSelect=this;
			var arrayDate=dateText.split(separator);
			arrayDate[2]=parseInt(arrayDate[2])+543;
			$(this).val(arrayDate[0]+separator+arrayDate[1]+separator+arrayDate[2]);		 
			$(this).change();
			setTimeout(function(){		
				$(objDateSelect).find(".ui-datepicker-year").text(arrayDate[2]);		
			},50);				
		}
	});					 
	
})(jQuery); 	