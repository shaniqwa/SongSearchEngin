$(document).ready(function(){
				var arrTemp = [];
				var arrTemp2 = [];
				var arrTemp3 = [];
			   var $form = $('#form');
			   $form.submit(function(){
			      $.get($(this).attr('action'), $(this).serialize(), function(response){ 
			      	console.log(response);
			      	$('#res').css("display","block");
			      	$('tbody').empty();
			      	arrTemp = [];
			      	arrTemp2 = [];
			      	var t = JSON.stringify(response);
			      	var temp = JSON.parse(t);
			      	
			      	for(i in temp){
						
    					temp[i].lyrics = temp[i].lyrics.replace(/(?:\r\n|\r|\n)/g, '<br />');
    					
 			      		//show the shortveiw of the results
			      		var str = temp[i].lyrics.slice(0,150);
			 			$(".table").append("<tr><td>" +  temp[i].artist +  "</td><td>" +  temp[i].songname + "</td><td class='clear" + i + "' style='width:50%;'>" + str + "..." + "</td></tr>");
			 			arrTemp.push(temp[i].lyrics);
			 			arrTemp2.push(str);
			 			
		    		}
		    		//expand song on click
		    		$("td").on('click',function(){
	                    for(i in arrTemp){ 
	                    	if($(this).hasClass("clear" + i)){
	                            $("td.clear" + i).empty();
	                            $("td.clear" + i).append(arrTemp[i]);

	                            var id = "td.clear" + i;
	                            var input = document.getElementById("input_id").value;
	                            var myHilitor = new Hilitor(id);
	  							myHilitor.apply(input);
	                     	}
		                    else{
		                     	$("td.clear" + i).empty();
		                     	$("td.clear" + i).append("<p class='clear" + i + "' style='width:50%;'>" + arrTemp2[i] + "..." + "</p>");
	                    	}
	                    } 
	                });
                   
		                   
			      },'json');
			      return false;
			   });

			});

$(document).ready(function(){
	
   var $form = $('#form2');
   $form.submit(function(){
      $.post($(this).attr('action'), $(this).serialize(), function(response){   
      if(response.status == "success"){
      	  alert("The song was added successfully!");       
      }
    
      },'json');
      return false;
   });
});


$(document).ready(function(){
	
   var $form = $('#form3');
   $form.submit(function(){
      $.post($(this).attr('action'), $(this).serialize(), function(response){   
      if(response.status == "success"){
      	  alert("The song was deleted successfully!");       
      }
    
      },'json');
      return false;
   });
});

