<?
    $config = [
        'components' => [
            'request' => [
                'class' => 'yii\web\Request',
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser',
                ]
            ]
        ]
    ];

    return $config;