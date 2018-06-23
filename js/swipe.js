$(document).ready(function()
{
	var outerContainer = $("#outercontainer");
	var innerContainer = $("#innercontainer");

	var ARROW_OPACITY_NORMAL = 0.1;
	var ARROW_OPACITY_HOVER = 0.3;
	var SCROLL_TIME = 150;
	var FADE_TIME = 150;
	var SWIPE_TIMEOUT_TIME = 1000;
	var startX = null;
	var swipeTimeoutId = null;
	var lastSwipeEvent = null;
	
	var currentPos = initPos;
	var maxPos = innerContainer.children().length;

	// init stuff
	updateItemWidth();
	updateSwipeView();
	updateTargetView(currentPos);
	
	function clearSwipeTimeout() {
		window.clearTimeout(swipeTimeoutId);
	}
	
	function resetSwipeTimeout() {
		clearSwipeTimeout();
		swipeTimeoutId = window.setTimeout(evaluateSwipe, SWIPE_TIMEOUT_TIME);
	}

	function evaluateSwipe() {
		if (swipeTimeoutId !== null) {
			if (lastSwipeEvent['gesture']['deltaX'] < 0)
			{
				tryMoveRight();
			}
			else
			{
				tryMoveLeft();
			}
			startX = null;
			clearSwipeTimeout();
			swipeTimeoutId = null;
		}
	}
	
	// update all inner result items based on outer container width
	function updateItemWidth()
	{
		var width = parseInt(outerContainer.css('width').replace(/[^-\d\.]/g, ''));
		innerContainer.children().css('width', "" + width + "px");
		itemWidth = width;
	}
	
	$(window).resize(function() {
		updateItemWidth();
		updateSwipeView();
	});
		
	function updateSwipeView()
	{
		// scroll to current position
		var newPos = -currentPos * itemWidth;
		innerContainer.animate({
			left: "" + newPos + "px"
		}, SCROLL_TIME);
		// set arrow states and opacity
		$(".arrow_left").animate({
			opacity: canMoveLeft() ? ARROW_OPACITY_NORMAL : 0
		}, FADE_TIME);
		$(".arrow_right").animate({
			opacity: canMoveRight() ? ARROW_OPACITY_NORMAL : 0
		}, FADE_TIME);
	}

	function canMoveLeft() {
		return (currentPos > 0);
	}

	function canMoveRight() {
		return (currentPos < maxPos - 1);
	}
	
	function tryMoveLeft() {
		if (canMoveLeft())
		{
			currentPos--;
			updateTargetView(currentPos);
		}
		updateSwipeView();
	}

	function tryMoveRight() {
		if (canMoveRight())
		{
			currentPos++;
			updateTargetView(currentPos);
		}
		updateSwipeView();
	}
	
	function resetToInitialPos() {
		currentPos = initPos;
		updateTargetView(currentPos);
		updateSwipeView();
	}
	
	outerContainer.hammer()
	.bind("panstart", function(ev) {
		startX = parseInt(innerContainer.css('left').replace(/[^-\d\.]/g, ''));
		$(".arrow").animate({
			opacity: 0
		}, 100);
		resetSwipeTimeout();
	})
	.bind("panend", function(ev) {
		lastSwipeEvent = ev;
		if (startX !== null)
		{
			evaluateSwipe();
		}
	})
	.bind("panleft panright", function(ev) {
		lastSwipeEvent = ev;
		resetSwipeTimeout();
		var deltaX = ev['gesture']['deltaX'];
		if (startX !== null)
		{
			// check if panning is impossible => add rubber band effect
			if ((deltaX > 0 && !canMoveLeft()) || (deltaX < 0 && !canMoveRight()))
			{
				deltaX = (deltaX < 0 ? -1 : 1) * 30 * (1 - Math.exp(-0.01 * Math.abs(deltaX)));
			}
			var newPos = startX + deltaX;
			innerContainer.css('left', "" + newPos + "px");
		}
	})
	.bind("press", function(ev) {
		resetToInitialPos();
	});
	
	$(".arrow")
	.on("mouseover", function() {
		if ($(this).hasClass("arrow_left") && canMoveLeft()
			|| $(this).hasClass("arrow_right") && canMoveRight()) {
			$(this).animate({
				opacity: ARROW_OPACITY_HOVER
			}, FADE_TIME);			
		}
	})
	.on("mouseout", function() {
		if ($(this).hasClass("arrow_left") && canMoveLeft()
			|| $(this).hasClass("arrow_right") && canMoveRight()) {
			$(this).animate({
				opacity: ARROW_OPACITY_NORMAL
			}, FADE_TIME);
			}
	})
	.hammer().bind("tap", function(ev) {
		if ($(ev.target).hasClass("arrow_left")) {
			tryMoveLeft();
		}
		else {
			tryMoveRight();
		}
	});
	
	$(window).keydown(function(ev) {
		var keyCode = ev.which;
		// left: move left
		if (37 == keyCode) {
			tryMoveLeft();
		}
		// up: go to initial result
		if (38 == keyCode) {
			resetToInitialPos();
		}
		// right: move right
		else if (39 == keyCode) {
			tryMoveRight();
		}
	});
});
