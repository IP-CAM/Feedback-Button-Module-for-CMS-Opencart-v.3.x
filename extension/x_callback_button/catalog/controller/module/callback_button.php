<?php
namespace Opencart\Catalog\Controller\Extension\XFeedbackButton\Module;

use Opencart\System\Engine\Registry;

class CallbackButton extends \Opencart\System\Engine\Controller
{
    private const EXTENSION_PATH = 'extension/x_feedback_button/module/button';
    private const DEFAULT_NAME_NAME_FIELD = 'Your name';
    private const DEFAULT_NAME_EMAIL_FIELD = 'Your e-mail';
    private const DEFAULT_NAME_PHONE_FIELD = 'Your phone number';
    private const DEFAULT_NAME_COMMENT_FIELD = 'Comment';
    private const DEFAULT_HEADER_TEXT = 'Request a callback';
    private const DEFAULT_SUCCESS_TEXT = 'Your request has been sent successfully!';
    private const DEFAULT_ERROR_TEXT = 'Something went wrong. Please try again';
    private const DEFAULT_BUTTON_TEXT = 'Send';


    private array $data = [];

    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        $this->load->model(self::EXTENSION_PATH);
    }

    public function index(): string
    {
        $this->document->addStyle('extension/x_feedback_button/catalog/view/css/main.css');
        $this->document->addScript('extension/x_feedback_button/catalog/view/js/main.js');

        $this->data = $this->model_extension_x_feedback_button_module_button->getSettings();
        $this->addPositionStyle($this->data);

        $language_code = $this->config->get('config_language');

        if (!$this->data['text_image']['show_image']) {
            $this->data['text_image']['text'] = $this->data['text_image'][$language_code];
        } else {
            $this->load->model('tool/image');
            $this->data['text_image']['img_path'] = $this->model_tool_image->resize(html_entity_decode($this->data['text_image']['img_path'], ENT_QUOTES, 'UTF-8'), 190, 190);
        }

        if (!empty($this->data['header_text'][$language_code])) {
            $this->data['header_text'] = $this->data['header_text'][$language_code];
        } else {
            $this->data['header_text'] = self::DEFAULT_HEADER_TEXT;
        }

        if (!empty($this->data['fields_names']['name_name_field'][$language_code])) {
            $this->data['fields_names']['name_name_field'] = $this->data['fields_names']['name_name_field'][$language_code];
        } else {
            $this->data['fields_names']['name_name_field'] = self::DEFAULT_NAME_NAME_FIELD;
        }

        if (!empty($this->data['fields_names']['name_email_field'][$language_code])) {
            $this->data['fields_names']['name_email_field'] = $this->data['fields_names']['name_email_field'][$language_code];
        } else {
            $this->data['fields_names']['name_email_field'] = self::DEFAULT_NAME_EMAIL_FIELD;
        }

        if (!empty($this->data['fields_names']['name_phone_field'][$language_code])) {
            $this->data['fields_names']['name_phone_field'] = $this->data['fields_names']['name_phone_field'][$language_code];
        } else {
            $this->data['fields_names']['name_phone_field'] = self::DEFAULT_NAME_PHONE_FIELD;
        }

        if (!empty($this->data['fields_names']['name_comment_field'][$language_code])) {
            $this->data['fields_names']['name_comment_field'] = $this->data['fields_names']['name_comment_field'][$language_code];
        } else {
            $this->data['fields_names']['name_comment_field'] = self::DEFAULT_NAME_COMMENT_FIELD;
        }

        if (!empty($this->data['messages_texts']['success_text'][$language_code])) {
            $this->data['messages_texts']['success_text'] = $this->data['messages_texts']['success_text'][$language_code];
        } else {
            $this->data['messages_texts']['success_text'] = self::DEFAULT_SUCCESS_TEXT;
        }

        if (!empty($this->data['messages_texts']['error_text'][$language_code])) {
            $this->data['messages_texts']['error_text'] = $this->data['messages_texts']['error_text'][$language_code];
        } else {
            $this->data['messages_texts']['error_text'] = self::DEFAULT_ERROR_TEXT;
        }

        if (!empty($this->data['button_text'][$language_code])) {
            $this->data['button_text'] = $this->data['button_text'][$language_code];
        } else {
            $this->data['button_text'] = self::DEFAULT_BUTTON_TEXT;
        }

        return $this->load->view(self::EXTENSION_PATH, $this->data);
    }

    public function saveRequest(): void
    {
        $this->model_extension_x_feedback_button_module_button->saveRequest($this->request->post);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['status' => 1]));
    }

    private function addPositionStyle(array $settings): void
    {
        $this->data['button_position'] = $settings['position'];

        if (!$this->data['button_position']['data']['custom']) {
            $this->document->addStyle('extension/x_feedback_button/catalog/view/css/positions/' . $this->data['button_position']['data']['position'] . '.css');
            $this->data['button_position'] = null;
        }
    }
}
