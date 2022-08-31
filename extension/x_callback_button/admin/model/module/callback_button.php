<?php
namespace Opencart\Admin\Model\Extension\XFeedbackButton\Module;

class CallbackButton extends \Opencart\System\Engine\Model
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

    public function saveSettings(array $data): void
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . self::SETTINGS_TABLE_NAME);
        $position_json = $this->preparePositionJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('position', '" .  $this->db->escape($position_json) ."')");
        $text_image_json = $this->prepareTextImageJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('text_image', '" .  $this->db->escape($text_image_json) ."')");
        $header_text = $this->prepareHeaderTextJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('header_text', '" .  $this->db->escape($header_text) ."')");
        $show_fields = $this->prepareShowFieldsJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('show_fields', '" .  $this->db->escape($show_fields) ."')");
        $fields_names = $this->prepareFieldsNamesJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('fields_names', '" .  $this->db->escape($fields_names) ."')");
        $button_text = $this->prepareButtonTextJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('button_text', '" .  $this->db->escape($button_text) ."')");
        $messages_texts = $this->prepareMessagesTextsJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('messages_texts', '" .  $this->db->escape($messages_texts) ."')");
        $button_size = $this->prepareButtonSizeJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('button_size', '" .  $this->db->escape($button_size) ."')");
        $button_colors = $this->prepareButtonColorsJson($data);
        $this->db->query("INSERT INTO " . DB_PREFIX . self::SETTINGS_TABLE_NAME . " (name, json) VALUES ('colors', '" .  $this->db->escape($button_colors) ."')");
    }

    public function getRequests(): array
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . self::REQUESTS_TABLE_NAME);
        return $query->rows;
    }

    public function saveRequest(array $data): void
    {
        $request_id = (int)$data['request_id'];
        $name = !empty($data['name']) ? $this->db->escape($data['name']) : '';
        $email = !empty($data['email']) ? $this->db->escape($data['email']) : '';
        $phone = !empty($data['phone']) ? $this->db->escape($data['phone']) : '';
        $comment = !empty($data['comment']) ? $this->db->escape($data['comment']) : '';
        $status = $this->db->escape($data['status']);
        $this->db->query("UPDATE " . DB_PREFIX . self::REQUESTS_TABLE_NAME . " SET name = '" . $name . "', email = '" . $email . "', phone = '" . $phone . "', comment = '" . $comment . "', status = '" . $status . "' WHERE id = " . $request_id);
    }

    public function deleteRequest(array $data): void
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . self::REQUESTS_TABLE_NAME . " WHERE id = " . (int)$data['request_id']);
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

    private function preparePositionJson(array $data) :string
    {
        if ($data['button_position'] !== 'custom-settings') {
            return json_encode([
                'custom' => false,
                'position' => $data['button_position']
            ], JSON_THROW_ON_ERROR);
        }

        return json_encode([
            'custom' => true,
            'left' => $data['button_left'],
            'top' => $data['button_top'],
            'right' => $data['button_right'],
            'bottom' => $data['button_bottom']
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareTextImageJson(array $data): string
    {
        if (!$data['show_image']) {
            $data['button_text']['show_image'] = false;
            return json_encode($data['button_text'], JSON_THROW_ON_ERROR);
        }

        return json_encode([
            'show_image' => true,
            'img_path' => $data['button_image']
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareHeaderTextJson(array $data): string
    {
        return json_encode($data['header_text'], JSON_THROW_ON_ERROR);
    }

    private function prepareShowFieldsJson(array $data): string
    {
        return json_encode([
            'show_name' => $data['show_name'],
            'show_email' => $data['show_email'],
            'show_phone' => $data['show_phone'],
            'show_comment' => $data['show_comment'],
            'name_required' => $data['name_required'],
            'email_required' => $data['email_required'],
            'phone_required' => $data['phone_required'],
            'comment_required' => $data['comment_required']
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareFieldsNamesJson(array $data): string
    {
        return json_encode([
            'name_name_field' => $data['name_name_field'],
            'name_email_field' => $data['name_email_field'],
            'name_phone_field' => $data['name_phone_field'],
            'name_comment_field' => $data['name_comment_field'],
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareButtonTextJson(array $data): string
    {
        return json_encode($data['button_text'], JSON_THROW_ON_ERROR);
    }

    private function prepareMessagesTextsJson(array $data): string
    {
        return json_encode([
            'success_text' => $data['success_text'],
            'error_text' => $data['error_text']
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareButtonSizeJson(array $data): string
    {
        return json_encode([
            'width' => $data['button_width'],
            'height' => $data['button_height'],
            'button_text_size' => $data['button_text_size']
        ], JSON_THROW_ON_ERROR);
    }

    private function prepareButtonColorsJson(array $data): string
    {
        return json_encode([
            'button_color' => $data['button_color'],
            'button_text_color' => $data['button_text_color'],
            'form_color' => $data['form_color'],
            'send_button_color' => $data['send_button_color'],
            'text_on_form_color' => $data['text_on_form_color']
        ], JSON_THROW_ON_ERROR);
    }
}
