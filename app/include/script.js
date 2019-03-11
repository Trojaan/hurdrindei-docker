window.addEvent('domready', function(){
	
	// The same as before: adding events
	$('parent').addEvents({
  
		'mouseenter': function(){

      children = this.getChildren();
      cnt = 0;
      rh = 25;

      children.each(function(){cnt++}
      );

      console.log(cnt * rh);

			// Always sets the duration of the tween to 1000 ms and a bouncing transition
			// And then tweens the height of the element
			this.set('tween', {
				duration: 1000,
				transition: Fx.Transitions.Bounce.easeOut // This could have been also 'bounce:out'
			}).tween('height', (cnt * rh) + 'px');
		},
		'mouseleave': function(){
			// Resets the tween and changes the element back to its original size
			this.set('tween', {}).tween('height', '22px');
		}
	});
});


