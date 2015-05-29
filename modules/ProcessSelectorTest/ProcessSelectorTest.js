/**
 * ProcessSelectorTest.js
 *
 * @copyright Copyright (c) 2012, Niklas Lakanen
 *
 */
$(function() {
	// apply jqTree to all elements with a certain class
	$('.selectortest-tree').tree({
		// no data given here (initialized a little later)
		// explicit definition is needed to prevent immediate ajax request for the first node
		data: [],

		// base url for loading nodes on-demand
		dataUrl: './load',

		// apply some formatting depending on node properties
		onCreateLi: function(node, $li) {
			var $title = $li.find('.jqtree-title');
			// construct title from key/value if label hasn't been defined (now in node.name)
			if(!node.name) {
				var $valueSpan = $('<span></span>').addClass('value').text(node.value);
				if(node.valueClass) {
					$valueSpan.addClass(node.valueClass);
				}
				if(node.key) {
					$title.html(node.key + ':').append($valueSpan);
				} else {
					$title.html($valueSpan);
				}
			}

			// set title-attribute for title-element if a tooltip is defined
			if(node.tooltip) {
				$title.attr('title', node.tooltip);
			}

			// use page's status values for title-element's class and title
			if(node.status && node.status.length) {
				$title.attr('title', node.status.join(', '));
				$title.addClass(node.status.join(' '));
			}

			// add a span with action links after title-element if there are actions defined
			if(node.actions) {
				var $actionsSpan = $('<span></span>').addClass('actions');
				$.each(node.actions, function(label, url) {
					$actionsSpan.append(' | <a href="' + url + '">' + label + '</a>');
				});
				$title.after($actionsSpan);
			}

			// add a class for title-element if there's one defined
			if(node.class) {
				$title.addClass(node.class);
			}
		}
	})
	// make clicking a node act as a toggle
	.bind('tree.click', function(event) {
		$(this).tree('toggle', event.node);
	});
});
