<?php


class SupsysticSlider_Slider_Module extends SupsysticSlider_Core_BaseModule
{

    /** Config item with the available sliders. */
    const AVAILABLE_SLIDERS = 'plugin_sliders';

    /**
     * Module initialization.
     */
    public function onInit()
    {
        /** @var SupsysticSlider_Slider_Controller $controller */
        $controller = $this->getController();
        $dispatcher = $this->getEnvironment()->getDispatcher();
        $twig = $this->getEnvironment()->getTwig();

        $translate = new Twig_SimpleFunction('translate', array($this->getController(), 'translate'));
        $unserialize = new Twig_SimpleFunction('unserialize_twig', 'unserialize');

        $twig->addFunction($translate);
        $twig->addFunction($unserialize);

        $twig->addFunction(
            new Twig_SimpleFunction(
                'all_posts',
                'get_posts'
            )
        );

        $twig->addFunction(
            new Twig_SimpleFunction(
                'all_pages',
                'get_pages'
            )
        );
		
		$twig->addFunction(
            new Twig_SimpleFunction(
                'all_categories',
                'get_categories'
            )
        );

        $twig->addFunction(
            new Twig_SimpleFunction(
                'get_image_src',
                'wp_get_attachment_image_src'
            )
        );


        // Loads module assets.
        $dispatcher->on('after_ui_loaded', array($this, 'loadAssets'));

        // Find all sliders after all modules has been loaded.
        $dispatcher->on('after_modules_loaded', array($this, 'findSliders'));

        // Load twig extensions.
        $dispatcher->on('after_modules_loaded', array($this, 'loadExtensions'));

        // If one of the photo will be removed from database
        // we'll remove it from the slider automatically.
        $dispatcher->on(
            'photos_delete_by_id',
            array($controller->getModel('resources'), 'deletePhotoById')
        );

        // Add shortcode
        add_shortcode('supsystic-slider', array($this, 'render'));

        add_action( 'widgets_init', array($this, 'registerWidget'));

        // Register menu items.
        $dispatcher->on(
            'ui_menu_items',
            array($this, 'addNewSliderMenuItem'),
            1,
            0
        );

        if ($this->isPluginPage()) {
            $this->reviewNoticeCheck();
        }

        $this->initVideoMetaBox();
    }

    function addPageVideoMetaBox() {
        add_meta_box('video_meta_box', 'Featured Video', array($this, 'showPageVideoMetaBox'), 'page', 'normal', 'high');
    }

    function addPostVideoMetaBox() {
        add_meta_box('video_meta_box', 'Featured Video', array($this, 'showPostVideoMetaBox'), 'post', 'normal', 'high');
    }

    function initVideoMetaBox() {
        add_action('add_meta_boxes', array($this, 'addPageVideoMetaBox'));
        add_action('add_meta_boxes', array($this, 'addPostVideoMetaBox'));
        add_action('save_post', array($this, 'saveVideoMeta'));
    }

    protected $videoMetaField = array(
        'label' => 'Video URL',
        'desc' => 'Paste video URL here',
        'id' => 'featured_video',
        'type' => 'textarea'
    );

    function showPageVideoMetaBox() {
        $this->showVideoMetaBox();
    }

    function showPostVideoMetaBox() {
        $this->showVideoMetaBox();
    }

    function showVideoMetaBox() {
        global $post;

        wp_nonce_field( basename( __FILE__ ), 'video_meta_box_nonce' );
        echo '<table class="form-table">';

        $field = $this->videoMetaField;
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>
				<th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
				<td>';
        switch ($field['type']) {
            case 'textarea':
                echo '<textarea style="width:100%" id="' . $field['id'] . '" name="' . $field['id'] . '">' . $meta . '</textarea>
                                                    <br /><span class="description">' . $field['desc'] . '</span>';
                break;
        }
        echo '</td></tr>';

        echo '</table>';
    }

