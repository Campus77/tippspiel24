function Format(val) {
	return (val == null ? '---' : val);
}

function FormatFlag(bet) {
	if (bet == null) return '---';
	subBets = bet.split(';');
	ret = '';
	for (i in subBets) {
		if (subBets[i] == '') {
			ret += '<span class="info">---</span>';
		}
		else {
			ret += '<span class="info"><div class="flag flag-' + flags[subBets[i]] + '" title="' + subBets[i] + '"/></span>';
		}
	}
	return ret;
}

function SumSubPoints(points) {
	if (points == null) return 0;
	subPoints = points.split(';');
	sum = 0;
	for (i in subPoints) {
		sum += parseInt(subPoints[i]);
	}
	return sum;
}

function updateTargetView(idx) {
	var elQuestion = $('#question');
	var elResult = $('#result');
	var elTable = $('#user_ranking > tbody');
	elTable.empty();
	elQuestion.html(bonusresults[idx].question);
	switch (bonusresults[idx].type) {
		case 'TEAM':
		case 'TEAM2':
		case 'TEAM4':
		case 'TEAMGROUP':
			result = FormatFlag(bonusresults[idx].result);
			break;
		default:
			result = Format(bonusresults[idx].result);
	}
	elResult.html(result);
	for (i in bonusresults[idx].bets) {
		e = bonusresults[idx].bets[i];
		switch (bonusresults[idx].type) {
			case 'TEAM':
			case 'TEAM2':
			case 'TEAM4':
			case 'TEAMGROUP':
				bet = FormatFlag(e.bet);
				points = Format(SumSubPoints(e.points));
				break;
			default:
				bet = Format(e.bet);
				points = Format(e.points);
		}
		elTable.append('<tr><td>' + e.user + '</td><td>' + bet + '</td><td>' + points + '</td>');
	}
}
