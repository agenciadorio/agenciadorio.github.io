<?php


class SupsysticSlider_Overview_Controller extends SupsysticSlider_Core_BaseController
{

    public function indexAction(Rsc_Http_Request $request)
    {
        $serverSettings = $this->getServerSettings();
        $config = $this->getEnvironment()->getConfig();
        global $current_user;
		wp_get_current_user();

        return $this->response(
            '@overview/index.twig',
            array(
                'serverSettings' => $serverSettings,
                'news' => $this->loadNews($config['post_url']),
                'contactForm' => array(
                    'name' => $current_user->user_firstname,
                    'email' => $current_user->user_email,
                    'website' => get_bloginfo('url')
                )
            )
        );
    }

    public function sendMailAction(Rsc_Http_Request $request) {
        $mail = $request->post['route']['data'];

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $mail['name'] . ' <' . $mail['email'] . '>'
        );

        $message = array(
            'Name: ' . $mail['name'],
            'E-mail: ' .  $mail['email'],
            'Website: ' . $mail['website'],
            'Subject: ' . $mail['subject'],
            'Topic: ' . str_replace('_', ' ', ucfirst($mail['question'])),
            'Мessage: ' . $mail['message']
        );
        $message = implode('<br>', $message);

        $config = $this->getEnvironment()->getConfig();

        wp_mail($config['mail'], $mail['subject'], $message, $headers);

        $response = array(
            'success' => true,
            'message' => $this->translate('Your message successfully send. We contact you soon.')
        );

        $errors = $this->getMailErrors();
        if (!empty($errors)) {
            $response = array(
                'success' => false,
                'message' => $errors[0]
            );
        }

        return $this->response(Rsc_Http_Response::AJAX, $response);
    }

    protected function getServerSettings() {
        return array(
            'Operating System' => array('value' => PHP_OS),
            'PHP Version' => array('value' => PHP_VERSION),
            'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
            'MySQL' => array('value' => function_exists('mysql_get_server_info') ? @mysql_get_server_info() : __('Undefined')),
            'PHP Safe Mode' => array('value' => ini_get('safe_mode') ? 'Yes' : 'No', 'error' => ini_get('safe_mode')),
            'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? 'Yes' : 'No'),
            'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
            'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
            'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
            'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
            'PHP EXIF Support' => array('value' => extension_loaded('exif') ? 'Yes' : 'No'),
            'PHP EXIF Version' => array('value' => phpversion('exif')),
            'PHP XML Support' => array('value' => extension_loaded('libxml') ? 'Yes' : 'No', 'error' => !extension_loaded('libxml')),
            'PHP CURL Support' => array('value' => extension_loaded('curl') ? 'Yes' : 'No', 'error' => !extension_loaded('curl')),
        );
    }

     protected function getMailErrors() {
        global $ts_mail_errors;
        global $phpmailer;

        if (!isset($ts_mail_errors)) {
            $ts_mail_errors = array();
        }

        if (isset($phpmailer)) {
            if(!empty($phpmailer->ErrorInfo)) {
                $ts_mail_errors[] = $phpmailer->ErrorInfo;
            }
        }

        return $ts_mail_errors;
    }

    protected function loadNews ($url) {
        $news = wp_remote_retrieve_body(wp_remote_get($url));

        return $news;
    }
} 