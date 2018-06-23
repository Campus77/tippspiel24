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


 var betcontrols = '<div id="betcontrols"> \
				<div class="bcplusminus"> \
					<button id="team1_plus" class="bet">+</button> \
					<button id="team1_minus"class="bet">-</button> \
				</div> \
				<div class="bcsubmit"> \
					<button id="submitBet" class="bet">&#10004;</button> \
				</div> \
				<div class="bcplusminus"> \
					<button id="team2_plus" class="bet">+</button> \
					<button id="team2_minus" class="bet">-</button> \
				</div> \
				<br clear="all"/> \
			</div>';

var FADE_OUT_TIME = 150;
var FADE_IN_TIME = 250;

function updateTargetView(currentPos)
{
	// remove any open bet controls
	removeBetControls();
	var currentDay = $('#innercontainer .matchday')[currentPos].innerHTML;
	$('.match').each(function() {
		var matchDay = $(this).data('matchday');
		if (matchDay == currentDay) {
			$(this).css('display', 'block');
		}
		else {
			$(this).css('display', 'none');
		}
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

			// highlight kickoff time if no bets are placed for matches that start in less than 3h
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
				$(this).off('click');
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

function handleButtonState(matchDiv) {
	var bets = matchDiv.find('.goal');
	var btnSubmit = matchDiv.find('#submitBet');

	var b1 = bets.filter(':first-child');
	var b2 = bets.filter(':last-child');

	var disabled = (b1.text() == b1.attr('value') && b2.text() == b2.attr('value')) || b1.text() == '--' || b2.text() == '--';
	btnSubmit.prop('disabled', disabled);
	btnSubmit.css('opacity', (disabled ? '0' : '1'));
	btnSubmit.css('cursor', (disabled ? 'auto' : 'pointer'));
}

function removeBetControls() {
	var bc = $("#betcontrols");
	// reset any changed values to defaults
	var parent = bc.parent();
	parent.find('.goal').each( function() {
		$(this).text($(this).attr('value'));
	});
	// reset hightlighting style of match div
	parent.removeClass('match_selected');
	// hide and remove bet control
	bc.hide(FADE_OUT_TIME, function() {
		$(this).remove();
	});
}

function createBetControls(parent) {
	$(betcontrols).hide().appendTo(parent).show(FADE_IN_TIME);
	handleButtonState(parent);
}

$(document).ready(function() {
	start_countdown();

	var loadingImage = $('<img />').attr('src', '../img/ajax-loader.gif').appendTo('body').hide();

	$("div.match_open").on("click", function() {
		var bc = $('#betcontrols');

		if (bc.length > 0)
		{
			var currentlyOpen = bc.parent();

			// same match? --> skip
			if (currentlyOpen.attr('value') == $(this).attr('value')) return false;

			// remove and create new bet controls
			removeBetControls();
			setTimeout(createBetControls, FADE_OUT_TIME, $(this));
		}
		else {
			createBetControls($(this));
		}

		$(this).addClass('match_selected');
		return false;
	});

	function increaseScore(el, first) {
	  var matchDiv = el.parents('div.match');
	  var child = matchDiv.find('.goal:' + (first ? 'first' : 'last') + '-child');
  
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
	  handleButtonState(matchDiv);
	}

	function decreaseScore(el, first) {
	  var matchDiv = el.parents('div.match');
	  var child = matchDiv.find('.goal:' + (first ? 'first' : 'last') + '-child');
  
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
	  handleButtonState(matchDiv);
	}

	$("div#match").on("click", "#team1_plus", function(e) {
		increaseScore($(this), true);
		return false;
	});

	$("div#match").on("click", "#team1_minus", function(e) {
		decreaseScore($(this), true);
		return false;
	});

	$("div#match").on("click", "#submitBet", function(e) {
		var btnSubmit = $('#submitBet');

		if (btnSubmit.prop('disabled')) return false;

		btnSubmit.prop('disabled', true);
		var matchDiv = btnSubmit.parents('div.match');
		var matchid = matchDiv.attr('value');
		var bets = matchDiv.find('.goal');
		var b1 = bets.filter(':first-child').text();
		var b2 = bets.filter(':last-child').text();

		btnSubmit.text("");
		btnSubmit.append(loadingImage.show());

		$.post('submitBet.php', { match: matchid, bet1: b1, bet2: b2 }, function(data) {
			// TODO: ergebnis zur�cklesen
			// reset defaults to changed values
			bets.each( function() {
				$(this).attr('value', $(this).text());
			});
			removeBetControls();

		}).fail(function() {
			// just in case posting your form failed
			alert( "Posting failed." );
		});
		return false;
	});

	$("div#match").on("click", "#team2_plus", function(e) {
		increaseScore($(this), false);
		return false;
	});

	$("div#match").on("click", "#team2_minus", function(e) {
		decreaseScore($(this), false);
		return false;
	});
});