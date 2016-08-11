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