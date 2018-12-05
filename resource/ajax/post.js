var hXMLHTTP = GetXMLHTTPHandler();

function GetXMLHTTPHandler() 
{
	var res = false;

	try 
	{
		/* for Firefox */
		res = new XMLHttpRequest();
	}catch (err) 
	{
		try 
		{
			/*new versions of IE */
			res = new ActiveXObject("Msxml2.XMLHTTP");
		}catch (err) 
		{
			try 
			{
				/*old versions of IE */
				res = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (err) 
			{
				res = false;
			}
		}
	}

	return res;
}

function AjaxPost( type , message , callBkFun , callBkPa )
{
	var thePage = 'resource/ajax/response.php';
        randSuffix = parseInt(Math.random()*99999);
        var theURL = thePage +"?rand=" + randSuffix;

	hXMLHTTP.open("POST", theURL, true);
	hXMLHTTP.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	hXMLHTTP.onreadystatechange = function(){callBkFun(callBkPa)};

	//get + and & translated!
	message = message.replace(/\+/g, "%2B");
	message = message.replace(/\&/g, "%26");
	hXMLHTTP.send('type=' + type + '&message=' + message);
}

function ifResponseComplete()
{
	return (hXMLHTTP.readyState == 4 && hXMLHTTP.status == 200);
}

function showResponseXML()
{
	alert(hXMLHTTP.responseText);
}

function getResponseTagArray( tagName )
{
	return hXMLHTTP.responseXML.getElementsByTagName(tagName);
}

function getTagArraySubTagValue( tagArray , subTagName )
{
	var xmlNode = tagArray.getElementsByTagName(subTagName)[0];

	//this approach is due to the 'Firefox 4k XML node limit'
	if(typeof(xmlNode.textContent) != "undefined") 
		return xmlNode.textContent;

	return xmlNode.childNodes[0].nodeValue;
    //or return xmlNode.firstChild.nodeValue;
}
