<div class="wcontent" id="popupLogin">
	<div class="contentLogin">
		<h2>Вход на сайт</h2>
		<div class="response" style="display:none; height: 100px; margin-bottom: 20px;">
			<div></div>
			<a href="javascript:void(0);" onclick='$("#popupLogin .contentLogin .response").hide();$("#formLogin").show();'>Попробовать еще раз</a>
		</div>
                    <?= CHtml::link(Yii::t('Site', 'I`m a new User'), array('site/login' , 'service' => $service, 'user' => 'new', 'reg_ask' => 1)); ?>
                    <?= CHtml::link(Yii::t('Site', 'I already have a login'), array('site/login' , 'service' => $service, 'user' => 'haveALogin', 'reg_ask' => 1)); ?>
		<div class="social">
		</div>
			
	</div><!-- /contentnLogin -->
</div>