    function saveVideoMeta($postID) {
        if (!isset($_POST['video_meta_box_nonce']) || !wp_verify_nonce($_POST['video_meta_box_nonce'], basename(__FILE__))) {
            return $postID;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $postID;
        }

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $postID)) {
                return $postID;
            }
        } elseif (!current_user_can('edit_post', $postID)) {
            return $postID;
        }

        $field = $this->videoMetaField['id'];

        if (isset($_POST[$field]) && false !== filter_var($_POST[$field], FILTER_VALIDATE_URL)) {
            $value = get_post_meta($postID, $field, true);
            $newValue = $_POST[$field];

            if ($newValue && $newValue != $value) {
                update_post_meta($postID, $field, $newValue);
            } elseif ('' == $newValue && $value) {
                delete_post_meta($postID, $field, $value);
            }
        }
    }

    /**
     * Loads module assets.
     * @param SupsysticSlider_Ui_Module $ui UI Module.
     */
    public function loadAssets(SupsysticSlider_Ui_Module $ui)
    {
        $environment = $this->getEnvironment();
        $preventCaching = $environment->isDev();

        if($environment->getPluginName() != 'ssl'){
            return;
        }

        if (!$environment->isModule('slider')) {
            return;
        }

        $ui->add(
            new SupsysticSlider_Ui_BackendJavascript(
                'supsysticSlider-slider-noty',
                $this->getLocationUrl() . '/assets/js/noty/js/noty/packaged/jquery.noty.packaged.min.js',
                $preventCaching
            )
        );

        $ui->add(
            new SupsysticSlider_Ui_BackendStylesheet(
                'supsysticSlider-slider-styles',
                $this->getLocationUrl() . '/assets/css/slider.css',
                $preventCaching
            )
        );

        if ($environment->isAction('index') || $environment->isAction('showPresets')) {
            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-index',
                    $this->getLocationUrl() . '/assets/js/index.js',
                    $preventCaching
                )
            );
        }

        if ($environment->isAction('add')) {
            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-add',
                    $this->getLocationUrl() . '/assets/js/add.js',
                    $preventCaching
                )
            );
        }

        if ($environment->isAction('view')) {


            $ui->add(
                new SupsysticSlider_Ui_BackendStylesheet(
                    'supsystic-slider-chosen-css',
                    $this->getLocationUrl() . '/assets/css/chosen.css',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsystic-slider-chosen-js',
                    '//oss.maxcdn.com/chosen/1.1.0/chosen.jquery.min.js',
                    $preventCaching
                )
            );
        

            $ui->add(
                new SupsysticSlider_Ui_BackendStylesheet(
                    'supsysticSlider-slider-stylesAnimate',
                    $this->getLocationUrl() . '/assets/css/animate.css',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendStylesheet(
                    'wp-color-picker'
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-frontend',
                    $this->getLocationUrl() . '/assets/js/frontend.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-settingsTabs',
                    $this->getLocationUrl() . '/assets/js/settings-tabs.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-settings',
                    $this->getLocationUrl() . '/assets/js/settings.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-view',
                    $this->getLocationUrl() . '/assets/js/view.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-viewToolbar',
                    $this->getLocationUrl() . '/assets/js/view-toolbar.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-sorting',
                    $this->getLocationUrl() . '/assets/js/sorting.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-preview',
                    $this->getLocationUrl() . '/assets/js/preview.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-lettering',
                    $this->getLocationUrl() . '/assets/js/jquery.lettering.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-texttillate',
                    $this->getLocationUrl() . '/assets/js/jquery.textillate.js',
                    $preventCaching
                )
            );

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-slider-easing',
                    '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js',
                    $preventCaching
                )
            );

            // Visual Editor.
            $veditor = new SupsysticSlider_Ui_BackendJavascript(
                'supsysticSlider-slider-veditor',
                $this->getLocationUrl() . '/assets/js/visual-editor.js',
                $preventCaching
            );

            $veditor->setDeps(array('wp-color-picker'));

            $ui->add($veditor);

            $ui->add(
                new SupsysticSlider_Ui_BackendJavascript(
                    'supsysticSlider-google-fl-preview',
                    '//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js',
                    $preventCaching
                )
            );
        }
    }

    /**
     * Loads Twig extensions.
     */
    public function loadExtensions()
    {
        $twig = $this->getEnvironment()->getTwig();

        $twig->addExtension(new SupsysticSlider_Slider_Twig_Attachment());
    }

    public function enqueueFrontendJavascript() {
        $dispatcher = $this->getEnvironment()->getDispatcher();
        $dispatcher->dispatch('bx_enqueue_javascript');

        wp_enqueue_script(
            'supsysticSlider-slider-frontend',
            $this->getLocationUrl() . '/assets/js/frontend.js',
            array(),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'supsysticSlider-slider-lettering',
            $this->getLocationUrl() . '/assets/js/jquery.lettering.js',
            array(),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'supsysticSlider-slider-texttillate',
            $this->getLocationUrl() . '/assets/js/jquery.textillate.js',
            array(),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'supsysticSlider-slider-easing',
            '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js',
            array(),
            '1.0.0',
            true
        );

    }

    public function enqueueFrontendStylesheet() {
        wp_enqueue_style(
            'rs-shadows-css',
            $this->getConfig()->get('plugin_url') . '/app/assets/css/shadows.css',
            array(),
            '1.0.0'
        );
        wp_enqueue_style(
            'supsysticSlider-slider-stylesAnimateLetters',
            $this->getLocationUrl() . '/assets/css/animate.css',
            array(),
            '1.0.0'
        );
    }

    public function render($attributes)
    {
        if (!isset($attributes['id'])) {
            // @TODO: Maybe we need to show error message here.
            return;
        }

        /** @var string|int $id */
        $id = $attributes['id'];
        /** @var SupsysticSlider_Slider_Controller $controller */
        $controller = $this->getController();
        /** @var SupsysticSlider_Slider_Model_Sliders $sliders */
        $sliders = $controller->getModel('sliders');
        $slider  = $sliders->getById((int)$id);
        $twig = $this->getEnvironment()->getTwig();

        if (!$slider) {
            // @TODO: Maybe we need to show error message here.
            return;
        }
        
        /** @var SupsysticSlider_Slider_Interface $module */
        $module = $this->getEnvironment()->getModule($slider->plugin);

        if (!$module) {
            return;
        }

        add_action('wp_footer', array($module, 'enqueueJavascript'));
        add_action('wp_footer', array($module, 'enqueueStylesheet'));

        add_action('wp_footer', array($this, 'enqueueFrontendJavascript'));
        add_action('wp_footer', array($this, 'enqueueFrontendStylesheet'));


        $cacheModule = $this->getModule('cache');
        $cache = $cacheModule->get($id);

        if ($cache !== false) {
			return do_shortcode($cache);
        }


        if (isset($attributes['width'])) {
            $slider->settings['properties']['width'] = $attributes['width'];
        }

        if (isset($attributes['height'])) {
            $slider->settings['properties']['height'] = $attributes['height'];
        }

		if (isset($attributes['position'])) {
			$slider->settings['properties']['position'] = $attributes['position'];
		}

		if (isset($attributes['start'])) {
			$slider->settings['properties']['start'] = $attributes['start'];
		}


        $posts = $this->getController()->getModel('settings')->getPosts($slider->id, 'full');

        $slider->posts = $posts;

        $extendedAttachmentOptions = array(
            'slideHtml' => 'html',
            'cropPosition' => 'cropPosition',
        );

        foreach($slider->images as $key => $value) {
            foreach ($extendedAttachmentOptions as $field => $attachmentKey) {
                $attachmentData = get_post_meta($value->attachment_id, $field);
                $slider->images[$key]->attachment[$attachmentKey] = reset($attachmentData);
            }
            if(defined('$slider->entities[$key]->url')) {
                $slider->entities[$key]->attachment['service'] = $this->getService($slider, $key);
            }
        }

        $data = $module->render($slider);
		$data = preg_replace('/\s+/', ' ', $data);	//Remove multiple spaces for prevent applying of wpautop filter / function
		$cacheModule->set($id, $data);
        return do_shortcode($data);
    }

    /**
     * Finds all modules that implement SupsysticSlider_Slider_Interface
     * and registers as sliders.
     */
    public function findSliders()
    {
        $environment = $this->getEnvironment();

        $config  = $environment->getConfig();
        $modules = $environment
            ->getResolver()
            ->getModulesList();

        if (!$config->has(self::AVAILABLE_SLIDERS)) {
            $config->add(self::AVAILABLE_SLIDERS, array());
        }

        $available = $config->get(self::AVAILABLE_SLIDERS);

        if ($modules->isEmpty()) {
            return;
        }

        foreach ($modules as $module) {
            if ($module instanceof SupsysticSlider_Slider_Interface) {
                $available[] = $module;
            }
        }

        $config->set(self::AVAILABLE_SLIDERS, $available);
    }

    /**
     * Returns array with the available sliders.
     *
     * @return SupsysticSlider_Slider_Info[]
     */
    public function getAvailableSliders()
    {
        return $this->getEnvironment()
            ->getConfig()
            ->get(
                self::AVAILABLE_SLIDERS,
                array()
            );
    }

    public function registerWidget() {
        register_widget( 'sslWidget' );
    }

    public function addNewSliderMenuItem()
    {
        $menu = $this->getEnvironment()->getMenu();

        $plugin_menu = $this->getConfig()->get('plugin_menu');
        $capability = $plugin_menu['capability'];

        $submenuNewSlider = $menu->createSubmenuItem();
        $submenuNewSlider->setCapability($capability)
            //->setMenuSlug('supsystic-slider&module=slider&action=index&add=true')
			->setMenuSlug('supsystic-slider&module=slider&action=showPresets')
            ->setMenuTitle($this->translate('New Slider'))
            ->setPageTitle($this->translate('New Slider'))
            ->setModuleName('slider');
		// Avoid conflicts with old vendor version
		if(method_exists($submenuNewSlider, 'setSortOrder')) {
			$submenuNewSlider->setSortOrder(20);
		}

        $menu->addSubmenuItem('newSlider', $submenuNewSlider);
//            ->register();

        $submenuSliders = $menu->createSubmenuItem();
        $submenuSliders->setCapability($capability)
            ->setMenuSlug('supsystic-slider&module=slider')
            ->setMenuTitle($this->translate('Sliders'))
            ->setPageTitle($this->translate('Sliders'))
            ->setModuleName('slider');
		// Avoid conflicts with old vendor version
		if(method_exists($submenuSliders, 'setSortOrder')) {
			$submenuSliders->setSortOrder(30);
		}

        $menu->addSubmenuItem('sliders', $submenuSliders);
    }

    protected function getService($slider, $index) {
        foreach (array('youtube', 'vimeo') as $service) {
            //$pattern = sprintf('/%s/i', $service);

            if (strpos($slider->entities[$index]->url, $service)) {
                return $service;
            }
        }
    }

    public function reviewNoticeCheck() {
        $option = $this->getConfig()->get('db_prefix') . 'reviewNotice';
        $notice = get_option($option);
        if (!$notice) {
            update_option($option, array(
                'time' => time() + (60 * 60 * 24 * 7),
                'is_shown' => false
            ));
        } elseif ($notice['is_shown'] === false && (isset($notice['time']) && time() > $notice['time'])) {
            add_action('admin_notices', array($this, 'showReviewNotice')); 
        }
    }

    public function showReviewNotice() {
    	$twig = $this->getTwig();
        print $twig->display('@slider/notice/review.twig');
    }
}

require_once('Model/widget.php');
