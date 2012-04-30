<h3>Send Notification Blast</h3>

<form name="notification_blast" id="notification_blast">

	<p><input type="text" name="notification_subject" placeholder="Heya, how goes it?" class="input_full" valule=""></p>
	<textarea id="status_update_text" placeholder="Yo dawg, I herd you like messages..." name="notification_message"></textarea>
	<div id="status_update_post">
		<input type="submit" name="post" id="status_post" value="Send" />
		<span id="character_count"></span>
	</div>

	<p>
		<input type="checkbox" class="nullify" name="notifications_mobile" value="yes"> &nbsp;Mobile Notifications<br>
		<input type="checkbox" class="nullify" name="notifications_sms" value="yes"> &nbsp;Text Messages<br>
		<input type="checkbox" class="nullify" name="notifications_email" value="yes"> &nbsp;Email
	</p>
	
	<p><input type="submit" name="save" value="Save" /></p>

</form>

<div class="clear"></div>
<div class="content_norm_separator"></div>

<script type="text/javascript">
$(document).ready(function()
{
	// Character Counter
	$('#status_update_text').NobleCount('#character_count',
	{
		on_negative: 'color_red'
	});	

	// Update Status
	$('#notification_blast').bind('submit', function(e)
	{
		e.preventDefault();
		$.validator(
		{
			elements : 
				[{
					'selector' 	: '#status_update_text',
					'rule'		: 'require', 
					'field'		: 'Please write something...',
					'action'	: 'element'
				}],
			message	 : '',
			success	 : function()
			{			
				var blast_data = $('#notification_blast').serializeArray();
		
				$.oauthAjax(
				{
					oauth 		: user_data,
					url			: base_url + 'api/notifications/blast',
					type		: 'POST',
					dataType	: 'json',
					data		: blast_data,
				  	success		: function(result)
				  	{		  		  	
						if (result.status == 'success')
						{
							$('#status_update_text').val('');
							
						}
						
						$('#content_message').notify({status:result.status,message:result.message});
					}
				});
				
			
			}
		});
	});

});
</script>