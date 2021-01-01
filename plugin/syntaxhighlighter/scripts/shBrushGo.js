// http://dkpfiles.com/dkp-extras/sh/shBrushLua.js

SyntaxHighlighter.brushes.Lua = function()
{
  var keywords =  'break        default      func         interface    select case         defer        go           map          struct chan         else         goto         package      switch const        fallthrough  if           range        type continue     for          import       return       var';
  var funcs = 'print println';

  this.regexList = [
			{ regex: SyntaxHighlighter.regexLib.singleLineCComments,	css: 'comments' },			// one line comments
			{ regex: SyntaxHighlighter.regexLib.multiLineCComments,		css: 'comments' },			// multiline comments
      { regex: SyntaxHighlighter.regexLib.doubleQuotedString,     css: 'string' },    // strings
        { regex: SyntaxHighlighter.regexLib.singleQuotedString,     css: 'string' },    // strings
        { regex: new RegExp(this.getKeywords(keywords), 'gm'),      css: 'keyword' },   // keyword
        { regex: new RegExp(this.getKeywords(funcs), 'gm'),         css: 'func' },      // functions
        ];
}

SyntaxHighlighter.brushes.Lua.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Lua.aliases = ['go'];


