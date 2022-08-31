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

    public function createDbTables(): void
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "xu_callback_button_requests` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) DEFAULT NULL,
                        `email` varchar(45) DEFAULT NULL,
                        `phone` varchar(45) DEFAULT NULL,
                        `comment` varchar(45) DEFAULT NULL,
                        `status` varchar(45) DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `id_UNIQUE` (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "xu_callback_button_settings` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) DEFAULT NULL,
                        `json` varchar(1000) DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `id_UNIQUE` (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=520 DEFAULT CHARSET=utf8mb4;");

        $this->db->query("INSERT INTO `oc_xu_callback_button_settings` VALUES (511,'position','{\"custom\":false,\"position\":\"lower-left-corner\"}'),(512,'text_image','{\"en-gb\":\"Request a callback\",\"ru-ru\":\"\\u0417\\u0430\\u043a\\u0430\\u0437\\u0430\\u0442\\u044c \\u043e\\u0431\\u0440\\u0430\\u0442\\u043d\\u044b\\u0439 \\u0437\\u0432\\u043e\\u043d\\u043e\\u043a\",\"show_image\":false}'),(513,'header_text','{\"en-gb\":\"Request a callback\",\"ru-ru\":\"\\u0417\\u0430\\u043a\\u0430\\u0437 \\u043e\\u0431\\u0440\\u0430\\u0442\\u043d\\u043e\\u0433\\u043e \\u0437\\u0432\\u043e\\u043d\\u043a\\u0430\"}'),(514,'show_fields','{\"show_name\":\"1\",\"show_email\":\"1\",\"show_phone\":\"1\",\"show_comment\":\"0\",\"name_required\":\"0\",\"email_required\":\"0\",\"phone_required\":\"1\",\"comment_required\":\"0\"}'),(515,'fields_names','{\"name_name_field\":{\"en-gb\":\"Name\",\"ru-ru\":\"\\u0418\\u043c\\u044f\"},\"name_email_field\":{\"en-gb\":\"E-mail\",\"ru-ru\":\"E-mail\"},\"name_phone_field\":{\"en-gb\":\"Phone\",\"ru-ru\":\"\\u0422\\u0435\\u043b\\u0435\\u0444\\u043e\\u043d\"},\"name_comment_field\":{\"en-gb\":\"Comment\",\"ru-ru\":\"\\u041a\\u043e\\u043c\\u043c\\u0435\\u043d\\u0442\\u0430\\u0440\\u0438\\u0439\"}}'),(516,'button_text','{\"en-gb\":\"Request a callback\",\"ru-ru\":\"\\u0417\\u0430\\u043a\\u0430\\u0437\\u0430\\u0442\\u044c \\u043e\\u0431\\u0440\\u0430\\u0442\\u043d\\u044b\\u0439 \\u0437\\u0432\\u043e\\u043d\\u043e\\u043a\"}'),(517,'messages_texts','{\"success_text\":{\"en-gb\":\"Your request has been sent successfully!\",\"ru-ru\":\"\\u0412\\u0430\\u0448 \\u0437\\u0430\\u043f\\u0440\\u043e\\u0441 \\u0443\\u0441\\u043f\\u0435\\u0448\\u043d\\u043e \\u043e\\u0442\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d!\"},\"error_text\":{\"en-gb\":\"Something went wrong. Please try again\",\"ru-ru\":\"\\u0427\\u0442\\u043e-\\u0442\\u043e \\u043f\\u043e\\u0448\\u043b\\u043e \\u043d\\u0435 \\u0442\\u0430\\u043a. \\u041f\\u043e\\u0436\\u0430\\u043b\\u0443\\u0439\\u0441\\u0442\\u0430, \\u043f\\u043e\\u043f\\u0440\\u043e\\u0431\\u0443\\u0439\\u0442\\u0435 \\u0435\\u0449\\u0435\"}}'),(518,'button_size','{\"width\":\"95\",\"height\":\"95\",\"button_text_size\":\"17\"}'),(519,'colors','{\"button_color\":\"#20bad5\",\"button_text_color\":\"#ffffff\",\"form_color\":\"#ebedf0\",\"send_button_color\":\"#37c0d2\",\"text_on_form_color\":\"#000000\"}');");
    }

    public function dropDbTables(): void
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "xu_callback_button_requests`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "xu_callback_button_settings`");
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
