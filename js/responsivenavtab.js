$.fn.responsiveTabs = function() {
  this.addClass('responsive-tabs');
  this.append($('<span class="glyphicon glyphicon-triangle-bottom"></span>'));
  this.append($('<span class="glyphicon glyphicon-triangle-top"></span>'));

  this.on('click', 'li.active > a, span.glyphicon', function() {
    this.toggleClass('open');
  }.bind(this));

  this.on('click', 'li:not(.active) > a', function() {
    this.removeClass('open');
  }.bind(this));
};