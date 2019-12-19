if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
	RedactorPlugins.fontfamily = function()
	{
		return {
			init: function ()
			{
				var fonts = [ 'Arial', 'Helvetica', 'Georgia', 'Times New Roman', 'Monospace','Verdana',' Tahoma','Century Gotic'];
				var that = this;
				var dropdown = {};

				$.each(fonts, function(i, s)
				{
					dropdown['s' + i] = { title: s, func: function() { that.fontfamily.set(s); }};
				});

				dropdown.remove = { title: '׃האכטעü רנטפע', func: that.fontfamily.reset };

				var button = this.button.add('fontfamily', 'ָחלוםטעü רנטפע');
				this.button.addDropdown(button, dropdown);

			},
			set: function (value)
			{
				this.inline.format('span', 'style', 'font-family:' + value + ';');
			},
			reset: function()
			{
				this.inline.removeStyleRule('font-family');
			}
		};
	};
})(jQuery);