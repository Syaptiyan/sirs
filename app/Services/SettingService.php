<?php

namespace App\Services;

use App\Models\SettingModel;

class SettingService
{
    private SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function get(string $key, $default = null)
    {
        $setting = $this->settingModel->where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting->value, $setting->type);
    }

    public function set(string $key, $value, string $type = 'string'): bool
    {
        $setting = $this->settingModel->where('key', $key)->first();

        $data = [
            'value' => $this->prepareValue($value, $type),
            'type'  => $type,
        ];

        if ($setting) {
            return $this->settingModel->update($setting->id, $data);
        }

        return $this->settingModel->insert(array_merge($data, [
            'key'   => $key,
            'group' => 'general',
        ])) !== false;
    }

    public function getGroup(string $group): array
    {
        $settings = $this->settingModel->where('group', $group)->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = $this->castValue($setting->value, $setting->type);
        }

        return $result;
    }

    public function setGroup(string $group, array $data): bool
    {
        foreach ($data as $key => $value) {
            $setting = $this->settingModel->where('key', $key)->first();
            $type = $setting->type ?? 'string';

            $updateData = [
                'value' => $this->prepareValue($value, $type),
            ];

            if ($setting) {
                $this->settingModel->update($setting->id, $updateData);
            } else {
                $this->settingModel->insert(array_merge($updateData, [
                    'key'   => $key,
                    'group' => $group,
                    'type'  => $type,
                ]));
            }
        }

        return true;
    }

    public function getAll(): array
    {
        $settings = $this->settingModel->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->group][$setting->key] = $this->castValue($setting->value, $setting->type);
        }

        return $result;
    }

    private function castValue($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($value, true),
            default   => (string) $value,
        };
    }

    private function prepareValue($value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer' => (string) (int) $value,
            'boolean' => $value ? '1' : '0',
            'json'    => is_string($value) ? $value : json_encode($value),
            default   => (string) $value,
        };
    }
}
