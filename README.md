# Modal - A solid contao modal window framework

Modal provides modal elements within modal archives. It works best together with *'heimrichhannot/contao-teaser'* and its teaser content elements.
By default it comes with Bootstrap 3 modal support.

## Features

- Url alias support with backlinks (browser history)
- extendable for other modal frameworks/libraries
- custom header/footer
- content elements within modal body
- inserttags
- works together with `contao-disclaimer`

### Insertags

 Insert-Tag | Description
 ---------- | ----------
`{{modal_url::*::*::*}}` | This link will be replaced with the modal url `<a href="{{modal_url::1::home}}">Hier klicken</a>`. Replace arguments: (1st - modal alias or id, 2nd - page alias or id where jump to, 3rd - link text & title)
`{{modal_link::*::*}}` | This link will be replaced with the modal link. Arguments: (1st - modal alias or id, 2nd - page alias or id where jump to)
`{{modal_link_open::*::*}}` | This link will be replaced with the modal link opening tag `<a href="{{modal_url::1::home}}">`. Add your custom text next. Arguments: (1st - modal alias or id, 2nd - page alias or id where jump to)
`{{modal_link_close::*}}` | This link will be replaced with the modal link closing tag `</a>`. Requires associated {{modal_link_open}} tag.
 
 
### Hooks

Name | Arguments | Expected return value | Description
 ---------- | ---------- | ---------- | ---------
generateModalUrl | $arrRow, $strParams, $strUrl | $strUrl | Modify the modal url.
generateModal | $objTemplate, $objModel, $objConfig, $objModal | void | Modify the modal output.

### Add custom modal framework

To extend modal with your own framework, you have add the following:

#### Add your own modal config

You have to register your custom modal within '$GLOBALS['TL_MODALS']'.

```
// my_module/config/config.php

/**
 * Modal types
 */
$GLOBALS['TL_MODALS']['my_custom_modal'] = array
(
	'header'   => true,
	'footer'   => true,
	'template' => 'modal_my_custom_modal',
	'link'     => array(
		'attributes' => array(
			'data-toggle' => 'modal',
		),
	),
	'js'       => array
	(
		'system/modules/my_module/assets/js/jquery.my_custom_modal.js',
	),
);
```

#### Add your modal template

The modal template contains the complete markup of your modal framework. Add as much as possible.

```
// my_module/templates/modals/modal_my_custom_modal.html5

<div class="modal fade in" tabindex="-1" role="dialog" data-back="<?= $this->back; ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php if ($this->showHeader): ?>
				<div class="modal-header">
					<?php if($this->customHeader): ?>
						<?= $this->header; ?>
					<?php elseif($this->headline): ?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<<?= $this->hl ?> class="modal-title"><?= $this->headline ?></<?= $this->hl ?>>
					<?php else :?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?= $this->title; ?></h4>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="modal-body">
				<?= $this->body; ?>
			</div>
			<?php if ($this->showFooter): ?>
				<div class="modal-footer">
					<?= $this->footer; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
```

#### Add your modal link template

The modal link template is required by inserttags (e.g {{modal_link::*}} to provide the correct trigger markup. 

```
// my_module/templates/modals/modallink_my_custom_modal.html5
<a href="<?= $this->href; ?>" title="<?= $this->linkTitle; ?>"<?= $this->target; ?><?= $this->linkAttributes; ?>><?= $this->link; ?></a>
```

#### Add your modal javascript logic

The modal windows are delivered asynchronous by the ModalController. You have to implement the toggle, close and ajax loading by your own.

```
// my_module/assets/js/jquery.my_custom_modal.js
(function ($) {

    var ModalBs3 = {
        init: function () {
            this.bindToggle();
            this.bindClose();
            $(document).ajaxComplete($.proxy(this.ajaxComplete, this));
        },
        ajaxComplete: function () {
            this.bindClose();
        },
        bindToggle: function () {
            $('[data-toggle=modal]').on('click', function () {
                var $el = $(this),
                    url = $el.attr('href');

                if (!url) {
                    return false;
                }

                $.ajax({
                    url: url,
                    dataType: 'json',
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 301) {
                            location.href = jqXHR.responseJSON.result.data.url;
                            closeModal(jqXHR.responseJSON, $form);
                            return;
                        }
                    },
                    success: function (response, textStatus, jqXHR) {

                        if (typeof response == 'undefined') {
                            return;
                        }

                        if (response.result.html && response.result.data.id) {
                            var $modal = $(response.result.html);
                            $('body').find('.modal').remove();
                            $modal.appendTo('body').modal('show');

                            if(typeof response.result.data.url !== 'undefined')
                            {
                                history.pushState(null, null, response.result.data.url);
                            }
                        }
                    }
                });

                return false;

            });
        },
        bindClose: function () {
            $('.modal').on('hide.bs.modal', function (e) {
                var $this = $(this);

                // stop embedded videos like youtube
                $this.find('iframe').each(function () {
                    var $this = $(this);

                    // reset the src will stop the video
                    $this.attr('src', $this.attr('src').replace('autoplay=1', 'autoplay=0'));
                });

                // stop embedded audio/video
                $this.find('audio, video').each(function () {
                    this.pause();
                });

                history.pushState(null, null, $this.data('back'));
                // window.location.replace($this.data('back'));
            });
        }
    }

    $(function () {
        ModalBs3.init()
    });

})(jQuery);

```