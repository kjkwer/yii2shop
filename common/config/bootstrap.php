<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
//>>阿里云短信验证别名
Yii::setAlias('@Aliyun', dirname(dirname(__DIR__)) . '/frontend/aliyun');