function LoadButtonClick(Obj)
{
	Obj.innerHTML = '<img src="resource/icon/loading.gif"/>';
	Obj.className = 'loadbutton_clicked'; 
}

function ifLoadButtonClicked(Obj)
{
	return (Obj.className == 'loadbutton_clicked');
}

function ResetLoadButton(Obj, label)
{
	Obj.innerHTML = '<a href="javascript:;">' + label + '</a>';
	Obj.className = 'load_button';
}

function DisableLoadButton(Obj, label)
{
	Obj.className = 'loadbutton_clicked';
	Obj.innerHTML = '<span>' + label + '</span>';
}
