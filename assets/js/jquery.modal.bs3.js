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
                    url: url
                }).done(function (data) {
                    var $modal = $(data);
                    $('body').find('.modal').remove();
                    $modal.appendTo('body').modal('show');
                    history.pushState(null, null, url);
                })
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
            });
        }
    }

    $(function () {
        ModalBs3.init()
    });

})(jQuery);