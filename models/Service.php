<?php

namespace app\models;

use Yii;

class Service extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'ip', 'domain', 'client_id'], 'required'],
            ['type', 'validateType'],
            [['client_id'], 'default', 'value' => null],
            [['client_id'], 'integer'],
            [['type', 'domain'], 'string', 'max' => 255],
            ['domain', 'validateDomain'],
            [['ip'], 'string', 'max' => 15],
            ['ip', 'ip', 'ipv6' => false],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'ip' => 'Ip',
            'domain' => 'Domain',
            'client_id' => 'Client ID',
        ];
    }

    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public function validateDomain($attribute, $params)
    {
        $valid = preg_match("/^(?!-)[A-Za-z0-9-]+([\\-\\.]{1}[a-z0-9]+)*\\.[A-Za-z]{2,6}$/", 
            $this->$attribute);
        if (!$valid) {
            $this->addError($attribute, 'Invalid domain.');
        }

    }

    public function validateType($attribute, $params)
    {
        if (!in_array($this->$attribute, array_column(ServiceType::cases(), 'name'))) {
            $this->addError($attribute, 'The type must be either "Hosting" or "Proxy".');
        }
    }
}
