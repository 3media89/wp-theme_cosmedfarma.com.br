jQuery(function ($) {
	$(document).ready(function() {
		$('form.login input[type="submit"]').click(function(e) {
			e.preventDefault();

			try
			{
				if ($('form.login input[name="login"]').val() == '')
				{
					$('form.login input[name="login"]').focus();
					throw cosmedfarma_i18n.please_inform_login;
				}

				if ($('form.login input[name="senha"]').val() == '')
				{
					$('form.login input[name="senha"]').focus();
					throw cosmedfarma_i18n.please_inform_password;
				}

				$('form.login p.entrar img').show();

				$.ajax({
					type: 'POST',
					url: theme_ajax.url,
					data: {
						action: 'user_login',
						nonce: $('form.login input[name="cosmed_user_login_nonce"]').val(),
						login: $('form.login input[name="login"]').val(),
						senha: $('form.login input[name="senha"]').val()
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
							alert('Senha e / ou login incorretos.');
							$('form.login p.entrar img').hide();
						}
				})
				.fail(function(data) {
						data = JSON.parse(data.responseText);
						alert('ERRO: ' + data.msg);
						$('form.login p.entrar img').hide();
					}
				);
			}
			catch(ex)
			{
				alert(ex);
			}
		});

		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			paginationClickable: true,
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
			spaceBetween: 30,
			autoplay: 5000,
			autoplayDisableOnInteraction: false,
			loop: true
		});
	});
});