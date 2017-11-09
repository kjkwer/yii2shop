<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑会员</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($userModel,"username")->textInput();
echo $form->field($userModel,"email")->textInput();
if ($userModel->isNewRecord){
    echo $form->field($userModel,"password_hash")->passwordInput();
}
echo $form->field($userModel,"status",["inline"=>true])->radioList([0=>"否",1=>"是"]);
echo $form->field($userModel,"roles",["inline"=>true])->checkboxList($roles);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();