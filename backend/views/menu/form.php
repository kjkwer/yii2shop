<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑菜单</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($menu,"name")->textInput();
echo $form->field($menu,"parent_id")->dropDownList(\backend\models\Menu::getAllToOne());
echo $form->field($menu,"url")->dropDownList(\backend\models\PermissionForm::getAllRoute());
echo $form->field($menu,"sort")->textInput();
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();