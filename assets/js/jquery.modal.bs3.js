(function($) {

    var ModalBs3 = {
        init: function() {
            this.bindToggle();
            this.bindClose();
            this.bindPopState();
            $(document).ajaxComplete($.proxy(this.ajaxComplete, this));
        },
        ajaxComplete: function() {
            this.bindClose(true);
        },
        bindToggle: function() {
            var self = this;
            $('body').on('click', '[data-toggle=modal]', function() {
                var $el = $(this),
                    url = $el.attr('href');

                var context = HASTE_PLUS.getParameterByName('ag', url);

                // calendar_plus, news_plus deprecated old modal window behavior, will be removed in future
                if ($el.data('event') == 'modal' || $el.data('news') == 'modal' || $el.data('job') == 'modal') {
                    return;
                }

                // support xlink:href within svg
                if (typeof url == 'undefined') {
                    url = $el.attr('xlink:href');
                }

                // redirect non ajax links
                if (context != 'modal') {
                    window.location.href = url;
                    return false;
                }

                if (typeof url == 'undefined') {
                    return false;
                }

                $.ajax({
                    url: url,
                    data: {location: window.location.href},
                    dataType: 'json',
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 300) {
                            location.href = jqXHR.responseJSON.result.data.url;
                            closeModal(jqXHR.responseJSON, $form);
                            return;
                        }
                    },
                    success: function(response, textStatus, jqXHR) {

                        if (typeof response == 'undefined') {
                            return;
                        }

                        if (response.result.html && response.result.data.id) {
                            var $html = $(response.result.html);
                            $('body').find('.bs-modal').remove();
                            $html.appendTo('body');
                            var $modal = $('.bs-modal');
                            $modal.modal('show');
                            if (typeof response.result.data.url !== 'undefined') {
                                if (window.history && window.history.pushState) {
                                    history.pushState({}, null, response.result.data.url);
                                }
                            }
                        }
                    }
                });

                return false;

            });
        },
        bindClose: function(isAjax) {
            $('.bs-modal').on('hide.bs.modal', function(e) {
                var $this = $(this);

                // stop embedded videos like youtube
                $this.find('iframe').each(function() {
                    var $this = $(this);

                    // reset the src will stop the video
                    $this.attr('src', $this.attr('src').replace('autoplay=1', 'autoplay=0'));
                });

                // stop embedded audio/video
                $this.find('audio, video').each(function() {
                    this.pause();
                });

                if (window.history && window.history.pushState) {
                    history.pushState({}, null, $this.data('back'));
                }
            });
        },
        bindPopState: function() {
            // If a pushstate has previously happened and the back button is clicked, hide any modals.
            $(window).on('popstate', function() {
                $('.bs-modal').modal('hide');
            });
        }
    };

    $(function() {
        ModalBs3.init();
    });

})(jQuery);