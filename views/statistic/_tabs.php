<?php
use yii\helpers\Html;
?>

<ul class="nav nav-tabs">
    <li role="presentation"<?php if ($tab == 'index'):?> class="active"<?php endif?>><?= Html::a('Всі роки', ['index']);?></li>
    <li role="presentation"<?php if ($tab == 'region'):?> class="active"<?php endif?>><?= Html::a('Адміністративні райони', ['region']);?></li>
    <li role="presentation"<?php if ($tab == 'orgs'):?> class="active"<?php endif?>><?= Html::a('Організації', ['orgs']);?></li>
</ul>
<br/>
<br/>
