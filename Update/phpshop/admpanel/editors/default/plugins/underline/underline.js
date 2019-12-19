if (!RedactorPlugins) var RedactorPlugins = {};


(function($)
{
    RedactorPlugins.underline = function()
    {
        return {
            init: function()
            {
                var button = this.button.addAfter('italic', 'underline', 'Подчеркнутый');
                this.button.addCallback(button, this.underline.format);
            },
            format: function()
            {
                this.inline.format('u');
            }
        };
    };
})(jQuery);