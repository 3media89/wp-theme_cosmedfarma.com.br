jQuery(function ($) {
	$(document).ready(function() {
		$('div.menu-user a.logout').click(function(e) {
			e.preventDefault();

			$('div.menu-user img').show();

			$.ajax({
					type: 'POST',
					url: theme_ajax.url,
					data: {
						action: 'user_logout'
					},
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				})
				.done(function(data) {
						data = JSON.parse(data);
						if (data.success)
							window.location = data.redirection_url;
						else
						{
							alert('ERRO: ' + data.msg);
							$('div.menu-user img').hide();
						}
				})
				.fail(function(data) {
						console.log(data);

						try
						{
							data = JSON.parse(data.responseText);
							alert('ERRO: ' + data.msg);
						}
						catch(ex)
						{
							alert(ex);
						}

						$('div.menu-user img').hide();
					}
				);
		});
	});
});