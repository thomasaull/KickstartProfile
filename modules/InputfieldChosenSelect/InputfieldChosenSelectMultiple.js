(function($) {
	// From Github:
	// https://github.com/mrhenry/jquery-chosen-sortable

  $.fn.chosenOrder = function() {
    var $this   = this.first(),
        $chosen = $this.siblings('.chosen-container');

    return $($chosen.find('.chosen-choices li[class!="search-field"]').map( function() {
      if (!this) {
        return undefined;
      }
      return $this.find('option:contains(' + $(this).text() + ')')[0];
    }));
  };

  $.fn.chosenSortable = function(){
    var $this = this;

    $this.each(function(){
      var $select = $(this);
      var $chosen = $select.siblings('.chosen-container');

      $chosen.find('.chosen-choices').bind('mousedown', function(event){
        if ($(event.target).is('span')) {
          event.stopPropagation();
        }
      });

      $chosen.find('.chosen-choices').sortable({
        'placeholder' : 'ui-state-highlight chosen-ghost',
        'items'       : 'li:not(.search-field)',
        //'update'      : _update,
        'tolerance'   : 'pointer'
      });

      $select.closest('form').one('submit.sortable', function(){
        var $options = $select.chosenOrder();
        $options.detach().appendTo($select);
      });

    });

    return this;
  };

  // Updated version of this:
  // http://stackoverflow.com/questions/7385246/allow-new-values-with-chosen-js-multiple-select/12961228#12961228

  $.fn.chosenAddable = function(){
  	var $select = this,
  			$chosen = $select.siblings('.chosen-container');

    function moveNewPagesToInputfield(){
      $(this).off("submit.addable");
      var options = $select.children("[rel='add']").detach().map(function () { 
        return $(this).text(); 
      }).get();

      if(options.length){
        // fieldname, remove "Inputfield_" in front of id
        fieldName = $select.attr("id").substring(11);
        $textarea = $('#_'+fieldName+'_add_items').val(options.join("\n"));
      }
    }

    function trackNoResultEntries(event){
      var stroke, _ref, target, list;
      // get keycode
      stroke = (_ref = event.which) != null ? _ref : event.keyCode;
      target = $(event.target);

      if (stroke === 9 || stroke === 13 ) {
        var value;
        // Get current Tags
        list = $select.data('chosen').results_data.map(function(ele){
          if(ele.text !== undefined) text = ele.text.toLowerCase();
          else text = "";
          return text;
        });

        value = $.trim(target.val());

        if(list.indexOf(value.toLowerCase()) === -1) {
          event.stopImmediatePropagation();
          event.stopPropagation();
          event.preventDefault();

          $('<option>')
            .text(value).val(value)
            .attr('selected','selected')
            .attr("rel", "add")
            .appendTo($select);

          setTimeout(function(){ $select.trigger('chosen:updated')}, 10);
          return false;
        }
      }

      $select.closest('form').one('submit.addable', moveNewPagesToInputfield);

      return this;
    }

    $chosen.on("keyup cut paste focus", trackNoResultEntries);
  	
  }

}(jQuery));

$(document).ready(function() {
	$(".InputfieldChosenSelectMultiple select[multiple=multiple]").each(function() {
		var $t = $(this),
        $addable = $t.closest(".InputfieldChosenSelectMultiple").find(".InputfieldPageAdd"); 

		if(typeof config === 'undefined') {
			var options = { sortable: true };
		} else {
			var options = config[$t.attr('id')]; 
		}

    if($addable.length){
      options["no_results_text"] = options["no_results_text_addable"];
      $t.chosen(options).chosenSortable().chosenAddable();

      if(!$t.data('chosen')){
        $addable.css("display", "block");
      }
    }else{
      $t.chosen(options).chosenSortable();
    }
	}); 
}); 
