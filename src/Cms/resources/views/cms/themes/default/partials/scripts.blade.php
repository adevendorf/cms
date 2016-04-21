<script>
$(function() {
    var TEMPLATES = {
        token: '<input type="hidden" name="_token" value="%token%" />'
    };

    var Form = function() {

        var self = this;
        self.handler = function(e) {

            var _this = this;
            var asAjax = false;

            if ($(_this).find("[name='_ajax']").length) asAjax = true;

            if ($(_this).find("[name='_token']").length) {

                if (!asAjax) return;

                e.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: $(_this).attr('action'),
                    data: $(_this).serialize()
                }).done(function(data) {
                    if (data.success) {
                        $(_this).html('<h4>Thanks!</h4>')
                    }
                });

                $(_this).find("[name='_token']").remove();


            } else {

                e.preventDefault();

                $.ajax({
                    method: 'GET',
                    url: '/submit/_token'
                }).done(function (data) {
                    $(_this).append(TEMPLATES.token.replace('%token%', data.token));
                    $(_this).submit();
                });
            }

        };

        return self;

    };

    var form = new Form();

    $('.cms-form').on('submit', form.handler);
});
</script>