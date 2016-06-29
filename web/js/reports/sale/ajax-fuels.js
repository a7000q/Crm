$(document).ready(function(){   
    showFuels();  
    setInterval('showFuels()',1000);  
}); 

function showFuels()
{
    $.ajax({  
        url: "index.php?r=ajax/get-real-fuels",  
        cache: false,  
        success: function(data)
        {  
              formBlock(data);
        }  
    });  
}

function formBlock(data)
{
    $('#mainBlockFuels').empty();
    var fuels = $.parseJSON(data);

    for(var i in fuels)
    {
    	showFuel(fuels[i]);
    }
}

function showFuel(fuel)
{
	var txt = "<div class='block'>";
	txt += "<h4>" + fuel.name + "</h4>";
	txt += "<b>" + fuel.partnerName + "</b></br>";
	txt += "<b>" + fuel.cardName + "</b></br>";
	txt += "<b>" + fuel.doza + "</b>";
	txt += "</div>";

	$('#mainBlockFuels').append(txt);
}

