
$(document).on('click','#verify_s_cod ', function(evt){
		evt.preventDefault();
		$('#getyoutubedata').submit();
});
$(document).on('click','#submit_ads_code' , function(evt){
	evt.preventDefault();
	$('#submitads').submit();
});
$(document).on('click','#hide-link' , function(evt){
	evt.preventDefault();
	$('#link').hide();
});

$(document).on('click','.submit-form' , function(evt){
		evt.preventDefault();
		$(this).closest('form').submit();
	});
$(document).on('click','#upload-video' , function(evt){
		evt.preventDefault();
		$(this).closest('form').submit();
});

/*$(document).on('focus','#p_form', function(){
	$('#publish_btn_container').toggleClass('hidden');
});

$(document).on('blur','#p_form', function(){
	setTimeout(function(){
			$('#publish_btn_container').toggleClass('hidden');
	}, 1000);
});*/
	

function Base64 (){	
		// Define the string
		var string = $('#ads_code').val();
		// Encode the String
		var encodedString = Base64.encode(string);
		//console.log(encodedString); // Outputs: "SGVsbG8gV29ybGQh"
		// Decode the String
		//var decodedString = Base64.decode(encodedString);
		//console.log(decodedString); // Outputs: "Hello World!"
		$('#ads_code').val(encodedString);
		alert(encodedString);
}


// theme ready function
$(document).ready(function(){
$("#p_form").emojioneArea({
			saveEmojisAs : 'image',
			useSprite: false,
			pickerPosition    : "bottom",
			events:{
				paste: function (editor, event) {
			      console.log('event:paste');
			    },
			    change: function (editor, event) {

				    if (editor.html().trim() !== '' && logged === true ) {

				    	var plaintext = stripScripts(editor.text());
				    	if (scriptsCount === 0 ) {
				    		plaintext = stripScripts(editor.html());
				    	}
				    	var field = editor.parent().prev('textarea');
				    		field.val(plaintext);

				    	$('#p_form').val(plaintext);
						
				    }

			    },
			}
		});
});
