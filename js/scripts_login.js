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
$(document).ready(function() {
	//$( "section#content" ).load( "ajax/div_login.html" );
});

$('body').addClass('js');

$( "body#login_body" ).on( "click", "a#register", function() {
	$( "section#content" ).load( "../ajax/div_register.html" );
});

$( "body#login_body" ).on( "click", "img#menu-icon", function(event) {
	
	//$( "section#content" ).load( "../ajax/div_login.html" );
});