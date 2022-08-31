<?php
namespace Opencart\Catalog\Model\Extension\XFeedbackButton\Module;

class Button extends \Opencart\System\Engine\Model
{
    private const SETTINGS_TABLE_NAME = 'xu_callback_button_settings';
    private const REQUESTS_TABLE_NAME = 'xu_callback_button_requests';

    /**
     * @return array
     */
    public function getSettings(): array
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . self::SETTINGS_TABLE_NAME);
        return $this->convertJsonSettingsToArray($query->rows);
    }

    /**
     * @param array $data
     */
    public function saveRequest(array $data): void
    {
        $name = !empty($data['name']) ? $this->db->escape($data['name']) : '';
        $email = !empty($data['email']) ? $this->db->escape($data['email']) : '';
        $phone = !empty($data['phone']) ? $this->db->escape($data['phone']) : '';
        $comment = !empty($data['comment']) ? $this->db->escape($data['comment']) : '';

        $this->db->query("INSERT INTO " . DB_PREFIX . self::REQUESTS_TABLE_NAME . " (name, email, phone, comment, status) VALUES 
        ('" . $name . "', '" . $email . "', '" . $phone . "', '" . $comment . "', 'new')" );
    }

    /**
     * @param array $settings
     * @return array
     */
    private function convertJsonSettingsToArray(array $settings): array
    {
        $result = [];

        foreach ($settings as $setting) {
            if ($setting['name'] === 'position') {
                $result[$setting['name']] = [
                    'id' => $setting['id'],
                    'name' => $setting['name'],
                    'data' => json_decode($setting['json'], true)
                ];
            } else {
                $result[$setting['name']] = json_decode($setting['json'], true);
            }
        }

        return $result;
    }
}
