/*
This file is part of tippspiel24.

tippspiel24 is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
 
tippspiel24 is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with tippspiel24.  If not, see <http://www.gnu.org/licenses/>.
*/
 var betcontrols = "<div id=\"betcontrols\"> \
				<div id=\"team1_plus\" class=\"bet bet_team_plus\">+</div> \
				<div id=\"team1_minus\"class=\"bet bet_team_minus\">-</div> \
				<div class=\"bet fill_width\" /> \
				<div id=\"submitBet\" class=\"bet bet_submit\">&#10004;</div> \
				<div class=\"bet fill_width\" /> \
				<div id=\"team2_plus\" class=\"bet bet_team_plus\">+</div> \
				<div id=\"team2_minus\" class=\"bet bet_team_minus\">-</div> \
			</div>";

$.fn.scrollTo = function( target, options, callback ){
  if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
  var settings = $.extend({
    scrollTarget  : target,
    offsetTop     : 50,
    duration      : 500,
    easing        : 'swing'
  }, options);
  return this.each(function(){
    var scrollPane = $(this);
    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
    scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
      if (typeof callback == 'function') { callback.call(this); }
    });
  });
}

function start_countdown() {

	countdown();
	var timer = setInterval(countdown, 1000);

	function pad(val) {
		var valString = val + "";
		if (valString.length < 2) {
			return "0" + valString;
		}
		else {
			return valString;
		}
	}
	
	function countdown() {
		$('div.match_open').each( function() {
			var kickoff = $(this).find('#kickoff').text();
			var t = kickoff.split(/[-\\., :]/);
			var dKickoff = new Date(t[2], t[1]-1, t[0], t[3], t[4], 0);
			var diffMS = dKickoff - Date.now();
			var diffS = diffMS / 1000;    
			var diffM = diffS / 60;
			var diffH = diffM / 60;
			var diffD = diffH / 24;
			
			var ttb = $(this).find('#timetobet');

			// hilite kickoff time if no bets are placed for matches that start in less than 3h
			if (diffH < 3) {
				var bets = $(this).find('.goal');

				var b1 = bets.filter(':first-child');
				var b2 = bets.filter(':last-child');

				var betplaced = (b1.attr('value') != '--' && b2.attr('value') != '--');
				
				if (betplaced && ttb.hasClass('red')) {
					ttb.removeClass('red');
				}
				if (!betplaced && !ttb.hasClass('red')) {
					ttb.addClass('red');
				}
			}

			if (diffMS < 0 ) {
				$(this).removeClass('match_open').addClass('match_closed');
				$(this).find('#betcontrols').remove();
				ttb.text(' ');
				// reset changed values to defaults
				$(this).find('.goal').each( function() {
					$(this).text($(this).attr('value'));
				});
			}
			else {
				// set count down text
				ttb.text( 'Anpfiff in ' + 
					pad(parseInt(diffD     )) + 'd ' + 
					pad(parseInt(diffH % 24)) + 'h ' + 
					pad(parseInt(diffM % 60)) + 'm ' + 
					pad(parseInt(diffS % 60)) + 's ' );
			}
		});
	}
}

function handleButtonState()
{
	var btnSubmit = $("div#submitBet");
	var matchDiv = btnSubmit.parent().parent();
	var bets = matchDiv.find('.goal');

	var b1 = bets.filter(':first-child');
	var b2 = bets.filter(':last-child');

	var disabled = (b1.text() == b1.attr('value') && b2.text() == b2.attr('value')) || b1.text() == '--' || b2.text() == '--';
	btnSubmit.prop('disabled', disabled);
	btnSubmit.css('opacity', (disabled ? '0' : '1'));
}

$(document).ready(function(){
	start_countdown();

	var loadingImage = $('<img />').attr('src', '../img/ajax-loader.gif').appendTo('body').hide();

	// scroll to next starting match unless there are open bets
	console.log(hasOpenBonusBets);
	if (!hasOpenBonusBets) {
		var pane = null;
		var offset = 0;
		if (navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {
			pane = $('body');
			offset = 230;
		}
		else {
			pane = $('html');
			offset = 90;
		}

		var top = $('div#match.match_open:first').offset().top - pane.offset().top - offset;
		pane.scrollTo(top);
	}

	$("div#match.match_open").on("click", function() {
		var bc = $('body').find('#betcontrols');
		var currentlyOpen = bc.parent();

		// same match? --> skip
		if (currentlyOpen.attr('value') == $(this).attr('value')) return false;

		// reset changed values to defaults
		currentlyOpen.find('.goal').each( function() {
			$(this).text($(this).attr('value'));
		});

		// remove bet controls	
		bc.remove();

		// add new bet controls
		$(this).append(betcontrols);
		handleButtonState();

		return false;
	});

	function increaseScore(el, first) {
	  var child = el.parent().parent().find('.goal:' + (first ? 'first' : 'last') + '-child');
  
	  var cont = child.text();
	  var team_goal = 0;
  
	  if ("--" == cont)
	  {
	     team_goal = 0;
	  }
	  else
	  {
	     team_goal = parseInt(cont);
	     ++team_goal;
	  }
  
	  child.text(team_goal.toString());
	  handleButtonState();
	}

	function decreaseScore(el, first) {
	  var child = el.parent().parent().find('.goal:' + (first ? 'first' : 'last') + '-child');
  
	  var cont = child.text();
	  var team_goal = 0;
  
	  if ("--" == cont)
	  {
	     team_goal = 0;
	  }
	  else
	  {
	     team_goal = parseInt(cont);
	     --team_goal;
	     if (team_goal < 0)
	     {
	       team_goal = 0;
	     }
	  }
  
	  child.text(team_goal.toString());
	  handleButtonState();
	}

	$("div#match").on("click", "div#team1_plus", function(e) {
		increaseScore($(this), true);
		return false;
	});

	$("div#match").on("click", "div#team1_minus", function(e) {
		decreaseScore($(this), true);
		return false;
	});

	$("div#match").on("click", "div#submitBet", function(e) {
		var btnSubmit = $("div#submitBet");

		if (btnSubmit.prop('disabled')) return false;

		btnSubmit.prop('disabled', true);
		var matchDiv = btnSubmit.parent().parent();
		var matchid = matchDiv.attr('value');
		var bets = matchDiv.find('.goal');
		var b1 = bets.filter(':first-child').text();
		var b2 = bets.filter(':last-child').text();

		btnSubmit.text("");
		btnSubmit.append(loadingImage.show());

		$.post('submitBet.php', { match: matchid, bet1: b1, bet2: b2 }, function(data) {
			// TODO: ergebnis zurücklesen
			// reset defaults to changed values
			bets.each( function() {
				$(this).attr('value', $(this).text());
			});
			$( "#betcontrols" ).remove();

		}).fail(function() {
			// just in case posting your form failed
			alert( "Posting failed." );
		});
		return false;
	});

	$("div#match").on("click", "div#team2_plus", function(e) {
		increaseScore($(this), false);
		return false;
	});

	$("div#match").on("click", "div#team2_minus", function(e) {
		decreaseScore($(this), false);
		return false;
	});
});