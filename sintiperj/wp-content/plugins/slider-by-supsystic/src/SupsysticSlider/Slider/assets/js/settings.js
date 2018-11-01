//clone jquery Fix
(function (original) {
  jQuery.fn.clone = function () {
    var result           = original.apply(this, arguments),
        my_textareas     = this.find('textarea').add(this.filter('textarea')),
        result_textareas = result.find('textarea').add(result.filter('textarea')),
        my_selects       = this.find('select').add(this.filter('select')),
        result_selects   = result.find('select').add(result.filter('select'));

    for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
    for (var i = 0, l = my_selects.length;   i < l; ++i) {
      for (var j = 0, m = my_selects[i].options.length; j < m; ++j) {
        if (my_selects[i].options[j].selected === true) {
          result_selects[i].options[j].selected = true;
        }
      }
    }
    return result;
  };
}) (jQuery.fn.clone);

(function ($, WordPress) {

    /**
     * Page Controller.
     *
     * @constructor
     */
    function Controller() {
        this.$submit = $('button#save');
        this.$form = $('form#settings');
        this.$pluginBtn = $('button#change');
        this.$pluginWindow = $('#changePluginWindow');
        this.$addVideoCoin = $('button#uploadVideoCoin');
        this.$avPluginWindow = $('#avChangePluginWindow');
        this.$shadowDialog = $('#selectShadowDialog');
        this.$importDialog = $('#choose-import-dialog');
        this.$deleteBtn = $('button#delete');
        this.$randomCheckbox = $('#generalRandomStart');
        this.$thumbArrowsCheckbox = $('#generalhideArrowsForThumbs');
        this.$verticalArrowsModeCheckbox = $('#generalVerticalArrowsMode');
        this.$addPage = $('.add-page');
        this.$addPost = $('.add-post');
        this.$showTitle = $('#post-feed-selectShowTitle');
        this.$optimizeTitle = $('#post-feed-selectOptimizeTitle');
        this.$showDate = $('#post-feed-selectShowDate');
        this.$showExcerpt = $('#post-feed-selectShowExcerpt');
        this.$showReadMore = $('#post-feed-selectShowReadMore');
        this.$addImages = $('#add-images');
        this.changed = null;
        this.saved = null;

        this.init();
    }

    /**
     *
     * toggle random slide start checkbox value
     *
     * @type {Function}
     */
    Controller.prototype.randomToggle = (function() {
        this.$randomCheckbox.on('click', function() {
            if(parseInt($(this).val(), 10)) {
                $(this).attr('value', '0');
            } else {
                $(this).attr('value', '1');
            }
        });
    });

    Controller.prototype.thumbArrowsCheckbox = (function() {
        this.$thumbArrowsCheckbox.on('click', function() {
            if(parseInt($(this).val(), 10)) {
                $(this).attr('value', '0');
            } else {
                $(this).attr('value', '1');
            }
        });
    });

    /**
     *
     * toggle vertical arrows mode checkbox value
     *
     * @type {Function}
     */
    Controller.prototype.verticalArrowsModeToggle = (function() {
        this.$verticalArrowsModeCheckbox.on('click', function() {
            if(parseInt($(this).val(), 10)) {
                $(this).attr('value', '0');
            } else {
                $(this).attr('value', '1');
            }
        });
    });

    Controller.prototype.formNavigation = (function() {
        var $buttons = $('form .add-new-h2');

        $buttons.on('click', function() {
            var $container = $(this).closest('tr');
            $container.find('.add-new-h2').removeClass('active');
            $buttons.css('background-color', 'white');
            $(this).addClass('active');
        });
    });

    Controller.prototype.checkWidth = (function() {
        var $widthType = $('[name="properties[widthType]"]'),
            $height = $('[name="properties[height]"]'),
            $width = $('[name="properties[width]"]'),
			notyParams = {
				layout: 'topRight',
				type: 'warning',
				text : '<h3>Warning</h3>Max width in percents is equal to 100',
				timeout: 2000,
				animation: {
					open: 'animated flipInX',
					close: 'animated flipOutX',
					easing: 'swing',
					speed: '800'
				}
			};

		$width.on('change', function() {
			if($widthType.val() == '%') {
				if(parseInt($(this).val()) > 100) {
					noty(notyParams);
				}
			}
		});

        $widthType.on('change', function() {
            if($(this).val() == '%') {

                if(parseInt($width.val()) > 100) {
                    $width.val('100');
					noty(notyParams);
                }
            } else {
                $height.attr('disabled', false);
            }
        });
    });

    Controller.prototype.addPages = function() {
        var sliderId = parseInt($('#sliderID').attr('value'));

        this.$addPage.on('click', function() {
            var postId = $('#post-feed-selectPages').val();

            $.post(WordPress.ajax.settings.url,
                {
                    id: postId,
                    slider : sliderId,
                    type: 'page',
                    action: 'supsystic-slider',
                    route: {
                        module: 'slider',
                        action: 'addPost'
                    }
                })
                .success(function (response) {
                    $.jGrowl(response.message);
                    window.location.reload(true);
                });
        });
    };

    Controller.prototype.addPosts = function() {
        var sliderId = parseInt($('#sliderID').attr('value'));

        this.$addPost.on('click', function() {
            var postId = $('#post-feed-selectPosts').val();

            $.post(WordPress.ajax.settings.url,
                {
                    id: postId,
                    slider : sliderId,
                    action: 'supsystic-slider',
                    route: {
                        module: 'slider',
                        action: 'addPost'
                    }
                })
                .success(function (response) {
                    $.jGrowl(response.message);
                    window.location.reload(true);
                });
        });
    };

    Controller.prototype.initPostsTable = function() {

        $("#posts-table").jqGrid({
            datatype: "local",
            autowidth: true,
            shrinkToFit: true,
            colNames:['Id', 'Image','Title'],
            colModel:[
                {name:'id', index:'id', sortable: false, width: 20, align: 'center'},
                {name:'image',index:'image', sortable: false, width: 60, align: 'center'},
                {name:'title', sortable: false, index:'title', align: 'center'}
            ],
            height: 'auto'
        });
    };

    Controller.prototype.fillPostsTable = function() {
        var posts = [],
            sliderId = parseInt($('#sliderID').attr('value')),
            self = this;

        $.post(WordPress.ajax.settings.url,
            {
                slider : sliderId,
                size: 'thumbnail',
                action: 'supsystic-slider',
                route: {
                    module: 'slider',
                    action: 'getPosts'
                }
            })
            .success(function (response) {

                $.each(response.elements, function(index, value) {
					var imageUrl = value.imageUrl ? value.imageUrl : value.url
					,	data = {
                        'id': '<input type="checkbox" class="post-cbox" value="' + value.id + '">',
                        'image': '<img src=' + imageUrl + ' width="100" height="100">',
                        'title': value.title
                    };

                    jQuery("#posts-table").jqGrid('addRowData', index, data);
                });
                self.showPostsTable();
                //$.jGrowl(response.message);
            });
    };

    Controller.prototype.deletePosts = function() {
        var sliderId = parseInt($('#sliderID').attr('value')),
            $button  = $('.remove-post');

        $button.on('click', function() {
            var $cboxSelected = $('.post-cbox:checked'),
                postsSelected = [];
            $.each($cboxSelected, function(index, value) {
                postsSelected.push($(value).val());
            });
            if($cboxSelected.length) {
                $.post(WordPress.ajax.settings.url,
                    {
                        slider : sliderId,
                        posts: postsSelected,
                        action: 'supsystic-slider',
                        route: {
                            module: 'slider',
                            action: 'deletePosts'
                        }
                    })
                    .success(function (response) {
                        console.log(response);
                        window.location.reload(true);
                    });
            }
        });
    };

	Controller.prototype.showTitle = function() {
		this.$showTitle.on('change', function () {
			$('#settings').find('input[name="post_settings[title]"]').val($(this).val());
		});
	};

	Controller.prototype.optimizeTitle = function() {
		this.$optimizeTitle.on('change', function () {
			$('#settings').find('input[name="post_settings[optimize_title]"]').val($(this).val());
		});
	};

	Controller.prototype.showDate = function() {
		this.$showDate.on('change', function () {
			$('#settings').find('input[name="post_settings[date]"]').val($(this).val());
		});
	};

	Controller.prototype.showExcerpt = function() {
		this.$showExcerpt.on('change', function () {
			$('#settings').find('input[name="post_settings[excerpt]"]').val($(this).val());
		});
	};

	Controller.prototype.showReadMore = function() {
		this.$showReadMore.on('change', function () {
			$('#settings').find('input[name="post_settings[read_more]"]').val($(this).val());
		});
	};

    Controller.prototype.initShadowDialog = (function () {
        this.$shadowDialog.dialog({
            modal:    true,
            width:    560,
            autoOpen: false
        });
    });

    Controller.prototype.initShadowSelection = (function () {
        var self = this;

        this.$shadowDialog.find('img').on('click', function () {
            $('#propertiesShadow').attr('value', $(this).data('value'));
            self.$shadowDialog.dialog('close');
        });
    });

    Controller.prototype.openShadowDialog = function() {
        var button = $('#select-shadow'),
            self = this;

        button.on('click', function(e) {
            e.preventDefault();
            self.$shadowDialog.dialog('open');
        });
    };

    Controller.prototype.initImportDialog = (function () {
        this.$importDialog.dialog({
            modal:    false,	// If set 'true' - search field in the wordpress media library will stop working
            width:    560,
            autoOpen: false
        });
    });

    Controller.prototype.openImportDialog = function() {
        var button = $('#choose-import'),
            self = this;

        button.on('click', function(e) {
            e.preventDefault();
            self.$importDialog.dialog('open');
        });
    };

    Controller.prototype.featuresNotices = function() {
        var $builderButton = $('#free-builder'),
            $videoButton = $('#free-video'),
            $builderNotice = $('#bx-editor-notice'),
            $videoNotice = $('#free-video-notice');

        $builderNotice.dialog({
            modal:    true,
            width:    940,
            autoOpen: false,
            buttons: {
            }
        });

        $builderButton.on('click', function() {
            $builderNotice.dialog('open');
        });

        $videoNotice.dialog({
            modal:    true,
            width:    460,
            autoOpen: false,
            buttons: {
            }
        });

        $videoButton.on('click', function() {
            $videoNotice.dialog('open');
        });

        /*$videoButton.on('click', function() {
            var notification = noty({
                layout: 'topCenter',
                type: 'information',
                text : '<h3>Notice</h3>You can import YouTube and Vimeo videos in PRO version</br><a class="button button-primary" href="http://supsystic.com/plugins/slider?utm_source=plugin&utm_medium=video&utm_campaign=slider" style="margin: 10px;">Get Pro</a>',
                timeout: false,
                animation: {
                    open: 'animated flipInX',
                    close: 'animated flipOutX',
                    easing: 'swing',
                    speed: '800'
                }
            });
        });*/
    };

    Controller.prototype.toggleChanges = function() {
        var $elements = $('input, select');

        $elements.on('change', $.proxy(function() {
            this.changed = true;
        }, this));
    };

    Controller.prototype.initAddImagesLink = function() {

        this.$addImages.on('click', function(e) {
            e.preventDefault();

            $('#choose-import').trigger('click');
        });
    };

    /**
     * Init controller.
     *
     * @type {Function}
     */
    Controller.prototype.init = (function () {
        this.intiPluginSelectWindow();
        this.initAvPluginSelectWindow();

        this.$submit.on('click', $.proxy(this.submit, this));
        this.$pluginBtn.on('click', $.proxy(this.openPluginSelectWindow, this));
        this.$addVideoCoin.on('click', $.proxy(this.openAvPluginSelectWindow, this));
        this.$deleteBtn.on('click', $.proxy(this.deleteSlider, this));
        this.$pluginWindow.find('form').submit(function (e) {
            e.preventDefault();
        });
        this.$avPluginWindow.find('form').submit(function (e) {
            e.preventDefault();
        });

        this.randomToggle();
        this.thumbArrowsCheckbox();
        this.verticalArrowsModeToggle();
        this.formNavigation();
        this.checkWidth();
        this.addPages();
        this.addPosts();
        this.initPostsTable();
        this.fillPostsTable();
        this.deletePosts();
		this.showTitle();
		this.optimizeTitle();
		this.showDate();
		this.showExcerpt();
		this.showReadMore();
        this.initShadowDialog();
        this.openShadowDialog();
        this.initImportDialog();
        this.featuresNotices();
        this.toggleChanges();
        this.openImportDialog();
        this.initAddImagesLink();
        this.initCodeSelection();
        this.initImport();
        //this.initShadowSelection();
    });

    /**
     * Removes the slider.
     * @type {Function}
     */
    Controller.prototype.deleteSlider = (function (e) {
        var id = this.$deleteBtn.data('id'),
            redirectUri = this.$deleteBtn.data('redirect-uri'),
            confirmMsg = this.$deleteBtn.data('confirm');

        if (!confirm(confirmMsg)) {
            e.preventDefault();

            return;
        }

        $.post(WordPress.ajax.settings.url, { id: id, action: 'supsystic-slider', route: { module: 'slider', action: 'delete' } })
            .success(function (response) {
                if (!response.error) {
                    window.location.href = redirectUri;
                }

                $.jGrowl(response.message);
        });
    });

    /**
     * Submit form.
     *
     * @type {Function}
     */
    Controller.prototype.submit = (function () {
        this.saved = true;
        this.$form.append($('#auto-posts').closest('table').hide());
        this.$form.submit();
    });

    Controller.prototype.intiPluginSelectWindow = (function () {
        var self = this;

        this.$pluginWindow.dialog({
            modal:    true,
            width:    400,
            autoOpen: false
            /*buttons:  {
                Change:   function () {
                    $.post(
                        WordPress.ajax.settings.url,
                        $('form#changePlugin').serialize()
                    ).success(
                            $.proxy(
                                function(response) {
                                    if (!response.error) {
                                        self.saved = true;
                                        window.location.reload(true);
                                    } else {
                                        $.jGrowl(response.message);
                                    }
                                },
                                this
                            )
                        );
                },
                Cancel: function () {
                    $(this).dialog('close');
                }
            }*/
        });

        jQuery('#changePlugin_changeBtn').click(function() {
            $.post(
                WordPress.ajax.settings.url,
                $('form#changePlugin').serialize()
            ).success(
                $.proxy(
                    function(response) {
                        if (!response.error) {
                            self.saved = true;
                            window.location.reload(true);
                        } else {
                            $.jGrowl(response.message);
                        }
                    },
                    this
                )
            );
        });

        var sliderNameChangeTimer = null;
        var changePluginName = function(event) {
            clearTimeout(sliderNameChangeTimer);

            sliderNameChangeTimer = setTimeout(function() {
                 $.post(
                    WordPress.ajax.settings.url,
                    $('form#change-name').serialize()
                ).success(
                    $.proxy(
                        function(response) {
                            if (response.error) {
                                $.jGrowl(response.message);
                            }
                        },
                        this
                    )
                );
            }, 150);
        };

        jQuery(document).on('change', '#change-name input[name="title"]', changePluginName);
        jQuery(document).on('keypress', '#change-name input[name="title"]', changePluginName);
        jQuery(document).on('keyup', '#change-name input[name="title"]', changePluginName);
        jQuery(document).on('keydown', '#change-name input[name="title"]', changePluginName);

        jQuery('#changePlugin_cancelBtn').click(function() {
            $('#changePluginWindow').dialog('close');
        });
    });

    Controller.prototype.openPluginSelectWindow = (function () {
        this.$pluginWindow.dialog('open');
    });

    Controller.prototype.initAvPluginSelectWindow = (function () {
        var self = this;

        this.$avPluginWindow.dialog({
            modal:    true,
            width:    400,
            autoOpen: false
        });

        jQuery('#avChangePlugin_changeBtn').click(function() {
            $.post(
                WordPress.ajax.settings.url,
                $('form#avChangePlugin').serialize()
            ).success(
                $.proxy(
                    function(response) {
                        if (!response.error) {
                            self.saved = true;
                            window.location.reload(true);
                        } else {
                            $.jGrowl(response.message);
                        }
                    },
                    this
                )
            );
        });

        jQuery('button#avChangePlugin_cancelBtn').click(function() {
            $('#avChangePluginWindow').dialog('close');
        });
    });

    Controller.prototype.openAvPluginSelectWindow = (function () {
        this.$avPluginWindow.dialog('open');
    });

    Controller.prototype.showPostsTable = function() {
        var $table = $('#gbox_posts-table .ui-jqgrid-btable'),
            rowsNumber = $table.find('.jqgrow').length;
        if(!rowsNumber) {
            $('.post-and-pages').addClass('empty').hide();
        }
    };

    Controller.prototype.initNoticeDialog = function() {
        $('#reviewNotice').dialog({
            modal:    true,
            width:    600,
            autoOpen: true
        });
    };

    Controller.prototype.initCodeSelection = function() {
        jQuery('span#propsShortcode').click(function() {
            var e=this, s, r;
            if(window.getSelection) {
                s = window.getSelection();
                if(s.setBaseAndExtent){
                    s.setBaseAndExtent(e,0,e,e.innerText.length-1);
                }else{
                    r=document.createRange();
                    r.selectNodeContents(e);
                    s.removeAllRanges();
                    s.addRange(r);}
            }else if(document.getSelection) {
                s=document.getSelection();
                r=document.createRange();
                r.selectNodeContents(e);
                s.removeAllRanges();
                s.addRange(r);
            }else if(document.selection) {
                r=document.body.createTextRange();
                r.moveToElementText(e);
                r.select();
            }
        });
    };

    Controller.prototype.initImport = function() {
        sliderId = parseInt($('[name="sliderId"]').val(), 10);
        var $wrapper = $('#settingsImportDialog');

        $wrapper.dialog({
            autoOpen: false,
            modal:    true,
            width:    460,
            buttons:  [
                {
                    text: 'Cancel',
                    click: function () {
                        $(this).dialog('close');
                    },
                },
                {
                    id: 'import-confirm-button',
                    text: 'Import',
                    click: function () {
                        $.post(window.wp.ajax.settings.url, {
                            action: 'supsystic-slider',
                            route: {
                                module: 'slider',
                                action: 'importSettings'
                            },
                            from: $(this).find('.list').val(),
                            to: sliderId
                        }).success(function(response) {
                            if (response.success) {
                                window.location.reload();
                            }
                        });
                    },
                }
            ]
        });

        $(document).on('click', '#openSettingsImportDialog', function(event) {
            event.preventDefault();

            $wrapper.dialog('open');
            if ($wrapper.find('.import').is(':hidden')) {
                $('#import-confirm-button').hide();
            }
        });
    };

    $(document).ready(function () {
        WordPress.settings = new Controller();
    });

    /*$(window).on('beforeunload', function(e) {
        if(WordPress.settings.changed && !WordPress.settings.saved) {
            return 'You have unsaved changes';
        }
    });*/

}(jQuery, window.wp = window.wp || {}));