jQuery(function ($) {
	$(document).ready(function() {
		$('form.cadastro-usuario input[type="submit"]').click(function(e) {
			e.preventDefault();
			var btnSubmit = $(this);
			btnSubmit.prop('disabled', true);

			try
			{
				var exigir_senha = !(cosmedfarma_global.is_user_logged_in == '1');

				if (($('form.cadastro-usuario input[name="nome"]').val() == '')
					|| ($('form.cadastro-usuario input[name="sobrenome"]').val() == '')
					|| ($('form.cadastro-usuario input[name="crm_crn"]').val() == '')
					|| ($('form.cadastro-usuario input[name="email"]').val() == '')
					|| (exigir_senha && (
							($('form.cadastro-usuario input[name="senha"]').val() == '')
							|| ($('form.cadastro-usuario input[name="senha_novamente"]').val() == '')
							)
						)
					)
				{
					throw cosmedfarma_i18n.all_fields_are_required;
				}

				if ($('form.cadastro-usuario input[name="senha"]').val() != $('form.cadastro-usuario input[name="senha_novamente"]').val())
					throw cosmedfarma_i18n.passwords_must_match;

				$('form.cadastro-usuario p.submit img').show();

				var action = (cosmedfarma_global.is_user_logged_in == '1') ? 'netsis_update_user' : 'netsis_new_user';

				$.ajax({
					type: 'POST',
					url: theme_ajax.url,
					data: {
						action: action,
						nonce: $('form.cadastro-usuario input[name="vb_user_nonce"]').val(),
						nome: $('form.cadastro-usuario input[name="nome"]').val(),
						sobrenome: $('form.cadastro-usuario input[name="sobrenome"]').val(),
						crm_crn: $('form.cadastro-usuario input[name="crm_crn"]').val(),
						email: $('form.cadastro-usuario input[name="email"]').val(),
						senha: $('form.cadastro-usuario input[name="senha"]').val(),
						senha_novamente: $('form.cadastro-usuario input[name="senha_novamente"]').val()
					},
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				})
				.done(function(data) {
						data = JSON.parse(data);
						if (data.success)
						{
							if (cosmedfarma_global.is_user_logged_in == '1')
							{
								if ((typeof data.code != 'undefined') && (data.code == 100))
								{
									alert(data.msg);
									window.location = data.redirection_url;
								}
								else
								{
									alert('Informações atualizadas com sucesso!');
									$('form.cadastro-usuario p.submit img').hide();
									btnSubmit.prop('disabled', false);
								}
							}
							else
								window.location = data.redirection_url;
						}
						else
							alert('ERRO: ' + data.msg);

						$('form.cadastro-usuario p.submit img').hide();
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

						$('form.cadastro-usuario p.submit img').hide();
						btnSubmit.prop('disabled', false);
					}
				);
			}
			catch(ex)
			{
				btnSubmit.prop('disabled', false);
				alert(ex);
			}
		});

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