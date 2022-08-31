<?php
namespace Opencart\Admin\Controller\Extension\XFeedbackButton\Module;

use Opencart\System\Engine\Registry;
use \Opencart\System\Helper AS Helper;

class Button extends \Opencart\System\Engine\Controller
{
    private const EXTENSION_PATH = 'extension/x_feedback_button/module/button';

    private array $languages;

    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        $this->load->model('localisation/language');
        $this->load->language(self::EXTENSION_PATH);
        $this->languages = $this->model_localisation_language->getLanguages();
        $this->load->model(self::EXTENSION_PATH);
    }

    public function index(): void
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('/extension/x_feedback_button/admin/view/js/main.js');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link(self::EXTENSION_PATH, 'user_token=' . $this->session->data['user_token'])
        ];

        $data['button_settings'] = $this->model_extension_x_feedback_button_module_button->getSettings();

        if ($data['button_settings']['text_image']['show_image']) {
            $this->load->model('tool/image');
            $data['button_settings']['text_image']['img_thumb'] = $this->model_tool_image->resize(html_entity_decode($data['button_settings']['text_image']['img_path'], ENT_QUOTES, 'UTF-8'), 190, 190);
        }

        $data['requests'] = $this->model_extension_x_feedback_button_module_button->getRequests();

        $data['user_token'] = $this->session->data['user_token'];
       // echo '<pre>'; print_r($data['requests']); echo '</pre>';

        $data['languages'] = $this->languages;

        $data['module_button_status'] = $this->config->get('module_button_status');
        $data['save'] = $this->url->link(self::EXTENSION_PATH . '|save', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view(self::EXTENSION_PATH, $data));
    }

    public function save(): void
    {
        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/opencart/module/latest')) {
            $json['error']['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['show_image']) {
            foreach ($this->languages as $language) {
                if (trim(empty($this->request->post['button_text'][$language['code']])) || Helper\Utf8\strlen($this->request->post['button_text'][$language['code']]) > 30) {
                    $json['error']['button-text-' . $language['code']] = $this->language->get('error_button_text');
                }
            }
        }

        foreach ($this->languages as $language) {
            if (trim(empty($this->request->post['header_text'][$language['code']])) || Helper\Utf8\strlen($this->request->post['header_text'][$language['code']]) > 30) {
                $json['error']['header-text-' . $language['code']] = $this->language->get('error_header_text');
            }
        }

        if (!$json) {
            $this->model_extension_x_feedback_button_module_button->saveSettings($this->request->post);

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('module_button', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveRequest(): void
    {
        $this->model_extension_x_feedback_button_module_button->saveRequest($this->request->post);
        $this->response->setOutput(json_encode(['success' => true]));
    }

    public function deleteRequest(): void
    {
        $this->model_extension_x_feedback_button_module_button->deleteRequest($this->request->post);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['success' => true]));
    }
}