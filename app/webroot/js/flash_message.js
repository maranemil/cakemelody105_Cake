function highlight_message()
{
	var id="flashMessage";
	if (document.getElementById(id)) {
		var elemento=document.getElementById(id);
		if (elemento.innerHTML.length>0)
			{
				new Effect.Highlight(id, {startcolor:'#FADB9F', duration: 0.5} );
				/*new Insertion.Bottom(id, '<p>Click anywhere to close</p>');*/
			}
	}
	Event.stopObserving(window, 'load', highlight_message);
}

function close_highlight_message()
{
	var id="flashMessage";
	if (document.getElementById(id)) {
		var elemento=document.getElementById(id);
		if (elemento.innerHTML.length>0)
			{
				new Effect.Fade(id);
			}
	}
	Event.stopObserving(window, 'load', highlight_message);
}

Event.observe(window, 'load', highlight_message, false);
Event.observe(window, 'click', close_highlight_message, false);