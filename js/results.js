function updateTargetView(currentPos) {
	var html = [];
	for (row in bet_data[currentPos]) {
		var b = bet_data[currentPos][row];
		html.push('<tr class="ranking_' + b[0] + (b[1] ? ' self' : '') + '">' +
				'<td>' + b[2] + '</td>' +
				'<td>' + b[3] + ':' + b[4] + '</td>' +
				'<td>' + b[5] + '</td></tr>');
	}
	$("#user_ranking > tbody").html(html.join(''));
}
