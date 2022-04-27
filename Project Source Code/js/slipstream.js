// Simple text Clock used on dashboard
// Taken From: https://stackoverflow.com/a/65871072 - 2022-04-15
function startTime() {
    var today=new Date();
    //                   1    2    3    4    5    6    7    8    9   10    11  12   13   14   15   16   17   18   19   20   21   22   23   24   25   26   27   28   29   30   31   32   33
    var suffixes = ['','st','nd','rd','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','th','st','nd','rd','th','th','th','th','th','th','th','st','nd','rd'];

    var weekday = new Array(7);
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var month = new Array(12);
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";

    document.getElementById('liveclock').innerHTML=(weekday[today.getDay()] + ',' + " " + today.getDate()+'<sup>'+suffixes[today.getDate()]+'</sup>' + ' of' + " " + month[today.getMonth()] + " " + today.getFullYear() + ' - ' + today.toLocaleTimeString());
    t=setTimeout(function(){startTime()},500);
}
// Populate form using exisiting data
// Taken from: https://stackoverflow.com/questions/7298364/using-jquery-and-json-to-populate-forms - 2022-04-27
// Modified by Adam Mutimer - Made it more suitable for our needs

function populateForm(data) {   
	$.each(data, function(key, value){  
		var $ctrl = $('[name="'+key+'"]');  // Adam: Better way of doing things
	
		if ($ctrl.is('select')) {
			// If 'value' is an array loop though it making the selections
			if (Array.isArray(value)) {
				var arrayLen = value.length;
				for (var i = 0; i < arrayLen; i++) {
					$("option", $ctrl).each(function() {
						if (this.value==value[i]) { 
							this.selected=true; 
						}
					});
				}
			} else {
				// not an array just make one selection
				$("option", $ctrl).each(function() {
					if (this.value==value) { 
						this.selected=true; 
					}
				});
			}
		}
		else {
			switch($ctrl.attr("type")) {  
				case "text" :   case "hidden":  case "textarea":  
					$ctrl.val(value);   
					break;   
				case "radio" : case "checkbox":   
					$ctrl.each(function(){
					   if($(this).attr('value') == value) {  
							$(this).attr("checked",value); 
					   } 
					});   
					break;
			} 
		} 
	});
}

// taken from: https://stackoverflow.com/a/1497512 (2022/04/27)
function generatePassword() {
    var length = 8,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}