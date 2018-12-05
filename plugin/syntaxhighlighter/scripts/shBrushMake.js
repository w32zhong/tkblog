SyntaxHighlighter.brushes.Make = function()
{
var funcs = '.default .delete_on_error .export_all_variables .ignore .intermediate .keep_state .libpatterns .notparallel .phony .posix .precious .secondary .silent .suffixes';
var keywords = 'addprefix basename call clean all dir else endif error eval export filter filter-out findstring firstword foreach if ifeq ifneq ifdef ifndef info include join notdir origin override patsubst shell sort strip subst suffix unexport vpath warning wildcard word wordlist words';

this.regexList = [
{ regex: /#(.*)$/gm,                                      css: 'comments' },
{ regex: SyntaxHighlighter.regexLib.singleQuotedString,   css: 'string' },
{ regex: SyntaxHighlighter.regexLib.doubleQuotedString,   css: 'string' },
{ regex: /\$\((.*)\)/gm,                                  css: 'color1' },
{ regex: new RegExp(this.getKeywords(funcs), 'gmi'),      css: 'color2' },
{ regex: new RegExp(this.getKeywords(keywords), 'gmi'),   css: 'keyword' },
{ regex: /\$[@^]/g,                                       css: 'color1' }
];
};
SyntaxHighlighter.brushes.Make.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Make.aliases = ['make'];
